import type { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from '@lucide/vue';

export type BreadcrumbItem = {
    /**
     * A translation key, not a label. A page declares its breadcrumbs in
     * `defineOptions`, which the compiler hoists out of `setup()` and evaluates
     * when the module loads — before the locale is known. Translating in
     * `Breadcrumbs.vue` instead keeps the label in the current language.
     */
    titleKey: string;
    href: NonNullable<InertiaLinkProps['href']>;
};

export type NavItem = {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
};
