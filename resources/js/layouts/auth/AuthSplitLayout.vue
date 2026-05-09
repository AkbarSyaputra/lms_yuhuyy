<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="min-h-screen flex bg-white dark:bg-zinc-950">
        <!-- Left Brand Panel -->
        <div class="hidden lg:flex lg:w-[420px] xl:w-[480px] flex-col bg-zinc-950 dark:bg-zinc-900 border-r border-zinc-800 flex-shrink-0">
            <div class="flex flex-col h-full p-10">
                <!-- Logo -->
                <Link :href="home()" class="flex items-center gap-2.5 mb-auto">
                    <div class="flex h-8 w-8 items-center justify-center rounded-md bg-primary">
                        <AppLogoIcon class="h-4 w-4 fill-current text-white" />
                    </div>
                    <span class="text-sm font-semibold text-white">Eduzy LMS</span>
                </Link>

                <!-- Testimonial / Quote -->
                <div class="mt-auto">
                    <blockquote class="border-l-2 border-zinc-700 pl-4">
                        <p class="text-sm leading-relaxed text-zinc-400">
                            "Education is the most powerful weapon which you can use to change the world."
                        </p>
                        <footer class="mt-2 text-xs text-zinc-600">— Nelson Mandela</footer>
                    </blockquote>
                </div>

                <!-- Feature list -->
                <div class="mt-8 space-y-2.5">
                    <div v-for="item in ['Role-based access control', 'Course & material management', 'Assignment submission & grading', 'Audit trail & activity logs']"
                        :key="item"
                        class="flex items-center gap-2.5 text-xs text-zinc-500">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ item }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Form Panel -->
        <div class="flex flex-1 flex-col justify-center px-6 py-12 sm:px-10">
            <!-- Back link -->
            <div class="absolute right-6 top-6">
                <Link :href="home()" class="text-xs text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 transition-colors">
                    ← Back to home
                </Link>
            </div>

            <div class="mx-auto w-full max-w-[360px]">
                <!-- Mobile Logo -->
                <div class="mb-8 flex items-center gap-2.5 lg:hidden">
                    <div class="flex h-8 w-8 items-center justify-center rounded-md bg-primary">
                        <AppLogoIcon class="h-4 w-4 fill-current text-white" />
                    </div>
                    <span class="text-sm font-semibold text-foreground">Eduzy LMS</span>
                </div>

                <div class="mb-8">
                    <h1 v-if="title" class="text-xl font-semibold text-foreground">{{ title }}</h1>
                    <p v-if="description" class="mt-1.5 text-sm text-muted-foreground">{{ description }}</p>
                </div>

                <slot />
            </div>
        </div>
    </div>
</template>
