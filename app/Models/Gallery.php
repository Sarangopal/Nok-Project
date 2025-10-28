<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'category',
        'year',
        'display_order',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'year' => 'integer',
        'display_order' => 'integer',
    ];

    // Scope for published items
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    // Scope for ordering
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('created_at', 'desc');
    }

    // Scope by category
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Get image URL
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            // Check if image is from new uploads (gallery/) or old hardcoded paths
            if (str_starts_with($this->image, 'gallery/')) {
                // New uploaded images go through storage link
                return asset('storage/' . $this->image);
            }
            // Old hardcoded images are in public directory
            return asset($this->image);
        }
        return asset('nokw/assets/img/project/default-placeholder.jpg');
    }
}

