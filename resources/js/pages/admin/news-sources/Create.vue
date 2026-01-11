<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import InputError from '@/components/InputError.vue'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import AppLayout from '@/layouts/AppLayout.vue'
import NewsSourceController from '@/actions/App/Http/Controllers/Admin/NewsSourceController'
import { index } from '@/actions/App/Http/Controllers/Admin/NewsSourceController'
import type { BreadcrumbItem } from '@/types'
import { Form, Head, Link } from '@inertiajs/vue3'

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
        title: 'News Sources',
        href: index().url,
    },
    {
        title: 'Create',
        href: '#',
    },
]

const typeOptions = [
    { value: 'newspaper', label: 'Newspaper' },
    { value: 'tv', label: 'Television' },
    { value: 'radio', label: 'Radio' },
    { value: 'online', label: 'Online' },
    { value: 'blog', label: 'Blog' },
    { value: 'aggregator', label: 'Aggregator' },
]

const scopeOptions = [
    { value: 'hyperlocal', label: 'Hyperlocal' },
    { value: 'local', label: 'Local' },
    { value: 'regional', label: 'Regional' },
    { value: 'provincial', label: 'Provincial' },
    { value: 'national', label: 'National' },
]

const languageOptions = [
    { value: 'en', label: 'English' },
    { value: 'fr', label: 'French' },
    { value: 'bilingual', label: 'Bilingual' },
    { value: 'other', label: 'Other' },
]
</script>

<template>
    <Head title="Admin - Create News Source" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
            <div>
                <h1 class="text-3xl font-bold tracking-tight">Create News Source</h1>
                <p class="text-muted-foreground">
                    Add a new news source to the system
                </p>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>News Source Information</CardTitle>
                </CardHeader>
                <CardContent>
                    <Form
                        v-bind="NewsSourceController.store.form()"
                        class="space-y-6"
                        v-slot="{ errors, processing, recentlySuccessful }"
                    >
                        <div class="grid gap-2">
                            <Label for="name">Name <span class="text-destructive">*</span></Label>
                            <Input
                                id="name"
                                name="name"
                                required
                                placeholder="News source name"
                            />
                            <InputError :message="errors.name" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="url">URL <span class="text-destructive">*</span></Label>
                            <Input
                                id="url"
                                type="url"
                                name="url"
                                required
                                placeholder="https://example.com"
                            />
                            <InputError :message="errors.url" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="type">Type <span class="text-destructive">*</span></Label>
                            <select
                                id="type"
                                name="type"
                                required
                                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-xs transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                            >
                                <option value="">Select a type</option>
                                <option
                                    v-for="option in typeOptions"
                                    :key="option.value"
                                    :value="option.value"
                                >
                                    {{ option.label }}
                                </option>
                            </select>
                            <InputError :message="errors.type" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="scope">Scope <span class="text-destructive">*</span></Label>
                            <select
                                id="scope"
                                name="scope"
                                required
                                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-xs transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                            >
                                <option value="">Select a scope</option>
                                <option
                                    v-for="option in scopeOptions"
                                    :key="option.value"
                                    :value="option.value"
                                >
                                    {{ option.label }}
                                </option>
                            </select>
                            <InputError :message="errors.scope" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="language">Language <span class="text-destructive">*</span></Label>
                            <select
                                id="language"
                                name="language"
                                required
                                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-xs transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                            >
                                <option value="">Select a language</option>
                                <option
                                    v-for="option in languageOptions"
                                    :key="option.value"
                                    :value="option.value"
                                >
                                    {{ option.label }}
                                </option>
                            </select>
                            <InputError :message="errors.language" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="reliability_score">Reliability Score</Label>
                            <Input
                                id="reliability_score"
                                type="number"
                                name="reliability_score"
                                placeholder="0-100"
                                min="0"
                                max="100"
                            />
                            <InputError :message="errors.reliability_score" />
                        </div>

                        <div class="flex items-center gap-4">
                            <Button
                                type="submit"
                                :disabled="processing"
                            >
                                {{ processing ? 'Creating...' : 'Create News Source' }}
                            </Button>
                            <Link :href="index().url">
                                <Button variant="outline" type="button">Cancel</Button>
                            </Link>
                            <Transition
                                enter-active-class="transition ease-in-out"
                                enter-from-class="opacity-0"
                                leave-active-class="transition ease-in-out"
                                leave-to-class="opacity-0"
                            >
                                <p
                                    v-show="recentlySuccessful"
                                    class="text-sm text-neutral-600"
                                >
                                    News source created successfully.
                                </p>
                            </Transition>
                        </div>
                    </Form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
