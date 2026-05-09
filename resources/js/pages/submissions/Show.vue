<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import StatusBadge from '@/components/StatusBadge.vue';
import { ArrowLeft, Paperclip } from 'lucide-vue-next';

interface Student { id: number; name: string; email: string; }
interface Submission {
    id: number; student: Student; content: string | null; file_path: string | null;
    file_url: string | null; score: number | null; feedback: string | null;
    status: string; submitted_at: string;
}
interface Assignment { id: number; title: string; max_score: number | null; type: string; }
interface Course { id: number; title: string; }

const props = defineProps<{ course: Course; assignment: Assignment; submission: Submission }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Courses', href: '/courses' },
    { title: props.course.title, href: `/courses/${props.course.id}` },
    { title: 'Submissions', href: route('assignments.submissions.index', { course: props.course.id, assignment: props.assignment.id }) },
    { title: props.submission.student.name, href: '#' },
];

const form = useForm({
    score: props.submission.score || 0,
    feedback: props.submission.feedback || '',
});

const submit = () => {
    form.patch(route('submissions.grade', { course: props.course.id, assignment: props.assignment.id, submission: props.submission.id }));
};
</script>

<template>
    <Head :title="'Grade: ' + submission.student.name" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-5 max-w-4xl">
            <!-- Header -->
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-lg font-semibold text-foreground">Grading Submission</h1>
                    <p class="mt-0.5 text-sm text-muted-foreground">
                        {{ submission.student.name }}
                        <span class="mx-1 text-border">·</span>
                        <span class="font-mono text-xs">{{ submission.student.email }}</span>
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <StatusBadge :status="submission.status" />
                    <Button variant="outline" size="sm" as-child>
                        <Link :href="route('assignments.submissions.index', { course: course.id, assignment: assignment.id })">
                            <ArrowLeft class="mr-1.5 h-3.5 w-3.5" /> Back
                        </Link>
                    </Button>
                </div>
            </div>

            <div class="grid md:grid-cols-5 gap-5">
                <!-- Student Answer -->
                <div class="md:col-span-3 rounded-lg border border-border bg-card overflow-hidden">
                    <div class="px-4 py-3 border-b border-border bg-muted/30">
                        <h2 class="text-xs font-medium uppercase tracking-widest text-muted-foreground">Student Answer</h2>
                        <p class="text-xs text-muted-foreground mt-0.5">
                            Submitted {{ new Date(submission.submitted_at).toLocaleString() }}
                        </p>
                    </div>
                    <div class="p-4">
                        <div v-if="submission.content" class="text-sm text-foreground whitespace-pre-wrap leading-relaxed">
                            {{ submission.content }}
                        </div>

                        <div v-if="submission.file_path" class="flex items-center gap-3 rounded-md border border-border p-3">
                            <Paperclip class="h-4 w-4 text-muted-foreground flex-shrink-0" />
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-foreground">Attached File</p>
                                <p class="text-xs text-muted-foreground">Submitted by student</p>
                            </div>
                            <Button as-child variant="outline" size="sm">
                                <a :href="submission.file_url!" target="_blank">Download</a>
                            </Button>
                        </div>

                        <div v-if="!submission.content && !submission.file_path" class="py-8 text-center text-sm text-muted-foreground">
                            No content submitted.
                        </div>
                    </div>
                </div>

                <!-- Grading Panel -->
                <div class="md:col-span-2">
                    <div class="rounded-lg border border-border bg-card p-4 space-y-4">
                        <h2 class="text-xs font-medium uppercase tracking-widest text-muted-foreground">Assessment</h2>
                        <form @submit.prevent="submit" class="space-y-4">
                            <div class="space-y-1.5">
                                <Label for="score" class="text-sm">
                                    Score <span class="text-muted-foreground font-normal">(max {{ assignment.max_score }})</span>
                                </Label>
                                <div class="flex items-center gap-2">
                                    <Input
                                        id="score"
                                        type="number"
                                        v-model="form.score"
                                        :max="assignment.max_score"
                                        :min="0"
                                        class="h-9"
                                        :disabled="form.processing"
                                    />
                                    <span class="text-sm text-muted-foreground flex-shrink-0">/ {{ assignment.max_score }}</span>
                                </div>
                                <p v-if="form.errors.score" class="text-xs text-destructive">{{ form.errors.score }}</p>
                            </div>

                            <div class="space-y-1.5">
                                <Label for="feedback" class="text-sm">Feedback</Label>
                                <Textarea
                                    id="feedback"
                                    v-model="form.feedback"
                                    placeholder="Write feedback for the student..."
                                    class="min-h-32 resize-none"
                                    :disabled="form.processing"
                                />
                                <p v-if="form.errors.feedback" class="text-xs text-destructive">{{ form.errors.feedback }}</p>
                            </div>

                            <Button type="submit" size="sm" class="w-full" :disabled="form.processing">
                                {{ form.processing ? 'Saving...' : 'Save grade' }}
                            </Button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
