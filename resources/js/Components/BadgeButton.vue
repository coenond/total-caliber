<script setup>
import { computed } from 'vue';

defineEmits(['click']);

const props = defineProps({
    type: {
        type: String,
        required: true,
    },
    selected: {
        type: Boolean,
        required: true
    }
});

const selectedClass = computed(() => {
    return props.selected
        ? 'text-indigo-100 bg-[#734b6d] border-[#734b6d]'
        : 'text-[#734b6d] bg-white hover:bg-[#ebe0e9]';
});
</script>

<template>
    <span
        class="cursor-pointer mx-1 inline-flex items-center justify-center px-3 py-2 text-xs font-bold leading-none border-2 rounded"
        :class="selectedClass"
        v-on:click="$emit('click', type)">
        {{ type }}
    </span>
</template>
