<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { BookOpen, Edit2, Trash2, Calendar, Users, Folder, Plus, FileText, Video, Link as LinkIcon, Paperclip, CheckSquare } from 'lucide-vue-next';
import StatusBadge from '@/components/StatusBadge.vue';
import EmptyState from '@/components/EmptyState.vue';
import ConfirmDialog from '@/components/ConfirmDialog.vue';

interface Category { id: number; name: string; }
interface Creator { id: number; name: string; }
interface Material { id: number; title: string; type: string; order: number; is_published: boolean; }
interface Assignment { id: number; title: string; type: string; due_date: string | null; max_score: number | null; is_published: boolean; is_submitted?: boolean; }
interface Course {
    id: number; title: string; slug: string; description: string | null; status: string;
    category: Category | null; creator: Creator | null;
    materials: Material[]; assignments: Assignment[];
    max_students: number | null; start_date: string | null; end_date: string | null;
}

const props = defineProps<{
    course: Course;
    isEnrolled: boolean;
    isStudent: boolean;
    isTeacher: boolean;
    isAdmin: boolean;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Courses', href: '/courses' },
    { title: props.course.title, href: `/courses/${props.course.id}` },
];

const canManage = props.isAdmin || (props.isTeacher && props.course.creator?.id === (window as any).__page?.auth?.user?.id);

const enroll = () => router.post(route('courses.enroll', props.course.id));
const deleteMaterial = (id: number) => router.delete(route('materials.destroy', { course: props.course.id, material: id }));
const deleteAssignment = (id: number) => router.delete(route('assignments.destroy', { course: props.course.id, assignment: id }));

const materialIcon = (type: string) => ({
    text: FileText, video: Video, file: Paperclip, link: LinkIcon
}[type] ?? FileText);
</script>

<template>
    <Head :title="course.title" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-6 max-w-5xl">

            <!-- Header -->
            <div class="flex items-start justify-between gap-6">
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2.5 mb-2">
                        <StatusBadge :status="course.status" />
                        <span v-if="course.category" class="text-xs text-muted-foreground">{{ course.category.name }}</span>
                    </div>
                    <h1 class="text-2xl font-semibold text-foreground leading-tight mb-2">{{ course.title }}</h1>
                    <p v-if="course.description" class="text-sm text-muted-foreground leading-relaxed max-w-2xl">{{ course.description }}</p>
                    <div class="mt-3 flex flex-wrap items-center gap-4 text-xs text-muted-foreground">
                        <span v-if="course.creator" class="flex items-center gap-1.5">
                            <Users class="h-3.5 w-3.5" /> {{ course.creator.name }}
                        </span>
                        <span v-if="course.max_students" class="flex items-center gap-1.5">
                            <Users class="h-3.5 w-3.5" /> Max {{ course.max_students }} students
                        </span>
                        <span v-if="course.start_date" class="flex items-center gap-1.5">
                            <Calendar class="h-3.5 w-3.5" /> {{ new Date(course.start_date).toLocaleDateString() }}
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <Button v-if="isStudent && !isEnrolled" size="sm" @click="enroll">Enroll</Button>
                    <Button v-if="isAdmin || (isTeacher && course.creator?.id === $page.props.auth.user.id)" variant="outline" size="sm" as-child>
                        <Link :href="route('courses.edit', course.id)">
                            <Edit2 class="mr-1.5 h-3.5 w-3.5" /> Edit
                        </Link>
                    </Button>
                </div>
            </div>

            <!-- Two column grid -->
            <div class="grid lg:grid-cols-2 gap-5">

                <!-- Materials -->
                <div class="rounded-lg border border-border bg-card overflow-hidden">
                    <div class="flex items-center justify-between px-4 py-3 border-b border-border bg-muted/30">
                        <div class="flex items-center gap-2">
                            <BookOpen class="h-4 w-4 text-muted-foreground" />
                            <span class="text-sm font-medium text-foreground">Materials</span>
                            <span class="text-xs text-muted-foreground">{{ course.materials.length }}</span>
                        </div>
                        <Button v-if="isAdmin || (isTeacher && course.creator?.id === $page.props.auth.user.id)" as-child variant="ghost" size="sm" class="h-7 text-xs">
                            <Link :href="route('materials.create', { course: course.id })">
                                <Plus class="mr-1 h-3.5 w-3.5" /> Add
                            </Link>
                        </Button>
                    </div>

                    <div v-if="course.materials.length > 0" class="divide-y divide-border">
                        <div v-for="material in course.materials" :key="material.id" class="flex items-center gap-3 px-4 py-3 hover:bg-muted/20 transition-colors group">
                            <component :is="materialIcon(material.type)" class="h-4 w-4 flex-shrink-0 text-muted-foreground" />
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-foreground truncate">{{ material.title }}</p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-[10px] uppercase tracking-widest text-muted-foreground">{{ material.type }}</span>
                                    <span v-if="!material.is_published" class="text-[10px] text-amber-600 dark:text-amber-400">Draft</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <template v-if="isStudent">
                                    <span v-if="!isEnrolled" class="text-[10px] text-muted-foreground">Locked</span>
                                </template>
                                <template v-if="isAdmin || (isTeacher && course.creator?.id === $page.props.auth.user.id)">
                                    <Button variant="ghost" size="icon" as-child class="h-6 w-6">
                                        <Link :href="route('materials.edit', { course: course.id, material: material.id })">
                                            <Edit2 class="h-3 w-3" />
                                        </Link>
                                    </Button>
                                    <ConfirmDialog title="Delete material" description="This will permanently delete this material." confirmText="Delete" destructive @confirm="deleteMaterial(material.id)">
                                        <Button variant="ghost" size="icon" class="h-6 w-6 hover:text-destructive">
                                            <Trash2 class="h-3 w-3" />
                                        </Button>
                                    </ConfirmDialog>
                                </template>
                            </div>
                        </div>
                    </div>
                    <EmptyState v-else title="No materials" description="Add learning content to this course." :icon="BookOpen" />
                </div>

                <!-- Assignments -->
                <div class="rounded-lg border border-border bg-card overflow-hidden">
                    <div class="flex items-center justify-between px-4 py-3 border-b border-border bg-muted/30">
                        <div class="flex items-center gap-2">
                            <CheckSquare class="h-4 w-4 text-muted-foreground" />
                            <span class="text-sm font-medium text-foreground">Assignments</span>
                            <span class="text-xs text-muted-foreground">{{ course.assignments.length }}</span>
                        </div>
                        <Button v-if="isAdmin || (isTeacher && course.creator?.id === $page.props.auth.user.id)" as-child variant="ghost" size="sm" class="h-7 text-xs">
                            <Link :href="route('assignments.create', { course: course.id })">
                                <Plus class="mr-1 h-3.5 w-3.5" /> Add
                            </Link>
                        </Button>
                    </div>

                    <div v-if="course.assignments.length > 0" class="divide-y divide-border">
                        <div v-for="assignment in course.assignments" :key="assignment.id" class="flex items-start gap-3 px-4 py-3 hover:bg-muted/20 transition-colors group">
                            <CheckSquare class="h-4 w-4 flex-shrink-0 mt-0.5 text-muted-foreground" />
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-foreground truncate">{{ assignment.title }}</p>
                                <div class="flex flex-wrap items-center gap-2 mt-0.5">
                                    <span class="text-[10px] uppercase tracking-widest text-muted-foreground">{{ assignment.type.replace('_', ' ') }}</span>
                                    <span v-if="assignment.due_date" class="text-[10px] text-muted-foreground">Due {{ new Date(assignment.due_date).toLocaleDateString() }}</span>
                                    <span v-if="assignment.max_score" class="text-[10px] text-muted-foreground">{{ assignment.max_score }} pts</span>
                                    <span v-if="!assignment.is_published" class="text-[10px] text-amber-600 dark:text-amber-400">Draft</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0">
                                <template v-if="isStudent && isEnrolled">
                                    <Button variant="ghost" size="sm" as-child class="h-6 text-xs">
                                        <Link :href="route('assignments.show', { course: course.id, assignment: assignment.id })">
                                            {{ assignment.is_submitted ? 'View' : 'Submit' }}
                                        </Link>
                                    </Button>
                                </template>
                                <template v-if="isAdmin || (isTeacher && course.creator?.id === $page.props.auth.user.id)">
                                    <Button variant="ghost" size="sm" as-child class="h-6 text-xs">
                                        <Link :href="route('assignments.submissions.index', { course: course.id, assignment: assignment.id })">Submissions</Link>
                                    </Button>
                                    <Button variant="ghost" size="icon" as-child class="h-6 w-6">
                                        <Link :href="route('assignments.edit', { course: course.id, assignment: assignment.id })">
                                            <Edit2 class="h-3 w-3" />
                                        </Link>
                                    </Button>
                                    <ConfirmDialog title="Delete assignment" description="This will permanently delete this assignment and all submissions." confirmText="Delete" destructive @confirm="deleteAssignment(assignment.id)">
                                        <Button variant="ghost" size="icon" class="h-6 w-6 hover:text-destructive">
                                            <Trash2 class="h-3 w-3" />
                                        </Button>
                                    </ConfirmDialog>
                                </template>
                            </div>
                        </div>
                    </div>
                    <EmptyState v-else title="No assignments" description="Create assignments for students to complete." :icon="CheckSquare" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
