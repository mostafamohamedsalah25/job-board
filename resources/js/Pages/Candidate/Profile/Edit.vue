<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    profile: Object,
    skills: Array, // أضفنا مصفوفة المهارات القادمة من الكنترولر
});

const user = usePage().props.auth.user;

const form = useForm({
    phone: props.profile?.phone || '',
    bio: props.profile?.bio || '',
    linkedin_id: props.profile?.linkedin_id && props.profile.linkedin_id.startsWith('http') ? props.profile.linkedin_id : '',
    // سحب الـ IDs الخاصة بالمهارات المحفوظة مسبقاً (إن وجدت)
    skills: props.profile?.skills?.map(skill => skill.id) || [],
    resume: null,
    _method: 'POST',
});

const submit = () => {
    form.post(route('candidate.profile.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="My Professional Profile"/>

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2 class="font-display text-2xl font-bold text-primary">My Professional Profile</h2>
                <p class="text-sm text-on-surface-variant mt-1">Upload your resume and details to stand out to employers.</p>
            </div>
        </template>

        <div class="mt-6 max-w-4xl">
            <div v-if="$page.props.flash && $page.props.flash.success" class="mb-6 px-4 py-3 bg-green-50 text-green-700 rounded-lg border border-green-100 flex items-center gap-2 font-medium shadow-sm">
                <span class="material-symbols-outlined">check_circle</span>
                {{ $page.props.flash.success }}
            </div>
            <div v-if="$page.props.flash && $page.props.flash.error" class="mb-6 px-4 py-3 bg-red-50 text-red-700 rounded-lg border border-red-100 flex items-center gap-2 font-medium shadow-sm">
                <span class="material-symbols-outlined">error</span>
                {{ $page.props.flash.error }}
            </div>

            <div class="bg-white overflow-hidden shadow-sm border border-outline sm:rounded-xl p-8">
                <form @submit.prevent="submit" class="space-y-6" enctype="multipart/form-data">

                    <!-- معلومات المستخدم الأساسية (للعرض فقط) -->
                    <div class="bg-surface-container p-4 rounded-lg border border-outline-variant mb-6 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-primary-container text-white flex items-center justify-center font-display font-bold text-xl">
                            {{ user.name.charAt(0) }}
                        </div>
                        <div>
                            <p class="font-bold text-primary">{{ user.name }}</p>
                            <p class="text-sm text-on-surface-variant">{{ user.email }}</p>
                            <p class="text-xs text-slate-400 mt-1">Change these in main account settings.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Phone -->
                        <div>
                            <InputLabel for="phone" value="Phone Number" class="font-bold text-primary mb-1"/>
                            <TextInput id="phone" type="text" class="block w-full" v-model="form.phone"/>
                            <InputError class="mt-2" :message="form.errors.phone"/>
                        </div>

                        <!-- LinkedIn URL -->
                        <div>
                            <InputLabel for="linkedin_id" value="LinkedIn Profile URL" class="font-bold text-primary mb-1"/>
                            <TextInput id="linkedin_id" type="url" class="block w-full" v-model="form.linkedin_id" placeholder="https://linkedin.com/in/username" />
                            <InputError class="mt-2" :message="form.errors.linkedin_id" />
                        </div>
                    </div>

                    <!-- Bio -->
                    <div>
                        <InputLabel for="bio" value="Professional Summary (Bio)" class="font-bold text-primary mb-1"/>
                        <textarea id="bio" v-model="form.bio" rows="4" class="block w-full border-outline-variant focus:border-secondary focus:ring-secondary rounded-lg shadow-sm text-sm p-3" placeholder="Tell employers about your skills and experience..."></textarea>
                        <InputError class="mt-2" :message="form.errors.bio"/>
                    </div>

                    <!-- Skills (القسم الجديد المضاف) -->
                    <div class="border-t border-outline-variant pt-6 mt-6">
                        <InputLabel value="Your Skills" class="font-display text-lg font-bold text-primary mb-3"/>
                        <p class="text-xs text-on-surface-variant mb-4">Select the skills that best match your expertise. Employers use these to find you!</p>

                        <div class="flex flex-wrap gap-3">
                            <label v-for="skill in skills" :key="skill.id" class="cursor-pointer relative">
                                <input type="checkbox" :value="skill.id" v-model="form.skills" class="peer sr-only" />
                                <span class="inline-flex items-center px-4 py-2 rounded-full border border-outline-variant text-sm font-medium text-on-surface-variant peer-checked:bg-secondary peer-checked:text-white peer-checked:border-secondary transition-all hover:bg-slate-50 peer-checked:hover:bg-orange-600 shadow-sm">
                                    {{ skill.name }}
                                </span>
                            </label>
                        </div>
                        <InputError class="mt-2" :message="form.errors.skills"/>
                    </div>

                    <!-- Resume Upload -->
                    <div class="border-t border-outline-variant pt-6 mt-6">
                        <InputLabel for="resume" value="Upload Resume (PDF only, Max 2MB)" class="font-bold text-primary mb-2"/>

                        <div v-if="profile?.resume_path" class="mb-4 flex items-center gap-2">
                            <a :href="'/storage/' + profile.resume_path" target="_blank" class="inline-flex items-center gap-1 text-sm font-semibold text-secondary hover:text-orange-700 bg-orange-50 px-3 py-1.5 rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-[18px]">description</span>
                                View Current Resume
                            </a>
                        </div>

                        <input type="file" id="resume" accept=".pdf" class="block w-full text-sm text-on-surface-variant
                            file:mr-4 file:py-2.5 file:px-4
                            file:rounded-lg file:border-0
                            file:text-sm file:font-bold
                            file:bg-primary-container file:text-white
                            hover:file:bg-primary transition-all cursor-pointer"
                            @input="form.resume = $event.target.files[0]"
                        />
                        <InputError class="mt-2" :message="form.errors.resume"/>
                        <progress v-if="form.progress" :value="form.progress.percentage" max="100" class="mt-2 w-full h-1.5 rounded bg-slate-200 accent-secondary">
                        </progress>
                    </div>

                    <!-- Social Connections -->
                    <div class="border-t border-outline-variant pt-6 mt-6 mb-6">
                        <h3 class="font-display text-lg font-bold text-primary mb-4">Social Connections</h3>

                        <div v-if="profile?.linkedin_id" class="flex items-center text-green-700 bg-green-50 px-4 py-3 rounded-lg border border-green-200 w-fit font-semibold text-sm shadow-sm">
                            <span class="material-symbols-outlined text-[20px] mr-2">check_circle</span>
                            LinkedIn Profile Connected
                        </div>

                        <div v-else class="flex flex-col items-start gap-2">
                            <a :href="route('candidate.linkedin.redirect')" class="inline-flex items-center px-6 py-2.5 bg-[#0077b5] text-white rounded-lg hover:bg-[#005e93] transition-all font-bold shadow-sm active:scale-95">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                Connect with LinkedIn
                            </a>
                            <p class="text-xs text-on-surface-variant mt-1">Connecting your LinkedIn profile increases your chances of being hired by verifying your professional identity.</p>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="flex items-center justify-end pt-6 mt-6 border-t border-outline-variant">
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Save Profile
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
