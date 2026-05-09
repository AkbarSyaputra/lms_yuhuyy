<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { ArrowLeft } from 'lucide-vue-next';

interface Course { id: number; title: string; }

const props = defineProps<{ course: Course }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Courses', href: '/courses' },
    { title: props.course.title, href: `/courses/${props.course.id}` },
    { title: 'Add Assignment', href: '#' },
];

const form = useForm({
    title: '',
    type: 'text' as 'file_upload' | 'text' | 'quiz',
    description: '',
    max_score: undefined as number | undefined,
    due_date: '',
    is_published: false,
});

const submit = () => form.post(route('assignments.store', { course: props.course.id }));
</script>

<template>
    <Head title="Add Assignment" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 max-w-2xl">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-foreground">New Assignment</h1>
                    <p class="mt-0.5 text-sm text-muted-foreground">For <span class="font-medium text-foreground">{{ course.title }}</span></p>
                </div>
                <div class="flex items-center gap-2">
                    <Button variant="outline" size="sm" as-child>
                        <Link :href="route('courses.show', course.id)">
                            <ArrowLeft class="mr-1.5 h-4 w-4" /> Cancel
                        </Link>
                    </Button>
                    <Button size="sm" type="submit" :disabled="form.processing" @click="submit">
                        {{ form.processing ? 'Saving...' : 'Save assignment' }}
                    </Button>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <div class="rounded-lg border border-border bg-card p-5 space-y-4">
                    <h2 class="text-xs font-medium uppercase tracking-widest text-muted-foreground">Assignment Details</h2>

                    <div class="space-y-1.5">
                        <Label for="title" class="text-sm">Title</Label>
                        <Input id="title" v-model="form.title" placeholder="e.g. Final Project" class="h-9" />
                        <p v-if="form.errors.title" class="text-xs text-destructive">{{ form.errors.title }}</p>
                    </div>

                    <div class="space-y-1.5">
                        <Label for="type" class="text-sm">Type</Label>
                        <select
                            id="type"
                            v-model="form.type"
                            class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                        >
                            <option value="text">Text response</option>
                            <option value="file_upload">File upload</option>
                            <option value="quiz">Quiz</option>
                        </select>
                        <p v-if="form.errors.type" class="text-xs text-destructive">{{ form.errors.type }}</p>
                    </div>

                    <div class="space-y-1.5">
                        <Label for="description" class="text-sm">Instructions</Label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="5"
                            class="flex w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring resize-none"
                            placeholder="Explain what students need to do..."
                        ></textarea>
                        <p v-if="form.errors.description" class="text-xs text-destructive">{{ form.errors.description }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <Label for="max_score" class="text-sm">Max Score</Label>
                            <Input id="max_score" v-model="form.max_score" type="number" placeholder="100" min="0" max="100" class="h-9" />
                            <p v-if="form.errors.max_score" class="text-xs text-destructive">{{ form.errors.max_score }}</p>
                        </div>
                        <div class="space-y-1.5">
                            <Label for="due_date" class="text-sm">Due Date</Label>
                            <Input id="due_date" v-model="form.due_date" type="datetime-local" class="h-9" />
                            <p v-if="form.errors.due_date" class="text-xs text-destructive">{{ form.errors.due_date }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-1">
                        <input id="is_published" v-model="form.is_published" type="checkbox" class="h-4 w-4 rounded border-input accent-primary" />
                        <Label for="is_published" class="text-sm cursor-pointer font-normal">Publish immediately</Label>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
