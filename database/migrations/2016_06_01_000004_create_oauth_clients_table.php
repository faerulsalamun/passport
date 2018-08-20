<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOauthClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('OAUTH_CLIENTS', function (Blueprint $table) {
            $table->increments('ID');
            $table->integer('USER_ID')->index()->nullable();
            $table->string('NAME');
            $table->string('SECRET', 100);
            $table->text('REDIRECT');
            $table->boolean('PERSONAL_ACCESS_CLIENT');
            $table->boolean('PASSWORD_CLIENT');
            $table->boolean('REVOKED');
            $table->timestamps('CREATED_AT');
            $table->timestamps('UPDATED_AT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('OAUTH_CLIENTS');
    }
}
