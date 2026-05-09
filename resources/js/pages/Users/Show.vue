<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import StatusBadge from '@/components/StatusBadge.vue';
import { type User } from '@/types/user';
import { Mail, Phone, Calendar, ArrowLeft, Edit2 } from 'lucide-vue-next';

const props = defineProps<{ user: User }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Users', href: '/users' },
    { title: props.user.name, href: `/users/${props.user.id}` },
];
</script>

<template>
    <Head :title="user.name" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 max-w-3xl space-y-6">
            <!-- Header -->
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-2">
                    <Button variant="outline" size="icon" as-child class="h-8 w-8 hidden sm:flex">
                        <Link :href="route('users.index')"><ArrowLeft class="h-4 w-4" /></Link>
                    </Button>
                    <div>
                        <h1 class="text-lg font-semibold text-foreground">{{ user.name }}</h1>
                        <p class="text-sm text-muted-foreground">{{ user.email }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <StatusBadge :status="user.status" />
                    <Button size="sm" as-child>
                        <Link :href="route('users.edit', user.id)"><Edit2 class="mr-1.5 h-3.5 w-3.5" /> Edit</Link>
                    </Button>
                </div>
            </div>

            <!-- Profile Card -->
            <div class="rounded-lg border border-border bg-card overflow-hidden">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 p-6 border-b border-border bg-muted/20">
                    <img :src="user.avatar_url" alt="" class="h-20 w-20 rounded-full object-cover border-2 border-background shadow-sm" />
                    <div class="space-y-2 flex-1">
                        <div class="flex flex-wrap gap-2">
                            <span v-for="role in user.roles" :key="role.id" class="inline-flex items-center rounded px-2 py-0.5 text-xs font-medium uppercase tracking-wider bg-primary/10 text-primary">
                                {{ role.name }}
                            </span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-y-1 gap-x-4 text-sm text-muted-foreground">
                            <span class="flex items-center gap-1.5"><Mail class="h-3.5 w-3.5" /> {{ user.email }}</span>
                            <span v-if="user.phone" class="flex items-center gap-1.5"><Phone class="h-3.5 w-3.5" /> {{ user.phone }}</span>
                            <span class="flex items-center gap-1.5"><Calendar class="h-3.5 w-3.5" /> Joined {{ new Date(user.created_at).toLocaleDateString() }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <h3 class="text-xs font-medium uppercase tracking-widest text-muted-foreground mb-3">Biography</h3>
                    <p class="text-sm text-foreground leading-relaxed whitespace-pre-wrap">{{ user.bio || 'No biography provided.' }}</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
