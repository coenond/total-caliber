<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import GoalInputForm from '@/Components/GoalInputForm.vue';

const stravaDescriptionForm = useForm({
    showWeekTotals: false,
});


</script>

<template>
    <Head title="My Goals" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Goals
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-sm sm:rounded-lg p-6 bg-white border-b border-gray-200">
                    <div v-if="$page.props.userGoal !== null">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Your goal</h2>
                        
                        <p>name: <strong>{{ $page.props.userGoal.name }}</strong></p>
                        <p>Goal Date: {{ $page.props.userGoal.end }}</p>
                        <p>Started training at: {{ $page.props.userGoal.start }}</p>

                    </div>
                    <div v-else>
                        <p>
                            Set your goals to track your efforts.
                        </p>
                        <GoalInputForm />
                    </div>
                </div>
            </div>
            <div v-if="$page.props.userGoal !== null" class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
                <div class="overflow-hidden shadow-sm sm:rounded-lg p-6 bg-white border-b border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Your Strava Description</h2>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
