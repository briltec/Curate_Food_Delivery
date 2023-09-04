<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\ItemCampaign;
use App\CentralLogics\Helpers;
use Brian2694\Toastr\Facades\Toastr;


class CampaignController extends Controller
{
    function list()
    {
        $campaigns=Campaign::with('restaurants')->running()->active()->latest()->paginate(config('default_pagination'));
        return view('vendor-views.campaign.list',compact('campaigns'));
    }
    function itemlist()
    {
        $campaigns=ItemCampaign::where('restaurant_id', Helpers::get_restaurant_id())->latest()->paginate(config('default_pagination'));
        return view('vendor-views.campaign.food_list',compact('campaigns'));
    }

    public function remove_restaurant(Campaign $campaign, $restaurant)
    {
        $campaign->restaurants()->detach($restaurant);
        $campaign->save();
        Toastr::success(translate('messages.restaurant_remove_from_campaign'));
        return back();
    }
    public function addrestaurant(Campaign $campaign, $restaurant)
    {
        $campaign->restaurants()->attach($restaurant, ['campaign_status' => 'pending']);
        $campaign->save();
        Toastr::success(translate('messages.restaurant_added_to_campaign'));
        return back();
    }

    public function search(Request $request){
        $key = explode(' ', $request['search']);
        $campaigns=Campaign::
        where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('title', 'like', "%{$value}%");
            }
        })->limit(50)->get();
        return response()->json([
            'view'=>view('vendor-views.campaign.partials._table',compact('campaigns'))->render()
        ]);
    }

    public function searchItem(Request $request){
        $key = explode(' ', $request['search']);
        $campaigns=ItemCampaign::where('restaurant_id', Helpers::get_restaurant_id())->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('title', 'like', "%{$value}%");
            }
        })->limit(50)->get();
        return response()->json([
            'view'=>view('vendor-views.campaign.partials._item_table',compact('campaigns'))->render(),
            'count'=>$campaigns->count(),
        ]);
    }

}
