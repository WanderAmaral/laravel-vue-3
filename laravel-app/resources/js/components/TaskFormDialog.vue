<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import {
    Dialog,
    DialogContent,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { useForm } from '@inertiajs/vue3';
import { computed, watch } from 'vue';

const props = defineProps<{
    open: boolean;
    task?: any | null;
    url: string;
    method?: 'post' | 'put';
}>();

const emit = defineEmits(['close', 'success']);

const form = useForm({
    name: '',
    started_at: '',
    finished_at: '',
});

/* ===========================
   DATAS BASE
=========================== */
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

const minDateTime = formatDateTime(now);
/* ===========================
   FORMATA DATA DO BACKEND
=========================== */
function formatToInput(value: string | null) {
    if (!value) return '';
    return value.replace(' ', 'T').slice(0, 16);
}

const maxFinishedAtEdit = computed(() => {
    if (!form.started_at) return '';

    const base = new Date(form.started_at);
    base.setDate(base.getDate() + 6); 
    return formatDateTime(base);
});

watch(
    () => props.task,
    (task) => {
        if (task) {
            form.name = task.name ?? '';
            form.started_at = formatToInput(task.started_at);
            form.finished_at = formatToInput(task.finished_at);
        } else {
            
            form.reset();
            form.started_at = minDateTime;
            form.finished_at = '';
        }
    },
    { immediate: true },
);

function submit() {
    form[props.method || 'post'](props.url, {
        onSuccess: () => {
            form.reset();
            form.started_at = minDateTime;
            emit('success');
            emit('close');
        },
    });
}
</script>

<template>
    <Dialog :open="open" @update:open="emit('close')">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>
                    {{ task ? 'Editar Task' : 'Nova Task' }}
                </DialogTitle>
            </DialogHeader>

            <form @submit.prevent="submit" class="mt-4 space-y-4">
                <label for="name">Name task</label>
                <input
                    v-model="form.name"
                    placeholder="Nome"
                    class="block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-50"
                />
                <label for="started_at">Started at</label>
                <input
                    type="datetime-local"
                    v-model="form.started_at"
                    :min="minDateTime"
                    :readonly="!!task"
                    :class="[
                        'block w-full rounded-md border px-3 py-2 text-sm shadow-sm',
                        task
                            ? 'cursor-not-allowed bg-slate-100 dark:bg-slate-800'
                            : 'bg-white dark:bg-slate-900',
                    ]"
                />

                <label for="finished_at">Finished at</label>
                <input
                    type="datetime-local"
                    v-model="form.finished_at"
                    :min="form.started_at"
                    :max="task ? maxFinishedAtEdit : undefined"
                    class="block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-900"
                />

                <DialogFooter>
                    <Button
                        type="button"
                        variant="outline"
                        @click="emit('close')"
                    >
                        Cancelar
                    </Button>

                    <Button type="submit" :disabled="form.processing">
                        {{ task ? 'Salvar alterações' : 'Criar task' }}
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
