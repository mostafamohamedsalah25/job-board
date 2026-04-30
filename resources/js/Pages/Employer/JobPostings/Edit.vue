<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    job: Object,
    categories: Array,
});

const form = useForm({
    title: props.job.title,
    category_id: props.job.category_id,
    location: props.job.location,
    type: props.job.type,
    salary_range: props.job.salary_range,
    deadline: props.job.deadline ? new Date(props.job.deadline).toISOString().split('T')[0] : '',
    description: props.job.description,
});

const submit = () => {
    form.put(route('employer.jobs.update', props.job.id));
};
</script>

<template>
    <Head title="Edit Job" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Job: {{ job.title }}</h2>
                <Link :href="route('employer.jobs.index')" class="text-indigo-600 hover:text-indigo-900">Back to Jobs</Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submit" class="space-y-6">

                        <div>
                            <InputLabel for="title" value="Job Title" />
                            <TextInput id="title" type="text" class="mt-1 block w-full" v-model="form.title" required autofocus />
                            <InputError class="mt-2" :message="form.errors.title" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <InputLabel for="category_id" value="Category" />
                                <select id="category_id" v-model="form.category_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option v-for="category in categories" :key="category.id" :value="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.category_id" />
                            </div>

                            <div>
                                <InputLabel for="type" value="Work Type" />
                                <select id="type" v-model="form.type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="on-site">On-site</option>
                                    <option value="remote">Remote</option>
                                    <option value="hybrid">Hybrid</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.type" />
                            </div>

                            <div>
                                <InputLabel for="location" value="Location" />
                                <TextInput id="location" type="text" class="mt-1 block w-full" v-model="form.location" required />
                                <InputError class="mt-2" :message="form.errors.location" />
                            </div>

                            <div>
                                <InputLabel for="salary_range" value="Salary Range (Optional)" />
                                <TextInput id="salary_range" type="text" class="mt-1 block w-full" v-model="form.salary_range" />
                                <InputError class="mt-2" :message="form.errors.salary_range" />
                            </div>

                            <div>
                                <InputLabel for="deadline" value="Application Deadline" />
                                <TextInput id="deadline" type="date" class="mt-1 block w-full" v-model="form.deadline" required />
                                <InputError class="mt-2" :message="form.errors.deadline" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="description" value="Job Description & Requirements" />
                            <textarea id="description" v-model="form.description" rows="5" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required></textarea>
                            <InputError class="mt-2" :message="form.errors.description" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Save Changes
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
