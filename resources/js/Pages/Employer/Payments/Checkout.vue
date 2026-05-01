<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    application: Object,
    fee: Number,
});

const form = useForm({});

const submitPayment = () => {
    form.post(route('employer.payments.processPayment', props.application.id));
};
</script>

<template>
    <Head title="Secure Checkout" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center w-full">
                <div>
                    <h2 class="font-display text-2xl font-bold text-primary">Secure Checkout</h2>
                    <p class="text-sm text-on-surface-variant mt-1">Unlock full contact details for this candidate.</p>
                </div>
                <Link :href="route('employer.jobs.show', application.job_posting_id)" class="text-sm font-semibold text-slate-500 hover:text-primary flex items-center gap-1 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    Cancel
                </Link>
            </div>
        </template>

        <div class="mt-8 max-w-4xl mx-auto">
            <div class="bg-white rounded-xl shadow-sm border border-outline overflow-hidden flex flex-col md:flex-row">

                <!-- ملخص الطلب (اليسار) -->
                <div class="p-8 md:w-1/2 bg-surface-container border-b md:border-b-0 md:border-r border-outline">
                    <h3 class="font-display text-lg font-bold text-primary mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined">receipt_long</span>
                        Order Summary
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-on-surface-variant uppercase tracking-wider font-semibold">Candidate</p>
                            <p class="font-bold text-primary text-lg">{{ application.candidate.name }}</p>
                        </div>
                        <hr class="border-outline-variant">
                        <div>
                            <p class="text-xs text-on-surface-variant uppercase tracking-wider font-semibold">Service</p>
                            <p class="font-medium text-primary">Unlock Contact Information</p>
                        </div>
                        <hr class="border-outline-variant">
                        <div class="flex justify-between items-center pt-2">
                            <span class="font-display font-bold text-primary text-xl">Total Due</span>
                            <span class="font-display font-extrabold text-secondary text-2xl">${{ fee.toFixed(2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- فورم الدفع (اليمين) -->
                <div class="p-8 md:w-1/2 flex flex-col justify-center">
                    <h3 class="font-display text-lg font-bold text-primary mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined">lock</span>
                        Payment Method
                    </h3>

                    <div class="space-y-6">
                        <!-- محاكاة لبطاقة ائتمان -->
                        <div class="p-4 border border-outline-variant rounded-lg bg-white flex items-center justify-between shadow-sm">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary">credit_card</span>
                                <span class="font-bold text-primary">Pay via Stripe</span>
                            </div>
                            <div class="flex gap-1">
                                <!-- دوائر توضيحية لفيزا/ماستركارد -->
                                <div class="w-6 h-4 bg-blue-900 rounded-sm"></div>
                                <div class="w-6 h-4 bg-red-500 rounded-sm"></div>
                            </div>
                        </div>

                        <div class="text-xs text-on-surface-variant bg-blue-50 p-3 rounded-lg border border-blue-100 flex items-start gap-2">
                            <span class="material-symbols-outlined text-[16px] text-blue-600">info</span>
                            You will be redirected to Stripe's secure checkout page to complete this transaction.
                        </div>

                        <form @submit.prevent="submitPayment">
                            <button type="submit" :disabled="form.processing" class="w-full py-3.5 bg-primary text-white font-bold rounded-lg hover:bg-slate-800 transition-all flex justify-center items-center gap-2 active:scale-95 shadow-lg shadow-primary/20">
                                <svg v-if="form.processing" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span v-else class="material-symbols-outlined text-[20px]">lock_open</span>
                                Proceed to Secure Checkout
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
