<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToEnqueriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('enqueries', 'status')) {
            Schema::table('enqueries', function (Blueprint $table) {
                $table->enum('status', ['pending', 'sent', 'failed'])->default('pending')->after('message');
                $table->text('email_error')->nullable()->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('enqueries', 'status')) {
            Schema::table('enqueries', function (Blueprint $table) {
                $table->dropColumn(['status', 'email_error']);
            });
        }
    }
}
