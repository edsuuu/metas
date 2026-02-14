<?php

namespace App\Livewire\Social;

use App\Models\User;
use App\Models\SocialPost;
use App\Models\Friendship;
use App\Services\SocialService;
use App\Services\StreakService;
use App\Services\FileService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Profile extends Component
{
    use WithFileUploads;

    public string $identifier = '';
    public ?User $profileUser = null;
    public $avatar;

    // Post interaction states
    public ?int $activeCommentPostId = null;
    public string $commentContent = '';
    public ?string $fullscreenImageUrl = null;

    // Modals
    public ?int $reportingPostId = null;
    public string $reportReason = '';
    public string $reportDetails = '';
    
    public ?int $editingPostId = null;
    public string $editContent = '';
    
    public ?int $deletingPostId = null;

    public function mount(?string $identifier = null): void
    {
        $this->identifier = $identifier ?: (Auth::user()->nickname ?: Auth::id());
        $this->loadUser();
    }

    public function loadUser(): void
    {
        $query = User::query()
            ->with(['experiences', 'goals.microTasks'])
            ->withCount(['goals' => function($q) {
                $q->where('status', 'completed');
            }]);

        if (is_numeric($this->identifier)) {
            $this->profileUser = $query->find($this->identifier);
        } else {
            $this->profileUser = $query->where('nickname', $this->identifier)->first();
        }

        if (!$this->profileUser && Auth::check() && !$this->identifier) {
            $this->profileUser = $query->find(Auth::id());
        }

        if (!$this->profileUser) {
            abort(404);
        }
    }

    public function toggleLike(int $postId, SocialService $socialService): void
    {
        $socialService->toggleLike($postId);
    }

    public function submitComment(int $postId, SocialService $socialService): void
    {
        if (empty($this->commentContent)) return;

        $socialService->addComment($postId, $this->commentContent);
        $this->reset('commentContent');
        $this->activeCommentPostId = null;
        $this->dispatch('toast', message: 'Comentário adicionado!', type: 'success');
    }

    public function toggleComments(int $postId): void
    {
        $this->activeCommentPostId = $this->activeCommentPostId === $postId ? null : $postId;
    }

    public function toggleFollow(SocialService $socialService): void
    {
        if ($this->profileUser->id === Auth::id()) return;

        $isFollowing = Friendship::query()
            ->where(function($q) {
                $q->where('user_id', Auth::id())->where('friend_id', $this->profileUser->id);
            })
            ->orWhere(function($q) {
                $q->where('user_id', $this->profileUser->id)->where('friend_id', Auth::id());
            })
            ->where('status', 'accepted')
            ->exists();

        if ($isFollowing) {
            $socialService->removeFriend($this->profileUser->id);
            $this->dispatch('toast', message: 'Você parou de seguir este usuário.', type: 'success');
        } else {
            $socialService->sendRequest($this->profileUser->id);
            $this->dispatch('toast', message: 'Convite enviado!', type: 'success');
        }

        $this->loadUser(); // Refresh user data to update is_following
    }

    public function updatedAvatar(FileService $fileService): void
    {
        $this->validate([
            'avatar' => 'image|max:2048', // 2MB
        ]);

        try {
            if ($this->profileUser->avatar) {
                $fileService->delete($this->profileUser->avatar);
            }

            $fileService->upload($this->avatar, $this->profileUser, 'avatars');
            
            $this->dispatch('toast', message: 'Avatar atualizado!', type: 'success');
            $this->loadUser();
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar avatar no Livewire: ' . $e->getMessage());
            $this->dispatch('toast', message: 'Erro ao atualizar avatar.', type: 'error');
        }
    }

    public function reportPost(SocialService $socialService): void
    {
        $this->validate([
            'reportReason' => 'required|string',
        ]);

        if ($socialService->reportPost($this->reportingPostId, $this->reportReason, $this->reportDetails)) {
            $this->dispatch('toast', message: 'Denúncia enviada!', type: 'success');
        } else {
            $this->dispatch('toast', message: 'Erro ao enviar denúncia.', type: 'error');
        }

        $this->reportingPostId = null;
        $this->reset(['reportReason', 'reportDetails']);
    }

    public function deletePost(SocialService $socialService): void
    {
        if (!$this->deletingPostId) return;

        if ($socialService->deletePost($this->deletingPostId)) {
            $this->dispatch('toast', message: 'Post excluído!', type: 'success');
        } else {
            $this->dispatch('toast', message: 'Erro ao excluir post.', type: 'error');
        }

        $this->deletingPostId = null;
    }

    public function updatePost(SocialService $socialService): void
    {
        $this->validate([
            'editContent' => 'required|string|max:1000',
        ]);

        if ($socialService->editPost($this->editingPostId, $this->editContent)) {
            $this->dispatch('toast', message: 'Post atualizado!', type: 'success');
        } else {
            $this->dispatch('toast', message: 'Erro ao atualizar post.', type: 'error');
        }

        $this->editingPostId = null;
        $this->reset('editContent');
    }

    public function hidePost(int $postId, SocialService $socialService): void
    {
        $socialService->hidePost($postId);
        $this->dispatch('toast', message: 'Post ocultado!', type: 'success');
    }

    public function render(SocialService $socialService, StreakService $streakService): \Illuminate\View\View
    {
        $userId = Auth::id();
        $isOwnProfile = $userId === $this->profileUser->id;

        // Streak info
        $streak = $streakService->getGlobalStreak($this->profileUser);

        // XP info
        $xpInfo = [
            'current' => $this->profileUser->current_xp,
            'level' => floor($this->profileUser->current_xp / 1000) + 1,
            'next_level_threshold' => 1000,
        ];

        // Is following
        $isFollowing = Friendship::query()
            ->where(function($q) {
                $q->where('user_id', Auth::id())->where('friend_id', $this->profileUser->id);
            })
            ->orWhere(function($q) {
                $q->where('user_id', $this->profileUser->id)->where('friend_id', Auth::id());
            })
            ->where('status', 'accepted')
            ->orWhere(function($q) {
                $q->where('user_id', Auth::id())->where('friend_id', $this->profileUser->id)->where('status', 'pending');
            })
            ->exists();

        // Posts
        $posts = SocialPost::query()
            ->with(['user', 'files', 'comments.user'])
            ->withCount(['likes', 'comments'])
            ->withExists(['likes as is_liked' => function($q) use ($userId) {
                $q->where('user_id', $userId);
            }])
            ->where('user_id', $this->profileUser->id)
            ->latest()
            ->get();

        return view('livewire.social.profile', [
            'isOwnProfile' => $isOwnProfile,
            'posts' => $posts,
            'streak' => $streak,
            'xpInfo' => $xpInfo,
            'isFollowing' => $isFollowing,
            'stats' => [
                'following' => $socialService->getFollowingCount($this->profileUser->id),
                'followers' => $socialService->getFollowersCount($this->profileUser->id),
            ],
            'isAdmin' => Auth::user()->hasAnyRole(['Administrador', 'Suporte']),
        ]);
    }
}
