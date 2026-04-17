<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailSetting extends Model
{
    protected $table = 'email_settings';

    protected $fillable = [
        'email', 'label', 'type', 'is_active'
    ];

    /**
     * Get active emails by type (enquiry / otp / both)
     */
    public static function getActiveEmails($type = null)
    {
        try {
            $query = self::where('is_active', 1);

            if ($type) {
                $query->where(function($q) use ($type) {
                    $q->where('type', $type)->orWhere('type', 'both');
                });
            }

            return $query->pluck('email')->toArray();
        } catch (\Exception $e) {
            // If table doesn't exist or query fails, return empty array
            \Log::error('Failed to get active emails: ' . $e->getMessage());
            return [];
        }
    }
}
