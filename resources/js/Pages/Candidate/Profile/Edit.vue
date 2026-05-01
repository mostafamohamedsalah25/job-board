<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    profile: Object,
});

const user = usePage().props.auth.user;

// في Inertia عند التعامل مع الملفات، نضع الملف كخصائص فارغة في البداية
const form = useForm({
    phone: props.profile?.phone || '',
    bio: props.profile?.bio || '',
    resume: null, // الحقل المخصص للملف
    _method: 'POST', // طريقة الإرسال
});

const submit = () => {
    // نستخدم post لضمان إرسال الملف (FormData) بشكل صحيح
    form.post(route('candidate.profile.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="My Profile"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Profile</h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

                <div v-if="$page.props.flash && $page.props.flash.success" class="mb-4 px-4 py-3 bg-green-100 text-green-700 rounded border border-green-200">
                    {{ $page.props.flash.success }}
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submit" class="space-y-6" enctype="multipart/form-data">

                        <!-- معلومات المستخدم الأساسية (للعرض فقط) -->
                        <div class="bg-gray-50 p-4 rounded border mb-6">
                            <p class="text-sm text-gray-500">Name: <span class="font-bold text-gray-900">{{ user.name }}</span></p>
                            <p class="text-sm text-gray-500">Email: <span class="font-bold text-gray-900">{{ user.email }}</span></p>
                            <p class="text-xs text-gray-400 mt-1">To change your name or email, please go to the main account settings.</p>
                        </div>

                        <!-- Phone -->
                        <div>
                            <InputLabel for="phone" value="Phone Number"/>
                            <TextInput id="phone" type="text" class="mt-1 block w-full" v-model="form.phone"/>
                            <InputError class="mt-2" :message="form.errors.phone"/>
                        </div>

                        <!-- Bio -->
                        <div>
                            <InputLabel for="bio" value="Professional Summary (Bio)"/>
                            <textarea id="bio" v-model="form.bio" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Tell employers about your skills and experience..."></textarea>
                            <InputError class="mt-2" :message="form.errors.bio"/>
                        </div>

                        <!-- Resume Upload -->
                        <div class="border-t pt-6 mt-6">
                            <InputLabel for="resume" value="Upload Resume (PDF only, Max 2MB)"/>

                            <!-- عرض رابط للسيرة الذاتية الحالية إن وجدت -->
                            <div v-if="profile?.resume_path" class="mb-3">
                                <a :href="'/storage/' + profile.resume_path" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-900 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>
                                    View Current Resume
                                </a>
                            </div>

                            <input type="file" id="resume" accept=".pdf" class="mt-1 block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-indigo-700
                                hover:file:bg-indigo-100"
                                @input="form.resume = $event.target.files[0]"
                            />
                            <InputError class="mt-2" :message="form.errors.resume"/>
                            <progress v-if="form.progress" :value="form.progress.percentage" max="100" class="mt-2 w-full">
                                {{ form.progress.percentage }}%
                            </progress>
                        </div>

                        <div class="border-t pt-6 mt-6 mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Social Connections</h3>

                            <!-- إذا كان مربوطاً بالفعل -->
                            <div v-if="profile?.linkedin_id" class="flex items-center text-green-600 font-semibold bg-green-50 p-3 rounded border border-green-200 w-fit">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                LinkedIn Connected Successfully
                            </div>

                            <div v-else>
                                <a :href="route('candidate.linkedin.redirect')" class="inline-flex items-center px-4 py-2 bg-[#0077b5] text-white rounded hover:bg-[#005e93] transition font-medium">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                    Connect with LinkedIn
                                </a>
                                <p class="text-xs text-gray-500 mt-2">Connecting your LinkedIn profile increases your chances of being hired.</p>
                            </div>
                        </div>
                        
                        <!-- Submit -->
                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Save Profile
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
