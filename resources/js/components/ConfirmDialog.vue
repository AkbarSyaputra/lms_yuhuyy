<script setup lang="ts">
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { ref } from 'vue';

const props = defineProps<{
    title: string;
    description: string;
    confirmText?: string;
    cancelText?: string;
    destructive?: boolean;
}>();

const emit = defineEmits<{
    (e: 'confirm'): void;
    (e: 'cancel'): void;
}>();

const isOpen = ref(false);

const handleConfirm = () => {
    emit('confirm');
    isOpen.value = false;
};

const handleCancel = () => {
    emit('cancel');
    isOpen.value = false;
};
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger asChild>
            <slot />
        </DialogTrigger>
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle>{{ title }}</DialogTitle>
                <DialogDescription>
                    {{ description }}
                </DialogDescription>
            </DialogHeader>
            <DialogFooter class="flex sm:justify-end gap-2 mt-4">
                <Button variant="outline" @click="handleCancel">
                    {{ cancelText || 'Cancel' }}
                </Button>
                <Button :variant="destructive ? 'destructive' : 'default'" @click="handleConfirm">
                    {{ confirmText || 'Confirm' }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
