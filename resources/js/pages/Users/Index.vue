<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { PaginatedData, User } from '@/types/user';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { ref, watch } from 'vue';
import { Plus, Search, Eye, Edit2, Trash2, Users } from 'lucide-vue-next';
import PageHeader from '@/components/PageHeader.vue';
import StatusBadge from '@/components/StatusBadge.vue';
import EmptyState from '@/components/EmptyState.vue';
import Pagination from '@/components/Pagination.vue';
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import DataTable from '@/components/DataTable.vue';
import usersRoutes from '@/routes/users/index';

const props = defineProps<{
    users: PaginatedData<User>;
    roles: string[];
    filters: Record<string, string>;
}>();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Users', href: '/users' }];
const search = ref(props.filters['filter[name]'] || '');

watch(search, (value) => {
    router.get('/users', { 'filter[name]': value }, { preserveState: true, replace: true, preserveScroll: true });
});

const destroy = (id: number) => router.delete(`/users/${id}`);

const columns = [
    { key: 'user', label: 'User', align: 'left' as const },
    { key: 'role', label: 'Role', align: 'left' as const },
    { key: 'status', label: 'Status', align: 'left' as const },
    { key: 'actions', label: '', align: 'right' as const },
];
</script>

<template>
    <Head title="Users" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-5">
            <PageHeader title="Users" description="Manage platform users, roles, and permissions.">
                <template #actions>
                    <Button as-child size="sm">
                        <Link :href="usersRoutes.create.url()">
                            <Plus class="mr-1.5 h-4 w-4" /> New User
                        </Link>
                    </Button>
                </template>
            </PageHeader>

            <div class="relative max-w-sm">
                <Search class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground pointer-events-none" />
                <Input v-model="search" placeholder="Search users..." class="pl-9 h-9 text-sm" />
            </div>

            <DataTable v-if="users.data.length > 0" :columns="columns" :data="users.data">
                <template #cell(user)="{ item }">
                    <div class="flex items-center gap-3">
                        <img :src="item.avatar_url" alt="" class="h-8 w-8 rounded-full object-cover flex-shrink-0" />
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-foreground truncate">{{ item.name }}</p>
                            <p class="text-xs text-muted-foreground truncate">{{ item.email }}</p>
                        </div>
                    </div>
                </template>

                <template #cell(role)="{ item }">
                    <div class="flex flex-wrap gap-1">
                        <span
                            v-for="role in item.roles"
                            :key="role.id"
                            class="inline-flex items-center rounded px-1.5 py-0.5 text-[10px] font-medium uppercase tracking-wider bg-muted text-muted-foreground"
                        >
                            {{ role.name }}
                        </span>
                    </div>
                </template>

                <template #cell(status)="{ item }">
                    <StatusBadge :status="item.status" />
                </template>

                <template #cell(actions)="{ item }">
                    <div class="flex justify-end gap-1">
                        <Button variant="ghost" size="icon" as-child class="h-7 w-7">
                            <Link :href="usersRoutes.show.url(item.id)" title="View">
                                <Eye class="h-3.5 w-3.5" />
                            </Link>
                        </Button>
                        <Button variant="ghost" size="icon" as-child class="h-7 w-7">
                            <Link :href="usersRoutes.edit.url(item.id)" title="Edit">
                                <Edit2 class="h-3.5 w-3.5" />
                            </Link>
                        </Button>
                        <ConfirmDialog
                            title="Delete user"
                            description="This will permanently delete the user account."
                            confirmText="Delete"
                            destructive
                            @confirm="destroy(item.id)"
                        >
                            <Button variant="ghost" size="icon" class="h-7 w-7 hover:text-destructive">
                                <Trash2 class="h-3.5 w-3.5" />
                            </Button>
                        </ConfirmDialog>
                    </div>
                </template>
            </DataTable>

            <EmptyState
                v-else
                title="No users found"
                description="No users match your search criteria."
                :icon="Users"
            >
                <template #action>
                    <Button as-child size="sm" variant="outline">
                        <Link :href="usersRoutes.create.url()">Add user</Link>
                    </Button>
                </template>
            </EmptyState>

            <Pagination :data="users" />
        </div>
    </AppLayout>
</template>
