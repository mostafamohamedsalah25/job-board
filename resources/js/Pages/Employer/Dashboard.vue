<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatCard from '@/Components/UI/StatCard.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

const props = defineProps({
    stats: Object,
    chartData: Array
});

const barChartData = computed(() => {
    return {
        labels: props.chartData.map(job => job.title),
        datasets: [
            {
                label: 'Number of Applications',
                backgroundColor: '#fd761a', // لون Brand Secondary الجديد
                borderRadius: 4, // جعل أعمدة الرسم البياني مستديرة الحواف
                data: props.chartData.map(job => job.applications_count)
            }
        ]
    };
});

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
        y: {
            beginAtZero: true,
            ticks: { stepSize: 1 }
        }
    },
    plugins: {
        legend: { display: false } // إخفاء الـ Legend لشكل أنظف وأبسط
    }
};
</script>

<template>
    <Head title="Employer Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center w-full">
                <div>
                    <h2 class="font-display text-2xl font-bold text-primary">Employer Dashboard</h2>
                    <p class="text-sm text-on-surface-variant mt-1">Manage your active listings and evaluate talent.</p>
                </div>
                <Link :href="route('employer.jobs.create')" class="bg-secondary text-white px-6 py-2.5 rounded-lg font-semibold shadow-sm hover:opacity-90 active:scale-95 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">add_circle</span>
                    Post New Job
                </Link>
            </div>
        </template>

        <div class="space-y-8 mt-6">
            <!-- Stats Bento Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <StatCard
                    title="Active Jobs"
                    :value="stats.totalJobs"
                    icon="work"
                    iconBg="bg-blue-50 text-blue-600"
                    trend="+12%"
                    trendType="up"
                />
                <StatCard
                    title="Total Applications"
                    :value="stats.totalApplications"
                    icon="group"
                    iconBg="bg-orange-50 text-orange-600"
                    trend="+5%"
                    trendType="up"
                />
                <!-- كروت إضافية ديكورية لإكمال الشكل الجمالي المستوحى من التصميم -->
                <StatCard
                    title="Profile Views"
                    value="8,920"
                    icon="visibility"
                    iconBg="bg-purple-50 text-purple-600"
                />
                <StatCard
                    title="Total Hired"
                    value="16"
                    icon="task_alt"
                    iconBg="bg-green-50 text-green-600"
                    trend="+2"
                    trendType="up"
                />
            </div>

            <!-- Chart Section -->
            <div class="bg-white rounded-xl shadow-sm border border-outline p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-display text-xl text-primary font-bold">Applications per Recent Job</h3>
                </div>

                <div v-if="chartData.length > 0" class="h-80">
                    <Bar :data="barChartData" :options="chartOptions" />
                </div>

                <div v-else class="text-center py-16 text-slate-500 bg-surface-container rounded-lg border border-dashed border-outline-variant">
                    <span class="material-symbols-outlined text-4xl mb-2 text-slate-400">bar_chart</span>
                    <p class="font-medium">No data available to display the chart.</p>
                    <Link :href="route('employer.jobs.create')" class="text-secondary font-semibold hover:underline mt-2 inline-block">Post your first job!</Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
