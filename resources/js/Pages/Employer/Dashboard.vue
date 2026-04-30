<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';

// تسجيل مكونات Chart.js
ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

const props = defineProps({
    stats: Object,
    chartData: Array
});

// تجهيز البيانات بصيغة يقبلها Chart.js
const barChartData = computed(() => {
    return {
        // أسماء الوظائف ستكون المحور الأفقي X
        labels: props.chartData.map(job => job.title),
        datasets: [
            {
                label: 'Number of Applications',
                backgroundColor: '#4f46e5', // لون indigo-600
                // أعداد المتقدمين ستكون المحور الرأسي Y
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
            ticks: {
                stepSize: 1 // لضمان أن الأرقام صحيحة (مفيش متقدم ونصف!)
            }
        }
    }
};
</script>

<template>
    <Head title="Employer Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Employer Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <!-- كروت الإحصائيات العلوية (Stats Cards) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex items-center space-x-4 border-l-4 border-indigo-500">
                        <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                            <!-- أيقونة حقيبة -->
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Posted Jobs</p>
                            <p class="text-2xl font-bold text-gray-900">{{ stats.totalJobs }}</p>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex items-center space-x-4 border-l-4 border-green-500">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <!-- أيقونة مستخدمين -->
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Applications Received</p>
                            <p class="text-2xl font-bold text-gray-900">{{ stats.totalApplications }}</p>
                        </div>
                    </div>
                </div>

                <!-- قسم الرسم البياني (Chart Section) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Applications per Recent Job</h3>

                    <div v-if="chartData.length > 0" class="h-80">
                        <!-- عرض الرسم البياني -->
                        <Bar :data="barChartData" :options="chartOptions" />
                    </div>

                    <div v-else class="text-center py-10 text-gray-500">
                        <p>No data available to display the chart.</p>
                        <Link :href="route('employer.jobs.create')" class="text-indigo-600 hover:underline mt-2 inline-block">Post your first job!</Link>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
