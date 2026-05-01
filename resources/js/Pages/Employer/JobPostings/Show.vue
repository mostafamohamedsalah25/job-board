<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Badge from '@/Components/UI/Badge.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    job: Object,
});

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
};

const getStatusType = (status) => {
    if (status === 'pending') return 'warning';
    if (status === 'approved') return 'success';
    if (status === 'rejected') return 'danger';
    return 'default';
};
</script>

<template>
    <Head :title="job.title" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center w-full gap-4">
                <div>
                    <h2 class="font-display text-2xl font-bold text-primary">Job Details: {{ job.title }}</h2>
                    <p class="text-sm text-on-surface-variant mt-1">Review your job posting and manage candidates.</p>
                </div>
                <div class="flex gap-3">
                    <Link :href="route('employer.jobs.index')" class="px-6 py-2.5 bg-white border border-outline-variant text-on-surface-variant font-bold rounded-lg hover:bg-slate-50 transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back
                    </Link>
                    <Link :href="route('employer.jobs.edit', job.id)" class="px-6 py-2.5 bg-secondary text-white font-bold rounded-lg hover:opacity-90 active:scale-95 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">edit</span> Edit Job
                    </Link>
                </div>
            </div>
        </template>

        <div class="mt-8">
            <!-- Header Section -->
            <div class="bg-white p-8 rounded-xl border border-outline shadow-sm mb-8">
                <div class="flex flex-wrap gap-6 text-sm font-medium text-on-surface-variant">
                    <span class="flex items-center gap-2 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                        <span class="material-symbols-outlined text-primary text-[18px]">category</span>
                        {{ job.category ? job.category.name : 'Uncategorized' }}
                    </span>
                    <span class="flex items-center gap-2 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100 capitalize">
                        <span class="material-symbols-outlined text-primary text-[18px]">work</span>
                        {{ job.type }}
                    </span>
                    <span class="flex items-center gap-2 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                        <span class="material-symbols-outlined text-primary text-[18px]">location_on</span>
                        {{ job.location }}
                    </span>
                    <span class="flex items-center gap-2 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                        <span class="material-symbols-outlined text-primary text-[18px]">schedule</span>
                        Deadline: {{ formatDate(job.deadline) }}
                    </span>
                    <Badge :type="getStatusType(job.status)" class="capitalize ml-auto flex items-center">{{ job.status }}</Badge>
                </div>

                <div class="mt-6 pt-6 border-t border-outline-variant">
                    <h4 class="font-display text-lg font-bold text-primary mb-3">Job Description</h4>
                    <div class="text-on-surface-variant whitespace-pre-wrap leading-relaxed text-sm">
                        {{ job.description }}
                    </div>
                </div>
            </div>

            <!-- Applications Table -->
            <div class="mb-6 flex justify-between items-center">
                <h3 class="font-display text-xl font-bold text-primary flex items-center gap-2">
                    <span class="material-symbols-outlined">group</span>
                    Candidates Applications ({{ job.applications_count || job.applications?.length || 0 }})
                </h3>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-outline">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-outline-variant text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Candidate</th>
                                <th class="px-6 py-4">Bio / Profile</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            <tr v-if="!job.applications || job.applications.length === 0">
                                <td colspan="4" class="px-6 py-12 text-center text-slate-500 font-medium">
                                    <span class="material-symbols-outlined text-4xl mb-2 text-slate-300">inbox</span>
                                    <p>No candidates have applied to this job yet.</p>
                                </td>
                            </tr>

                            <tr v-for="application in job.applications" :key="application.id" class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-primary text-base">{{ application.candidate.name }}</div>
                                    <div class="text-xs text-slate-500 mt-1 font-medium">
                                        {{ application.is_paid ? application.candidate.email : 'Email Hidden (Pay to View)' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="truncate w-48 text-on-surface-variant mb-1" :title="application.candidate.profile?.bio">
                                        {{ application.candidate.profile?.bio || 'No bio provided' }}
                                    </p>
                                    <a v-if="application.candidate.profile?.linkedin_id && application.candidate.profile.linkedin_id.startsWith('http')"
                                       :href="application.candidate.profile.linkedin_id"
                                       target="_blank"
                                       class="text-blue-600 hover:underline text-xs font-bold flex items-center gap-1 w-fit">
                                        <span class="material-symbols-outlined text-[14px]">link</span> LinkedIn Profile
                                    </a>
                                </td>
                                <td class="px-6 py-4 flex flex-col gap-2 items-start">
                                    <Badge :type="getStatusType(application.status)" class="capitalize">
                                        {{ application.status }}
                                    </Badge>
                                    <Badge v-if="application.is_paid" type="info" class="!bg-blue-100 !text-blue-800">
                                        Paid & Unlocked
                                    </Badge>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <!-- Pending Actions -->
                                    <template v-if="application.status === 'pending'">
                                        <Link :href="route('employer.applications.status', { id: application.id })" method="patch" :data="{ status: 'accepted' }" as="button" class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-700 font-bold rounded hover:bg-green-100 border border-green-200 transition-colors text-xs">
                                            Accept
                                        </Link>
                                        <Link :href="route('employer.applications.status', { id: application.id })" method="patch" :data="{ status: 'rejected' }" as="button" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 font-bold rounded hover:bg-red-100 border border-red-200 transition-colors text-xs">
                                            Reject
                                        </Link>
                                    </template>

                                    <!-- Accepted but Unpaid Action -->
                                    <template v-if="application.status === 'accepted' && !application.is_paid">
                                        <Link :href="route('employer.payments.checkout', application.id)" class="inline-flex items-center px-4 py-2 bg-primary text-white font-bold rounded-lg hover:bg-slate-800 transition-colors shadow-sm">
                                            <span class="material-symbols-outlined text-[16px] mr-1">lock_open</span> Unlock Info
                                        </Link>
                                    </template>

                                    <!-- Paid Action -->
                                    <template v-if="application.is_paid">
                                        <a :href="'mailto:' + application.candidate.email" class="inline-flex items-center px-4 py-2 bg-secondary text-white font-bold rounded-lg hover:opacity-90 transition-colors shadow-sm">
                                            <span class="material-symbols-outlined text-[16px] mr-1">mail</span> Contact
                                        </a>
                                    </template>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
