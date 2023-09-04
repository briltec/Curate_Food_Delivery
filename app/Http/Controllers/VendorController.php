<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\Vendor;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;
use App\Models\SubscriptionPackage;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Grimzy\LaravelMysqlSpatial\Types\Point;

class VendorController extends Controller
{
    public function create()
    {
        $status = BusinessSetting::where('key', 'toggle_restaurant_registration')->first();
        if(!isset($status) || $status->value == '0')
        {
            Toastr::error(translate('messages.not_found'));
            return back();
        }
        return view('vendor-views.auth.register-step-1');
    }

    public function store(Request $request)
    {
        $status = BusinessSetting::where('key', 'toggle_restaurant_registration')->first();
        if(!isset($status) || $status->value == '0')
        {
            Toastr::error(translate('messages.not_found'));
            return back();
        }
        $validator = Validator::make($request->all(), [
            'f_name' => 'required',
            'name' => 'required',
            'address' => 'required',
            'latitude' => 'required|numeric|min:-90|max:90',
            'longitude' => 'required|numeric|min:-180|max:180',
            'email' => 'required|email|unique:vendors',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:vendors',
            'minimum_delivery_time' => 'required|regex:/^([0-9]{2})$/|min:2|max:2',
            'maximum_delivery_time' => 'required|regex:/^([0-9]{2})$/|min:2|max:2',
            'password' => 'required|min:6',
            'zone_id' => 'required',
            'logo' => 'required|max:2048',
            'cover_photo' => 'required|max:2048',
            'tax' => 'required',

        ]);

        if($request->zone_id)
        {
            $point = new Point($request->latitude, $request->longitude);
            $zone = Zone::contains('coordinates', $point)->where('id', $request->zone_id)->first();
            if(!$zone){
                $validator->getMessageBag()->add('latitude', translate('messages.coordinates_out_of_zone'));
                return back()->withErrors($validator)
                        ->withInput();
            }
        }
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        try{
            $cuisine_ids = [];
            $cuisine_ids=$request->cuisine_ids;

            DB::beginTransaction();
            $vendor = new Vendor();
            $vendor->f_name = $request->f_name;
            $vendor->l_name = $request->l_name;
            $vendor->email = $request->email;
            $vendor->phone = $request->phone;
            $vendor->password = bcrypt($request->password);
            $vendor->status = null;
            $vendor->save();

            $restaurant = new Restaurant;
            $restaurant->name = $request->name;
            $restaurant->phone = $request->phone;
            $restaurant->email = $request->email;
            $restaurant->logo = Helpers::upload('restaurant/', 'png', $request->file('logo'));
            $restaurant->cover_photo = Helpers::upload('restaurant/cover/', 'png', $request->file('cover_photo'));
            $restaurant->address = $request->address;
            $restaurant->latitude = $request->latitude;
            $restaurant->longitude = $request->longitude;
            $restaurant->vendor_id = $vendor->id;
            $restaurant->zone_id = $request->zone_id;
            $restaurant->tax = $request->tax;
            $restaurant->delivery_time = $request->minimum_delivery_time .'-'. $request->maximum_delivery_time;
            $restaurant->status = 0;
            $restaurant->restaurant_model = 'none';


            $restaurant->save();
            $restaurant->cuisine()->sync($cuisine_ids);


            DB::commit();
            if(config('mail.status')){
                Mail::to($request['email'])->send(new \App\Mail\SelfRegistration('pending', $vendor->f_name.' '.$vendor->l_name));
            }
            $restaurant_id = $restaurant->id;
            // $restaurant_id = 36;
            $admin_commission= BusinessSetting::where('key','admin_commission')->first();
            $business_name= BusinessSetting::where('key','business_name')->first();
            $packages= SubscriptionPackage::where('status',1)->get();
            if (Helpers::subscription_check()) {
                Toastr::success(translate('messages.your_registration_info_is_saved_successfully_now_please_choose_your_business_model'));
                return view('vendor-views.auth.register-step-2',[
                    'restaurant_id' => $restaurant_id,
                    'packages' =>$packages,
                    'business_name' =>$business_name->value,
                    'admin_commission' =>$admin_commission->value,
                ]);
            } else{
                $restaurant->restaurant_model = 'commission';
                $restaurant->save();
                Toastr::success(translate('messages.your_restaurant_registration_is_successful'));
                return view('vendor-views.auth.register-step-4',[
                    'logo'=> $restaurant->logo
                ]);
            }

        }catch(\Exception $ex){
            DB::rollback();
            info($ex);
            Toastr::success(translate('messages.something_went_wrong_Please_try_again.'));
            return back();
        }

    }

    public function business_plan(Request $request){
            $restaurant=Restaurant::findOrFail($request->restaurant_id);

            if ($request->business_plan == 'subscription-base' && $request->package_id != null ) {
                return view('vendor-views.auth.register-step-3',[
                'package_id'=> $request->package_id,
                'restaurant_id' => $request->restaurant_id,
                'type'=>$request->type
                ]);
            }
            elseif($request->business_plan == 'commission-base' ){
                $restaurant->restaurant_model = 'commission';
                $restaurant->save();
                return view('vendor-views.auth.register-step-4',[
                    'logo'=> $restaurant->logo,
                    'type'=>$request->type
                ]);
            }
            else{
                $admin_commission= BusinessSetting::where('key','admin_commission')->first();
                $business_name= BusinessSetting::where('key','business_name')->first();
                $packages= SubscriptionPackage::where('status',1)->get();
                Toastr::error(translate('messages.please_follow_the_steps_properly.'));
                return view('vendor-views.auth.register-step-2',[
                    'admin_commission'=> $admin_commission->value,
                    'business_name'=> $business_name->value,
                    'packages'=> $packages,
                    'restaurant_id' => $request->restaurant_id,
                    'type'=>$request->type
                    ]);
            }
    }


    public function payment(Request $request){
        $restaurant_id=$request->restaurant_id;
        $package_id=$request->package_id;
        $payment_method=$request->payment_method ?? 'free_trial';
        $reference=$request->reference ?? null;
        $discount=$request->discount ?? 0;
        $restaurant=Restaurant::findOrFail($restaurant_id);
        $type=$request->type ?? 'new_join';
        if($request->payment == 'free_trial' ){
            $status=  Helpers::subscription_plan_chosen($restaurant_id ,$package_id, $payment_method ,$reference ,$discount,$type);
            if($status === 'downgrade_error'){
                Toastr::error(translate('messages.You_can_not_downgraded_to_this_package_please_choose_a_package_with_higher_upload_limits') );
                return back();
                }
            Toastr::success(translate('messages.application_placed_successfully'));
            return view('vendor-views.auth.register-step-4',[
                'logo'=> $restaurant->logo
            ]);
        }
        elseif($request->payment == 'paying_now'){

            // dd('paying_now');

            $payment_method='manual_payment_by_restaurant';
            $status=  Helpers::subscription_plan_chosen($restaurant_id ,$package_id, $payment_method ,$reference ,$discount,$type);
            if($status === 'downgrade_error'){
                Toastr::error(translate('messages.You_can_not_downgraded_to_this_package_please_choose_a_package_with_higher_upload_limits') );
                return back();
                }
            Toastr::success(translate('messages.application_placed_successfully'));
            return view('vendor-views.auth.register-step-4',[
                'logo'=> $restaurant->logo
            ]);
        }
    }

    public function back(Request $request){
        $restaurant_id = decrypt($request->restaurant_id);
        $admin_commission= BusinessSetting::where('key','admin_commission')->first();
        $business_name= BusinessSetting::where('key','business_name')->first();
        $packages= SubscriptionPackage::where('status',1)->get();
        return view('vendor-views.auth.register-step-2',[
            'admin_commission'=> $admin_commission->value,
            'business_name'=> $business_name->value,
            'packages'=> $packages,
            'restaurant_id' => $restaurant_id
            ]);
    }

}
