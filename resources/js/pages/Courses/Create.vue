<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { ArrowLeft } from 'lucide-vue-next';

const props = defineProps<{ categories: any[] }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Courses', href: '/courses' },
    { title: 'New Course', href: '/courses/create' },
];

const form = useForm({
    title: '',
    description: '',
    category_id: undefined as number | string | undefined,
    status: 'draft',
    max_students: undefined as number | string | undefined,
    start_date: '',
    end_date: '',
});

const submit = () => form.post(route('courses.store'));
</script>

<template>
    <Head title="Create Course" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 max-w-3xl">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-foreground">New Course</h1>
                    <p class="mt-0.5 text-sm text-muted-foreground">Define your course structure and basic information.</p>
                </div>
                <div class="flex items-center gap-2">
                    <Button variant="outline" size="sm" as-child>
                        <Link :href="route('courses.index')">
                            <ArrowLeft class="mr-1.5 h-4 w-4" /> Cancel
                        </Link>
                    </Button>
                    <Button size="sm" type="submit" :disabled="form.processing" @click="submit">
                        {{ form.processing ? 'Saving...' : 'Create course' }}
                    </Button>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <!-- Basic Info -->
                <div class="rounded-lg border border-border bg-card p-5 space-y-4">
                    <h2 class="text-xs font-medium uppercase tracking-widest text-muted-foreground">Basic Information</h2>
                    <div class="space-y-1.5">
                        <Label for="title" class="text-sm">Title</Label>
                        <Input id="title" v-model="form.title" placeholder="e.g. Introduction to Programming" class="h-9" />
                        <p v-if="form.errors.title" class="text-xs text-destructive">{{ form.errors.title }}</p>
                    </div>
                    <div class="space-y-1.5">
                        <Label for="description" class="text-sm">Description</Label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="4"
                            class="flex w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 resize-none"
                            placeholder="What will students learn in this course?"
                        ></textarea>
                        <p v-if="form.errors.description" class="text-xs text-destructive">{{ form.errors.description }}</p>
                    </div>
                </div>

                <!-- Classification -->
                <div class="rounded-lg border border-border bg-card p-5 space-y-4">
                    <h2 class="text-xs font-medium uppercase tracking-widest text-muted-foreground">Classification</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <Label class="text-sm">Category</Label>
                            <Select v-model="form.category_id">
                                <SelectTrigger class="h-9">
                                    <SelectValue placeholder="Select category" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="cat in categories" :key="cat.id" :value="cat.id.toString()">
                                        {{ cat.name }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.category_id" class="text-xs text-destructive">{{ form.errors.category_id }}</p>
                        </div>
                        <div class="space-y-1.5">
                            <Label class="text-sm">Status</Label>
                            <Select v-model="form.status">
                                <SelectTrigger class="h-9">
                                    <SelectValue placeholder="Select status" />
                                </SelectTrigger>
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

                <!-- Schedule -->
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
            </form>
        </div>
    </AppLayout>
</template>
