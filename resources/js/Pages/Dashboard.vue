<script setup>
import { onMounted, ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/inertia-vue3';
import YearsProgressChart from '@/Charts/YearsProgressChart.vue';

const props = defineProps({
    yearOverviewDataChartDataInTime: { type: Object, required: false },
    yearOverviewDataChartDataInDistance: { type: Object, required: false },
    yearOverviewDataChartLabels: { type: Array, required: false },

    yearContributionLastYear: { type: Array, required: false },
    streak: { type: Number, required: false },
});

const selectedYear = ref(2023);
const windowSize = ref(0);
const onScreenResize = () => {
    window.addEventListener("resize", () => {
        windowSize.value = window.innerWidth;
    });
};
onMounted(() => onScreenResize());

const hoverDay = ref('');
const setHover = (day) =>  hoverDay.value = day;
const colorVariants = {
    0: 'bg-gray-200',
    1: 'bg-[#ddbbdd]',
    2: 'bg-[#ca97ca]',
    3: 'bg-[#ad5cad]',
    4: 'bg-[#800080]',
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div>
            <div v-if="yearContributionLastYear" class="max-w-7xl mx-auto sm:px-6 lg:px-8 pt-8">
                <div class="overflow-hidden shadow-sm sm:rounded-lg p-6 bg-white border-b border-gray-200">
                    <h2 class="text-xl pb-10">
                       Your year contribution
                    </h2>

                    <div v-if="streak > 3" class="flex flex-center mb-4">
                        <div>
                            <img class="h-10" src="/images/fire.svg" />
                        </div>
                        <p class="text-l font-bold pl-4 pt-2">You're on a <span
      class="inline-block whitespace-nowrap rounded-[0.27rem] bg-purple-100 px-[0.65em] pt-[0.35em] pb-[0.25em] text-center align-baseline text-[0.75em] font-bold leading-none text-purple-700"
      >{{ streak }}</span> days streak. Keep it going!</p>
                    </div>

                    <div class="inline-flex mb-4">
                        <div class="inline-flex mr-6">
                            <div class="bg-gray-200 w-4 h-4 rounded-sm mr-1 mt-1 border-white hover:rounded-lg transition-all"></div>
                            <p>No activities on this day</p>
                        </div>
                        <div class="inline-flex mr-6">
                            <div class="bg-[#ddbbdd] w-4 h-4 rounded-sm mr-1 mt-1 border-white hover:rounded-lg transition-all"></div>
                            <p>&#60; 40 minutes</p>
                        </div>
                        <div class="inline-flex mr-6">
                            <div class="bg-[#ca97ca] w-4 h-4 rounded-sm mr-1 mt-1 border-white hover:rounded-lg transition-all"></div>
                            <p>&#60; 1.5 hours</p>
                        </div>
                        <div class="inline-flex mr-6">
                            <div class="bg-[#ad5cad] w-4 h-4 rounded-sm mr-1 mt-1 border-white hover:rounded-lg transition-all"></div>
                            <p>&#60; 3 hours</p>
                        </div>
                        <div class="inline-flex mr-6">
                            <div class="bg-[#800080] w-4 h-4 rounded-sm mr-1 mt-1 border-white hover:rounded-lg transition-all"></div>
                            <p>&#62; 3 hours</p>
                        </div>
                    </div>

                    <div class="flex">
                        <div>
                            <div v-for="i in 7" :key="i" class="w-12 h-5" @mouseover="setHover(day.day)" >
                                <p v-if="i === 1">Mon</p>
                                <p v-if="i === 3">Wed</p>
                                <p v-if="i === 3"></p>
                                <p v-if="i === 5">Fri</p>
                                <p v-if="i === 5"></p>
                                <p v-if="i === 7">Sun</p>
                            </div>
                        </div>
                        <div v-for="week in yearContributionLastYear" :key="week">
                            <div v-for="day in week" :key="day.day" class="group" @mouseover="setHover(day.day)" >
                                <div :class="colorVariants[day.grade]" class="w-4 h-4 rounded-sm mr-1 mt-1 border-white hover:rounded-lg transition-all"></div>
                                <!-- <span :class="`group-hover:opacity-60 transition-opacity bg-gray-800 text-sm text-gray-100 rounded-md absolute opacity-0 p-1 z-10 mt-8`">
                                    <p>{{ day.day }}</p>
                                </span> -->
                            </div>
                        </div>
                    </div>

                    <p>{{ hoverDay }}</p>
                </div>
            </div>

            <div v-if="yearOverviewDataChartDataInTime" class="max-w-7xl mx-auto sm:px-6 lg:px-8 pt-8">
                <div class="overflow-hidden shadow-sm sm:rounded-lg p-6 bg-white border-b border-gray-200">
                    <h2 class="text-xl pb-10">
                       Your year progress
                    </h2>

                    <YearsProgressChart
                        :dataInTime="yearOverviewDataChartDataInTime"
                        :dataInDistance="yearOverviewDataChartDataInDistance"
                        :labels="yearOverviewDataChartLabels"
                    />
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
