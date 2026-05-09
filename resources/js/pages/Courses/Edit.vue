<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { ArrowLeft } from 'lucide-vue-next';

const props = defineProps<{
    course: any;
    categories: any[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Courses', href: '/courses' },
    { title: props.course.title, href: `/courses/${props.course.id}` },
    { title: 'Edit', href: `/courses/${props.course.id}/edit` },
];

const form = useForm({
    title: props.course.title,
    description: props.course.description || '',
    category_id: props.course.category_id?.toString(),
    status: props.course.status,
    max_students: props.course.max_students,
    start_date: props.course.start_date ? props.course.start_date.split('T')[0] : '',
    end_date: props.course.end_date ? props.course.end_date.split('T')[0] : '',
});

const submit = () => form.put(route('courses.update', props.course.id));
</script>

<template>
    <Head title="Edit Course" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 max-w-3xl">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-foreground">Edit Course</h1>
                    <p class="mt-0.5 text-sm text-muted-foreground">Modify your course details and settings.</p>
                </div>
                <div class="flex items-center gap-2">
                    <Button variant="outline" size="sm" as-child>
                        <Link :href="route('courses.show', course.id)">
                            <ArrowLeft class="mr-1.5 h-4 w-4" /> Cancel
                        </Link>
                    </Button>
                    <Button size="sm" :disabled="form.processing" @click="submit">
                        {{ form.processing ? 'Saving...' : 'Save changes' }}
                    </Button>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <div class="rounded-lg border border-border bg-card p-5 space-y-4">
                    <h2 class="text-xs font-medium uppercase tracking-widest text-muted-foreground">Basic Information</h2>
                    <div class="space-y-1.5">
                        <Label for="title" class="text-sm">Title</Label>
                        <Input id="title" v-model="form.title" placeholder="Course title" class="h-9" />
                        <p v-if="form.errors.title" class="text-xs text-destructive">{{ form.errors.title }}</p>
                    </div>
                    <div class="space-y-1.5">
                        <Label for="description" class="text-sm">Description</Label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="4"
                            class="flex w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring resize-none"
                            placeholder="What will students learn?"
                        ></textarea>
                        <p v-if="form.errors.description" class="text-xs text-destructive">{{ form.errors.description }}</p>
                    </div>
                </div>

                <div class="rounded-lg border border-border bg-card p-5 space-y-4">
                    <h2 class="text-xs font-medium uppercase tracking-widest text-muted-foreground">Classification</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <Label class="text-sm">Category</Label>
                            <Select v-model="form.category_id">
                                <SelectTrigger class="h-9"><SelectValue placeholder="Select category" /></SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="cat in categories" :key="cat.id" :value="cat.id.toString()">{{ cat.name }}</SelectItem>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.category_id" class="text-xs text-destructive">{{ form.errors.category_id }}</p>
                        </div>
                        <div class="space-y-1.5">
                            <Label class="text-sm">Status</Label>
                            <Select v-model="form.status">
                                <SelectTrigger class="h-9"><SelectValue placeholder="Select status" /></SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="draft">Draft</SelectItem>
                                    <SelectItem value="published">Published</SelectItem>
                                    <SelectItem value="archived">Archived</SelectItem>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.status" class="text-xs text-destructive">{{ form.errors.status }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border border-border bg-card p-5 space-y-4">
                    <h2 class="text-xs font-medium uppercase tracking-widest text-muted-foreground">Schedule & Capacity</h2>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="space-y-1.5">
                            <Label for="start_date" class="text-sm">Start Date</Label>
                            <Input id="start_date" type="date" v-model="form.start_date" class="h-9" />
                            <p v-if="form.errors.start_date" class="text-xs text-destructive">{{ form.errors.start_date }}</p>
                        </div>
                        <div class="space-y-1.5">
                            <Label for="end_date" class="text-sm">End Date</Label>
                            <Input id="end_date" type="date" v-model="form.end_date" class="h-9" />
                            <p v-if="form.errors.end_date" class="text-xs text-destructive">{{ form.errors.end_date }}</p>
                        </div>
                        <div class="space-y-1.5">
                            <Label for="max_students" class="text-sm">Max Students</Label>
                            <Input id="max_students" type="number" v-model="form.max_students" placeholder="Unlimited" class="h-9" />
                            <p v-if="form.errors.max_students" class="text-xs text-destructive">{{ form.errors.max_students }}</p>
                        </div>
                    </div>
                </div>

                <p class="text-xs text-muted-foreground">
                    Publishing a course makes it immediately visible to all enrolled students.
                </p>
            </form>
        </div>
    </AppLayout>
</template>
