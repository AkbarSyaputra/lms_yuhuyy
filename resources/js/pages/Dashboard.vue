<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { dashboard } from '@/routes';
import { index as usersIndex } from '@/routes/users/index';
import { index as coursesIndex } from '@/routes/courses/index';
import { index as categoriesIndex } from '@/routes/categories/index';
import AppLayout from '@/layouts/AppLayout.vue';
import StudentStats from '@/components/Dashboard/StudentStats.vue';
import TeacherStats from '@/components/Dashboard/TeacherStats.vue';
import StatsCard from '@/components/StatsCard.vue';
import { ShieldCheck, Users, BookOpen, Layers } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';

const breadcrumbs = [{ title: 'Dashboard', href: dashboard.url() }];

defineProps<{
    studentStats?: any;
    teacherStats?: any;
}>();

const page = usePage();
const user = page.props.auth.user as any;
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-6">
            <!-- Greeting -->
            <div>
                <h1 class="text-xl font-semibold text-foreground">Good morning, {{ user.name.split(' ')[0] }}</h1>
                <p class="mt-0.5 text-sm text-muted-foreground">
                    {{ new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
                </p>
            </div>

            <!-- Student Dashboard -->
            <template v-if="studentStats">
                <StudentStats :stats="studentStats" />
            </template>

            <!-- Teacher Dashboard -->
            <template v-if="teacherStats">
                <TeacherStats :stats="teacherStats" />
            </template>

            <!-- Admin Dashboard -->
            <template v-if="!studentStats && !teacherStats">
                <div class="grid gap-4 grid-cols-2 lg:grid-cols-4">
                    <StatsCard
                        title="System Status"
                        value="Operational"
                        description="All services running"
                        :icon="ShieldCheck"
                    />
                    <StatsCard
                        title="Users"
                        value="—"
                        description="Manage accounts"
                        :icon="Users"
                    />
                    <StatsCard
                        title="Courses"
                        value="—"
                        description="Active courses"
                        :icon="BookOpen"
                    />
                    <StatsCard
                        title="Categories"
                        value="—"
                        description="Course subjects"
                        :icon="Layers"
                    />
                </div>

                <div class="rounded-lg border border-border bg-card p-5">
                    <p class="text-xs font-medium uppercase tracking-widest text-muted-foreground mb-4">Quick Access</p>
                    <div class="grid sm:grid-cols-3 gap-3">
                        <Button variant="outline" size="sm" as-child class="justify-start text-sm font-normal">
                            <Link :href="usersIndex.url()">
                                <Users class="mr-2 h-4 w-4 text-muted-foreground" /> Manage Users
                            </Link>
                        </Button>
                        <Button variant="outline" size="sm" as-child class="justify-start text-sm font-normal">
                            <Link :href="coursesIndex.url()">
                                <BookOpen class="mr-2 h-4 w-4 text-muted-foreground" /> Browse Courses
                            </Link>
                        </Button>
                        <Button variant="outline" size="sm" as-child class="justify-start text-sm font-normal">
                            <Link :href="categoriesIndex.url()">
                                <Layers class="mr-2 h-4 w-4 text-muted-foreground" /> Manage Categories
                            </Link>
                        </Button>
                    </div>
                </div>
            </template>
        </div>
    </AppLayout>
</template>
