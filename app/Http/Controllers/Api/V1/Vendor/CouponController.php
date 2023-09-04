<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Models\User;
use App\Models\Coupon;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    public function list(Request $request){
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);
        $restaurant_id = $request->vendor->restaurants[0]->id;
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $limit = $request['limit']??25;
        $offset = $request['offset']??1;
        $storage = [];
        $coupons = Coupon::latest()->where('created_by', 'vendor' )->where('restaurant_id',$restaurant_id)
        ->paginate($limit, ['*'], 'page', $offset);

            foreach($coupons->items() as $item){
                $item['data'] = json_decode($item['data'],true);
                $item['customer_id'] = json_decode($item['customer_id'],true);
                array_push($storage, $item);
            }

        return response()->json($storage,200);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:coupons|max:100',
            'title' => 'required|max:191',
            'start_date' => 'required',
            'expire_date' => 'required',
            'coupon_type' => 'required|in:free_delivery,default',
            'discount' => 'required_if:coupon_type,default'
        ]);
        $customer_id  = $request->customer_ids ?? ['all'];
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $restaurant_id = $request->vendor->restaurants[0]->id;

        $data = "";
        DB::table('coupons')->insert([
            'title' => $request->title,
            'code' => $request->code,
            'limit' => $request->coupon_type=='first_order'?1:$request->limit,
            'coupon_type' => $request->coupon_type,
            'start_date' => $request->start_date,
            'expire_date' => $request->expire_date,
            'min_purchase' => $request->min_purchase != null ? $request->min_purchase : 0,
            'max_discount' => $request->max_discount != null ? $request->max_discount : 0,
            'discount' => $request->discount_type == 'amount' ? $request->discount : $request['discount'],
            'discount_type' => $request->discount_type??'',
            'status' => 1,
            'created_by' => 'vendor',
            'data' => json_encode($data),
            'restaurant_id' =>$restaurant_id,
            'customer_id' => json_encode($customer_id),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return response()->json(['message' => translate('messages.coupon_added_successfully')], 200);

    }

    public function view(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coupon_id'=> 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $coupon = Coupon::find($request->coupon_id);
        $coupon['data'] = json_decode($coupon['data'],true);
        $coupon['customer_id'] = json_decode($coupon['customer_id'],true);
        return response()->json([$coupon],200);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coupon_id'=> 'required',
            'code' => 'required|max:100|unique:coupons,code,'.$request->coupon_id,
            'title' => 'required|max:191',
            'start_date' => 'required',
            'expire_date' => 'required',
            'discount' => 'required_if:coupon_type,default',
            'coupon_type' => 'required|in:free_delivery,default',

        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $restaurant_id = $request->vendor->restaurants[0]->id;
        $customer_id  = $request->customer_ids ?? ['all'];

        DB::table('coupons')->where(['id' => $request->coupon_id])->where('created_by', 'vendor' )->where('restaurant_id',$restaurant_id)
        ->update([
            'title' => $request->title,
            'code' => $request->code,
            'limit' => $request->coupon_type=='first_order'?1:$request->limit,
            'coupon_type' => $request->coupon_type,
            'start_date' => $request->start_date,
            'expire_date' => $request->expire_date,
            'min_purchase' => $request->min_purchase != null ? $request->min_purchase : 0,
            'max_discount' => $request->max_discount != null ? $request->max_discount : 0,
            'discount' => $request->discount_type == 'amount' ? $request->discount : $request['discount'],
            'discount_type' => $request->discount_type ?? '',
            'customer_id' => json_encode($customer_id),
            'updated_at' => now()
        ]);
        return response()->json(['message' => translate('messages.coupon_updated_successfully')], 200);
    }

    public function status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coupon_id'=> 'required',
            'status'=> 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $restaurant_id = $request->vendor->restaurants[0]->id;

        $coupon = Coupon::where(['id' => $request->coupon_id])->where('created_by', 'vendor' )->where('restaurant_id',$restaurant_id)->first();

        $coupon->status = $request->status;
        $coupon->save();
        return response()->json(['message' => translate('messages.coupon_status_updated')], 200);
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coupon_id'=> 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $restaurant_id = $request->vendor->restaurants[0]->id;

        $coupon = Coupon::where(['id' => $request->coupon_id])->where('created_by', 'vendor' )->where('restaurant_id',$restaurant_id)->first();
        $coupon->delete();
        return response()->json(['message' => translate('messages.coupon_deleted_successfully')], 200);

    }

    public function search(Request $request){

        $validator = Validator::make($request->all(), [
            'search' => 'required',
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $restaurant_id = $request->vendor->restaurants[0]->id;

        $limit = $request['limit']??25;
        $offset = $request['offset']??1;

        $key = explode(' ', $request['search']);
        $coupons=Coupon::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('title', 'like', "%{$value}%")
                ->orWhere('code', 'like', "%{$value}%");
            }
        })->where('created_by', 'vendor' )->where('restaurant_id',$restaurant_id)
        ->paginate($limit, ['*'], 'page', $offset);
        $data = [
            'total_size' => $coupons->total(),
            'limit' => $limit,
            'offset' => $offset,
            'coupons' => $coupons->items()
        ];
        return response()->json([$data],200);
    }

}
