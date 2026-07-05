<script setup lang="ts">
import { ref, watch, computed } from "vue";
import { Clock } from "@lucide/vue";
import { cn } from "@/lib/utils";
import { Button } from "@/components/ui/button";
import { Popover, PopoverContent, PopoverTrigger } from "@/components/ui/popover";
import { ScrollArea } from "@/components/ui/scroll-area";
import { Input } from "@/components/ui/input";

const props = withDefaults(
  defineProps<{
    modelValue?: string;
    name?: string;
  }>(),
  {
    modelValue: "00:00:00",
  }
);

const emit = defineEmits<{
  (e: "update:modelValue", value: string): void;
}>();

const isOpen = ref(false);

const parseIncomingTime = (val: string | undefined): string => {
  if (!val) return "--:--";
  const parts = val.split(":");
  return `${parts[0] || "00"}:${parts[1] || "00"}`;
};

const displayTime = ref<string>(parseIncomingTime(props.modelValue));

watch(() => props.modelValue, (newVal) => {
  displayTime.value = parseIncomingTime(newVal);
}, { immediate: true });

const formattedTimeValue = computed({
  get: () => displayTime.value,
  set: (newVal: string) => {
    if (newVal) updateTime(newVal);
  }
});

const hoursList = Array.from({ length: 24 }, (_, i) => i.toString().padStart(2, "0"));
const minutesList = Array.from({ length: 60 }, (_, i) => i.toString().padStart(2, "0"));

const getSelectedHour = () => displayTime.value.split(":")[0] || "";
const getSelectedMinute = () => displayTime.value.split(":")[1] || "";

const updateTime = (newHHmm: string) => {
  displayTime.value = newHHmm;
  emit("update:modelValue", `${newHHmm}:00`);
};

const handleTimeClick = (type: "hour" | "minute", val: string) => {
  let currentHour = getSelectedHour() || "12";
  let currentMinute = getSelectedMinute() || "00";

  if (type === "hour") {
    currentHour = val;
    currentMinute = "00"
  }
  if (type === "minute") currentMinute = val;

  updateTime(`${currentHour}:${currentMinute}`);
};
</script>

<template>
  <div class="flex items-center space-x-2 w-full">
    <input v-if="name" type="hidden" :name="name" :value="modelValue" />

    <Popover v-model:open="isOpen">
      <PopoverTrigger as-child>
        <Button variant="outline" :class="cn(
          'w-full justify-start text-left font-normal px-3',
          !displayTime && 'text-muted-foreground'
        )">
          <Clock class="mr-2 h-4 w-4 shrink-0" />
          <Input v-model="formattedTimeValue" type="time" class="border-none shadow-none focus-visible:ring-0" @click.stop />
        </Button>
      </PopoverTrigger>

      <PopoverContent class="w-auto p-0" align="start">
        <div class="flex h-[250px] divide-x">
          <ScrollArea class="w-16">
            <div class="flex flex-col p-1">
              <Button v-for="hour in hoursList" :key="hour" size="sm"
                :variant="getSelectedHour() === hour ? 'default' : 'ghost'" class="w-full shrink-0"
                @click="handleTimeClick('hour', hour)">
                {{ hour }}
              </Button>
            </div>
          </ScrollArea>

          <ScrollArea class="w-16">
            <div class="flex flex-col p-1">
              <Button v-for="minute in minutesList" :key="minute" size="sm"
                :variant="getSelectedMinute() === minute ? 'default' : 'ghost'" class="w-full shrink-0"
                @click="handleTimeClick('minute', minute)">
                {{ minute }}
              </Button>
            </div>
          </ScrollArea>
        </div>
      </PopoverContent>
    </Popover>
  </div>
</template>
