<script setup>
defineProps({
    type: {
        type: String,
        default: 'default', // success, warning, danger, info
    }
});

const colorClasses = {
    success: 'bg-green-50 text-green-700 border-green-100',
    warning: 'bg-orange-50 text-orange-700 border-orange-100',
    danger: 'bg-red-50 text-red-700 border-red-100',
    info: 'bg-blue-50 text-blue-700 border-blue-100',
    default: 'bg-slate-100 text-slate-700 border-slate-200',
};
</script>

<template>
    <span :class="['px-3 py-1 rounded-full text-xs font-semibold border', colorClasses[type]]">
        <slot />
    </span>
</template>
