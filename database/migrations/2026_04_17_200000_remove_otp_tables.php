<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveOtpTables extends Migration
{
    /**
     * Run the migrations - Remove OTP system tables
     *
     * @return void
     */
    public function up()
    {
        // Drop OTP-related tables
        Schema::dropIfExists('email_logs');
        Schema::dropIfExists('email_settings');
        Schema::dropIfExists('o_t_ps');
    }

    /**
     * Reverse the migrations - Recreate tables if needed
     *
     * @return void
     */
    public function down()
    {
        // If you want to restore OTP system, run the original migrations
        // This is just a placeholder
    }
}
