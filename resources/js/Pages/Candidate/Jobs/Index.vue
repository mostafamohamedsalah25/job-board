<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Badge from '@/Components/UI/Badge.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive, watch } from 'vue';
import debounce from 'lodash/debounce';

const props = defineProps({
    jobs: Object,
    filters: Object,
    categories: Array,
});

const searchForm = reactive({
    search: props.filters?.search || '',
    location: props.filters?.location || '',
    type: props.filters?.type || '',
    category_id: props.filters?.category_id || '',
});

watch(
    searchForm,
    debounce((value) => {
        router.get(
            route('candidate.jobs.index'),
            value,
            { preserveState: true, preserveScroll: true, replace: true }
        );
    }, 300)
);
</script>

<template>
    <Head title="Browse Jobs" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center w-full gap-4">
                <div>
                    <h1 class="font-display text-2xl font-bold text-primary">Find Your Next Role</h1>
                    <p class="font-body-sm text-on-surface-variant mt-1">Showing {{ jobs.total || 0 }} available opportunities</p>
                </div>
            </div>
        </template>

        <div class="flex flex-col lg:flex-row gap-8 mt-6">

            <!-- Sidebar Filters -->
            <aside class="w-full lg:w-64 shrink-0 space-y-8 bg-white p-6 rounded-xl border border-outline shadow-sm h-fit">
                <div class="flex items-center justify-between">
                    <h2 class="font-display text-lg font-bold text-primary">Filters</h2>
                    <Link :href="route('candidate.jobs.index')" class="text-xs font-semibold text-secondary hover:underline">Clear</Link>
                </div>

                <div class="space-y-6">
                    <!-- Keywords -->
                    <div>
                        <label class="font-semibold text-sm text-primary mb-2 block">Keywords</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
                            <input type="text" v-model="searchForm.search" placeholder="Job title, skill..." class="w-full pl-10 pr-3 py-2 bg-slate-50 border border-outline-variant rounded-lg text-sm focus:border-secondary focus:ring-1 focus:ring-secondary transition-colors">
                        </div>
                    </div>

                    <!-- Location -->
                    <div>
                        <label class="font-semibold text-sm text-primary mb-2 block">Location</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">location_on</span>
                            <input type="text" v-model="searchForm.location" placeholder="City or country" class="w-full pl-10 pr-3 py-2 bg-slate-50 border border-outline-variant rounded-lg text-sm focus:border-secondary focus:ring-1 focus:ring-secondary transition-colors">
                        </div>
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="font-semibold text-sm text-primary mb-2 block">Category</label>
                        <select v-model="searchForm.category_id" class="w-full px-3 py-2 bg-slate-50 border border-outline-variant rounded-lg text-sm focus:border-secondary focus:ring-1 focus:ring-secondary transition-colors">
                            <option value="">All Categories</option>
                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Work Type -->
                    <div>
                        <label class="font-semibold text-sm text-primary mb-2 block">Work Type</label>
                        <select v-model="searchForm.type" class="w-full px-3 py-2 bg-slate-50 border border-outline-variant rounded-lg text-sm focus:border-secondary focus:ring-1 focus:ring-secondary transition-colors">
                            <option value="">All Types</option>
                            <option value="on-site">On-site</option>
                            <option value="remote">Remote</option>
                            <option value="hybrid">Hybrid</option>
                        </select>
                    </div>
                </div>
            </aside>

            <!-- Main Content (Job Listings) -->
            <section class="flex-1 space-y-4">

                <div v-if="jobs.data.length === 0" class="bg-white p-12 rounded-xl border border-outline shadow-sm text-center">
                    <span class="material-symbols-outlined text-4xl text-slate-300 mb-3">search_off</span>
                    <h3 class="font-display text-xl font-bold text-primary">No jobs found</h3>
                    <p class="text-slate-500 mt-2">Try adjusting your filters or search keywords.</p>
                </div>

                <!-- Job Card (Horizontal Layout) -->
                <div v-for="job in jobs.data" :key="job.id" class="bg-white p-6 rounded-xl border border-outline shadow-sm hover:shadow-md transition-shadow flex flex-col md:flex-row gap-6">
                    <div class="w-14 h-14 rounded-lg bg-primary-container text-white flex items-center justify-center shrink-0 font-bold text-xl uppercase shadow-inner">
                        {{ job.employer?.company_name.charAt(0) }}
                    </div>

                    <div class="flex-1">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                            <h3 class="font-display text-lg font-bold text-primary">{{ job.title }}</h3>
                            <span v-if="job.salary_range" class="font-bold text-sm text-secondary">{{ job.salary_range }}</span>
                        </div>

                        <div class="flex flex-wrap gap-x-6 gap-y-2 mb-4">
                            <div class="flex items-center gap-1.5 text-on-surface-variant text-sm font-medium">
                                <span class="material-symbols-outlined text-[18px]">business</span>
                                {{ job.employer?.company_name }}
                            </div>
                            <div class="flex items-center gap-1.5 text-on-surface-variant text-sm font-medium">
                                <span class="material-symbols-outlined text-[18px]">location_on</span>
                                {{ job.location }}
                            </div>
                            <div class="flex items-center gap-1.5 text-on-surface-variant text-sm font-medium">
                                <span class="material-symbols-outlined text-[18px]">schedule</span>
                                Deadline: {{ new Date(job.deadline).toLocaleDateString() }}
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <Badge :type="job.type === 'remote' ? 'success' : (job.type === 'hybrid' ? 'warning' : 'info')" class="capitalize">{{ job.type }}</Badge>
                            <Badge type="default">{{ job.category?.name || 'Uncategorized' }}</Badge>
                        </div>
                    </div>

                    <div class="flex items-end mt-4 md:mt-0">
                        <Link :href="route('candidate.jobs.show', job.id)" class="w-full md:w-auto bg-secondary text-white px-6 py-2.5 rounded-lg font-semibold hover:opacity-90 active:scale-95 transition-all text-center">
                            View Details
                        </Link>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="jobs.links && jobs.data.length > 0" class="flex justify-center pt-8">
                    <div class="flex flex-wrap gap-1">
                        <template v-for="(link, index) in jobs.links" :key="index">
                            <div v-if="link.url === null" class="px-4 py-2 border border-outline-variant rounded-lg text-slate-400 bg-white text-sm" v-html="link.label"></div>
                            <Link v-else :href="link.url" class="px-4 py-2 border rounded-lg text-sm transition-colors font-medium" :class="{ 'bg-secondary text-white border-secondary': link.active, 'bg-white border-outline-variant text-slate-600 hover:bg-slate-50': !link.active }" v-html="link.label"></Link>
                        </template>
                    </div>
                </div>

            </section>
        </div>
    </AuthenticatedLayout>
</template>
