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
            
            try {
                OTP::generateOTP($user, $context);
                return redirect()->route('otp.verify');
            } catch (\Exception $e) {
                // If OTP email fails (SMTP error), log it but allow access
                \Log::error('OTP generation failed: ' . $e->getMessage());
                
                // Set session as verified to bypass OTP for this request
                $request->session()->put($sessionKey, true);
                
                // Show warning message
                $request->session()->flash('warning', 'OTP email could not be sent. Please check email settings.');
            }
        }

        return $next($request);
    }
}
