<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive, watch } from 'vue';
import debounce from 'lodash/debounce';

const props = defineProps({
    jobs: Object, // أصبح Object بسبب الـ Pagination
    filters: Object,
    categories: Array,
});

// تجهيز متغيرات الفلترة بقيمها القادمة من الـ URL (إن وجدت)
const searchForm = reactive({
    search: props.filters?.search || '',
    location: props.filters?.location || '',
    type: props.filters?.type || '',
    category_id: props.filters?.category_id || '',
});

// مراقبة أي تغيير في الفلاتر لإرسال طلب تلقائي
watch(
    searchForm,
    debounce((value) => {
        router.get(
            route('candidate.jobs.index'),
            value,
            {
                preserveState: true, // الحفاظ على حالة الصفحة (لا يتم مسح ما يكتبه المستخدم)
                preserveScroll: true, // عدم رفع الشاشة لأعلى بعد جلب النتائج
                replace: true, // استبدال الرابط في الـ History لتجنب زحمة الـ Back button
            }
        );
    }, 300) // تأخير 300 ملي ثانية حتى ينتهي المستخدم من الكتابة
);
</script>

<template>
    <Head title="Browse Jobs" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Browse Available Jobs</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <!-- 1. شريط البحث والفلاتر (Live Filters) -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                        <!-- Search Keyword -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Keywords</label>
                            <input id="search" v-model="searchForm.search" type="text" placeholder="Job title or skill..." class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        </div>

                        <!-- Location -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                            <input id="location" v-model="searchForm.location" type="text" placeholder="City or country..." class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select id="category" v-model="searchForm.category_id" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">All Categories</option>
                                <option v-for="category in categories" :key="category.id" :value="category.id">
                                    {{ category.name }}
                                </option>
                            </select>
                        </div>

                        <!-- Work Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Work Type</label>
                            <select id="type" v-model="searchForm.type" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">All Types</option>
                                <option value="on-site">On-site</option>
                                <option value="remote">Remote</option>
                                <option value="hybrid">Hybrid</option>
                            </select>
                        </div>

                    </div>
                </div>

                <!-- 2. قائمة الوظائف (نستخدم jobs.data بسبب الـ Pagination) -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                    <div v-if="jobs.data.length === 0" class="col-span-full text-center py-12 bg-white rounded-lg shadow-sm text-gray-500">
                        No jobs match your search criteria. Try adjusting your filters.
                    </div>

                    <!-- كارت الوظيفة -->
                    <div v-for="job in jobs.data" :key="job.id" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border hover:border-indigo-500 transition duration-150 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 line-clamp-1" :title="job.title">{{ job.title }}</h3>
                                    <p class="text-sm text-indigo-600 font-semibold">{{ job.employer?.company_name || 'Unknown Company' }}</p>
                                </div>
                                <span class="px-2 py-1 bg-gray-100 text-xs text-gray-600 rounded capitalize">{{ job.type }}</span>
                            </div>

                            <div class="space-y-2 mb-4 text-sm text-gray-600">
                                <p class="flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>{{ job.location }}</p>
                                <p class="flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>Deadline: {{ new Date(job.deadline).toLocaleDateString() }}</p>
                                <p v-if="job.salary_range" class="flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ job.salary_range }}</p>
                            </div>
                        </div>

                        <Link :href="route('candidate.jobs.show', job.id)" class="block w-full text-center px-4 py-2 bg-indigo-50 text-indigo-700 rounded hover:bg-indigo-100 transition font-medium mt-4">
                            View Details
                        </Link>
                    </div>
                </div>

                <!-- 3. التصفح (Pagination Links) -->
                <div v-if="jobs.links && jobs.data.length > 0" class="mt-8 flex justify-center">
                    <div class="flex flex-wrap gap-1">
                        <template v-for="(link, index) in jobs.links" :key="index">
                            <div v-if="link.url === null" class="px-4 py-2 border rounded text-gray-400 bg-gray-50 text-sm" v-html="link.label"></div>
                            <Link v-else :href="link.url" class="px-4 py-2 border rounded hover:bg-gray-100 text-sm" :class="{ 'bg-indigo-600 text-white hover:bg-indigo-700 border-indigo-600': link.active }" v-html="link.label"></Link>
                        </template>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
