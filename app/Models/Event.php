<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'body',
        'event_date',
        'event_time',
        'location',
        'banner_image',
        'category',
        'is_published',
        'featured',
        'meta_description',
    ];

    protected $casts = [
        'event_date' => 'date',
        'is_published' => 'boolean',
        'featured' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Auto-generate slug from title
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->title);
            }
        });

        static::updating(function ($event) {
            if ($event->isDirty('title') && empty($event->slug)) {
                $event->slug = Str::slug($event->title);
            }
        });
    }

    // Scope for published events
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    // Scope for upcoming events
    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now())->orderBy('event_date', 'asc');
    }

    // Scope for past events
    public function scopePast($query)
    {
        return $query->where('event_date', '<', now())->orderBy('event_date', 'desc');
    }

    // Scope for featured events
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }
}

