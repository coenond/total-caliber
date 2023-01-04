<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button :type="type" class="inline-flex items-center px-4 py-2 bg-[#734b6d] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#42275a] active:bg-[#734b6d] focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150">
        <slot />
    </button>
</template>
