<script setup>
import { computed} from 'vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import BadgeButton from '@/Components/BadgeButton.vue';


const props = defineProps({
    sportTypes: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    goalTitle: '',
    goalStart: '',
    goalEnd: '',
    selectedSportTypes: [],
});

const submit = () => {
    form.post('/dashboard/goals');
};


const isSelected = (type) => {
    return form.selectedSportTypes.includes(type);
}

const toggle = (type) => {
    if (isSelected(type)) {
        const index = form.selectedSportTypes.indexOf(type);
        form.selectedSportTypes.splice(index, 1);
    } else {
        form.selectedSportTypes.push(type);
    }
};

</script>

<template><div class="flex p-12">
    <div class="w-full max-w-[550px]">
        <form @submit.prevent="submit">
            <div class="mb-5">
                <label for="name" class="mb-3 block text-base font-medium text-[#07074D]">
                    Give your goal a name
                </label>
                <TextInput
                    v-model="form.goalTitle"
                    class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                    placeholder="Example: New York Marathon"
                    required autofocus />
            </div>
            <div class="mb-5">
                <label for="email" class="mb-3 block text-base font-medium text-[#07074D]">When did you start training for this goal?</label>
                <Datepicker v-model="form.goalStart" :enableTimePicker="false" :required="true" showNowButton nowButtonLabel="Today"/>
            </div>
            <div class="mb-5">
                <label for="email" class="mb-3 block text-base font-medium text-[#07074D]">When is your Goal/Event</label>
                <Datepicker v-model="form.goalEnd" :enableTimePicker="false" :required="true" :minDate="new Date()" />
            </div>

            <div class="mb-5">
                <label for="email" class="mb-3 block text-base font-medium text-[#07074D]">Select the sport types you want to include in your summaries</label>
                <BadgeButton v-for="sportType in props.sportTypes" :key="sportType" class="border-b" :type="sportType" :selected="isSelected(sportType)" @click="toggle(sportType)" />
            </div>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Set Goal
            </PrimaryButton>
        </form>
    </div>
</div>
</template>
