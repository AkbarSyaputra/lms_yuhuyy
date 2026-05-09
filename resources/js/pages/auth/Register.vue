<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { login } from '@/routes';
import { store } from '@/routes/register';
import AuthSplitLayout from '@/layouts/auth/AuthSplitLayout.vue';

</script>

<template>
    <Head title="Register" />

    <AuthSplitLayout title="Create an account" description="Enter your details below to get started">
        <Form
            v-bind="store.form()"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-5"
        >
            <div class="space-y-1.5">
                <Label for="name" class="text-sm">Name</Label>
                <Input
                    id="name"
                    type="text"
                    required
                    autofocus
                    :tabindex="1"
                    autocomplete="name"
                    name="name"
                    placeholder="Full name"
                    class="h-9"
                />
                <InputError :message="errors.name" />
            </div>

            <div class="space-y-1.5">
                <Label for="email" class="text-sm">Email address</Label>
                <Input
                    id="email"
                    type="email"
                    required
                    :tabindex="2"
                    autocomplete="email"
                    name="email"
                    placeholder="email@example.com"
                    class="h-9"
                />
                <InputError :message="errors.email" />
            </div>

            <div class="space-y-1.5">
                <Label for="password" class="text-sm">Password</Label>
                <PasswordInput
                    id="password"
                    required
                    :tabindex="3"
                    autocomplete="new-password"
                    name="password"
                    placeholder="Create a password"
                    class="h-9"
                />
                <InputError :message="errors.password" />
            </div>

            <div class="space-y-1.5">
                <Label for="password_confirmation" class="text-sm">Confirm password</Label>
                <PasswordInput
                    id="password_confirmation"
                    required
                    :tabindex="4"
                    autocomplete="new-password"
                    name="password_confirmation"
                    placeholder="Confirm your password"
                    class="h-9"
                />
                <InputError :message="errors.password_confirmation" />
            </div>

            <Button
                type="submit"
                class="mt-2 w-full"
                tabindex="5"
                :disabled="processing"
                data-test="register-user-button"
            >
                <Spinner v-if="processing" class="mr-2 h-4 w-4" />
                Create account
            </Button>

            <div class="mt-4 text-center text-sm text-muted-foreground">
                Already have an account?
                <TextLink :href="login()" :tabindex="6" class="font-medium text-foreground hover:underline ml-1">Log in</TextLink>
            </div>
        </Form>
    </AuthSplitLayout>
</template>
