<script setup>
import { ref, watch, computed, onMounted } from 'vue';
import { usePage } from '@inertiajs/inertia-vue3';

const props = defineProps({
    boxClasses: { type: String, required: true },
});

const showNotification = ref(false);
const position = computed(() => {
    return showNotification.value ? 'bottom-8 md:left-0' : '-bottom-[20%] md:bottom-8 md:-left-[100%]';
});
watch(() => usePage().props.value.notification.message, () => {
    showNotification.value = true;
    setTimeout(() => showNotification.value = false, 5000);
});
onMounted(() => {
    if (usePage().props.value.notification.message) {
        showNotification.value = true;
        setTimeout(() => showNotification.value = false, 5000)
    }
});
</script>

<style>
.gradient {
    background: linear-gradient(90deg, #42275a   0%, #734b6d 100%);
}
</style>

<template>
    <div :class="position" class="transition-all fixed bottom-8 mx-8 flex p-4 bg-green-300 bg-opacity-80 rounded-lg lg:max-w-[400px] mx-auto py-6 px-4 sm:px-6 lg:px-8 lg:mt-4" role="alert">
        <svg aria-hidden="true" class="relative flex-shrink-0 w-5 h-5 text-green-700 dark:text-green-800" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
        <span class="sr-only">Info</span>
        <div class="ml-3 text-m text-green-800">
            <strong>{{ $page.props.notification.message }}</strong>
        </div>
    </div>
    <div class="leading-normal tracking-normal gradient">
        <!--Push-->
        <div class="h-64"></div>
        <div class="relative -mt-12 lg:-mt-24">
            <svg viewBox="0 0 1428 174" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g transform="translate(-2.000000, 44.000000)" fill="#FFFFFF" fill-rule="nonzero">
                <path d="M0,0 C90.7283404,0.927527913 147.912752,27.187927 291.910178,59.9119003 C387.908462,81.7278826 543.605069,89.334785 759,82.7326078 C469.336065,156.254352 216.336065,153.6679 0,74.9732496" opacity="0.100000001"></path>
                <path
                    d="M100,104.708498 C277.413333,72.2345949 426.147877,52.5246657 546.203633,45.5787101 C666.259389,38.6327546 810.524845,41.7979068 979,55.0741668 C931.069965,56.122511 810.303266,74.8455141 616.699903,111.243176 C423.096539,147.640838 250.863238,145.462612 100,104.708498 Z"
                    opacity="0.100000001"
                ></path>
                <path d="M1046,51.6521276 C1130.83045,29.328812 1279.08318,17.607883 1439,40.1656806 L1439,120 C1271.17211,77.9435312 1140.17211,55.1609071 1046,51.6521276 Z" id="Path-4" opacity="0.200000003"></path>
                </g>
                <g transform="translate(-4.000000, 76.000000)" fill="#FFFFFF" fill-rule="nonzero">
                <path
                    d="M0.457,34.035 C57.086,53.198 98.208,65.809 123.822,71.865 C181.454,85.495 234.295,90.29 272.033,93.459 C311.355,96.759 396.635,95.801 461.025,91.663 C486.76,90.01 518.727,86.372 556.926,80.752 C595.747,74.596 622.372,70.008 636.799,66.991 C663.913,61.324 712.501,49.503 727.605,46.128 C780.47,34.317 818.839,22.532 856.324,15.904 C922.689,4.169 955.676,2.522 1011.185,0.432 C1060.705,1.477 1097.39,3.129 1121.236,5.387 C1161.703,9.219 1208.621,17.821 1235.4,22.304 C1285.855,30.748 1354.351,47.432 1440.886,72.354 L1441.191,104.352 L1.121,104.031 L0.457,34.035 Z"
                ></path>
                </g>
            </g>
            </svg>
        </div>
    </div>

    <div :class="props.boxClasses" class="w-screen flex justify-center items-center mt-[-190px] xl:mt-[-280px] px-8">
        <slot />
    </div>
</template>
