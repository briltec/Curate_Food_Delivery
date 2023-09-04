<?php

namespace App\Http\Controllers\Vendor\Auth;

use App\Models\Vendor;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Models\SubscriptionPackage;
use Gregwar\Captcha\CaptchaBuilder;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Http;
use App\Models\RestaurantSubscription;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;


class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:vendor', ['except' => 'logout']);
    }

    public function login()
    {
        $custome_recaptcha = new CaptchaBuilder;
        $custome_recaptcha->build();
        Session::put('six_captcha', $custome_recaptcha->getPhrase());
        return view('vendor-views.auth.login', compact('custome_recaptcha'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $recaptcha = Helpers::get_business_settings('recaptcha');
        if (isset($recaptcha) && $recaptcha['status'] == 1) {
            $request->validate([
                'g-recaptcha-response' => [
                    function ($attribute, $value, $fail) {
                        $secret_key = Helpers::get_business_settings('recaptcha')['secret_key'];
                        $response = $value;
                        $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $response;
                        $response = Http::get($url);
                        $response = $response->json();
                        if (!isset($response['success']) || !$response['success']) {
                            $fail(translate('messages.ReCAPTCHA Failed'));
                        }
                    },
                ],
            ]);
        } else if(session('six_captcha') != $request->custome_recaptcha)
        {
            Toastr::error(translate('messages.ReCAPTCHA Failed'));
            return back();
        }

        $vendor = Vendor::where('email', $request->email)->first();
        if($vendor)
        {
            if( $vendor->restaurants[0]->restaurant_model == 'none')
            {
                $admin_commission= BusinessSetting::where('key','admin_commission')->first();
                $business_name= BusinessSetting::where('key','business_name')->first();
                $packages= SubscriptionPackage::where('status',1)->get();
                    return view('vendor-views.auth.register-step-2',[
                        'restaurant_id' => $vendor->restaurants[0]->id,
                        'packages' =>$packages,
                        'business_name' =>$business_name->value,
                        'admin_commission' =>$admin_commission->value,
                    ]);
            }

            if($vendor->restaurants[0]->status == 0 &&  $vendor->status == 0)
            {
                return redirect()->back()->withInput($request->only('email', 'remember'))
            ->withErrors([translate('messages.inactive_vendor_warning')]);
            }
        }
        if (auth('vendor')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            return redirect()->route('vendor.dashboard');
        }

        return redirect()->back()->withInput($request->only('email', 'remember'))
            ->withErrors([translate('messages.credentials_does_not_match')]);
    }

    public function logout(Request $request)
    {
        auth()->guard('vendor')->logout();
        return redirect()->route('vendor.auth.login');
    }
}
