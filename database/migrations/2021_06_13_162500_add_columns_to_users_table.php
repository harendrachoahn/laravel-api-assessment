<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            
            $table->string('user_name')->nullable()->after('email');
            $table->string('avatar')->nullable()->after('password');
            $table->string('registered_at')->nullable()->after('remember_token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
           
            $table->dropColumn('avatar');
            $table->dropColumn('user_name');
            $table->dropColumn('registered_at');
        });
    }
}
