<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Plus, Search, Edit2, Trash2, Tags } from 'lucide-vue-next';
import PageHeader from '@/components/PageHeader.vue';
import EmptyState from '@/components/EmptyState.vue';
import Pagination from '@/components/Pagination.vue';
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import DataTable from '@/components/DataTable.vue';
import categoriesRoutes from '@/routes/categories/index';

interface Category {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    parent_id: number | null;
    courses_count: number;
    parent?: Category | null;
}

interface PaginatedData<T> {
    data: T[];
    total: number;
    per_page: number;
    from: number;
    to: number;
    prev_page_url: string | null;
    next_page_url: string | null;
}

const props = defineProps<{
    categories: PaginatedData<Category>;
    filters: Record<string, string>;
}>();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Categories', href: '/categories' }];
const search = ref(props.filters['filter[name]'] || '');
const isModalOpen = ref(false);
const editingCategory = ref<Category | null>(null);

const form = useForm({ name: '', description: '', parent_id: null as number | null });

watch(search, (value) => {
    router.get('/categories', { 'filter[name]': value }, { preserveState: true, replace: true, preserveScroll: true });
});

const openCreateModal = () => {
    editingCategory.value = null;
    form.reset();
    isModalOpen.value = true;
};

const openEditModal = (category: Category) => {
    editingCategory.value = category;
    form.name = category.name;
    form.description = category.description || '';
    form.parent_id = category.parent_id;
    isModalOpen.value = true;
};

const submit = () => {
    const opts = { onSuccess: () => { isModalOpen.value = false; form.reset(); } };
    if (editingCategory.value) {
        form.put(categoriesRoutes.update.url(editingCategory.value.id), opts);
    } else {
        form.post(categoriesRoutes.store.url(), opts);
    }
};

const destroy = (id: number) => router.delete(categoriesRoutes.destroy.url(id));

const columns = [
    { key: 'name', label: 'Name', align: 'left' as const },
    { key: 'description', label: 'Description', align: 'left' as const },
    { key: 'courses', label: 'Courses', align: 'left' as const },
    { key: 'actions', label: '', align: 'right' as const },
];
</script>

<template>
    <Head title="Categories" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-5">
            <PageHeader title="Categories" description="Organize courses by subject area.">
                <template #actions>
                    <Button size="sm" @click="openCreateModal">
                        <Plus class="mr-1.5 h-4 w-4" /> New Category
                    </Button>
                </template>
            </PageHeader>

            <div class="relative max-w-sm">
                <Search class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground pointer-events-none" />
                <Input v-model="search" placeholder="Search categories..." class="pl-9 h-9 text-sm" />
            </div>

            <DataTable v-if="categories.data.length > 0" :columns="columns" :data="categories.data">
                <template #cell(name)="{ item }">
                    <div>
                        <p class="text-sm font-medium text-foreground">{{ item.name }}</p>
                        <p class="text-xs text-muted-foreground font-mono">/{{ item.slug }}</p>
                    </div>
                </template>

                <template #cell(description)="{ item }">
                    <p class="text-sm text-muted-foreground line-clamp-1 max-w-xs">
                        {{ item.description || '—' }}
                    </p>
                </template>

                <template #cell(courses)="{ item }">
                    <span class="text-sm text-foreground">{{ item.courses_count }}</span>
                </template>

                <template #cell(actions)="{ item }">
                    <div class="flex justify-end gap-1">
                        <Button variant="ghost" size="icon" class="h-7 w-7" @click="openEditModal(item)">
                            <Edit2 class="h-3.5 w-3.5" />
                        </Button>
                        <ConfirmDialog
                            title="Delete category"
                            description="Courses in this category will become uncategorized."
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

            <EmptyState v-else title="No categories" description="Create a category to organize your courses." :icon="Tags">
                <template #action>
                    <Button size="sm" variant="outline" @click="openCreateModal">Add category</Button>
                </template>
            </EmptyState>

            <Pagination :data="categories" />
        </div>

        <!-- Create/Edit Modal -->
        <Dialog v-model:open="isModalOpen">
            <DialogContent class="sm:max-w-sm">
                <DialogHeader>
                    <DialogTitle class="text-base font-semibold">{{ editingCategory ? 'Edit Category' : 'New Category' }}</DialogTitle>
                    <DialogDescription class="text-sm">
                        {{ editingCategory ? 'Update this category.' : 'Add a new course category.' }}
                    </DialogDescription>
                </DialogHeader>
                <form @submit.prevent="submit" class="space-y-4 pt-1">
                    <div class="space-y-1.5">
                        <Label for="cat-name" class="text-sm">Name</Label>
                        <Input id="cat-name" v-model="form.name" placeholder="e.g. Programming" class="h-9" />
                        <p v-if="form.errors.name" class="text-xs text-destructive">{{ form.errors.name }}</p>
                    </div>
                    <div class="space-y-1.5">
                        <Label for="cat-desc" class="text-sm">Description <span class="text-muted-foreground font-normal">(optional)</span></Label>
                        <textarea
                            id="cat-desc"
                            v-model="form.description"
                            rows="3"
                            class="flex w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring resize-none"
                            placeholder="Brief description"
                        ></textarea>
                        <p v-if="form.errors.description" class="text-xs text-destructive">{{ form.errors.description }}</p>
                    </div>
                    <DialogFooter class="gap-2">
                        <Button variant="outline" size="sm" type="button" @click="isModalOpen = false">Cancel</Button>
                        <Button size="sm" type="submit" :disabled="form.processing">
                            {{ form.processing ? 'Saving...' : (editingCategory ? 'Update' : 'Create') }}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
