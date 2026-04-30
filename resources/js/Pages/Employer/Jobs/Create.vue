<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
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
    <Head title="Post a New Job"/>

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Post a New Job</h2>
                <Link :href="route('employer.dashboard')" class="text-indigo-600 hover:text-indigo-900">Back to Dashboard</Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submit" class="space-y-6">

                        <!-- Job Title -->
                        <div>
                            <InputLabel for="title" value="Job Title"/>
                            <TextInput id="title" type="text" class="mt-1 block w-full" v-model="form.title" required autofocus/>
                            <InputError class="mt-2" :message="form.errors.title"/>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Category -->
                            <div>
                                <InputLabel for="category_id" value="Category"/>
                                <select id="category_id" v-model="form.category_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="" disabled>Select a Category</option>
                                    <option v-for="category in categories" :key="category.id" :value="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.category_id"/>
                            </div>

                            <!-- Work Type -->
                            <div>
                                <InputLabel for="type" value="Work Type"/>
                                <select id="type" v-model="form.type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="on-site">On-site</option>
                                    <option value="remote">Remote</option>
                                    <option value="hybrid">Hybrid</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.type"/>
                            </div>

                            <!-- Location -->
                            <div>
                                <InputLabel for="location" value="Location"/>
                                <TextInput id="location" type="text" class="mt-1 block w-full" v-model="form.location" required placeholder="e.g. Cairo, Egypt or Remote"/>
                                <InputError class="mt-2" :message="form.errors.location"/>
                            </div>

                            <!-- Salary Range -->
                            <div>
                                <InputLabel for="salary_range" value="Salary Range (Optional)"/>
                                <TextInput id="salary_range" type="text" class="mt-1 block w-full" v-model="form.salary_range" placeholder="e.g. $1000 - $1500"/>
                                <InputError class="mt-2" :message="form.errors.salary_range"/>
                            </div>

                            <!-- Deadline -->
                            <div>
                                <InputLabel for="deadline" value="Application Deadline"/>
                                <TextInput id="deadline" type="date" class="mt-1 block w-full" v-model="form.deadline" required/>
                                <InputError class="mt-2" :message="form.errors.deadline"/>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <InputLabel for="description" value="Job Description & Requirements"/>
                            <textarea id="description" v-model="form.description" rows="5" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required></textarea>
                            <InputError class="mt-2" :message="form.errors.description"/>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Post Job
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
