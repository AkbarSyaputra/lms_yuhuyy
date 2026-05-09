<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { ArrowLeft } from 'lucide-vue-next';

const props = defineProps<{ roles: string[] }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Users', href: '/users' },
    { title: 'New User', href: '/users/create' },
];

const form = useForm({
    name: '', email: '', password: '',
    role: 'student', status: 'active',
    phone: '', bio: '',
});

const submit = () => form.post(route('users.store'));
</script>

<template>
    <Head title="Create User" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 max-w-2xl">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-foreground">New User</h1>
                    <p class="mt-0.5 text-sm text-muted-foreground">Add a new user to the system.</p>
                </div>
                <div class="flex items-center gap-2">
                    <Button variant="outline" size="sm" as-child>
                        <Link :href="route('users.index')">
                            <ArrowLeft class="mr-1.5 h-4 w-4" /> Cancel
                        </Link>
                    </Button>
                    <Button size="sm" :disabled="form.processing" @click="submit">
                        {{ form.processing ? 'Saving...' : 'Create user' }}
                    </Button>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <div class="rounded-lg border border-border bg-card p-5 space-y-4">
                    <h2 class="text-xs font-medium uppercase tracking-widest text-muted-foreground">Account Information</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <Label for="name" class="text-sm">Name</Label>
                            <Input id="name" v-model="form.name" class="h-9" required autofocus />
                            <p v-if="form.errors.name" class="text-xs text-destructive">{{ form.errors.name }}</p>
                        </div>
                        <div class="space-y-1.5">
                            <Label for="email" class="text-sm">Email</Label>
                            <Input id="email" type="email" v-model="form.email" class="h-9" required />
                            <p v-if="form.errors.email" class="text-xs text-destructive">{{ form.errors.email }}</p>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <Label for="password" class="text-sm">Password</Label>
                        <Input id="password" type="password" v-model="form.password" class="h-9" required />
                        <p v-if="form.errors.password" class="text-xs text-destructive">{{ form.errors.password }}</p>
                    </div>
                </div>

                <div class="rounded-lg border border-border bg-card p-5 space-y-4">
                    <h2 class="text-xs font-medium uppercase tracking-widest text-muted-foreground">Access & Role</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <Label for="role" class="text-sm">Role</Label>
                            <select
                                id="role"
                                v-model="form.role"
                                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                            >
                                <option v-for="role in roles" :key="role" :value="role" class="capitalize">{{ role }}</option>
                            </select>
                            <p v-if="form.errors.role" class="text-xs text-destructive">{{ form.errors.role }}</p>
                        </div>
                        <div class="space-y-1.5">
                            <Label for="status" class="text-sm">Status</Label>
                            <select
                                id="status"
                                v-model="form.status"
                                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                            >
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                            </select>
                            <p v-if="form.errors.status" class="text-xs text-destructive">{{ form.errors.status }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border border-border bg-card p-5 space-y-4">
                    <h2 class="text-xs font-medium uppercase tracking-widest text-muted-foreground">Profile Details (Optional)</h2>
                    <div class="space-y-1.5">
                        <Label for="phone" class="text-sm">Phone Number</Label>
                        <Input id="phone" v-model="form.phone" class="h-9" />
                        <p v-if="form.errors.phone" class="text-xs text-destructive">{{ form.errors.phone }}</p>
                    </div>
                    <div class="space-y-1.5">
                        <Label for="bio" class="text-sm">Bio</Label>
                        <textarea
                            id="bio"
                            v-model="form.bio"
                            rows="4"
                            class="flex w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring resize-none"
                        ></textarea>
                        <p v-if="form.errors.bio" class="text-xs text-destructive">{{ form.errors.bio }}</p>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
