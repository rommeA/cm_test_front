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
        Schema::create('smtp_servers', function (Blueprint $table) {
            $table->id();
            $table->string('transport')->default('smtp');
            $table->string('host');
            $table->integer('port')->default(587);
            $table->string('username');
            $table->string('password');
            $table->string('encryption')->default('tls');
            $table->string('from_address');
            $table->string('from_name')->default(config('app.name'));
            $table->integer('attempts')->default(10);

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
        Schema::dropIfExists('smtp_servers');
    }
};
