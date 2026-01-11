<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import InputError from '@/components/InputError.vue'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import AppLayout from '@/layouts/AppLayout.vue'
import MunicipalityController from '@/actions/App/Http/Controllers/Admin/MunicipalityController'
import { index, show } from '@/actions/App/Http/Controllers/Admin/MunicipalityController'
import type { BreadcrumbItem } from '@/types'
import type { Municipality, Province, MunicipalityType } from '@/types/models'
import { Form, Head, Link } from '@inertiajs/vue3'

const props = defineProps<{
    municipality: Municipality
    provinces: Province[]
    types: MunicipalityType[]
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
        title: 'Municipalities',
        href: index().url,
    },
    {
        title: props.municipality.name,
        href: show(props.municipality).url,
    },
    {
        title: 'Edit',
        href: '#',
    },
]
</script>

<template>
    <Head :title="`Admin - Edit Municipality: ${municipality.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
            <div>
                <h1 class="text-3xl font-bold tracking-tight">Edit Municipality</h1>
                <p class="text-muted-foreground">
                    Update municipality information
                </p>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Municipality Information</CardTitle>
                </CardHeader>
                <CardContent>
                    <Form
                        v-bind="MunicipalityController.update(municipality).form()"
                        class="space-y-6"
                        v-slot="{ errors, processing, recentlySuccessful }"
                    >
                        <div class="grid gap-2">
                            <Label for="name">Name <span class="text-destructive">*</span></Label>
                            <Input
                                id="name"
                                name="name"
                                :default-value="municipality.name"
                                required
                                placeholder="Municipality name"
                            />
                            <InputError :message="errors.name" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="name_fr">Name (French)</Label>
                            <Input
                                id="name_fr"
                                name="name_fr"
                                :default-value="municipality.name_fr || ''"
                                placeholder="Municipality name in French"
                            />
                            <InputError :message="errors.name_fr" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="province_id">Province <span class="text-destructive">*</span></Label>
                            <select
                                id="province_id"
                                name="province_id"
                                required
                                :value="municipality.province.id"
                                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-xs transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                            >
                                <option value="">Select a province</option>
                                <option
                                    v-for="province in provinces"
                                    :key="province.id"
                                    :value="province.id"
                                >
                                    {{ province.name }}
                                </option>
                            </select>
                            <InputError :message="errors.province_id" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="municipality_type_id">Municipality Type <span class="text-destructive">*</span></Label>
                            <select
                                id="municipality_type_id"
                                name="municipality_type_id"
                                required
                                :value="municipality.municipality_type.id"
                                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-xs transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                            >
                                <option value="">Select a type</option>
                                <option
                                    v-for="type in types"
                                    :key="type.id"
                                    :value="type.id"
                                >
                                    {{ type.name }}
                                </option>
                            </select>
                            <InputError :message="errors.municipality_type_id" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="statcan_id">StatCan ID</Label>
                            <Input
                                id="statcan_id"
                                name="statcan_id"
                                :default-value="municipality.statcan_id || ''"
                                placeholder="Statistics Canada ID"
                            />
                            <InputError :message="errors.statcan_id" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="population">Population</Label>
                            <Input
                                id="population"
                                type="number"
                                name="population"
                                :default-value="municipality.population || ''"
                                placeholder="Population"
                            />
                            <InputError :message="errors.population" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="population_year">Population Year</Label>
                            <Input
                                id="population_year"
                                type="number"
                                name="population_year"
                                :default-value="municipality.population_year || ''"
                                placeholder="Year"
                                min="1900"
                                max="2100"
                            />
                            <InputError :message="errors.population_year" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="latitude">Latitude</Label>
                            <Input
                                id="latitude"
                                type="number"
                                step="any"
                                name="latitude"
                                :default-value="municipality.latitude || ''"
                                placeholder="Latitude"
                            />
                            <InputError :message="errors.latitude" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="longitude">Longitude</Label>
                            <Input
                                id="longitude"
                                type="number"
                                step="any"
                                name="longitude"
                                :default-value="municipality.longitude || ''"
                                placeholder="Longitude"
                            />
                            <InputError :message="errors.longitude" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="area_sq_km">Area (sq km)</Label>
                            <Input
                                id="area_sq_km"
                                type="number"
                                step="any"
                                name="area_sq_km"
                                :default-value="municipality.area_sq_km || ''"
                                placeholder="Area in square kilometers"
                            />
                            <InputError :message="errors.area_sq_km" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="timezone">Timezone</Label>
                            <Input
                                id="timezone"
                                name="timezone"
                                :default-value="municipality.timezone || ''"
                                placeholder="Timezone"
                            />
                            <InputError :message="errors.timezone" />
                        </div>

                        <div class="flex items-center gap-4">
                            <Button
                                type="submit"
                                :disabled="processing"
                            >
                                {{ processing ? 'Updating...' : 'Update Municipality' }}
                            </Button>
                            <Link :href="show(municipality).url">
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
                                    Municipality updated successfully.
                                </p>
                            </Transition>
                        </div>
                    </Form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
