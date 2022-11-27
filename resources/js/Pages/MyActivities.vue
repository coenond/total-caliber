<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/inertia-vue3';
import { Link } from '@inertiajs/inertia-vue3'
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                My Strava Activities
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-sm sm:rounded-lg p-6 bg-white border-b border-gray-200">
                    <h1 class="text-xl pb-10">
                        These are your latests activities on Strava {{ $page.props.auth.user.name }},
                    </h1>

                    <div v-if="$page.props.syncIsOnCoolDown" class="py-4">
                        <span class="inline-flex items-center justify-center px-4 py-2 text-base font-medium leading-6 text-white whitespace-no-wrap bg-gray-400 border border-gray-500 rounded-md shadow-sm">
                            Sync my activities
                        </span>
                        <p>Sync jobs can only be done once per hour. New or updated activities will automatically be uploaded to Total Caliber</p>
                    </div>

                    <Link
                        v-else
                        href="/dashboard/my-activities/create-sync-job"
                        method="post" as="button" type="button"
                        preserve-state
                        class="inline-flex items-center justify-center px-4 py-2 text-base font-medium leading-6 text-white whitespace-no-wrap bg-blue-600 border border-blue-700 rounded-md shadow-sm hover:bg-blue-700">
                            Sync my activities
                    </Link>

                    <table v-if="$page.props.activities" class="min-w-full text-gray-900">
                        <thead class="border-b font-bold">
                            <tr>
                                <th scope="col" class="text-sm font-medium px-6 py-4 text-left font-bold">
                                    Name
                                </th>
                                <th scope="col" class="text-sm font-medium px-6 py-4 text-left font-bold">
                                    Type
                                </th>
                                <th scope="col" class="text-sm font-medium px-6 py-4 text-left font-bold">
                                    Distance
                                </th>
                                <th scope="col" class="text-sm font-medium px-6 py-4 text-left font-bold">
                                    Time
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="activity in $page.props.activities" :key="activity.id" class="border-b" >
                                <td class="text-sm font-light px-6 py-4">
                                    <a :href="`https://www.strava.com/activities/${activity.strava_id}`" class="hover:underline" target="_blank">{{activity.name}}</a>
                                </td>
                                <td class="text-sm font-light px-6 py-4 whitespace-nowrap">
                                    {{activity.sport_type.type}}
                                </td>
                                <td class="text-sm font-light px-6 py-4 whitespace-nowrap">
                                    {{activity.readableDistanceInKm}}
                                </td>
                                <td class="text-sm font-light px-6 py-4 whitespace-nowrap">
                                    {{activity.readableTime}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
