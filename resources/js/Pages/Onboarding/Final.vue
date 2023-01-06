<script setup>
import WavyLayout from '@/Layouts/WavyLayout.vue';
import { Link } from '@inertiajs/inertia-vue3';
import GoalInputForm from '@/Components/GoalInputForm.vue';

const props = defineProps({
    sportTypeOptions: { type: Array, required: true },
    userGoal: { type: Object, required: false },
});
</script>

<template>
    <WavyLayout boxClasses="mt-[-190px] xl:mt-[-280px]">
        <div class="p-10 bg-white rounded-xl drop-shadow-xl space-y-5 md:w-[560px] text-center">
            <div class="w-full bg-gray-200"><div class="bg-[#734b6d] h-1 rounded-full" style="width: 100%"></div></div>
            <h1 class="text-2xl py-8 font-bold">You're all set!</h1>
            
            <div class="flex flex-wrap justify-center mb-6">
                <img class="w-24" src="/images/Party_popper.svg" />
            </div>
        
            
            <div class="py-8">
                <p>Get started by clicking the button below to open the dashboard. Explore your training volume and see the hard work you've put in!</p>
            </div>
            
            <Link href="/dashboard" class="inline-flex items-center justify-center px-4 py-2 text-base font-medium leading-6 text-white whitespace-no-wrap bg-[#734b6d] border rounded-md shadow-sm hover:bg-[#42275a]">open my dashboard</Link>
        </div>
    </WavyLayout>
</template>