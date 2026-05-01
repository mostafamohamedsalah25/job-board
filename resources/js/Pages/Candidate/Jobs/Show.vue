<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Badge from '@/Components/UI/Badge.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    job: Object,
    hasApplied: Boolean,
});

const newComment = ref('');
</script>

<template>
    <Head :title="job.title" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-4">
                <Link :href="route('candidate.jobs.index')" class="hover:text-primary transition-colors flex items-center">
                    <span class="material-symbols-outlined text-[18px] mr-1">arrow_back</span>
                    Back to Jobs
                </Link>
            </div>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-start gap-6">
                    <div class="w-20 h-20 rounded-xl bg-white flex items-center justify-center border border-outline shadow-sm font-display text-3xl font-bold text-primary-container">
                        {{ job.employer?.company_name.charAt(0) }}
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-3 mb-2">
                            <h1 class="font-display text-3xl font-bold text-primary">{{ job.title }}</h1>
                        </div>
                        <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-on-surface-variant text-sm font-medium">
                            <div class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[18px]">business</span> {{ job.employer?.company_name }}</div>
                            <div class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[18px]">location_on</span> {{ job.location }}</div>
                            <div v-if="job.salary_range" class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[18px]">payments</span> {{ job.salary_range }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <div class="mt-6 grid grid-cols-1 lg:grid-cols-12 gap-8 relative items-start">

            <!-- Left Column: Content -->
            <div class="lg:col-span-8 space-y-10">

                <!-- رسائل النظام -->
                <div v-if="$page.props.flash && $page.props.flash.success" class="px-4 py-3 bg-green-50 text-green-700 rounded-lg border border-green-100 flex items-center gap-2 font-medium shadow-sm">
                    <span class="material-symbols-outlined">check_circle</span>
                    {{ $page.props.flash.success }}
                </div>
                <div v-if="$page.props.flash && $page.props.flash.error" class="px-4 py-3 bg-red-50 text-red-700 rounded-lg border border-red-100 flex items-center gap-2 font-medium shadow-sm">
                    <span class="material-symbols-outlined">error</span>
                    <div>
                        {{ $page.props.flash.error }}
                        <Link :href="route('candidate.profile.edit')" class="ml-1 underline font-bold">Update Profile</Link>
                    </div>
                </div>

                <!-- الوصف (Description) -->
                <section class="bg-white p-8 rounded-xl border border-outline shadow-sm">
                    <h2 class="font-display text-xl font-bold text-primary mb-4">Job Description & Requirements</h2>
                    <div class="text-on-surface-variant leading-relaxed whitespace-pre-wrap">
                        {{ job.description }}
                    </div>
                </section>

                <!-- قسم التعليقات -->
                <section class="bg-white p-8 rounded-xl border border-outline shadow-sm">
                    <h2 class="font-display text-xl font-bold text-primary mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined">forum</span>
                        Questions & Comments
                    </h2>

                    <form @submit.prevent="router.post(route('comments.store', job.id), { body: newComment }, { preserveScroll: true, onSuccess: () => newComment = '' })" class="mb-8 relative">
                        <textarea v-model="newComment" rows="3" class="w-full bg-slate-50 border-outline-variant rounded-lg shadow-sm focus:border-secondary focus:ring-secondary text-sm p-4" placeholder="Ask the employer a question..."></textarea>
                        <div class="mt-3 flex justify-end">
                            <button type="submit" :disabled="!newComment" class="bg-primary-container text-white px-6 py-2 rounded-lg font-semibold hover:bg-primary transition-colors disabled:opacity-50">Post Comment</button>
                        </div>
                    </form>

                    <div class="space-y-4">
                        <div v-if="!job.comments || job.comments.length === 0" class="text-center py-6 text-slate-400 bg-slate-50 rounded-lg border border-dashed border-slate-200">
                            No comments yet. Be the first to ask!
                        </div>

                        <div v-for="comment in job.comments" :key="comment.id" class="p-4 rounded-lg bg-slate-50 border border-slate-100">
                            <div v-if="comment.is_hidden && $page.props.auth.user.role !== 'admin'" class="text-slate-400 italic text-sm">
                                This comment was hidden by a moderator.
                            </div>
                            <div v-else>
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center font-bold text-slate-600 text-xs">{{ comment.user.name.charAt(0) }}</div>
                                        <div>
                                            <span class="font-bold text-primary text-sm block">{{ comment.user.name }}</span>
                                            <span class="text-slate-400 text-xs">{{ new Date(comment.created_at).toLocaleDateString() }}</span>
                                        </div>
                                    </div>
                                    <button v-if="$page.props.auth.user.role === 'admin'" @click="router.patch(route('admin.comments.toggle', comment.id), {}, { preserveScroll: true })" :class="['text-xs font-bold px-2 py-1 rounded', comment.is_hidden ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700']">
                                        {{ comment.is_hidden ? 'Unhide' : 'Hide' }}
                                    </button>
                                </div>
                                <p class="text-on-surface-variant text-sm mt-2 ml-10" :class="{'opacity-50 line-through': comment.is_hidden}">
                                    {{ comment.body }}
                                </p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Right Column: Sticky Sidebar -->
            <aside class="lg:col-span-4 sticky top-28 space-y-6">
                <div class="bg-white rounded-xl border border-outline shadow-sm p-6">

                    <div class="mb-6 space-y-3">
                        <div class="flex items-center gap-2 text-error text-sm font-semibold mb-4 bg-red-50 p-2 rounded-lg justify-center border border-red-100">
                            <span class="material-symbols-outlined text-[18px]">schedule</span>
                            Deadline: {{ new Date(job.deadline).toLocaleDateString() }}
                        </div>

                        <button v-if="hasApplied" disabled class="w-full bg-slate-100 text-slate-400 border border-slate-200 font-bold py-3.5 rounded-lg cursor-not-allowed flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">check_circle</span>
                            Applied Successfully
                        </button>

                        <Link v-else :href="route('candidate.jobs.apply', job.id)" method="post" as="button" class="w-full bg-secondary text-white font-bold text-lg py-3.5 rounded-lg shadow-md shadow-secondary/20 hover:opacity-90 active:scale-95 transition-all">
                            Apply Now
                        </Link>
                    </div>

                    <hr class="border-outline-variant mb-6" />

                    <div class="space-y-5">
                        <h4 class="font-bold text-primary uppercase tracking-wider text-xs">Job Overview</h4>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded bg-blue-50 text-blue-600 flex items-center justify-center"><span class="material-symbols-outlined">calendar_today</span></div>
                            <div>
                                <p class="text-xs text-slate-500 font-medium">Posted On</p>
                                <p class="text-sm font-bold text-primary">{{ new Date(job.created_at).toLocaleDateString() }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded bg-orange-50 text-orange-600 flex items-center justify-center"><span class="material-symbols-outlined">work</span></div>
                            <div>
                                <p class="text-xs text-slate-500 font-medium">Work Type</p>
                                <p class="text-sm font-bold text-primary capitalize">{{ job.type }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded bg-green-50 text-green-600 flex items-center justify-center"><span class="material-symbols-outlined">category</span></div>
                            <div>
                                <p class="text-xs text-slate-500 font-medium">Category</p>
                                <p class="text-sm font-bold text-primary">{{ job.category?.name || 'Uncategorized' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

        </div>
    </AuthenticatedLayout>
</template>
