<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Zone;
use App\Models\Vehicle;
use App\Models\Currency;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Grimzy\LaravelMysqlSpatial\Types\Point;

class ConfigController extends Controller
{
    private $map_api_key;

    function __construct()
    {
        $map_api_key_server = BusinessSetting::where(['key' => 'map_api_key_server'])->first();
        $map_api_key_server = $map_api_key_server ? $map_api_key_server->value : null;
        $this->map_api_key = $map_api_key_server;
    }

    public function configuration()
    {
        $key = [
            'cash_on_delivery', 'digital_payment', 'default_location', 'free_delivery_over', 'business_name', 'logo', 'address', 'phone', 'email_address', 'country', 'currency_symbol_position', 'app_minimum_version_android',
            'app_url_android', 'app_minimum_version_ios', 'app_url_ios', 'customer_verification', 'order_delivery_verification', 'terms_and_conditions', 'privacy_policy', 'about_us', 'maintenance_mode', 'popular_food', 'popular_restaurant', 'new_restaurant', 'most_reviewed_foods', 'show_dm_earning', 'canceled_by_deliveryman', 'canceled_by_restaurant', 'timeformat', 'toggle_veg_non_veg', 'toggle_dm_registration', 'toggle_restaurant_registration', 'schedule_order_slot_duration',
            'loyalty_point_exchange_rate', 'loyalty_point_item_purchase_point', 'loyalty_point_status', 'loyalty_point_minimum_point', 'wallet_status', 'schedule_order', 'dm_tips_status', 'ref_earning_status', 'ref_earning_exchange_rate', 'theme','business_model','admin_commission','footer_text' ,'icon','refund_active_status',
            'refund_policy','shipping_policy','cancellation_policy','free_trial_period','app_minimum_version_android_restaurant',
            'app_url_android_restaurant','app_minimum_version_ios_restaurant','app_url_ios_restaurant','app_minimum_version_android_deliveryman','tax_included','order_subscription',
            'app_url_android_deliveryman',
        ];
        $social_login = [];
        $social_login_data=Helpers::get_business_settings('social_login') ?? [];
        foreach ($social_login_data as $social) {
            $config = [
                'login_medium' => $social['login_medium'],
                'status' => (boolean)$social['status']
            ];
            array_push($social_login, $config);
        }

        $settings =  array_column(BusinessSetting::whereIn('key', $key)->get()->toArray(), 'value', 'key');
        $currency_symbol = Currency::where(['currency_code' => Helpers::currency_code()])->first()->currency_symbol;
        $cod = json_decode($settings['cash_on_delivery'], true);
        $business_plan = isset($settings['business_model']) ? json_decode($settings['business_model'], true) : [
            'commission'        =>  1,
            'subscription'     =>  0,
        ];

        $digital_payment = json_decode($settings['digital_payment'], true);

        $default_location = isset($settings['default_location']) ? json_decode($settings['default_location'], true) : 0;
        $free_delivery_over = $settings['free_delivery_over'];
        $free_delivery_over = $free_delivery_over ? (float)$free_delivery_over : $free_delivery_over;
        $languages = Helpers::get_business_settings('language');
        $lang_array = [];
        foreach ($languages as $language) {
            array_push($lang_array, [
                'key' => $language,
                'value' => Helpers::get_language_name($language)
            ]);
        }


        $apple_login = [];
        $apples = Helpers::get_business_settings('apple_login');
        if(isset($apples)){
            foreach (Helpers::get_business_settings('apple_login') as $apple) {
                $config = [
                    'login_medium' => $apple['login_medium'],
                    'status' => (boolean)$apple['status'],
                    'client_id' => $apple['client_id']
                ];
                array_push($apple_login, $config);
            }
        }

        return response()->json([
            'business_name' => $settings['business_name'],
            'logo' => $settings['logo'],
            'address' => $settings['address'],
            'phone' => $settings['phone'],
            'email' => $settings['email_address'],
            'base_urls' => [
                'product_image_url' => asset('storage/app/public/product'),
                'customer_image_url' => asset('storage/app/public/profile'),
                'banner_image_url' => asset('storage/app/public/banner'),
                'category_image_url' => asset('storage/app/public/category'),
                'cuisine_image_url' => asset('storage/app/public/cuisine'),
                'review_image_url' => asset('storage/app/public/review'),
                'notification_image_url' => asset('storage/app/public/notification'),
                'restaurant_image_url' => asset('storage/app/public/restaurant'),
                'vendor_image_url' => asset('storage/app/public/vendor'),
                'restaurant_cover_photo_url' => asset('storage/app/public/restaurant/cover'),
                'delivery_man_image_url' => asset('storage/app/public/delivery-man'),
                'chat_image_url' => asset('storage/app/public/conversation'),
                'campaign_image_url' => asset('storage/app/public/campaign'),
                'business_logo_url' => asset('storage/app/public/business'),
                'react_landing_page_images' => asset('storage/app/public/react_landing') ,
                'react_landing_page_feature_images' => asset('storage/app/public/react_landing/feature') ,
                'refund_image_url' => asset('storage/app/public/refund'),
            ],
            'country' => $settings['country'],
            'default_location' => ['lat' => $default_location ? $default_location['lat'] : '23.757989', 'lng' => $default_location ? $default_location['lng'] : '90.360587'],
            'currency_symbol' => $currency_symbol,
            'currency_symbol_direction' => $settings['currency_symbol_position'],
            'app_minimum_version_android' => (float)$settings['app_minimum_version_android'],
            'app_url_android' => $settings['app_url_android'],
            'app_minimum_version_ios' => (float)$settings['app_minimum_version_ios'],
            'app_url_ios' => $settings['app_url_ios'],
            'customer_verification' => (bool)$settings['customer_verification'],
            'schedule_order' => (bool)$settings['schedule_order'],
            'order_delivery_verification' => (bool)$settings['order_delivery_verification'],
            'cash_on_delivery' => (bool)($cod['status'] == 1 ? true : false),
            'digital_payment' => (bool)($digital_payment['status'] == 1 ? true : false),
            'terms_and_conditions' => $settings['terms_and_conditions'],
            'privacy_policy' => $settings['privacy_policy'],
            'about_us' => $settings['about_us'],
            'free_delivery_over' => $free_delivery_over,
            'demo' => (bool)(env('APP_MODE') == 'demo' ? true : false),
            'maintenance_mode' => (bool)Helpers::get_business_settings('maintenance_mode') ?? 0,
            'order_confirmation_model' => config('order_confirmation_model'),
            'popular_food' => (float)$settings['popular_food'],
            'popular_restaurant' => (float)$settings['popular_restaurant'],
            'new_restaurant' => (float)$settings['new_restaurant'],
            'most_reviewed_foods' => (float)$settings['most_reviewed_foods'],
            'show_dm_earning' => (bool)$settings['show_dm_earning'],
            'canceled_by_deliveryman' => (bool)$settings['canceled_by_deliveryman'],
            'canceled_by_restaurant' => (bool)$settings['canceled_by_restaurant'],
            'timeformat' => (string)$settings['timeformat'],
            'language' => $lang_array,
            'toggle_veg_non_veg' => (bool)$settings['toggle_veg_non_veg'],
            'toggle_dm_registration' => (bool)$settings['toggle_dm_registration'],
            'toggle_restaurant_registration' => (bool)$settings['toggle_restaurant_registration'],
            'schedule_order_slot_duration' => (int)$settings['schedule_order_slot_duration'],
            'digit_after_decimal_point' => (int)config('round_up_to_digit'),
            'loyalty_point_exchange_rate' => (int)(isset($settings['loyalty_point_item_purchase_point']) ? $settings['loyalty_point_exchange_rate'] : 0),
            'loyalty_point_item_purchase_point' => (float)(isset($settings['loyalty_point_item_purchase_point']) ? $settings['loyalty_point_item_purchase_point'] : 0.0),
            'loyalty_point_status' => (int)(isset($settings['loyalty_point_status']) ? $settings['loyalty_point_status'] : 0),
            'minimum_point_to_transfer' => (int)(isset($settings['loyalty_point_minimum_point']) ? $settings['loyalty_point_minimum_point'] : 0),
            'customer_wallet_status' => (int)(isset($settings['wallet_status']) ? $settings['wallet_status'] : 0),
            'ref_earning_status' => (int)(isset($settings['ref_earning_status']) ? $settings['ref_earning_status'] : 0),
            'ref_earning_exchange_rate' => (double)(isset($settings['ref_earning_exchange_rate']) ? $settings['ref_earning_exchange_rate'] : 0),
            'dm_tips_status' => (int)(isset($settings['dm_tips_status']) ? $settings['dm_tips_status'] : 0),
            'theme' => (int)$settings['theme'],
            'social_media'=>SocialMedia::active()->get()->toArray(),
            'social_login' => $social_login,
            'business_plan' => $business_plan,
            'admin_commission' => (float)(isset($settings['admin_commission']) ? $settings['admin_commission'] : 0),
            'footer_text' => $settings['footer_text'],
            'fav_icon' => $settings['icon'],
            'refund_active_status' => (bool)(isset($settings['refund_active_status']) ? $settings['refund_active_status'] : 0),
            'refund_policy_status' => (int)(isset($settings['refund_policy']) ? json_decode($settings['refund_policy'], true)['status'] : 0),
            'refund_policy_data' =>(isset($settings['refund_policy']) ? json_decode($settings['refund_policy'], true)['data'] : null),
            'cancellation_policy_status' => (int)(isset($settings['cancellation_policy']) ? json_decode($settings['cancellation_policy'], true)['status'] : 0),
            'cancellation_policy_data' => (isset($settings['cancellation_policy']) ? json_decode($settings['cancellation_policy'], true)['data'] : null),
            'shipping_policy_status' => (int)(isset($settings['shipping_policy']) ? json_decode($settings['shipping_policy'], true)['status'] : 0),
            'shipping_policy_data' => (isset($settings['shipping_policy']) ? json_decode($settings['shipping_policy'], true)['data'] : null),
            'free_trial_period_status' => (int)(isset($settings['free_trial_period']) ? json_decode($settings['free_trial_period'], true)['status'] : 0),
            'free_trial_period_data' =>  (int)(isset($settings['free_trial_period']) ? json_decode($settings['free_trial_period'], true)['data'] : 0),

            'app_minimum_version_android_restaurant' => (float)(isset($settings['app_minimum_version_android_restaurant']) ? $settings['app_minimum_version_android_restaurant'] : 0),
            'app_url_android_restaurant' => (isset($settings['app_url_android_restaurant']) ? $settings['app_url_android_restaurant'] : null),
            'app_minimum_version_ios_restaurant' => (float)(isset($settings['app_minimum_version_ios_restaurant']) ? $settings['app_minimum_version_ios_restaurant'] : 0),
            'app_url_ios_restaurant' => (isset($settings['app_url_ios_restaurant']) ? $settings['app_url_ios_restaurant'] : null),
            'app_minimum_version_android_deliveryman' => (float)(isset($settings['app_minimum_version_android_deliveryman']) ? $settings['app_minimum_version_android_deliveryman'] : 0),
            'app_url_android_deliveryman' => (isset($settings['app_url_android_deliveryman']) ? $settings['app_url_android_deliveryman'] : null),
            'tax_included' => (int)(isset($settings['tax_included']) ? $settings['tax_included'] : 0),
            'apple_login' => $apple_login,
            'order_subscription' => (int)(isset($settings['order_subscription']) ? $settings['order_subscription'] : 0),
        ]);
    }

    public function get_zone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $point = new Point($request->lat, $request->lng);
        $zones = Zone::contains('coordinates', $point)->latest()->get(['id', 'status', 'minimum_shipping_charge',
        'increased_delivery_fee','increased_delivery_fee_status','increase_delivery_charge_message','per_km_shipping_charge','max_cod_order_amount','maximum_shipping_charge']);
        if (count($zones) < 1) {
            return response()->json([
                'errors' => [
                    ['code' => 'coordinates', 'message' => translate('messages.service_not_available_in_this_area')]
                ]
            ], 404);
        }
        $data = array_filter($zones->toArray(), function ($zone) {
            if ($zone['status'] == 1) {
                return $zone;
            }
        });

        if (count($data) > 0) {
            return response()->json(['zone_id' => json_encode(array_column($data, 'id')), 'zone_data'=>array_values($data)], 200);
        }

        return response()->json([
            'errors' => [
                ['code' => 'coordinates', 'message' => translate('messages.we_are_temporarily_unavailable_in_this_area')]
            ]
        ], 403);
    }

    public function place_api_autocomplete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search_text' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $response = Http::get('https://maps.googleapis.com/maps/api/place/autocomplete/json?input=' . $request['search_text'] . '&key=' . $this->map_api_key);
        return $response->json();
    }


    public function distance_api(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'origin_lat' => 'required',
            'origin_lng' => 'required',
            'destination_lat' => 'required',
            'destination_lng' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json?origins=' . $request['origin_lat'] . ',' . $request['origin_lng'] . '&destinations=' . $request['destination_lat'] . ',' . $request['destination_lng'] . '&key=' . $this->map_api_key . '&mode=walking');
        return $response->json();
    }


    public function place_api_details(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'placeid' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $response = Http::get('https://maps.googleapis.com/maps/api/place/details/json?placeid=' . $request['placeid'] . '&key=' . $this->map_api_key);
        return $response->json();
    }

    public function geocode_api(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $request->lat . ',' . $request->lng . '&key=' . $this->map_api_key);
        return $response->json();
    }

    public function landing_page(){
        $key =['react_header_banner','banner_section_full','banner_section_half' ,'footer_logo','app_section_image',
        'react_feature' ,'discount_banner','landing_page_links'];
        $settings =  array_column(BusinessSetting::whereIn('key', $key)->get()->toArray(), 'value', 'key');
        return  response()->json(
            [
                'react_header_banner'=>(isset($settings['react_header_banner']) )  ? $settings['react_header_banner'] : null ,
                'app_section_image'=> (isset($settings['app_section_image'])) ? $settings['app_section_image']  : null,
                'footer_logo'=> (isset($settings['footer_logo'])) ? $settings['footer_logo'] : null,
                'banner_section_full'=> (isset($settings['banner_section_full']) )  ? json_decode($settings['banner_section_full'], true) : null ,
                'banner_section_half'=>(isset($settings['banner_section_half']) )  ? json_decode($settings['banner_section_half'], true) : [],
                'react_feature'=> (isset($settings['react_feature'])) ? json_decode($settings['react_feature'], true) : [],
                'discount_banner'=> (isset($settings['discount_banner'])) ? json_decode($settings['discount_banner'], true) : null,
                'landing_page_links'=> (isset($settings['landing_page_links'])) ? json_decode($settings['landing_page_links'], true) : null,
        ]);
    }


    public function extra_charge(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'distance' => 'required',
        ]);
        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $distance_data = $request->distance ?? 0;
        $data =  Vehicle::active()->
        where(function ($query) use ($distance_data) {
            $query->where('starting_coverage_area', '<=', $distance_data)->where('maximum_coverage_area', '>=', $distance_data)
            ->orWhere(function ($query) use ($distance_data) {
                $query->where('starting_coverage_area', '>=', $distance_data);
            });
        })
            ->orderBy('starting_coverage_area')->first();
            $extra_charges = (float) (isset($data) ? $data->extra_charges  : 0);
        return response()->json($extra_charges,200);
    }

    public function get_vehicles(Request $request){
        $data = Vehicle::active()->get(['id','type']);
        return response()->json($data, 200);
    }


}
