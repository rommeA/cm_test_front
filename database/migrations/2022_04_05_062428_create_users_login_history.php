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
        Schema::create('authentication_log', function (Blueprint $table) {
            $table->id();
            $table->timestamp('login_at');
            $table->timestamp('logout_at')->nullable();
            $table->integer('authenticatable_id');
            $table->string('authenticatable_type');
            $table->string('ip_address')->nullable();
            $table->bigInteger('country_geoname_id')->nullable()->index();
            $table->string('country_name')->nullable();
            $table->string('country_iso_code')->nullable();
            $table->bigInteger('city_geoname_id')->nullable()->index();
            $table->string('city_name')->nullable();
            $table->text('user_agent')->nullable();
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
        Schema::dropIfExists('authentication_log');
    }
};
