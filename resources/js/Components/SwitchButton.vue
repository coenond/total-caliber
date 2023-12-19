<script setup>
import { computed } from 'vue';

defineEmits(['click']);

const props = defineProps({
    type: {
        type: String,
        required: true,
    },
    firstOption: {
        type: String,
        required: true,
    },
    secondOption: {
        type: String,
        required: true,
    },
    selected: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <label for="themeSwitcherOne" class="themeSwitcherTwo shadow-card relative inline-flex cursor-pointer select-none items-center justify-center rounded-md bg-white p-1">
        <input type="checkbox" name="themeSwitcherOne" id="themeSwitcherOne" class="sr-only">
        <span class="light text-primary bg-gray flex items-center space-x-[6px] rounded py-2 px-[18px] text-sm font-medium">
            {{ firstOption }}
        </span>
        <span class="dark text-body-color flex items-center space-x-[6px] rounded py-2 px-[18px] text-sm font-medium">
            {{ secondOption }}
        </span>
    </label>
</template>
