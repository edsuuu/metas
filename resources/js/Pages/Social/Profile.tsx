import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, useForm, usePage, router } from "@inertiajs/react";
import React, { useState } from "react";
import Dropdown from "@/Components/Dropdown";
import Modal from "@/Components/Modal";
import SecondaryButton from "@/Components/SecondaryButton";
import DangerButton from "@/Components/DangerButton";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import InputLabel from "@/Components/InputLabel";
import InputError from "@/Components/InputError";

interface User {
    id: number;
    name: string;
    nickname: string | null;
    avatar_url: string | null;
    goals_count?: number;
    is_following?: boolean;
}

interface PostFile {
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
    created_at: string;
    files: PostFile[];
    likes_count: number;
    comments_count: number;
    is_liked: boolean;
    comments: Comment[];
}

interface ProfileProps {
    auth: any;
    profileUser: User;
    posts: Post[];
    stats: {
        followers: number;
        following: number;
    };
    xpInfo: {
        current: number;
        level: number;
        next_level_threshold: number;
    };
    streak: number;
}

export default function SocialProfile({
    auth,
    profileUser,
    posts,
    stats,
    xpInfo,
    streak,
}: ProfileProps) {
    const isOwnProfile = auth.user.id === profileUser.id;
    const [fullscreenImage, setFullscreenImage] = React.useState<string | null>(
        null,
    );
    const [activeCommentPost, setActiveCommentPost] = React.useState<
        number | null
    >(null);

    const {
        data: commentData,
        setData: setCommentData,
        post: postComment,
        processing: commentProcessing,
        reset: resetComment,
    } = useForm({
        content: "",
    });

    // Estados para Denúncia
    const [reportingPost, setReportingPost] = useState<number | null>(null);
    const [reportReason, setReportReason] = useState("");
    const [reportDetails, setReportDetails] = useState("");

    // Estados para Edição
    const [editingPost, setEditingPost] = useState<number | null>(null);
    const [editContent, setEditContent] = useState("");
    const [editImage, setEditImage] = useState<File | null>(null);

    const formatDateTime = (dateStr: string) => {
        const date = new Date(dateStr);
        const now = new Date();
        const diffInSeconds = Math.floor(
            (now.getTime() - date.getTime()) / 1000,
        );

        if (diffInSeconds < 60) return "agora";
        const diffInMinutes = Math.floor(diffInSeconds / 60);
        if (diffInMinutes < 60) return `${diffInMinutes}m`;
        const diffInHours = Math.floor(diffInMinutes / 60);
        if (diffInHours < 24) return `${diffInHours}h`;

        return date.toLocaleDateString("pt-BR", {
            day: "2-digit",
            month: "2-digit",
        });
    };

    const handleLike = (postId: number) => {
        router.post(
            route("social.post.like", postId),
            {},
            { preserveScroll: true },
        );
    };

    const handleComment = (e: React.FormEvent, postId: number) => {
        e.preventDefault();
        postComment(route("social.post.comment", postId), {
            onSuccess: () => {
                resetComment();
                setActiveCommentPost(null);
            },
            preserveScroll: true,
        });
    };

    const [showDeleteModal, setShowDeleteModal] = useState<number | null>(null);

    const handleDeletePost = (postId: number) => {
        router.delete(route("social.post.delete", postId), {
            onSuccess: () => setShowDeleteModal(null),
            preserveScroll: true,
        });
    };

    const handleReportPost = (e: React.FormEvent) => {
        e.preventDefault();
        router.post(
            route("social.post.report", reportingPost),
            {
                reason: reportReason,
                details: reportDetails,
            },
            {
                onSuccess: () => {
                    setReportingPost(null);
                    setReportReason("");
                    setReportDetails("");
                },
                preserveScroll: true,
            },
        );
    };

    const handleUpdatePost = (e: React.FormEvent) => {
        e.preventDefault();
        router.post(
            route("social.post.update", editingPost),
            {
                _method: "patch",
                content: editContent,
                image: editImage,
            },
            {
                forceFormData: true,
                onSuccess: () => {
                    setEditingPost(null);
                    setEditContent("");
                    setEditImage(null);
                },
                preserveScroll: true,
            },
        );
    };

    const isAdmin =
        auth.user.roles &&
        auth.user.roles.some(
            (r: any) => r.name === "Administrador" || r.name === "Suporte",
        );

    return (
        <AuthenticatedLayout>
            <Head title={`Perfil de ${profileUser.name}`} />

            <main className="max-w-[1100px] mx-auto pb-20">
                {/* Cabeçalho do Perfil (Novo Design) */}
                <div className="relative bg-white dark:bg-gray-900 border-x border-b border-gray-100 dark:border-gray-800 transition-all duration-300">
                    <div className="h-48 md:h-72 w-full overflow-hidden bg-gradient-to-r from-emerald-400 via-primary to-blue-400">
                        {/* Imagem de Capa (Opcional) */}
                    </div>

                    <div className="px-6 pb-6 relative">
                        <div className="flex flex-col md:flex-row items-start md:items-end gap-6 -mt-16 md:-mt-20">
                            <div className="relative group shrink-0 md:mb-6">
                                <div className="size-32 md:size-40 rounded-full border-[5px] border-white dark:border-gray-900 overflow-hidden bg-white shadow-xl relative group">
                                    <img
                                        alt={profileUser.name}
                                        className="w-full h-full object-cover"
                                        src={
                                            profileUser.avatar_url ||
                                            `https://ui-avatars.com/api/?name=${profileUser.name}&size=200`
                                        }
                                    />
                                    {isOwnProfile && (
                                        <div
                                            onClick={() =>
                                                document
                                                    .getElementById(
                                                        "avatar-upload",
                                                    )
                                                    ?.click()
                                            }
                                            className="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer"
                                        >
                                            <span className="material-symbols-outlined text-white text-2xl">
                                                photo_camera
                                            </span>
                                            <input
                                                id="avatar-upload"
                                                type="file"
                                                className="hidden"
                                                accept="image/*"
                                                onChange={(e) => {
                                                    const file =
                                                        e.target.files?.[0];
                                                    if (file) {
                                                        router.post(
                                                            route(
                                                                "social.profile.avatar",
                                                            ),
                                                            { image: file },
                                                            {
                                                                forceFormData: true,
                                                            },
                                                        );
                                                    }
                                                }}
                                            />
                                        </div>
                                    )}
                                </div>
                            </div>
                            <div className="flex-1 pb-2 space-y-3 w-full md:w-auto">
                                <div className="flex flex-wrap items-center gap-3">
                                    <h1 className="text-2xl md:text-3xl font-extrabold dark:text-white uppercase tracking-tighter">
                                        {profileUser.name}
                                    </h1>
                                    <p className="text-gray-500 dark:text-gray-400 font-medium">
                                        @
                                        {profileUser.nickname ||
                                            profileUser.name
                                                .toLowerCase()
                                                .replace(" ", "_")}
                                    </p>
                                    {isOwnProfile && (
                                        <Link
                                            href={route("profile.edit")}
                                            className="ml-2 text-xs font-bold text-gray-400 hover:text-primary transition-colors flex items-center gap-1"
                                        >
                                            <span className="material-symbols-outlined text-sm">
                                                settings
                                            </span>{" "}
                                            Editar Perfil
                                        </Link>
                                    )}
                                </div>
                                <div className="flex items-center gap-4 w-full">
                                    <div className="flex-1">
                                        <div className="flex justify-between items-end mb-1">
                                            <span className="text-[10px] font-black uppercase text-primary tracking-tighter">
                                                Nível {xpInfo.level}
                                            </span>
                                            <span className="text-[10px] font-bold text-gray-400">
                                                {xpInfo.current} /{" "}
                                                {xpInfo.next_level_threshold *
                                                    xpInfo.level}{" "}
                                                XP
                                            </span>
                                        </div>
                                        <div className="h-1.5 w-full bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                                            <div
                                                className="bg-gradient-to-r from-primary to-blue-400 h-full rounded-full"
                                                style={{
                                                    width: `${(xpInfo.current % 1000) / 10}%`,
                                                }}
                                            ></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {!isOwnProfile && (
                                <div className="flex items-center gap-2 mb-2">
                                    <button
                                        onClick={() =>
                                            router.post(
                                                route(
                                                    profileUser.is_following
                                                        ? "social.unfollow"
                                                        : "social.request.send",
                                                    profileUser.id,
                                                ),
                                            )
                                        }
                                        className={`font-extrabold px-6 py-2.5 rounded-full transition-all shadow-lg shadow-primary/20 ${profileUser.is_following ? "bg-red-500 hover:bg-red-600 text-white" : "bg-primary hover:bg-emerald-400 text-black"}`}
                                    >
                                        {profileUser.is_following
                                            ? "Parar de Seguir"
                                            : "Adicionar amigo"}
                                    </button>
                                </div>
                            )}
                        </div>
                        <div className="mt-6 flex flex-wrap gap-6 border-t border-gray-50 dark:border-gray-800 pt-6">
                            <div className="flex gap-1 items-center">
                                <span className="font-bold dark:text-white">
                                    {stats.followers}
                                </span>
                                <span className="text-gray-500 dark:text-gray-400 text-sm">
                                    Seguidores
                                </span>
                            </div>
                            <div className="flex gap-1 items-center">
                                <span className="font-bold dark:text-white">
                                    {stats.following}
                                </span>
                                <span className="text-gray-500 dark:text-gray-400 text-sm">
                                    Seguindo
                                </span>
                            </div>
                            <div className="flex gap-1 items-center">
                                <span className="font-bold dark:text-white">
                                    {profileUser.goals_count || 0}
                                </span>
                                <span className="text-gray-500 dark:text-gray-400 text-sm">
                                    Metas Batidas
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Grid de Conteúdo */}
                <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 mt-8 px-4 md:px-0">
                    {/* Feed Column */}
                    <div className="lg:col-span-8 space-y-6 order-2 lg:order-1">
                        <h3 className="text-lg font-black dark:text-white flex items-center gap-2 mb-4">
                            <span className="material-symbols-outlined text-primary">
                                dynamic_feed
                            </span>
                            Feed de Metas
                        </h3>

                        {posts.length > 0 ? (
                            posts.map((post) => (
                                <article
                                    key={post.id}
                                    className="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl overflow-hidden shadow-sm transition-all hover:shadow-md"
                                >
                                    <div className="p-4 flex items-center justify-between">
                                        <div className="flex items-center gap-3">
                                            <div className="size-10 rounded-full overflow-hidden border border-primary shrink-0">
                                                <img
                                                    alt={post.user.name}
                                                    className="w-full h-full object-cover"
                                                    src={
                                                        post.user.avatar_url ||
                                                        `https://ui-avatars.com/api/?name=${post.user.name}`
                                                    }
                                                />
                                            </div>
                                            <div>
                                                <div className="flex items-center gap-2">
                                                    <p className="font-bold text-sm dark:text-white">
                                                        {post.user.name}
                                                    </p>
                                                    {post.type ===
                                                        "goal_completed" && (
                                                        <span className="text-xs text-gray-400">
                                                            concluiu uma meta
                                                        </span>
                                                    )}
                                                </div>
                                                <p className="text-xs text-gray-400 flex items-center gap-1">
                                                    <span className="material-symbols-outlined text-[12px]">
                                                        schedule
                                                    </span>{" "}
                                                    {formatDateTime(
                                                        post.created_at,
                                                    )}
                                                </p>
                                            </div>
                                        </div>

                                        {!(
                                            post.user_id == auth.user.id &&
                                            (post.type === "goal_completed" ||
                                                post.type ===
                                                    "streak_maintained")
                                        ) && (
                                            <Dropdown>
                                                <Dropdown.Trigger>
                                                    <button className="text-gray-400 hover:text-gray-600 p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800">
                                                        <span className="material-symbols-outlined">
                                                            more_horiz
                                                        </span>
                                                    </button>
                                                </Dropdown.Trigger>
                                                <Dropdown.Content>
                                                    {post.user_id ==
                                                    auth.user.id ? (
                                                        !(
                                                            post.type ===
                                                                "goal_completed" ||
                                                            post.type ===
                                                                "streak_maintained"
                                                        ) && (
                                                            <>
                                                                <button
                                                                    onClick={() => {
                                                                        setEditingPost(
                                                                            post.id,
                                                                        );
                                                                        setEditContent(
                                                                            post.content,
                                                                        );
                                                                    }}
                                                                    className="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition duration-150 ease-in-out"
                                                                >
                                                                    Editar
                                                                </button>
                                                                <button
                                                                    onClick={() =>
                                                                        setShowDeleteModal(
                                                                            post.id,
                                                                        )
                                                                    }
                                                                    className="block w-full px-4 py-2 text-start text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-800 transition duration-150 ease-in-out"
                                                                >
                                                                    Excluir
                                                                </button>
                                                            </>
                                                        )
                                                    ) : (
                                                        <>
                                                            <button
                                                                onClick={() =>
                                                                    router.post(
                                                                        route(
                                                                            "social.post.hide",
                                                                            post.id,
                                                                        ),
                                                                        {},
                                                                        {
                                                                            preserveScroll: true,
                                                                        },
                                                                    )
                                                                }
                                                                className="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition duration-150 ease-in-out"
                                                            >
                                                                Ocultar da
                                                                Timeline
                                                            </button>
                                                            <button
                                                                onClick={() => {
                                                                    setReportingPost(
                                                                        post.id,
                                                                    );
                                                                    setReportReason(
                                                                        "",
                                                                    );
                                                                }}
                                                                className="block w-full px-4 py-2 text-start text-sm leading-5 text-orange-600 hover:bg-gray-100 dark:hover:bg-gray-800 transition duration-150 ease-in-out"
                                                            >
                                                                Denunciar
                                                                Postagem
                                                            </button>
                                                            {isAdmin && (
                                                                <button
                                                                    onClick={() =>
                                                                        setShowDeleteModal(
                                                                            post.id,
                                                                        )
                                                                    }
                                                                    className="block w-full px-4 py-2 text-start text-sm leading-5 text-red-600 border-t dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800 transition duration-150 ease-in-out"
                                                                >
                                                                    Excluir
                                                                    (Staff)
                                                                </button>
                                                            )}
                                                        </>
                                                    )}
                                                </Dropdown.Content>
                                            </Dropdown>
                                        )}
                                    </div>
                                    <div className="px-4 pb-2">
                                        {post.type === "goal_completed" && (
                                            <div className="bg-emerald-50 dark:bg-emerald-900/20 px-3 py-2 rounded-xl mb-3 flex items-center gap-2">
                                                <span className="material-symbols-outlined text-primary text-sm">
                                                    check_circle
                                                </span>
                                                <span className="text-xs font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wide">
                                                    Meta Batida
                                                </span>
                                            </div>
                                        )}
                                        <p className="text-sm dark:text-gray-300 leading-relaxed whitespace-pre-wrap">
                                            {post.content}
                                        </p>
                                    </div>

                                    {post.files && post.files.length > 0 && (
                                        <div className="mt-3 aspect-video bg-gray-100 dark:bg-gray-800 overflow-hidden">
                                            <img
                                                src={route(
                                                    "files.show",
                                                    post.files[0].uuid,
                                                )}
                                                alt="Post Content"
                                                className="w-full h-full object-cover cursor-pointer hover:scale-[1.02] transition-transform"
                                                onClick={() =>
                                                    setFullscreenImage(
                                                        route(
                                                            "files.show",
                                                            post.files[0].uuid,
                                                        ),
                                                    )
                                                }
                                            />
                                        </div>
                                    )}

                                    <div className="p-4 border-t border-gray-50 dark:border-gray-800">
                                        <div className="flex items-center gap-4">
                                            <button
                                                onClick={() =>
                                                    handleLike(post.id)
                                                }
                                                className={`flex items-center gap-1.5 transition-all hover:scale-110 ${post.is_liked ? "text-secondary font-bold" : "text-gray-500 font-bold"}`}
                                            >
                                                <span
                                                    className={`material-symbols-outlined text-sm ${post.is_liked ? "fill-1" : ""}`}
                                                >
                                                    favorite
                                                </span>
                                                <span className="text-sm">
                                                    {post.likes_count || 0}
                                                </span>
                                            </button>
                                            <button
                                                onClick={() =>
                                                    setActiveCommentPost(
                                                        activeCommentPost ===
                                                            post.id
                                                            ? null
                                                            : post.id,
                                                    )
                                                }
                                                className="flex items-center gap-1.5 text-gray-500 dark:text-gray-400 hover:text-primary transition-colors font-bold"
                                            >
                                                <span className="material-symbols-outlined text-sm">
                                                    chat_bubble
                                                </span>
                                                <span className="text-sm">
                                                    {post.comments_count || 0}
                                                </span>
                                            </button>
                                        </div>

                                        {/* Comentários Expandidos */}
                                        {(activeCommentPost === post.id ||
                                            post.comments_count > 0) && (
                                            <div className="space-y-3 pt-4 mt-4 border-t dark:border-gray-800">
                                                {post.comments?.map(
                                                    (comment) => (
                                                        <div
                                                            key={comment.id}
                                                            className="flex gap-2"
                                                        >
                                                            <Link
                                                                href={route(
                                                                    "social.profile",
                                                                    comment.user
                                                                        .nickname ||
                                                                        comment
                                                                            .user
                                                                            .id,
                                                                )}
                                                                className="size-6 rounded-lg overflow-hidden shrink-0 mt-0.5"
                                                            >
                                                                <img
                                                                    src={
                                                                        comment
                                                                            .user
                                                                            .avatar_url ||
                                                                        `https://ui-avatars.com/api/?name=${comment.user.name}`
                                                                    }
                                                                    alt=""
                                                                    className="w-full h-full object-cover"
                                                                />
                                                            </Link>
                                                            <div className="flex-1 bg-gray-50 dark:bg-gray-800 p-2 rounded-xl">
                                                                <div className="flex justify-between items-center mb-0.5">
                                                                    <Link
                                                                        href={route(
                                                                            "social.profile",
                                                                            comment
                                                                                .user
                                                                                .nickname ||
                                                                                comment
                                                                                    .user
                                                                                    .id,
                                                                        )}
                                                                        className="text-[10px] font-black dark:text-white hover:underline"
                                                                    >
                                                                        {
                                                                            comment
                                                                                .user
                                                                                .name
                                                                        }
                                                                    </Link>
                                                                    <span className="text-[8px] text-gray-400">
                                                                        {formatDateTime(
                                                                            comment.created_at,
                                                                        )}
                                                                    </span>
                                                                </div>
                                                                <p className="text-xs text-gray-700 dark:text-gray-300">
                                                                    {
                                                                        comment.content
                                                                    }
                                                                </p>
                                                            </div>
                                                        </div>
                                                    ),
                                                )}

                                                <form
                                                    onSubmit={(e) =>
                                                        handleComment(
                                                            e,
                                                            post.id,
                                                        )
                                                    }
                                                    className="flex gap-2 items-center mt-2"
                                                >
                                                    <input
                                                        type="text"
                                                        placeholder="Incentive com um comentário..."
                                                        className="flex-1 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-xs focus:ring-primary dark:text-white py-2"
                                                        value={
                                                            activeCommentPost ===
                                                            post.id
                                                                ? commentData.content
                                                                : ""
                                                        }
                                                        onChange={(e) => {
                                                            setActiveCommentPost(
                                                                post.id,
                                                            );
                                                            setCommentData(
                                                                "content",
                                                                e.target.value,
                                                            );
                                                        }}
                                                    />
                                                    <button
                                                        disabled={
                                                            commentProcessing ||
                                                            !commentData.content ||
                                                            activeCommentPost !==
                                                                post.id
                                                        }
                                                        className="text-primary disabled:opacity-50"
                                                    >
                                                        <span className="material-symbols-outlined text-sm">
                                                            send
                                                        </span>
                                                    </button>
                                                </form>
                                            </div>
                                        )}
                                    </div>
                                </article>
                            ))
                        ) : (
                            <div className="text-center py-20 bg-white dark:bg-gray-900 border border-dashed border-gray-100 dark:border-gray-800 rounded-2xl">
                                <p className="text-gray-500 font-bold">
                                    Nenhum marco alcançado ainda.
                                </p>
                            </div>
                        )}
                    </div>

                    {/* Lateral Column - Achievements & Streak */}
                    <aside className="lg:col-span-4 space-y-6 order-1 lg:order-2">
                        {/* <section className="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
                            <h4 className="text-sm font-black uppercase text-gray-400 tracking-widest mb-4">Principais Conquistas</h4>
                            <div className="grid grid-cols-4 gap-3">
                                <div className="aspect-square rounded-xl bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center text-orange-600" title="Líder do Mês">
                                    <span className="material-symbols-outlined text-2xl">workspace_premium</span>
                                </div>
                                <div className="aspect-square rounded-xl bg-primary/10 flex items-center justify-center text-primary" title="Iniciativa 100%">
                                    <span className="material-symbols-outlined text-2xl">bolt</span>
                                </div>
                                <div className="aspect-square rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600" title="Corredor Matinal">
                                    <span className="material-symbols-outlined text-2xl">directions_run</span>
                                </div>
                                <div className="aspect-square rounded-xl bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center text-yellow-600" title="Expert em Foco">
                                    <span className="material-symbols-outlined text-2xl">target</span>
                                </div>
                            </div>
                            <button className="w-full mt-4 text-xs font-bold text-primary hover:underline">Ver todas</button>
                        </section> */}

                        <section className="bg-gradient-to-br from-gray-900 to-black rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
                            <div className="relative z-10">
                                <div className="flex items-center gap-2 mb-4">
                                    <span className="material-symbols-outlined text-orange-500 fill-1">
                                        local_fire_department
                                    </span>
                                    <span className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                        Ritmo Atual
                                    </span>
                                </div>
                                <div className="flex items-baseline gap-1">
                                    <span className="text-4xl font-black">
                                        {streak}
                                    </span>
                                    <span className="text-xs font-bold text-gray-400 uppercase">
                                        {streak <= 1 ? "Dia" : "Dias"} de
                                        Ofensiva
                                    </span>
                                </div>
                                <div className="mt-4 h-1 w-full bg-white/10 rounded-full overflow-hidden">
                                    <div
                                        className="bg-orange-500 h-full transition-all duration-1000"
                                        style={{
                                            width: `${Math.min((streak / 30) * 100, 100)}%`,
                                        }}
                                    ></div>
                                </div>
                                <p className="text-[10px] mt-2 text-gray-500 font-bold uppercase tracking-tight">
                                    Mantenha a chama acesa!
                                </p>
                            </div>
                            {/* Efeito visual de fundo */}
                            <div className="absolute top-0 right-0 -mr-8 -mt-8 size-32 bg-orange-500/10 blur-[60px] rounded-full"></div>
                        </section>
                    </aside>
                </div>
            </main>

            {/* Modais Customizados */}
            <Modal
                show={fullscreenImage !== null}
                onClose={() => setFullscreenImage(null)}
                maxWidth="4xl"
                transparent={true}
            >
                <div className="p-0 flex justify-center bg-black/5">
                    <img
                        src={fullscreenImage || ""}
                        alt="Fullscreen"
                        className="max-w-full max-h-[85vh] w-auto h-auto mx-auto object-contain"
                    />
                </div>
            </Modal>

            <Modal
                show={reportingPost !== null}
                onClose={() => setReportingPost(null)}
            >
                <form onSubmit={handleReportPost} className="p-6">
                    <h2 className="text-lg font-bold dark:text-white mb-4">
                        Denunciar Postagem
                    </h2>
                    <div className="space-y-4">
                        <div>
                            <InputLabel
                                value="Motivo da Denúncia"
                                children={undefined}
                            />
                            <select
                                className="w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary focus:ring-primary rounded-xl shadow-sm"
                                value={reportReason}
                                onChange={(e) =>
                                    setReportReason(e.target.value)
                                }
                                required
                            >
                                <option value="">Selecione um motivo...</option>
                                <option value="spam">Spam / Propaganda</option>
                                <option value="harassment">
                                    Assédio / Ofensa
                                </option>
                                <option value="inappropriate">
                                    Conteúdo Inadequado
                                </option>
                                <option value="hate_speech">
                                    Discurso de Ódio
                                </option>
                                <option value="other">Outro</option>
                            </select>
                        </div>
                        <div>
                            <InputLabel
                                value="Detalhes (opcional)"
                                children={undefined}
                            />
                            <textarea
                                className="w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary focus:ring-primary rounded-xl shadow-sm"
                                rows={3}
                                value={reportDetails}
                                onChange={(e) =>
                                    setReportDetails(e.target.value)
                                }
                            ></textarea>
                        </div>
                    </div>
                    <div className="mt-6 flex justify-end gap-3">
                        <SecondaryButton
                            onClick={() => setReportingPost(null)}
                            disabled={false}
                        >
                            Cancelar
                        </SecondaryButton>
                        <PrimaryButton disabled={!reportReason}>
                            Enviar Denúncia
                        </PrimaryButton>
                    </div>
                </form>
            </Modal>

            <Modal
                show={editingPost !== null}
                onClose={() => setEditingPost(null)}
            >
                <form onSubmit={handleUpdatePost} className="p-6">
                    <h2 className="text-lg font-bold dark:text-white mb-4">
                        Editar Postagem
                    </h2>
                    <div className="space-y-4">
                        <div>
                            <InputLabel value="Conteúdo" children={undefined} />
                            <textarea
                                className="w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary focus:ring-primary rounded-xl shadow-sm"
                                rows={4}
                                value={editContent}
                                onChange={(e) => setEditContent(e.target.value)}
                                required
                            ></textarea>
                        </div>
                    </div>
                    <div className="mt-6 flex justify-end gap-3">
                        <SecondaryButton
                            onClick={() => setEditingPost(null)}
                            disabled={false}
                        >
                            Cancelar
                        </SecondaryButton>
                        <PrimaryButton disabled={!editContent}>
                            Salvar Alterações
                        </PrimaryButton>
                    </div>
                </form>
            </Modal>

            <Modal
                show={showDeleteModal !== null}
                onClose={() => setShowDeleteModal(null)}
            >
                <div className="p-6 text-center">
                    <h2 className="text-xl font-black dark:text-white mb-2 uppercase tracking-tighter">
                        Confirmar Exclusão
                    </h2>
                    <p className="text-gray-500 dark:text-gray-400 mb-6">
                        Esta ação é permanente. Deseja mesmo excluir este post?
                    </p>
                    <div className="flex justify-center gap-4">
                        <SecondaryButton
                            onClick={() => setShowDeleteModal(null)}
                            disabled={false}
                        >
                            Manter Post
                        </SecondaryButton>
                        <DangerButton
                            onClick={() =>
                                showDeleteModal &&
                                handleDeletePost(showDeleteModal)
                            }
                            disabled={false}
                        >
                            Excluir Agora
                        </DangerButton>
                    </div>
                </div>
            </Modal>
        </AuthenticatedLayout>
    );
}

declare function route(name: string, params?: any): string;
