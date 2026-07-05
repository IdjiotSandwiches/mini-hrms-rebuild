<script setup lang="ts">
import { computed, ref } from 'vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';

const props = defineProps<{
    data: any
}>();

const series = computed(() => {
    const values = Object.values(props.data);
    return [
        {
            name: "Attendance",
            data: values.map((day: any) => day.attendance)
        },
        {
            name: "Late",
            data: values.map((day: any) => day.late)
        },
        {
            name: "Early",
            data: values.map((day: any) => day.early)
        },
        {
            name: "Absence",
            data: values.map((day: any) => day.absence)
        }
    ];
});


const options = ref({
    chart: {
        type: 'area'
    },
    tooltip: {
        enabled: true,
        x: {
            show: false,
        },
    },
    dataLabels: {
        enabled: false,
    },
    legend: {
        show: true,
        position: 'bottom',
    },
    xaxis: {
        floating: false,
        labels: {
            show: true,
        },
        axisBorder: {
            show: true,
        },
        axisTicks: {
            show: false,
        },
        categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
    },
    yaxis: {
        show: true,
    },
    fill: {
        type: "gradient",
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.5,
            opacityTo: 0.6,
            stops: [0, 90, 100]
        }
    },
    stroke: {
        width: 2
    },
});

</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle>Weekly Attendance</CardTitle>
            <CardDescription>Weekly chart of users performance.</CardDescription>
        </CardHeader>
        <CardContent>
            <apexchart height="400" :options="options" :series="series"></apexchart>
        </CardContent>
    </Card>
</template>
