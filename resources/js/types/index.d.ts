export interface User {
    id: number;
    name: string;
    email: string;
    nickname?: string;
    avatar_url?: string;
    email_verified_at?: string;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
    flash: {
        success?: string;
        error?: string;
    };
};
