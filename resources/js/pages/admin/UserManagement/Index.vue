<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { Search } from '@lucide/vue';
import { useDebounceFn } from '@vueuse/core';
import { onMounted, ref } from 'vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { InputGroup, InputGroupAddon, InputGroupInput } from '@/components/ui/input-group';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { edit } from '@/routes/v2/admin/management';

defineProps<{
    users: {
        data: any[],
        from: number,
        to: number,
        total: number,
        next_page_url: string,
        prev_page_url: string,
    }
}>();

const urlParams = ref<URLSearchParams | null>(null);
onMounted(() => {
    urlParams.value = new URLSearchParams(window.location.search);
});

const search = ref(urlParams.value?.get('search') || '');
const filter = useDebounceFn(() => {
    router.reload({
        data: { search: search.value },
        except: ['search'],
    });
}, 200);

</script>

<template>
    <Head title="User Management" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl px-4 py-6">
        <Heading
            title="User Management"
            description="Manage registered users"
        />

        <div class="space-y-2">
            <InputGroup>
                <InputGroupInput
                    v-model="search"
                    @keyup="filter"
                    class="max-w-sm"
                    placeholder="Search..."
                />
                <InputGroupAddon>
                    <Search />
                </InputGroupAddon>
            </InputGroup>
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>No.</TableHead>
                        <TableHead>Name</TableHead>
                        <TableHead>Email</TableHead>
                        <TableHead class="text-center">Action</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-if="!users || users.data.length == 0">
                        <TableCell :colspan="4" class="text-center text-sm text-muted-foreground">
                            No users
                        </TableCell>
                    </TableRow>

                    <TableRow
                        v-for="(user, idx) in users.data"
                        :key="idx"
                    >
                        <TableCell>{{ idx + users.from }}</TableCell>
                        <TableCell class="text-muted-foreground">{{ user.name }}</TableCell>
                        <TableCell class="text-muted-foreground">{{ user.email }}</TableCell>
                        <TableCell class="text-center">
                            <Button
                                variant="link"
                                @click="router.get(edit(user.uuid))"
                            >
                                Edit
                            </Button>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
            <div class="flex items-center justify-end space-x-2">
                <div class="flex-1 text-sm text-muted-foreground">
                    Showing
                    {{ users?.from }} to
                    {{ users?.to }} of
                    {{ users?.total }} results
                </div>
                <div class="space-x-2">
                    <Button
                        size="sm"
                        variant="outline"
                        :disabled="!users?.prev_page_url"
                        @click="() => router.visit(users?.prev_page_url || '')"
                    >
                        Previous
                    </Button>
                    <Button
                        size="sm"
                        variant="outline"
                        :disabled="!users?.next_page_url"
                        @click="() => router.visit(users?.next_page_url || '')"
                    >
                        Next
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>
