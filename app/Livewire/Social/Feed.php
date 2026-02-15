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
use App\Models\Friendship;
use App\Models\SocialPostHide;
use Illuminate\View\View;
use Illuminate\Contracts\Pagination\Paginator;
use App\Events\SocialPostUpdated;

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

    public ?int $activeCommentPostId = null;
    public ?int $fullscreenPostId = null;
    public ?string $fullscreenImageUrl = null;
    public array $commentContent = [];

    public function getListeners(): array
    {
        return [
            'loadMore' => 'loadMore',
            "echo:social-feed,SocialPostUpdated" => 'handlePostUpdated',
        ];
    }

    public function handlePostUpdated(array $event): void
    {
        // When a post is updated (like, comment, etc.), we refresh to get latest counts
        // Livewire will only re-render affected parts.
    }

    protected function rules(): array
    {
        return [
            'content' => 'required|string|max:1000',
            'image' => 'nullable|image|max:10240',

            'editContent' => 'required|string|max:1000',
            'reportReason' => 'required|string',
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'content' => 'conteúdo da postagem',
            'image' => 'imagem',

            'editContent' => 'conteúdo editado',
            'reportReason' => 'motivo da denúncia',
        ];
    }

    protected function messages(): array
    {
        return [
            'content.required' => 'O que você está pensando? Escreva algo!',
            'image.max' => 'A imagem deve ter no máximo 10MB.',
            'editContent.required' => 'O conteúdo não pode ficar vazio.',
            'reportReason.required' => 'Por favor, selecione um motivo.',
        ];
    }

    public function loadMore(): void
    {
        $this->perPage += 5;
    }

    public function submitPost(SocialService $socialService, FileService $fileService): void
    {
        if (empty($this->content) && empty($this->image)) return;

        if ($this->image) {
            $this->validateOnly('image');
        }

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
        if (empty($this->commentContent[$postId] ?? '')) return;

        $socialService->addComment($postId, $this->commentContent[$postId]);
        
        // Reset specific comment input
        unset($this->commentContent[$postId]);
        $this->dispatch('toast', message: 'Incentivo enviado!', type: 'success');
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
        $this->validateOnly('reportReason');

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
        $this->validateOnly('editContent');

        if ($socialService->editPost($this->editingPostId, $this->editContent)) {
            $this->dispatch('toast', message: 'Post atualizado!', type: 'success');
        } else {
            $this->dispatch('toast', message: 'Erro ao atualizar post.', type: 'error');
        }

        $this->editingPostId = null;
        $this->reset('editContent');
    }

    public function render(SocialService $socialService): View
    {
        $user = Auth::user();

        $feed = $socialService->getFeed($this->perPage);

        return view('livewire.social.feed', [
            'posts' => $feed['paginator'],
            'suggestions' => $socialService->getSuggestions(),
            'isAdmin' => $user->hasAnyRole(['Administrador', 'Suporte']),
        ]);
    }
}
