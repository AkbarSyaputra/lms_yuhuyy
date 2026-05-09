<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
</script>

<template>
    <div class="flex aspect-square size-8 items-center justify-center rounded-md bg-primary text-primary-foreground">
        <AppLogoIcon class="size-5 fill-current text-primary-foreground" />
    </div>
    <div class="ml-2 grid flex-1 text-left text-sm leading-tight">
        <span class="truncate font-semibold tracking-tight text-foreground">Eduzy</span>
        <span class="truncate text-[10px] uppercase tracking-widest text-muted-foreground">LMS</span>
    </div>
</template>
