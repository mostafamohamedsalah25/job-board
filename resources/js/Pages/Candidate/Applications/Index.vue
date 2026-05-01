<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    applications: Array,
});

const statusClasses = (status) => {
    return {
        'bg-yellow-100 text-yellow-800': status === 'pending',
        'bg-green-100 text-green-800': status === 'accepted',
        'bg-red-100 text-red-800': status === 'rejected',
    };
};
</script>

<template>
    <Head title="My Applications" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Applications</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div v-if="$page.props.flash && $page.props.flash.success" class="mb-4 px-4 py-3 bg-green-100 text-green-700 rounded border border-green-200">
                    {{ $page.props.flash.success }}
                </div>
                <div v-if="$page.props.flash && $page.props.flash.error" class="mb-4 px-4 py-3 bg-red-100 text-red-700 rounded border border-red-200">
                    {{ $page.props.flash.error }}
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Job Title</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied On</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-if="applications.length === 0">
                                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        You haven't applied to any jobs yet.
                                        <Link :href="route('candidate.jobs.index')" class="text-indigo-600 hover:underline ml-1">Browse jobs now.</Link>
                                    </td>
                                </tr>

                                <tr v-for="application in applications" :key="application.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <Link :href="route('candidate.jobs.show', application.job_posting_id)" class="font-medium text-indigo-600 hover:text-indigo-900">
                                            {{ application.job_posting?.title }}
                                        </Link>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ application.job_posting?.employer?.company_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ new Date(application.created_at).toLocaleDateString() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="['px-2 inline-flex text-xs leading-5 font-semibold rounded-full capitalize', statusClasses(application.status)]">
                                            {{ application.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <!-- زر التراجع يظهر فقط لو كانت الحالة Pending -->
                                        <Link v-if="application.status === 'pending'"
                                              :href="route('candidate.applications.destroy', application.id)"
                                              method="delete" as="button" type="button"
                                              class="text-red-600 hover:text-red-900"
                                              preserve-scroll
                                              onclick="return confirm('Are you sure you want to withdraw this application?')">
                                            Withdraw
                                        </Link>
                                        <span v-else class="text-gray-400 text-xs italic">Reviewed</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
