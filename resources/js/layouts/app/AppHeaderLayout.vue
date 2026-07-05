<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppHeader from '@/components/AppHeader.vue';
import AppShell from '@/components/AppShell.vue';
import { Button } from '@/components/ui/button';
import { Toaster } from '@/components/ui/sonner';
import type { BreadcrumbItem } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { getAdminHome } from '@/lib/utils';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const page = usePage();
const auth = computed(() => page.props.auth);

</script>

<template>
    <AppShell variant="header">
        <AppHeader :breadcrumbs="breadcrumbs" />
        <AppContent variant="header">
            <slot />
        </AppContent>
        <Toaster :rich-colors="true" />

        <a class="fixed bottom-5 left-1/2 -translate-x-1/2 -translate-y-1/2" :href="getAdminHome(auth.can.admin).url">
            <Button class="bg-blue-600 dark:text-primary hover:bg-blue-600/80 shadow-lg/30">Go to Legacy Version</Button>
        </a>
    </AppShell>
</template>
