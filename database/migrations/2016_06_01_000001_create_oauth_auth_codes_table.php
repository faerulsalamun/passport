<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOauthAuthCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('OAUTH_AUTH_CODES', function (Blueprint $table) {
            $table->string('ID', 100)->primary();
            $table->integer('USER_ID');
            $table->integer('CLIENT_ID');
            $table->text('SCOPES')->nullable();
            $table->boolean('REVOKED');
            $table->dateTime('EXPIRES_AT')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('OAUTH_AUTH_CODES');
    }
}
