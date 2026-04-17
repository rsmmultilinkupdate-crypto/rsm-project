<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateEmailRecipientsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('email_recipients')) {
            Schema::create('email_recipients', function (Blueprint $table) {
                $table->increments('id');
                $table->string('email')->unique();
                $table->string('name')->nullable();
                $table->boolean('is_active')->default(1);
                $table->timestamps();
            });
            
            // Insert default emails
            DB::table('email_recipients')->insert([
                [
                    'email' => 'rsmmultilinkenquiry@gmail.com',
                    'name' => 'Primary Enquiry Email',
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'email' => 'kumarshivam827@gmail.com',
                    'name' => 'Admin Email',
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('email_recipients');
    }
}
