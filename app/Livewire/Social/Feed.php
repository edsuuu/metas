<?php

namespace App\Livewire\Social;

use App\Models\SocialPost;
use App\Models\User;
use App\Services\SocialService;
use App\Services\FileService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Feed extends Component
{
    use WithFileUploads;
    use WithPagination;

    public string $content = '';
    public $image;
    public int $perPage = 5;

    // Modais
    public ?int $reportingPostId = null;
    public string $reportReason = '';
    public string $reportDetails = '';
    public bool $showReportSuccess = false;

    public ?int $editingPostId = null;
    public string $editContent = '';

    public ?int $deletingPostId = null;

    public ?string $fullscreenImageUrl = null;
    public ?int $activeCommentPostId = null;
    public string $commentContent = '';

    protected $listeners = ['loadMore' => 'loadMore'];

    public function loadMore(): void
    {
        $this->perPage += 5;
    }

    public function submitPost(SocialService $socialService, FileService $fileService): void
    {
        $this->validate([
            'content' => 'required|string|max:1000',
            'image' => 'nullable|image|max:10240', // 10MB
        ]);

        try {
            $post = $socialService->createPost($this->content);

            if ($post && $this->image) {
                $fileService->upload($this->image, $post, 'feed_images');
            }

            $this->reset(['content', 'image']);
            $this->dispatch('toast', message: 'Postagem publicada!', type: 'success');
        } catch (\Exception $e) {
            Log::error('Erro ao criar post no Livewire: ' . $e->getMessage());
            $this->dispatch('toast', message: 'Erro ao criar post.', type: 'error');
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

    public function reportPost(SocialService $socialService): void
    {
        $this->validate([
            'reportReason' => 'required|string',
        ]);

        if ($socialService->reportPost($this->reportingPostId, $this->reportReason, $this->reportDetails)) {
            $this->showReportSuccess = true;
        } else {
            $this->dispatch('toast', message: 'Erro ao enviar denúncia.', type: 'error');
        }

        $this->reportingPostId = null;
        $this->reset(['reportReason', 'reportDetails']);
    }

    public function hidePost(int $postId, SocialService $socialService): void
    {
        $socialService->hidePost($postId);
        $this->dispatch('toast', message: 'Post ocultado!', type: 'success');
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

    public function render(SocialService $socialService): \Illuminate\View\View
    {
        $userId = Auth::id();
        $user = Auth::user();

        // Get feed items manually to support infinite scroll with perPage
        // In a real app we might use pagination, but let's follow the React logic of "allPosts" list
        $feedData = $socialService->getFeed(); // This returns paginated results based on internal logic
        
        // Let's refactor slightly to use perPage directly
        $friendIds = \App\Models\Friendship::query()
            ->where(function($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->orWhere('friend_id', $userId);
            })
            ->where('status', 'accepted')
            ->get()
            ->flatMap(fn($f) => [$f->user_id, $f->friend_id])
            ->unique()
            ->toArray();

        $userIds = array_unique(array_merge($friendIds, [$userId]));
        $hiddenPostIds = \App\Models\SocialPostHide::where('user_id', $userId)->pluck('post_id')->toArray();

        $posts = SocialPost::query()
            ->with(['user', 'files', 'comments.user'])
            ->withCount(['likes', 'comments'])
            ->withExists(['likes as is_liked' => function($q) use ($userId) {
                $q->where('user_id', $userId);
            }])
            ->whereIn('user_id', $userIds)
            ->whereNotIn('id', $hiddenPostIds)
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.social.feed', [
            'posts' => $posts,
            'suggestions' => $socialService->getSuggestions(),
            'isAdmin' => $user->hasAnyRole(['Administrador', 'Suporte']),
        ]);
    }
}
