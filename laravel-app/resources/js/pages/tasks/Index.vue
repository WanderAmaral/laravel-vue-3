<script setup lang="ts">
import FlashMessage from '@/components/FlashMessage.vue';
import Pagination from '@/components/Pagination.vue';
import Button from '@/components/ui/button/Button.vue';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { CirclePlus } from 'lucide-vue-next';
import { ref } from 'vue';

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

const dialogOpen = ref(false);

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

const form = useForm({
    name: '',
    started_at: minDateTime,
    finished_at: '',
});

function openDialog() {
    dialogOpen.value = true;
}

function closeDialog() {
    dialogOpen.value = false;
    form.reset();
    form.clearErrors();
}

function submit() {
    form.post('/tasks', {
        onSuccess: () => {
            closeDialog();
        },
    });
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
                    <Dialog v-model:open="dialogOpen">
                        <Button
                            type="button"
                            class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-emerald-700 focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2 focus-visible:ring-offset-slate-900 focus-visible:outline-none dark:focus-visible:ring-offset-slate-900"
                            @click="openDialog"
                        >
                            <CirclePlus class="h-4 w-4" />
                            <span>New Task</span>
                        </Button>

                        <DialogContent>
                            <DialogHeader>
                                <DialogTitle>New Task</DialogTitle>
                                <DialogDescription>
                                    Preencha os campos abaixo para cadastrar uma
                                    nova tarefa.
                                </DialogDescription>
                            </DialogHeader>

                            <form
                                class="mt-4 space-y-4"
                                @submit.prevent="submit"
                            >
                                <!-- Nome -->
                                <div>
                                    <label
                                        for="task-name"
                                        class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200"
                                    >
                                        Name <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        id="task-name"
                                        v-model="form.name"
                                        type="text"
                                        class="block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-50"
                                        required
                                    />
                                    <p
                                        v-if="form.errors.name"
                                        class="mt-1 text-xs text-red-500"
                                    >
                                        {{ form.errors.name }}
                                    </p>
                                </div>

                                <!-- Datas -->
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label
                                            for="task-started-at"
                                            class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200"
                                        >
                                            Start
                                        </label>
                                        <input
                                            id="task-started-at"
                                            type="datetime-local"
                                            v-model="form.started_at"
                                            :min="minDateTime"
                                            class="block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-50"
                                        />
                                        <p
                                            v-if="form.errors.started_at"
                                            class="mt-1 text-xs text-red-500"
                                        >
                                            {{ form.errors.started_at }}
                                        </p>
                                    </div>

                                    <div>
                                        <label
                                            for="task-finished-at"
                                            class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200"
                                        >
                                            End
                                        </label>
                                        <input
                                            id="task-finished-at"
                                            v-model="form.finished_at"
                                            :min="minDateTime"
                                            :max="maxDateTime"
                                            type="datetime-local"
                                            class="block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-50"
                                        />
                                        <p
                                            v-if="form.errors.finished_at"
                                            class="mt-1 text-xs text-red-500"
                                        >
                                            {{ form.errors.finished_at }}
                                        </p>
                                    </div>
                                </div>

                                <DialogFooter
                                    class="mt-4 flex items-center justify-end gap-3"
                                >
                                    <Button
                                        type="button"
                                        variant="outline"
                                        class="border-slate-300 text-slate-700 hover:bg-slate-50 dark:border-slate-600 dark:text-slate-100 dark:hover:bg-slate-800"
                                        @click="closeDialog"
                                    >
                                        Cancelar
                                    </Button>

                                    <Button
                                        type="submit"
                                        class="bg-emerald-600 text-white hover:bg-emerald-700"
                                        :disabled="form.processing"
                                    >
                                        <span v-if="form.processing"
                                            >Salvando...</span
                                        >
                                        <span v-else>Salvar task</span>
                                    </Button>
                                </DialogFooter>
                            </form>
                        </DialogContent>
                    </Dialog>
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
                                        <Link
                                            :href="`/tasks/${task.id}/edit`"
                                            class="inline-flex items-center rounded-md bg-amber-500/90 px-3 py-1.5 text-xs font-medium text-white hover:bg-amber-600"
                                        >
                                            Edit
                                        </Link>
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
