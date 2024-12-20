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
        Schema::table('report', function (Blueprint $table) {
            // Primeiro remove a foreign key existente com o nome explícito
            $table->dropForeign('report_auction_id_fkey');

            // Agora adiciona novamente com cascade delete
            $table->foreign('auction_id')
                ->references('auction_id')->on('Auction')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('report', function (Blueprint $table) {
            // Remove novamente a nova foreign key
            $table->dropForeign('report_auction_id_fkey');

            // Recria a original sem cascade
            $table->foreign('auction_id')
                ->references('auction_id')->on('Auction');
        });
    }
};
