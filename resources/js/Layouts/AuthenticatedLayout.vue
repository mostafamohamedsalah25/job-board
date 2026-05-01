<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

const isActive = (routeName) => route().current(routeName);

// 🎯 تعريف الروابط لكل Role
const links = {
    admin: [
        { name: 'Overview', route: 'admin.dashboard', icon: 'dashboard' },
    ],
    employer: [
        { name: 'Overview', route: 'employer.dashboard', icon: 'dashboard' },
        { name: 'My Listings', route: 'employer.jobs.index', icon: 'work', active: 'employer.jobs.*' },
        { name: 'Find Talent', route: 'employer.candidates.search', icon: 'person_search', active: 'employer.candidates.*' },
    ],
    candidate: [
        { name: 'Overview', route: 'candidate.dashboard', icon: 'dashboard' },
        { name: 'Browse Jobs', route: 'candidate.jobs.index', icon: 'search', active: 'candidate.jobs.*' },
        { name: 'My Applications', route: 'candidate.applications.index', icon: 'work_history', active: 'candidate.applications.*' },
        { name: 'My Profile', route: 'candidate.profile.edit', icon: 'person', active: 'candidate.profile.*' },
    ],
};
</script>

<template>
<div class="bg-background text-on-surface min-h-screen flex font-sans">

    <!-- Sidebar -->
    <aside class="h-screen w-64 fixed left-0 top-0 border-r border-outline bg-white flex flex-col z-40">

        <!-- Logo -->
        <div class="text-2xl font-display font-extrabold text-primary px-6 py-8 tracking-tight">
            HireLink
        </div>

        <!-- Links -->
        <nav class="flex flex-col flex-1 space-y-1">

            <!-- Dynamic Links -->
            <template v-for="item in links[user.role]" :key="item.route">
                <Link
                    :href="route(item.route)"
                    :class="[
                        'flex items-center gap-3 px-6 py-3 font-semibold group border-r-4 transition-colors',
                        isActive(item.active || item.route)
                            ? 'bg-blue-50 text-primary border-primary'
                            : 'text-on-surface-variant hover:bg-slate-50 border-transparent'
                    ]"
                >
                    <span class="material-symbols-outlined transition-transform group-hover:translate-x-1">
                        {{ item.icon }}
                    </span>
                    <span>{{ item.name }}</span>
                </Link>
            </template>

            <!-- Settings -->
            <Link :href="route('profile.edit')"
                :class="[
                    'flex items-center gap-3 px-6 py-3 font-semibold group border-r-4 transition-colors',
                    isActive('profile.*')
                        ? 'bg-blue-50 text-primary border-primary'
                        : 'text-on-surface-variant hover:bg-slate-50 border-transparent'
                ]"
            >
                <span class="material-symbols-outlined">settings</span>
                <span>Settings</span>
            </Link>

        </nav>

        <!-- User -->
        <div class="p-6 mt-auto border-t border-outline">
            <div class="flex items-center gap-3 px-1">

                <div class="w-10 h-10 rounded-full bg-primary-container text-white flex items-center justify-center font-bold">
                    {{ user.name.charAt(0) }}
                </div>

                <div class="overflow-hidden flex-1">
                    <p class="text-sm font-bold truncate text-primary">{{ user.name }}</p>
                    <p class="text-xs text-on-surface-variant capitalize">{{ user.role }}</p>
                </div>

                <Link :href="route('logout')" method="post" as="button"
                      class="text-on-surface-variant hover:text-error">
                    <span class="material-symbols-outlined">logout</span>
                </Link>

            </div>
        </div>

    </aside>

    <!-- Main -->
    <main class="flex-1 ml-64 min-h-screen flex flex-col">

        <!-- Header -->
        <header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-outline px-8 py-4 flex justify-between items-center">
            <slot name="header" />

            <button class="relative p-2 text-on-surface-variant hover:bg-slate-50 rounded-full">
                <span class="material-symbols-outlined">notifications</span>
                <span class="absolute top-2 right-2 w-2 h-2 bg-secondary rounded-full border-2 border-white"></span>
            </button>
        </header>

        <!-- Content -->
        <div class="flex-1 p-8 max-w-7xl mx-auto w-full">
            <slot />
        </div>

    </main>

</div>
</template>
