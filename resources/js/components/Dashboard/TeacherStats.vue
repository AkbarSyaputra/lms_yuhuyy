<script setup lang="ts">
import { Users, BookOpen, AlertCircle, ChevronRight } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';
import { show as submissionShow } from '@/routes/submissions/index';
import StatsCard from '@/components/StatsCard.vue';
import EmptyState from '@/components/EmptyState.vue';

defineProps<{
    stats: {
        total_courses_count: number;
        total_students_count: number;
        pending_grading_count: number;
        recent_pending_submissions: Array<{
            id: number;
            assignment_id: number;
            course_id: number;
            assignment_title: string;
            course_title: string;
            student_name: string;
            submitted_at: string;
        }>;
    };
}>();
</script>

<template>
    <div class="space-y-6">
        <!-- Stats Overview -->
        <div class="grid gap-4 grid-cols-2 md:grid-cols-3">
            <StatsCard
                title="Total Courses"
                :value="stats.total_courses_count"
                description="Active courses you teach"
                :icon="BookOpen"
            />
            
            <StatsCard
                title="Total Students"
                :value="stats.total_students_count"
                description="Students across all courses"
                :icon="Users"
            />
            
            <StatsCard
                title="Pending Grading"
                :value="stats.pending_grading_count"
                description="Submissions waiting review"
                :icon="AlertCircle"
                :iconClass="stats.pending_grading_count > 0 ? 'text-amber-500 bg-amber-500/10' : ''"
            />
        </div>

        <!-- Recent Pending Submissions -->
        <div class="rounded-lg border border-border bg-card overflow-hidden">
            <div class="px-5 py-4 border-b border-border bg-muted/20">
                <h3 class="text-xs font-medium uppercase tracking-widest text-muted-foreground">Needs Grading</h3>
            </div>
            
            <div class="p-0">
                <EmptyState
                    v-if="stats.recent_pending_submissions.length === 0"
                    title="All caught up!"
                    description="There are no pending submissions to grade."
                    :icon="AlertCircle"
                />
                <div v-else class="divide-y divide-border">
                    <div v-for="submission in stats.recent_pending_submissions" :key="submission.id" class="p-5 flex items-center justify-between hover:bg-muted/30 transition-colors">
                        <div>
                            <p class="text-sm font-medium text-foreground">{{ submission.assignment_title }}</p>
                            <div class="mt-0.5 flex items-center gap-2 text-xs text-muted-foreground">
                                <span>{{ submission.course_title }}</span>
                                <span class="text-border">&bull;</span>
                                <span class="font-medium text-foreground">{{ submission.student_name }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <p class="text-xs text-muted-foreground hidden sm:block">{{ new Date(submission.submitted_at).toLocaleDateString() }}</p>
                            <Button as-child size="sm" variant="outline" class="h-8 text-xs">
                                <Link :href="submissionShow.url({ course: submission.course_id, assignment: submission.assignment_id, submission: submission.id })">
                                    Grade <ChevronRight class="ml-1 h-3 w-3" />
                                </Link>
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
