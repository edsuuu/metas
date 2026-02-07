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
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Email notifications
            $table->boolean('streak_email_enabled')->default(true);
            
            // Push notifications (preparado para futuro)
            $table->boolean('streak_push_enabled')->default(false);
            
            // Horários dos lembretes (formato HH:MM)
            $table->string('morning_reminder_time', 5)->default('09:00');
            $table->string('evening_reminder_time', 5)->default('21:00');
            
            // Timezone do usuário (opcional, usa app timezone se null)
            $table->string('timezone')->nullable();
            
            $table->timestamps();
            
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
    }
};
