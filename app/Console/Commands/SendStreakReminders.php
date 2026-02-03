<?php

namespace App\Console\Commands;

use App\Services\NotificationService;
use Illuminate\Console\Command;

class SendStreakReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'streaks:remind {--period=evening : O período do lembrete (morning ou evening)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia lembretes de ofensiva para usuários com metas em risco';

    /**
     * Execute the console command.
     */
    public function handle(NotificationService $notificationService): int
    {
        $period = $this->option('period');

        if (!in_array($period, ['morning', 'evening'])) {
            $this->error("Período inválido: {$period}. Use 'morning' ou 'evening'.");
            return self::FAILURE;
        }

        $this->info("Enviando lembretes de ofensiva ({$period})...");

        $sent = $notificationService->sendStreakReminders($period);

        $this->info("Lembretes enviados: {$sent}");

        return self::SUCCESS;
    }
}
