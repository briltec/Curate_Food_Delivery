<?php

namespace App\Http\Controllers\Admin;

use App\Models\Zone;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use Illuminate\Support\Carbon;
use App\Models\RestaurantWallet;
use App\Models\SubscriptionPackage;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\RestaurantSubscription;
use App\Models\SubscriptionTransaction;

class SubscriptionController extends Controller
{
    public function package_list()
    {
        $packages = SubscriptionPackage::withCount('transactions')
            ->latest()
            ->paginate(config('default_pagination'));
            // $expire_soon = RestaurantSubscription::where('status', 1)->whereDate('expiry_date', '<=', Carbon::today()->addDays('10'))->count();
            // $rest= RestaurantSubscription::get();
            // $active= $rest->where('status',1)->count();
            // $suspended= $rest->where('status',0)->count();
            // $total_sub=SubscriptionTransaction::count();
            $total=$packages->total();
        return view('admin-views.subscription.index', [
            'packages' => $packages,
            'total' => $total,
            // 'expire_soon' => $expire_soon,
            // 'active' => $active,
            // 'suspended' => $suspended,
            // 'total_sub' => $total_sub,
        ]);
    }

    public function create()
    {
        return view('admin-views.subscription.create');
    }

    public function edit($id)
    {
        $package = SubscriptionPackage::findOrFail($id);
        return view('admin-views.subscription.edit', compact('package'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'package_name' => 'required|max:191|unique:subscription_packages',
            'package_price' => 'required|numeric|between:0,999999999999.99',
            'package_validity' => 'required|integer|between:0,999999999',
            'max_order' => 'nullable|integer|between:0,999999999',
            'max_product' => 'nullable|integer|between:0,999999999',

            'pos_system' => 'nullable|boolean',
            'mobile_app' => 'nullable|boolean',
            'self_delivery' => 'nullable|boolean',
            'chat' => 'nullable|boolean',
            'review' => 'nullable|boolean',

            ], [
            'price.required' => translate('Must enter Price for the Package'),
            'package_name.required' => translate('Name of the Package is required'),
            'validity.required' => translate('Must enter a validity period for the Package in days'),
        ]);


        SubscriptionPackage::create([
            'package_name' => $request->package_name,
            'price' => $request->package_price,
            'validity' => $request->package_validity,
            'max_order' => $request->max_order  ?? 'unlimited',
            'max_product' => $request->max_product ?? 'unlimited',
            'pos' => $request->pos_system ?? 0,
            'mobile_app' => $request->mobile_app ?? 0,
            'self_delivery' => $request->self_delivery ?? 0,
            'chat' => $request->chat ?? 0,
            'review' => $request->review ?? 0,
            'colour' => $request->colour,
            'text' => $request->text,
        ]);
        Toastr::success(translate('Subscription Plan Added Successfully'));
        return redirect()->route('admin.subscription.package_list');
    }


    public function update(Request $request)
    {

        $request->validate([
            'package_name' => 'required|max:191|unique:subscription_packages,package_name,' . $request->id,
            'package_price' => 'required|numeric|between:0,999999999999.99',
            'package_validity' => 'required|integer|between:0,999999999',
            'max_order' => 'nullable|integer|between:0,999999999',
            'max_product' => 'nullable|integer|between:0,999999999',

            'pos_system' => 'nullable|boolean',
            'mobile_app' => 'nullable|boolean',
            'self_delivery' => 'nullable|boolean',
            'chat' => 'nullable|boolean',
            'review' => 'nullable|boolean',

        ], [
            'price.required' => translate('Must enter Price for the Package'),
            'package_name.required' => translate('Name of the Package is required'),
            'validity.required' => translate('Must enter a validity period for the Package in days'),
        ]);

        SubscriptionPackage::findOrfail($request->id)->update([
            'package_name' => $request->package_name,
            'price' => $request->package_price,
            'validity' => $request->package_validity,
            'max_order' => $request->max_order ?? 'unlimited',
            'max_product' => $request->max_product ?? 'unlimited',
            'pos' => $request->pos_system ?? 0,
            'mobile_app' => $request->mobile_app ?? 0,
            'self_delivery' => $request->self_delivery ?? 0,
            'chat' => $request->chat ?? 0,
            'review' => $request->review ?? 0,
            'colour' => $request->colour,
            'text' => $request->text,
        ]);


        Toastr::success(translate('Subscription Plan Updated Successfully'));
        return redirect()->route('admin.subscription.package_list');
        }

    public function details($id)
    {

        $package = SubscriptionPackage::withCount('transactions')->findOrFail($id);
        $expire_soon = RestaurantSubscription::where('status', 1)->where('package_id',$package->id)->whereDate('expiry_date', '<=', Carbon::today()->addDays('7'))->count();
        $RestaurantSubscription=RestaurantSubscription::where('package_id',$package->id)->get();
        $active= $RestaurantSubscription->where('status',1)->count();
        $deactive=  $RestaurantSubscription->where('status',0)->count();
        $total_renewed=  $RestaurantSubscription->sum('total_package_renewed');
        $total_on_package=$RestaurantSubscription->count();
        $on_trail=SubscriptionTransaction::where('package_id',$package->id)->where('payment_method','free_trial')->count();

        $this_month_active= RestaurantSubscription::where('package_id',$package->id)->where('status',1)->whereMonth('created_at', Carbon::now()->month)->count();
        $this_month_deactive=  RestaurantSubscription::where('package_id',$package->id)->where('status',0)->whereMonth('created_at', Carbon::now()->month)->count();
        $this_month_total_renewed=  RestaurantSubscription::where('package_id',$package->id)->whereMonth('created_at', Carbon::now()->month)->sum('total_package_renewed');
        $this_month_total_sub= SubscriptionTransaction::where('package_id',$package->id)->whereMonth('created_at', Carbon::now()->month)->count();
        $this_month_total_on_package= RestaurantSubscription::where('package_id',$package->id)->whereMonth('created_at', Carbon::now()->month)->count();
        $his_month_on_trail=SubscriptionTransaction::where('package_id',$package->id)->where('payment_method','free_trial')->whereMonth('created_at', Carbon::now()->month)->count();



        $this_year_active= RestaurantSubscription::where('package_id',$package->id)->where('status',1)->whereYear('created_at', Carbon::now()->year)->count();
        $this_year_deactive=  RestaurantSubscription::where('package_id',$package->id)->where('status',0)->whereYear('created_at', Carbon::now()->year)->count();
        $this_year_total_renewed=  RestaurantSubscription::where('package_id',$package->id)->whereYear('created_at', Carbon::now()->year)->sum('total_package_renewed');
        $this_year_total_sub= SubscriptionTransaction::where('package_id',$package->id)->whereYear('created_at', Carbon::now()->year)->count();
        $this_year_total_on_package= RestaurantSubscription::where('package_id',$package->id)->whereYear('created_at', Carbon::now()->year)->count();
        $his_year_on_trail=SubscriptionTransaction::where('package_id',$package->id)->where('payment_method','free_trial')->whereMonth('created_at', Carbon::now()->year)->count();

        $this_month = SubscriptionTransaction::where('package_id', $id)->whereMonth('created_at', Carbon::now()->month)->sum('paid_amount');
        $transcation_sum = SubscriptionTransaction::where('package_id', $id)->sum('paid_amount');

        $transcation_sum_month = SubscriptionTransaction::where('package_id', $id)->whereMonth('created_at', Carbon::now()->month)->sum('paid_amount');
        $transcation_sum_year = SubscriptionTransaction::where('package_id', $id)->whereYear('created_at', Carbon::now()->year)->sum('paid_amount');
        $transcation_sum = SubscriptionTransaction::where('package_id', $id)->sum('paid_amount');
        return view('admin-views.subscription.view', compact([
                    'package',
                    'active',
                    'transcation_sum',
                    'deactive',
                    'this_month',
                    'total_renewed',
                    'expire_soon',
                    'this_month_active',
                    'this_month_deactive',
                    'this_month_total_renewed',
                    'this_month_total_sub',
                    'this_year_active',
                    'this_year_deactive',
                    'this_year_total_renewed',
                    'this_year_total_sub',
                    'transcation_sum_month',
                    'transcation_sum_year',
                    'total_on_package',
                    'this_month_total_on_package',
                    'this_year_total_on_package',
                    'on_trail',
                    'his_month_on_trail',
                    'his_year_on_trail',
        ]));
    }


    public function transcation_list(Request $request, $id){
        $filter = $request->query('filter', 'all');
        $transcations = SubscriptionTransaction::withoutGlobalScope(RestaurantScope::class)->where('package_id',$id)
        ->when($filter == 'month', function ($query) {
            return $query->whereMonth('created_at', Carbon::now()->month);
        })
        ->when($filter == 'year', function ($query) {
            return $query->whereYear('created_at', Carbon::now()->year);
        })
        ->latest()->paginate(config('default_pagination'));
        $total = $transcations->total();
        return view('admin-views.subscription.subscription-transaction',[
        'transcations' => $transcations,
        'filter' => $filter,
        'total' => $total,
        'package_id' => $id,
        ]);
    }

    public function trans_search_by_date(Request $request){
        $from=$request->start_date;
        $to= $request->end_date;
        $id=$request->package_id;
        $filter = 'all';
        $transcations=SubscriptionTransaction::where('package_id',$id)
        ->whereBetween('created_at', ["{$from}", "{$to} 23:59:59"])
        ->latest()->paginate(config('default_pagination'));
        $total = $transcations->total();
        return view('admin-views.subscription.subscription-transaction',[
            'transcations' => $transcations,
            'filter' => $filter,
            'total' => $total,
            'package_id' => $id,
            'from' =>  $from,
            'to' =>  $to,
            ]);
    }

    public function transcation_search(Request $request)
    {
        $key = explode(' ', $request['search']);
        $transcations = SubscriptionTransaction::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('id', 'like', "%{$value}%")
                    ->orWhere('paid_amount', 'like', "%{$value}%")
                    ->orWhere('reference', 'like', "%{$value}%")
                    ->orWheredate('created_at', 'like', "%{$value}%");
            }
        })
            ->with('restaurant')
            ->latest()->paginate(config('default_pagination'));
        $total = $transcations->count();
        return response()->json([
            'view' => view('admin-views.subscription.partials._subs_transcation', compact('transcations'))->render(),'total' => $total
        ]);
    }



    public function search(Request $request)
    {
        $key = explode(' ', $request['search']);
        $packages = SubscriptionPackage::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('package_name', 'like', "%{$value}%")
                    ->orWhere('price', 'like', "%{$value}%")
                    ->orWhere('validity', 'like', "%{$value}%");
            }
        })->latest()->paginate(config('default_pagination'));
        $total=$packages->total();
        return response()->json([
            'view' => view('admin-views.subscription.partials._table', compact('packages'))->render(),'total' => $total
        ]);
    }


    public function subscription_search(Request $request){
        $key = explode(' ', $request['search']);
        $restaurants = RestaurantSubscription::with('restaurant')->whereHas('restaurant',function($query)use($key){
            foreach ($key as $value) {
                $query->where('name', 'like', "%{$value}%")->orWhere('email', 'like', "%{$value}%");
            }
        })->latest()->paginate(config('default_pagination'));
        $total=$restaurants->total();
        return response()->json([
            'view' => view('admin-views.subscription.partials._subs_table', compact('restaurants'))->render(),'total' => $total
        ]);
    }

    public function status(SubscriptionPackage $package, Request $request)
    {
        $package->status = $request->status;
        $package->save();
        Toastr::success(translate('messages.Package') . translate('messages.status_updated'));
        return back();
    }



    public function subscription_list(Request $request)
    {
        $zone_id = $request->query('zone_id', 'all');
        $type = $request->query('type', 'all');
        $restaurants = Restaurant::whereHas('restaurant_sub_update_application', function($query)use($type){
            $query->when($type == 'subscribed', function ($query) {
                return $query->where('status', 1);
            })
            ->when($type == 'unsubscribed', function ($query) {
                return $query->where('status', 0);
            })
            ->when($type == 'expire_soon', function ($query) {
                return $query->where('status', 1)->whereDate('expiry_date', '<=', Carbon::today()->addDays('10'));
            });
        })
        ->when($request->query('key'),function($query)use($request){
            $query->where(function($query)use($request){
                $key = explode(' ', $request->query('key'));
                foreach ($key as $value) {
                    $query->orWhere('name', 'like', "%{$value}%")->orWhere('email', 'like', "%{$value}%");
                }
            });
        })
        ->when(is_numeric($request->zone_id), function ($query) use ($request) {
            return $query->where('zone_id', $request->zone_id);
        })
        ->latest()->paginate(config('default_pagination'));

        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $transcations=SubscriptionTransaction::get();
        $sub_transcations = $transcations->count();
        $total_earning = $transcations->sum('paid_amount');
        $this_month = SubscriptionTransaction::whereMonth('created_at', Carbon::now()->month)->sum('paid_amount');

        $restaurant_subscription=RestaurantSubscription::get();
        $total_restaurant = $restaurant_subscription->groupBy('restaurant_id')->count();
        $total_active_subscription = $restaurant_subscription->where('status', 1)->groupBy('restaurant_id')->count();
        $total_inactive_subscription = $restaurant_subscription->where('status', 0)->groupBy('restaurant_id')->count();
        $expire_soon = RestaurantSubscription::where('status', 1)->groupBy('restaurant_id')->whereDate('expiry_date', '<=', Carbon::today()->addDays('10'))->count();

        return view('admin-views.subscription.list', compact(
            'restaurants',
            'zone',
            'type',
            'sub_transcations',
            'expire_soon',
            'this_month',
            'total_earning',
            'total_active_subscription',
            'total_inactive_subscription',
            'total_restaurant'
        ));
    }


    public function package_renew_change_update(Request $request){
        $package = SubscriptionPackage::findOrFail($request->package_id);
        $discount = $request->discount ?? 0;
        $restaurant=Restaurant::findOrFail($request->restaurant_id);
        $restaurant_id=$restaurant->id;
        $total_parice =$package->price - (($package->price*$discount)/100);
        $reference= $request->reference ?? null;
        if($request->button == 'renew'){
            $type = 'renew';
        }else{
            $type = null;
        }
        if ($request->payment_type == 'wallet') {
            $wallet = RestaurantWallet::where('vendor_id',$restaurant->vendor_id)->first();
            if ( $wallet->balance >= $total_parice) {
                $payment_method= 'wallet';
                $status=  Helpers::subscription_plan_chosen($restaurant_id ,$package->id, $payment_method ,$reference ,$discount,$type);

                if($status === 'downgrade_error'){
                Toastr::error(translate('messages.You_can_not_downgraded_to_this_package_please_choose_a_package_with_higher_upload_limits') );
                return back();
                }
                $wallet->total_withdrawn= $wallet->total_withdrawn +$total_parice;
                    $wallet->save();
            }
            else{
                Toastr::error('Insufficient Balance');
                return back();
            }
        }
        elseif ($request->payment_type == 'pay_now') {
            // dd('pay_now');
        $payment_method= 'manual_payment_admin';
        $status=  Helpers::subscription_plan_chosen($restaurant_id ,$package->id, $payment_method ,$reference ,$discount,$type);
        if($status === 'downgrade_error'){
            Toastr::error(translate('messages.You_can_not_downgraded_to_this_package_please_choose_a_package_with_higher_upload_limits') );
            return back();
            }

        }
        Toastr::success(translate('messages.subscription_successful') );
        return back();
    }


    public function package_selected(Request $request,$id,$rest_id){
        $restaurant_id = $rest_id;
        $rest_subscription= RestaurantSubscription::where('restaurant_id', $restaurant_id)->with(['package'])->latest()->first();
        $package = SubscriptionPackage::where('status',1)->where('id',$id)->first();
        return response()->json([
            'view' => view('admin-views.subscription.partials._package_selected', compact('rest_subscription','package','restaurant_id'))->render()
        ]);
    }

    public function package_cancel(Request $request){
        // RestaurantSubscription::where('restaurant_id', $request->id)->update([
        //     'status' => 0
        // ]);

        $restaurant =  Restaurant::where('id', $request->id)->first();
        $restaurant->update([
            'status' => 0,
            'self_delivery_system' => 1,
            'reviews_section' => 1,
            'free_delivery' => 0,
            'pos_system' => 1,
            'restaurant_model' => 'unsubscribed',
        ]);
        $restaurant->coupon()->where('created_by','vendor')->where('coupon_type','free_delivery')->delete();
        $restaurant->restaurant_subs()->update(['status' => 0]);
        Toastr::success(translate('Subscription Canceled') );
        return back();
    }

    public function invoice($id){
        $subscription_transaction= SubscriptionTransaction::findOrFail($id);
        $restaurant= Restaurant::findOrFail($subscription_transaction->restaurant_id);

        return view('admin-views.subscription.subs_transcation_invoice', compact(
            'restaurant',
            'subscription_transaction',
        ));
    }
    public function settings(){
        // $free_trial_period= BusinessSetting::where('key', 'free_trial_period')->first();
        // return view('admin-views.subscription.settings',['free_trial_period'=>$free_trial_period]);

        $free_trial_period = BusinessSetting::where(['key' => 'free_trial_period'])->first();
        if ($free_trial_period == false) {

            $values= [
                'data' => '',
                'status' => 0,
            ];
            Helpers::insert_business_settings_key('free_trial_period',  json_encode($values) );
        }
        $free_trial_period = json_decode(BusinessSetting::where(['key' => 'free_trial_period'])->first()->value,true);
        return view('admin-views.subscription.settings',['free_trial_period'=>$free_trial_period]);


    }
    public function settings_update(Request $request){
        $data = json_decode(BusinessSetting::where(['key' => 'free_trial_period'])->first()->value,true);
        $values= [
            'data' => $request->free_trial_period,
            'status' => $data['status'],
        ];
        BusinessSetting::where(['key' => 'free_trial_period'])->update([
            'value' => $values,
        ]);
        Toastr::success(translate('messages.free_trial_period_updated') );
        return back();
    }



    public function settings_update_status($status){
        $data = json_decode(BusinessSetting::where(['key' => 'free_trial_period'])->first()->value,true);
        $values= [
            'data' => $data['data'],
            'status' => $status,
        ];
        BusinessSetting::where(['key' => 'free_trial_period'])->update([
            'value' => $values,
        ]);
        return response()->json(['status'=>"changed"]);
    }


}
