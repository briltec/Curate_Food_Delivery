<?php

namespace App\CentralLogics;

use App\Models\Food;
use App\Models\User;
use App\Models\Admin;
use App\Models\Order;
use App\Models\Incentive;
use App\Models\AdminWallet;
use Illuminate\Support\Str;
use App\Models\IncentiveLog;
use App\Models\BusinessSetting;
use App\Models\OrderTransaction;
use App\Models\RestaurantWallet;
use App\Models\DeliveryManWallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Subscription;
use App\Models\SubscriptionLog;
use Exception;

class OrderLogic
{
    public static function gen_unique_id()
    {
        return rand(1000, 9999) . '-' . Str::random(5) . '-' . time();
    }

    public static function track_order($order_id)
    {
        return Helpers::order_data_formatting(Order::with(['details', 'delivery_man.rating'])->where(['id' => $order_id])->first(), false);
    }

    public static function place_order($customer_id, $email, $customer_info, $cart, $payment_method, $discount, $coupon_code = null)
    {
        try {
            $or = [
                'id' => 100000 + Order::all()->count() + 1,
                'user_id' => $customer_id,
                'order_amount' => CartManager::cart_grand_total($cart) - $discount,
                'payment_status' => 'unpaid',
                'order_status' => 'pending',
                'payment_method' => $payment_method,
                'transaction_ref' => null,
                'discount_amount' => $discount,
                'coupon_code' => $coupon_code,
                'discount_type' => $discount == 0 ? null : 'coupon_discount',
                'shipping_address' => $customer_info['address_id'],
                'created_at' => now(),
                'updated_at' => now()
            ];

            $o_id = DB::table('orders')->insertGetId($or);

            foreach ($cart as $c) {
                $product = Food::where('id', $c['id'])->first();
                $or_d = [
                    'order_id' => $o_id,
                    'food_id' => $c['id'],
                    'seller_id' => $product->added_by == 'seller' ? $product->user_id : '0',
                    'product_details' => $product,
                    'qty' => $c['quantity'],
                    'price' => $c['price'],
                    'tax' => $c['tax'] * $c['quantity'],
                    'discount' => $c['discount'] * $c['quantity'],
                    'discount_type' => 'discount_on_product',
                    'variant' => $c['variant'],
                    'variation' => json_encode($c['variations']),
                    'delivery_status' => 'pending',
                    'shipping_method_id' => $c['shipping_method_id'],
                    'payment_status' => 'unpaid',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                DB::table('order_details')->insert($or_d);
            }
            if(config('mail.status'))
            {
                Mail::to($email)->send(new \App\Mail\OrderPlaced($o_id));
            }

        } catch (\Exception $e) {

        }

        return $o_id;
    }

    public static function updated_order_calculation($order)
    {
        return true;
    }

    //here
    public static function create_transaction($order, $received_by=false, $status = null)
    {
        $comission = !isset($order->restaurant->comission)?\App\Models\BusinessSetting::where('key','admin_commission')->first()->value:$order->restaurant->comission;

        $admin_subsidy = 0;
        $amount_admin = 0;
        $restaurant_d_amount = 0;
        $admin_coupon_discount_subsidy =0;
        $restaurant_subsidy =0;
        $restaurant_coupon_discount_subsidy =0;
        $restaurant_discount_amount=0;
        $restaurant= $order->restaurant;
        $rest_sub = $restaurant->restaurant_sub;

        // free delivery by admin
        if($order->free_delivery_by == 'admin')
        {
            $admin_subsidy = $order->original_delivery_charge;
            Helpers::expenseCreate($order->original_delivery_charge,'free_delivery',now(),$order->id,$order->free_delivery_by);
        }
        // free delivery by restaurant
        if($order->free_delivery_by == 'vendor')
        {
            $restaurant_subsidy = $order->original_delivery_charge;
            Helpers::expenseCreate($order->original_delivery_charge,'free_delivery',now(),$order->id,$order->free_delivery_by,$order->restaurant->id);
        }
        // coupon discount by Admin
        if($order->coupon_created_by == 'admin')
        {
            $admin_coupon_discount_subsidy = $order->coupon_discount_amount;
            Helpers::expenseCreate($admin_coupon_discount_subsidy,'coupon_discount',now(),$order->id,$order->coupon_created_by);
        }
        // coupon discount by restaurant
        if($order->coupon_created_by == 'vendor')
        {
            $restaurant_coupon_discount_subsidy = $order->coupon_discount_amount;
            Helpers::expenseCreate($restaurant_coupon_discount_subsidy,'coupon_discount',now(),$order->id,$order->coupon_created_by,$order->restaurant->id);
        }

        if($order->restaurant_discount_amount > 0  && $order->discount_on_product_by == 'vendor')
        {
            if($restaurant->restaurant_model == 'subscription' && isset($rest_sub)){
                $restaurant_d_amount=  $order->restaurant_discount_amount;
                Helpers::expenseCreate($restaurant_d_amount,'discount_on_product',now(),$order->id,'vendor',$order->restaurant->id);
            } else{
                $amount_admin = $comission?($order->restaurant_discount_amount/ 100) * $comission:0;
                $restaurant_d_amount=  $order->restaurant_discount_amount- $amount_admin;
                Helpers::expenseCreate($restaurant_d_amount,'discount_on_product',now(),$order->id,'vendor',$order->restaurant->id);
                Helpers::expenseCreate($amount_admin,'discount_on_product',now(),$order->id,'admin');
            }
        }

        if($order->restaurant_discount_amount > 0  && $order->discount_on_product_by == 'admin')
        {
            $restaurant_discount_amount=$order->restaurant_discount_amount;
            Helpers::expenseCreate($restaurant_discount_amount,'discount_on_product',now(),$order->id,'admin');
        }


        $order_amount = $order->order_amount - $order->delivery_charge - $order->total_tax_amount - $order->dm_tips + $order->coupon_discount_amount + $restaurant_discount_amount;

        if($restaurant->restaurant_model == 'subscription' && isset($rest_sub)){
            $comission_amount =0;
            $subscription_mode= 1;
            $commission_percentage= 0;
        }
        else{
            $comission_amount = $comission?($order_amount/ 100) * $comission:0;
            $subscription_mode= 0;
            $commission_percentage= $comission;
        }

        if(($restaurant->restaurant_model == 'subscription' && isset($rest_sub) && $rest_sub->self_delivery == 1) || ($restaurant->restaurant_model != 'subscription' && $restaurant->self_delivery_system)){
            $comission_on_delivery =0;
            $comission_on_actual_delivery_fee =0;
        }
        else{
            $delivery_charge_comission = BusinessSetting::where('key', 'delivery_charge_comission')->first();
            $delivery_charge_comission_percentage = $delivery_charge_comission ? $delivery_charge_comission->value : 0;

            $comission_on_delivery = $delivery_charge_comission_percentage * ( $order->original_delivery_charge / 100 );
            $comission_on_actual_delivery_fee = ($order->delivery_charge > 0) ? $comission_on_delivery : 0;
        }
        $restaurant_amount =$order_amount + $order->total_tax_amount - $comission_amount - $restaurant_coupon_discount_subsidy ;
        try{
            OrderTransaction::insert([
                'vendor_id' =>$order->restaurant->vendor->id,
                'delivery_man_id'=>$order->delivery_man_id,
                'order_id' =>$order->id,
                'order_amount'=>$order->order_amount,
                'restaurant_amount'=>$restaurant_amount,
                'admin_commission'=>$comission_amount - $admin_subsidy - $admin_coupon_discount_subsidy,
                //add a new column. add the comission here
                'delivery_charge'=>$order->delivery_charge - $comission_on_actual_delivery_fee,//minus here
                'original_delivery_charge'=>$order->original_delivery_charge - $comission_on_delivery,//calculate the comission with this. minus here
                'tax'=>$order->total_tax_amount,
                'received_by'=> $received_by?$received_by:'admin',
                'zone_id'=>$order->zone_id,
                'status'=> $status,
                'dm_tips'=> $order->dm_tips,
                'created_at' => now(),
                'updated_at' => now(),
                'delivery_fee_comission'=>$comission_on_actual_delivery_fee,
                'admin_expense'=>$admin_subsidy + $admin_coupon_discount_subsidy + $restaurant_discount_amount + $amount_admin,
                'restaurant_expense'=>$restaurant_subsidy + $restaurant_coupon_discount_subsidy ,
                // for restaurant business model
                'is_subscribed'=> $subscription_mode,
                'commission_percentage'=> $commission_percentage,
                'discount_amount_by_restaurant' => $restaurant_coupon_discount_subsidy + $restaurant_d_amount + $restaurant_subsidy,
                // for subscription order
                'is_subscription' => isset($order->subscription_id) ?  $order->subscription_id : 0 ,
            ]);
            $adminWallet = AdminWallet::firstOrNew(
                ['admin_id' => Admin::where('role_id', 1)->first()->id]
            );
            $vendorWallet = RestaurantWallet::firstOrNew(
                ['vendor_id' => $order->restaurant->vendor->id]
            );
            if($order->delivery_man &&
           (($restaurant->restaurant_model == 'subscription' && isset($rest_sub) && $rest_sub->self_delivery == 0) || ($restaurant->restaurant_model != 'subscription' && $restaurant->self_delivery_system == 0))
            ){
                $dmWallet = DeliveryManWallet::firstOrNew(
                    ['delivery_man_id' => $order->delivery_man_id]
                );
                if (isset($order->delivery_man->earning) && $order->delivery_man->earning == 1) {
                    self::check_incentive($order->zone_id, $order->delivery_man_id, $order->delivery_man->todays_earning()->sum('original_delivery_charge'), $order->delivery_man->incentive);
                    
                    $dmWallet->total_earning = $dmWallet->total_earning + $order->dm_tips + $order->original_delivery_charge - $comission_on_delivery;
                } else {
                    $adminWallet->total_commission_earning = $adminWallet->total_commission_earning + $order->dm_tips + $order->original_delivery_charge - $comission_on_delivery;
                }
            }

            $adminWallet->total_commission_earning = $adminWallet->total_commission_earning + $comission_amount + $comission_on_actual_delivery_fee - $admin_subsidy - $admin_coupon_discount_subsidy -$restaurant_discount_amount;

            if(($restaurant->restaurant_model == 'subscription' && isset($rest_sub) && $rest_sub->self_delivery == 1) || ($restaurant->restaurant_model != 'subscription' && $restaurant->self_delivery_system == 1))
            {
                $vendorWallet->total_earning = $vendorWallet->total_earning + $order->delivery_charge + $order->dm_tips;
            }
            else{
                $adminWallet->delivery_charge = $adminWallet->delivery_charge + $order->delivery_charge - $comission_on_actual_delivery_fee;
            }
            $vendorWallet->total_earning = $vendorWallet->total_earning + $restaurant_amount;
            try
            {
                DB::beginTransaction();
                if($received_by=='admin')
                {
                    $adminWallet->digital_received = $adminWallet->digital_received + $order->order_amount;
                }
                else if($received_by=='restaurant' && $order->payment_method == 'cash_on_delivery')
                {
                    $vendorWallet->collected_cash = $vendorWallet->collected_cash + $order->order_amount;
                }
                else if($received_by==false)
                {
                    $adminWallet->manual_received = $adminWallet->manual_received + $order->order_amount;
                }
                else if($received_by=='deliveryman' && $order->delivery_man->type == 'zone_wise' && $order->payment_method == 'cash_on_delivery')
                {
                    if(!isset($dmWallet)) {
                        $dmWallet = DeliveryManWallet::firstOrNew(
                            ['delivery_man_id' => $order->delivery_man_id]
                        );
                    }
                    $dmWallet->collected_cash=$dmWallet->collected_cash+$order->order_amount;
                }
                if(isset($dmWallet)) {
                    $dmWallet->save();
                }
                $vendorWallet->save();
                $adminWallet->save();
                DB::commit();

                $ref_status = BusinessSetting::where('key','ref_earning_status')->first()->value;
                if(isset($order->customer->ref_by) && $order->customer->order_count == 0  && $ref_status == 1){
                    $ref_code_exchange_amt = BusinessSetting::where('key','ref_earning_exchange_rate')->first()->value;
                    $referar_user=User::where('id',$order->customer->ref_by)->first();
                    $refer_wallet_transaction = CustomerLogic::create_wallet_transaction($referar_user->id, $ref_code_exchange_amt, 'referrer',$order->customer->phone);
                    try{
                        if(config('mail.status')) {
                            Mail::to($referar_user->email)->send(new \App\Mail\AddFundToWallet($refer_wallet_transaction));
                            }
                        } catch(\Exception $ex){
                            info($ex->getMessage());
                        }
                }

                if($order->user_id) CustomerLogic::create_loyalty_point_transaction($order->user_id, $order->id, $order->order_amount, 'order_place');
            }
            catch(\Exception $e)
            {
                DB::rollBack();
                info($e);
                return false;
            }
        }
        catch(\Exception $e){
            info($e);
            return false;
        }
        return true;
    }
    public static function refund_before_delivered($order){
        $adminWallet = AdminWallet::firstOrNew(
            ['admin_id' => Admin::where('role_id', 1)->first()->id]
        );
        if ($order->payment_method == 'cash_on_delivery') {
            return false;
        }
        $adminWallet->digital_received = $adminWallet->digital_received - $order->order_amount;
        $adminWallet->save();
        if ($order->payment_status == "paid" && BusinessSetting::where('key', 'wallet_add_refund')->first()->value == 1) {
            CustomerLogic::create_wallet_transaction($order->user_id, $order->order_amount, 'order_refund', $order->id);
        }
        return true;
    }


    public static function refund_order($order)
    {
        $order_transaction = $order->transaction;
        if($order_transaction == null || $order->restaurant == null)
        {
            return false;
        }
        $received_by = $order_transaction->received_by;

        $adminWallet = AdminWallet::firstOrNew(
            ['admin_id' => Admin::where('role_id', 1)->first()->id]
        );

        $vendorWallet = RestaurantWallet::firstOrNew(
            ['vendor_id' => $order->restaurant->vendor->id]
        );

        $adminWallet->total_commission_earning = $adminWallet->total_commission_earning - $order_transaction->admin_commission;

        $vendorWallet->total_earning = $vendorWallet->total_earning - $order_transaction->restaurant_amount;

        $refund_amount = $order->order_amount;

        $status = 'refunded_with_delivery_charge';
        if($order->order_status == 'delivered' || $order->order_status == 'refund_requested'|| $order->order_status == 'refund_request_canceled')
        {
            $refund_amount = $order->order_amount - $order->delivery_charge - $order->dm_tips;
            $status = 'refunded_without_delivery_charge';
        }
        else
        {
            $adminWallet->delivery_charge = $adminWallet->delivery_charge - $order_transaction->delivery_charge;
        }
        try
        {
            DB::beginTransaction();
            if($received_by=='admin')
            {
                if($order->delivery_man_id && $order->payment_method != "cash_on_delivery")
                {
                    $adminWallet->digital_received = $adminWallet->digital_received - $refund_amount;
                }
                else
                {
                    $adminWallet->manual_received = $adminWallet->manual_received - $refund_amount;
                }

            }
            else if($received_by=='restaurant')
            {
                $vendorWallet->collected_cash = $vendorWallet->collected_cash - $refund_amount;
            }

                // DB::table('account_transactions')->insert([
                //     'from_type'=>'customer',
                //     'from_id'=>$order->user_id,
                //     'current_balance'=> 0,
                //     'amount'=> $refund_amount,
                //     'method'=>'CASH',
                //     'created_at' => now(),
                //     'updated_at' => now()
                // ]);

            else if($received_by=='deliveryman')
            {
                $dmWallet = DeliveryManWallet::firstOrNew(
                    ['delivery_man_id' => $order->delivery_man_id]
                );
                $dmWallet->collected_cash=$dmWallet->collected_cash - $refund_amount;
                $dmWallet->save();
            }
            $order_transaction->status = $status;
            $order_transaction->save();

            $adminWallet->save();
            $vendorWallet->save();
            DB::commit();
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            info($e);
            return false;
        }
        return true;

    }

    public static function format_export_data($orders)
    {
        $data = [];
        foreach($orders as $key=>$order)
        {

            $data[]=[
                '#'=>$key+1,
                translate('messages.order')=>$order['id'],
                translate('messages.date')=>date('d M Y',strtotime($order['created_at'])),
                translate('messages.customer')=>$order->customer?$order->customer['f_name'].' '.$order->customer['l_name']:translate('messages.invalid').' '.translate('messages.customer').' '.translate('messages.data'),
                translate('messages.Restaurant')=>Str::limit($order->restaurant?$order->restaurant->name:translate('messages.Restaurant deleted!'),20,'...'),
                translate('messages.payment').' '.translate('messages.status')=>$order->payment_status=='paid'?translate('messages.paid'):translate('messages.unpaid'),
                translate('messages.total')=>\App\CentralLogics\Helpers::format_currency($order['order_amount']),
                translate('messages.order').' '.translate('messages.status')=>translate('messages.'. $order['order_status']),
                translate('messages.order').' '.translate('messages.type')=>translate('messages.'.$order['order_type'])
            ];
        }
        return $data;
    }

    public static function format_order_report_export_data($orders)
    {
        $data = [];
        foreach($orders as $key=>$order)
        {

            $data[]=[
                '#'=>$key+1,
                translate('messages.order')=>$order['id'],
                translate('messages.restaurant')=>$order->restaurant?$order->restaurant->name:translate('messages.invalid'),
                translate('messages.customer_name')=>$order->customer?$order->customer['f_name'].' '.$order->customer['l_name']:translate('messages.invalid').' '.translate('messages.customer').' '.translate('messages.data'),
                translate('Total product Amount')=>\App\CentralLogics\Helpers::format_currency($order['order_amount']-$order['dm_tips']-$order['total_tax_amount']-$order['delivery_charge']+$order['coupon_discount_amount'] + $order['restaurant_discount_amount']),
                translate('product Discount')=>\App\CentralLogics\Helpers::format_currency($order->details->sum('discount_on_item')),
                translate('Coupon Discount')=>\App\CentralLogics\Helpers::format_currency($order['coupon_discount_amount']),
                translate('Discounted Amount')=>\App\CentralLogics\Helpers::format_currency($order['coupon_discount_amount'] + $order['restaurant_discount_amount']),
                translate('messages.tax')=>\App\CentralLogics\Helpers::format_currency($order['total_tax_amount']),
                translate('messages.delivery_charge')=>\App\CentralLogics\Helpers::format_currency($order['original_delivery_charge']),
                translate('messages.order_amount')=>\App\CentralLogics\Helpers::format_currency($order['order_amount']),
                translate('messages.amount_received_by')=>isset($order->transaction) ? $order->transaction->received_by : translate('messages.unpaid'),
                translate('messages.payment_method')=>translate(str_replace('_', ' ', $order['payment_method'])),
                translate('messages.order').' '.translate('messages.status')=>translate('messages.'. $order['order_status']),
                translate('messages.order').' '.translate('messages.type')=>translate('messages.'.$order['order_type']),
            ];
        }
        return $data;
    }

    public static function check_incentive($zone_id, $delivery_man_id, $delivery_man_earning, $dm_incentive)
    {
        $incentive = Incentive::where('zone_id', $zone_id)->where('earning', '<=', $delivery_man_earning)->orderBy('earning', 'desc')->first();

        if ($dm_incentive) {
            if ($incentive && $dm_incentive->earning != $incentive->earning){
                $dm_incentive->earning = $incentive ? $incentive->earning : $dm_incentive->earning;
                $dm_incentive->incentive = $incentive ? $incentive->incentive : $dm_incentive->incentive;
            }
        } else {
            $dm_incentive = new IncentiveLog();
            $dm_incentive->earning = $incentive ? $incentive->earning : 0;
            $dm_incentive->incentive = $incentive ? $incentive->incentive : 0;
            $dm_incentive->delivery_man_id = $delivery_man_id;
            $dm_incentive->zone_id = $zone_id;
            $dm_incentive->date = now();
            $dm_incentive->status = 'pending';
        }
        // $min_pay = self::check_min_pay($delivery_man_id, $delivery_man_earning + $dm_incentive->incentive);
        // $dm_incentive->min_pay_subsidy = $min_pay[0];
        // $dm_incentive->working_hours = $min_pay[1];
        $dm_incentive->today_earning = $delivery_man_earning;
        $dm_incentive->save();
        // info(["incentive_created", $dm_incentive]);
        // Helpers::expenseCreate($dm_incentive->incentive,'incentive',now(),$delivery_man_id);
        return true;
    }

    public static function create_subscription_log($id=null)
    {
        $order = Order::find($id);
        if(!isset($order)  || !isset($order->subscription->schedule) || !isset($order->subscription->schedule_today) || isset($order->subscription_log ) || $order->restaurant->restaurant_model == 'unsubscribed'){
            return true;
        }

        $day = $order->subscription->schedule_today->day ??  $order->subscription->schedule->day ?? 0;
        $today = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'][$day] ??'Sun';
        $nextdate = date('Y-m-d', strtotime('next ' . $today));

        $time= $order->subscription->schedule_today->time ?? $order->subscription->schedule->time;
        $schedule_at = $day != 0 ? $nextdate : now()->format('Y-m-d');
        $subscription_log = new SubscriptionLog();
        $subscription_log->subscription_id = $order->subscription_id;
        $subscription_log->order_id = $order->id;
        $subscription_log->order_status = 'pending';
        $subscription_log->schedule_at = $schedule_at.' '.$time;
        $subscription_log->updated_at = now();
        $subscription_log->created_at = now();
        $order->subscription_log()->save($subscription_log);
        $order->order_status = 'pending';
        $order->payment_status='unpaid';
        $order->schedule_at = $schedule_at.' '.$time ;
        $order->save();

        Helpers::send_order_notification($order);

        return true;
    }

    public static function update_subscription_log(Order $order):void
    {
        if(!isset($order->subscription_log) || !isset($order->subscription_id)){
            return ;
        }
        $schedule_today = $order->subscription_log;
        $schedule_today->order_status = $order->order_status;
        $schedule_today->delivery_man_id = $order->delivery_man_id;
        if($order->order_status != 'pending')$schedule_today->{$order->order_status} = now();
        $schedule_today->save();

        if($order->order_status == 'delivered'){
            $subscription = $order->subscription;
            $subscription->billing_amount += $order->order_amount;
            $subscription->paid_amount += $order->order_amount;
            $subscription->save();

            $order->delivery_man_id = null;
            $order->save();
        }

        // if(in_array($order->order_status, ['delivered', 'canceled', 'failed']) && ($order->canceled == null))
        // {
        //     // $order->order_status = 'pending';
        //     $order->delivery_man_id = null;
        //     $order->save();
        // }


        return ;
    }

    public static function check_subscription(User $user):void
    {
        $subscriptions = Subscription::where('user_id', $user->id)->expired()->get();
        try{
            DB::beginTransaction();
            foreach($subscriptions as $subscription){
                if($subscription->paid_amount > $subscription->billing_amount){
                    $extra = $subscription->paid_amount - $subscription->billing_amount;
                    CustomerLogic::create_wallet_transaction($user->id, $extra, 'add_fund', "Subscription, Id:{$subscription->id}");
                }
                $subscription->status = 'expired';
                $subscription->save();
            }
            DB::commit();
        }catch(Exception $ex){
            DB::rollBack();
            info($ex->getMessage());
        }
    }
}
