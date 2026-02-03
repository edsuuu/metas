<?php

namespace App\Http\Controllers;

use App\Services\SocialService;
use App\Services\FileService;
use App\Models\User;
use App\Models\SocialPost;
use App\Models\File;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Social\StorePostRequest;
use App\Http\Requests\Social\UpdateAvatarRequest;
use App\Http\Requests\Social\StoreCommentRequest;
use App\Models\Friendship;
use App\Services\StreakService;
use Illuminate\Support\Facades\Log;

class SocialController extends Controller
{
    public SocialService $socialService;
    public FileService $fileService;

    public function __construct(
        SocialService $socialService,
        FileService $fileService
    ) {
        $this->socialService = $socialService;
        $this->fileService = $fileService;
    }

    /**
     * Tela de descoberta de amigos.
     */
    public function index(Request $request): Response
    {
        $search = $request->input('search');
        $users = $search ? $this->socialService->searchUsers($search) : $this->socialService->getSuggestions();
        
        return Inertia::render('Social/Index', [
            'users' => $users,
            'pendingReceived' => $this->socialService->getPendingRequests(),
            'pendingSent' => $this->socialService->getSentRequests(),
            'search' => $search,
            'tab' => $request->input('tab', 'discovery'),
            'hasReceivedBonus' => $this->socialService->hasReceivedSocialBonus(auth()->id()),
        ]);
    }

    /**
     * Envia convite.
     */
    public function sendRequest(int $userId): RedirectResponse
    {
        $this->socialService->sendRequest($userId);
        return back()->with('success', 'Convite enviado!');
    }

    /**
     * Aceita convite.
     */
    public function acceptRequest(int $friendshipId): RedirectResponse
    {
        $this->socialService->acceptRequest($friendshipId);
        return back()->with('success', 'Convite aceito!');
    }

    /**
     * Remove amizade ou cancela convite enviado.
     */
    public function unfollow(int $userId): RedirectResponse
    {
        $this->socialService->removeFriend($userId);
        return back()->with('success', 'Você parou de seguir este usuário.');
    }

    /**
     * Recusa convite.
     */
    public function declineRequest(int $friendshipId): RedirectResponse
    {
        $this->socialService->declineRequest($friendshipId);
        return back()->with('success', 'Convite recusado!');
    }

    /**
     * Feed da comunidade.
     */
    public function feed(Request $request)
    {
        $feed = $this->socialService->getFeed();

        if ($request->wantsJson() || $request->input('type') === 'json') {
            return response()->json($feed);
        }

        return Inertia::render('Social/Feed', [
            'posts' => $feed,
            'suggestions' => $this->socialService->getSuggestions(),
        ]);
    }

    /**
     * Exibe um post específico.
     */
    public function showPost(int $postId): Response
    {
        $post = SocialPost::query()
            ->with(['user', 'files', 'comments.user'])
            ->withCount(['likes', 'comments'])
            ->withExists(['likes as is_liked' => function($q) {
                $q->where('user_id', auth()->id());
            }])
            ->findOrFail($postId);

        return Inertia::render('Social/Show', [
            'post' => $post,
        ]);
    }

    /**
     * Cria uma nova postagem no feed.
     */
    public function storePost(StorePostRequest $request): RedirectResponse
    {
        try {
            $post = $this->socialService->createPost($request->input('content'));

            if (!$post) {
                return back()->with('error', 'Erro ao criar post.');
            }

            if ($request->hasFile('image')) {
                $this->fileService->upload($request->file('image'), $post, 'feed_images');
            }

            return back()->with('success', 'Postagem publicada!');
        } catch (\Exception $e) {
            Log::error('Erro no controller storePost: ' . $e->getMessage(), ['exception' => $e]);
            return back()->with('error', 'Ocorreu um erro inesperado.');
        }
    }

    /**
     * Atualiza o avatar do usuário.
     */
    public function updateAvatar(UpdateAvatarRequest $request): RedirectResponse
    {
        try {
            $user = auth()->user();
            
            // Delete old avatar if exists
            if ($user->avatar) {
                $this->fileService->delete($user->avatar);
            }

            $this->fileService->upload($request->file('image'), $user, 'avatars');

            // No need to update avatar_url column anymore as it is computed
            // $user->update(['avatar_url' => ...]); 

            return back()->with('success', 'Avatar atualizado!');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar avatar: ' . $e->getMessage(), ['exception' => $e]);
            return back()->with('error', 'Erro ao atualizar avatar.');
        }
    }

    /**
     * Perfil social.
     */
    public function profile(?string $identifier = null)
    {
        $query = User::query()
            ->with(['experiences', 'goals.microTasks'])
            ->withCount(['goals' => function($q) {
                $q->where('status', 'completed');
            }]);

        if (!$identifier) {
            $user = auth()->user();
            $param = $user->nickname ?? $user->id;
            return to_route('social.profile', ['identifier' => $param]);
        } elseif (is_numeric($identifier)) {
            $user = $query->findOrFail($identifier);
        } else {
            $user = $query->where('nickname', $identifier)->firstOrFail();
        }

        $userId = $user->id;

        // Streak info
        $streakService = app(StreakService::class);
        $streak = $streakService->getGlobalStreak($user);

        //XP info
        $xpInfo = [
            'current' => $user->current_xp,
            'level' => floor($user->current_xp / 1000) + 1,
            'next_level_threshold' => 1000,
        ];

        // Check if is following (bi-directional or pending sent)
        $user->is_following = Friendship::query()
            ->where(function($q) use ($user) {
                $q->where('user_id', auth()->id())->where('friend_id', $user->id);
            })
            ->orWhere(function($q) use ($user) {
                $q->where('user_id', $user->id)->where('friend_id', auth()->id());
            })
            ->where('status', 'accepted')
            ->orWhere(function($q) use ($user) {
                // Se eu enviei e está pendente
                $q->where('user_id', auth()->id())->where('friend_id', $user->id)->where('status', 'pending');
            })
            ->exists();

        return Inertia::render('Social/Profile', [
            'profileUser' => $user,
            'xpInfo' => $xpInfo,
            'streak' => $streak,
            'posts' => SocialPost::query()
                ->with(['user', 'files', 'comments.user'])
                ->withCount(['likes', 'comments'])
                ->withExists(['likes as is_liked' => function($q) {
                    $q->where('user_id', auth()->id());
                }])
                ->where('user_id', $userId)
                ->latest()
                ->get(),
            'stats' => [
                'following' => $this->socialService->getFollowingCount($userId),
                'followers' => $this->socialService->getFollowersCount($userId),
            ]
        ]);
    }

    /**
     * Curte/Descurte um post.
     */
    public function toggleLike(int $postId): RedirectResponse
    {
        $this->socialService->toggleLike($postId);
        return back();
    }

    /**
     * Adiciona um comentário a um post.
     */
    public function storeComment(StoreCommentRequest $request, int $postId): RedirectResponse
    {
        $this->socialService->addComment($postId, $request->input('content'));
        return back()->with('success', 'Comentário adicionado!');
    }

    /**
     * Denuncia um post.
     */
    public function reportPost(int $postId, Request $request): RedirectResponse
    {
        $request->validate([
            'reason' => 'required|string|max:255',
            'details' => 'nullable|string',
        ]);

        $this->socialService->reportPost($postId, $request->reason, $request->details);
        return back()->with('success', 'Denúncia enviada com sucesso!');
    }

    /**
     * Oculta um post.
     */
    public function hidePost(int $postId): RedirectResponse
    {
        $this->socialService->hidePost($postId);
        return back()->with('success', 'Post ocultado!');
    }

    /**
     * Exclui um post.
     */
    public function deletePost(int $postId): RedirectResponse
    {
        $post = SocialPost::query()->findOrFail($postId);
        if (in_array($post->type, ['goal_completed', 'streak_maintained'])) {
             return back()->with('error', 'Este tipo de postagem não pode ser excluído.');
        }

        if ($this->socialService->deletePost($postId)) {
            return back()->with('success', 'Post excluído!');
        }
        return back()->with('error', 'Não foi possível excluir o post.');
    }

    /**
     * Atualiza um post.
     */
    public function updatePost(int $postId, StorePostRequest $request): RedirectResponse
    {
        try {
            $postModel = SocialPost::query()->findOrFail($postId);
            
            if (in_array($postModel->type, ['goal_completed', 'streak_maintained'])) {
                return back()->with('error', 'Este tipo de postagem não pode ser editado.');
            }

            if ($this->socialService->editPost($postId, $request->input('content'))) {
                // Post updates no longer allow image changes as per user request
                return back()->with('success', 'Post atualizado!');
            }
            return back()->with('error', 'Não foi possível atualizar o post.');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar post: ' . $e->getMessage(), ['exception' => $e]);
            return back()->with('error', 'Erro ao processar atualização.');
        }
    }

    /**
     * Retorna status social para polling (notificações em tempo real).
     */
    public function getSocialStatus(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'pending_requests' => $this->socialService->getPendingRequests()->count(),
        ]);
    }
}
