<script setup>
import { ref } from 'vue';

const props = defineProps({
    date: {
        type: String,
        required: true,
    },
});

const days = ref(0);
const hours = ref(0);
const minutes = ref(0);
const seconds = ref(null);

setInterval(() => {
    const goalDate = new Date(props.date);
    const currentDate = new Date();
    var delta = Math.abs(goalDate - currentDate) / 1000;
    // calculate (and subtract) whole days
    days.value = parseInt(Math.floor(delta / 86400));
    delta -= days.value * 86400;
    // calculate (and subtract) whole hours
    hours.value = String(parseInt(Math.floor(delta / 3600) % 24)).padStart(2, '0');;
    delta -= hours.value * 3600;
    // calculate (and subtract) whole minutes
    minutes.value = String(parseInt(Math.floor(delta / 60) % 60)).padStart(2, '0');;
    delta -= minutes.value * 60;
    // what's left is seconds
    seconds.value = String(parseInt(delta % 60)).padStart(2, '0');
}, 1000);
</script>

<template>
    <div v-if="seconds" class="text-6xl text-center flex w-full items-center justify-center">
        <div class="m-w-24 mx-1 p-2 bg-[#ebe0e9] text-[#734b6d] rounded-lg">
            <div class="font-mono leading-none" x-text="days">{{ days }}</div>
            <div class="font-mono uppercase text-sm leading-none">Days</div>
        </div>
        <div class="w-24 mx-1 p-2 bg-[#ebe0e9] text-[#734b6d] rounded-lg">
            <div class="font-mono leading-none" x-text="hours">{{ hours }}</div>
            <div class="font-mono uppercase text-sm leading-none">Hours</div>
        </div>
        <div class="w-24 mx-1 p-2 bg-[#ebe0e9] text-[#734b6d] rounded-lg">
            <div class="font-mono leading-none" x-text="minutes">{{ minutes }}</div>
            <div class="font-mono uppercase text-sm leading-none">Minutes</div>
        </div>
        <div class="w-24 mx-1 p-2 bg-[#ebe0e9] text-[#734b6d] rounded-lg">
            <div class="font-mono leading-none" x-text="seconds">{{ seconds }}</div>
            <div class="font-mono uppercase text-sm leading-none">Seconds</div>
        </div>
    </div>
</template>
