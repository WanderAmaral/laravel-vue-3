<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, PencilLine, Trash2 } from 'lucide-vue-next';
import { computed } from 'vue';

export interface Task {
    id: number;
    name: string;
    started_at: string | null;
    finished_at: string | null;
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    task: Task;
}>();

const breadcrumbItems = [
    {
        title: 'Tasks',
        href: '/tasks',
    },
    {
        title: 'Task Details',
        href: '',
    },
];

const formatDateTime = (value: string | null): string | null => {
    if (!value) return null;
    const d = new Date(value);
    if (Number.isNaN(d.getTime())) return value;
    return d.toLocaleString();
};

const taskStatus = computed(() => {
    if (props.task.finished_at) {
        return {
            label: 'Concluída',
            classes:
                'inline-flex items-center gap-1 rounded-full bg-emerald-500/15 px-2.5 py-0.5 text-[11px] font-medium text-emerald-400 ring-1 ring-inset ring-emerald-500/40',
        };
    }
    if (props.task.started_at) {
        return {
            label: 'Em andamento',
            classes:
                'inline-flex items-center gap-1 rounded-full bg-amber-500/15 px-2.5 py-0.5 text-[11px] font-medium text-amber-400 ring-1 ring-inset ring-amber-500/40',
        };
    }
    return {
        label: 'Pendente',
        classes:
            'inline-flex items-center gap-1 rounded-full bg-slate-500/15 px-2.5 py-0.5 text-[11px] font-medium text-slate-300 ring-1 ring-inset ring-slate-500/40',
    };
});

const duration = computed(() => {
    const { started_at, finished_at } = props.task;
    if (!started_at || !finished_at) return null;

    const start = new Date(started_at);
    const end = new Date(finished_at);
    const diffMs = end.getTime() - start.getTime();

    if (!Number.isFinite(diffMs) || diffMs <= 0) return null;

    const totalMinutes = Math.floor(diffMs / 60000);
    const hours = Math.floor(totalMinutes / 60);
    const minutes = totalMinutes % 60;

    if (hours && minutes) return `${hours}h ${minutes}min`;
    if (hours) return `${hours}h`;
    return `${minutes}min`;
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Task Details" />

        <div class="p-4 text-slate-900 sm:p-6 lg:p-8 dark:text-slate-50">
            <!-- Header -->
            <div
                class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
            >
                <div>
                    <div
                        class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400"
                    >
                        <span
                            class="rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-medium tracking-wide uppercase dark:bg-slate-800"
                        >
                            Task #{{ props.task.id }}
                        </span>

                        <span :class="taskStatus.classes">
                            {{ taskStatus.label }}
                        </span>
                    </div>

                    <h1 class="mt-2 text-2xl leading-tight font-semibold">
                        {{ props.task.name || 'Task Details' }}
                    </h1>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Task information and data history.
                    </p>
                </div>

                <div class="flex flex-wrap justify-start gap-2 sm:justify-end">
                    <!-- Voltar -->
                    <Link
                        href="/tasks"
                        class="inline-flex items-center justify-center gap-2 rounded-md border border-slate-500/70 bg-slate-800 px-3 py-1.5 text-xs font-medium text-slate-100 transition hover:bg-slate-700 sm:text-sm dark:border-slate-600"
                    >
                        <ArrowLeft class="h-4 w-4" />
                        <span>Back</span>
                    </Link>

                    <!-- Editar -->
                    <Link
                        :href="`/tasks/${props.task.id}/edit`"
                        class="inline-flex items-center justify-center gap-2 rounded-md bg-emerald-600 px-3 py-1.5 text-xs font-medium text-white shadow-sm transition hover:bg-emerald-700 sm:text-sm"
                    >
                        <PencilLine class="h-4 w-4" />
                        <span>Edit</span>
                    </Link>

                    <!-- Excluir -->
                    <Link
                        as="button"
                        method="delete"
                        :href="`/tasks/${props.task.id}`"
                        class="inline-flex items-center justify-center gap-2 rounded-md bg-rose-600 px-3 py-1.5 text-xs font-medium text-white shadow-sm transition hover:bg-rose-700 sm:text-sm"
                    >
                        <Trash2 class="h-4 w-4" />
                        <span>Delete</span>
                    </Link>
                </div>
            </div>

            <!-- Card com "cara" de tabela -->
            <div
                class="rounded-xl border border-slate-200 bg-white/90 p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900"
            >
                <h2
                    class="text-sm font-semibold tracking-wide text-slate-500 uppercase dark:text-slate-400"
                >
                    Details Task
                </h2>

                <div
                    class="mt-4 overflow-hidden rounded-lg border border-slate-200 dark:border-slate-700"
                >
                    <table class="min-w-full text-sm">
                        <tbody
                            class="divide-y divide-slate-200 dark:divide-slate-800"
                        >
                            <!-- Nome -->
                            <tr>
                                <th
                                    scope="row"
                                    class="w-40 bg-slate-50 px-4 py-2 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase sm:w-56 dark:bg-slate-800 dark:text-slate-400"
                                >
                                    Name
                                </th>
                                <td
                                    class="px-4 py-2 text-slate-900 dark:text-slate-50"
                                >
                                    {{ props.task.name }}
                                </td>
                            </tr>

                            <!-- ID -->
                            <tr>
                                <th
                                    scope="row"
                                    class="w-40 bg-slate-50 px-4 py-2 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase sm:w-56 dark:bg-slate-800 dark:text-slate-400"
                                >
                                    ID
                                </th>
                                <td
                                    class="px-4 py-2 text-slate-900 dark:text-slate-50"
                                >
                                    #{{ props.task.id }}
                                </td>
                            </tr>

                            <!-- Criada em -->
                            <tr>
                                <th
                                    scope="row"
                                    class="w-40 bg-slate-50 px-4 py-2 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase sm:w-56 dark:bg-slate-800 dark:text-slate-400"
                                >
                                    Created at
                                </th>
                                <td
                                    class="px-4 py-2 text-slate-900 dark:text-slate-50"
                                >
                                    {{ formatDateTime(props.task.created_at) }}
                                </td>
                            </tr>

                            <!-- Atualizada em -->
                            <tr>
                                <th
                                    scope="row"
                                    class="w-40 bg-slate-50 px-4 py-2 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase sm:w-56 dark:bg-slate-800 dark:text-slate-400"
                                >
                                    update at
                                </th>
                                <td
                                    class="px-4 py-2 text-slate-900 dark:text-slate-50"
                                >
                                    {{ formatDateTime(props.task.updated_at) }}
                                </td>
                            </tr>

                            <!-- Início -->
                            <tr>
                                <th
                                    scope="row"
                                    class="w-40 bg-slate-50 px-4 py-2 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase sm:w-56 dark:bg-slate-800 dark:text-slate-400"
                                >
                                    Start
                                </th>
                                <td
                                    class="px-4 py-2 text-slate-900 dark:text-slate-50"
                                >
                                    <span v-if="props.task.started_at">
                                        {{
                                            formatDateTime(
                                                props.task.started_at,
                                            )
                                        }}
                                    </span>
                                    <span
                                        v-else
                                        class="text-slate-400 italic dark:text-slate-500"
                                    >
                                        Not started
                                    </span>
                                </td>
                            </tr>

                            <!-- Conclusão -->
                            <tr>
                                <th
                                    scope="row"
                                    class="w-40 bg-slate-50 px-4 py-2 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase sm:w-56 dark:bg-slate-800 dark:text-slate-400"
                                >
                                    Conclued
                                </th>
                                <td
                                    class="px-4 py-2 text-slate-900 dark:text-slate-50"
                                >
                                    <span v-if="props.task.finished_at">
                                        {{
                                            formatDateTime(
                                                props.task.finished_at,
                                            )
                                        }}
                                    </span>
                                    <span
                                        v-else
                                        class="text-slate-400 italic dark:text-slate-500"
                                    >
                                        Not finished
                                    </span>
                                </td>
                            </tr>

                            <!-- Duração -->
                            <tr>
                                <th
                                    scope="row"
                                    class="w-40 bg-slate-50 px-4 py-2 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase sm:w-56 dark:bg-slate-800 dark:text-slate-400"
                                >
                                    Duration
                                </th>
                                <td
                                    class="px-4 py-2 text-slate-900 dark:text-slate-50"
                                >
                                    <span v-if="duration">
                                        {{ duration }}
                                    </span>
                                    <span
                                        v-else
                                        class="text-slate-400 italic dark:text-slate-500"
                                    >
                                        —
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
