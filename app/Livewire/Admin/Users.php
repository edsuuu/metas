<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\View\View;

class Users extends Component
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
        $users = User::query()
            ->withCount('goals')
            ->when($this->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(20);

        return view('livewire.admin.users', [
            'users' => $users,
        ]);
    }
}
