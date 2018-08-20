<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOauthRefreshTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('OAUTH_REFRESH_TOKENS', function (Blueprint $table) {
            $table->string('ID', 100)->primary();
            $table->string('ACCESS_TOKEN_ID', 100)->index();
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
        Schema::dropIfExists('OAUTH_REFRESH_TOKENS');
    }
}
