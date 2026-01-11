<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import AppLayout from '@/layouts/AppLayout.vue'
import { index, edit, destroy } from '@/actions/App/Http/Controllers/Admin/UserController'
import type { BreadcrumbItem } from '@/types'
import type { User } from '@/types'
import { Head, Link, router, useForm } from '@inertiajs/vue3'

const props = defineProps<{
    user: User
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
    {
        title: props.user.name,
        href: '#',
    },
]

const deleteForm = useForm({})

const handleDelete = () => {
    if (confirm(`Are you sure you want to delete ${props.user.name}? This action cannot be undone.`)) {
        deleteForm.delete(destroy(props.user).url, {
            onSuccess: () => {
                router.visit(index().url)
            },
        })
    }
}
</script>

<template>
    <Head :title="`Admin - User: ${user.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">{{ user.name }}</h1>
                    <p class="text-muted-foreground">
                        User details and information
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <Link :href="edit(user).url">
                        <Button>Edit</Button>
                    </Link>
                    <Button
                        variant="destructive"
                        @click="handleDelete"
                    >
                        Delete
                    </Button>
                </div>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>User Information</CardTitle>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div class="grid gap-2">
                        <span class="text-sm font-medium text-muted-foreground">Name</span>
                        <p class="text-base">{{ user.name }}</p>
                    </div>

                    <div class="grid gap-2">
                        <span class="text-sm font-medium text-muted-foreground">Email</span>
                        <p class="text-base">{{ user.email }}</p>
                    </div>

                    <div class="grid gap-2">
                        <span class="text-sm font-medium text-muted-foreground">Email Verified</span>
                        <p class="text-base">
                            <span v-if="user.email_verified_at" class="text-green-600">
                                Verified on {{ new Date(user.email_verified_at).toLocaleDateString() }}
                            </span>
                            <span v-else class="text-muted-foreground">Not verified</span>
                        </p>
                    </div>

                    <div class="grid gap-2">
                        <span class="text-sm font-medium text-muted-foreground">Created At</span>
                        <p class="text-base">{{ new Date(user.created_at).toLocaleString() }}</p>
                    </div>

                    <div class="grid gap-2">
                        <span class="text-sm font-medium text-muted-foreground">Updated At</span>
                        <p class="text-base">{{ new Date(user.updated_at).toLocaleString() }}</p>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
