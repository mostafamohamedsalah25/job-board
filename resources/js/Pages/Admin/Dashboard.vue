<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    jobs: Object,
});

const statusClasses = (status) => {
    return {
        'bg-yellow-100 text-yellow-800': status === 'pending',
        'bg-green-100 text-green-800': status === 'approved',
        'bg-red-100 text-red-800': status === 'rejected',
    };
};
</script>

<template>
    <Head title="Admin Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Admin Dashboard - Job Approvals</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div v-if="$page.props.flash && $page.props.flash.success" class="mb-4 px-4 py-3 bg-green-100 text-green-700 rounded border border-green-200">
                    {{ $page.props.flash.success }}
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Job / Company</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Posted</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="job in jobs.data" :key="job.id" :class="{'bg-yellow-50': job.status === 'pending'}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900">{{ job.title }}</div>
                                        <div class="text-sm text-gray-500">{{ job.employer?.company_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ new Date(job.created_at).toLocaleDateString() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="['px-2 inline-flex text-xs leading-5 font-semibold rounded-full capitalize', statusClasses(job.status)]">
                                            {{ job.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">

                                        <!-- زر الموافقة -->
                                        <Link v-if="job.status !== 'approved'" :href="route('admin.jobs.status', job.id)" method="patch" :data="{ status: 'approved' }" as="button" class="inline-flex items-center px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-xs transition">
                                            Approve
                                        </Link>

                                        <!-- زر الرفض -->
                                        <Link v-if="job.status !== 'rejected'" :href="route('admin.jobs.status', job.id)" method="patch" :data="{ status: 'rejected' }" as="button" class="inline-flex items-center px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-xs transition">
                                            Reject
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div v-if="jobs.links" class="mt-6">
                            <div class="flex flex-wrap gap-1">
                                <template v-for="(link, index) in jobs.links" :key="index">
                                    <div v-if="link.url === null" class="px-4 py-2 border rounded text-gray-400 bg-gray-50 text-sm" v-html="link.label"></div>
                                    <Link v-else :href="link.url" class="px-4 py-2 border rounded hover:bg-gray-100 text-sm" :class="{ 'bg-indigo-600 text-white hover:bg-indigo-700 border-indigo-600': link.active }" v-html="link.label"></Link>
                                </template>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
