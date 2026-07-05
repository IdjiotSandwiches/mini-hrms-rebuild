<script setup lang="ts">
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow
} from '@/components/ui/table';
import { router } from '@inertiajs/vue3';
import { convertDate, convertTime, formatSeconds, validateBoolean } from '@/lib/utils';
import { Button } from '@/components/ui/button';

export interface ReportProps {
    attendances: {
        data: any[],
        from: number,
        next_page_url: string,
        prev_page_url: string,
    };
    hours: number;
}

defineProps<{
    data?: ReportProps
}>();

</script>

<template>
    <div class="space-y-2">
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead class="text-center">No.</TableHead>
                    <TableHead class="text-center">Date</TableHead>
                    <TableHead class="text-center">Check In Time</TableHead>
                    <TableHead class="text-center">Check Out Time</TableHead>
                    <TableHead class="text-center">Early</TableHead>
                    <TableHead class="text-center">Late</TableHead>
                    <TableHead class="text-center">Absence</TableHead>
                    <TableHead class="text-center">Work Duration</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-if="!data || data.attendances.data.length == 0">
                    <TableCell :colspan="8" class="text-center text-sm text-muted-foreground">
                        You do not have work attendance
                    </TableCell>
                </TableRow>

                <TableRow
                    v-else
                    v-for="(item, idx) in data.attendances.data"
                    class="text-center"
                >
                    <TableCell>{{ idx + data.attendances.from }}</TableCell>
                    <TableCell>{{ convertDate(item.check_in) }}</TableCell>
                    <TableCell>{{ convertTime(item.check_in) }}</TableCell>
                    <TableCell>{{ convertTime(item.check_out) }}</TableCell>
                    <TableCell>{{ validateBoolean(item.early) }}</TableCell>
                    <TableCell>{{ validateBoolean(item.late) }}</TableCell>
                    <TableCell>{{ validateBoolean(item.absence) }}</TableCell>
                    <TableCell>{{ formatSeconds(item.duration) }}</TableCell>
                </TableRow>
            </TableBody>
        </Table>
        <div class="flex items-center justify-end space-x-2">
            <p class="text-sm text-muted-foreground antialiased flex-1">Total work hours: {{ data?.hours || 0 }} Hours</p>
            <div class="space-x-2">
                <Button
                    size="sm"
                    variant="outline"
                    :disabled="!data?.attendances.prev_page_url"
                    @click="() => router.visit(data?.attendances.prev_page_url || '')"
                >
                    Previous
                </Button>
                <Button
                    size="sm"
                    variant="outline"
                    :disabled="!data?.attendances.next_page_url"
                    @click="() => router.visit(data?.attendances.next_page_url || '')"
                >
                    Next
                </Button>
            </div>
        </div>
    </div>
</template>
