<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $fillable = [
        'recipient',
        'subject',
        'message',
        'type',
        'status',
        'method',
        'error'
    ];

    /**
     * Log email send attempt
     */
    public static function logEmail($recipient, $subject, $message, $type, $status, $method, $error = null)
    {
        try {
            self::create([
                'recipient' => $recipient,
                'subject' => $subject,
                'message' => substr($message, 0, 500), // Store first 500 chars
                'type' => $type,
                'status' => $status,
                'method' => $method,
                'error' => $error,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to log email: ' . $e->getMessage());
        }
    }
}
