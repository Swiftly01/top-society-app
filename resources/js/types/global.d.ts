import type { Auth } from '@/types/auth';
import { SiteShared } from './content';

declare module 'react' {
    interface InputHTMLAttributes<T> {
        passwordrules?: string;
    }
}

declare module '@inertiajs/core' {
    export interface InertiaConfig {
        sharedPageProps: {
            name: string;
            auth: Auth;
            sidebarOpen: boolean;
            site: SiteShared;
            [key: string]: unknown;
        };
    }
}
