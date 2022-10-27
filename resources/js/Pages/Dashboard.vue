<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/inertia-vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        You're logged in change!!
                        {{ $page.props.stravaAuthUrl }}
                    </div>
                    <div class="p-6 mt-6 bg-white border-b border-gray-200">
                        <a :href=$page.props.stravaAuthUrl class="underline">Connect TotalCaliber to your strava</a>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
