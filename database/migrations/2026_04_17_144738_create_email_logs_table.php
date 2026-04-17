<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailLogsTable extends Migration
{
    public function up()
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('recipient');
            $table->string('subject');
            $table->text('message')->nullable();
            $table->string('type')->default('otp'); // otp, enquiry, etc
            $table->enum('status', ['sent', 'failed'])->default('sent');
            $table->string('method')->nullable(); // smtp, php_mail
            $table->text('error')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('email_logs');
    }
}
