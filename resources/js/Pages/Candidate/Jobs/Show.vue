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

        <!-- قسم التعليقات -->
        <div class="mt-12 border-t pt-8 max-w-4xl mx-auto sm:px-6 lg:px-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Questions & Comments</h3>

            <!-- فورم إضافة تعليق -->
            <div class="bg-gray-50 p-4 rounded-lg mb-8 border">
                <form @submit.prevent="router.post(route('comments.store', job.id), { body: newComment }, { preserveScroll: true, onSuccess: () => newComment = '' })">
                    <textarea v-model="newComment" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ask a question about this job..."></textarea>
                    <div class="mt-2 flex justify-end">
                        <button type="submit" :disabled="!newComment" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 disabled:opacity-50">Post Comment</button>
                    </div>
                </form>
            </div>

            <!-- عرض التعليقات -->
            <div class="space-y-6">
                <div v-if="!job.comments || job.comments.length === 0" class="text-gray-500 italic text-sm">
                    No comments yet. Be the first to ask!
                </div>

                <div v-for="comment in job.comments" :key="comment.id" class="bg-white p-4 rounded-lg border shadow-sm">
                    <!-- إذا كان التعليق مخفياً والمستخدم الحالي ليس مديراً -->
                    <div v-if="comment.is_hidden && $page.props.auth.user.role !== 'admin'" class="text-gray-400 italic text-sm">
                        This comment has been hidden by a moderator.
                    </div>

                    <div v-else>
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <span class="font-bold text-gray-900 text-sm">{{ comment.user.name }}</span>
                                <span class="text-gray-500 text-xs ml-2">{{ new Date(comment.created_at).toLocaleDateString() }}</span>
                            </div>

                            <!-- زر الإخفاء/الإظهار (يظهر للمدير فقط) -->
                            <button v-if="$page.props.auth.user.role === 'admin'"
                                    @click="router.patch(route('admin.comments.toggle', comment.id), {}, { preserveScroll: true })"
                                    class="text-xs font-bold px-2 py-1 rounded"
                                    :class="comment.is_hidden ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'">
                                {{ comment.is_hidden ? 'Unhide' : 'Hide' }}
                            </button>
                        </div>

                        <p class="text-gray-700 text-sm" :class="{'opacity-50 line-through': comment.is_hidden}">
                            {{ comment.body }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
