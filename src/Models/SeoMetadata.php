<?php

namespace Salehye\Seo\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoMetadata extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'seo_metadata';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'model_type',
        'model_id',
        'model_key',
        'title',
        'description',
        'keywords',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'twitter_image',
        'canonical_url',
        'robots',
        'no_index',
        'no_follow',
        'schema_data',
        'og_data',
        'twitter_data',
        'additional_meta',
        'locale',
        'language',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'schema_data' => 'array',
        'og_data' => 'array',
        'twitter_data' => 'array',
        'additional_meta' => 'array',
        'no_index' => 'boolean',
        'no_follow' => 'boolean',
    ];

    /**
     * Get the parent model.
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include SEO data for a specific model.
     */
    public function scopeForModel($query, string $modelType, int $modelId, string $locale = null)
    {
        $query->where('model_type', $modelType)
              ->where('model_id', $modelId);
        
        if ($locale) {
            $query->where('locale', $locale);
        }
        
        return $query;
    }

    /**
     * Scope a query to only include SEO data for a specific key.
     */
    public function scopeForKey($query, string $key)
    {
        return $query->where('model_key', $key);
    }

    /**
     * Scope a query to only include SEO data for a specific locale.
     */
    public function scopeForLocale($query, string $locale)
    {
        return $query->where('locale', $locale);
    }

    /**
     * Scope a query to only include indexable SEO data.
     */
    public function scopeIndexable($query)
    {
        return $query->where('no_index', false)
                     ->where('robots', 'LIKE', '%index%');
    }

    /**
     * Get the full title with site name.
     */
    public function getFullTitleAttribute(): string
    {
        $title = $this->meta_title ?? $this->title;
        $siteName = config('seo.site_name');
        
        if (str_contains($title, '|')) {
            return $title;
        }
        
        return "{$title} | {$siteName}";
    }

    /**
     * Get the canonical URL.
     */
    public function getCanonicalUrlAttribute(): ?string
    {
        return $this->attributes['canonical_url'] ?? null;
    }

    /**
     * Get the OG image URL.
     */
    public function getOgImageUrlAttribute(): ?string
    {
        $image = $this->og_image ?? null;
        
        if (!$image) {
            return null;
        }
        
        if (filter_var($image, FILTER_VALIDATE_URL)) {
            return $image;
        }
        
        return asset($image);
    }

    /**
     * Get the Twitter image URL.
     */
    public function getTwitterImageUrlAttribute(): ?string
    {
        $image = $this->twitter_image ?? $this->og_image ?? null;
        
        if (!$image) {
            return null;
        }
        
        if (filter_var($image, FILTER_VALIDATE_URL)) {
            return $image;
        }
        
        return asset($image);
    }

    /**
     * Get the robots meta tag value.
     */
    public function getRobotsValueAttribute(): string
    {
        if ($this->no_index || $this->no_follow) {
            $robots = [];
            
            if ($this->no_index) {
                $robots[] = 'noindex';
            } else {
                $robots[] = 'index';
            }
            
            if ($this->no_follow) {
                $robots[] = 'nofollow';
            } else {
                $robots[] = 'follow';
            }
            
            return implode(', ', $robots);
        }
        
        return $this->robots ?? 'index, follow';
    }

    /**
     * Create or update SEO data for a model.
     */
    public static function createOrUpdateForModel(
        Model $model, 
        array $data, 
        string $locale = null
    ): self {
        $locale = $locale ?? app()->getLocale();
        
        return static::updateOrCreate(
            [
                'model_type' => get_class($model),
                'model_id' => $model->getKey(),
                'locale' => $locale,
            ],
            $data
        );
    }

    /**
     * Get SEO data for a model.
     */
    public static function forModel(
        Model $model, 
        string $locale = null
    ): ?self {
        $locale = $locale ?? app()->getLocale();
        
        return static::forModel(
            get_class($model), 
            $model->getKey(), 
            $locale
        )->first();
    }

    /**
     * Generate SEO data array for use in views.
     */
    public function toSeoArray(): array
    {
        return [
            'title' => $this->full_title,
            'description' => $this->meta_description ?? $this->description,
            'keywords' => $this->meta_keywords ?? $this->keywords,
            'canonical' => $this->canonical_url,
            'robots' => $this->robots_value,
            'image' => $this->og_image_url,
            'locale' => $this->locale,
            'structured_data' => $this->schema_data ?? [],
            'open_graph' => $this->og_data ?? [],
            'twitter_card' => $this->twitter_data ?? [],
            'additional_meta' => $this->additional_meta ?? [],
        ];
    }
}
