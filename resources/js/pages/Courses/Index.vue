<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Plus, Search, Edit2, Trash2, BookOpen, Users as UsersIcon } from 'lucide-vue-next';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import PageHeader from '@/components/PageHeader.vue';
import StatusBadge from '@/components/StatusBadge.vue';
import EmptyState from '@/components/EmptyState.vue';
import Pagination from '@/components/Pagination.vue';
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import coursesRoutes from '@/routes/courses/index';

interface Course {
    id: number;
    title: string;
    slug: string;
    description: string | null;
    thumbnail_url: string;
    status: string;
    category?: { id: number; name: string } | null;
    creator?: { id: number; name: string } | null;
    enrollments_count: number;
    max_students: number | null;
}

interface PaginatedData<T> {
    data: T[];
    total: number;
    per_page: number;
    from: number;
    to: number;
    prev_page_url: string | null;
    next_page_url: string | null;
}

const props = defineProps<{
    courses: PaginatedData<Course>;
    categories: any[];
    filters: Record<string, string>;
}>();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Courses', href: '/courses' }];
const search = ref(props.filters['filter[title]'] || '');

watch(search, (value) => {
    router.get('/courses', { 'filter[title]': value }, { preserveState: true, replace: true, preserveScroll: true });
});

const destroy = (id: number) => {
    router.delete(coursesRoutes.destroy.url(id));
};
</script>

<template>
    <Head title="Courses" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-6">
            <PageHeader title="Courses" description="Manage and publish educational content.">
                <template #actions>
                    <Button as-child size="sm">
                        <Link :href="coursesRoutes.create.url()">
                            <Plus class="mr-1.5 h-4 w-4" /> New Course
                        </Link>
                    </Button>
                </template>
            </PageHeader>

            <!-- Search Bar -->
            <div class="relative max-w-sm">
                <Search class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground pointer-events-none" />
                <Input v-model="search" placeholder="Search courses..." class="pl-9 h-9 text-sm" />
            </div>

            <!-- Course Grid -->
            <div v-if="courses.data.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                <div
                    v-for="course in courses.data"
                    :key="course.id"
                    class="group rounded-lg border border-border bg-card overflow-hidden hover:border-zinc-300 dark:hover:border-zinc-700 transition-colors"
                >
                    <!-- Thumbnail -->
                    <div class="relative aspect-video overflow-hidden bg-muted">
                        <img
                            :src="course.thumbnail_url"
                            class="h-full w-full object-cover"
                            alt=""
                            loading="lazy"
                        />
                        <div class="absolute top-2 right-2">
                            <StatusBadge :status="course.status" />
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-medium uppercase tracking-widest text-muted-foreground">
                                {{ course.category?.name ?? 'Uncategorized' }}
                            </span>
                            <span class="flex items-center gap-1 text-xs text-muted-foreground">
                                <UsersIcon class="h-3 w-3" />
                                {{ course.enrollments_count }}
                            </span>
                        </div>
                        <h3 class="mb-1 text-sm font-semibold text-foreground leading-snug line-clamp-2 group-hover:text-primary transition-colors">
                            {{ course.title }}
                        </h3>
                        <p class="text-xs text-muted-foreground line-clamp-2 leading-relaxed">
                            {{ course.description ?? 'No description provided.' }}
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between border-t border-border px-4 py-2.5 bg-muted/20">
                        <div class="flex items-center gap-1">
                            <Button variant="ghost" size="icon" as-child class="h-7 w-7">
                                <Link :href="coursesRoutes.edit.url(course.id)" title="Edit">
                                    <Edit2 class="h-3.5 w-3.5" />
                                </Link>
                            </Button>
                            <ConfirmDialog
                                title="Delete course"
                                description="This will permanently delete the course along with all materials and assignments."
                                confirmText="Delete"
                                destructive
                                @confirm="destroy(course.id)"
                            >
                                <Button variant="ghost" size="icon" class="h-7 w-7 hover:text-destructive">
                                    <Trash2 class="h-3.5 w-3.5" />
                                </Button>
                            </ConfirmDialog>
                        </div>
                        <Button variant="ghost" size="sm" as-child class="h-7 text-xs px-2">
                            <Link :href="coursesRoutes.show.url(course.id)">View →</Link>
                        </Button>
                    </div>
                </div>
            </div>

            <EmptyState
                v-else
                title="No courses yet"
                description="Create your first course to get started."
                :icon="BookOpen"
            >
                <template #action>
                    <Button as-child size="sm" variant="outline">
                        <Link :href="coursesRoutes.create.url()">Create course</Link>
                    </Button>
                </template>
            </EmptyState>

            <Pagination :data="courses" />
        </div>
    </AppLayout>
</template>
