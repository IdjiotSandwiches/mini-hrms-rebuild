<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Head } from '@inertiajs/vue3';
import { ItemGroup } from '@/components/ui/item';
import { AttendanceChart, ExceptionChart, RankCard, WeeklyChart } from '@/pages/admin/components';

defineProps<{
    daily: any,
    weekly: any,
    rank: any
}>();

</script>

<template>
    <Head title="Dashboard" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl px-4 py-6">
        <Heading
            title="Dashboard"
            description="Review of registered users"
        />

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
            <ItemGroup class="grid grid-rows-2 gap-2 sm:col-span-2 lg:col-span-1">
                <RankCard :data="rank.onTime" title="Most On Time" />
                <RankCard :data="rank.absence" title="Most Absence" />
            </ItemGroup>
            <AttendanceChart :data="daily.attendances" />
            <ExceptionChart :data="daily.exceptions" />
            <WeeklyChart :data="weekly" class="sm:col-span-2 lg:col-span-3" />
        </div>
    </div>
</template>
