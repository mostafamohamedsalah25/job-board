<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Badge from '@/Components/UI/Badge.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    jobs: Object,
});

// دالة لتحويل حالة الوظيفة إلى نوع الـ Badge المناسب
const getStatusType = (status) => {
    if (status === 'pending') return 'warning';
    if (status === 'approved') return 'success';
    if (status === 'rejected') return 'danger';
    return 'default';
};
</script>

<template>
    <Head title="Admin Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2 class="font-display text-2xl font-bold text-primary">Admin Dashboard</h2>
                <p class="text-sm text-on-surface-variant mt-1">Review and manage pending job postings.</p>
            </div>
        </template>

        <div class="space-y-6 mt-6">
            <!-- رسائل النجاح -->
            <div v-if="$page.props.flash && $page.props.flash.success" class="px-4 py-3 bg-green-50 text-green-700 rounded-lg border border-green-100 flex items-center gap-2 font-medium shadow-sm">
                <span class="material-symbols-outlined">check_circle</span>
                {{ $page.props.flash.success }}
            </div>

            <!-- جدول الوظائف -->
            <div class="bg-white rounded-xl shadow-sm border border-outline overflow-hidden">
                <div class="p-6 border-b border-outline flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-display text-lg font-bold text-primary">Pending Jobs Approval</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-outline-variant text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <th class="px-6 py-4">Job / Company</th>
                                <th class="px-6 py-4">Date Posted</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            <tr v-if="jobs.data.length === 0">
                                <td colspan="4" class="px-6 py-12 text-center text-slate-500 font-medium">
                                    No jobs currently require approval.
                                </td>
                            </tr>
                            <tr v-for="job in jobs.data" :key="job.id" class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-primary text-base">{{ job.title }}</div>
                                    <div class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">business</span>
                                        {{ job.employer?.company_name || 'Unknown' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-600 font-medium">
                                    {{ new Date(job.created_at).toLocaleDateString() }}
                                </td>
                                <td class="px-6 py-4">
                                    <Badge :type="getStatusType(job.status)" class="capitalize">
                                        {{ job.status }}
                                    </Badge>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <!-- زر الموافقة (أيقونة) -->
                                        <Link v-if="job.status !== 'approved'" :href="route('admin.jobs.status', job.id)" method="patch" :data="{ status: 'approved' }" as="button" class="p-1.5 text-slate-400 hover:text-green-600 transition-colors bg-white border border-slate-200 rounded shadow-sm hover:shadow" title="Approve">
                                            <span class="material-symbols-outlined text-[20px]">check_circle</span>
                                        </Link>

                                        <!-- زر الرفض (أيقونة) -->
                                        <Link v-if="job.status !== 'rejected'" :href="route('admin.jobs.status', job.id)" method="patch" :data="{ status: 'rejected' }" as="button" class="p-1.5 text-slate-400 hover:text-red-600 transition-colors bg-white border border-slate-200 rounded shadow-sm hover:shadow" title="Reject">
                                            <span class="material-symbols-outlined text-[20px]">cancel</span>
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="jobs.links && jobs.links.length > 3" class="p-4 border-t border-outline flex justify-center bg-slate-50">
                    <div class="flex flex-wrap gap-1">
                        <template v-for="(link, index) in jobs.links" :key="index">
                            <div v-if="link.url === null" class="px-3 py-1 border border-outline-variant rounded text-slate-400 bg-white text-sm" v-html="link.label"></div>
                            <Link v-else :href="link.url" class="px-3 py-1 border rounded text-sm transition-colors" :class="{ 'bg-secondary text-white border-secondary font-bold': link.active, 'bg-white border-outline-variant text-slate-600 hover:bg-slate-50': !link.active }" v-html="link.label"></Link>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
