<script setup lang="ts" generic="T">
const props = defineProps<{
    columns: Array<{
        key: keyof T | string;
        label: string;
        align?: 'left' | 'center' | 'right';
        class?: string;
    }>;
    data: T[];
}>();

const getAlignClass = (align?: 'left' | 'center' | 'right') => {
    switch (align) {
        case 'center': return 'text-center';
        case 'right': return 'text-right';
        default: return 'text-left';
    }
};
</script>

<template>
    <div class="overflow-hidden rounded-lg border border-border bg-card">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border bg-muted/40">
                        <th
                            v-for="col in columns"
                            :key="String(col.key)"
                            :class="[
                                'px-4 py-3 text-xs font-medium uppercase tracking-widest text-muted-foreground',
                                getAlignClass(col.align),
                                col.class
                            ]"
                        >
                            {{ col.label }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    <tr
                        v-for="(row, index) in data"
                        :key="index"
                        class="transition-colors hover:bg-muted/30"
                    >
                        <td
                            v-for="col in columns"
                            :key="String(col.key)"
                            :class="[
                                'px-4 py-3 align-middle',
                                getAlignClass(col.align),
                                col.class
                            ]"
                        >
                            <slot :name="`cell(${String(col.key)})`" :item="row">
                                {{ (row as any)[col.key] }}
                            </slot>
                        </td>
                    </tr>
                    <tr v-if="data.length === 0">
                        <td :colspan="columns.length" class="py-12 text-center text-sm text-muted-foreground">
                            <slot name="empty">No records found.</slot>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
