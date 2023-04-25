<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            // system info
            $table->id('id');
            $table->string('firstname')->index()->nullable();
            $table->string('lastname')->index()->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('editing_blocked')->default(false);
            $table->boolean('is_blocked')->default(false);
            $table->boolean('is_seaman')->default(false);
            $table->boolean('is_office_employee')->default(true);

            $table->string('password')->nullable();

            $table->string('position')->nullable();
            $table->string('slug')->unique();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
