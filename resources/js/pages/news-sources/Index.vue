<script setup lang="ts">
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import AppLayout from '@/layouts/AppLayout.vue'
import { index, show } from '@/actions/App/Http/Controllers/NewsSourceController'
import type { BreadcrumbItem } from '@/types'
import type { NewsSource, PaginatedResponse } from '@/types/models'
import { Head, Link, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import { useDebounceFn } from '@vueuse/core'

const props = defineProps<{
    newsSources: PaginatedResponse<NewsSource>
    filters?: {
        search?: string
        type?: string
        scope?: string
        language?: string
        active?: boolean
    }
}>()

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'News Sources',
        href: index().url,
    },
]

const search = ref(props.filters?.search || '')

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

const getTypeLabel = (type: string) => {
    const labels: Record<string, string> = {
        'newspaper': 'Newspaper',
        'tv': 'Television',
        'radio': 'Radio',
        'online': 'Online',
        'blog': 'Blog',
        'aggregator': 'Aggregator'
    }
    return labels[type] || type
}

const getScopeLabel = (scope: string) => {
    const labels: Record<string, string> = {
        'hyperlocal': 'Hyperlocal',
        'local': 'Local',
        'regional': 'Regional',
        'provincial': 'Provincial',
        'national': 'National'
    }
    return labels[scope] || scope
}

const getLanguageLabel = (lang: string) => {
    const labels: Record<string, string> = {
        'en': 'English',
        'fr': 'French',
        'bilingual': 'Bilingual',
        'other': 'Other'
    }
    return labels[lang] || lang
}
</script>

<template>
    <Head title="News Sources" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
            <div class="flex flex-col gap-4">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">News Sources</h1>
                    <p class="text-muted-foreground">
                        Browse news outlets covering Canadian municipalities
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <Input
                        v-model="search"
                        type="search"
                        placeholder="Search news sources..."
                        class="max-w-sm"
                    />
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <Link
                    v-for="newsSource in newsSources.data"
                    :key="newsSource.id"
                    :href="show(newsSource).url"
                    class="transition-all hover:scale-[1.02]"
                >
                    <Card class="h-full">
                        <CardHeader>
                            <div class="flex items-start justify-between gap-2">
                                <CardTitle class="text-xl">{{ newsSource.name }}</CardTitle>
                                <Badge v-if="newsSource.is_active" variant="default">Active</Badge>
                                <Badge v-else variant="outline">Inactive</Badge>
                            </div>
                            <CardDescription class="line-clamp-1">
                                <a :href="newsSource.url" target="_blank" rel="noopener noreferrer" class="hover:underline" @click.stop>
                                    {{ newsSource.url }}
                                </a>
                            </CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-3">
                            <div class="flex flex-wrap gap-2">
                                <Badge variant="secondary">{{ getTypeLabel(newsSource.type) }}</Badge>
                                <Badge variant="secondary">{{ getScopeLabel(newsSource.scope) }}</Badge>
                                <Badge variant="secondary">{{ getLanguageLabel(newsSource.language) }}</Badge>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-muted-foreground">Municipalities:</span>
                                <Badge variant="outline">{{ newsSource.municipalities_count || 0 }}</Badge>
                            </div>
                            <div v-if="newsSource.reliability_score" class="flex items-center justify-between text-sm">
                                <span class="text-muted-foreground">Reliability:</span>
                                <span class="font-medium">{{ newsSource.reliability_score }}/100</span>
                            </div>
                        </CardContent>
                    </Card>
                </Link>
            </div>

            <div v-if="newsSources.data.length === 0" class="py-12 text-center">
                <p class="text-muted-foreground">No news sources found.</p>
            </div>

            <div v-if="newsSources.meta.last_page > 1" class="flex items-center justify-center gap-2">
                <Button
                    v-if="newsSources.meta.current_page > 1"
                    variant="outline"
                    @click="router.visit(newsSources.links.prev)"
                >
                    Previous
                </Button>
                <span class="text-sm text-muted-foreground">
                    Page {{ newsSources.meta.current_page }} of {{ newsSources.meta.last_page }}
                </span>
                <Button
                    v-if="newsSources.meta.current_page < newsSources.meta.last_page"
                    variant="outline"
                    @click="router.visit(newsSources.links.next)"
                >
                    Next
                </Button>
            </div>
        </div>
    </AppLayout>
</template>
