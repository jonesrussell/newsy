export interface Province {
  id: number
  code: string
  name: string
  name_fr?: string
}

export interface MunicipalityType {
  id: number
  slug: string
  name: string
  name_fr?: string
  description?: string
}

export interface Municipality {
  id: number
  statcan_id?: string
  name: string
  name_fr?: string
  slug: string
  municipality_type: MunicipalityType
  province: Province
  population?: number
  population_year?: number
  latitude?: number
  longitude?: number
  area_sq_km?: number
  timezone?: string
  data_source: string
  verified_at?: string
  news_sources?: NewsSource[]
  news_sources_count?: number
  created_at: string
  updated_at: string
}

export interface NewsSource {
  id: number
  name: string
  slug: string
  url: string
  type: 'newspaper' | 'tv' | 'radio' | 'online' | 'blog' | 'aggregator'
  scope: 'hyperlocal' | 'local' | 'regional' | 'provincial' | 'national'
  language: 'en' | 'fr' | 'bilingual' | 'other'
  reliability_score?: number
  is_active: boolean
  last_verified_at?: string
  discovery_method: 'manual' | 'scraped' | 'api' | 'user_submitted'
  municipalities_count?: number
  pivot?: {
    coverage_type: 'primary' | 'secondary' | 'occasional'
    verified_at?: string
    notes?: string
  }
  created_at: string
  updated_at: string
}

export interface PaginatedResponse<T> {
  data: T[]
  links: {
    first: string
    last: string
    prev?: string
    next?: string
  }
  meta: {
    current_page: number
    from: number
    last_page: number
    path: string
    per_page: number
    to: number
    total: number
  }
}
