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
        \Log::info('=== CheckOtp Middleware START ===', ['context' => $context, 'url' => $request->url()]);
        
        $user = Auth::user();
        
        if (!$user) {
            \Log::error('No authenticated user found');
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
            'user_email' => $user->email,
        ]);

        // Check if OTP is already verified for this context
        if ($request->session()->get($sessionKey) === true) {
            \Log::info('OTP already verified for context: ' . $context);
            return $next($request);
        }

        // OTP not verified, generate and send
        \Log::info('OTP not verified, generating new OTP');
        $request->session()->put('otp_context', $context);
        
        try {
            \Log::info('Calling OTP::generateOTP', ['user_id' => $user->id, 'context' => $context]);
            OTP::generateOTP($user, $context);
            \Log::info('OTP generated successfully for context: ' . $context);
        } catch (\Exception $e) {
            // If OTP email fails (SMTP error), log it and show error
            \Log::error('OTP generation failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            
            // Redirect back with error message
            return redirect()->route('dashboard')->with('error', 'Failed to send OTP email: ' . $e->getMessage());
        }
        
        \Log::info('Redirecting to OTP verify page');
        return redirect()->route('otp.verify');
    }
}
