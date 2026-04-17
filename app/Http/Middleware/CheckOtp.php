<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\OTP;

class CheckOtp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string    $context  'enquiry' | 'email_settings'
     * @return mixed
     */
    public function handle($request, Closure $next, $context = 'enquiry')
    {
        $user = Auth::user();

        $otp = OTP::where('user_id', $user->id)->latest()->first();

        // Session key alag hoga context ke hisaab se
        $sessionKey = 'otp_verified_' . $context;

        if (!$otp || $request->session()->get($sessionKey) !== true) {
            // Context session mein store karo taaki verify ke baad sahi redirect ho
            $request->session()->put('otp_context', $context);
            OTP::generateOTP($user, $context);
            return redirect()->route('otp.verify');
        }

        return $next($request);
    }
}
