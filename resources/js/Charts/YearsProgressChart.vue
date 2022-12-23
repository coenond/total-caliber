<script setup>
import { ref, computed } from 'vue';
import { Line } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, Colors, PointElement } from 'chart.js';
import BadgeButton from '@/Components/BadgeButton.vue';

ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, Colors, PointElement);

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
                        if (showChartIn.value === 'time') {
                            const minutes = Math.floor(value / 60);
                            if (minutes < 60) {
                                return minutes + 'min';
                            }
                            return Math.floor(minutes / 60) + 'h';
                        }
                        return value.toLocaleString("nl-NL", { style: "decimal" }) + ' km';
                    }
                }
            },
            x: {
                ticks: {
                    callback: function(value, index, ticks) {
                        const date = new Date();
                        date.setFullYear(new Date().getFullYear());
                        date.setDate(value);
                        return date.toLocaleString("en-US", { month: "long", day: "numeric" });
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
        <Line
            id="weekly"
            class="max-h-[300px] sm:max-h-[400px] md:max-h-[600px]"
            :options="chartOptions"
            :data="dataComputed"
        />
    </div>
</template>
