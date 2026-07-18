<script setup lang="ts">
import { computed, ref } from 'vue';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Item, ItemContent, ItemGroup } from '@/components/ui/item';

const props = defineProps<{
    data: any;
}>();

const data = ref([
    {
        count: props.data.late,
        color: '#1C64F2',
        label: 'Late',
    },
    {
        count: props.data.early,
        color: '#16BDCA',
        label: 'Early',
    },
    {
        count: props.data.absence,
        color: '#FDBA8C',
        label: 'Absence',
    },
]);

const options = ref({
    colors: computed(() => data.value.map((item) => item.color)).value,
    chart: {
        type: 'radialBar',
        sparkline: {
            enabled: true,
        },
    },
    plotOptions: {
        radialBar: {
            dataLabels: {
                show: false,
            },
            hollow: {
                margin: 0,
                size: '40%',
            },
        },
    },
    grid: {
        show: false,
        strokeDashArray: 4,
        padding: {
            left: 2,
            right: 2,
            top: -23,
            bottom: -20,
        },
    },
    labels: computed(() => data.value.map((item) => item.label)).value,
    legend: {
        show: true,
        position: 'bottom',
    },
    tooltip: {
        enabled: true,
        x: {
            show: false,
        },
    },
    yaxis: {
        show: false,
    },
});

const count = computed(() => data.value.map((item) => item.count)).value;
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle>Late, early, and absence</CardTitle>
            <CardDescription
                >Daily count of user late, early, and absence.</CardDescription
            >
        </CardHeader>
        <CardContent>
            <ItemGroup class="grid grid-cols-3 gap-2 text-center">
                <Item
                    v-for="(d, idx) in data"
                    :key="idx"
                    :style="{ '--color': d.color }"
                    class="bg-(--color)/10 text-center text-(--color)"
                >
                    <ItemContent>
                        <p class="font-semibold">{{ d.count }}</p>
                        <p class="font-semibold">{{ d.label }}</p>
                    </ItemContent>
                </Item>
            </ItemGroup>
            <apexchart :options="options" :series="count"></apexchart>
        </CardContent>
    </Card>
</template>
