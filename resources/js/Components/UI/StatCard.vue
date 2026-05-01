<script setup>
defineProps({
    title: String,
    value: [String, Number],
    icon: String,
    trend: String, // مثلاً "+12%"
    trendType: { type: String, default: 'up' }, // up أو down
    iconBg: { type: String, default: 'bg-blue-50 text-blue-600' }
});
</script>

<template>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-outline flex flex-col justify-between hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start mb-4">
            <div :class="['p-3 rounded-lg', iconBg]">
                <span class="material-symbols-outlined">{{ icon }}</span>
            </div>
            <span v-if="trend" :class="['text-xs font-bold px-2 py-1 rounded-full flex items-center', trendType === 'up' ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50']">
                {{ trend }}
                <span class="material-symbols-outlined text-[14px] ml-1">{{ trendType === 'up' ? 'trending_up' : 'trending_down' }}</span>
            </span>
        </div>
        <div>
            <p class="text-on-surface-variant text-sm font-semibold">{{ title }}</p>
            <p class="font-display text-3xl font-bold text-primary mt-1">{{ value }}</p>
        </div>
    </div>
</template>
