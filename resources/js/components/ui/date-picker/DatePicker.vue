<script setup lang="ts">
import { getLocalTimeZone, today, fromDate } from '@internationalized/date'
import { CalendarIcon } from '@lucide/vue'
import { computed } from 'vue'
import type { DateRange } from 'reka-ui' // shadcn uses reka-ui/radix-vue under the hood
import { Button } from '@/components/ui/button'
import { RangeCalendar } from '@/components/ui/range-calendar'
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/components/ui/popover'
import { cn } from '@/lib/utils'

// 1. Define custom type for native JS Date range
export interface JsDateRange {
  start: Date | undefined
  end: Date | undefined
}

// 2. Setup the model value using a standard JS structure
const modelValue = defineModel<JsDateRange>({
  default: () => ({ start: undefined, end: undefined })
})

const tz = getLocalTimeZone()

// 3. Dual-binding translation layer for the UI calendar wrapper
const calendarRange = computed({
  get(): DateRange {
    return {
      start: modelValue.value.start ? fromDate(modelValue.value.start, tz) : undefined,
      end: modelValue.value.end ? fromDate(modelValue.value.end, tz) : undefined
    }
  },
  set(newRange: DateRange) {
    modelValue.value = {
      start: newRange.start ? newRange.start.toDate(tz) : undefined,
      end: newRange.end ? newRange.end.toDate(tz) : undefined
    }
  }
})

// Helper function to render human-readable labels in the input button
const formatDisplayDate = (date: Date | undefined) => {
  if (!date) return ''
  return date.toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' })
}

const defaultPlaceholder = today(tz)
</script>

<template>
  <Popover>
    <PopoverTrigger as-child>
      <Button
        variant="outline"
        :class="cn(
          'w-[300px] justify-start text-left font-normal',
          (!modelValue.start && !modelValue.end) && 'text-muted-foreground',
        )"
      >
        <CalendarIcon class="mr-2 h-4 w-4" />
        <template v-if="modelValue.start">
          <template v-if="modelValue.end">
            {{ formatDisplayDate(modelValue.start) }} - {{ formatDisplayDate(modelValue.end) }}
          </template>
          <template v-else>
            {{ formatDisplayDate(modelValue.start) }}
          </template>
        </template>
        <template v-else>
          Pick a date range
        </template>
      </Button>
    </PopoverTrigger>
    <PopoverContent class="w-auto p-0" align="start">
      <RangeCalendar
        v-model="calendarRange"
        :initial-focus="true"
        :default-placeholder="defaultPlaceholder"
        :number-of-months="2"
      />
    </PopoverContent>
  </Popover>
</template>
