<script setup>
import { ref, computed, onMounted } from 'vue';
import StravaBtn from '@/Components/StravaBtn.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/inertia-vue3';
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, Colors } from 'chart.js';
import BadgeButton from '@/Components/BadgeButton.vue';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, Colors);

const props = defineProps({
    weekDataChartDataInTime: { type: Array, required: false },
    weekDataChartDataInDistance: { type: Array, required: false },
    weekDataChartLabels: { type: Array, required: false },
});

const weekChartOptions = computed(() => {
    return {
        responsive: true,
        plugins: {
            legend: {
                position: 'top'
            }
        },
        scales: {
            y: {
                ticks: {
                    // Include a dollar sign in the ticks
                    callback: function(value, index, ticks) {
                        return showWeekChartIn.value === 'distance'
                            ? value + 'km'
                            : Math.floor(value / 60) + 'min';
                    }
                }
            }
        }
    };
});

const showWeekChartIn = ref('distance');
const weekChartDataComputed = computed(() => {
    const data = showWeekChartIn.value === 'distance'
        ? props.weekDataChartDataInDistance
        : props.weekDataChartDataInTime;
    return {
        labels: props.weekDataChartLabels,
        datasets: data
    };
});
const toggleYearTotals = (type) => showWeekChartIn.value = type;
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-sm sm:rounded-lg p-6 bg-white border-b border-gray-200">
                    <h1 class="text-xl pb-10">
                        Welcome <strong>{{ $page.props.auth.user.name }}</strong>,
                    </h1>
                    <p v-if="$page.props.userHasStravaAuth">
                        You've successfully authorized Strava for to track your <strong>Total Caliber.</strong>
                    </p>
                    <p v-else>
                       Click the button below for authorizing Total Caliber to post your progress.
                    </p>
                    <div v-if="$page.props.stravaAuthUrl && !$page.props.userHasStravaAuth" class="p-6 mt-6 bg-white">
                        <a :href=$page.props.stravaAuthUrl>
                            <StravaBtn />
                        </a>
                    </div>
                </div>
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pt-8">
                <div class="overflow-hidden shadow-sm sm:rounded-lg p-6 bg-white border-b border-gray-200">
                    <h2 class="text-xl pb-10">
                       Your year overview per month
                    </h2>
                    
                    <div class="mb-5">
                        <label for="email" class="mb-3 block text-base font-medium text-[#07074D]">Show chart in: </label>
                        <BadgeButton class="border-b mt-2" type="Distance" :selected="showWeekChartIn === 'distance'" @click="toggleYearTotals('distance')" />
                        <BadgeButton class="border-b mt-2" type="Time" :selected="showWeekChartIn === 'time'" @click="toggleYearTotals('time')" />
                    </div>
                    <Bar
                        id="weekly"
                        :options="weekChartOptions"
                        :data="weekChartDataComputed"
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
