<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaSubscriptionsTable extends Migration
{
    public function up()
    {
        Schema::create('media_subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade');
            $table->unsignedInteger('media_id')->references('id')->on('medias')->onDelete('cascade');
            $table->timestamp('subscribed_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('media_subscriptions');
    }
}
