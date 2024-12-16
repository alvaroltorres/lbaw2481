<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('auctions', function (Blueprint $table) {
            $table->increments('auction_id');
            $table->integer('user_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->decimal('starting_price', 10, 2)->default(0);
            $table->decimal('reserve_price', 10, 2)->default(0);
            $table->decimal('current_price', 10, 2)->default(0);
            $table->decimal('minimum_bid_increment', 10, 2)->default(1.00);
            $table->text('description')->nullable();
            $table->timestamp('starting_date');
            $table->timestamp('ending_date');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->string('title', 255);
            $table->string('location', 255)->nullable();
            $table->string('status', 50);

            // Adicione chaves estrangeiras conforme necessário
            // $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            // $table->foreign('category_id')->references('category_id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auctions');
    }
};
