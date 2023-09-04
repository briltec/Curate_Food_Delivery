<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    public function add_new(Request $request)
    {
        $key = explode(' ', $request['search']);
        $coupons = Coupon::where('created_by','admin')
        ->when(isset($key), function($query)use($key){
            $query->where( function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('title', 'like', "%{$value}%")
                    ->orWhere('code', 'like', "%{$value}%");
                }
            });
        })
        ->latest()->paginate(config('default_pagination'));
        return view('admin-views.coupon.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons|max:100',
            'title' => 'required|max:191',
            'start_date' => 'required',
            'expire_date' => 'required',
            'discount' => 'required',
            'coupon_type' => 'required|in:zone_wise,restaurant_wise,free_delivery,first_order,default',
            'zone_ids' => 'required_if:coupon_type,zone_wise',
            'restaurant_ids' => 'required_if:coupon_type,restaurant_wise'
        ]);
        $data  = '';
        $customer_id  = $request->customer_ids ?? ['all'];
        if($request->coupon_type == 'zone_wise')
        {
            $data = $request->zone_ids;
        }
        else if($request->coupon_type == 'restaurant_wise')
        {
            $data = $request->restaurant_ids;
        }

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
            'created_by' => 'admin',
            'data' => json_encode($data),
            'customer_id' => json_encode($customer_id),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Toastr::success(translate('messages.coupon_added_successfully'));
        return back();
    }

    public function edit($id)
    {
        $coupon = Coupon::where(['id' => $id])->first();
        // dd(json_decode($coupon->data));
        return view('admin-views.coupon.edit', compact('coupon'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|max:100|unique:coupons,code,'.$id,
            'title' => 'required|max:191',
            'start_date' => 'required',
            'expire_date' => 'required',
            'discount' => 'required',
            'zone_ids' => 'required_if:coupon_type,zone_wise',
            'restaurant_ids' => 'required_if:coupon_type,restaurant_wise'
        ]);
        $data  = '';
        if($request->coupon_type == 'zone_wise')
        {
            $data = $request->zone_ids;
        }
        else if($request->coupon_type == 'restaurant_wise')
        {
            $data = $request->restaurant_ids;
        }
        $customer_id  = $request->customer_ids ?? ['all'];
        DB::table('coupons')->where(['id' => $id])->update([
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
            'data' => json_encode($data),
            'customer_id' => json_encode($customer_id),
            'updated_at' => now()
        ]);

        Toastr::success(translate('messages.coupon_updated_successfully'));
        return redirect()->route('admin.coupon.add-new');
    }

    public function status(Request $request)
    {
        $coupon = Coupon::find($request->id);
        $coupon->status = $request->status;
        $coupon->save();
        Toastr::success(translate('messages.coupon_status_updated'));
        return back();
    }

    public function delete(Request $request)
    {
        $coupon = Coupon::find($request->id);
        $coupon->delete();
        Toastr::success(translate('messages.coupon_deleted_successfully'));
        return back();
    }

    // public function search(Request $request){
    //     $key = explode(' ', $request['search']);
    //     $coupons=Coupon::where(function ($q) use ($key) {
    //         foreach ($key as $value) {
    //             $q->orWhere('title', 'like', "%{$value}%")
    //             ->orWhere('code', 'like', "%{$value}%");
    //         }
    //     })->where('created_by','admin')->limit(50)->get();
    //     return response()->json([
    //         'view'=>view('admin-views.coupon.partials._table',compact('coupons'))->render(),
    //         'count'=>$coupons->count()
    //     ]);
    // }
}
