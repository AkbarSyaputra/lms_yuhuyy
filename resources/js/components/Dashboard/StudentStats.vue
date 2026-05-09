<script setup lang="ts">
import { BookOpen, Clock, Award } from 'lucide-vue-next';
import StatsCard from '@/components/StatsCard.vue';
import EmptyState from '@/components/EmptyState.vue';
import StatusBadge from '@/components/StatusBadge.vue';

defineProps<{
    stats: {
        enrolled_courses_count: number;
        pending_assignments_count: number;
        recent_grades: Array<{
            assignment_title: string;
            course_title: string;
            score: number;
            max_score: number;
            graded_at: string;
        }>;
    };
}>();
</script>

<template>
    <div class="space-y-6">
        <!-- Stats Overview -->
        <div class="grid gap-4 grid-cols-2 md:grid-cols-3">
            <StatsCard
                title="Enrolled Courses"
                :value="stats.enrolled_courses_count"
                description="Active courses"
                :icon="BookOpen"
            />
            <StatsCard
                title="Pending Assignments"
                :value="stats.pending_assignments_count"
                description="Awaiting submission"
                :icon="Clock"
            />
            <StatsCard
                title="Recent Grades"
                :value="stats.recent_grades.length"
                description="Graded submissions"
                :icon="Award"
            />
        </div>

        <!-- Recent Grades List -->
        <div class="rounded-lg border border-border bg-card overflow-hidden">
            <div class="px-5 py-4 border-b border-border bg-muted/20">
                <h3 class="text-xs font-medium uppercase tracking-widest text-muted-foreground">Recent Grades</h3>
            </div>
            
            <div class="p-0">
                <EmptyState
                    v-if="stats.recent_grades.length === 0"
                    title="No grades yet"
                    description="When your teachers grade your assignments, they will appear here."
                    :icon="Award"
                />
                <div v-else class="divide-y divide-border">
                    <div v-for="(grade, index) in stats.recent_grades" :key="index" class="p-5 flex items-center justify-between hover:bg-muted/30 transition-colors">
                        <div>
                            <p class="text-sm font-medium text-foreground">{{ grade.assignment_title }}</p>
                            <p class="mt-0.5 text-xs text-muted-foreground">{{ grade.course_title }}</p>
                        </div>
                        <div class="text-right">
                            <StatusBadge status="graded" class="text-sm">
                                {{ grade.score }} / {{ grade.max_score }}
                            </StatusBadge>
                            <p class="mt-1.5 text-xs text-muted-foreground">{{ new Date(grade.graded_at).toLocaleDateString() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
