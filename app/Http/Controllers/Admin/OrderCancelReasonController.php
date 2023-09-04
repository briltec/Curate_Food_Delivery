<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\OrderCancelReason;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class OrderCancelReasonController extends Controller
{
    public function index()
    {
        $reasons = OrderCancelReason::latest()->paginate(config('default_pagination'));
        return view('admin-views.order.cancelation-reason', compact('reasons'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'reason'=>'required|max:255',
            'user_type' =>'required|max:50',
        ]);
        $cancelReason = new OrderCancelReason();
        $cancelReason->reason = $request->reason;
        $cancelReason->user_type=$request->user_type;
        $cancelReason->created_at = now();
        $cancelReason->updated_at = now();
        $cancelReason->save();

        Toastr::success(translate('messages.order_cancellation_reason_added_successfully'));
        return back();
    }
    public function destroy($cancelReason)
    {
        $cancelReason = OrderCancelReason::findOrFail($cancelReason);
        $cancelReason->delete();
        Toastr::success(translate('messages.order_cancellation_reason_deleted_successfully'));
        return back();
    }

    public function status(Request $request)
    {
        $cancelReason = OrderCancelReason::findOrFail($request->id);
        $cancelReason->status = $request->status;
        $cancelReason->save();
        Toastr::success(translate('messages.status_updated'));
        return back();
    }
    public function update(Request $request)
    {
        $request->validate([
            'reason' => 'required|max:255',
            'user_type' =>'required|max:50',
        ]);
        $cancelReason = OrderCancelReason::findOrFail($request->reason_id);
        $cancelReason->reason = $request->reason;
        $cancelReason->user_type=$request->user_type;
        $cancelReason->save();

        Toastr::success(translate('order_cancellation_reason_updated_successfully'));
        return back();
    }
}
