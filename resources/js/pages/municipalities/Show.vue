<script setup lang="ts">
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Separator } from '@/components/ui/separator'
import AppLayout from '@/layouts/AppLayout.vue'
import { index, show } from '@/actions/App/Http/Controllers/MunicipalityController'
import type { BreadcrumbItem } from '@/types'
import type { Municipality } from '@/types/models'
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps<{
    municipality: Municipality
}>()

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Municipalities',
        href: index().url,
    },
    {
        title: props.municipality.name,
        href: show(props.municipality).url,
    },
]

const formatNumber = (num?: number) => {
    if (!num) return 'N/A'
    return new Intl.NumberFormat('en-CA').format(num)
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
</script>

<template>
    <Head :title="municipality.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
            <div class="flex items-start justify-between">
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-3xl font-bold tracking-tight">{{ municipality.name }}</h1>
                        <Badge variant="outline" class="text-sm">{{ municipality.province.code }}</Badge>
                    </div>
                    <p v-if="municipality.name_fr" class="text-lg text-muted-foreground">
                        {{ municipality.name_fr }}
                    </p>
                </div>
                <Link :href="index().url">
                    <Button variant="outline">
                        Back to Municipalities
                    </Button>
                </Link>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <Card>
                    <CardHeader>
                        <CardTitle>Basic Information</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="grid gap-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-muted-foreground">Type:</span>
                                <span class="font-medium">{{ municipality.municipality_type.name }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-muted-foreground">Province:</span>
                                <span class="font-medium">{{ municipality.province.name }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-muted-foreground">Population:</span>
                                <span class="font-medium">{{ formatNumber(municipality.population) }}</span>
                            </div>
                            <div v-if="municipality.population_year" class="flex items-center justify-between">
                                <span class="text-sm text-muted-foreground">Population Year:</span>
                                <span class="font-medium">{{ municipality.population_year }}</span>
                            </div>
                            <div v-if="municipality.area_sq_km" class="flex items-center justify-between">
                                <span class="text-sm text-muted-foreground">Area:</span>
                                <span class="font-medium">{{ formatNumber(municipality.area_sq_km) }} km²</span>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card v-if="municipality.latitude && municipality.longitude">
                    <CardHeader>
                        <CardTitle>Location</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="grid gap-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-muted-foreground">Latitude:</span>
                                <span class="font-mono text-sm font-medium">{{ municipality.latitude }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-muted-foreground">Longitude:</span>
                                <span class="font-mono text-sm font-medium">{{ municipality.longitude }}</span>
                            </div>
                            <div v-if="municipality.timezone" class="flex items-center justify-between">
                                <span class="text-sm text-muted-foreground">Timezone:</span>
                                <span class="font-medium">{{ municipality.timezone }}</span>
                            </div>
                            <div v-if="municipality.statcan_id" class="flex items-center justify-between">
                                <span class="text-sm text-muted-foreground">StatCan ID:</span>
                                <span class="font-mono text-sm font-medium">{{ municipality.statcan_id }}</span>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <Separator />

            <div>
                <div class="mb-4">
                    <h2 class="text-2xl font-bold tracking-tight">News Sources</h2>
                    <p class="text-muted-foreground">
                        {{ municipality.news_sources?.length || 0 }} news source(s) covering this municipality
                    </p>
                </div>

                <div v-if="municipality.news_sources && municipality.news_sources.length > 0" class="grid gap-4 md:grid-cols-2">
                    <Card v-for="source in municipality.news_sources" :key="source.id">
                        <CardHeader>
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex-1">
                                    <CardTitle class="text-lg">
                                        <a :href="source.url" target="_blank" rel="noopener noreferrer" class="hover:underline">
                                            {{ source.name }}
                                        </a>
                                    </CardTitle>
                                    <CardDescription class="mt-1">
                                        <a :href="source.url" target="_blank" rel="noopener noreferrer" class="text-xs hover:underline">
                                            {{ source.url }}
                                        </a>
                                    </CardDescription>
                                </div>
                                <Badge v-if="source.is_active" variant="default" class="shrink-0">Active</Badge>
                                <Badge v-else variant="outline" class="shrink-0">Inactive</Badge>
                            </div>
                        </CardHeader>
                        <CardContent class="space-y-3">
                            <div class="flex flex-wrap gap-2">
                                <Badge variant="secondary">{{ getTypeLabel(source.type) }}</Badge>
                                <Badge variant="secondary">{{ getScopeLabel(source.scope) }}</Badge>
                                <Badge variant="secondary">{{ getLanguageLabel(source.language) }}</Badge>
                            </div>
                            <div v-if="source.pivot" class="flex items-center justify-between text-sm">
                                <span class="text-muted-foreground">Coverage:</span>
                                <Badge variant="outline">{{ source.pivot.coverage_type }}</Badge>
                            </div>
                            <div v-if="source.reliability_score" class="flex items-center justify-between text-sm">
                                <span class="text-muted-foreground">Reliability Score:</span>
                                <span class="font-medium">{{ source.reliability_score }}/100</span>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <div v-else class="py-12 text-center">
                    <p class="text-muted-foreground">No news sources found for this municipality.</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
