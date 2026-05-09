<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';
import AuthSplitLayout from '@/layouts/auth/AuthSplitLayout.vue';

defineProps<{
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
}>();
</script>

<template>
    <Head title="Log in" />

    <AuthSplitLayout title="Welcome back" description="Log in to your account to continue">
        <div v-if="status" class="mb-4 text-center text-sm font-medium text-emerald-600">
            {{ status }}
        </div>

        <Form
            v-bind="store.form()"
            :reset-on-success="['password']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-5"
        >
            <div class="space-y-1.5">
                <Label for="email" class="text-sm">Email address</Label>
                <Input
                    id="email"
                    type="email"
                    name="email"
                    required
                    autofocus
                    :tabindex="1"
                    autocomplete="email"
                    placeholder="email@example.com"
                    class="h-9"
                />
                <InputError :message="errors.email" />
            </div>

            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <Label for="password" class="text-sm">Password</Label>
                    <TextLink
                        v-if="canResetPassword"
                        :href="request()"
                        class="text-xs text-muted-foreground hover:text-foreground"
                        :tabindex="5"
                    >
                        Forgot password?
                    </TextLink>
                </div>
                <PasswordInput
                    id="password"
                    name="password"
                    required
                    :tabindex="2"
                    autocomplete="current-password"
                    placeholder="Password"
                    class="h-9"
                />
                <InputError :message="errors.password" />
            </div>

            <div class="flex items-center pt-1">
                <Label for="remember" class="flex items-center gap-2 cursor-pointer">
                    <Checkbox id="remember" name="remember" :tabindex="3" class="rounded-sm w-4 h-4" />
                    <span class="text-sm font-normal text-muted-foreground hover:text-foreground transition-colors">Remember for 30 days</span>
                </Label>
            </div>

            <Button
                type="submit"
                class="mt-2 w-full"
                :tabindex="4"
                :disabled="processing"
                data-test="login-button"
            >
                <Spinner v-if="processing" class="mr-2 h-4 w-4" />
                Sign in
            </Button>

            <div v-if="canRegister" class="mt-4 text-center text-sm text-muted-foreground">
                Don't have an account?
                <TextLink :href="register()" :tabindex="5" class="font-medium text-foreground hover:underline ml-1">Sign up</TextLink>
            </div>
        </Form>
    </AuthSplitLayout>
</template>
