<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    job: Object,
    hasApplied: Boolean,
});
</script>

<template>
    <Head :title="job.title" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Job Details</h2>
                <Link :href="route('candidate.jobs.index')" class="text-indigo-600 hover:text-indigo-900">Back to Jobs</Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

                <!-- رسائل النجاح أو الخطأ -->
                <div v-if="$page.props.flash && $page.props.flash.success" class="mb-4 px-4 py-3 bg-green-100 text-green-700 rounded border border-green-200">
                    {{ $page.props.flash.success }}
                </div>
                <div v-if="$page.props.flash && $page.props.flash.error" class="mb-4 px-4 py-3 bg-red-100 text-red-700 rounded border border-red-200">
                    {{ $page.props.flash.error }}
                    <Link :href="route('candidate.profile.edit')" class="ml-2 underline font-bold">Go to Profile</Link>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="border-b pb-6 mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ job.title }}</h3>
                            <p class="text-lg text-indigo-600 mt-1">{{ job.employer?.company_name }}</p>
                        </div>

                        <!-- زر التقديم -->
                        <div>
                            <button v-if="hasApplied" disabled class="px-6 py-2 bg-gray-400 text-white font-bold rounded cursor-not-allowed">
                                Already Applied
                            </button>
                            <!-- نستخدم مكون Link من Inertia مع method="post" لعمل Submit بدون فورم كاملة -->
                            <Link v-else :href="route('candidate.jobs.apply', job.id)" method="post" as="button" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded hover:bg-indigo-700 transition">
                                Apply Now
                            </Link>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2 space-y-4">
                            <h4 class="text-lg font-semibold">Description & Requirements</h4>
                            <div class="text-gray-700 whitespace-pre-wrap">{{ job.description }}</div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg border space-y-4 h-fit">
                            <div>
                                <span class="block text-sm font-medium text-gray-500">Location</span>
                                <span class="text-gray-900">{{ job.location }}</span>
                            </div>
                            <div>
                                <span class="block text-sm font-medium text-gray-500">Work Type</span>
                                <span class="text-gray-900 capitalize">{{ job.type }}</span>
                            </div>
                            <div v-if="job.salary_range">
                                <span class="block text-sm font-medium text-gray-500">Salary Range</span>
                                <span class="text-gray-900">{{ job.salary_range }}</span>
                            </div>
                            <div>
                                <span class="block text-sm font-medium text-gray-500">Deadline</span>
                                <span class="text-gray-900">{{ new Date(job.deadline).toLocaleDateString() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
