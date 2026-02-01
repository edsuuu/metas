<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin {email}';
    protected $description = 'Atribui o papel de Administrador a um usuário';

    public function handle()
    {
        $email = $this->argument('email');
        $user = \App\Models\User::where('email', $email)->first();

        if (!$user) {
            $this->error("Usuário com email {$email} não encontrado.");
            return 1;
        }

        $user->assignRole('Administrador');
        $this->info("Usuário {$user->name} agora é um Administrador.");
        return 0;
    }
}
