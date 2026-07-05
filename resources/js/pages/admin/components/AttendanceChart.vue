<script setup lang="ts">
import { computed, ref } from 'vue';
import { Item, ItemContent, ItemGroup } from '@/components/ui/item';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';

const props = defineProps<{
    data: any
}>();

const data = ref([
    {
        count: props.data.check_in,
        color: '#1C64F2',
        label: 'Checked In'
    },
    {
        count: props.data.check_out,
        color: '#16BDCA',
        label: 'Checked Out'
    }
]);

const options = ref({
    colors: computed(() => data.value.map(item => item.color)).value,
    chart: {
        type: 'donut',
    },
    stroke: {
        colors: ['transparent'],
    },
    plotOptions: {
        pie: {
            donut: {
                labels: {
                    show: true,
                    name: {
                        show: true,
                        offsetY: 20,
                    },
                    total: {
                        showAlways: true,
                        show: true,
                        label: 'User active',
                        formatter: function (w: any) {
                            const val = w.globals.seriesTotals;
                            return val[0] - val[1];
                        },
                    },
                    value: {
                        show: true,
                        offsetY: -20,
                        formatter: (value: any) => value,
                    },
                },
                size: '85%',
            },
        },
    },
    grid: {
        padding: {
            top: -2,
        },
    },
    labels: computed(() => data.value.map(item => item.label)).value,
    dataLabels: {
        enabled: false,
    },
    legend: {
        position: 'bottom',
    },
    yaxis: {
        labels: {
            formatter: (value: any) => value,
        },
    },
    xaxis: {
        labels: {
            formatter: (value: any) => value,
        },
        axisTicks: { show: false },
        axisBorder: { show: false },
    },
});

const count = computed(() => data.value.map(item => item.count)).value;

</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle>Daily checked in & out</CardTitle>
            <CardDescription>Daily count of user check in & out.</CardDescription>
        </CardHeader>
        <CardContent>
            <ItemGroup class="grid grid-cols-2 gap-2 text-center">
                <Item
                    v-for="d in data"
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
