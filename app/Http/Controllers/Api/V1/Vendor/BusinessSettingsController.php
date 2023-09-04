<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use Illuminate\Support\Facades\Validator;
use App\Models\RestaurantSchedule;

class BusinessSettingsController extends Controller
{

    public function update_restaurant_setup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'contact_number' => 'required',
            'delivery' => 'required|boolean',
            'take_away' => 'required|boolean',
            'schedule_order' => 'required|boolean',
            'veg' => 'required|boolean',
            'non_veg' => 'required|boolean',
            'order_subscription_active' => 'required|boolean',
            'minimum_order' => 'required|numeric',
            'gst' => 'required_if:gst_status,1',
            'logo' => 'nullable|max:2048',
            'cover_photo' => 'nullable|max:2048',

            // 'cuisine_ids' => 'required',
        ],[
            'gst.required_if' => translate('messages.gst_can_not_be_empty'),
        ]);
        $restaurant = $request['vendor']->restaurants[0];
        $data =0;
        if(($restaurant->restaurant_model == 'subscription' && isset($restaurant->restaurant_sub) && $restaurant->restaurant_sub->self_delivery == 1)  || ($restaurant->restaurant_model == 'commission' &&  $restaurant->self_delivery_system == 1) ){
        $data =1;
        }

        $validator->sometimes('per_km_delivery_charge', 'required_with:minimum_delivery_charge', function ($request) use($data) {
            return ($data);
        });
        $validator->sometimes('minimum_delivery_charge', 'required_with:per_km_delivery_charge', function ($request) use($data) {
            return ($data);
        });

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        if(!$request->take_away && !$request->delivery)
        {
            return response()->json([
                'error'=>[
                    ['code'=>'delivery_or_take_way', 'message'=>translate('messages.can_not_disable_both_take_away_and_delivery')]
                ]
            ],403);
        }

        if(!$request->veg && !$request->non_veg)
        {
            return response()->json([
                'error'=>[
                    ['code'=>'veg_non_veg', 'message'=>translate('messages.veg_non_veg_disable_warning')]
                ]
            ],403);
        }

        $restaurant->order_subscription_active = $request->order_subscription_active;
        $restaurant->delivery = $request->delivery;
        $restaurant->take_away = $request->take_away;
        $restaurant->schedule_order = $request->schedule_order;
        $restaurant->veg = $request->veg;
        $restaurant->non_veg = $request->non_veg;
        $restaurant->minimum_order = $request->minimum_order;
        $restaurant->opening_time = $request->opening_time;
        $restaurant->closeing_time = $request->closeing_time;
        $restaurant->off_day = $request->off_day??'';
        $restaurant->gst = json_encode(['status'=>$request->gst_status, 'code'=>$request->gst]);

        $restaurant->name = $request->name;
        $restaurant->address = $request->address;
        $restaurant->phone = $request->contact_number;
        $restaurant->minimum_shipping_charge = $data?$request->minimum_delivery_charge??0: $restaurant->minimum_shipping_charge;
        $restaurant->per_km_shipping_charge = $data?$request->per_km_delivery_charge??0: $restaurant->per_km_shipping_charge;

        $restaurant->maximum_shipping_charge = $data?$request->maximum_delivery_charge??0: $restaurant->maximum_delivery_charge;
        $restaurant->logo = $request->has('logo') ? Helpers::update('restaurant/', $restaurant->logo, 'png', $request->file('logo')) : $restaurant->logo;
        $restaurant->cover_photo = $request->has('cover_photo') ? Helpers::update('restaurant/cover/', $restaurant->cover_photo, 'png', $request->file('cover_photo')) : $restaurant->cover_photo;

        $restaurant->save();

        $cuisine_ids = [];
        $cuisine_ids = json_decode($request->cuisine_ids, true);
        $restaurant->cuisine()->sync($cuisine_ids);

        if($restaurant->vendor->userinfo) {
            $userinfo = $restaurant->vendor->userinfo;
            $userinfo->f_name = $restaurant->name;
            $userinfo->image = $restaurant->logo;
            $userinfo->save();
        }

        return response()->json(['message'=>translate('messages.restaurant_settings_updated')], 200);
    }

    public function add_schedule(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'opening_time'=>'required|date_format:H:i:s',
            'closing_time'=>'required|date_format:H:i:s|after:opening_time',
        ],[
            'closing_time.after'=>translate('messages.End time must be after the start time')
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)],400);
        }
        $restaurant = $request['vendor']->restaurants[0];
        $temp = RestaurantSchedule::where('day', $request->day)->where('restaurant_id',$restaurant->id)
        ->where(function($q)use($request){
            return $q->where(function($query)use($request){
                return $query->where('opening_time', '<=' , $request->opening_time)->where('closing_time', '>=', $request->opening_time);
            })->orWhere(function($query)use($request){
                return $query->where('opening_time', '<=' , $request->closing_time)->where('closing_time', '>=', $request->closing_time);
            });
        })
        ->first();

        if(isset($temp))
        {
            return response()->json(['errors' => [
                ['code'=>'time', 'message'=>translate('messages.schedule_overlapping_warning')]
            ]], 400);
        }

        $restaurant_schedule = RestaurantSchedule::insertGetId(['restaurant_id'=>$restaurant->id,'day'=>$request->day,'opening_time'=>$request->opening_time,'closing_time'=>$request->closing_time]);
        return response()->json(['message'=>translate('messages.Schedule added successfully'), 'id'=>$restaurant_schedule], 200);
    }

    public function remove_schedule(Request $request, $restaurant_schedule)
    {
        $restaurant = $request['vendor']->restaurants[0];
        $schedule = RestaurantSchedule::where('restaurant_id', $restaurant->id)->find($restaurant_schedule);
        if(!$schedule)
        {
            return response()->json([
                'error'=>[
                    ['code'=>'not-fond', 'message'=>translate('messages.Schedule not found')]
                ]
            ],404);
        }
        $schedule->delete();
        return response()->json(['message'=>translate('messages.Schedule removed successfully')], 200);
    }
}
