<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcceptedTermsTable extends Migration
{
    public function up()
    {
        Schema::create('accepted_terms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade');
            $table->unsignedInteger('term_id')->references('id')->on('terms');
            $table->timestamp('accepted_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('accepted_terms');
    }
}
