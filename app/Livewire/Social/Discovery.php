<?php

namespace App\Livewire\Social;

use App\Models\User;
use App\Models\Friendship;
use App\Services\SocialService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class Discovery extends Component
{
    public ?string $search = '';
    public ?string $tab = 'discovery';

    public function mount(?string $tab = null, ?string $search = null): void
    {
        if ($tab) {
            $this->tab = $tab;
        }
        if ($search) {
            $this->search = $search;
        }
    }

    public function sendRequest(int $userId, SocialService $socialService): void
    {
        $socialService->sendRequest($userId);
        $this->dispatch('toast', message: 'Convite enviado!', type: 'success');
    }

    public function acceptRequest(int $friendshipId, SocialService $socialService): void
    {
        if ($socialService->acceptRequest($friendshipId)) {
            $this->dispatch('toast', message: 'Convite aceito!', type: 'success');
        } else {
            $this->dispatch('toast', message: 'Erro ao aceitar convite.', type: 'error');
        }
    }

    public function declineRequest(int $friendshipId, SocialService $socialService): void
    {
        if ($socialService->declineRequest($friendshipId)) {
            $this->dispatch('toast', message: 'Convite recusado!', type: 'success');
        } else {
            $this->dispatch('toast', message: 'Erro ao recusar convite.', type: 'error');
        }
    }

    public function render(SocialService $socialService): \Illuminate\View\View
    {
        $users = $this->search 
            ? $socialService->searchUsers($this->search) 
            : $socialService->getSuggestions();

        return view('livewire.social.discovery', [
            'users' => $users,
            'pendingReceived' => $socialService->getPendingRequests(),
            'pendingSent' => $socialService->getSentRequests(),
            'hasReceivedBonus' => $socialService->hasReceivedSocialBonus(Auth::id()),
        ]);
    }
}
