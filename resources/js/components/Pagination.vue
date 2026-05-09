<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { router } from '@inertiajs/vue3';

defineProps<{
    data: {
        from: number;
        to: number;
        total: number;
        per_page: number;
        prev_page_url: string | null;
        next_page_url: string | null;
    };
}>();
</script>

<template>
    <div v-if="data.total > data.per_page" class="mt-auto flex items-center justify-between border-t border-border pt-4">
        <div class="text-sm font-medium text-muted-foreground">
            Showing <span class="font-bold text-foreground">{{ data.from }}</span> to <span class="font-bold text-foreground">{{ data.to }}</span> of <span class="font-bold text-foreground">{{ data.total }}</span> results
        </div>
        <div class="flex items-center space-x-2">
            <Button
                variant="outline"
                size="sm"
                :disabled="!data.prev_page_url"
                @click="data.prev_page_url && router.get(data.prev_page_url)"
                class="hover:bg-primary/10 hover:text-primary transition-colors"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1 h-4 w-4"><path d="m15 18-6-6 6-6"/></svg>
                Previous
            </Button>
            <Button
                variant="outline"
                size="sm"
                :disabled="!data.next_page_url"
                @click="data.next_page_url && router.get(data.next_page_url)"
                class="hover:bg-primary/10 hover:text-primary transition-colors"
            >
                Next
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-1 h-4 w-4"><path d="m9 18 6-6-6-6"/></svg>
            </Button>
        </div>
    </div>
</template>
