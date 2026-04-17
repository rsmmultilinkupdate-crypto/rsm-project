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
        try {
            // Set mail configuration with proper handling of special characters
            $password = env('MAIL_PASSWORD');
            
            // Remove quotes if present
            $password = trim($password, '"\'');
            
            // Log for debugging (remove in production)
            \Log::info('Mail Config', [
                'host' => env('MAIL_HOST'),
                'port' => env('MAIL_PORT'),
                'username' => env('MAIL_USERNAME'),
                'encryption' => env('MAIL_ENCRYPTION'),
                'password_length' => strlen($password),
            ]);
            
            Config::set('mail.password', $password);
            Config::set('mail.username', env('MAIL_USERNAME'));
            Config::set('mail.host', env('MAIL_HOST'));
            Config::set('mail.port', env('MAIL_PORT'));
            Config::set('mail.encryption', env('MAIL_ENCRYPTION'));
            
            // Set stream options for SSL
            Config::set('mail.stream', [
                'ssl' => [
                    'allow_self_signed' => true,
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('CustomMailServiceProvider error: ' . $e->getMessage());
        }
    }
}
