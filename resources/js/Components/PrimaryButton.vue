<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button :type="type" class="inline-flex items-center px-4 py-2 bg-[#1985A1] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#17A7CB] active:bg-[#1985A1] focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150">
        <slot />
    </button>
</template>
