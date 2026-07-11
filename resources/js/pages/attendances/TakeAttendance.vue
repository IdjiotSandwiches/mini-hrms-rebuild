<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { getCurrentTime } from '@/lib/utils';
import { store } from '@/routes/v2/take-attendance';

defineProps<{
    isCheckedIn: boolean
}>();

const date = ref({ day: '', hours: '00', minutes: '00', seconds: '00' });
let stopClock: () => void;

onMounted(() => {
    stopClock = getCurrentTime((timeData: any) => {
        date.value = timeData;
    });
});

onUnmounted(() => {
    if (stopClock) {
stopClock();
}
});

</script>

<template>
    <Head title="Take Attendance" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl px-4 py-6">
        <Heading
            title="Take Attendance"
            description="This is where you verify your attendance"
        />

        <div class="py-10 flex flex-col gap-6 justify-center items-center select-none">
            <h1 class="scroll-m-20 text-center text-4xl font-extrabold tracking-tight text-balance">
                {{ date.day }}
            </h1>
            <div class="grid grid-cols-3 gap-2">
                <div class="flex justify-center items-center w-28 md:w-48 h-32 bg-gray-300 rounded-md font-semibold text-6xl lg:text-7xl shadow-md dark:bg-gray-600">
                    {{ date.hours }}
                </div>
                <div class="flex justify-center items-center w-28 md:w-48 h-32 bg-gray-300 rounded-md font-semibold text-6xl lg:text-7xl shadow-md dark:bg-gray-600">
                    {{ date.minutes }}
                </div>
                <div class="flex justify-center items-center w-28 md:w-48 h-32 bg-gray-300 rounded-md font-semibold text-6xl lg:text-7xl shadow-md dark:bg-gray-600">
                    {{ date.seconds }}
                </div>
            </div>

            <Form
                v-bind="store.form()"
                v-slot="{ processing }"
                disable-while-processing
            >
                <fieldset :disabled="processing">
                    <Button size="lg">
                        <slot v-if="isCheckedIn">Check Out</slot>
                        <slot v-else>Check In</slot>
                    </Button>
                </fieldset>
            </Form>
        </div>
    </div>
</template>
