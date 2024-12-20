<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Criar a tabela 'blockedusers' apenas se ela não existir
        if (!Schema::hasTable('blockeduser')) {
            Schema::create('blockeduser', function (Blueprint $table) {
                $table->id(); // Chave primária
                $table->unsignedBigInteger('admin_id'); // ID do admin que bloqueou
                $table->unsignedBigInteger('blocked_user_id'); // ID do utilizador bloqueado
                $table->string('reason', 255); // Motivo do bloqueio
                $table->timestamp('blocked_at')->nullable(); // Data do bloqueio

                // Definição das foreign keys
                $table->foreign('admin_id')->references('id')->on('User')->onDelete('cascade');
                $table->foreign('blocked_user_id')->references('id')->on('User')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remover a tabela "blocked_users" caso ela exista
        Schema::dropIfExists('blockeduser');
    }
};
