<!-- eslint-disable vue/block-lang -->
<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

const sucess = computed(() => page.props.flash?.success);
const error = computed(() => page.props.flash?.error);
</script>

<template>
    <div class="mb-4">
        <div
            v-if="success"
            class="my-3 rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-800"
        >
            {{ sucess }}
        </div>

        <div
            v-if="error"
            class="my-3 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-800"
        >
            {{ error }}
        </div>
    </div>
</template>
