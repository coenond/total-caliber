<script setup>
import WavyLayout from '@/Layouts/WavyLayout.vue';
import { Link } from '@inertiajs/inertia-vue3';
import GoalInputForm from '@/Components/GoalInputForm.vue';

const props = defineProps({
    sportTypeOptions: { type: Array, required: true },
});
</script>

<template>
    <WavyLayout boxClasses="mt-[-190px] xl:mt-[-280px]">
        <div class="p-10 bg-white rounded-xl drop-shadow-xl space-y-5 md:w-[560px]">
            <div class="w-full bg-gray-200"><div class="bg-[#734b6d] h-1 rounded-full" style="width: 60%"></div></div>
            <h1 class="text-2xl font-bold">Set Your Goal</h1>

            <p>Set your goal and let us help you achieve it! By telling us your goal, we can update your Strava description to reflect your training efforts. Show the world what you're working towards and let us support you along the way</p>
            
            <hr />

            <GoalInputForm :sport-types="props.sportTypeOptions" :padding="false"/>
        </div>
    </WavyLayout>
</template>