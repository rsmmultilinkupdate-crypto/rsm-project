<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\OTP;

class OtpController extends Controller
{
    public function showVerifyForm()
    {
        return view('auth.otp');
    }

    public function verifyOTP(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);

        $user = Auth::user();
        $otp  = OTP::where('user_id', $user->id)->latest('id')->first();

        if ($otp && $otp->otp == $request->otp) {

            // Context ke hisaab se session set karo
            $context    = $request->session()->get('otp_context', 'enquiry');
            $sessionKey = 'otp_verified_' . $context;
            $request->session()->put($sessionKey, true);
            $request->session()->forget('otp_context');

            // Context ke hisaab se redirect
            if ($context === 'email_settings') {
                return redirect()->route('email-settings.index');
            }

            return redirect()->action('Admin\EnqueryController@index');
        }

        return back()->withErrors(['otp' => 'Invalid OTP or OTP expired']);
    }
}
