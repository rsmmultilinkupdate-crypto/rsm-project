<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTPMail;
use App\EmailSetting;
use App\EmailLog;

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
            // Get recipients from database based on context
            $recipients = [];
            
            if ($context === 'email_settings') {
                // Email Settings page - only OTP type emails
                $recipients = EmailSetting::getActiveEmails('otp');
            } else {
                // Enquiry page - OTP type emails
                $recipients = EmailSetting::getActiveEmails('otp');
            }
            
            // If no emails found in database, log error
            if (empty($recipients)) {
                \Log::error('No active email addresses found in email_settings table for OTP delivery');
                throw new \Exception('No email addresses configured for OTP delivery. Please add emails in Email Settings.');
            }
            
            \Log::info('OTP Recipients', ['context' => $context, 'recipients' => $recipients]);

            // Try to send via Laravel Mail with proper SMTP
            try {
                Mail::to($recipients)->send(new OTPMail($otp));
                \Log::info('OTP email sent successfully via SMTP', ['recipients' => $recipients]);
                
                // Log success for each recipient
                foreach ($recipients as $recipient) {
                    EmailLog::logEmail($recipient, 'RSM Admin - OTP Verification', "OTP: $otp", 'otp', 'sent', 'smtp');
                }
            } catch (\Exception $smtpError) {
                // If SMTP fails, try PHP mail() as fallback
                \Log::warning('SMTP failed, trying PHP mail()', ['error' => $smtpError->getMessage()]);
                
                $successCount = 0;
                $failCount = 0;
                
                foreach ($recipients as $recipient) {
                    $subject = 'RSM Admin - OTP Verification';
                    $message = "Your OTP for RSM Admin Panel is: $otp\n\n";
                    $message .= "This OTP will expire in 10 minutes.\n\n";
                    $message .= "Context: " . ucfirst($context) . "\n\n";
                    $message .= "If you did not request this, please ignore this email.\n\n";
                    $message .= "---\nRSM Multilink\nwww.rsmmultilink.com";
                    
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
                        \Log::info("OTP sent via PHP mail()", ['recipient' => $recipient, 'otp' => $otp]);
                        EmailLog::logEmail($recipient, $subject, $message, 'otp', 'sent', 'php_mail');
                        $successCount++;
                    } else {
                        \Log::error("Failed to send OTP via PHP mail()", ['recipient' => $recipient]);
                        EmailLog::logEmail($recipient, $subject, $message, 'otp', 'failed', 'php_mail', 'PHP mail() returned false');
                        $failCount++;
                    }
                }
                
                \Log::info('PHP mail() summary', [
                    'success' => $successCount,
                    'failed' => $failCount,
                    'total' => count($recipients)
                ]);
                
                if ($successCount === 0) {
                    throw new \Exception('Failed to send OTP email to any recipient');
                }
            }
        } catch (\Exception $e) {
            // Log error and re-throw
            \Log::error('OTP generation failed', ['error' => $e->getMessage()]);
            throw $e;
        }

        return $otp;
    }
}