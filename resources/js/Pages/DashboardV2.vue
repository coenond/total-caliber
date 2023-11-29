<script setup>
import { onMounted } from 'vue';
import { initFlowbite } from 'flowbite';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

// initialize components based on data attribute selectors
onMounted(() => { initFlowbite(); })
</script>

<template>
    <DashboardLayout />
</template>