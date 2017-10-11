<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsTable extends Migration
{
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('subscription_type_id');
            $table->unsignedInteger('owner_contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->unsignedInteger('source_contact_id')->nullable()->references('id')->on('contacts')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
