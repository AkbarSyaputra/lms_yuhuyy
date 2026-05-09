<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { dashboard, login, register } from '@/routes';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Button } from '@/components/ui/button';
import { ArrowRight, BookOpen, Users, Award, Lock } from 'lucide-vue-next';

withDefaults(
    defineProps<{ canRegister: boolean }>(),
    { canRegister: true },
);
</script>

<template>
    <Head title="Eduzy — Learning Management System" />
    <div class="min-h-screen bg-white dark:bg-zinc-950 text-zinc-900 dark:text-zinc-50 antialiased">

        <!-- Nav -->
        <nav class="fixed top-0 inset-x-0 z-50 border-b border-zinc-200 dark:border-zinc-800 bg-white/90 dark:bg-zinc-950/90 backdrop-blur-sm">
            <div class="mx-auto flex h-14 max-w-6xl items-center justify-between px-6">
                <Link :href="login()" class="flex items-center gap-2">
                    <div class="flex h-7 w-7 items-center justify-center rounded-md bg-primary text-primary-foreground">
                        <AppLogoIcon class="h-4 w-4 fill-current text-primary-foreground" />
                    </div>
                    <span class="text-sm font-semibold tracking-tight">Eduzy LMS</span>
                </Link>
                <div class="flex items-center gap-2">
                    <Button v-if="$page.props.auth.user" as-child size="sm">
                        <Link :href="dashboard()">Go to Dashboard</Link>
                    </Button>
                    <template v-else>
                        <Button as-child variant="ghost" size="sm" class="text-sm">
                            <Link :href="login()">Sign in</Link>
                        </Button>
                        <Button v-if="canRegister" as-child size="sm" class="text-sm">
                            <Link :href="register()">Get started</Link>
                        </Button>
                    </template>
                </div>
            </div>
        </nav>

        <!-- Hero -->
        <section class="pt-32 pb-24 px-6">
            <div class="mx-auto max-w-3xl text-center">
                <div class="mb-6 inline-flex items-center rounded-full border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900 px-3 py-1 text-xs font-medium text-zinc-600 dark:text-zinc-400">
                    Learning Management System
                </div>
                <h1 class="mb-6 text-5xl font-bold tracking-tight text-zinc-900 dark:text-white leading-[1.1]">
                    The modern platform for<br />online education
                </h1>
                <p class="mb-10 text-lg text-zinc-500 dark:text-zinc-400 leading-relaxed max-w-xl mx-auto">
                    Manage courses, assignments, and student progress with an interface built for clarity and speed.
                </p>
                <div class="flex items-center justify-center gap-3">
                    <Button as-child size="lg" class="h-11 px-6 text-sm font-medium">
                        <Link :href="login()">
                            Get started
                            <ArrowRight class="ml-2 h-4 w-4" />
                        </Link>
                    </Button>
                    <Button as-child variant="outline" size="lg" class="h-11 px-6 text-sm font-medium border-zinc-200 dark:border-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-900">
                        <a href="#demo">View demo accounts</a>
                    </Button>
                </div>
            </div>
        </section>

        <!-- Features Grid -->
        <section class="border-t border-zinc-100 dark:border-zinc-800/60 py-24 px-6 bg-zinc-50 dark:bg-zinc-900/40">
            <div class="mx-auto max-w-5xl">
                <div class="mb-14 text-center">
                    <h2 class="text-2xl font-semibold text-zinc-900 dark:text-white mb-3">Everything you need, nothing you don't</h2>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">A focused set of tools for modern educational institutions.</p>
                </div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="rounded-lg border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 space-y-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-md bg-primary/10 text-primary">
                            <BookOpen class="h-5 w-5" />
                        </div>
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">Course Management</h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed">Create structured courses with materials, assignments, and enrollment controls.</p>
                    </div>
                    <div class="rounded-lg border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 space-y-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-md bg-primary/10 text-primary">
                            <Users class="h-5 w-5" />
                        </div>
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">Role-Based Access</h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed">Separate portals for admins, teachers, and students with tailored permissions.</p>
                    </div>
                    <div class="rounded-lg border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 space-y-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-md bg-primary/10 text-primary">
                            <Award class="h-5 w-5" />
                        </div>
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">Assignment & Grading</h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed">Collect submissions and grade them with a clean, efficient workflow.</p>
                    </div>
                    <div class="rounded-lg border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 space-y-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-md bg-primary/10 text-primary">
                            <Lock class="h-5 w-5" />
                        </div>
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">Secure & Reliable</h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed">Enterprise-grade security with policy-based authorization and audit logs.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Demo Accounts -->
        <section id="demo" class="py-24 px-6 border-t border-zinc-100 dark:border-zinc-800/60">
            <div class="mx-auto max-w-3xl">
                <div class="mb-10 text-center">
                    <h2 class="text-2xl font-semibold text-zinc-900 dark:text-white mb-2">Demo accounts</h2>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">
                        Log in with any of these accounts using the password
                        <code class="mx-1 rounded bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 text-xs font-mono text-zinc-700 dark:text-zinc-300">password</code>
                    </p>
                </div>
                <div class="grid gap-3 sm:grid-cols-3">
                    <div v-for="account in [
                        { role: 'Admin', email: 'admin@eduzy.test', desc: 'Full system access' },
                        { role: 'Teacher', email: 'teacher@eduzy.test', desc: 'Manage courses & grade' },
                        { role: 'Student', email: 'student@eduzy.test', desc: 'Enroll & submit work' },
                    ]" :key="account.role"
                        class="rounded-lg border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5">
                        <p class="mb-1 text-xs font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">{{ account.role }}</p>
                        <p class="mb-1 text-sm font-medium text-zinc-900 dark:text-white font-mono">{{ account.email }}</p>
                        <p class="mb-4 text-xs text-zinc-500 dark:text-zinc-400">{{ account.desc }}</p>
                        <Button as-child variant="outline" size="sm" class="w-full text-xs border-zinc-200 dark:border-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-800">
                            <Link :href="login()">Login as {{ account.role }}</Link>
                        </Button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t border-zinc-100 dark:border-zinc-800/60 py-8 px-6">
            <div class="mx-auto max-w-6xl flex items-center justify-between text-xs text-zinc-400 dark:text-zinc-600">
                <div class="flex items-center gap-2">
                    <div class="flex h-5 w-5 items-center justify-center rounded bg-zinc-900 dark:bg-zinc-100">
                        <AppLogoIcon class="h-3 w-3 fill-current text-white dark:text-zinc-900" />
                    </div>
                    <span>© {{ new Date().getFullYear() }} Eduzy LMS</span>
                </div>
                <span>Built with Laravel & Vue</span>
            </div>
        </footer>
    </div>
</template>
