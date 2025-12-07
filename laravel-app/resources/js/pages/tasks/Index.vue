<script setup lang="ts">
import FlashMessage from '@/components/FlashMessage.vue';
import Pagination from '@/components/Pagination.vue';
import TaskFormDialog from '@/components/TaskFormDialog.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { CirclePlus } from 'lucide-vue-next';

export interface Vehicle {
    id: number;
    name: string;
    started_at: string | null;
    finished_at: string | null;
}

const props = defineProps<{
    tasks: {
        data: Vehicle[];
        links: {
            url: string | null;
            label: string;
            active: boolean;
        }[];
    };
}>();

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Tasks',
        href: '',
    },
];

const now = new Date();

const pad = (n: number) => String(n).padStart(2, '0');

const formatDateTime = (d: Date) =>
    d.getFullYear() +
    '-' +
    pad(d.getMonth() + 1) +
    '-' +
    pad(d.getDate()) +
    'T' +
    pad(d.getHours()) +
    ':' +
    pad(d.getMinutes());

const nextWeek = new Date(now);
nextWeek.setDate(nextWeek.getDate() + 7);

const minDateTime = formatDateTime(now);
const maxDateTime = formatDateTime(nextWeek);

const dialogOpen = ref(false);
const taskEdit = ref<Vehicle | null>(null);

function openCreate() {
    taskEdit.value = null;
    dialogOpen.value = true;
}

function openEdit(task: Vehicle) {
    taskEdit.value = task;
    dialogOpen.value = true;
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Tasks" />

        <div class="space-y-6 p-4 text-slate-900 dark:text-slate-50">
            <!-- Título + botão -->
            <div
                class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
            >
                <div>
                    <h1 class="text-xl font-semibold">Tasks</h1>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Visualize, edite e acompanhe o status das suas tarefas.
                    </p>
                </div>

                <div
                    class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
                >
                    <!-- Botão + Dialog -->
                    <Button
                        class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-emerald-700 focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2 focus-visible:ring-offset-slate-900 focus-visible:outline-none dark:focus-visible:ring-offset-slate-900"
                        @click="openCreate"
                    >
                        <CirclePlus class="h-4 w-4" />
                        New Task
                    </Button>
                    <TaskFormDialog
                        :open="dialogOpen"
                        :task="taskEdit"
                        :url="taskEdit ? `/tasks/${taskEdit.id}` : '/tasks'"
                        :method="taskEdit ? 'put' : 'post'"
                        :min-date-time="minDateTime"
                        :max-date-time="maxDateTime"
                        @close="dialogOpen = false"
                        @success="dialogOpen = false"
                    />
                </div>
            </div>

            <FlashMessage />

            <!-- Card da tabela -->
            <div
                class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900"
            >
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead
                            class="border-b border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-800"
                        >
                            <tr>
                                <th
                                    scope="col"
                                    class="rounded-tl-lg px-4 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase dark:text-slate-400"
                                >
                                    ID
                                </th>
                                <th
                                    scope="col"
                                    class="px-4 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase dark:text-slate-400"
                                >
                                    Name
                                </th>
                                <th
                                    scope="col"
                                    class="px-4 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase dark:text-slate-400"
                                >
                                    Start
                                </th>
                                <th
                                    scope="col"
                                    class="px-4 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase dark:text-slate-400"
                                >
                                    End
                                </th>
                                <th
                                    scope="col"
                                    class="rounded-tr-xl px-4 py-3 text-right text-xs font-semibold tracking-wide text-slate-500 uppercase dark:text-slate-400"
                                >
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <!-- Quando tem registros -->
                        <tbody
                            v-if="props.tasks.data.length"
                            class="divide-y divide-slate-100 dark:divide-slate-800"
                        >
                            <tr
                                v-for="task in props.tasks.data"
                                :key="task.id"
                                class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/70"
                            >
                                <td
                                    class="px-4 py-3 text-slate-600 dark:text-slate-300"
                                >
                                    #{{ task.id }}
                                </td>
                                <td
                                    class="px-4 py-3 font-medium text-slate-900 dark:text-slate-50"
                                >
                                    {{ task.name }}
                                </td>
                                <td
                                    class="px-4 py-3 text-slate-600 dark:text-slate-300"
                                >
                                    {{
                                        task.started_at
                                            ? new Date(
                                                  task.started_at,
                                              ).toLocaleString()
                                            : 'N/A'
                                    }}
                                </td>
                                <td
                                    class="px-4 py-3 text-slate-600 dark:text-slate-300"
                                >
                                    {{
                                        task.finished_at
                                            ? new Date(
                                                  task.finished_at,
                                              ).toLocaleString()
                                            : 'N/A'
                                    }}
                                </td>
                                <td class="px-4 py-3">
                                    <div
                                        class="flex items-center justify-end gap-2"
                                    >
                                        <Link
                                            :href="`/tasks/${task.id}/`"
                                            class="inline-flex items-center rounded-md border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-600 dark:text-slate-100 dark:hover:bg-slate-800"
                                        >
                                            Show
                                        </Link>
                                        <Button
                                            class="inline-flex items-center rounded-md bg-amber-500/90 px-3 py-1.5 text-xs font-medium text-white hover:bg-amber-600 cursor-pointer"
                                            @click="openEdit(task)"
                                        >
                                            Edit
                                        </Button>

                                        <DeleteButton
                                            :url="`/tasks/${task.id}`"
                                            title="Do you really want to delete this task?"
                                        />
                                    </div>
                                </td>
                            </tr>
                        </tbody>

                        <!-- Estado vazio -->
                        <tbody v-else>
                            <tr>
                                <td
                                    colspan="5"
                                    class="px-4 py-10 text-center text-sm text-slate-500 dark:text-slate-400"
                                >
                                    Nenhuma task cadastrada no momento.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <div
                    class="flex justify-center border-t border-slate-200 px-4 py-3 dark:border-slate-700"
                >
                    <Pagination :links="props.tasks.links" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
