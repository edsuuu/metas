<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabela preparada para Web Push Notifications (futuro).
     * Só será usada quando VAPID_PUBLIC_KEY estiver configurado no .env
     */
    public function up(): void
    {
        Schema::create('push_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Dados da subscription do Web Push API
            $table->text('endpoint');
            $table->string('endpoint_hash', 64); // SHA256 hash para índice único
            $table->string('public_key')->nullable(); // p256dh
            $table->string('auth_token')->nullable();
            
            // Identificação do dispositivo
            $table->string('user_agent')->nullable();
            $table->string('device_name')->nullable();
            
            // Controle de expiração
            $table->timestamp('expires_at')->nullable();
            
            $table->timestamps();
            
            // Um usuário pode ter múltiplos dispositivos, mas não duplicar endpoint
            $table->unique(['user_id', 'endpoint_hash']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('push_subscriptions');
    }
};

