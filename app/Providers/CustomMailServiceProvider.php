<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class CustomMailServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Set mail configuration with proper handling of special characters
        $password = env('MAIL_PASSWORD');
        
        // Remove quotes if present
        $password = trim($password, '"\'');
        
        Config::set('mail.password', $password);
        
        // Set stream options for SSL
        Config::set('mail.stream', [
            'ssl' => [
                'allow_self_signed' => true,
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);
    }
}
