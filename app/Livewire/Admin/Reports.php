<?php

namespace App\Livewire\Admin;

use App\Services\SocialService;
use Livewire\Component;
use Illuminate\View\View;

class Reports extends Component
{
    public function resolve(int $reportId, string $status, bool $deletePost): void
    {
        if (!in_array($status, ['resolved', 'dismissed'])) {
            $this->dispatch('toast', message: 'Status inválido.', type: 'error');
            return;
        }

        /** @var SocialService $socialService */
        $socialService = app(SocialService::class);

        if ($socialService->resolveReport($reportId, $status, $deletePost)) {
            $this->dispatch('toast', message: 'Denúncia resolvida!', type: 'success');
        } else {
            $this->dispatch('toast', message: 'Erro ao resolver denúncia.', type: 'error');
        }
    }

    public function render(): View
    {
        /** @var SocialService $socialService */
        $socialService = app(SocialService::class);

        return view('livewire.admin.reports', [
            'reports' => $socialService->getPendingReports(),
        ]);
    }
}
