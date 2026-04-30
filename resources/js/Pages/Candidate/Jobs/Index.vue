<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    jobs: Array,
});
</script>

<template>
    <Head title="Browse Jobs" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Browse Available Jobs</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                    <div v-if="jobs.length === 0" class="col-span-full text-center py-12 bg-white rounded-lg shadow-sm text-gray-500">
                        No jobs are currently available. Check back later!
                    </div>

                    <!-- كارت الوظيفة -->
                    <div v-for="job in jobs" :key="job.id" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border hover:border-indigo-500 transition duration-150">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 truncate">{{ job.title }}</h3>
                                <p class="text-sm text-indigo-600 font-semibold">{{ job.employer?.company_name || 'Unknown Company' }}</p>
                            </div>
                            <span class="px-2 py-1 bg-gray-100 text-xs text-gray-600 rounded capitalize">{{ job.type }}</span>
                        </div>

                        <div class="space-y-2 mb-4 text-sm text-gray-600">
                            <p class="flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>{{ job.location }}</p>
                            <p class="flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>Deadline: {{ new Date(job.deadline).toLocaleDateString() }}</p>
                            <p v-if="job.salary_range" class="flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ job.salary_range }}</p>
                        </div>

                        <Link :href="route('candidate.jobs.show', job.id)" class="block w-full text-center px-4 py-2 bg-indigo-50 text-indigo-700 rounded hover:bg-indigo-100 transition font-medium">
                            View Details
                        </Link>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
