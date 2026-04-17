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

        Mail::to($recipients)->send(new OTPMail($otp));

        return $otp;
    }
}