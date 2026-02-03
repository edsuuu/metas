<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('streaks', function (Blueprint $table) {
            $table->date('completed_date')->nullable()->after('goal_id');
            $table->unique(['goal_id', 'completed_date'], 'streaks_goal_date_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('streaks', function (Blueprint $table) {
            $table->dropForeign(['goal_id']);
            $table->dropUnique('streaks_goal_date_unique');
            $table->dropColumn('completed_date');
            $table->foreign('goal_id')->references('id')->on('goals')->onDelete('cascade');
        });
    }
};
