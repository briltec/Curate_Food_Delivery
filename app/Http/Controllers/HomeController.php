<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Models\ContactMessage;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        /*$this->middleware('auth');*/
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home-v2');
    }

    public function terms_and_conditions()
    {
        $data = self::get_settings('terms_and_conditions');
        return view('terms-and-conditions',compact('data'));
    }

    public function about_us()
    {
        $data = self::get_settings('about_us');
        return view('about-us',compact('data'));
    }

    public function contact_us(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email:filter',
                'message' => 'required',
            ],[
                'name.required' => translate('messages.Name is required!'),
                'email.required' => translate('messages.Email is required!'),
                'email.filter' => translate('messages.Must ba a valid email!'),
                'message.required' => translate('messages.Message is required!'),
            ]);

            $email = Helpers::get_settings('email_address');
            $messageData = [
                'name' => $request->name,
                'email' => $request->email,
                'message' => $request->message,
            ];
            ContactMessage::create($messageData);

            $business_name=Helpers::get_settings('business_name') ?? 'Stackfood';
            $subject='Enquiry from '.$business_name;
            try{
                if(config('mail.status')) {
                    Mail::to($email)->send(new ContactMail($messageData,$subject));
                    Toastr::success(translate('messages.Thanks_for_your_enquiry._We_will_get_back_to_you_soon.'));
                }
            }catch(\Exception $ex)
            {
                info($ex);
            }
            return back();
        }
        return view('contact-us');
    }

    public function privacy_policy()
    {
        $data = self::get_settings('privacy_policy');
        return view('privacy-policy',compact('data'));
    }

    public function refund_policy()
    {
        $data = self::get_settings('refund_policy');
        abort_if($data['status'] == 0 ,404);
        return view('refund_policy',compact('data'));
    }

    public function shipping_policy()
    {
        $data = self::get_settings('shipping_policy');
        abort_if($data['status'] == 0 ,404);
        return view('shipping_policy',compact('data'));
    }

    public function cancellation_policy()
    {
        $data = self::get_settings('cancellation_policy');
        abort_if($data['status'] == 0 ,404);
        return view('cancellation_policy',compact('data'));
    }

    public static function get_settings($name)
    {
        $config = null;
        $data = BusinessSetting::where(['key' => $name])->first();
        if (isset($data)) {            
            $config = json_decode($data['value'], true);
            if (is_null($config)) {
                $config = $data['value'];
            }
        }
        return $config;
    }


    public function lang($local)
    {
        $direction = BusinessSetting::where('key', 'site_direction')->first();
        $direction = $direction->value ?? 'ltr';
        $language = BusinessSetting::where('key', 'system_language')->first();
        foreach (json_decode($language['value'], true) as $key => $data) {
            if ($data['code'] == $local) {
                $direction = isset($data['direction']) ? $data['direction'] : 'ltr';
            }
        }
        session()->forget('landing_language_settings');
        Helpers::landing_language_load();
        session()->put('landing_site_direction', $direction);
        session()->put('landing_local', $local);
        return redirect()->back();
    }
}
