<script setup>
import { ref, computed } from 'vue';
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, Colors } from 'chart.js';
import BadgeButton from '@/Components/BadgeButton.vue';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, Colors);

const props = defineProps({
    dataInTime: { type: Array, required: false },
    dataInDistance: { type: Array, required: false },
    labels: { type: Array, required: false },
});

const chartOptions = computed(() => {
    return {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top'
            }
        },
        scales: {
            y: {
                ticks: {
                    callback: function(value, index, ticks) {
                        return showChartIn.value === 'distance'
                            ? value + 'km'
                            : Math.floor(value / 60) + 'min';
                    }
                }
            }
        }
    };
});

const showChartIn = ref('distance');
const dataComputed = computed(() => {
    const data = showChartIn.value === 'distance'
        ? props.dataInDistance
        : props.dataInTime;
    return {
        labels: props.labels,
        datasets: data
    };
});
const toggle = (type) => showChartIn.value = type;
</script>

<template>
    <div>
        <div class="mb-5">
            <label for="email" class="mb-3 block text-base font-medium text-[#07074D]">Show chart in: </label>
            <BadgeButton class="border-b mt-2" type="Distance" :selected="showChartIn === 'distance'" @click="toggle('distance')" />
            <BadgeButton class="border-b mt-2" type="Time" :selected="showChartIn === 'time'" @click="toggle('time')" />
        </div>
        <Bar
            id="weekly"
            class="max-h-[300px] sm:max-h-[400px] md:max-h-[600px]"
            :options="chartOptions"
            :data="dataComputed"
        />
    </div>
</template>
