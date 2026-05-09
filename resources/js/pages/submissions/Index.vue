<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import StatusBadge from '@/components/StatusBadge.vue';
import DataTable from '@/components/DataTable.vue';
import EmptyState from '@/components/EmptyState.vue';
import { ClipboardList } from 'lucide-vue-next';

interface Student { id: number; name: string; email: string; }
interface Submission { id: number; student: Student; status: string; score: number | null; submitted_at: string; }
interface Assignment { id: number; title: string; }
interface Course { id: number; title: string; }

const props = defineProps<{ course: Course; assignment: Assignment; submissions: Submission[] }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Courses', href: '/courses' },
    { title: props.course.title, href: `/courses/${props.course.id}` },
    { title: 'Submissions', href: '#' },
];

const columns = [
    { key: 'student', label: 'Student', align: 'left' as const },
    { key: 'status', label: 'Status', align: 'left' as const },
    { key: 'submitted_at', label: 'Submitted', align: 'left' as const },
    { key: 'score', label: 'Score', align: 'left' as const },
    { key: 'actions', label: '', align: 'right' as const },
];
</script>

<template>
    <Head title="Submissions" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-5">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-foreground">Submissions</h1>
                    <p class="mt-0.5 text-sm text-muted-foreground">
                        {{ assignment.title }} — {{ submissions.length }} submission{{ submissions.length !== 1 ? 's' : '' }}
                    </p>
                </div>
            </div>

            <DataTable v-if="submissions.length > 0" :columns="columns" :data="submissions">
                <template #cell(student)="{ item }">
                    <div>
                        <p class="text-sm font-medium text-foreground">{{ item.student.name }}</p>
                        <p class="text-xs text-muted-foreground">{{ item.student.email }}</p>
                    </div>
                </template>

                <template #cell(status)="{ item }">
                    <StatusBadge :status="item.status" />
                </template>

                <template #cell(submitted_at)="{ item }">
                    <span class="text-sm text-muted-foreground">{{ new Date(item.submitted_at).toLocaleString() }}</span>
                </template>

                <template #cell(score)="{ item }">
                    <span v-if="item.score !== null" class="text-sm font-medium text-foreground">{{ item.score }}</span>
                    <span v-else class="text-sm text-muted-foreground">—</span>
                </template>

                <template #cell(actions)="{ item }">
                    <div class="flex justify-end">
                        <Button as-child variant="ghost" size="sm" class="h-7 text-xs">
                            <Link :href="route('submissions.show', { course: course.id, assignment: assignment.id, submission: item.id })">
                                {{ item.status === 'graded' ? 'Review' : 'Grade' }} →
                            </Link>
                        </Button>
                    </div>
                </template>
            </DataTable>

            <EmptyState
                v-else
                title="No submissions yet"
                description="Students haven't submitted their work for this assignment."
                :icon="ClipboardList"
            />
        </div>
    </AppLayout>
</template>
