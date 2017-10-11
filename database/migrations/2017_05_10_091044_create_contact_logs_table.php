<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactLogsTable extends Migration
{
    public function up()
    {
        Schema::create('contact_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->string('type');
            $table->string('user_token');
            $table->string('user_organization')->nullable();
            $table->string('username');
            $table->string('old_value')->nullable();
            $table->string('new_value')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contact_logs');
    }
}
