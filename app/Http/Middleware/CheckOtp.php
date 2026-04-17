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
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Session key alag hoga context ke hisaab se
        $sessionKey = 'otp_verified_' . $context;
        
        // Debug logging
        \Log::info('CheckOtp Middleware', [
            'context' => $context,
            'session_key' => $sessionKey,
            'is_verified' => $request->session()->get($sessionKey),
            'user_id' => $user->id,
        ]);

        // Check if OTP is already verified for this context
        if ($request->session()->get($sessionKey) === true) {
            \Log::info('OTP already verified for context: ' . $context);
            return $next($request);
        }

        // OTP not verified, generate and send
        $request->session()->put('otp_context', $context);
        
        try {
            OTP::generateOTP($user, $context);
            \Log::info('OTP generated successfully for context: ' . $context);
        } catch (\Exception $e) {
            // If OTP email fails (SMTP error), log it and show error
            \Log::error('OTP generation failed: ' . $e->getMessage());
            
            // Redirect back with error message
            return redirect()->route('dashboard')->with('error', 'Failed to send OTP email. Please contact administrator to fix email settings.');
        }
        
        return redirect()->route('otp.verify');
    }
}
