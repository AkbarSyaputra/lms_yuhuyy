<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    status: string;
}>();

const config = computed(() => {
    switch (props.status?.toLowerCase()) {
        case 'published':
        case 'active':
            return { label: props.status, classes: 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-900/20 dark:text-emerald-400 dark:ring-emerald-400/20' };
        case 'draft':
            return { label: 'Draft', classes: 'bg-zinc-100 text-zinc-600 ring-zinc-500/10 dark:bg-zinc-800 dark:text-zinc-400 dark:ring-zinc-700' };
        case 'archived':
            return { label: 'Archived', classes: 'bg-zinc-100 text-zinc-500 ring-zinc-500/10 dark:bg-zinc-800 dark:text-zinc-500 dark:ring-zinc-700' };
        case 'suspended':
        case 'inactive':
            return { label: props.status, classes: 'bg-red-50 text-red-600 ring-red-600/20 dark:bg-red-900/20 dark:text-red-400 dark:ring-red-400/20' };
        case 'pending':
            return { label: 'Pending', classes: 'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-900/20 dark:text-amber-400 dark:ring-amber-400/20' };
        default:
            return { label: props.status, classes: 'bg-zinc-100 text-zinc-600 ring-zinc-500/10 dark:bg-zinc-800 dark:text-zinc-400 dark:ring-zinc-700' };
    }
});
</script>

<template>
    <span :class="['inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium ring-1 ring-inset capitalize', config.classes]">
        {{ config.label }}
    </span>
</template>
