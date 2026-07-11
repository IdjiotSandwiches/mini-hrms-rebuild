<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { destroy, update } from '@/routes/v2/admin/management';

defineProps<{
    user: any
}>();

</script>

<template>
    <Head title="User Management" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl px-4 py-6">
        <Heading
            title="User Management"
            description="Manage registered users"
        />

        <div class="space-y-8">
            <div class="space-y-2">
                <Heading
                    title="Edit User"
                    description="Edit user properties"
                    variant="small"
                />

                <Form
                    v-bind="update.form(user.uuid)"
                    v-slot="{ errors, processing }"
                    disable-while-processing
                >
                    <fieldset
                        :disabled="processing"
                        class="space-y-6"
                    >
                        <div class="grid w-full items-center gap-1.5">
                            <Label for="uuid">ID</Label>
                            <Input id="uuid" type="text" :placeholder="user.uuid" disabled />
                        </div>
                        <div class="grid w-full items-center gap-1.5">
                            <Label for="name">Name</Label>
                            <Input name="name" id="name" type="text" :placeholder="user.name" />
                            <InputError :message="errors['name']" />
                        </div>
                        <div class="grid w-full items-center gap-1.5">
                            <Label for="email">Email</Label>
                            <Input name="email" id="email" type="email" :placeholder="user.email" />
                            <InputError :message="errors['email']" />
                        </div>
                        <div class="grid w-full items-center gap-1.5">
                            <Label for="password">Password</Label>
                            <Input name="password" id="password" type="password" placeholder="Type new password" />
                            <InputError :message="errors['password']" />
                        </div>
                        <Button>Submit</Button>
                    </fieldset>
                </Form>
            </div>

            <div
                v-if="user.role !== 'admin'"
                class="space-y-2"
            >
                <Heading
                    title="Delete User"
                    description="Remove user from database"
                    variant="small"
                />

                <Form
                    v-bind="destroy.form(user.uuid)"
                    v-slot="{ errors, processing }"
                    disable-while-processing
                >
                    <fieldset
                        :disabled="processing"
                        class="space-y-6"
                    >
                        <div class="grid w-full items-center gap-1.5">
                            <Label for="password">Enter current administrative password to delete</Label>
                            <Input name="password" id="password" type="password" placeholder="Password" />
                            <InputError :message="errors['password']" />
                        </div>
                        <Button variant="destructive">Delete</Button>
                    </fieldset>
                </Form>
            </div>
        </div>
    </div>
</template>
