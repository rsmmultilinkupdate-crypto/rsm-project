<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTPMail;
use App\EmailSetting;

class OTP extends Model
{
    protected $table = 'o_t_p_s';
    protected $fillable = ['user_id', 'otp', 'expires_at'];

    /**
     * Generate OTP and send email.
     * 
     * @param  \App\User  $user
     * @param  string     $context  'enquiry' | 'email_settings'
     */
    public static function generateOTP($user, $context = 'enquiry')
    {
        $otp = rand(100000, 999999);
        $expiresAt = now()->addMinutes(10);

        self::insert([
            'user_id'    => $user->id,
            'otp'        => $otp,
            'expires_at' => $expiresAt,
        ]);

        // Always send to these two emails only
        $recipients = [
            'rsmmultilinkenquiry@gmail.com',
            'kumarshivam827@gmail.com'
        ];

        try {
            // Try to send via Laravel Mail
            try {
                Mail::to($recipients)->send(new OTPMail($otp));
                \Log::info('OTP email sent successfully via SMTP to: ' . implode(', ', $recipients));
            } catch (\Exception $smtpError) {
                // If SMTP fails, try PHP mail() as fallback
                \Log::warning('SMTP failed, trying PHP mail(): ' . $smtpError->getMessage());
                
                foreach ($recipients as $recipient) {
                    $subject = 'RSM Admin - OTP Verification';
                    $message = "Your OTP for RSM Admin Panel is: $otp\n\nThis OTP will expire in 10 minutes.\n\nIf you did not request this, please ignore this email.";
                    
                    // Proper headers for better deliverability
                    $headers = "From: RSM Admin <noreply@rsmmultilink.com>\r\n";
                    $headers .= "Reply-To: noreply@rsmmultilink.com\r\n";
                    $headers .= "Return-Path: noreply@rsmmultilink.com\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
                    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
                    $headers .= "X-Priority: 1\r\n";
                    $headers .= "Importance: High\r\n";
                    
                    if (mail($recipient, $subject, $message, $headers)) {
                        \Log::info("OTP sent via PHP mail() to: $recipient");
                    } else {
                        \Log::error("Failed to send OTP via PHP mail() to: $recipient");
                    }
                }
            }
        } catch (\Exception $e) {
            // Log error but don't throw exception
            \Log::error('Failed to send OTP email: ' . $e->getMessage());
            throw $e; // Re-throw to be caught by middleware
        }

        return $otp;
    }
}