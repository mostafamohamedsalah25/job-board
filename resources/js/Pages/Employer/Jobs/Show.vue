<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    job: Object,
});

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};
</script>

<template>
    <Head :title="job.title" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Job Details: {{ job.title }}</h2>
                <div class="space-x-2">
                    <Link :href="route('employer.jobs.index')" class="text-gray-600 hover:text-gray-900 underline">Back to Jobs</Link>
                    <Link :href="route('employer.jobs.edit', job.id)" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                        Edit Job
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                    <!-- Header Section -->
                    <div class="border-b pb-4 mb-4">
                        <h3 class="text-2xl font-bold text-gray-900">{{ job.title }}</h3>
                        <div class="mt-2 flex flex-wrap gap-4 text-sm text-gray-600">
                            <span class="flex items-center">
                                <!-- أيقونة التصنيف -->
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                {{ job.category ? job.category.name : 'Uncategorized' }}
                            </span>
                            <span class="flex items-center capitalize">
                                <!-- أيقونة نوع العمل -->
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                {{ job.type }}
                            </span>
                            <span class="flex items-center">
                                <!-- أيقونة الموقع -->
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ job.location }}
                            </span>
                        </div>
                    </div>

                    <!-- Details Section -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Main Content (Description) -->
                        <div class="md:col-span-2 space-y-6">
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800 mb-2">Job Description</h4>
                                <div class="text-gray-700 whitespace-pre-wrap leading-relaxed">
                                    {{ job.description }}
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar (Status & Quick Facts) -->
                        <div class="bg-gray-50 p-4 rounded-lg border space-y-4">
                            <div>
                                <span class="block text-sm font-medium text-gray-500">Status</span>
                                <span class="capitalize font-semibold"
                                    :class="{'text-yellow-600': job.status === 'pending', 'text-green-600': job.status === 'approved', 'text-red-600': job.status === 'rejected'}">
                                    {{ job.status }}
                                </span>
                            </div>

                            <div v-if="job.salary_range">
                                <span class="block text-sm font-medium text-gray-500">Salary Range</span>
                                <span class="text-gray-900 font-medium">{{ job.salary_range }}</span>
                            </div>

                            <div>
                                <span class="block text-sm font-medium text-gray-500">Application Deadline</span>
                                <span class="text-gray-900 font-medium">{{ formatDate(job.deadline) }}</span>
                            </div>

                            <div class="pt-4 border-t">
                                <span class="block text-sm font-medium text-gray-500">Total Applications</span>
                                <span class="text-2xl font-bold text-indigo-600">{{ job.applications_count }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
