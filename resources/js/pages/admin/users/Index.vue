<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import AppLayout from '@/layouts/AppLayout.vue'
import { index, create, edit, show, destroy } from '@/actions/App/Http/Controllers/Admin/UserController'
import type { BreadcrumbItem } from '@/types'
import type { User } from '@/types'
import type { PaginatedResponse } from '@/types/models'
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import { useDebounceFn } from '@vueuse/core'

const props = defineProps<{
    users: PaginatedResponse<User>
}>()

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Admin',
        href: '#',
    },
    {
        title: 'Users',
        href: index().url,
    },
]

const search = ref('')

const debouncedSearch = useDebounceFn((value: string) => {
    router.get(index({ query: { search: value || undefined } }).url, {}, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    })
}, 300)

watch(search, (value) => {
    debouncedSearch(value)
})

const deleteForm = useForm({})

const handleDelete = (user: User) => {
    if (confirm(`Are you sure you want to delete ${user.name}? This action cannot be undone.`)) {
        deleteForm.delete(destroy(user).url, {
            preserveScroll: true,
            onSuccess: () => {
                // Success handled by redirect
            },
        })
    }
}
</script>

<template>
    <Head title="Admin - Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
            <div class="flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight">Users</h1>
                        <p class="text-muted-foreground">
                            Manage system users
                        </p>
                    </div>
                    <Link :href="create().url">
                        <Button>Create User</Button>
                    </Link>
                </div>

                <div class="flex items-center gap-4">
                    <Input
                        v-model="search"
                        type="search"
                        placeholder="Search users..."
                        class="max-w-sm"
                    />
                </div>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>All Users</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="px-4 py-3 text-left text-sm font-medium">Name</th>
                                    <th class="px-4 py-3 text-left text-sm font-medium">Email</th>
                                    <th class="px-4 py-3 text-left text-sm font-medium">Verified</th>
                                    <th class="px-4 py-3 text-left text-sm font-medium">Created</th>
                                    <th class="px-4 py-3 text-right text-sm font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="user in users.data"
                                    :key="user.id"
                                    class="border-b transition-colors hover:bg-muted/50"
                                >
                                    <td class="px-4 py-3">
                                        <Link :href="show(user).url" class="font-medium hover:underline">
                                            {{ user.name }}
                                        </Link>
                                    </td>
                                    <td class="px-4 py-3">{{ user.email }}</td>
                                    <td class="px-4 py-3">
                                        <span v-if="user.email_verified_at" class="text-sm text-green-600">Verified</span>
                                        <span v-else class="text-sm text-muted-foreground">Not verified</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-muted-foreground">
                                        {{ new Date(user.created_at).toLocaleDateString() }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-end gap-2">
                                            <Link :href="edit(user).url">
                                                <Button variant="ghost" size="sm">Edit</Button>
                                            </Link>
                                            <Button
                                                variant="ghost"
                                                size="sm"
                                                class="text-destructive hover:text-destructive"
                                                @click="handleDelete(user)"
                                            >
                                                Delete
                                            </Button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="users.data.length === 0" class="py-12 text-center">
                        <p class="text-muted-foreground">No users found.</p>
                    </div>

                    <div v-if="users.meta.last_page > 1" class="mt-4 flex items-center justify-center gap-2">
                        <Button
                            v-if="users.meta.current_page > 1"
                            variant="outline"
                            @click="router.visit(users.links.prev)"
                        >
                            Previous
                        </Button>
                        <span class="text-sm text-muted-foreground">
                            Page {{ users.meta.current_page }} of {{ users.meta.last_page }}
                        </span>
                        <Button
                            v-if="users.meta.current_page < users.meta.last_page"
                            variant="outline"
                            @click="router.visit(users.links.next)"
                        >
                            Next
                        </Button>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
