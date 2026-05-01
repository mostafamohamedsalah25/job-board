<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Badge from '@/Components/UI/Badge.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    jobs: Array,
});

const getStatusType = (status) => {
    if (status === 'pending') return 'warning';
    if (status === 'approved') return 'success';
    if (status === 'rejected') return 'danger';
    return 'default';
};
</script>

<template>
    <Head title="My Listings" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center w-full">
                <div>
                    <h2 class="font-display text-2xl font-bold text-primary">My Listings</h2>
                    <p class="text-sm text-on-surface-variant mt-1">Manage your posted jobs and track applications.</p>
                </div>
                <Link :href="route('employer.jobs.create')" class="bg-secondary text-white px-6 py-2.5 rounded-lg font-semibold shadow-sm hover:opacity-90 active:scale-95 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">add_circle</span>
                    Post New Job
                </Link>
            </div>
        </template>

        <div class="py-6 mt-6">
            <div v-if="$page.props.flash && $page.props.flash.success" class="mb-6 px-4 py-3 bg-green-50 text-green-700 rounded-lg border border-green-100 flex items-center gap-2 font-medium shadow-sm">
                <span class="material-symbols-outlined">check_circle</span>
                {{ $page.props.flash.success }}
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-outline overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-outline-variant text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Job Title</th>
                                <th class="px-6 py-4">Category</th>
                                <th class="px-6 py-4">Type / Location</th>
                                <th class="px-6 py-4">Deadline</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            <tr v-if="jobs.length === 0">
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500 font-medium">
                                    You haven't posted any jobs yet.
                                </td>
                            </tr>
                            <tr v-for="job in jobs" :key="job.id" class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-5">
                                    <div class="font-bold text-primary text-base">{{ job.title }}</div>
                                    <div class="text-xs text-slate-500 mt-1 font-semibold text-secondary">
                                        {{ job.applications_count || 0 }} Applications
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-slate-600 font-medium">
                                    {{ job.category ? job.category.name : 'N/A' }}
                                </td>
                                <td class="px-6 py-5">
                                    <span class="capitalize font-semibold text-slate-700">{{ job.type }}</span><br>
                                    <span class="text-xs text-slate-400">{{ job.location }}</span>
                                </td>
                                <td class="px-6 py-5 text-slate-600 font-medium">
                                    {{ new Date(job.deadline).toLocaleDateString() }}
                                </td>
                                <td class="px-6 py-5">
                                    <Badge :type="getStatusType(job.status)" class="capitalize">
                                        {{ job.status }}
                                    </Badge>
                                </td>
                                <td class="px-6 py-5 text-right space-x-2">
                                    <Link :href="route('employer.jobs.show', job.id)" class="inline-flex p-1.5 text-slate-400 hover:text-blue-600 transition-colors border border-transparent hover:border-slate-200 rounded hover:shadow-sm" title="View Applications">
                                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                                    </Link>
                                    <Link :href="route('employer.jobs.edit', job.id)" class="inline-flex p-1.5 text-slate-400 hover:text-orange-600 transition-colors border border-transparent hover:border-slate-200 rounded hover:shadow-sm" title="Edit">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </Link>
                                    <Link :href="route('employer.jobs.destroy', job.id)" method="delete" as="button" type="button" class="inline-flex p-1.5 text-slate-400 hover:text-red-600 transition-colors border border-transparent hover:border-slate-200 rounded hover:shadow-sm" title="Delete" onclick="return confirm('Are you sure you want to delete this job?')">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
