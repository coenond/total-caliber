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
                <div class="overflow-hidden shadow-sm sm:rounded-lg p-6 bg-white border-b border-gray-200">
                    <h1 class="text-xl pb-10">
                        Welcome <strong>{{ $page.props.auth.user.name }}</strong>,
                    </h1>
                    <p v-if="$page.props.userHasStravaAuth">
                        You've succesfully authorized Strava for to track your <strong>Total Caliber.</strong>
                    </p>
                    <p v-else>
                       Click the button below for authorizing Total Caliber to post your progress.
                    </p>
                    <div v-if="$page.props.stravaAuthUrl && !$page.props.userHasStravaAuth" class="p-6 mt-6 bg-white">
                        <a :href=$page.props.stravaAuthUrl>
                            <button class="group relative h-12 w-48 overflow-hidden rounded-lg bg-white text-lg shadow">
                                <div class="absolute inset-0 w-3 bg-orange-600 transition-all duration-[250ms] ease-out group-hover:w-full"></div>
                                <span class="relative text-black group-hover:text-white">Connect to Strava</span>
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="$page.props.success_message">
            <div class="max-w-xs bg-green-500 text-sm text-white rounded-md shadow-lg mb-3 ml-3" role="alert">
                <div class="flex p-4">
                    {{ $page.props.success_message }}
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
