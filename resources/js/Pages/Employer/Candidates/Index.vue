<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive, watch } from 'vue';
import debounce from 'lodash/debounce';

const props = defineProps({
    candidates: Object,
    filters: Object,
    skills: Array,
});

const searchForm = reactive({
    search: props.filters?.search || '',
    skill_id: props.filters?.skill_id || '',
});

watch(
    searchForm,
    debounce((value) => {
        router.get(
            route('employer.candidates.search'),
            value,
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            }
        );
    }, 300)
);
</script>

<template>
    <Head title="Search Candidates" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Resume Database</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <!-- شريط البحث (Live Filters) -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Keywords (Name or Bio)</label>
                            <input id="search" v-model="searchForm.search" type="text" placeholder="e.g. Mostafa, Frontend developer..." class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        </div>

                        <div>
                            <label for="skill" class="block text-sm font-medium text-gray-700 mb-1">Filter by Skill</label>
                            <select id="skill" v-model="searchForm.skill_id" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">All Skills</option>
                                <option v-for="skill in skills" :key="skill.id" :value="skill.id">
                                    {{ skill.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- قائمة الموظفين -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                    <div v-if="candidates.data.length === 0" class="col-span-full text-center py-12 bg-white rounded-lg shadow-sm text-gray-500">
                        No candidates found matching your criteria.
                    </div>

                    <!-- كارت الموظف -->
                    <div v-for="candidate in candidates.data" :key="candidate.id" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-gray-900">{{ candidate.name }}</h3>
                                <!-- رابط لينكد إن إن وجد -->
                                <a v-if="candidate.profile?.linkedin_id && candidate.profile.linkedin_id.startsWith('http')"
                                   :href="candidate.profile.linkedin_id" target="_blank"
                                   class="text-[#0077b5] hover:opacity-80" title="LinkedIn Profile">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                </a>
                            </div>

                            <p class="text-sm text-gray-600 mb-4 line-clamp-3" :title="candidate.profile?.bio">
                                {{ candidate.profile?.bio || 'No professional summary provided.' }}
                            </p>

                            <!-- المهارات -->
                            <div v-if="candidate.profile?.skills?.length" class="flex flex-wrap gap-2 mb-4">
                                <span v-for="skill in candidate.profile.skills.slice(0, 5)" :key="skill.id" class="px-2 py-1 bg-indigo-50 text-indigo-700 text-xs rounded-full">
                                    {{ skill.name }}
                                </span>
                                <span v-if="candidate.profile.skills.length > 5" class="text-xs text-gray-500 py-1">
                                    +{{ candidate.profile.skills.length - 5 }} more
                                </span>
                            </div>
                        </div>

                        <!-- رابط دعوة أو تحميل الـ CV (يمكن تطويره لاحقاً) -->
                        <div class="mt-4 pt-4 border-t flex justify-between items-center">
                            <span class="text-xs text-gray-500">Joined: {{ new Date(candidate.created_at).toLocaleDateString() }}</span>
                            <a v-if="candidate.profile?.resume_path" :href="'/storage/' + candidate.profile.resume_path" target="_blank" class="text-indigo-600 hover:underline text-sm font-semibold">
                                View Resume
                            </a>
                        </div>
                    </div>
                </div>

                <!-- التصفح (Pagination) -->
                <div v-if="candidates.links && candidates.data.length > 0" class="mt-8 flex justify-center">
                    <div class="flex flex-wrap gap-1">
                        <template v-for="(link, index) in candidates.links" :key="index">
                            <div v-if="link.url === null" class="px-4 py-2 border rounded text-gray-400 bg-gray-50 text-sm" v-html="link.label"></div>
                            <Link v-else :href="link.url" class="px-4 py-2 border rounded hover:bg-gray-100 text-sm" :class="{ 'bg-indigo-600 text-white hover:bg-indigo-700 border-indigo-600': link.active }" v-html="link.label"></Link>
                        </template>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
