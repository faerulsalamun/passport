<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOauthPersonalAccessClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('OAUTH_PERSONAL_ACCESS_CLIENTS', function (Blueprint $table) {
            $table->increments('ID');
            $table->integer('CLIENT_ID')->index();
            $table->timestamp('CREATED_AT');
            $table->timestamp('UPDATED_AT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('OAUTH_PERSONAL_ACCESS_CLIENTS');
    }
}
