<script setup>
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';


const form = useForm({
    goalTitle: '',
    goalStart: '',
    goalEnd: '',
});

const submit = () => {
    form.post(route('dashboard.goals.store'));
};

</script>

<template><div class="flex p-12">
    <div class="mx-auto w-full max-w-[550px]">
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
                <label for="email" class="mb-3 block text-base font-medium text-[#07074D]">When is your Goal/Event</label>
                <Datepicker v-model="form.goalStart" :enableTimePicker="false" :required="true" />
            </div>
            <div class="mb-5">
                <label for="email" class="mb-3 block text-base font-medium text-[#07074D]">When id you start traning for this goal?</label>
                <Datepicker v-model="form.goalEnd" :enableTimePicker="false" :required="true" />
            </div>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Set Goal
            </PrimaryButton>
        </form>
    </div>
</div>
</template>
