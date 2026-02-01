import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm, router } from '@inertiajs/react';
import React from 'react';
import Dropdown from '@/Components/Dropdown';

interface User {
    id: number;
    name: string;
    nickname: string | null;
    avatar_url: string | null;
}

interface File {
    id: number;
    uuid: string;
    path: string;
    filename: string;
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
    is_edited: boolean;
    created_at: string;
    updated_at: string;
    files: File[];
    likes_count: number;
    comments_count: number;
    is_liked: boolean;
    comments: Comment[];
}

export default function Show({ auth, post }: { auth: any, post: Post }) {
    const { data, setData, post: postComment, processing, reset } = useForm({
        content: '',
    });

    const formatDateTime = (dateStr: string) => {
        const date = new Date(dateStr);
        return date.toLocaleDateString('pt-BR', { 
            day: '2-digit', 
            month: '2-digit', 
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    };

    const handleLike = () => {
        router.post(route('social.post.like', post.id), {}, { preserveScroll: true });
    };

    const handleComment = (e: React.FormEvent) => {
        e.preventDefault();
        postComment(route('social.post.comment', post.id), {
            onSuccess: () => reset(),
            preserveScroll: true,
        });
    };

    return (
        <AuthenticatedLayout>
            <Head title={`Postagem de ${post.user.name}`} />

            <main className="max-w-[800px] mx-auto py-10 px-4">
                <Link href={route('social.feed')} className="flex items-center gap-2 text-gray-500 hover:text-primary mb-6 transition-colors font-bold">
                    <span className="material-symbols-outlined">arrow_back</span>
                    Voltar ao Feed
                </Link>

                <article className="bg-white dark:bg-gray-800 border border-[#dbe6e1] dark:border-gray-700 rounded-3xl overflow-hidden shadow-sm">
                    <div className="p-6">
                        <div className="flex justify-between items-start mb-4">
                            <Link href={route('social.profile', post.user.nickname || post.user.id)} className="flex gap-4">
                                <div className="size-12 rounded-2xl overflow-hidden border-2 border-primary shrink-0">
                                    <img alt={post.user.name} className="w-full h-full object-cover" src={post.user.avatar_url || `https://ui-avatars.com/api/?name=${post.user.name}`} />
                                </div>
                                <div>
                                    <h4 className="font-black dark:text-white flex items-center gap-2 hover:underline">
                                        {post.user.name}
                                    </h4>
                                    <p className="text-xs text-gray-500 font-medium whitespace-nowrap">
                                        {formatDateTime(post.created_at)}
                                        {post.is_edited && (
                                            <span className="ml-2 text-[10px] text-gray-400 font-bold">(editado em {formatDateTime(post.updated_at)})</span>
                                        )}
                                    </p>
                                </div>
                            </Link>
                        </div>

                        <p className="text-lg font-bold text-gray-800 dark:text-gray-200 mb-6 whitespace-pre-wrap leading-relaxed">
                            {post.content}
                        </p>

                        {post.files && post.files.length > 0 && (
                            <div className="mb-6 rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                                <img 
                                    src={route('files.show', post.files[0].uuid)} 
                                    alt="Conteúdo" 
                                    className="w-full h-auto max-h-[800px] object-contain" 
                                />
                            </div>
                        )}

                        <div className="flex items-center gap-6 py-4 border-y dark:border-gray-700 mb-6">
                            <button 
                                onClick={handleLike}
                                className={`flex items-center gap-2 font-bold hover:scale-110 transition-transform ${post.is_liked ? 'text-red-500' : 'text-primary'}`}
                            >
                                <span className={`material-symbols-outlined ${post.is_liked ? 'fill-1' : ''}`}>favorite</span>
                                <span>{post.likes_count} Incentivar</span>
                            </button>
                            <div className="flex items-center gap-2 text-gray-500 font-bold">
                                <span className="material-symbols-outlined">chat_bubble</span>
                                <span>{post.comments_count} Comentários</span>
                            </div>
                        </div>

                        <div className="space-y-6">
                            {post.comments?.map((comment) => (
                                <div key={comment.id} className="flex gap-4 bg-gray-50/50 dark:bg-gray-700/30 p-4 rounded-2xl">
                                    <Link href={route('social.profile', comment.user.nickname || comment.user.id)} className="size-10 rounded-xl overflow-hidden shrink-0">
                                        <img src={comment.user.avatar_url || `https://ui-avatars.com/api/?name=${comment.user.name}`} alt="" className="w-full h-full object-cover" />
                                    </Link>
                                    <div className="flex-1">
                                        <div className="flex justify-between items-center mb-1">
                                            <Link href={route('social.profile', comment.user.nickname || comment.user.id)} className="text-sm font-black dark:text-white hover:underline">
                                                {comment.user.name}
                                            </Link>
                                            <span className="text-[10px] text-gray-400">{formatDateTime(comment.created_at)}</span>
                                        </div>
                                        <p className="text-sm text-gray-700 dark:text-gray-300">{comment.content}</p>
                                    </div>
                                </div>
                            ))}

                            <form onSubmit={handleComment} className="flex gap-4 items-center mt-8">
                                <div className="size-10 rounded-xl overflow-hidden shrink-0">
                                    <img src={auth.user.avatar_url || `https://ui-avatars.com/api/?name=${auth.user.name}`} alt="" className="w-full h-full object-cover" />
                                </div>
                                <input 
                                    type="text" 
                                    placeholder="Escreva um comentário..."
                                    className="flex-1 bg-gray-100 dark:bg-gray-700 border-none rounded-2xl text-sm focus:ring-primary dark:text-white h-12 px-6"
                                    value={data.content}
                                    onChange={(e) => setData('content', e.target.value)}
                                />
                                <button 
                                    disabled={processing || !data.content}
                                    className="bg-primary size-12 rounded-2xl flex items-center justify-center text-gray-900 shadow-lg shadow-primary/20 hover:scale-105 active:scale-95 transition-all disabled:opacity-50"
                                >
                                    <span className="material-symbols-outlined">send</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </article>
            </main>
        </AuthenticatedLayout>
    );
}

declare function route(name: string, params?: any): string;
