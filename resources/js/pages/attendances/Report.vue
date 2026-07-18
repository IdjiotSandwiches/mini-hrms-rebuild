<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { Search } from '@lucide/vue';
import { onMounted, onUnmounted, ref } from 'vue';
import { toast } from 'vue-sonner';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import type { JsDateRange } from '@/components/ui/date-picker';
import { DatePicker } from '@/components/ui/date-picker';
import { getCurrentTime } from '@/lib/utils';
import type { ReportProps } from '@/pages/attendances/components';
import { ReportTable } from '@/pages/attendances/components';

defineProps<{
    ranged?: ReportProps;
    weekly: ReportProps;
    monthly: ReportProps;
}>();

const urlParams = ref<URLSearchParams | null>(null);
const date = ref({ day: '', hours: '00', minutes: '00', seconds: '00' });
let stopClock: () => void;

onMounted(() => {
    urlParams.value = new URLSearchParams(window.location.search);
    stopClock = getCurrentTime((timeData: any) => {
        date.value = timeData;
    });
});

onUnmounted(() => {
    if (stopClock) {
        stopClock();
    }
});

const dateWindow = ref<JsDateRange>({
    start: urlParams.value?.get('start')
        ? new Date(urlParams.value.get('start')!)
        : undefined,
    end: urlParams.value?.get('end')
        ? new Date(urlParams.value.get('end')!)
        : undefined,
});

function handleFilter() {
    if (!dateWindow.value.start || !dateWindow.value.end) {
        toast.warning('Please select both a start and end date.');

        return;
    }

    const toLocalYYYYMMDD = (date: Date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`;
    };

    const startStr = toLocalYYYYMMDD(dateWindow.value.start);
    const endStr = toLocalYYYYMMDD(dateWindow.value.end);

    router.reload({
        data: {
            start: startStr,
            end: endStr,
        },
        only: ['ranged'],
        except: ['start', 'end'],
    });
}
</script>

<template>
    <Head title="Attendance Report" />

    <div
        class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl px-4 py-6"
    >
        <Heading
            title="Report"
            description="This is where your weekly and monthly work report will be displayed"
        />

        <div class="space-y-8">
            <Heading
                title="Current Date"
                :description="`Today is ${date.day}. ${date.hours}:${date.minutes}:${date.seconds}`"
                variant="small"
            />

            <div class="space-y-2">
                <Heading
                    title="Custom Report"
                    description="Pick your own date"
                    variant="small"
                />
                <div class="flex gap-2">
                    <DatePicker v-model="dateWindow" />
                    <Button @click="handleFilter">
                        <Search />
                        View
                    </Button>
                </div>
                <ReportTable :data="ranged" />
            </div>

            <div>
                <Heading
                    title="Weekly Report"
                    description="This is your work report for the last 7 days"
                    variant="small"
                />
                <ReportTable :data="weekly" />
            </div>

            <div>
                <Heading
                    title="Monthly Report"
                    description="This is your work report for the last 30 days"
                    variant="small"
                />
                <ReportTable :data="monthly" />
            </div>
        </div>
    </div>
</template>
