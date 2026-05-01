<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

defineProps({
    categories: Array,
});

const form = useForm({
    title: '',
    category_id: '',
    location: '',
    type: 'on-site',
    salary_range: '',
    deadline: '',
    description: '',
});

const submit = () => {
    form.post(route('employer.jobs.store'));
};
</script>

<template>
    <Head title="Post a New Job" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center w-full">
                <div>
                    <h2 class="font-display text-2xl font-bold text-primary">Post a New Job</h2>
                    <p class="text-sm text-on-surface-variant mt-1">Fill in the details below to attract top talent.</p>
                </div>
                <Link :href="route('employer.jobs.index')" class="text-sm font-semibold text-slate-500 hover:text-primary transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    Cancel
                </Link>
            </div>
        </template>

        <div class="mt-6 max-w-4xl">
            <div class="bg-white rounded-xl shadow-sm border border-outline p-8">
                <form @submit.prevent="submit" class="space-y-6">

                    <div>
                        <InputLabel for="title" value="Job Title" class="font-bold text-primary mb-1" />
                        <TextInput id="title" type="text" class="block w-full border-outline-variant focus:border-secondary focus:ring-secondary rounded-lg" v-model="form.title" required autofocus placeholder="e.g. Senior Frontend Developer"/>
                        <InputError class="mt-2" :message="form.errors.title"/>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <InputLabel for="category_id" value="Category" class="font-bold text-primary mb-1" />
                            <select id="category_id" v-model="form.category_id" class="block w-full border-outline-variant focus:border-secondary focus:ring-secondary rounded-lg text-sm" required>
                                <option value="" disabled>Select a Category</option>
                                <option v-for="category in categories" :key="category.id" :value="category.id">
                                    {{ category.name }}
                                </option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.category_id"/>
                        </div>

                        <div>
                            <InputLabel for="type" value="Work Type" class="font-bold text-primary mb-1" />
                            <select id="type" v-model="form.type" class="block w-full border-outline-variant focus:border-secondary focus:ring-secondary rounded-lg text-sm" required>
                                <option value="on-site">On-site</option>
                                <option value="remote">Remote</option>
                                <option value="hybrid">Hybrid</option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.type"/>
                        </div>

                        <div>
                            <InputLabel for="location" value="Location" class="font-bold text-primary mb-1" />
                            <TextInput id="location" type="text" class="block w-full border-outline-variant focus:border-secondary focus:ring-secondary rounded-lg" v-model="form.location" required placeholder="e.g. Cairo, Egypt or Remote"/>
                            <InputError class="mt-2" :message="form.errors.location"/>
                        </div>

                        <div>
                            <InputLabel for="salary_range" value="Salary Range (Optional)" class="font-bold text-primary mb-1" />
                            <TextInput id="salary_range" type="text" class="block w-full border-outline-variant focus:border-secondary focus:ring-secondary rounded-lg" v-model="form.salary_range" placeholder="e.g. $1000 - $1500"/>
                            <InputError class="mt-2" :message="form.errors.salary_range"/>
                        </div>

                        <div class="md:col-span-2">
                            <InputLabel for="deadline" value="Application Deadline" class="font-bold text-primary mb-1" />
                            <TextInput id="deadline" type="date" class="block w-full md:w-1/2 border-outline-variant focus:border-secondary focus:ring-secondary rounded-lg" v-model="form.deadline" required/>
                            <InputError class="mt-2" :message="form.errors.deadline"/>
                        </div>
                    </div>

                    <div>
                        <InputLabel for="description" value="Job Description & Requirements" class="font-bold text-primary mb-1" />
                        <textarea id="description" v-model="form.description" rows="6" class="block w-full border-outline-variant focus:border-secondary focus:ring-secondary rounded-lg text-sm p-3" required placeholder="Describe the role, responsibilities, and ideal candidate..."></textarea>
                        <InputError class="mt-2" :message="form.errors.description"/>
                    </div>

                    <div class="flex items-center justify-end pt-4 border-t border-outline-variant">
                        <button type="submit" :class="['bg-secondary text-white px-8 py-3 rounded-lg font-bold hover:opacity-90 active:scale-95 transition-all shadow-md shadow-secondary/20', { 'opacity-50 cursor-not-allowed': form.processing }]" :disabled="form.processing">
                            Publish Job
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
