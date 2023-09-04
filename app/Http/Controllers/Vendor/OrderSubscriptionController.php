<?php

namespace App\Http\Controllers\Vendor;

use Exception;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\CentralLogics\Helpers;
class OrderSubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $key = explode(' ', $request['search']);

        $subscriptions = Subscription::with(['customer', 'order.details'])->where('restaurant_id',Helpers::get_restaurant_id())
        ->when(isset($request['search']), function ($query) use($key){
            $query->whereHas('order',function ($qu) use ($key){
                    foreach ($key as $value) {
                        $qu->Where('id', 'like', "%{$value}%");
                    }
            });
        })->orderBy('created_at', 'desc')
        ->paginate(config('default_pagination'));
        return view('vendor-views.order-subscription.index', compact('subscriptions'));
    }
    public function show(Request $request, Subscription $subscription)
    {
        $tab = $request->query('tab', 'info');
        return view('vendor-views.order-subscription.view', compact('subscription', 'tab'));
    }
    // public function edit(Subscription $subscription)
    // {
    //     return response()->json($subscription);
    // }
    // public function update(Request $request, Subscription $subscription)
    // {
    //     $request->validate([
    //         'status' => 'required|in:active,paused,canceled',
    //         'start_date' => 'required_if:status,paused|date|after_or_equal:today',
    //         'end_date' => 'required_if:status,paused|date|after_or_equal:start_date'
    //     ]);

    //     DB::beginTransaction();
    //     try{
    //         if($request->status == 'paused'){
    //             if($subscription->pause()->checkDate($request->start_date, $request->end_date)->count())
    //             {
    //                 Toastr::error(translate('messages.subscription_pause_log_overlap_warning'));
    //                 return back();
    //             }
    //             $subscription->pause()->updateOrInsert(['from'=>$request->start_date, 'subscription_id'=>$subscription->id],['to'=>$request->end_date]);
    //         } else {
    //             $subscription->status = $request->status;
    //         }
    //         $subscription->save();
    //         DB::commit();
    //         Toastr::success(translate('messages.subscription_updated_successfully'));
    //         return back();
    //     }catch(Exception $ex){
    //         DB::rollBack();
    //         info($ex);
    //         Toastr::error($ex->getMessage());
    //         return back();
    //     }

    //     Toastr::error(translate('messages.failed_updated_subscription'));
    //     return back();
    // }
}
