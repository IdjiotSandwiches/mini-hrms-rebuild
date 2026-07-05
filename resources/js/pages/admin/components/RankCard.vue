<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

defineProps<{
    data: any,
    title: string
}>();

</script>

<template>
    <Card class="justify-center text-center">
        <CardHeader>
            <CardTitle>{{ title }}</CardTitle>
        </CardHeader>
        <CardContent>
            <slot v-if="data">
                <h4 class="scroll-m-20 text-xl font-semibold tracking-tight capitalize">{{ data.name }}</h4>
                <p class="text-sm text-muted-foreground">On Time Count: {{ data.count }}</p>
            </slot>
            <slot v-else>
                <h4 class="scroll-m-20 text-xl font-semibold tracking-tight capitalize">-</h4>
            </slot>
        </CardContent>
    </Card>
</template>
