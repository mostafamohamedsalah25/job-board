<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    jobs: Array,
});

// دالة بسيطة لتنسيق شكل الحالة (Status) بالألوان
const statusClasses = (status) => {
    return {
        'bg-yellow-100 text-yellow-800': status === 'pending',
        'bg-green-100 text-green-800': status === 'approved',
        'bg-red-100 text-red-800': status === 'rejected',
    };
};
</script>

<template>
    <Head title="My Jobs" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Job Postings</h2>
                <Link :href="route('employer.jobs.create')" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                    + Post New Job
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- رسالة النجاح (Flash Message) إذا تمت إضافة وظيفة بنجاح -->
                <div v-if="$page.props.flash && $page.props.flash.success" class="mb-4 px-4 py-3 bg-green-100 text-green-700 rounded border border-green-200">
                    {{ $page.props.flash.success }}
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Job Title</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type / Location</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deadline</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <!-- عرض رسالة إذا لم يكن هناك وظائف -->
                                <tr v-if="jobs.length === 0">
                                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        You haven't posted any jobs yet.
                                    </td>
                                </tr>

                                <!-- حلقة التكرار (Loop) لعرض الوظائف -->
                                <tr v-for="job in jobs" :key="job.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900">{{ job.title }}</div>
                                        <div class="text-sm text-gray-500">{{ job.applications_count || 0 }} Applications</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ job.category ? job.category.name : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="capitalize">{{ job.type }}</span><br>
                                        <span class="text-xs text-gray-400">{{ job.location }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ new Date(job.deadline).toLocaleDateString() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="['px-2 inline-flex text-xs leading-5 font-semibold rounded-full capitalize', statusClasses(job.status)]">
                                            {{ job.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <Link :href="route('employer.jobs.show', job.id)" class="text-blue-600 hover:text-blue-900 mr-3 font-bold">
                                            View
                                        </Link>
                                        <Link :href="route('employer.jobs.edit', job.id)" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</Link>

                                        <Link :href="route('employer.jobs.destroy', job.id)" method="delete" as="button" type="button" class="text-red-600 hover:text-red-900" preserve-scroll onclick="return confirm('Are you sure you want to delete this job?')">
                                            Delete
                                        </Link>
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
