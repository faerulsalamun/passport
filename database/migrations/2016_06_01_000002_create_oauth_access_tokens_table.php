<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOauthAccessTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('OAUTH_ACCESS_TOKENS', function (Blueprint $table) {
            $table->string('ID', 100)->primary();
            $table->integer('USER_ID')->index()->nullable();
            $table->integer('CLIENT_ID');
            $table->string('NAME')->nullable();
            $table->text('SCOPES')->nullable();
            $table->boolean('REVOKED');
            $table->timestamp('CREATED_AT');
            $table->timestamp('UPDATED_AT');
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
        Schema::dropIfExists('OAUTH_ACCESS_TOKENS');
    }
}
