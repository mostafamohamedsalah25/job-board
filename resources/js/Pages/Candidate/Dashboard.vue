<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatCard from '@/Components/UI/StatCard.vue';
import Badge from '@/Components/UI/Badge.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    stats: Object,
    recentApplications: Array,
    profileCompletion: Number
});

const getStatusType = (status) => {
    if (status === 'pending') return 'warning';
    if (status === 'accepted') return 'success';
    if (status === 'rejected') return 'danger';
    return 'default';
};
</script>

<template>
    <Head title="Candidate Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center w-full">
                <div>
                    <h2 class="font-display text-2xl font-bold text-primary">Welcome back, {{ $page.props.auth.user.name }}!</h2>
                    <p class="text-sm text-on-surface-variant mt-1">Here is what's happening with your job search.</p>
                </div>
                <Link :href="route('candidate.jobs.index')" class="bg-secondary text-white px-6 py-2.5 rounded-lg font-semibold shadow-sm hover:opacity-90 active:scale-95 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">search</span>
                    Find New Jobs
                </Link>
            </div>
        </template>

        <div class="space-y-8 mt-6">

            <!-- شريط اكتمال البروفايل -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-outline">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-semibold text-primary">Profile Completion</h3>
                    <span class="text-secondary font-bold">{{ profileCompletion }}%</span>
                </div>
                <div class="w-full bg-surface-container rounded-full h-2.5 mb-3">
                    <div class="bg-secondary h-2.5 rounded-full transition-all duration-500" :style="`width: ${profileCompletion}%`"></div>
                </div>
                <p v-if="profileCompletion < 100" class="text-xs text-on-surface-variant">
                    Complete your profile by adding your resume and LinkedIn to get noticed 2x faster.
                    <Link :href="route('candidate.profile.edit')" class="text-primary hover:underline font-semibold ml-1">Edit Profile</Link>
                </p>
            </div>

            <!-- Stats Bento Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <StatCard
                    title="Applications Sent"
                    :value="stats.total"
                    icon="send"
                    iconBg="bg-blue-50 text-blue-600"
                />
                <StatCard
                    title="Accepted (Interviews)"
                    :value="stats.accepted"
                    icon="event"
                    iconBg="bg-green-50 text-green-600"
                />
                <StatCard
                    title="Pending Reviews"
                    :value="stats.pending"
                    icon="hourglass_empty"
                    iconBg="bg-orange-50 text-orange-600"
                />
                <StatCard
                    title="Rejected"
                    :value="stats.rejected"
                    icon="cancel"
                    iconBg="bg-red-50 text-red-600"
                />
            </div>

            <!-- Recent Applications Table -->
            <div class="bg-white rounded-xl shadow-sm border border-outline overflow-hidden">
                <div class="px-6 py-5 border-b border-outline flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-display text-lg font-bold text-primary">Recent Applications</h3>
                    <Link :href="route('candidate.applications.index')" class="text-secondary font-semibold text-sm hover:underline">View All</Link>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-bold border-b border-outline-variant">
                            <tr>
                                <th class="px-6 py-4">Job Title</th>
                                <th class="px-6 py-4">Company</th>
                                <th class="px-6 py-4">Date Applied</th>
                                <th class="px-6 py-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            <tr v-if="recentApplications.length === 0">
                                <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                    You haven't applied to any jobs yet.
                                </td>
                            </tr>
                            <tr v-for="app in recentApplications" :key="app.id" class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-primary">
                                    <Link :href="route('candidate.jobs.show', app.job_posting_id)" class="hover:text-secondary transition-colors">
                                        {{ app.job_posting?.title }}
                                    </Link>
                                </td>
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <div class="w-8 h-8 rounded bg-primary-container text-white flex items-center justify-center font-bold text-xs uppercase">
                                        {{ app.job_posting?.employer?.company_name.charAt(0) }}
                                    </div>
                                    <span class="font-medium text-slate-700">{{ app.job_posting?.employer?.company_name }}</span>
                                </td>
                                <td class="px-6 py-4 text-on-surface-variant font-medium">
                                    {{ new Date(app.created_at).toLocaleDateString() }}
                                </td>
                                <td class="px-6 py-4">
                                    <Badge :type="getStatusType(app.status)" class="capitalize">{{ app.status }}</Badge>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>
