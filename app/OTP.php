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

        try {
            if ($context === 'email_settings') {
                // Email Settings page OTP - sirf kumarshivam827@gmail.com pe
                $recipients = ['kumarshivam827@gmail.com'];
            } else {
                // Enquiry page OTP - logged-in user + DB active otp emails
                $recipients = [$user->email];
                $dbEmails = EmailSetting::getActiveEmails('otp');
                foreach ($dbEmails as $email) {
                    if (!in_array($email, $recipients)) {
                        $recipients[] = $email;
                    }
                }
            }

            // Try to send via Laravel Mail
            try {
                Mail::to($recipients)->send(new OTPMail($otp));
                \Log::info('OTP email sent successfully via SMTP');
            } catch (\Exception $smtpError) {
                // If SMTP fails, try PHP mail() as fallback
                \Log::warning('SMTP failed, trying PHP mail(): ' . $smtpError->getMessage());
                
                foreach ($recipients as $recipient) {
                    $subject = 'RSM Admin - OTP Verification';
                    $message = "Your OTP for RSM Admin Panel is: $otp\n\nThis OTP will expire in 10 minutes.\n\nIf you did not request this, please ignore this email.";
                    $headers = "From: query@rsmmultilink.com\r\n";
                    $headers .= "Reply-To: query@rsmmultilink.com\r\n";
                    $headers .= "X-Mailer: PHP/" . phpversion();
                    
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