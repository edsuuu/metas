<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->withCount('goals')
            ->latest()
            ->paginate(20)
            ->through(fn($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar_url' => $user->avatar_url,
                'goals_count' => $user->goals_count,
                'created_at' => $user->created_at->format('d/m/Y'),
                'roles' => $user->getRoleNames(),
            ]);

        return Inertia::render('Admin/Users/Index', [
            'users' => $users
        ]);
    }
}
