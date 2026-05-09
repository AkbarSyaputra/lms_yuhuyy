<script setup lang="ts">
import { defineProps, withDefaults } from 'vue';
import type { Component } from 'vue';

withDefaults(defineProps<{
    title: string;
    value: string | number;
    description?: string;
    icon?: Component;
    trend?: { value: number; label: string };
}>(), {});
</script>

<template>
    <div class="rounded-lg border border-border bg-card p-5">
        <div class="flex items-start justify-between">
            <div class="min-w-0 flex-1">
                <p class="text-xs font-medium uppercase tracking-widest text-muted-foreground">{{ title }}</p>
                <p class="mt-2 text-2xl font-semibold tracking-tight text-foreground">{{ value }}</p>
                <p v-if="description" class="mt-1 text-xs text-muted-foreground truncate">{{ description }}</p>
            </div>
            <div v-if="icon" class="ml-4 flex-shrink-0 rounded-md p-2 bg-primary/8 text-primary">
                <component :is="icon" class="h-5 w-5" />
            </div>
        </div>
        <div v-if="trend" class="mt-3 flex items-center gap-1 text-xs">
            <span :class="trend.value >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-500'">
                {{ trend.value >= 0 ? '+' : '' }}{{ trend.value }}%
            </span>
            <span class="text-muted-foreground">{{ trend.label }}</span>
        </div>
    </div>
</template>
