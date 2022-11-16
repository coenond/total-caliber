<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/inertia-vue3';
import GoalInputForm from '@/Components/GoalInputForm.vue';
// import TextInput from '@/Components/TextInput.vue';
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
                    <p>
                        Set your goals to track your effords.
                    </p>

                    <GoalInputForm />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
