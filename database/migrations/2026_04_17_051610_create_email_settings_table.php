<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateEmailSettingsTable extends Migration
{
    public function up()
    {
        // Check if table already exists
        if (Schema::hasTable('email_settings')) {
            \Log::info('email_settings table already exists, skipping creation');
            return;
        }
        
        Schema::create('email_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->unique();
            $table->string('label')->nullable();       // e.g. "Enquiry Email", "Admin Email"
            $table->enum('type', ['enquiry', 'otp', 'both'])->default('both'); // which emails to receive
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
        
        // Insert default emails
        DB::table('email_settings')->insert([
            [
                'email' => 'rsmmultilinkenquiry@gmail.com',
                'label' => 'Primary Enquiry Email',
                'type' => 'both',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 'kumarshivam827@gmail.com',
                'label' => 'Admin Email',
                'type' => 'both',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('email_settings');
    }
}
