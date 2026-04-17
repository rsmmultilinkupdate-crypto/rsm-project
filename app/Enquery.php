<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Enquery extends Model
{
    protected $table = 'enqueries';
    
    protected $fillable = [
        'country', 'name', 'email', 'phone', 'product_name', 'message', 'status', 'email_error'
    ];
    
    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'sent' => 'success',
            'failed' => 'danger'
        ];
        return $badges[$this->status] ?? 'secondary';
    }
    
    /**
     * Get formatted status
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Pending',
            'sent' => 'Email Sent',
            'failed' => 'Email Failed'
        ];
        return $labels[$this->status] ?? 'Unknown';
    }
}

