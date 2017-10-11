<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaConfirmationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_confirmations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->string('code');
            $table->string('type');
            $table->text('data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media_confirmations');
    }
}
