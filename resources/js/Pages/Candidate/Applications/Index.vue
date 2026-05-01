<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Badge from '@/Components/UI/Badge.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    applications: Array,
});

const getStatusType = (status) => {
    if (status === 'pending') return 'warning';
    if (status === 'accepted') return 'success';
    if (status === 'rejected') return 'danger';
    return 'default';
};
</script>

<template>
    <Head title="My Applications" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2 class="font-display text-2xl font-bold text-primary">My Applications</h2>
                <p class="text-sm text-on-surface-variant mt-1">Track the status of your job applications.</p>
            </div>
        </template>

        <div class="mt-6">
            <div v-if="$page.props.flash && $page.props.flash.success" class="mb-6 px-4 py-3 bg-green-50 text-green-700 rounded-lg border border-green-100 flex items-center gap-2 font-medium shadow-sm">
                <span class="material-symbols-outlined">check_circle</span>
                {{ $page.props.flash.success }}
            </div>
            <div v-if="$page.props.flash && $page.props.flash.error" class="mb-6 px-4 py-3 bg-red-50 text-red-700 rounded-lg border border-red-100 flex items-center gap-2 font-medium shadow-sm">
                <span class="material-symbols-outlined">error</span>
                {{ $page.props.flash.error }}
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-outline overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-outline-variant text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Job Title</th>
                                <th class="px-6 py-4">Company</th>
                                <th class="px-6 py-4">Applied On</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            <tr v-if="applications.length === 0">
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                    <span class="material-symbols-outlined text-4xl mb-2 text-slate-300">work_history</span>
                                    <p class="font-medium">You haven't applied to any jobs yet.</p>
                                    <Link :href="route('candidate.jobs.index')" class="text-secondary hover:underline font-bold mt-2 inline-block">Browse jobs now</Link>
                                </td>
                            </tr>

                            <tr v-for="application in applications" :key="application.id" class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-primary">
                                    <Link :href="route('candidate.jobs.show', application.job_posting_id)" class="hover:text-secondary transition-colors">
                                        {{ application.job_posting?.title }}
                                    </Link>
                                </td>
                                <td class="px-6 py-4 text-slate-600 font-medium">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[16px] text-slate-400">business</span>
                                        {{ application.job_posting?.employer?.company_name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-500 font-medium">
                                    {{ new Date(application.created_at).toLocaleDateString() }}
                                </td>
                                <td class="px-6 py-4">
                                    <Badge :type="getStatusType(application.status)" class="capitalize">
                                        {{ application.status }}
                                    </Badge>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <Link v-if="application.status === 'pending'"
                                          :href="route('candidate.applications.destroy', application.id)"
                                          method="delete" as="button" type="button"
                                          class="text-xs font-bold text-red-500 hover:text-red-700 bg-red-50 px-3 py-1.5 rounded-lg transition-colors border border-transparent hover:border-red-200"
                                          preserve-scroll
                                          onclick="return confirm('Are you sure you want to withdraw this application?')">
                                        Withdraw
                                    </Link>
                                    <span v-else class="text-slate-400 text-xs italic font-medium bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">Reviewed</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
