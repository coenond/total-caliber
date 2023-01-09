<script setup>
import WavyLayout from '@/Layouts/WavyLayout.vue';
import { Link } from '@inertiajs/inertia-vue3';
import StravaBtn from '@/Components/StravaBtn.vue';


const props = defineProps({
    stravaAuthUrl: { type: String, required: true },
    userHasStravaAuth: { type: Boolean, required: true },
    syncIsOnCoolDown: { type: Boolean, required: true },
});

</script>

<template>
    <WavyLayout boxClasses="mt-[-190px] xl:mt-[-280px]">
        <div v-if="props.userHasStravaAuth" class="p-10 bg-white rounded-xl drop-shadow-xl space-y-5 md:w-[560px]">
            <div class="w-full bg-gray-200"><div class="bg-[#734b6d] h-1 rounded-full" style="width: 40%"></div></div>
            <h1 class="text-2xl font-bold">Welcome to Total Caliber!</h1>
            <h2 class="text-l">Thank you for registering. All that's left now is to go through some quick and easy steps to get you up and running.</h2>

            <hr />

            <p>You have successfully connected Strava with Total Caliber. To begin syncing your activities, simply click the button below. Let's get started!</p>

            <div v-if="props.syncIsOnCoolDown" class="py-4">
                <p>We are currently syncing your activities. This process usually takes between 1-5 minutes.</p>
                <div class="flex justify-between  mt-12">
                    <span class="inline-flex items-center justify-center px-4 py-2 text-base font-medium leading-6 text-white whitespace-no-wrap bg-gray-400 border border-gray-500 rounded-md shadow-sm">
                        Sync my activities
                    </span>
                    <Link href="/onboarding/set-goal" class="underline ml-2 mt-2">next</Link>
                </div>
            </div>

            <div v-else >
                <p class="font-bold">Click the button below to start syncing your Strava activities.</p>
                <div class="flex justify-between  mt-12">
                    <Link
                        href="/onboarding/syncActivities"
                        method="post" as="button" type="button"
                        preserve-state
                        class="inline-flex items-center justify-center px-4 py-2 text-base font-medium leading-6 text-white whitespace-no-wrap bg-[#734b6d] border rounded-md shadow-sm hover:bg-[#42275a]">
                            Sync my activities
                    </Link>
                    <Link href="/onboarding/set-goal" class="underline ml-2 mt-2">skip</Link>
                </div>
            </div>
        </div>
        <div v-else class="p-10 bg-white rounded-xl drop-shadow-xl space-y-5 md:w-[560px]">
            <div class="w-full bg-gray-200"><div class="bg-[#734b6d] h-1 rounded-full" style="width: 20%"></div></div>
            <h1 class="text-2xl font-bold">Welcome to Total Caliber!</h1>
            <h2 class="text-l">Thank you for registering. All that's left now is to go through some quick and easy steps to get you up and running.</h2>

            <hr />

            <p>Click the button below to link your Strava account.</p>       

            <p></p>
            <a :href=props.stravaAuthUrl>
                <StravaBtn />
            </a>
        </div>
    </WavyLayout>
</template>