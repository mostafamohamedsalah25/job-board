<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-background relative overflow-hidden font-sans">

        <!-- خلفية زخرفية ناعمة -->
        <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top_right,rgba(253,118,26,0.08),transparent_50%),radial-gradient(circle_at_bottom_left,rgba(2,36,72,0.05),transparent_50%)]"></div>

        <div class="mb-8">
            <Link href="/" class="flex items-center gap-2">
                <div class="w-12 h-12 bg-primary text-white rounded-xl flex items-center justify-center font-display font-bold text-2xl shadow-lg">
                    J
                </div>
                <span class="font-display text-3xl font-extrabold text-primary tracking-tight">JobBoard</span>
            </Link>
        </div>

        <!-- صندوق الفورم -->
        <div class="w-full sm:max-w-md px-8 py-10 bg-white shadow-xl shadow-slate-200/50 overflow-hidden sm:rounded-2xl border border-outline/50 relative z-10">
            <slot />
        </div>

        <!-- حقوق النشر (اختياري) -->
        <div class="mt-12 text-sm text-slate-400">
            © 2024 JobBoard Ecosystem. All rights reserved.
        </div>
    </div>
</template>
