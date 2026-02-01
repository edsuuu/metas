<?php

namespace App\Services;

use App\Models\User;
use App\Models\Experience;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExperienceService
{
    /**
     * Award XP to a user.
     *
     * @param User $user
     * @param int $amount
     * @param string $description
     * @param string|null $source
     * @return Experience
     */
    public function award(User $user, int $amount, string $description, ?string $source = null, ?int $goalId = null)
    {
        return DB::transaction(function () use ($user, $amount, $description, $source, $goalId) {
            // Create transaction record (Append Only)
            $transaction = Experience::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'description' => $description,
                'source' => $source,
                'goal_id' => $goalId
            ]);

            // Update user's aggregate XP
            // Now calculated dynamically via User model accessor.
            // $user->increment('current_xp', $amount);

            Log::info("Awarded {$amount} XP to User {$user->id} for {$description}");

            return $transaction;
        });
    }

    /**
     * Calculate level based on XP.
     * Example: Level = floor(sqrt(XP / 100)) or similar custom logic.
     *
     * @param int $xp
     * @return int
     */
    public function calculateLevel(int $xpUser): int
    {
        $level = 1;
        
        while (true) {
            $threshold = $this->getNextLevelXp($level);
            if ($xpUser < $threshold) {
                return $level;
            }
            $level++;
        }
    }

    /**
     * Get XP required for next level.
     */
    public function getNextLevelXp(int $currentLevel): int
    {
        if ($currentLevel === 1) {
            return 200;
        }

        $increment = min(250 + ($currentLevel * 5), 350);

        return $this->getNextLevelXp($currentLevel - 1) + $increment;
    }
}
