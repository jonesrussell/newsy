<script setup lang="ts">
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Separator } from '@/components/ui/separator'
import AppLayout from '@/layouts/AppLayout.vue'
import { index, show } from '@/actions/App/Http/Controllers/NewsSourceController'
import { show as showMunicipality } from '@/actions/App/Http/Controllers/MunicipalityController'
import type { BreadcrumbItem } from '@/types'
import type { NewsSource } from '@/types/models'
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps<{
    newsSource: NewsSource
}>()

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'News Sources',
        href: index().url,
    },
    {
        title: props.newsSource.name,
        href: show(props.newsSource).url,
    },
]

const formatNumber = (num?: number) => {
    if (!num) return 'N/A'
    return new Intl.NumberFormat('en-CA').format(num)
}

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

const getDiscoveryMethodLabel = (method: string) => {
    const labels: Record<string, string> = {
        'manual': 'Manual Entry',
        'scraped': 'Web Scraping',
        'api': 'API Integration',
        'user_submitted': 'User Submitted'
    }
    return labels[method] || method
}

const formatDate = (date?: string) => {
    if (!date) return 'Never'
    return new Date(date).toLocaleDateString('en-CA', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}
</script>

<template>
    <Head :title="newsSource.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3">
                        <h1 class="text-3xl font-bold tracking-tight">{{ newsSource.name }}</h1>
                        <Badge v-if="newsSource.is_active" variant="default">Active</Badge>
                        <Badge v-else variant="outline">Inactive</Badge>
                    </div>
                    <p class="mt-2 text-lg text-muted-foreground">
                        <a :href="newsSource.url" target="_blank" rel="noopener noreferrer" class="hover:underline">
                            {{ newsSource.url }}
                        </a>
                    </p>
                </div>
                <Link :href="index().url">
                    <Button variant="outline">
                        Back to News Sources
                    </Button>
                </Link>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <Card>
                    <CardHeader>
                        <CardTitle>Source Information</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="grid gap-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-muted-foreground">Type:</span>
                                <Badge variant="secondary">{{ getTypeLabel(newsSource.type) }}</Badge>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-muted-foreground">Scope:</span>
                                <Badge variant="secondary">{{ getScopeLabel(newsSource.scope) }}</Badge>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-muted-foreground">Language:</span>
                                <Badge variant="secondary">{{ getLanguageLabel(newsSource.language) }}</Badge>
                            </div>
                            <div v-if="newsSource.reliability_score" class="flex items-center justify-between">
                                <span class="text-sm text-muted-foreground">Reliability Score:</span>
                                <span class="font-medium">{{ newsSource.reliability_score }}/100</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-muted-foreground">Discovery Method:</span>
                                <span class="text-sm font-medium">{{ getDiscoveryMethodLabel(newsSource.discovery_method) }}</span>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Verification Status</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="grid gap-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-muted-foreground">Status:</span>
                                <Badge v-if="newsSource.is_active" variant="default">Active</Badge>
                                <Badge v-else variant="destructive">Inactive</Badge>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-muted-foreground">Last Verified:</span>
                                <span class="text-sm font-medium">{{ formatDate(newsSource.last_verified_at) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-muted-foreground">Created:</span>
                                <span class="text-sm font-medium">{{ formatDate(newsSource.created_at) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-muted-foreground">Updated:</span>
                                <span class="text-sm font-medium">{{ formatDate(newsSource.updated_at) }}</span>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <Separator />

            <div>
                <div class="mb-4">
                    <h2 class="text-2xl font-bold tracking-tight">Coverage Area</h2>
                    <p class="text-muted-foreground">
                        {{ newsSource.municipalities?.length || 0 }} municipalities covered by this news source
                    </p>
                </div>

                <div v-if="newsSource.municipalities && newsSource.municipalities.length > 0" class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <Link
                        v-for="municipality in newsSource.municipalities"
                        :key="municipality.id"
                        :href="showMunicipality(municipality).url"
                        class="transition-all hover:scale-[1.02]"
                    >
                        <Card class="h-full">
                            <CardHeader>
                                <div class="flex items-start justify-between gap-2">
                                    <CardTitle class="text-lg">{{ municipality.name }}</CardTitle>
                                    <Badge variant="outline">{{ municipality.province.code }}</Badge>
                                </div>
                                <CardDescription v-if="municipality.name_fr">
                                    {{ municipality.name_fr }}
                                </CardDescription>
                            </CardHeader>
                            <CardContent class="space-y-2">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-muted-foreground">Type:</span>
                                    <span class="text-sm font-medium">{{ municipality.municipality_type.name }}</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-muted-foreground">Population:</span>
                                    <span class="text-sm font-medium">{{ formatNumber(municipality.population) }}</span>
                                </div>
                                <div v-if="municipality.pivot" class="flex items-center justify-between text-sm">
                                    <span class="text-muted-foreground">Coverage:</span>
                                    <Badge variant="outline" class="text-xs">{{ municipality.pivot.coverage_type }}</Badge>
                                </div>
                            </CardContent>
                        </Card>
                    </Link>
                </div>

                <div v-else class="py-12 text-center">
                    <p class="text-muted-foreground">This news source is not currently associated with any municipalities.</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
