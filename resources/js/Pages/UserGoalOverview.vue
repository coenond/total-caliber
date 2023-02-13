<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/inertia-vue3';
import GoalInputForm from '@/Components/GoalInputForm.vue';
import CountDownClock from '@/Components/CountDownClock.vue';
import BadgeButton from '@/Components/BadgeButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    hasGoal: { type: Boolean, required: true },
    name: { type: String },
    start: { type: String },
    startReadable: { type: String },
    end: { type: String },
    endReadable: { type: String },
    sportTypes:  { type: Array, required: false },
    sportTypeOptions: { type: Array, required: true },
    userStravaDescription: { type: Object, required: false },
});

const goalSportTypeForm = useForm({
    goalTitle: props.name || '',
    goalStart: props.start || '',
    goalEnd: props.end || '',
    selectedSportTypes: props.sportTypes || [],
});
const stravaDescriptionForm = useForm({
    enabled: props.userStravaDescription?.enabled || false,
    simple: props.userStravaDescription?.simple || false,
    showTotals: props.userStravaDescription?.totals || false,
    showWeekStats: props.userStravaDescription?.week_stats || false,
    showMonthStats: props.userStravaDescription?.month_stats || false,
});

const toggleStravaDescription = () => {
    stravaDescriptionForm.enabled = !stravaDescriptionForm.enabled;
    if (!stravaDescriptionForm.enabled) {
        stravaDescriptionForm.post('/dashboard/goals/strava-description');
    }

};
const toggleSportTypes = (key) => {
    if (stravaDescriptionForm['simple']) {
         goalSportTypeForm.selectedSportTypes = [key];
    } else {
        goalSportTypeForm.selectedSportTypes.includes(key)
            ? goalSportTypeForm.selectedSportTypes = goalSportTypeForm.selectedSportTypes.filter((t) => t !== key)
            : goalSportTypeForm.selectedSportTypes.push(key);
    }
};
const toggleDescriptionKey = (key) => {
    if (key === 'simple') {
        if (goalSportTypeForm.selectedSportTypes.length > 1) {
            goalSportTypeForm.selectedSportTypes = ['Run'];
        }
    }
    stravaDescriptionForm[key] = !stravaDescriptionForm[key]
};
const hasSportType = (type) => {
    return goalSportTypeForm.selectedSportTypes.includes(type);
};

const submitGoalUpdate = () => {
    goalSportTypeForm.post('/dashboard/goals');
};
const submitStravaDescription = () => {
    stravaDescriptionForm.post('/dashboard/goals/strava-description');
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
                    <div v-if="props.hasGoal" class="container mx-auto">
                        <div class="grid grid-cols-1 lg:grid-cols-2">
                            <div>
                                <h2 class="font-semibold text-2xl text-gray-800 leading-tight pb-4"><strong>{{ props.name }}</strong></h2>
                                
                                <p>The {{ props.name }} will take place on <strong>{{ props.endReadable }}</strong></p>
                                <p>On <strong>{{ props.startReadable }}</strong> you've started your training.</p>

                                <p class="font-bold pt-6 pb-4">Sport types that contribute towards {{ props.name }}:</p>
                                <BadgeButton v-for="sportType in props.sportTypeOptions" :key="sportType" class="border-b" :type="sportType" :selected="hasSportType(sportType)" @click="toggleSportTypes(sportType)" />
                            </div>
                            <div class="py-8 w-full lg:w-1/2">
                                <CountDownClock :date="props.end" />
                                <PrimaryButton v-if="goalSportTypeForm.isDirty" @click="submitGoalUpdate()" :class="{ 'opacity-25': goalSportTypeForm.processing }">
                                    Save
                                </PrimaryButton>
                            </div>
                        </div>

                    </div>
                    <div v-else>
                        <p>
                            Set your goals to track your efforts.
                        </p>
                        <GoalInputForm
                            post-url="/dashboard/goals"
                            :sport-types="props.sportTypeOptions" />
                    </div>
                </div>
            </div>
            <div v-if="props.hasGoal" class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
                <div class="overflow-hidden shadow-sm sm:rounded-lg p-6 bg-white border-b border-gray-200">
                    <div class="flex mb-6 justify-between">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Total Caliber Strava Description</h2>
                        <div>
                            <label class="inline-flex relative items-center cursor-pointer">
                                <input @click="toggleStravaDescription()" type="checkbox" value="" class="sr-only peer" :checked="stravaDescriptionForm.enabled">
                                <div class="w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-[#734b6d]"></div>
                            </label>
                        </div>
                    </div>

                    <div class="bg-gray-100 rounded-md flex justify-between lg:w-1/3 md:w-2/3 px-2 pt-2 pb-4 pr-5">
                        <span class="pt-2">Use simple description</span>
                        <label class="inline-flex relative items-center cursor-pointer mt-2">
                            <input @click="toggleDescriptionKey('simple')" type="checkbox" value="" class="sr-only peer" :checked="stravaDescriptionForm.simple">
                            <div class="w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-[#734b6d]"></div>
                        </label>
                    </div>

                    <div v-if="stravaDescriptionForm.enabled && !stravaDescriptionForm.simple" class="flex justify-between">
                        <div class="mb-5">
                            <label for="email" class="mb-3 block text-base font-medium text-[#07074D]">Select the sport types you want to include in your summaries</label>
                            <BadgeButton class="border-b mt-2" type="Totals" :selected="stravaDescriptionForm.showTotals" @click="toggleDescriptionKey('showTotals')" />
                            <BadgeButton class="border-b mt-2" type="Week Stats" :selected="stravaDescriptionForm.showWeekStats" @click="toggleDescriptionKey('showWeekStats')" />
                            <BadgeButton class="border-b mt-2" type="Month Stats" :selected="stravaDescriptionForm.showMonthStats" @click="toggleDescriptionKey('showMonthStats')" />
                        </div>
                        <div>
                            <PrimaryButton v-if="stravaDescriptionForm.isDirty" @click="submitStravaDescription()" :class="{ 'opacity-25': stravaDescriptionForm.processing }">
                                Save
                            </PrimaryButton>
                        </div>
                    </div>

                    <div v-if="stravaDescriptionForm.enabled && goalSportTypeForm.selectedSportTypes.length > 0" class="mt-12">
                        <strong>Description preview</strong>
                        <hr class="my-3"/>
                        <div v-if="stravaDescriptionForm.simple" class="font-mono">
                            <p>Totals: 276km, 16h 12min in 35 runs towards {{ props.name }} on {{ props.endReadable }}.</p>
                            <p>&gt;&gt; by https://totalcaliber.com/</p>
                        </div>
                        <div v-else class="font-mono">
                            <p>&gt;&gt; Total Caliber Report &lt;&lt;</p>
                            <p v-if="stravaDescriptionForm.showTotals">Totals:</p>
                            <p v-if="stravaDescriptionForm.showTotals && goalSportTypeForm.selectedSportTypes.includes('Run')">- 22 runs: 190.2km in 16h 12min</p>
                            <p v-if="stravaDescriptionForm.showTotals && goalSportTypeForm.selectedSportTypes.includes('Ride')">- 8 rides: 324km in 11h 38min</p>
                            <p v-if="stravaDescriptionForm.showWeekStats">This week:</p>
                            <p v-if="stravaDescriptionForm.showWeekStats && goalSportTypeForm.selectedSportTypes.includes('Run')">- 1 run, 12.3km, 1h 8min</p>
                            <p v-if="stravaDescriptionForm.showWeekStats && goalSportTypeForm.selectedSportTypes.includes('Ride')">- 1 ride: 62.9km in 2h 10min</p>
                            <p v-if="stravaDescriptionForm.showMonthStats">This month:</p>
                            <p v-if="stravaDescriptionForm.showMonthStats && goalSportTypeForm.selectedSportTypes.includes('Run')">- 8 runs, 62.3km in 5h 15min</p>
                            <p v-if="stravaDescriptionForm.showMonthStats && goalSportTypeForm.selectedSportTypes.includes('Ride')">- 3 rides: 143.2km in 4h 43min</p>
                            <p>Training from {{ props.startReadable }} towards my goal on {{ props.endReadable }}</p>
                            <p>&gt;&gt; by https://totalcaliber.com/</p>
                        </div>
                        <hr class="my-3"/>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
