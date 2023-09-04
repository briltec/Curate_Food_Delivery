<?php

namespace App\Http\Controllers\Admin;

use App\Models\Currency;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BusinessSettingsController extends Controller
{

    private $restaurant;

    public function business_index()
    {
        return view('admin-views.business-settings.business-index');
    }

    public function business_setup(Request $request)

    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }
        // DB::table('business_settings')->updateOrInsert(['key' => 'max_otp_hit'], [
        //     'value' => $request['max_otp_hit']
        // ]);
        // DB::table('business_settings')->updateOrInsert(['key' => 'max_otp_hit_time'], [
        //     'value' => $request['max_otp_hit_time']
        // ]);
        // DB::table('business_settings')->updateOrInsert(['key' => 'otp_interval_time'], [
        //     'value' => $request['otp_interval_time']
        // ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'tax_included'], [
            'value' => $request['tax_included']
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'order_subscription'], [
            'value' => $request['order_subscription']
        ]);

        if($request['order_subscription']  == null ){
            Restaurant::query()->update([
                // 'order_subscription' => 0,
                'order_subscription_active' => 0,
            ]);
        }
        // DB::table('business_settings')->updateOrInsert(['key' => 'site_direction'], [
        //     'value' => $request['site_direction']
        // ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'business_name'], [
            'value' => $request['restaurant_name']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'currency'], [
            'value' => $request['currency']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'timezone'], [
            'value' => $request['timezone']
        ]);

        $curr_logo = BusinessSetting::where(['key' => 'logo'])->first();
        if ($request->has('logo')) {

            $validator = Validator::make($request->all(), [
                'logo' => 'required|max:2048',
            ]);
            if ($validator->fails()) {
            Toastr::error( translate('Image size must be within 2mb'));
            return back();
            }

            $image_name = Helpers::update('business/', $curr_logo->value, 'png', $request->file('logo'));
        } else {
            $image_name = $curr_logo['value'];
        }

        DB::table('business_settings')->updateOrInsert(['key' => 'logo'], [
            'value' => $image_name
        ]);

        $fav_icon = BusinessSetting::where(['key' => 'icon'])->first();
        if ($request->has('icon')) {
            $validator = Validator::make($request->all(), [
                'icon' => 'required|max:2048',
            ]);
            if ($validator->fails()) {
        Toastr::error( translate('Image size must be within 2mb'));
        return back();
            }
            $image_name = Helpers::update('business/', $fav_icon->value, 'png', $request->file('icon'));
        } else {
            $image_name = $fav_icon['value'];
        }

        DB::table('business_settings')->updateOrInsert(['key' => 'icon'], [
            'value' => $image_name
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'phone'], [
            'value' => $request['phone']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'email_address'], [
            'value' => $request['email']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'address'], [
            'value' => $request['address']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'footer_text'], [
            'value' => $request['footer_text']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'customer_verification'], [
            'value' => $request['customer_verification']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'order_delivery_verification'], [
            'value' => $request['odc']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'currency_symbol_position'], [
            'value' => $request['currency_symbol_position']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'schedule_order'], [
            'value' => $request['schedule_order']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'order_confirmation_model'], [
            'value' => $request['order_confirmation_model']
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'dm_tips_status'], [
            'value' => $request['dm_tips_status']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'tax'], [
            'value' => $request['tax']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'admin_commission'], [
            'value' => $request['admin_commission']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'country'], [
            'value' => $request['country']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'default_location'], [
            'value' => json_encode(['lat' => $request['latitude'], 'lng' => $request['longitude']])
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'admin_order_notification'], [
            'value' => $request['admin_order_notification']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'free_delivery_over'], [
            'value' => $request['free_delivery_over_status'] ? $request['free_delivery_over'] : null
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'dm_maximum_orders'], [
            'value' => $request['dm_maximum_orders']
        ]);

        // $languages = $request['language'];

        // if (in_array('en', $languages)) {
        //     unset($languages[array_search('en', $languages)]);
        // }
        // array_unshift($languages, 'en');

        // DB::table('business_settings')->updateOrInsert(['key' => 'language'], [
        //     'value' => json_encode($languages),
        // ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'timeformat'], [
            'value' => $request['time_format']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'canceled_by_restaurant'], [
            'value' => $request['canceled_by_restaurant']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'canceled_by_deliveryman'], [
            'value' => $request['canceled_by_deliveryman']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'show_dm_earning'], [
            'value' => $request['show_dm_earning']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'toggle_veg_non_veg'], [
            'value' => $request['vnv']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'toggle_dm_registration'], [
            'value' => $request['dm_self_registration']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'toggle_restaurant_registration'], [
            'value' => $request['restaurant_self_registration']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'schedule_order_slot_duration'], [
            'value' => $request['schedule_order_slot_duration']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'digit_after_decimal_point'], [
            'value' => $request['digit_after_decimal_point']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'delivery_charge_comission'], [
            'value' => $request['admin_comission_in_delivery_charge']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'dm_max_cash_in_hand'], [
            'value' => $request['dm_max_cash_in_hand']
        ]);

        if(!isset($request->commission) && !isset($request->subscription)){
            Toastr::error( translate('You_must_select_at_least_one_business_model_between_commission_and_subscription'));
            return back();
        }
        // For commission Model
        if (isset($request->commission) && !isset($request->subscription)) {
            DB::table('business_settings')->updateOrInsert(['key' => 'business_model'], [
                    'value' => json_encode(['commission' => 1, 'subscription' => 0 ])
                ]);
                $business_model= BusinessSetting::where('key', 'business_model')->first();
                $business_model = $business_model->value ? json_decode($business_model->value, true) : [];

            if ( $business_model && $business_model['subscription'] == 0 ){
                Restaurant::where('restaurant_model','unsubscribed')
                ->update(['restaurant_model' => 'commission',
                // 'status' => 1,
            ]);
            }
        }
        // For subscription model
            elseif(isset($request->subscription) && !isset($request->commission)) {
            DB::table('business_settings')->updateOrInsert(['key' => 'business_model'], [
                'value' => json_encode(['commission' =>  0, 'subscription' => 1 ])
            ]);
            $business_model= BusinessSetting::where('key', 'business_model')->first();
            $business_model = $business_model->value ? json_decode($business_model->value, true) : [];

            if ( $business_model && $business_model['commission'] == 0 ){
                Restaurant::where('restaurant_model','commission')
                ->update(['restaurant_model' => 'unsubscribed',
                'status' => 0,]);
            }

        } else {
            DB::table('business_settings')->updateOrInsert(['key' => 'business_model'], [
                'value' => json_encode(['commission' =>  1, 'subscription' => 1 ])
            ]);
        }




        Toastr::success( translate('Successfully updated. To see the changes in app restart the app.'));
        return back();
    }

    public function mail_index()
    {
        return view('admin-views.business-settings.mail-index');
    }

    public function mail_config(Request $request)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }
        BusinessSetting::updateOrInsert(
            ['key' => 'mail_config'],
            [
                'value' => json_encode([
                    "status" => $request['status'] ?? 0,
                    "name" => $request['name'],
                    "host" => $request['host'],
                    "driver" => $request['driver'],
                    "port" => $request['port'],
                    "username" => $request['username'],
                    "email_id" => $request['email'],
                    "encryption" => $request['encryption'],
                    "password" => $request['password']
                ]),
                'updated_at' => now()
            ]
        );
        Toastr::success(translate('messages.configuration_updated_successfully'));
        return back();
    }

    public function payment_index()
    {
        return view('admin-views.business-settings.payment-index');
    }

    public function payment_update(Request $request, $name)
    {

        if ($name == 'cash_on_delivery') {
            $payment = BusinessSetting::where('key', 'cash_on_delivery')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'key'        => 'cash_on_delivery',
                    'value'      => json_encode([
                        'status' => $request['status'],
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'cash_on_delivery'])->update([
                    'key'        => 'cash_on_delivery',
                    'value'      => json_encode([
                        'status' => $request['status'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'digital_payment') {
            $payment = BusinessSetting::where('key', 'digital_payment')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'key'        => 'digital_payment',
                    'value'      => json_encode([
                        'status' => $request['status'],
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'digital_payment'])->update([
                    'key'        => 'digital_payment',
                    'value'      => json_encode([
                        'status' => $request['status'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'ssl_commerz_payment') {
            $payment = BusinessSetting::where('key', 'ssl_commerz_payment')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'key'        => 'ssl_commerz_payment',
                    'value'      => json_encode([
                        'status'         => 1,
                        'store_id'       => '',
                        'store_password' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'ssl_commerz_payment'])->update([
                    'key'        => 'ssl_commerz_payment',
                    'value'      => json_encode([
                        'status'         => $request['status'],
                        'store_id'       => $request['store_id'],
                        'store_password' => $request['store_password'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'razor_pay') {
            $payment = BusinessSetting::where('key', 'razor_pay')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'key'        => 'razor_pay',
                    'value'      => json_encode([
                        'status'       => 1,
                        'razor_key'    => '',
                        'razor_secret' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'razor_pay'])->update([
                    'key'        => 'razor_pay',
                    'value'      => json_encode([
                        'status'       => $request['status'],
                        'razor_key'    => $request['razor_key'],
                        'razor_secret' => $request['razor_secret'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'paypal') {
            $payment = BusinessSetting::where('key', 'paypal')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'key'        => 'paypal',
                    'value'      => json_encode([
                        'status'           => 1,
                        'paypal_client_id' => '',
                        'paypal_secret'    => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'paypal'])->update([
                    'key'        => 'paypal',
                    'value'      => json_encode([
                        'status'           => $request['status'],
                        'paypal_client_id' => $request['paypal_client_id'],
                        'paypal_secret'    => $request['paypal_secret'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'stripe') {
            $payment = BusinessSetting::where('key', 'stripe')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'key'        => 'stripe',
                    'value'      => json_encode([
                        'status'        => 1,
                        'api_key'       => '',
                        'published_key' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'stripe'])->update([
                    'key'        => 'stripe',
                    'value'      => json_encode([
                        'status'        => $request['status'],
                        'api_key'       => $request['api_key'],
                        'published_key' => $request['published_key'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'senang_pay') {
            $payment = BusinessSetting::where('key', 'senang_pay')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([

                    'key'        => 'senang_pay',
                    'value'      => json_encode([
                        'status'        => 1,
                        'secret_key'    => '',
                        'published_key' => '',
                        'merchant_id' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'senang_pay'])->update([
                    'key'        => 'senang_pay',
                    'value'      => json_encode([
                        'status'        => $request['status'],
                        'secret_key'    => $request['secret_key'],
                        'published_key' => $request['publish_key'],
                        'merchant_id' => $request['merchant_id'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'paystack') {
            $payment = BusinessSetting::where('key', 'paystack')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'key'        => 'paystack',
                    'value'      => json_encode([
                        'status'        => 1,
                        'publicKey'     => '',
                        'secretKey'     => '',
                        'paymentUrl'    => '',
                        'merchantEmail' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'paystack'])->update([
                    'key'        => 'paystack',
                    'value'      => json_encode([
                        'status'        => $request['status'],
                        'publicKey'     => $request['publicKey'],
                        'secretKey'     => $request['secretKey'],
                        'paymentUrl'    => $request['paymentUrl'],
                        'merchantEmail' => $request['merchantEmail'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'flutterwave') {
            $payment = BusinessSetting::where('key', 'flutterwave')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'key'        => 'flutterwave',
                    'value'      => json_encode([
                        'status'        => 1,
                        'public_key'     => '',
                        'secret_key'     => '',
                        'hash'    => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'flutterwave'])->update([
                    'key'        => 'flutterwave',
                    'value'      => json_encode([
                        'status'        => $request['status'],
                        'public_key'     => $request['public_key'],
                        'secret_key'     => $request['secret_key'],
                        'hash'    => $request['hash'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'mercadopago') {
            $payment = BusinessSetting::updateOrInsert(
                ['key' => 'mercadopago'],
                [
                    'value'      => json_encode([
                        'status'        => $request['status'],
                        'public_key'     => $request['public_key'],
                        'access_token'     => $request['access_token'],
                    ]),
                    'updated_at' => now()
                ]
            );
        } elseif ($name == 'paymob_accept') {
            DB::table('business_settings')->updateOrInsert(['key' => 'paymob_accept'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'api_key' => $request['api_key'],
                    'iframe_id' => $request['iframe_id'],
                    'integration_id' => $request['integration_id'],
                    'hmac' => $request['hmac'],
                ]),
                'updated_at' => now()
            ]);
        } elseif ($name == 'liqpay') {
            DB::table('business_settings')->updateOrInsert(['key' => 'liqpay'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'public_key' => $request['public_key'],
                    'private_key' => $request['private_key']
                ]),
                'updated_at' => now()
            ]);
        } elseif ($name == 'paytm') {
            DB::table('business_settings')->updateOrInsert(['key' => 'paytm'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'paytm_merchant_key' => $request['paytm_merchant_key'],
                    'paytm_merchant_mid' => $request['paytm_merchant_mid'],
                    'paytm_merchant_website' => $request['paytm_merchant_website'],
                    'paytm_refund_url' => $request['paytm_refund_url'],
                ]),
                'updated_at' => now()
            ]);
        } elseif ($name == 'bkash') {
            DB::table('business_settings')->updateOrInsert(['key' => 'bkash'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'api_key' => $request['api_key'],
                    'api_secret' => $request['api_secret'],
                    'username' => $request['username'],
                    'password' => $request['password'],
                ]),
                'updated_at' => now()
            ]);
        } elseif ($name == 'paytabs') {
            DB::table('business_settings')->updateOrInsert(['key' => 'paytabs'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'profile_id' => $request['profile_id'],
                    'server_key' => $request['server_key'],
                    'base_url' => $request['base_url']
                ]),
                'updated_at' => now()
            ]);
        }

        Toastr::success(translate('messages.payment_settings_updated'));
        return back();
    }
    public function theme_settings()
    {
        return view('admin-views.business-settings.theme-settings');
    }
    public function update_theme_settings(Request $request)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }
        DB::table('business_settings')->updateOrInsert(['key' => 'theme'], [
            'value' => $request['theme']
        ]);
        Toastr::success(translate('theme_settings_updated'));
        return back();
    }

    public function app_settings()
    {
        return view('admin-views.business-settings.app-settings');
    }

    public function update_app_settings(Request $request)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }
        DB::table('business_settings')->updateOrInsert(['key' => 'app_minimum_version_android_restaurant'], [
            'value' => $request['app_minimum_version_android_restaurant']
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'app_url_android_restaurant'], [
            'value' => $request['app_url_android_restaurant']
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'app_minimum_version_ios_restaurant'], [
            'value' => $request['app_minimum_version_ios_restaurant']
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'app_url_ios_restaurant'], [
            'value' => $request['app_url_ios_restaurant']
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'app_minimum_version_android_deliveryman'], [
            'value' => $request['app_minimum_version_android_deliveryman']
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'app_url_android_deliveryman'], [
            'value' => $request['app_url_android_deliveryman']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'app_minimum_version_android'], [
            'value' => $request['app_minimum_version_android']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'app_minimum_version_ios'], [
            'value' => $request['app_minimum_version_ios']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'app_url_android'], [
            'value' => $request['app_url_android']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'app_url_ios'], [
            'value' => $request['app_url_ios']
        ]);
        Toastr::success(translate('messages.app_settings_updated'));
        return back();
    }

    public function landing_page_settings($tab)
    {
        if ($tab == 'index') {
            return view('admin-views.business-settings.landing-page-settings.index');
        } else if ($tab == 'links') {
            return view('admin-views.business-settings.landing-page-settings.links');
        } else if ($tab == 'speciality') {
            return view('admin-views.business-settings.landing-page-settings.speciality');
        } else if ($tab == 'testimonial') {
            return view('admin-views.business-settings.landing-page-settings.testimonial');
        } else if ($tab == 'feature') {
            return view('admin-views.business-settings.landing-page-settings.feature');
        } else if ($tab == 'image') {
            return view('admin-views.business-settings.landing-page-settings.image');
        } else if ($tab == 'backgroundChange') {
            return view('admin-views.business-settings.landing-page-settings.backgroundChange');
        }  else if ($tab == 'react') {
            return view('admin-views.business-settings.landing-page-settings.react');
        } else if ($tab == 'react-feature') {
            return view('admin-views.business-settings.landing-page-settings.react_feature');
        } else if ($tab == 'platform-order') {
            return view('admin-views.business-settings.landing-page-settings.our_platform');
        } else if ($tab == 'platform-restaurant') {
            return view('admin-views.business-settings.landing-page-settings.restaurant_platform');
        } else if ($tab == 'platform-delivery') {
            return view('admin-views.business-settings.landing-page-settings.delivery_platform');
        } else if ($tab == 'react-half-banner') {
            return view('admin-views.business-settings.landing-page-settings.react_half_banner');
        }
    }

    public function update_landing_page_settings(Request $request, $tab)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }

        if ($tab == 'text') {
            DB::table('business_settings')->updateOrInsert(['key' => 'landing_page_text'], [
                'value' => json_encode([
                    'header_title_1' => $request['header_title_1'],
                    'header_title_2' => $request['header_title_2'],
                    'header_title_3' => $request['header_title_3'],
                    'about_title' => $request['about_title'],
                    'why_choose_us' => $request['why_choose_us'],
                    'why_choose_us_title' => $request['why_choose_us_title'],
                    'testimonial_title' => $request['testimonial_title'],
                    'mobile_app_section_heading' => $request['mobile_app_section_heading'],
                    'mobile_app_section_text' => $request['mobile_app_section_text'],
                    'feature_section_description' => $request['feature_section_description'],
                    'feature_section_title' => $request['feature_section_title'],
                    'footer_article' => $request['footer_article'],

                    'join_us_title' => $request['join_us_title'],
                    'join_us_sub_title' => $request['join_us_sub_title'],
                    'join_us_article' => $request['join_us_article'],
                    'our_platform_title' => $request['our_platform_title'],
                    'our_platform_article' => $request['our_platform_article'],
                    'newsletter_title' => $request['newsletter_title'],
                    'newsletter_article' => $request['newsletter_article'],
                ])
            ]);
            Toastr::success(translate('messages.landing_page_text_updated'));
        } else if ($tab == 'links') {
            DB::table('business_settings')->updateOrInsert(['key' => 'landing_page_links'], [
                'value' => json_encode([
                    'app_url_android_status' => $request['app_url_android_status'],
                    'app_url_android' => $request['app_url_android'],
                    'app_url_ios_status' => $request['app_url_ios_status'],
                    'app_url_ios' => $request['app_url_ios'],
                    'web_app_url_status' => $request['web_app_url_status'],
                    'web_app_url' => $request['web_app_url'],
                    'order_now_url_status' => $request['order_now_url_status'],
                    'order_now_url' => $request['order_now_url']
                ])
            ]);
            Toastr::success(translate('messages.landing_page_links_updated'));
        } else if ($tab == 'speciality') {
            $data = [];
            $imageName = null;
            $speciality = BusinessSetting::where('key', 'speciality')->first();
            if ($speciality) {
                $data = json_decode($speciality->value, true);
            }
            if ($request->has('image')) {
                $validator = Validator::make($request->all(), [
                    'image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                }

                $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                $request->image->move(public_path('assets/landing/image'), $imageName);
            }
            array_push($data, [
                'img' => $imageName,
                'title' => $request->speciality_title
            ]);

            DB::table('business_settings')->updateOrInsert(['key' => 'speciality'], [
                'value' => json_encode($data)
            ]);
            Toastr::success(translate('messages.landing_page_speciality_updated'));
        } else if ($tab == 'feature') {
            $data = [];
            $imageName = null;
            $feature = BusinessSetting::where('key', 'feature')->first();
            if ($feature) {
                $data = json_decode($feature->value, true);
            }
            if ($request->has('image')) {
                $validator = Validator::make($request->all(), [
                    'image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                }
                $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                $request->image->move(public_path('assets/landing/image'), $imageName);
            }
            array_push($data, [
                'img' => $imageName,
                'title' => $request->feature_title,
                'feature_description' => $request->feature_description
            ]);

            DB::table('business_settings')->updateOrInsert(['key' => 'feature'], [
                'value' => json_encode($data)
            ]);
            Toastr::success(translate('messages.landing_page_feature_updated'));
        }
         else if ($tab == 'testimonial') {
            $data = [];
            $imageName = null;
            $speciality = BusinessSetting::where('key', 'testimonial')->first();
            if ($speciality) {
                $data = json_decode($speciality->value, true);
            }
            if ($request->has('image')) {
                $validator = Validator::make($request->all(), [
                    'image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                }
                $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                $request->image->move(public_path('assets/landing/image'), $imageName);
            }
            array_push($data, [
                'img' => $imageName,
                'name' => $request->reviewer_name,
                'position' => $request->reviewer_designation,
                'detail' => $request->review,
            ]);

            DB::table('business_settings')->updateOrInsert(['key' => 'testimonial'], [
                'value' => json_encode($data)
            ]);
            Toastr::success(translate('messages.landing_page_testimonial_updated'));
        }
        else if ($tab == 'image') {
            $data = [];
            $images = BusinessSetting::where('key', 'landing_page_images')->first();
            if ($images) {
                $data = json_decode($images->value, true);
            }
            if ($request->has('top_content_image')) {
                $validator = Validator::make($request->all(), [
                    'top_content_image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                }
                $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                $request->top_content_image->move(public_path('assets/landing/image'), $imageName);
                $data['top_content_image'] = $imageName;
            }
            if ($request->has('about_us_image')) {
                $validator = Validator::make($request->all(), [
                    'about_us_image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                 }
                $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                $request->about_us_image->move(public_path('assets/landing/image'), $imageName);
                $data['about_us_image'] = $imageName;
            }

            if ($request->has('feature_section_image')) {
                $validator = Validator::make($request->all(), [
                    'feature_section_image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                    }
                $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                $request->feature_section_image->move(public_path('assets/landing/image'), $imageName);
                $data['feature_section_image'] = $imageName;
            }
            if ($request->has('mobile_app_section_image')) {
                $validator = Validator::make($request->all(), [
                    'mobile_app_section_image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                }
                $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                $request->mobile_app_section_image->move(public_path('assets/landing/image'), $imageName);
                $data['mobile_app_section_image'] = $imageName;
            }
            DB::table('business_settings')->updateOrInsert(['key' => 'landing_page_images'], [
                'value' => json_encode($data)
            ]);
            Toastr::success(translate('messages.landing_page_image_updated'));
        } else if ($tab == 'background-change') {
            DB::table('business_settings')->updateOrInsert(['key' => 'backgroundChange'], [
                // 'value' => json_encode([
                //     'header-bg' => $request['header-bg'],
                //     'footer-bg' => $request['footer-bg'],
                //     'landing-page-bg' => $request['landing-page-bg']
                // ])
                'value' => json_encode([
                    'primary_1_hex' => $request['header-bg'],
                    'primary_1_rgb' => Helpers::hex_to_rbg($request['header-bg']),
                    'primary_2_hex' => $request['footer-bg'],
                    'primary_2_rgb' => Helpers::hex_to_rbg($request['footer-bg']),
                ])
            ]);
            Toastr::success(translate('messages.background_updated'));
        } else if ($tab == 'react_header') {
            $data = null;
            $image = BusinessSetting::where('key', 'react_header_banner')->first();
            if ($image) {
                $data = $image->value;
            }
            $image_name =$data ?? \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
            if ($request->has('react_header_banner')) {
                // $image_name = ;
                $validator = Validator::make($request->all(), [
                    'react_header_banner' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                }
                $data = Helpers::update('react_landing/', $image_name, 'png', $request->file('react_header_banner')) ?? null;
            }
            DB::table('business_settings')->updateOrInsert(['key' => 'react_header_banner'], [
                'value' => $data
            ]);
            Toastr::success(translate('Landing page header banner updated'));
        } else if ($tab == 'full-banner') {
            $data = [];
            $banner_section_full = BusinessSetting::where('key','banner_section_full')->first();
            $imageName = null;
            if($banner_section_full){
                $data = json_decode($banner_section_full->value, true);
                $imageName =$data['banner_section_img_full'] ?? null;
            }
            if ($request->has('banner_section_img_full'))   {
                $validator = Validator::make($request->all(), [
                    'banner_section_img_full' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                }

                if (empty($imageName)) {
                    $imageName = Helpers::upload('react_landing/', 'png', $request->file('banner_section_img_full'));
                    }  else{
                    $imageName= Helpers::update('react_landing/', $data['banner_section_img_full'], 'png', $request->file('banner_section_img_full')) ;
                    }
            }
            $data = [
                'banner_section_img_full' => $imageName,
                'full_banner_section_title' => $request->full_banner_section_title ?? $banner_section_full['full_banner_section_title'] ,
                'full_banner_section_sub_title' => $request->full_banner_section_sub_title ?? $banner_section_full['full_banner_section_sub_title'],
            ];
            DB::table('business_settings')->updateOrInsert(['key' => 'banner_section_full'], [
                'value' => json_encode($data)
            ]);
            Toastr::success(translate('messages.landing_page_banner_section_updated'));
        } else if ($tab == 'discount-banner') {
            $data = [];
            $discount_banner = BusinessSetting::where('key','discount_banner')->first();
            $imageName = null;
            if($discount_banner){
                $data = json_decode($discount_banner->value, true);
                $imageName =$data['img'] ?? null;
            }
            if ($request->has('img'))   {
                $validator = Validator::make($request->all(), [
                    'img' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                }
                if (empty($imageName)) {
                    $imageName = Helpers::upload('react_landing/', 'png', $request->file('img'));
                    }  else{
                    $imageName= Helpers::update('react_landing/', $data['img'], 'png', $request->file('img')) ;
                    }
            }
            $data = [
                'img' => $imageName,
                'title' => $request->title ?? $discount_banner['title'] ,
                'sub_title' => $request->sub_title ?? $discount_banner['sub_title'],
            ];
            DB::table('business_settings')->updateOrInsert(['key' => 'discount_banner'], [
                'value' => json_encode($data)
            ]);
            Toastr::success(translate('messages.landing_page_discount_banner_section_updated'));
        } else if ($tab == 'banner-section-half') {
            $data = [];
            $imageName = null;
            $banner_section_half = BusinessSetting::where('key', 'banner_section_half')->first();
            if ($banner_section_half) {
                $data = json_decode($banner_section_half->value, true);
            }

            if ($request->has('image')) {
                $validator = Validator::make($request->all(), [
                    'image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                }
                $imageName=Helpers::upload('react_landing/','png', $request->file('image')) ;
            }
            array_push($data, [
                'img' => $imageName,
                'title' => $request->title,
                'sub_title' => $request->sub_title
            ]);

            DB::table('business_settings')->updateOrInsert(['key' => 'banner_section_half'], [
                'value' => json_encode($data)
            ]);
            Toastr::success(translate('messages.landing_page_banner_section_updated'));
        } else if ($tab == 'app_section_image') {
            $data = null;
            $image = BusinessSetting::where('key', 'app_section_image')->first();
            if ($image) {
                $data = $image->value;
            }
            $image_name =$data ?? \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
            if ($request->has('app_section_image')) {
                $validator = Validator::make($request->all(), [
                    'app_section_image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                }
                $data = Helpers::update('react_landing/', $image_name, 'png', $request->file('app_section_image')) ?? null;
            }
            DB::table('business_settings')->updateOrInsert(['key' => 'app_section_image'], [
                'value' => $data
            ]);
            Toastr::success(translate('App section image updated'));
        } else if ($tab == 'footer_logo') {
            $data = null;
            $image = BusinessSetting::where('key', 'footer_logo')->first();
            if ($image) {
                $data = $image->value;
            }
            $image_name =$data ?? \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
            if ($request->has('footer_logo')) {
                $validator = Validator::make($request->all(), [
                    'footer_logo' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                }
                $data = Helpers::update('react_landing/', $image_name, 'png', $request->file('footer_logo')) ?? null;
            }
            DB::table('business_settings')->updateOrInsert(['key' => 'footer_logo'], [
                'value' => $data
            ]);
            Toastr::success(translate('Footer logo updated'));
        }  else if ($tab == 'react-feature') {
            $data = [];
            $imageName = null;
            $feature = BusinessSetting::where('key', 'react_feature')->first();
            if ($feature) {
                $data = json_decode($feature->value, true);
            }
            if ($request->has('image')) {
                $validator = Validator::make($request->all(), [
                    'image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                }
                $imageName=Helpers::upload('react_landing/feature/','png', $request->file('image')) ;
            }
            array_push($data, [
                'img' => $imageName,
                'title' => $request->feature_title,
                'feature_description' => $request->feature_description
            ]);

            DB::table('business_settings')->updateOrInsert(['key' => 'react_feature'], [
                'value' => json_encode($data)
            ]);
            Toastr::success(translate('messages.landing_page_feature_updated'));
        } else if ($tab == 'platform-main') {

            if($request->button == 'restaurant_platform'){
                $data = [];
                $imageName = null;
                $restaurant_platform = BusinessSetting::where('key', 'restaurant_platform')->first();
                if ($restaurant_platform) {
                    $data = json_decode($restaurant_platform->value, true);
                    $imageName = $data['image'] ?? null;
                }

                $image_name =$data['image'] ?? \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                if ($request->has('image')) {
                    $validator = Validator::make($request->all(), [
                        'image' => 'required|max:2048',
                    ]);
                    if ($validator->fails()) {
                    Toastr::error( translate('Image size must be within 2mb'));
                    return back();
                    }

                    $imageName  = Helpers::update('landing/', $image_name, 'png', $request->file('image')) ?? null;
                }

                $data= [
                    'image' => $imageName,
                    'title' => $request->title,
                    'url' => $request->url,
                    'url_status' => $request->url_status ?? 0,
                ];

                DB::table('business_settings')->updateOrInsert(['key' => 'restaurant_platform'], [
                    'value' => json_encode($data)
                ]);
            }
            if($request->button == 'order_platform'){

                $data = [];
                $imageName = null;
                $order_platform = BusinessSetting::where('key', 'order_platform')->first();
                if ($order_platform) {
                    $data = json_decode($order_platform->value, true);
                    $imageName = $data['image'] ?? null;
                }
                $image_name =$data['image'] ?? \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                if ($request->has('image')) {
                    $validator = Validator::make($request->all(), [
                        'image' => 'required|max:2048',
                    ]);
                    if ($validator->fails()) {
                    Toastr::error( translate('Image size must be within 2mb'));
                    return back();
                    }
                    $imageName  = Helpers::update('landing/', $image_name, 'png', $request->file('image')) ?? null;
                }
                $data= [
                    'image' => $imageName,
                    'title' => $request->title,
                    'url' => $request->url,
                    'url_status' => $request->url_status ?? 0,
                ];

                DB::table('business_settings')->updateOrInsert(['key' => 'order_platform'], [
                    'value' => json_encode($data)
                ]);
            }
            if($request->button == 'delivery_platform'){
                // dd($request->all());
                $data = [];
                $imageName = null;
                $delivery_platform = BusinessSetting::where('key', 'delivery_platform')->first();
                if ($delivery_platform) {
                    $data = json_decode($delivery_platform->value, true);
                    $imageName = $data['image'] ?? null;
                }
                $image_name =$data['image'] ?? \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                if ($request->has('image')) {
                    $validator = Validator::make($request->all(), [
                        'image' => 'required|max:2048',
                    ]);
                    if ($validator->fails()) {
                    Toastr::error( translate('Image size must be within 2mb'));
                    return back();
                    }
                    $imageName  = Helpers::update('landing/', $image_name, 'png', $request->file('image')) ?? null;
                }
                $data= [
                    'image' => $imageName,
                    'title' => $request->title,
                    // 'sub_title' => $request->sub_title,
                    // 'detail' => $request->detail,
                    'url' => $request->url,
                    'url_status' => $request->url_status ?? 0,
                ];

                DB::table('business_settings')->updateOrInsert(['key' => 'delivery_platform'], [
                    'value' => json_encode($data)
                ]);
            }

            Toastr::success(translate('messages.landing_page_our_platform_updated'));
        }


        else if ($tab == 'platform-data') {
            if($request->button == 'platform_order_data'){
                $data = [];
                $imageName = null;
                $platform_order_data = BusinessSetting::where('key', 'platform_order_data')->first();
                if ($platform_order_data) {
                    $data = json_decode($platform_order_data->value, true);
                }
                array_push($data, [
                    'title' => $request->title,
                    'detail' => $request->detail,
                ]);
                DB::table('business_settings')->updateOrInsert(['key' => 'platform_order_data'], [
                    'value' => json_encode($data)
                ]);
                Toastr::success(translate('messages.landing_page_order_platform_data_added'));
            }
            if($request->button == 'platform_restaurant_data'){
                $data = [];
                $imageName = null;
                $platform_restaurant_data = BusinessSetting::where('key', 'platform_restaurant_data')->first();
                if ($platform_restaurant_data) {
                    $data = json_decode($platform_restaurant_data->value, true);
                }
                array_push($data, [
                    'title' => $request->title,
                    'detail' => $request->detail,
                ]);
                DB::table('business_settings')->updateOrInsert(['key' => 'platform_restaurant_data'], [
                    'value' => json_encode($data)
                ]);
                Toastr::success(translate('messages.landing_page_restaurant_platform_data_added'));
            }
            if($request->button == 'platform_delivery_data'){
                $data = [];
                $imageName = null;
                $platform_delivery_data = BusinessSetting::where('key', 'platform_delivery_data')->first();
                if ($platform_delivery_data) {
                    $data = json_decode($platform_delivery_data->value, true);
                }
                array_push($data, [
                    'title' => $request->title,
                    'detail' => $request->detail,
                ]);
                DB::table('business_settings')->updateOrInsert(['key' => 'platform_delivery_data'], [
                    'value' => json_encode($data)
                ]);
                Toastr::success(translate('messages.landing_page_delivary_platform_data_updated'));
            }

        }

        return back();
    }

    public function delete_landing_page_settings($tab, $key)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }
        $item = BusinessSetting::where('key', $tab)->first();
        $data = $item ? json_decode($item->value, true) : null;
        if ($data && array_key_exists($key, $data)) {
            if($tab == 'react_feature' && isset($data[$key]['img']) && Storage::disk('public')->exists('react_landing/feature/'. $data[$key]['img'])){
                Storage::disk('public')->delete('react_landing/feature/'. $data[$key]['img']);
            }
            if ( $tab != 'react_feature' && isset($data[$key]['img']) && file_exists(public_path('assets/landing/image') . $data[$key]['img'])) {
                unlink(public_path('assets/landing/image') . $data[$key]['img']);
            }

            array_splice($data, $key, 1);

            $item->value = json_encode($data);
            $item->save();
            Toastr::success(translate('messages.' . $tab) . ' ' . translate('messages.deleted'));
            return back();
        }
        Toastr::error(translate('messages.not_found'));
        return back();

    }

    public function currency_index()
    {
        return view('admin-views.business-settings.currency-index');
    }

    public function currency_store(Request $request)
    {
        $request->validate([
            'currency_code' => 'required|unique:currencies',
        ]);

        Currency::create([
            "country" => $request['country'],
            "currency_code" => $request['currency_code'],
            "currency_symbol" => $request['symbol'],
            "exchange_rate" => $request['exchange_rate'],
        ]);
        Toastr::success(translate('messages.currency_added_successfully'));
        return back();
    }

    public function currency_edit($id)
    {
        $currency = Currency::find($id);
        return view('admin-views.business-settings.currency-update', compact('currency'));
    }

    public function currency_update(Request $request, $id)
    {
        Currency::where(['id' => $id])->update([
            "country" => $request['country'],
            "currency_code" => $request['currency_code'],
            "currency_symbol" => $request['symbol'],
            "exchange_rate" => $request['exchange_rate'],
        ]);
        Toastr::success(translate('messages.currency_updated_successfully'));
        return redirect('restaurant-panel/business-settings/currency-add');
    }

    public function currency_delete($id)
    {
        Currency::where(['id' => $id])->delete();
        Toastr::success(translate('messages.currency_deleted_successfully'));
        return back();
    }

    public function terms_and_conditions()
    {
        $tnc = BusinessSetting::where(['key' => 'terms_and_conditions'])->first();
        if ($tnc == false) {
            BusinessSetting::insert([
                'key' => 'terms_and_conditions',
                'value' => ''
            ]);
        }
        return view('admin-views.business-settings.terms-and-conditions', compact('tnc'));
    }

    public function terms_and_conditions_update(Request $request)
    {
        BusinessSetting::where(['key' => 'terms_and_conditions'])->update([
            'value' => $request->tnc
        ]);

        Toastr::success(translate('messages.terms_and_condition_updated'));
        return back();
    }

    public function privacy_policy()
    {
        $data = BusinessSetting::where(['key' => 'privacy_policy'])->first();
        if ($data == false) {
            $data = [
                'key' => 'privacy_policy',
                'value' => '',
            ];
            BusinessSetting::insert($data);
        }
        return view('admin-views.business-settings.privacy-policy', compact('data'));
    }

    public function privacy_policy_update(Request $request)
    {
        BusinessSetting::where(['key' => 'privacy_policy'])->update([
            'value' => $request->privacy_policy,
        ]);

        Toastr::success(translate('messages.privacy_policy_updated'));
        return back();
    }

    public function refund_policy()
    {
        $data = BusinessSetting::where(['key' => 'refund_policy'])->first();
        if ($data == false) {

            $values= [
                'data' => '',
                'status' => 0,
            ];
            DB::table('business_settings')->updateOrInsert(['key' => 'refund_policy'], [
                'value' => json_encode($values)
            ]);
        }
        $data = json_decode(BusinessSetting::where(['key' => 'refund_policy'])->first()->value,true);
        return view('admin-views.business-settings.refund_policy', compact('data'));
    }

    public function refund_policy_update(Request $request)
    {
        $data = json_decode(BusinessSetting::where(['key' => 'refund_policy'])->first()->value,true);
        $values= [
            'data' => $request->refund_policy,
            'status' => $data['status'],
        ];
        BusinessSetting::where(['key' => 'refund_policy'])->update([
            'value' => $values,
        ]);
        Toastr::success(translate('messages.refund_policy_updated'));
        return back();
    }
    public function refund_policy_status($status)
    {
        $data = json_decode(BusinessSetting::where(['key' => 'refund_policy'])->first()->value,true);
        $values= [
            'data' => $data['data'],
            'status' => $status ,
        ];
        BusinessSetting::where(['key' => 'refund_policy'])->update([
            'value' => $values,
        ]);

        return response()->json(['status'=>"changed"]);
    }

    public function shipping_policy()
    {
        $data = BusinessSetting::where(['key' => 'shipping_policy'])->first();
        if ($data == false) {

            $values= [
                'data' => '',
                'status' => 0,
            ];
            DB::table('business_settings')->updateOrInsert(['key' => 'shipping_policy'], [
                'value' => json_encode($values)
            ]);
        }
        $data = json_decode(BusinessSetting::where(['key' => 'shipping_policy'])->first()->value,true);
        return view('admin-views.business-settings.shipping_policy', compact('data'));
    }

    public function shipping_policy_update(Request $request)
    {
        $data = json_decode(BusinessSetting::where(['key' => 'shipping_policy'])->first()->value,true);
        $values= [
            'data' => $request->shipping_policy,
            'status' => $data['status'],
        ];
        BusinessSetting::where(['key' => 'shipping_policy'])->update([
            'value' => $values,
        ]);
        Toastr::success(translate('messages.shipping_policy_updated'));
        return back();
    }


    public function shipping_policy_status($status)
    {
        $data = json_decode(BusinessSetting::where(['key' => 'shipping_policy'])->first()->value,true);
        $values= [
            'data' => $data['data'],
            'status' => $status,
        ];
        BusinessSetting::where(['key' => 'shipping_policy'])->update([
            'value' => $values,
        ]);
        return response()->json(['status'=>"changed"]);

    }

    public function cancellation_policy()
    {
        $data = BusinessSetting::where(['key' => 'cancellation_policy'])->first();
        if ($data == false) {
            $values= [
                'data' => '',
                'status' => 0,
            ];
            DB::table('business_settings')->updateOrInsert(['key' => 'cancellation_policy'], [
                'value' => json_encode($values)
            ]);
        }
        $data = json_decode(BusinessSetting::where(['key' => 'cancellation_policy'])->first()->value,true);
        return view('admin-views.business-settings.cancellation_policy', compact('data'));
    }

    public function cancellation_policy_update(Request $request)
    {
        $data = json_decode(BusinessSetting::where(['key' => 'cancellation_policy'])->first()->value,true);
        $values= [
            'data' => $request->cancellation_policy,
            'status' => $data['status'],
        ];
        BusinessSetting::where(['key' => 'cancellation_policy'])->update([
            'value' => $values,
        ]);
        Toastr::success(translate('messages.cancellation_policy_updated'));
        return back();
    }

    public function cancellation_policy_status($status)
    {
        $data = json_decode(BusinessSetting::where(['key' => 'cancellation_policy'])->first()->value,true);
        $values= [
            'data' => $data['data'],
            'status' => $status,
        ];
        BusinessSetting::where(['key' => 'cancellation_policy'])->update([
            'value' => $values,
        ]);
        return response()->json(['status'=>"changed"]);
    }

    public function about_us()
    {
        $data = BusinessSetting::where(['key' => 'about_us'])->first();
        if ($data == false) {
            $data = [
                'key' => 'about_us',
                'value' => '',
            ];
            BusinessSetting::insert($data);
        }
        return view('admin-views.business-settings.about-us', compact('data'));
    }

    public function about_us_update(Request $request)
    {
        BusinessSetting::where(['key' => 'about_us'])->update([
            'value' => $request->about_us,
        ]);

        Toastr::success(translate('messages.about_us_updated'));
        return back();
    }

    public function fcm_index()
    {
        $fcm_credentials = Helpers::get_business_settings('fcm_credentials');
        return view('admin-views.business-settings.fcm-index', compact('fcm_credentials'));
    }

    public function update_fcm(Request $request)
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'fcm_project_id'], [
            'value' => $request['projectId']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'push_notification_key'], [
            'value' => $request['push_notification_key']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'fcm_credentials'], [
            'value' => json_encode([
                'apiKey'=> $request->apiKey,
                'authDomain'=> $request->authDomain,
                'projectId'=> $request->projectId,
                'storageBucket'=> $request->storageBucket,
                'messagingSenderId'=> $request->messagingSenderId,
                'appId'=> $request->appId,
                'measurementId'=> $request->measurementId
            ])
        ]);
        Toastr::success(translate('messages.settings_updated'));
        return back();
    }

    public function update_fcm_messages(Request $request)
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'order_pending_message'], [
            'value' => json_encode([
                'status' => $request['pending_status'] == 1 ? 1 : 0,
                'message' => $request['pending_message']
            ])
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'order_confirmation_msg'], [
            'value' => json_encode([
                'status' => $request['confirm_status'] == 1 ? 1 : 0,
                'message' => $request['confirm_message']
            ])
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'order_processing_message'], [
            'value' => json_encode([
                'status' => $request['processing_status'] == 1 ? 1 : 0,
                'message' => $request['processing_message']
            ])
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'out_for_delivery_message'], [
            'value' => json_encode([
                'status' => $request['out_for_delivery_status'] == 1 ? 1 : 0,
                'message' => $request['out_for_delivery_message']
            ])
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'order_delivered_message'], [
            'value' => json_encode([
                'status' => $request['delivered_status'] == 1 ? 1 : 0,
                'message' => $request['delivered_message']
            ])
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'delivery_boy_assign_message'], [
            'value' => json_encode([
                'status' => $request['delivery_boy_assign_status'] == 1 ? 1 : 0,
                'message' => $request['delivery_boy_assign_message']
            ])
        ]);

        // DB::table('business_settings')->updateOrInsert(['key' => 'delivery_boy_start_message'], [
        //     'value' => json_encode([
        //         'status' => $request['delivery_boy_start_status'] == 1 ? 1 : 0,
        //         'message' => $request['delivery_boy_start_message']
        //     ])
        // ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'delivery_boy_delivered_message'], [
            'value' => json_encode([
                'status' => $request['delivery_boy_delivered_status'] == 1 ? 1 : 0,
                'message' => $request['delivery_boy_delivered_message']
            ])
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'order_handover_message'], [
            'value' => json_encode([
                'status' => $request['order_handover_message_status'] == 1 ? 1 : 0,
                'message' => $request['order_handover_message']
            ])
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'order_cancled_message'], [
            'value' => json_encode([
                'status' => $request['order_cancled_message_status'] == 1 ? 1 : 0,
                'message' => $request['order_cancled_message']
            ])
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'order_refunded_message'], [
            'value' => json_encode([
                'status' => $request['order_refunded_message_status'] == 1 ? 1 : 0,
                'message' => $request['order_refunded_message']
            ])
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'refund_cancel_message'], [
            'value' => json_encode([
                'status' => $request['refund_cancel_message_status'] == 1 ? 1 : 0,
                'message' => $request['refund_cancel_message']
            ])
        ]);

        Toastr::success(translate('messages.message_updated'));
        return back();
    }


    public function location_index()
    {
        return view('admin-views.business-settings.location-index');
    }

    public function location_setup(Request $request)
    {
        $restaurant = Helpers::get_restaurant_id();
        $restaurant->latitude = $request['latitude'];
        $restaurant->longitude = $request['longitude'];
        $restaurant->save();

        Toastr::success(translate('messages.settings_updated'));
        return back();
    }

    public function config_setup()
    {
        return view('admin-views.business-settings.config');
    }

    public function config_update(Request $request)
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'map_api_key'], [
            'value' => $request['map_api_key']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'map_api_key_server'], [
            'value' => $request['map_api_key_server']
        ]);

        Toastr::success(translate('messages.config_data_updated'));
        return back();
    }

    public function toggle_settings($key, $value)
    {
        DB::table('business_settings')->updateOrInsert(['key' => $key], [
            'value' => $value
        ]);

        Toastr::success(translate('messages.app_settings_updated'));
        return back();
    }

    public function viewSocialLogin()
    {
        $data = BusinessSetting::where('key', 'social_login')->first();
        if(! $data){
            Helpers::insert_business_settings_key('social_login','[{"login_medium":"google","client_id":"","client_secret":"","status":"0"},{"login_medium":"facebook","client_id":"","client_secret":"","status":""}]');
            $data = BusinessSetting::where('key', 'social_login')->first();
        }
        $apple = BusinessSetting::where('key', 'apple_login')->first();
        if (!$apple) {
            Helpers::insert_business_settings_key('apple_login', '[{"login_medium":"apple","client_id":"","client_secret":"","team_id":"","key_id":"","service_file":"","redirect_url":"","status":""}]');
            $apple = BusinessSetting::where('key', 'apple_login')->first();
        }
        $appleLoginServices = json_decode($apple->value, true);
        $socialLoginServices = json_decode($data->value, true);
        return view('admin-views.business-settings.social-login.view', compact('socialLoginServices','appleLoginServices'));
    }

    public function updateSocialLogin($service, Request $request)
    {
        $socialLogin = BusinessSetting::where('key', 'social_login')->first();
        $credential_array = [];
        foreach (json_decode($socialLogin['value'], true) as $key => $data) {
            if ($data['login_medium'] == $service) {
                $cred = [
                    'login_medium' => $service,
                    'client_id' => $request['client_id'],
                    'client_secret' => $request['client_secret'],
                    'status' => $request['status'],
                ];
                array_push($credential_array, $cred);
            } else {
                array_push($credential_array, $data);
            }
        }
        BusinessSetting::where('key', 'social_login')->update([
            'value' => $credential_array
        ]);

        Toastr::success(translate('messages.credential_updated', ['service' => $service]));
        return redirect()->back();
    }
    public function updateAppleLogin($service, Request $request)
    {
        $appleLogin = BusinessSetting::where('key', 'apple_login')->first();
        $credential_array = [];
        if($request->hasfile('service_file')){
            $fileName = Helpers::upload('apple-login/', 'p8', $request->file('service_file'));
        }
        foreach (json_decode($appleLogin['value'], true) as $key => $data) {
            if ($data['login_medium'] == $service) {
                $cred = [
                    'login_medium' => $service,
                    'client_id' => $request['client_id'],
                    'client_secret' => $request['client_secret'],
                    'status' => $request['status'],
                    'team_id' => $request['team_id'],
                    'key_id' => $request['key_id'],
                    'service_file' => isset($fileName)?$fileName:$data['service_file'],
                    'redirect_url' => $request['redirect_url'],
                ];
                array_push($credential_array, $cred);
            } else {
                array_push($credential_array, $data);
            }
        }
        BusinessSetting::where('key', 'apple_login')->update([
            'value' => $credential_array
        ]);

        Toastr::success(translate('messages.credential_updated', ['service' => $service]));
        return redirect()->back();
    }

    //recaptcha
    public function recaptcha_index(Request $request)
    {
        return view('admin-views.business-settings.recaptcha-index');
    }

    public function recaptcha_update(Request $request)
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'recaptcha'], [
            'key' => 'recaptcha',
            'value' => json_encode([
                'status' => $request['status'],
                'site_key' => $request['site_key'],
                'secret_key' => $request['secret_key']
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Toastr::success(translate('messages.updated_successfully'));
        return back();
    }

    public function send_mail(Request $request)
    {
        $response_flag = 0;
        try {

            Mail::to($request->email)->send(new \App\Mail\TestEmailSender());
            $response_flag = 1;
        } catch (\Exception $exception) {
            info($exception);
            $response_flag = 2;
        }

        return response()->json(['success' => $response_flag]);
    }

    public function react_setup()
    {
        Helpers::react_domain_status_check();
        return view('admin-views.business-settings.react-setup');
    }

    public function react_update(Request $request)
    {
        $request->validate([
            'react_license_code'=>'required',
            'react_domain'=>'required'
        ],[
            'react_license_code.required'=>translate('messages.license_code_is_required'),
            'react_domain.required'=>translate('messages.doamain_is_required'),
        ]);
        if(Helpers::activation_submit($request['react_license_code'])){
            DB::table('business_settings')->updateOrInsert(['key' => 'react_setup'], [
                'value' => json_encode([
                    'status'=>1,
                    'react_license_code'=>$request['react_license_code'],
                    'react_domain'=>$request['react_domain'],
                    'react_platform' => 'codecanyon'
                ])
            ]);

            Toastr::success(translate('messages.react_data_updated'));
            return back();
        }
        elseif(Helpers::react_activation_check($request->react_domain, $request->react_license_code)){

            DB::table('business_settings')->updateOrInsert(['key' => 'react_setup'], [
                'value' => json_encode([
                    'status'=>1,
                    'react_license_code'=>$request['react_license_code'],
                    'react_domain'=>$request['react_domain'],
                    'react_platform' => 'iss'
                ])
            ]);

            Toastr::success(translate('messages.react_data_updated'));
            return back();
        }
        Toastr::error(translate('messages.Invalid_license_code_or_unregistered_domain'));
        return back()->withInput(['invalid-data'=>true]);
    }


    public function site_direction(Request $request){
        if (env('APP_MODE') == 'demo') {
            session()->put('site_direction', ($request->status == 1?'ltr':'rtl'));
            return response()->json();
        }
        if($request->status == 1){
            DB::table('business_settings')->updateOrInsert(['key' => 'site_direction'], [
                'value' => 'ltr'
            ]);
        } else
        {
            DB::table('business_settings')->updateOrInsert(['key' => 'site_direction'], [
                'value' => 'rtl'
            ]);
        }
        return ;
    }
}
