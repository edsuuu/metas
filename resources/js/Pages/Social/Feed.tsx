import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm, usePage, router } from '@inertiajs/react';
import React, { useState, useRef } from 'react';
import axios from 'axios';
import InputError from '@/Components/InputError';
import Dropdown from '@/Components/Dropdown';
import Modal from '@/Components/Modal';
import SecondaryButton from '@/Components/SecondaryButton';
import DangerButton from '@/Components/DangerButton';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import InputLabel from '@/Components/InputLabel';

interface PostFile {
    id: number;
    uuid: string;
    path: string;
    filename: string;
}

interface User {
    id: number;
    name: string;
    nickname: string | null;
    avatar_url: string | null;
    is_following?: boolean;
}

interface Comment {
    id: number;
    user: User;
    content: string;
    created_at: string;
}

interface Post {
    id: number;
    user_id: number;
    user: User;
    content: string;
    type: string;
    created_at: string;
    files: PostFile[];
    likes_count: number;
    comments_count: number;
    is_liked: boolean;
    comments: Comment[];
}

interface FeedProps {
    auth: any;
    posts: {
        items: Post[];
        hasMore: boolean;
        nextPage: number;
        total: number;
    };
    suggestions: User[];
}

export default function SocialFeed({ auth, posts: initialPosts, suggestions }: FeedProps) {
    const [allPosts, setAllPosts] = useState<Post[]>(initialPosts.items);
    const [hasMore, setHasMore] = useState(initialPosts.hasMore);
    const [nextPage, setNextPage] = useState(initialPosts.nextPage);
    const [loadingMore, setLoadingMore] = useState(false);

    const { data, setData, post, processing, reset, errors } = useForm({
        content: '',
        image: null as any,
    });

    const [previewUrl, setPreviewUrl] = React.useState<string | null>(null);
    const [fullscreenImage, setFullscreenImage] = React.useState<string | null>(null);
    const [activeCommentPost, setActiveCommentPost] = React.useState<number | null>(null);
    const fileInputRef = React.useRef<HTMLInputElement>(null);
    const loadMoreRef = useRef<HTMLDivElement>(null);

    const { data: commentData, setData: setCommentData, post: postComment, processing: commentProcessing, reset: resetComment } = useForm({
        content: '',
    });

    // Estados para Modais
    const [reportingPost, setReportingPost] = useState<number | null>(null);
    const [reportReason, setReportReason] = useState('');
    const [reportDetails, setReportDetails] = useState('');
    const [showReportSuccess, setShowReportSuccess] = useState(false);

    const [editingPost, setEditingPost] = useState<number | null>(null);
    const [editContent, setEditContent] = useState('');
    const [editImage, setEditImage] = useState<File | null>(null);

    const [showDeleteModal, setShowDeleteModal] = useState<number | null>(null);

    // Atualizar posts quando os props mudarem - Apenas se for a primeira página (ex: novo post criado ou recarregamento)
    // Isso evita que o estado seja resetado quando o Inertia atualiza props de outras interações
    React.useEffect(() => {
        // Se a paginação voltar para 2 (próxima da 1), significa que resetou. 
        // Mas a lógica do backend envia "nextPage". Se estamos na página 1, nextPage é 2.
        // Se initialPosts tem items e nextPage é 2, é um reset.
        if (initialPosts.nextPage === 2) {
            setAllPosts(initialPosts.items);
            setHasMore(initialPosts.hasMore);
            setNextPage(initialPosts.nextPage);
        }
    }, [initialPosts]);

    const loadMorePosts = () => {
        if (loadingMore || !hasMore) return;

        setLoadingMore(true);
        axios.get(route('social.feed'), {
            params: { page: nextPage, type: 'json' }
        }).then((response) => {
            const newPosts = response.data.items;
            setAllPosts(prev => [...prev, ...newPosts]);
            setHasMore(response.data.hasMore);
            setNextPage(response.data.nextPage);
            setLoadingMore(false);
        }).catch(() => {
            setLoadingMore(false);
        });
    };

    React.useEffect(() => {
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting && hasMore && !loadingMore) {
                loadMorePosts();
            }
        }, { threshold: 0.1 });

        if (loadMoreRef.current) {
            observer.observe(loadMoreRef.current);
        }

        return () => observer.disconnect();
    }, [hasMore, loadingMore, nextPage]);

    const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0];
        if (file) {
            setData('image', file as any);
            setPreviewUrl(URL.createObjectURL(file));
        }
    };

    const submitPost = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('social.post.store'), {
            onSuccess: () => {
                reset();
                setPreviewUrl(null);
            },
            forceFormData: true,
            preserveScroll: true,
        });
    };

    const handleLike = (postId: number) => {
        router.post(route('social.post.like', postId), {}, {
            preserveScroll: true,
        });
    };

    const handleComment = (e: React.FormEvent, postId: number) => {
        e.preventDefault();
        postComment(route('social.post.comment', postId), {
            onSuccess: () => {
                resetComment();
                setActiveCommentPost(null);
            },
            preserveScroll: true,
        });
    };

    const handleDeletePost = (postId: number) => {
        router.delete(route('social.post.delete', postId), {
            onSuccess: () => setShowDeleteModal(null),
            preserveScroll: true
        });
    };

    const handleReportPost = (e: React.FormEvent) => {
        e.preventDefault();
        router.post(route('social.post.report', reportingPost), {
            reason: reportReason,
            details: reportDetails
        }, {
            onSuccess: () => {
                setReportingPost(null);
                setReportReason('');
                setReportDetails('');
                setShowReportSuccess(true);
            },
            preserveScroll: true
        });
    };

    const handleUpdatePost = (e: React.FormEvent) => {
        e.preventDefault();
        router.post(route('social.post.update', editingPost), {
            _method: 'patch',
            content: editContent,
            image: editImage
        }, {
            forceFormData: true,
            onSuccess: () => {
                setEditingPost(null);
                setEditContent('');
                setEditImage(null);
            },
            preserveScroll: true
        });
    };

    const formatDateTime = (dateStr: string) => {
        const date = new Date(dateStr);
        const now = new Date();
        const diffInSeconds = Math.floor((now.getTime() - date.getTime()) / 1000);

        if (diffInSeconds < 60) return 'agora mesmo';
        const diffInMinutes = Math.floor(diffInSeconds / 60);
        if (diffInMinutes < 60) return `há ${diffInMinutes}m`;
        const diffInHours = Math.floor(diffInMinutes / 60);
        if (diffInHours < 24) return `há ${diffInHours}h`;

        return date.toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit' });
    };

    const isAdmin = auth.user.roles && auth.user.roles.some((r: any) => r.name === 'Administrador' || r.name === 'Suporte');

    return (
        <AuthenticatedLayout>
            <Head title="Feed da Comunidade" />

            <main className="max-w-[1100px] mx-auto px-4 md:px-0 py-10">
                <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    {/* Sidebar Esquerda (Design Perfil) */}
                    <aside className="hidden lg:block lg:col-span-3 space-y-6">
                        <div className="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm text-center">
                            <Link href={route('social.profile')} className="block relative group">
                                <div className="size-20 mx-auto rounded-2xl overflow-hidden border-2 border-primary mb-4 transition-transform group-hover:scale-105">
                                    <img alt={auth.user.name} className="w-full h-full object-cover" src={auth.user.avatar_url || `https://ui-avatars.com/api/?name=${auth.user.name}`} />
                                </div>
                            </Link>
                            <h3 className="font-extrabold text-lg dark:text-white uppercase tracking-tighter">{auth.user.name}</h3>
                            <p className="text-gray-500 text-xs mb-4">@{auth.user.nickname || auth.user.name.toLowerCase()}</p>
                            <div className="flex flex-col gap-2 pt-4 border-t dark:border-gray-800">
                                <Link href={route('social.profile')} className="text-[10px] font-black uppercase text-primary tracking-widest hover:underline">Meu Perfil</Link>
                                <div className="flex items-center justify-center gap-1.5 bg-orange-50 dark:bg-orange-900/20 px-3 py-1 rounded-full border border-orange-100 dark:border-orange-900/30 w-fit mx-auto mt-2">
                                    <span className="material-symbols-outlined text-orange-500 text-xs" style={{ fontVariationSettings: "'FILL' 1" }}>local_fire_department</span>
                                    <span className="text-orange-600 dark:text-orange-400 font-bold text-xs">Ofensiva</span>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
                            <h4 className="text-xs font-black uppercase text-gray-400 tracking-widest mb-4 flex items-center gap-2">
                                <span className="material-symbols-outlined text-xs text-primary">trending_up</span>
                                Ranking
                            </h4>
                            <div className="space-y-4">
                                <p className="text-[10px] text-gray-400 font-bold uppercase text-center italic">Em breve novidades!</p>
                            </div>
                        </div>
                    </aside>

                    {/* Feed Central */}
                    <div className="lg:col-span-6 space-y-6">
                        {/* Criar Post */}
                        <section className="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm transition-all focus-within:shadow-md">
                            <div className="flex gap-4">
                                <div className="size-10 rounded-xl overflow-hidden shrink-0">
                                    <img alt="Avatar" className="w-full h-full object-cover" src={auth.user.avatar_url || `https://ui-avatars.com/api/?name=${auth.user.name}`} />
                                </div>
                                <div className="flex-1 space-y-4">
                                    <form onSubmit={submitPost}>
                                        <textarea 
                                            className="w-full border-none focus:ring-0 text-sm font-bold dark:bg-transparent dark:text-white resize-none p-0 placeholder-gray-400" 
                                            placeholder={`O que você conquistou hoje, ${auth.user.name.split(' ')[0]}?`} 
                                            rows={2}
                                            value={data.content}
                                            onChange={(e) => setData('content', e.target.value)}
                                        ></textarea>
                                        <InputError message={errors.content} className="mt-1" />

                                        {previewUrl && (
                                            <div className="relative mt-2 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 aspect-video bg-gray-50 dark:bg-gray-800">
                                                <img src={previewUrl} alt="Preview" className="w-full h-full object-contain" />
                                                <button 
                                                    type="button"
                                                    onClick={() => { setPreviewUrl(null); setData('image', null); }}
                                                    className="absolute top-2 right-2 size-8 bg-black/50 text-white rounded-full flex items-center justify-center hover:bg-black/70 transition-colors"
                                                >
                                                    <span className="material-symbols-outlined text-sm">close</span>
                                                </button>
                                            </div>
                                        )}

                                        <div className="flex items-center justify-between pt-4 border-t dark:border-gray-800 mt-2">
                                            <div className="flex gap-2">
                                                <input 
                                                    type="file" 
                                                    ref={fileInputRef} 
                                                    className="hidden" 
                                                    accept="image/*"
                                                    onChange={handleFileChange}
                                                />
                                                <button 
                                                    type="button" 
                                                    onClick={() => fileInputRef.current?.click()}
                                                    className={`px-3 py-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl transition-colors flex items-center gap-2 ${data.image ? 'text-primary' : 'text-gray-400 font-bold'}`}
                                                >
                                                    <span className="material-symbols-outlined text-sm">image</span>
                                                    <span className="text-[10px] uppercase font-black tracking-widest">{data.image ? 'Midia OK' : 'Foto'}</span>
                                                </button>
                                            </div>
                                            <button 
                                                disabled={processing || !data.content}
                                                className="bg-primary px-6 py-2 rounded-xl text-gray-900 font-black text-xs uppercase tracking-widest shadow-lg shadow-primary/20 hover:scale-105 active:scale-95 transition-all disabled:opacity-50"
                                            >
                                                Postar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </section>

                        <div className="space-y-6">
                            {allPosts.map((post) => (
                                <article key={post.id} className="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl overflow-hidden shadow-sm transition-all hover:shadow-md">
                                    <div className="p-4 flex items-center justify-between">
                                        <div className="flex items-center gap-3">
                                            <Link href={route('social.profile', post.user.nickname || post.user.id)} className="size-10 rounded-xl overflow-hidden border border-primary shrink-0 relative hover:scale-105 transition-transform">
                                                <img alt={post.user.name} className="w-full h-full object-cover" src={post.user.avatar_url || `https://ui-avatars.com/api/?name=${post.user.name}`} />
                                            </Link>
                                            <div>
                                                <div className="flex items-center gap-2">
                                                    <Link href={route('social.profile', post.user.nickname || post.user.id)} className="font-extrabold text-sm dark:text-white hover:underline uppercase tracking-tighter">{post.user.name}</Link>
                                                    {post.type === 'goal_completed' && <span className="text-[10px] text-gray-400 font-bold uppercase tracking-widest">concluiu uma meta</span>}
                                                </div>
                                                <p className="text-[10px] text-gray-400 flex items-center gap-1 font-bold uppercase"><span className="material-symbols-outlined text-[12px] text-gray-400">schedule</span> {formatDateTime(post.created_at)}</p>
                                            </div>
                                        </div>
                                        
                                        {!(post.user_id == auth.user.id && (post.type === 'goal_completed' || post.type === 'streak_maintained')) && (
                                            <Dropdown>
                                                <Dropdown.Trigger>
                                                    <button className="text-gray-400 hover:text-gray-600 p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800">
                                                        <span className="material-symbols-outlined">more_horiz</span>
                                                    </button>
                                                </Dropdown.Trigger>
                                                <Dropdown.Content>
                                                    {post.user_id == auth.user.id ? (
                                                        !(post.type === 'goal_completed' || post.type === 'streak_maintained') && (
                                                            <>
                                                                <button 
                                                                    onClick={() => { setEditingPost(post.id); setEditContent(post.content); }}
                                                                    className="block w-full px-4 py-2 text-start text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800"
                                                                >
                                                                    Editar
                                                                </button>
                                                                <button 
                                                                    onClick={() => setShowDeleteModal(post.id)}
                                                                    className="block w-full px-4 py-2 text-start text-sm font-bold text-red-600 hover:bg-gray-100 dark:hover:bg-gray-800"
                                                                >
                                                                    Excluir
                                                                </button>
                                                            </>
                                                        )
                                                    ) : (
                                                        <>
                                                            <button 
                                                                onClick={() => router.post(route('social.post.hide', post.id), {}, { preserveScroll: true })}
                                                                className="block w-full px-4 py-2 text-start text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800"
                                                            >
                                                                Ocultar do Feed
                                                            </button>
                                                            <button 
                                                                onClick={() => { setReportingPost(post.id); setReportReason(''); }}
                                                                className="block w-full px-4 py-2 text-start text-sm font-bold text-orange-600 hover:bg-gray-100 dark:hover:bg-gray-800"
                                                            >
                                                                Denunciar Postagem
                                                            </button>
                                                            {isAdmin && (
                                                                <button 
                                                                    onClick={() => setShowDeleteModal(post.id)}
                                                                    className="block w-full px-4 py-2 text-start text-sm font-bold text-red-600 border-t dark:border-gray-800"
                                                                >
                                                                    Excluir (Staff)
                                                                </button>
                                                            )}
                                                        </>
                                                    )}
                                                </Dropdown.Content>
                                            </Dropdown>
                                        )}
                                    </div>

                                    <div className="px-4 pb-2">
                                        {post.type === 'goal_completed' && (
                                            <div className="bg-emerald-50 dark:bg-emerald-900/20 px-3 py-1.5 rounded-xl mb-3 flex items-center gap-2 w-fit">
                                                <span className="material-symbols-outlined text-primary text-xs">check_circle</span>
                                                <span className="text-[10px] font-black text-emerald-700 dark:text-emerald-400 uppercase tracking-widest">Meta Batida</span>
                                            </div>
                                        )}
                                        <p className="text-sm font-medium dark:text-gray-300 leading-relaxed whitespace-pre-wrap">{post.content}</p>
                                    </div>

                                    {post.files && post.files.length > 0 && (
                                        <div className="mt-3 aspect-video bg-gray-50 dark:bg-gray-800 overflow-hidden border-y border-gray-50 dark:border-gray-800">
                                            <img 
                                                src={route('files.show', post.files[0].uuid)} 
                                                alt="Post Content" 
                                                className="w-full h-full object-cover cursor-pointer hover:opacity-95 transition-opacity" 
                                                onClick={() => setFullscreenImage(route('files.show', post.files[0].uuid))}
                                            />
                                        </div>
                                    )}

                                    <div className="p-4 border-t border-gray-50 dark:border-gray-800">
                                        <div className="flex items-center gap-6">
                                            <button 
                                                onClick={() => handleLike(post.id)}
                                                className={`flex items-center gap-1.5 font-bold transition-all hover:scale-110 ${post.is_liked ? 'text-secondary font-black' : 'text-gray-500 hover:text-primary'}`}
                                            >
                                                <span className={`material-symbols-outlined text-sm ${post.is_liked ? 'fill-1' : ''}`}>favorite</span>
                                                <span className="text-xs uppercase tracking-tighter">{post.likes_count || 0} Incentivar</span>
                                            </button>
                                            <button 
                                                onClick={() => setActiveCommentPost(activeCommentPost === post.id ? null : post.id)}
                                                className="flex items-center gap-1.5 text-gray-500 font-bold hover:text-primary transition-colors"
                                            >
                                                <span className="material-symbols-outlined text-sm">chat_bubble</span>
                                                <span className="text-xs uppercase tracking-tighter">{post.comments_count || 0} Comentários</span>
                                            </button>
                                        </div>

                                        {/* Comentários Expandidos */}
                                        {(activeCommentPost === post.id || post.comments_count > 0) && (
                                            <div className="space-y-3 pt-4 mt-4 border-t dark:border-gray-800">
                                                {post.comments?.map((comment) => (
                                                    <div key={comment.id} className="flex gap-2">
                                                        <Link href={route('social.profile', comment.user.nickname || comment.user.id)} className="size-6 rounded-lg overflow-hidden shrink-0 mt-0.5">
                                                            <img src={comment.user.avatar_url || `https://ui-avatars.com/api/?name=${comment.user.name}`} alt="" className="w-full h-full object-cover" />
                                                        </Link>
                                                        <div className="flex-1 bg-gray-50 dark:bg-gray-800 p-2 rounded-xl">
                                                            <div className="flex justify-between items-center mb-0.5">
                                                                <Link href={route('social.profile', comment.user.nickname || comment.user.id)} className="text-[10px] font-black dark:text-white hover:underline uppercase tracking-tighter">
                                                                    {comment.user.name}
                                                                </Link>
                                                                <span className="text-[8px] text-gray-400 font-bold">{formatDateTime(comment.created_at)}</span>
                                                            </div>
                                                            <p className="text-xs text-gray-700 dark:text-gray-300 font-medium">{comment.content}</p>
                                                        </div>
                                                    </div>
                                                ))}

                                                <form onSubmit={(e) => handleComment(e, post.id)} className="flex gap-2 items-center mt-2">
                                                    <input 
                                                        type="text" 
                                                        placeholder="Mande um incentivo..."
                                                        className="flex-1 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-xs focus:ring-primary dark:text-white py-2 px-4 shadow-inner"
                                                        value={activeCommentPost === post.id ? commentData.content : ''}
                                                        onChange={(e) => {
                                                            setActiveCommentPost(post.id);
                                                            setCommentData('content', e.target.value);
                                                        }}
                                                    />
                                                    <button 
                                                        disabled={commentProcessing || !commentData.content || activeCommentPost !== post.id}
                                                        className="text-primary disabled:opacity-30 hover:scale-110 transition-transform"
                                                    >
                                                        <span className="material-symbols-outlined text-sm">send</span>
                                                    </button>
                                                </form>
                                            </div>
                                        )}
                                    </div>
                                </article>
                            ))}

                            {/* Elemento Sentinela para Infinite Scroll */}
                            <div ref={loadMoreRef} className="py-10 text-center">
                                {loadingMore ? (
                                    <div className="flex justify-center gap-2">
                                        <div className="size-2 bg-primary rounded-full animate-bounce"></div>
                                        <div className="size-2 bg-primary rounded-full animate-bounce [animation-delay:-0.15s]"></div>
                                        <div className="size-2 bg-primary rounded-full animate-bounce [animation-delay:-0.3s]"></div>
                                    </div>
                                ) : hasMore ? (
                                    <span className="text-xs font-bold text-gray-400 uppercase tracking-widest">Carregando mais conquistas...</span>
                                ) : allPosts.length > 0 ? (
                                    <span className="text-xs font-bold text-gray-400 uppercase tracking-widest flex items-center justify-center gap-2">
                                        <span className="material-symbols-outlined text-xs">done_all</span>
                                        Você chegou ao fim da trilha!
                                    </span>
                                ) : (
                                    <div className="text-center py-10">
                                        <p className="text-gray-500 font-bold">O feed está silencioso...</p>
                                        <Link href={route('social.index')} className="text-primary font-black uppercase text-[10px] tracking-widest hover:underline mt-4 inline-block">Procurar Amigos</Link>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>

                    {/* Sidebar Direita */}
                    <aside className="hidden lg:block lg:col-span-3 space-y-6">
                        <div className="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
                            <h4 className="text-xs font-black uppercase text-gray-400 tracking-widest mb-4">Sugestões de amigos</h4>
                            <div className="space-y-4">
                                {suggestions.map((user) => (
                                    <div key={user.id} className="flex items-center justify-between">
                                        <div className="flex items-center gap-3">
                                            <Link href={route('social.profile', user.nickname || user.id)} className="size-9 rounded-xl overflow-hidden shrink-0 hover:ring-2 ring-primary transition-all">
                                                <img alt={user.name} className="w-full h-full object-cover" src={user.avatar_url || `https://ui-avatars.com/api/?name=${user.name}`} />
                                            </Link>
                                            <div className="min-w-0">
                                                <p className="text-xs font-black dark:text-white truncate uppercase tracking-tighter">{user.name}</p>
                                                <p className="text-[9px] text-gray-500">@{user.nickname || user.name.split(' ')[0].toLowerCase()}</p>
                                            </div>
                                        </div>
                                        <button 
                                            onClick={() => router.post(route(user.is_following ? 'social.unfollow' : 'social.request.send', user.id))}
                                            className={`p-1.5 rounded-lg transition-colors ${user.is_following ? 'text-red-500 hover:bg-red-50' : 'text-primary hover:bg-primary/10'}`}
                                            title={user.is_following ? 'Parar de seguir' : 'Adicionar amigo'}
                                        >
                                            <span className="material-symbols-outlined text-sm">
                                                {user.is_following ? 'person_remove' : 'person_add'}
                                            </span>
                                        </button>
                                    </div>
                                ))}
                                {suggestions.length === 0 && <p className="text-[10px] text-gray-400 font-bold italic">Nada por aqui no momento.</p>}
                            </div>
                        </div>

                        {/* Banner Promocional / Info */}
                        {/* <div className="bg-gradient-to-br from-gray-900 to-black rounded-2xl p-6 text-white shadow-xl relative overflow-hidden group">
                            <div className="relative z-10">
                                <h5 className="text-[10px] font-black uppercase tracking-[0.2em] text-primary mb-2">Desafio da Semana</h5>
                                <p className="text-sm font-bold leading-tight">Chegue aos 3.000m de atitude em suas conquistas</p>
                                <button className="mt-4 text-[9px] font-black uppercase tracking-widest border border-white/20 px-3 py-1.5 rounded-lg hover:bg-white hover:text-black transition-all">Participar</button>
                            </div>
                            <div className="absolute -bottom-4 -right-4 size-20 bg-primary/20 blur-3xl rounded-full group-hover:bg-primary/40 transition-all"></div>
                        </div> */}
                    </aside>
                </div>
            </main>

            {/* Modais */}
            <Modal show={fullscreenImage !== null} onClose={() => setFullscreenImage(null)} maxWidth="2xl">
                <div className="p-2">
                    <img src={fullscreenImage || ''} alt="Fullscreen" className="w-full h-auto rounded-xl" />
                </div>
            </Modal>

            <Modal show={reportingPost !== null} onClose={() => setReportingPost(null)}>
                <form onSubmit={handleReportPost} className="p-6">
                    <h2 className="text-lg font-black dark:text-white mb-4 uppercase tracking-tighter">Denunciar Postagem</h2>
                    <div className="space-y-4">
                        <div>
                            <InputLabel value="Motivo Principal" children={undefined} />
                            <select 
                                className="w-full mt-1 border-gray-100 dark:border-gray-800 dark:bg-gray-800 dark:text-gray-300 focus:border-primary focus:ring-primary rounded-xl shadow-inner font-bold text-sm"
                                value={reportReason}
                                onChange={(e) => setReportReason(e.target.value)}
                                required
                            >
                                <option value="">Selecione...</option>
                                <option value="spam">Spam / Propaganda</option>
                                <option value="harassment">Assédio / Ofensa</option>
                                <option value="inappropriate">Inadequado</option>
                                <option value="hate_speech">Discurso de Ódio</option>
                                <option value="other">Outro</option>
                            </select>
                        </div>
                        <div>
                            <InputLabel value="O que aconteceu? (opcional)" children={undefined} />
                            <textarea 
                                className="w-full mt-1 border-gray-100 dark:border-gray-800 dark:bg-gray-800 dark:text-gray-300 focus:border-primary focus:ring-primary rounded-xl shadow-inner text-sm font-medium"
                                rows={3}
                                value={reportDetails}
                                onChange={(e) => setReportDetails(e.target.value)}
                            ></textarea>
                        </div>
                    </div>
                    <div className="mt-6 flex justify-end gap-3">
                        <SecondaryButton onClick={() => setReportingPost(null)} disabled={false}>Cancelar</SecondaryButton>
                        <PrimaryButton disabled={!reportReason}>Enviar Agora</PrimaryButton>
                    </div>
                </form>
            </Modal>

            <Modal show={editingPost !== null} onClose={() => setEditingPost(null)}>
                <form onSubmit={handleUpdatePost} className="p-6">
                    <h2 className="text-lg font-black dark:text-white mb-4 uppercase tracking-tighter">Ajustar Conquista</h2>
                    <div className="space-y-4">
                        <div>
                            <InputLabel value="Texto da Postagem" children={undefined} />
                            <textarea 
                                className="w-full mt-1 border-gray-100 dark:border-gray-800 dark:bg-gray-800 dark:text-gray-300 focus:border-primary focus:ring-primary rounded-xl shadow-inner font-bold text-sm"
                                rows={4}
                                value={editContent}
                                onChange={(e) => setEditContent(e.target.value)}
                                required
                            ></textarea>
                        </div>
                    </div>
                    <div className="mt-6 flex justify-end gap-3">
                        <SecondaryButton onClick={() => setEditingPost(null)} disabled={false}>Voltar</SecondaryButton>
                        <PrimaryButton disabled={!editContent}>Atualizar</PrimaryButton>
                    </div>
                </form>
            </Modal>

            <Modal show={showDeleteModal !== null} onClose={() => setShowDeleteModal(null)}>
                <div className="p-6 text-center">
                    <h2 className="text-xl font-black dark:text-white mb-2 uppercase tracking-tighter">Remover do Everest?</h2>
                    <p className="text-gray-500 text-sm font-medium mb-6">Esta conquista será removida permanentemente de sua trilha.</p>
                    <div className="flex justify-center gap-4">
                        <SecondaryButton onClick={() => setShowDeleteModal(null)} disabled={false}>Manter Post</SecondaryButton>
                        <DangerButton onClick={() => showDeleteModal && handleDeletePost(showDeleteModal)} disabled={false}>Excluir Agora</DangerButton>
                    </div>
                </div>
            </Modal>

            {/* Modal de Sucesso (Toast-like) */}
            <Modal show={showReportSuccess} onClose={() => setShowReportSuccess(false)}>
                <div className="p-8 text-center">
                    <span className="material-symbols-outlined text-primary text-6xl mb-4">task_alt</span>
                    <h2 className="text-xl font-black dark:text-white uppercase tracking-tighter">Recebemos sua denúncia</h2>
                    <p className="text-gray-500 font-medium mt-2">Nossa equipe revisará o conteúdo em breve. Obrigado por ajudar a manter a comunidade segura!</p>
                    <button 
                        onClick={() => setShowReportSuccess(false)}
                        className="mt-6 bg-gray-900 dark:bg-white dark:text-black text-white px-8 py-2 rounded-xl text-xs font-black uppercase tracking-widest"
                    >
                        Entendido
                    </button>
                </div>
            </Modal>
        </AuthenticatedLayout>
    );
}

declare function route(name: string, params?: any): string;
