<script setup>
import WavyLayout from '@/Layouts/WavyLayout.vue';
import CountDownClock from '@/Components/CountDownClock.vue';
import { useForm, Link } from '@inertiajs/inertia-vue3';
import BadgeButton from '@/Components/BadgeButton.vue';

const props = defineProps({
    sportTypeOptions: { type: Array, required: true },
    userGoal: { type: Object, required: true },
    stravaDescription: { type: Object, required: false },
    startReadable: { type: String },
    endReadable: { type: String },
});
const stravaDescriptionForm = useForm({
    enabled: props.stravaDescription?.enabled || false,
    showTotals: props.stravaDescription?.totals || false,
    showWeekStats: props.stravaDescription?.week_stats || false,
    showMonthStats: props.stravaDescription?.month_stats || false,
});

const toggleStravaDescription = () => {
    stravaDescriptionForm.enabled = !stravaDescriptionForm.enabled;
};

const toggleSportTypes = (key) => {
    goalSportTypeForm.selectedSportTypes.includes(key)
        ? goalSportTypeForm.selectedSportTypes = goalSportTypeForm.selectedSportTypes.filter((t) => t !== key)
        : goalSportTypeForm.selectedSportTypes.push(key);
};
const toggleDescriptionKey = (key) => {
    stravaDescriptionForm[key] = !stravaDescriptionForm[key]
};
const hasSportType = (type) => {
    return goalSportTypeForm.selectedSportTypes.includes(type);
};

const submitStravaDescription = () => {
    stravaDescriptionForm.post('/onboarding/strava-description');
};
</script>

<template>
    <WavyLayout boxClasses="mt-[-190px] xl:mt-[-280px]">
        <div class="p-10 bg-white rounded-xl drop-shadow-xl space-y-5 md:w-[560px]">
            <div class="w-full bg-gray-200"><div class="bg-[#734b6d] h-1 rounded-full" style="width: 85%"></div></div>
            <h1 class="text-2xl font-bold">Set Your Strava Description</h1>

            <p>Set your goal and let us help you achieve it!</p>
            <p>Take control of your training and let us help you show the world what you're capable of. We'll automatically update your Strava description after every activity, to reflect your hard work and dedication. You don't have to do it alone - let us support and motivate you every step of the way.</p>

<!--             
            <CountDownClock :date="props.userGoal.end" /> -->
            
            <hr />
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Enable Strava Description:</h2>
                <div>
                    <label class="inline-flex relative items-center cursor-pointer">
                        <input @click="toggleStravaDescription()" type="checkbox" value="" class="sr-only peer" :checked="stravaDescriptionForm.enabled">
                        <div class="w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-[#734b6d]"></div>
                    </label>
                </div>
            </div>
            <div v-if="stravaDescriptionForm.enabled" class="flex justify-between">
                <div class="mb-5">
                    <label class="mb-3 block text-base font-medium text-[#07074D]">Select the sport types you want to include in your summaries</label>
                    <BadgeButton class="border-b mt-2" type="Totals" :selected="stravaDescriptionForm.showTotals" @click="toggleDescriptionKey('showTotals')" />
                    <BadgeButton class="border-b mt-2" type="Week Stats" :selected="stravaDescriptionForm.showWeekStats" @click="toggleDescriptionKey('showWeekStats')" />
                    <BadgeButton class="border-b mt-2" type="Month Stats" :selected="stravaDescriptionForm.showMonthStats" @click="toggleDescriptionKey('showMonthStats')" />
                </div>
            </div>

            <div v-if="stravaDescriptionForm.enabled" class="mt-12">
                <strong>Here's what your Strava description will look like after we update it to reflect your training efforts and goals:</strong>
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
                    <p>Working towards my goal on {{ props.endReadable }}, with training starting on {{ props.startReadable }}.</p>
                    <p>----</p>
                </div>
                <hr class="my-3"/>

            </div>

            <div>
                <Link
                    @click="submitStravaDescription()" as="button" type="button"
                    class="inline-flex items-center justify-center px-4 py-2 text-base font-medium leading-6 text-white whitespace-no-wrap bg-[#734b6d] border rounded-md shadow-sm hover:bg-[#42275a]">
                        Save & finish
                </Link>
            </div>
        </div>
    </WavyLayout>
</template>