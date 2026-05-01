<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    application: Object,
    fee: Number,
});

const form = useForm({});

const submitPayment = () => {
    // نرسل طلب POST لمعالجة الدفع
    form.post(route('employer.payments.processPayment', props.application.id));
};
</script>

<template>
    <Head title="Checkout" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Secure Checkout</h2>
                <Link :href="route('employer.jobs.show', application.job_posting_id)" class="text-gray-600 hover:text-gray-900 underline">Cancel</Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 grid grid-cols-1 md:grid-cols-2 gap-8">

                    <!-- ملخص الطلب -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Order Summary</h3>
                        <div class="bg-gray-50 p-4 rounded border">
                            <p class="text-sm text-gray-600">Candidate:</p>
                            <p class="font-semibold text-gray-900">{{ application.candidate.name }}</p>

                            <hr class="my-3">

                            <p class="text-sm text-gray-600">Service:</p>
                            <p class="font-semibold text-gray-900">Unlock Contact Information</p>

                            <hr class="my-3">

                            <div class="flex justify-between items-center text-lg font-bold text-gray-900 mt-4">
                                <span>Total Fee:</span>
                                <span>${{ fee.toFixed(2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- فورم الدفع (وهمي للتوضيح) -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Payment Method</h3>
                        <div class="space-y-4">
                            <!-- محاكاة لبطاقة ائتمان -->
                            <div class="p-4 border rounded bg-gray-50 flex items-center justify-between">
                                <span class="font-medium">Credit Card (Mock)</span>
                                <svg class="w-8 h-8 text-indigo-600" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
                            </div>

                            <div class="text-sm text-gray-500 mb-4">
                                * This is a simulation. In production, Stripe Elements or PayPal SDK will be rendered here.
                            </div>

                            <form @submit.prevent="submitPayment">
                                <button type="submit" :disabled="form.processing" class="w-full py-3 bg-indigo-600 text-white font-bold rounded hover:bg-indigo-700 transition flex justify-center items-center">
                                    <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Proceed to Stripe Checkout
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
