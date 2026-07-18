<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { TimePicker } from '@/components/ui/time-picker';
import { store } from '@/routes/v2/input-schedule';

const props = defineProps<{
    schedules: any;
    canUpdate: boolean;
    dayWeek: any;
}>();

const getInitialSchedules = () => {
    const schedule = props.schedules;

    return props.dayWeek.map((day: any) => {
        const s = schedule.find((x: any) => x.day == day);

        return {
            day: day,
            start_time: s?.start_time || null,
            end_time: s?.end_time || null,
        };
    });
};

const initSchedules = ref(getInitialSchedules());
</script>

<template>
    <Head title="Schedule Management" />

    <div
        class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl px-4 py-6"
    >
        <Heading
            title="Schedule Management"
            description="View and manage your work schedules"
        />

        <Form
            v-bind="store.form()"
            v-slot="{ errors, processing }"
            disable-while-processing
        >
            <fieldset :disabled="processing || !canUpdate">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="text-center">Day</TableHead>
                            <TableHead class="text-center"
                                >Start Time</TableHead
                            >
                            <TableHead class="text-center">End Time</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(day, idx) in dayWeek" :key="idx">
                            <TableCell class="w-1/3 text-center font-medium">
                                {{ day }}
                                <input
                                    type="hidden"
                                    :name="`schedules.${idx}.day`"
                                    :value="day"
                                />
                            </TableCell>
                            <TableCell class="w-1/3 align-top">
                                <TimePicker
                                    :name="`schedules.${idx}.start`"
                                    v-model="initSchedules[idx].start_time"
                                />
                                <InputError
                                    :message="errors[`schedules.${idx}.start`]"
                                    class="text-wrap"
                                />
                            </TableCell>
                            <TableCell class="w-1/3 align-top">
                                <TimePicker
                                    :name="`schedules.${idx}.end`"
                                    v-model="initSchedules[idx].end_time"
                                />
                                <InputError
                                    :message="errors[`schedules.${idx}.end`]"
                                    class="text-wrap"
                                />
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>

                <div v-if="canUpdate" class="text-end">
                    <Button>Save Schedules</Button>
                </div>
            </fieldset>
        </Form>
    </div>
</template>
