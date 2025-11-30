<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Input from '@/Components/Forms/Input.vue';
import Button from '@/Components/UI/Button.vue';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout title="Login">
        <form @submit.prevent="submit" class="space-y-6">
            <Input
                v-model="form.email"
                type="email"
                label="Email"
                :error="form.errors.email"
                placeholder="admin@bptd.go.id"
                required
            />

            <Input
                v-model="form.password"
                type="password"
                label="Password"
                :error="form.errors.password"
                placeholder="••••••••"
                required
            />

            <div class="flex items-center">
                <input
                    id="remember"
                    v-model="form.remember"
                    type="checkbox"
                    class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                />
                <label for="remember" class="ml-2 block text-sm text-gray-900">
                    Remember me
                </label>
            </div>

            <Button
                type="submit"
                variant="primary"
                :disabled="form.processing"
                :loading="form.processing"
                class="w-full"
            >
                Login
            </Button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Demo Credentials:<br>
                <span class="font-mono">admin@bptd.go.id / password</span>
            </p>
        </div>
    </GuestLayout>
</template>