<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailRecipient extends Model
{
    protected $table = 'email_recipients';
    
    protected $fillable = ['email', 'name', 'is_active'];
    
    /**
     * Get all active email recipients
     */
    public static function getActiveEmails()
    {
        return self::where('is_active', 1)->pluck('email')->toArray();
    }
}
