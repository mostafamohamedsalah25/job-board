<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profile Settings" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2 class="font-display text-2xl font-bold text-primary">Profile Settings</h2>
                <p class="text-sm text-on-surface-variant mt-1">Manage your account details, password, and security.</p>
            </div>
        </template>

        <div class="mt-6">
            <div class="mx-auto max-w-4xl space-y-6">
                <!-- كارت المعلومات الشخصية -->
                <div class="bg-white p-8 shadow-sm border border-outline sm:rounded-xl">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <!-- كارت تغيير كلمة المرور -->
                <div class="bg-white p-8 shadow-sm border border-outline sm:rounded-xl">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <!-- كارت حذف الحساب -->
                <div class="bg-white p-8 shadow-sm border border-red-100 sm:rounded-xl bg-red-50/10">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
