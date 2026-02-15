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
        Schema::table('users', function (Blueprint $table) {
            $table->string('last_ip')->nullable()->after('password');
            $table->string('city')->nullable()->after('last_ip');
            $table->string('state')->nullable()->after('city');
            $table->json('location_metadata')->nullable()->after('state');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['last_ip', 'city', 'state', 'location_metadata']);
        });
    }
};
