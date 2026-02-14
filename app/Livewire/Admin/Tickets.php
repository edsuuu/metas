<?php

namespace App\Livewire\Admin;

use App\Models\SupportTicket;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\View\View;

class Tickets extends Component
{
    use WithPagination;

    public string $search = '';

    protected array $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $tickets = SupportTicket::query()
            ->when($this->search, function ($query, $search) {
                $query->where('protocol', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('subject', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(20);

        return view('livewire.admin.tickets', [
            'tickets' => $tickets,
        ]);
    }
}
