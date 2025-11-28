<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

defineProps<{
    links: PaginationLink[];
}>();
</script>

<template>
    <!-- Só mostra se tiver mais de 1 link -->
    <nav
        v-if="links && links.length > 1"
        class="inline-flex items-center gap-1"
        aria-label="Pagination"
    >
        <template v-for="(link, index) in links" :key="index">
            <!-- Links desabilitados / reticências -->
            <span
                v-if="!link.url"
                class="inline-flex min-w-[2.25rem] justify-center px-3 py-1.5 text-xs font-medium select-none
                       border rounded-md first:rounded-l-md last:rounded-r-md
                       border-slate-200 bg-slate-50 text-slate-400
                       dark:border-slate-700 dark:bg-slate-800 dark:text-slate-500"
                v-html="link.label"
            />

            <!-- Links clicáveis -->
            <Link
                v-else
                :href="link.url"
                preserve-scroll
                class="inline-flex min-w-[2.25rem] justify-center px-3 py-1.5 text-xs font-medium
                       border rounded-md first:rounded-l-md last:rounded-r-md
                       border-slate-200 bg-white text-slate-700 hover:bg-slate-50
                       dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800
                       transition"
                :class="{
                    // Página atual
                    '!border-transparent !bg-emerald-600 !text-white hover:!bg-emerald-700 dark:!bg-emerald-500 dark:hover:!bg-emerald-600':
                        link.active,
                }"
            >
                <span v-html="link.label" />
            </Link>
        </template>
    </nav>
</template>
