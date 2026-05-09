<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import StatusBadge from '@/components/StatusBadge.vue';
import { Calendar, Award } from 'lucide-vue-next';

interface Course { id: number; title: string; }
interface Assignment {
    id: number; title: string; description: string | null;
    type: 'file_upload' | 'text' | 'quiz'; max_score: number | null; due_date: string | null;
}
interface Submission {
    id: number; content: string | null; file_path: string | null; file_url: string | null;
    score: number | null; feedback: string | null; status: string;
    submitted_at: string; graded_at: string | null;
}

const props = defineProps<{
    course: Course;
    assignment: Assignment;
    submission: Submission | null;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Courses', href: '/courses' },
    { title: props.course.title, href: `/courses/${props.course.id}` },
    { title: props.assignment.title, href: '#' },
];

const form = useForm({
    content: props.submission?.content || '',
    file: null as File | null,
});

const submit = () => {
    form.post(route('submissions.store', { course: props.course.id, assignment: props.assignment.id }), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head :title="assignment.title" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 max-w-3xl space-y-5">

            <!-- Assignment Info -->
            <div class="rounded-lg border border-border bg-card p-5">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h1 class="text-lg font-semibold text-foreground">{{ assignment.title }}</h1>
                        <p class="mt-0.5 text-sm text-muted-foreground capitalize">{{ assignment.type.replace('_', ' ') }} assignment</p>
                    </div>
                    <div class="flex-shrink-0 text-right space-y-1.5">
                        <div v-if="assignment.due_date" class="flex items-center gap-1.5 text-xs text-muted-foreground justify-end">
                            <Calendar class="h-3.5 w-3.5" />
                            Due {{ new Date(assignment.due_date).toLocaleString() }}
                        </div>
                        <div v-if="assignment.max_score" class="flex items-center gap-1.5 text-xs text-muted-foreground justify-end">
                            <Award class="h-3.5 w-3.5" />
                            {{ assignment.max_score }} points max
                        </div>
                    </div>
                </div>
                <div v-if="assignment.description" class="mt-4 text-sm text-muted-foreground leading-relaxed whitespace-pre-wrap border-t border-border pt-4">
                    {{ assignment.description }}
                </div>
            </div>

            <!-- Result (if graded) -->
            <div v-if="submission?.status === 'graded'" class="rounded-lg border border-border bg-card p-5">
                <h2 class="text-xs font-medium uppercase tracking-widest text-muted-foreground mb-4">Result</h2>
                <div class="flex items-baseline gap-2 mb-4">
                    <span class="text-4xl font-bold text-foreground">{{ submission.score }}</span>
                    <span class="text-muted-foreground text-sm">/ {{ assignment.max_score }}</span>
                </div>
                <div v-if="submission.feedback" class="rounded-md bg-muted/50 p-4">
                    <p class="text-xs font-medium uppercase tracking-widest text-muted-foreground mb-2">Teacher Feedback</p>
                    <p class="text-sm text-foreground leading-relaxed whitespace-pre-wrap">{{ submission.feedback }}</p>
                </div>
            </div>

            <!-- Submission status (not graded) -->
            <div v-else-if="submission" class="rounded-lg border border-border bg-card p-4 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-foreground">Submission received</p>
                    <p class="text-xs text-muted-foreground mt-0.5">
                        Submitted {{ new Date(submission.submitted_at).toLocaleString() }}
                    </p>
                </div>
                <StatusBadge :status="submission.status" />
            </div>

            <!-- Submit Form (if not graded) -->
            <div v-if="!submission || submission.status !== 'graded'" class="rounded-lg border border-border bg-card p-5 space-y-4">
                <h2 class="text-xs font-medium uppercase tracking-widest text-muted-foreground">
                    {{ submission ? 'Update your submission' : 'Your submission' }}
                </h2>

                <form @submit.prevent="submit" class="space-y-4">
                    <div v-if="assignment.type === 'text' || assignment.type === 'quiz'" class="space-y-1.5">
                        <Label for="content" class="text-sm">Answer</Label>
                        <Textarea
                            id="content"
                            v-model="form.content"
                            placeholder="Type your answer here..."
                            class="min-h-40 resize-none"
                            :disabled="form.processing"
                        />
                        <p v-if="form.errors.content" class="text-xs text-destructive">{{ form.errors.content }}</p>
                    </div>

                    <div v-if="assignment.type === 'file_upload'" class="space-y-1.5">
                        <Label for="file" class="text-sm">File</Label>
                        <Input
                            id="file"
                            type="file"
                            @input="form.file = ($event.target as HTMLInputElement).files?.[0] || null"
                            :disabled="form.processing"
                            class="h-auto py-1.5 cursor-pointer file:mr-3 file:rounded file:border-0 file:bg-muted file:px-2.5 file:py-1 file:text-xs file:font-medium"
                        />
                        <p class="text-xs text-muted-foreground">PDF, DOC, DOCX, ZIP — max 10MB</p>
                        <a v-if="submission?.file_url" :href="submission.file_url" target="_blank" class="text-xs text-primary hover:underline">
                            View current submission →
                        </a>
                        <p v-if="form.errors.file" class="text-xs text-destructive">{{ form.errors.file }}</p>
                    </div>

                    <div class="flex justify-end">
                        <Button type="submit" size="sm" :disabled="form.processing">
                            {{ form.processing ? 'Submitting...' : (submission ? 'Update submission' : 'Submit assignment') }}
                        </Button>
                    </div>
                </form>
            </div>

        </div>
    </AppLayout>
</template>
