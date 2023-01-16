<script setup>
import Sidebar from '@/Layouts/Particles/Sidebar.vue';
import Topbar from '@/Layouts/Particles/Topbar.vue';
import FourColumnCards from '@/Layouts/Particles/FourColumnCards.vue';
import TwoColumnCardsL from '@/Layouts/Particles/TwoColumnCardsL.vue';
import TwoColumnCardsR from '@/Layouts/Particles/TwoColumnCardsR.vue';

</script>

<template>
    <div class="m-0 font-sans antialiased font-normal text-base leading-default bg-gray-50 text-slate-500">
        <Sidebar />
    </div>
    <main class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200">
        <Topbar />
        <FourColumnCards />
        <TwoColumnCardsL />
        <TwoColumnCardsR />
    </main>
</template>
