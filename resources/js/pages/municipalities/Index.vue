<script setup lang="ts">
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import AppLayout from '@/layouts/AppLayout.vue'
import { index, show } from '@/actions/App/Http/Controllers/MunicipalityController'
import type { BreadcrumbItem } from '@/types'
import type { Municipality, MunicipalityType, PaginatedResponse, Province } from '@/types/models'
import { Head, Link, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import { useDebounceFn } from '@vueuse/core'

const props = defineProps<{
    municipalities: PaginatedResponse<Municipality>
    provinces: Province[]
    types: MunicipalityType[]
    filters?: {
        search?: string
        province?: string
        type?: string
    }
}>()

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Municipalities',
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

const formatNumber = (num?: number) => {
    if (!num) return 'N/A'
    return new Intl.NumberFormat('en-CA').format(num)
}
</script>

<template>
    <Head title="Municipalities" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
            <div class="flex flex-col gap-4">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">Canadian Municipalities</h1>
                    <p class="text-muted-foreground">
                        Browse and explore municipalities across Canada
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <Input
                        v-model="search"
                        type="search"
                        placeholder="Search municipalities..."
                        class="max-w-sm"
                    />
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <Link
                    v-for="municipality in municipalities.data"
                    :key="municipality.id"
                    :href="show(municipality).url"
                    class="transition-all hover:scale-[1.02]"
                >
                    <Card class="h-full">
                        <CardHeader>
                            <div class="flex items-start justify-between gap-2">
                                <CardTitle class="text-xl">{{ municipality.name }}</CardTitle>
                                <Badge variant="outline">
                                    {{ municipality.province.code }}
                                </Badge>
                            </div>
                            <CardDescription v-if="municipality.name_fr">
                                {{ municipality.name_fr }}
                            </CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-muted-foreground">Type:</span>
                                <span class="font-medium">{{ municipality.municipality_type.name }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-muted-foreground">Population:</span>
                                <span class="font-medium">{{ formatNumber(municipality.population) }}</span>
                            </div>
                            <div v-if="municipality.news_sources_count !== undefined" class="flex items-center justify-between text-sm">
                                <span class="text-muted-foreground">News Sources:</span>
                                <Badge variant="secondary">{{ municipality.news_sources_count }}</Badge>
                            </div>
                        </CardContent>
                    </Card>
                </Link>
            </div>

            <div v-if="municipalities.data.length === 0" class="py-12 text-center">
                <p class="text-muted-foreground">No municipalities found.</p>
            </div>

            <div v-if="municipalities.meta.last_page > 1" class="flex items-center justify-center gap-2">
                <Button
                    v-if="municipalities.meta.current_page > 1"
                    variant="outline"
                    @click="router.visit(municipalities.links.prev)"
                >
                    Previous
                </Button>
                <span class="text-sm text-muted-foreground">
                    Page {{ municipalities.meta.current_page }} of {{ municipalities.meta.last_page }}
                </span>
                <Button
                    v-if="municipalities.meta.current_page < municipalities.meta.last_page"
                    variant="outline"
                    @click="router.visit(municipalities.links.next)"
                >
                    Next
                </Button>
            </div>
        </div>
    </AppLayout>
</template>
