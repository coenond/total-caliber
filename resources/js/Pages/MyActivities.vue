<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/inertia-vue3';
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

                    <table v-if="$page.props.activities" class="min-w-full text-gray-900">
                        <thead class="border-b">
                            <tr>
                            <th scope="col" class="text-sm font-medium px-6 py-4 text-left">
                                #
                            </th>
                            <th scope="col" class="text-sm font-medium px-6 py-4 text-left">
                                Name
                            </th>
                            <th scope="col" class="text-sm font-medium px-6 py-4 text-left">
                                Distance
                            </th>
                            <th scope="col" class="text-sm font-medium px-6 py-4 text-left">
                                Time
                            </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="activity in $page.props.activities" :key="activity.id" class="border-b" >
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    #{{activity.id}}
                                </td>
                                <td class="text-sm font-light px-6 py-4 whitespace-nowrap">
                                    {{activity.name}}
                                </td>
                                <td class="text-sm font-light px-6 py-4 whitespace-nowrap">
                                    {{activity.distance}}
                                </td>
                                <td class="text-sm font-light px-6 py-4 whitespace-nowrap">
                                    {{activity.moving_time}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
