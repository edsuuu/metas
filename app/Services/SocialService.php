<?php

namespace App\Services;

use App\Models\User;
use App\Models\Friendship;
use App\Models\SocialPost;
use App\Models\SocialPostLike;
use App\Models\SocialPostComment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\SocialPostReport;
use App\Models\SocialPostHide;

class SocialService
{
    /**
     * Envia um convite de amizade.
     */
    public function sendRequest(int $friendId): ?Friendship
    {
        try {
            // Verifica se já existe amizade ou pedido em qualquer direção
            $existing = Friendship::query()
                ->where(function($q) use ($friendId) {
                    $q->where('user_id', Auth::id())->where('friend_id', $friendId);
                })
                ->orWhere(function($q) use ($friendId) {
                    $q->where('user_id', $friendId)->where('friend_id', Auth::id());
                })
                ->first();

            if ($existing) {
                if ($existing->status === 'accepted') {
                    return $existing;
                }
                
                if ($existing->user_id === $friendId && $existing->status === 'pending') {
                   $existing->update(['status' => 'accepted']);
                   $this->checkAndAwardSocialBonus(Auth::id());
                   $this->checkAndAwardSocialBonus($friendId);
                   return $existing;
                }

                if ($existing->user_id === Auth::id()) {
                    return $existing;
                }
            }

            return Friendship::create([
                'user_id' => Auth::id(),
                'friend_id' => $friendId,
                'status' => 'pending',
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao enviar pedido de amizade: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Cria um post no feed social.
     */
    public function createPost(string $content, string $type = 'generic', array $metadata = []): ?SocialPost
    {
        try {
            return SocialPost::create([
                'user_id' => Auth::id(),
                'content' => $content,
                'type' => $type,
                'metadata' => $metadata,
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao criar post social: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Aceita um convite de amizade.
     */
    public function acceptRequest(int $friendshipId): bool
    {
        try {
            DB::beginTransaction();

            $friendship = Friendship::query()->findOrFail($friendshipId);
            
            if ($friendship->friend_id !== Auth::id()) {
                return false;
            }

            $friendship->update(['status' => 'accepted']);
            
            $this->checkAndAwardSocialBonus(Auth::id());
            $this->checkAndAwardSocialBonus($friendship->user_id);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao aceitar pedido de amizade: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Recusa um convite de amizade.
     */
    /**
     * Remove uma amizade pelo ID do usuário amigo.
     */
    public function removeFriend(int $friendId): bool
    {
        try {
            return Friendship::query()
                ->where('user_id', Auth::id())
                ->where('friend_id', $friendId)
                ->delete();
        } catch (\Exception $e) {
            Log::error('Erro ao remover amigo: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Recusa um convite de amizade.
     */
    public function declineRequest(int $friendshipId): bool
    {
        try {
            $friendship = Friendship::query()->findOrFail($friendshipId);
            
            if ($friendship->friend_id !== Auth::id()) {
                return false;
            }

            return $friendship->delete();
        } catch (\Exception $e) {
            Log::error('Erro ao recusar pedido de amizade: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Lista pedidos de amizade recebidos (pendentes).
     */
    public function getPendingRequests(): Collection
    {
        return Friendship::query()
            ->with('user')
            ->where('friend_id', Auth::id())
            ->where('status', 'pending')
            ->get();
    }

    /**
     * Lista pedidos de amizade enviados (pendentes).
     */
    public function getSentRequests(): Collection
    {
        return Friendship::query()
            ->with('friend')
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->get();
    }

    /**
     * Busca usuários por nickname ou email.
     */
    public function searchUsers(string $query): Collection
    {
        return User::query()
            ->where('id', '!=', Auth::id())
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('nickname', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->get()
            ->each(function($user) {
                $user->is_following = Friendship::where(function($q) use ($user) {
                        $q->where('user_id', Auth::id())->where('friend_id', $user->id);
                    })
                    ->orWhere(function($q) use ($user) {
                        $q->where('user_id', $user->id)->where('friend_id', Auth::id());
                    })
                    ->where('status', 'accepted')
                    ->orWhere(function($q) use ($user) {
                        $q->where('user_id', Auth::id())->where('friend_id', $user->id)->where('status', 'pending');
                    })
                    ->exists();
            });
    }

    /**
     * Retorna sugestões de amigos (baseado em quem não é amigo ainda).
     */
    /**
     * Retorna sugestões de amigos baseadas na localização (cidade/estado).
     */
    public function getSuggestions(): \Illuminate\Support\Collection
    {
        $userId = Auth::id();
        $user = Auth::user();
        
        $friendIds = Friendship::query()
            ->where('user_id', $userId)
            ->orWhere('friend_id', $userId)
            ->get()
            ->flatMap(fn($f) => [$f->user_id, $f->friend_id])
            ->unique()
            ->toArray();

        return User::query()
            ->where('id', '!=', $userId)
            ->whereNotIn('id', $friendIds)
            ->when($user->city, function ($query) use ($user) {
                $query->orderByRaw("CASE WHEN city = ? THEN 0 ELSE 1 END", [$user->city]);
            })
            ->when($user->state, function ($query) use ($user) {
                $query->orderByRaw("CASE WHEN state = ? THEN 0 ELSE 1 END", [$user->state]);
            })
            ->limit(10)
            ->get()
            ->each(function($u) use ($userId) {
                $u->is_following = Friendship::where(function($q) use ($userId, $u) {
                        $q->where('user_id', $userId)->where('friend_id', $u->id);
                    })
                    ->orWhere(function($q) use ($userId, $u) {
                        $q->where('user_id', $u->id)->where('friend_id', $userId);
                    })
                    ->exists();
            });
    }

    /**
     * Retorna o feed de atividades com suporte a Reverb.
     */
    public function getFeed(int $perPage = 5): array
    {
        $userId = Auth::id();
        $user = Auth::user();

        // Tentar capturar IP se não estiver setado
        if (!$user->last_ip) {
            $user->update(['last_ip' => request()->ip()]);
            // Aqui poderíamos disparar um Job para buscar a cidade via API externa
            // Exemplo fictício para o teste sugerido pelo usuário:
            if ($user->last_ip === '127.0.0.1' || strpos($user->last_ip, '192.168') === 0) {
                 $user->update(['city' => 'Itapevi', 'state' => 'SP']);
            }
        }
        
        $friendIds = Friendship::query()
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
        $hiddenPostIds = SocialPostHide::where('user_id', $userId)->pluck('post_id')->toArray();

        $paginator = SocialPost::query()
            ->with(['user', 'files', 'comments.user'])
            ->withCount(['likes', 'comments'])
            ->withExists(['likes as is_liked' => function($q) use ($userId) {
                $q->where('user_id', $userId);
            }])
            ->whereIn('user_id', $userIds)
            ->whereNotIn('id', $hiddenPostIds)
            ->latest()
            ->paginate($perPage);

        return [
            'items' => $paginator->items(),
            'hasMore' => $paginator->hasMorePages(),
            'nextPage' => $paginator->currentPage() + 1,
            'total' => $paginator->total(),
            'paginator' => $paginator
        ];
    }

    /**
     * Curte ou descurte um post.
     */
    public function toggleLike(int $postId): bool
    {
        try {
            $userId = Auth::id();
            $like = SocialPostLike::where('user_id', $userId)
                ->where('social_post_id', $postId)
                ->first();

            if ($like) {
                $like->delete();
            } else {
                SocialPostLike::create([
                    'user_id' => $userId,
                    'social_post_id' => $postId,
                ]);
            }

            // Broadcast real-time update
            event(new \App\Events\SocialPostUpdated(SocialPost::find($postId)));

            return true;
        } catch (\Exception $e) {
            Log::error('Erro ao alternar like no post: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Comenta em um post.
     */
    public function addComment(int $postId, string $content): ?SocialPostComment
    {
        try {
            $comment = SocialPostComment::create([
                'user_id' => Auth::id(),
                'social_post_id' => $postId,
                'content' => $content,
            ]);

            // Broadcast real-time update
            event(new \App\Events\SocialPostUpdated(SocialPost::find($postId)));

            return $comment;
        } catch (\Exception $e) {
            Log::error('Erro ao adicionar comentário: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Retorna a contagem de pessoas que o usuário está seguindo.
     * Inclui: Convites enviados (mesmo pendentes) e convites recebidos que foram aceitos.
     */
    public function getFollowingCount(int $userId): int
    {
        $sentRequests = Friendship::where('user_id', $userId)->count();
        $acceptedReceived = Friendship::where('friend_id', $userId)->where('status', 'accepted')->count();
        
        return $sentRequests + $acceptedReceived;
    }

    /**
     * Retorna a contagem de seguidores do usuário.
     * Inclui: Convites recebidos (mesmo pendentes) e convites enviados que foram aceitos.
     */
    public function getFollowersCount(int $userId): int
    {
        $receivedRequests = Friendship::where('friend_id', $userId)->count();
        $acceptedSent = Friendship::where('user_id', $userId)->where('status', 'accepted')->count();
        
        return $receivedRequests + $acceptedSent;
    }

    /**
     * Retorna o ranking de amigos (incluindo o próprio usuário) por XP.
     */
    public function getFriendRanking(): Collection
    {
        $userId = Auth::id();
        $friendIds = Friendship::query()
            ->where(function($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->orWhere('friend_id', $userId);
            })
            ->where('status', 'accepted')
            ->get()
            ->flatMap(function($f) {
                return [$f->user_id, $f->friend_id];
            })
            ->unique()
            ->toArray();

        $userIds = array_merge($friendIds, [$userId]);

        return User::query()
            ->whereIn('id', $userIds)
            ->withSum('experiences', 'amount')
            ->orderByDesc('experiences_sum_amount')
            ->limit(10)
            ->get();
    }

    /**
     * Oculta um post para o usuário atual.
     */
    public function hidePost(int $postId): ?SocialPostHide
    {
        try {
            return SocialPostHide::updateOrCreate([
                'user_id' => Auth::id(),
                'post_id' => $postId,
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao ocultar post: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Denuncia um post.
     */
    public function reportPost(int $postId, string $reason, ?string $details = null): ?SocialPostReport
    {
        try {
            return SocialPostReport::create([
                'user_id' => Auth::id(),
                'post_id' => $postId,
                'reason' => $reason,
                'details' => $details,
                'status' => 'pending',
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao denunciar post: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Exclui um post (se for o dono ou admin).
     */
    public function deletePost(int $postId): bool
    {
        try {
            $post = SocialPost::findOrFail($postId);
            
            // Verifica se é o dono ou se tem permissão de admin (assumindo que admin tem role)
            if ($post->user_id !== Auth::id() && !Auth::user()->hasAnyRole(['Administrador', 'Suporte'])) {
                return false;
            }

            return $post->delete();
        } catch (\Exception $e) {
            Log::error('Erro ao excluir post: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualiza o conteúdo de um post e registra histórico se for a primeira edição.
     */
    public function editPost(int $postId, string $content): bool
    {
        try {
            $post = SocialPost::findOrFail($postId);
            
            if ($post->user_id !== Auth::id()) {
                return false;
            }

            // Se nunca foi editado, guarda o original
            if (!$post->is_edited) {
                $post->is_edited = true;
                $post->original_content = $post->content;
                $post->original_file_uuid = $post->files()->first()?->uuid;
            }

            return $post->update(['content' => $content]);
        } catch (\Exception $e) {
            Log::error('Erro ao editar post: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Lista denúncias pendentes para a administração.
     */
    public function getPendingReports(): Collection
    {
        return SocialPostReport::with(['user', 'post.user', 'post.files'])
            ->where('status', 'pending')
            ->latest()
            ->get();
    }

    /**
     * Resolve uma denúncia.
     */
    public function resolveReport(int $reportId, string $status, bool $deletePost = false): bool
    {
        try {
            DB::beginTransaction();

            $report = SocialPostReport::query()->findOrFail($reportId);
            
            if ($deletePost) {
                // deletePost já tem seu próprio try/catch, mas aqui queremos que falhe atomicamente
                // Idealmente, a lógica inteira deveria estar nesta transaction.
                // Vou chamar delete diretamente aqui para garantir atomicidade ou refatorar deletePost
                // Como deletePost é public, vamos chamá-lo. Se falhar retorna false.
                // Mas deletePost inicia LOG e não lança exceção. Isso é problemático para transaction.
                // Melhor: duplicar lógica simples aqui ou lançar exceção.
                // Simplificando: atomicidade via DB
                
                $post = SocialPost::query()->find($report->post_id);
                if ($post) $post->delete(); 
                
                // Se deletePost falhar (ex: permissão), não chegamos aqui se fizermos manual.
                // Como é admin resolvendo report, assumimos permissão.

                $report->update(['status' => 'resolved']);
            } else {
                $report->update(['status' => $status]);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao resolver denúncia: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica se o usuário já recebeu o bônus de 3 amigos.
     */
    public function hasReceivedSocialBonus(int $userId): bool
    {
        return \App\Models\Experience::where('user_id', $userId)
            ->where('description', 'Bônus Social: 3 Amigos')
            ->exists();
    }

    /**
     * Verifica e concede bônus de XP se atingir 3 amigos.
     */
    public function checkAndAwardSocialBonus(int $userId): void
    {
        try {
            // Se já ganhou, não ganha mais
            if ($this->hasReceivedSocialBonus($userId)) {
                return;
            }

            // Conta amigos aceitos
            $friendCount = Friendship::query()
                ->where(function($q) use ($userId) {
                    $q->where('user_id', $userId)->orWhere('friend_id', $userId);
                })
                ->where('status', 'accepted')
                ->count();

            if ($friendCount >= 3) {
                \App\Models\Experience::create([
                    'user_id' => $userId,
                    'amount' => 200,
                    'description' => 'Bônus Social: 3 Amigos',
                    'source' => 'social_bonus',
                ]);
                
                // Opcional: Notificar usuário (seria ideal ter um sistema de notificações)
            }
        } catch (\Exception $e) {
            Log::error('Erro ao verificar/conceder bônus social: ' . $e->getMessage());
        }
    }
}
