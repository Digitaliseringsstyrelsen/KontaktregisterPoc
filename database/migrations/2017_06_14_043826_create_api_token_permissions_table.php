<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiTokenPermissionsTable extends Migration
{
    public function up()
    {
        Schema::create('api_token_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('api_token_id')->nullble();
            $table->foreign('api_token_id')->references('id')->on('api_tokens')->onDelete('cascade');
            $table->string('name');
            $table->string('description');
            $table->dateTime('granted_at');
            $table->dateTime('revoked_at')->nullable();
            $table->dateTime('expired_at')->nullable();
            $table->string('permission_key');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('api_token_permissions');
    }
}
