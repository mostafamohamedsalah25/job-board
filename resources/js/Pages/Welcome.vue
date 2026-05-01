<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

// بيانات وهمية مؤقتة (Mock Data) لعرضها بشكل ديناميكي لتقليل تكرار الـ HTML
const categories = [
    { name: 'Software Dev', count: '1,240 Open Positions', icon: 'terminal', color: 'blue' },
    { name: 'Design & UI/UX', count: '850 Open Positions', icon: 'palette', color: 'orange' },
    { name: 'Marketing', count: '420 Open Positions', icon: 'campaign', color: 'green' },
    { name: 'Finance', count: '315 Open Positions', icon: 'payments', color: 'purple' },
];

const featuredJobs = [
    { title: 'Senior Product Designer', company: 'Stellar Analytics', location: 'San Francisco, CA', salary: '$140k - $180k', type: 'Full-Time', time: '2h ago', logo: 'https://images.unsplash.com/photo-1560179707-f14e90ef3623?w=100&h=100&fit=crop' },
    { title: 'Backend Engineer (Go)', company: 'CloudScale Systems', location: 'Remote, Global', salary: '$120k - $160k', type: 'Remote', time: '5h ago', logo: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=100&h=100&fit=crop' },
    { title: 'Marketing Strategist', company: 'GrowthLabs Global', location: 'London, UK', salary: '$80k - $100k', type: 'Contract', time: '1d ago', logo: 'https://images.unsplash.com/photo-1572044162444-ad60f128bdea?w=100&h=100&fit=crop' },
    { title: 'Mobile App Developer', company: 'Rocket Mobile', location: 'Austin, TX', salary: '$110k - $150k', type: 'Full-Time', time: '2d ago', logo: 'https://images.unsplash.com/photo-1611162617474-5b21e879e113?w=100&h=100&fit=crop' },
    { title: 'Data Scientist', company: 'Precision Finance', location: 'New York, NY', salary: '$160k - $210k', type: 'Full-Time', time: '3d ago', logo: 'https://images.unsplash.com/photo-1551434678-e076c223a692?w=100&h=100&fit=crop' },
    { title: 'Product Manager', company: 'Omni Retail', location: 'Remote, US', salary: '$130k - $170k', type: 'Remote', time: '4d ago', logo: 'https://images.unsplash.com/photo-1599305445671-ac291c95aaa9?w=100&h=100&fit=crop' },
];

const steps = [
    { title: 'Create Account', desc: 'Build your professional profile and tell us about your skills and career aspirations.', icon: 'person_add' },
    { title: 'Upload CV', desc: 'Sync your resume and our AI will match you with the best opportunities in real-time.', icon: 'upload_file' },
    { title: 'Find Jobs', desc: 'Apply with a single click and track your applications directly from your dashboard.', icon: 'task_alt' },
];
</script>

<template>
    <Head title="Welcome to JobBoard" />

    <div class="bg-background text-on-surface font-sans antialiased overflow-x-hidden">

        <!-- TopNavBar -->
        <header class="bg-white/95 backdrop-blur-md sticky top-0 z-50 border-b border-outline shadow-sm">
            <div class="flex justify-between items-center w-full px-6 py-4 max-w-7xl mx-auto">
                <div class="flex items-center gap-8">
                    <span class="text-2xl font-display font-extrabold tracking-tight text-primary">JobBoard</span>
                    <nav class="hidden md:flex items-center gap-6">
                        <a href="#" class="text-primary font-semibold border-b-2 border-secondary pb-1">Find Jobs</a>
                        <a href="#" class="text-on-surface-variant font-medium hover:text-primary transition-all duration-200">Companies</a>
                        <a href="#" class="text-on-surface-variant font-medium hover:text-primary transition-all duration-200">Pricing</a>
                    </nav>
                </div>

                <!-- أزرار تسجيل الدخول والديناميكية -->
                <div v-if="canLogin" class="flex items-center gap-4">
                    <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="bg-primary text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-opacity-90 transition-all duration-200 active:scale-95 transform">
                        Go to Dashboard
                    </Link>
                    <template v-else>
                        <Link :href="route('login')" class="hidden lg:block text-on-surface-variant font-medium hover:text-primary px-4 py-2 transition-all duration-200 active:scale-95 transform">
                            Log in
                        </Link>
                        <Link v-if="canRegister" :href="route('register')" class="bg-secondary text-white px-6 py-2.5 rounded-lg font-semibold hover:opacity-90 transition-all duration-200 active:scale-95 transform">
                            Post a Job / Join
                        </Link>
                    </template>
                </div>
            </div>
        </header>

        <main>
            <!-- Hero Section -->
            <section class="relative pt-20 pb-32 overflow-hidden">
                <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top_right,rgba(253,118,26,0.08),transparent_50%),radial-gradient(circle_at_bottom_left,rgba(2,36,72,0.05),transparent_50%)]"></div>
                <div class="max-w-7xl mx-auto px-6 text-center">
                    <h1 class="font-display text-5xl md:text-6xl font-bold text-primary mb-6 max-w-4xl mx-auto leading-tight">
                        Discover Your Next Professional Milestone
                    </h1>
                    <p class="text-lg text-on-surface-variant mb-12 max-w-2xl mx-auto">
                        Connecting world-class talent with the industry's most innovative companies. Start your journey with us today.
                    </p>

                    <!-- Search Component -->
                    <div class="max-w-4xl mx-auto bg-white p-2 rounded-xl shadow-lg flex flex-col md:flex-row items-center gap-2 border border-slate-100">
                        <div class="w-full flex items-center px-4 py-2 border-r border-slate-100">
                            <span class="material-symbols-outlined text-slate-400 mr-3">search</span>
                            <input type="text" class="w-full border-none focus:ring-0 text-sm" placeholder="Job title, keywords...">
                        </div>
                        <div class="w-full flex items-center px-4 py-2 border-r border-slate-100">
                            <span class="material-symbols-outlined text-slate-400 mr-3">location_on</span>
                            <input type="text" class="w-full border-none focus:ring-0 text-sm" placeholder="Location">
                        </div>
                        <Link :href="route('login')" class="w-full md:w-auto bg-secondary text-white px-8 py-3 rounded-lg font-semibold hover:opacity-90 transition-all flex items-center justify-center gap-2">
                            Search Jobs
                        </Link>
                    </div>

                    <!-- Stats Bar -->
                    <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-8 max-w-3xl mx-auto bg-white/50 backdrop-blur-sm py-8 px-12 rounded-2xl border border-white/50 shadow-sm">
                        <div>
                            <div class="text-3xl font-display font-bold text-primary">10,000+</div>
                            <div class="text-xs text-on-surface-variant uppercase tracking-wider mt-1 font-semibold">Live Jobs</div>
                        </div>
                        <div>
                            <div class="text-3xl font-display font-bold text-primary">5,000+</div>
                            <div class="text-xs text-on-surface-variant uppercase tracking-wider mt-1 font-semibold">Companies</div>
                        </div>
                        <div>
                            <div class="text-3xl font-display font-bold text-primary">50,000+</div>
                            <div class="text-xs text-on-surface-variant uppercase tracking-wider mt-1 font-semibold">Candidates</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Job Categories (Componentized via v-for) -->
            <section class="py-24 bg-surface-container">
                <div class="max-w-7xl mx-auto px-6">
                    <div class="flex justify-between items-end mb-12">
                        <div>
                            <h2 class="text-3xl font-display font-bold text-primary mb-2">Explore by Category</h2>
                            <p class="text-on-surface-variant">Find the perfect role across diverse industries.</p>
                        </div>
                        <a href="#" class="text-secondary font-semibold flex items-center gap-2 hover:underline hidden sm:flex">
                            View All <span class="material-symbols-outlined">arrow_forward</span>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div v-for="(category, index) in categories" :key="index" class="bg-white p-8 rounded-xl border border-slate-100 shadow-sm hover:shadow-md transition-all group cursor-pointer">
                            <div :class="`w-12 h-12 bg-${category.color}-50 text-${category.color}-700 rounded-lg flex items-center justify-center mb-6 transition-colors`">
                                <span class="material-symbols-outlined">{{ category.icon }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-primary mb-1">{{ category.name }}</h3>
                            <p class="text-sm text-slate-500">{{ category.count }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Featured Jobs -->
            <section class="py-24">
                <div class="max-w-7xl mx-auto px-6">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl font-display font-bold text-primary mb-4">Featured Career Opportunities</h2>
                        <p class="text-on-surface-variant max-w-xl mx-auto">Hand-picked premium roles from our top partners curated for excellence.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="(job, index) in featuredJobs" :key="index" class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm hover:shadow-lg transition-all relative">
                            <div class="flex justify-between items-start mb-4">
                                <div class="w-12 h-12 rounded-lg bg-slate-50 flex items-center justify-center overflow-hidden border border-slate-100 p-1">
                                    <img :src="job.logo" :alt="job.company" class="rounded object-cover h-full w-full">
                                </div>
                                <span class="bg-blue-50 text-blue-900 text-xs px-3 py-1 rounded-full font-semibold">{{ job.type }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-primary mb-1">{{ job.title }}</h3>
                            <p class="text-slate-600 font-medium mb-4">{{ job.company }}</p>

                            <div class="flex flex-wrap gap-4 text-slate-500 mb-6 text-sm">
                                <div class="flex items-center gap-1"><span class="material-symbols-outlined text-[18px]">location_on</span> {{ job.location }}</div>
                                <div class="flex items-center gap-1"><span class="material-symbols-outlined text-[18px]">payments</span> {{ job.salary }}</div>
                            </div>

                            <div class="pt-6 border-t border-slate-50 flex justify-between items-center">
                                <span class="text-xs text-slate-400">Posted {{ job.time }}</span>
                                <Link :href="route('login')" class="bg-indigo-50 text-indigo-700 px-4 py-2 rounded-lg font-semibold text-sm hover:bg-indigo-600 hover:text-white transition-colors">
                                    Apply Now
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- How It Works -->
            <section class="py-24 bg-primary text-white overflow-hidden relative">
                <div class="absolute right-0 top-0 w-1/3 h-full bg-secondary/10 skew-x-12 transform translate-x-20"></div>
                <div class="max-w-7xl mx-auto px-6 relative z-10">
                    <div class="text-center mb-20">
                        <h2 class="text-3xl font-display font-bold mb-4">Your Path to Career Success</h2>
                        <p class="text-indigo-200 max-w-xl mx-auto">Three simple steps to unlock your professional potential.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                        <div v-for="(step, index) in steps" :key="index" class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center mb-8 border border-white/20 text-secondary relative">
                                <span class="material-symbols-outlined text-3xl">{{ step.icon }}</span>
                                <div class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-secondary text-white font-bold flex items-center justify-center text-sm shadow-lg">{{ index + 1 }}</div>
                            </div>
                            <h3 class="text-xl font-bold mb-4">{{ step.title }}</h3>
                            <p class="text-indigo-100 text-sm leading-relaxed">{{ step.desc }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="py-24">
                <div class="max-w-5xl mx-auto px-6">
                    <div class="bg-gradient-to-br from-secondary to-orange-600 rounded-2xl p-12 text-center text-white relative overflow-hidden shadow-2xl">
                        <div class="absolute -left-20 -top-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                        <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-primary/20 rounded-full blur-3xl"></div>
                        <h2 class="font-display text-4xl font-bold mb-6 relative z-10">Find Your Dream Job Today</h2>
                        <p class="text-lg mb-10 opacity-90 max-w-2xl mx-auto relative z-10">Join thousands of professionals who have found their perfect career match through our ecosystem.</p>
                        <div class="flex flex-col sm:flex-row justify-center gap-4 relative z-10">
                            <Link :href="route('register')" class="bg-primary text-white px-10 py-4 rounded-lg font-bold text-lg hover:bg-slate-800 transition-all active:scale-95 shadow-lg">
                                Get Started Now
                            </Link>
                            <Link :href="route('login')" class="bg-white/20 backdrop-blur-md text-white border border-white/30 px-10 py-4 rounded-lg font-bold text-lg hover:bg-white/30 transition-all active:scale-95">
                                Browse All Jobs
                            </Link>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-outline py-12 text-sm">
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 lg:flex lg:justify-between items-start gap-12">
                <div class="max-w-xs">
                    <div class="text-xl font-display font-bold text-primary mb-6">JobBoard</div>
                    <p class="text-on-surface-variant mb-6 leading-relaxed">The world's leading platform for job seekers and hiring managers. Streamlining the path to professional excellence.</p>
                </div>
                <div class="grid grid-cols-2 gap-12 lg:gap-24">
                    <div>
                        <h4 class="font-bold text-primary mb-6">Platform</h4>
                        <ul class="space-y-4">
                            <li><a href="#" class="text-on-surface-variant hover:text-primary transition-colors">About Us</a></li>
                            <li><a href="#" class="text-on-surface-variant hover:text-primary transition-colors">Careers</a></li>
                            <li><a href="#" class="text-on-surface-variant hover:text-primary transition-colors">Support</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold text-primary mb-6">Legal</h4>
                        <ul class="space-y-4">
                            <li><a href="#" class="text-on-surface-variant hover:text-primary transition-colors">Privacy</a></li>
                            <li><a href="#" class="text-on-surface-variant hover:text-primary transition-colors">Terms</a></li>
                        </ul>
                    </div>
                </div>
                <div class="bg-surface-container p-6 rounded-xl border border-outline shadow-sm">
                    <h4 class="font-bold text-primary mb-4">Join our Newsletter</h4>
                    <p class="text-on-surface-variant mb-4">Stay updated with the latest job trends.</p>
                    <div class="flex gap-2">
                        <input type="email" placeholder="Email address" class="bg-white border-outline rounded-lg text-sm px-4 focus:ring-secondary focus:border-secondary w-full">
                        <button class="bg-secondary text-white px-4 py-2 rounded-lg font-semibold hover:opacity-90 transition-colors">Subscribe</button>
                    </div>
                </div>
            </div>
            <div class="max-w-7xl mx-auto px-6 mt-12 pt-8 border-t border-outline flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-on-surface-variant">
                <p>© 2024 JobBoard Ecosystem. Empowering careers globally. Laravel v{{ laravelVersion }}</p>
                <div class="flex gap-6 items-center">
                    <span class="material-symbols-outlined text-slate-400">language</span>
                    <span>English (US)</span>
                </div>
            </div>
        </footer>
    </div>
</template>
