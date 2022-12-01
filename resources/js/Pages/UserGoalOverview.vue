<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import GoalInputForm from '@/Components/GoalInputForm.vue';
import BadgeButton from '@/Components/BadgeButton.vue';

const props = defineProps({
    hasGoal: { type: Boolean, required: true },
    name: { type: String },
    start: { type: String },
    startReadable: { type: String },
    end: { type: String },
    endReadable: { type: String },
    sportTypes: { type: Array, required: true },
});

const stravaDescriptionForm = useForm({
    enabled: false,
    showTotals: false,
    showWeekStats: false,
    showMonthStats: false,
});

const toggleStravaDescription = () => {
    stravaDescriptionForm.enabled = !stravaDescriptionForm.enabled;
};
const toggle = (key) => {
    stravaDescriptionForm[key] = !stravaDescriptionForm[key]
};
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
                    <div v-if="props.hasGoal">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight pb-4">Your goal</h2>
                        
                        <p><strong>{{ props.name }}</strong> on {{ props.end }}</p>
                        <p><i>Started training at: {{ props.start }}</i></p>

                    </div>
                    <div v-else>
                        <p>
                            Set your goals to track your efforts.
                        </p>
                        <GoalInputForm :sport-types="props.sportTypes" />
                    </div>
                </div>
            </div>
            <div v-if="$page.props.userGoal !== null" class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
                <div class="overflow-hidden shadow-sm sm:rounded-lg p-6 bg-white border-b border-gray-200">
                    <div class="flex mb-6">
                        <h2 class="flex-auto font-semibold text-xl text-gray-800 leading-tight">Total Caliber Strava Description</h2>
                        <div>
                        <label class="flex-auto inline-flex relative items-center mr-5 cursor-pointer">
                            <input @click="toggleStravaDescription()" type="checkbox" value="" class="sr-only peer" :checked="stravaDescriptionForm.enabled">
                            <div class="w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-[#734b6d]"></div>
                        </label>
                        </div>
                    </div>

                    <div v-if="stravaDescriptionForm.enabled" class="mb-5">
                        <label for="email" class="mb-3 block text-base font-medium text-[#07074D]">Select the sport types you want to include in your summaries</label>
                        <BadgeButton class="border-b mt-2" type="Totals" :selected="stravaDescriptionForm.showTotals" @click="toggle('showTotals')" />
                        <BadgeButton class="border-b mt-2" type="Week Stats" :selected="stravaDescriptionForm.showWeekStats" @click="toggle('showWeekStats')" />
                        <BadgeButton class="border-b mt-2" type="Month Stats" :selected="stravaDescriptionForm.showMonthStats" @click="toggle('showMonthStats')" />
                    </div>

                    <div v-if="stravaDescriptionForm.enabled" class="mt-12">
                        <strong>Example Description</strong>
                        <hr class="my-3"/>
                        <div class="font-mono">
                            <p>&gt;&gt; Training Caliber &lt;&lt;</p>
                            <p v-if="stravaDescriptionForm.showTotals">Totals:</p>
                            <p v-if="stravaDescriptionForm.showTotals">- 22 runs: 190.2km in 16h 12min</p>
                            <p v-if="stravaDescriptionForm.showTotals">- 8 rides: 324km in 11h 38min</p>
                            <p v-if="stravaDescriptionForm.showWeekStats">This week:</p>
                            <p v-if="stravaDescriptionForm.showWeekStats">- 1 run, 12.3km, 1h 8min</p>
                            <p v-if="stravaDescriptionForm.showWeekStats">- 1 ride: 62.9km in 2h 10min</p>
                            <p v-if="stravaDescriptionForm.showMonthStats">This month:</p>
                            <p v-if="stravaDescriptionForm.showMonthStats">- 8 runs, 62.3km in 5h 15min</p>
                            <p v-if="stravaDescriptionForm.showMonthStats">- 3 rides: 143.2km in 4h 43min</p>
                            <p>Training from {{ props.startReadable }} towards my goal on {{ props.endReadable }}</p>
                            <p>----</p>
                        </div>
                        <hr class="my-3"/>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
