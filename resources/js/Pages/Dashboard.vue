<script setup>
import { onMounted, ref } from 'vue';
import StravaBtn from '@/Components/StravaBtn.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/inertia-vue3';
import YearByWeekChart from '@/Charts/YearByWeekChart.vue';
import YearsProgressChart from '@/Charts/YearsProgressChart.vue';

const props = defineProps({
    weekDataChartDataInTime: { type: Array, required: false },
    weekDataChartDataInDistance: { type: Array, required: false },
    weekDataChartLabels: { type: Array, required: false },

    yearOverviewDataChartDataInTime: { type: Object, required: false },
    yearOverviewDataChartDataInDistance: { type: Object, required: false },
    yearOverviewDataChartLabels: { type: Array, required: false },

    yearContributionByYear: { type: Array, required: false },
    yearContributionLastYear: { type: Array, required: false },
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
    4: 'bg-[#ad5cad]',
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
                            <div class="bg-[#ad5cad] w-4 h-4 rounded-sm mr-1 mt-1 border-white hover:rounded-lg transition-all"></div>
                            <p>&#62; 3 hours</p>
                        </div>
                    </div>

                    <div class="flex">
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
            <div v-if="weekDataChartDataInTime" class="max-w-7xl mx-auto sm:px-6 lg:px-8 pt-8">
                <div class="overflow-hidden shadow-sm sm:rounded-lg p-6 bg-white border-b border-gray-200">
                    <h2 class="text-xl pb-10">
                       Your year overview per month
                    </h2>

                    <YearByWeekChart
                        :dataInTime="weekDataChartDataInTime"
                        :dataInDistance="weekDataChartDataInDistance"
                        :labels="weekDataChartLabels"
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
