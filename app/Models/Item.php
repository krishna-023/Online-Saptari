<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'reference_id', 'title', 'subtitle', 'content',
        'item_featured', 'collection_date', 'permalink', 'image',
        'author_username', 'author_email', 'author_first_name', 'author_last_name',
        'slug', 'parent', 'parent_slug', 'status'
    ];

protected $casts = [
            'item_featured' => 'boolean',
    'collection_date' => 'datetime',
];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function contacts()
    {
        return $this->hasOne(Contact::class, 'item_id', 'id');
    }

    public function openingTimes()
    {
        return $this->hasOne(OpeningTime::class, 'item_id');
    }
    public function socialIcons()
    {
        return $this->hasMany(SocialIcon::class, 'item_id', 'id');
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class, 'item_id', 'id');
    }
public function getFeaturedImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        return asset('storage/' . $this->image);
    }
    public static function generateUniqueSlug($title, $id = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (self::where('slug', $slug)
            ->when($id, fn($q) => $q->where('id', '!=', $id))
            ->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    protected static function booted()
    {
        static::creating(function ($item) {
            $slug = self::generateUniqueSlug($item->title);
            $item->slug = $slug;
            $item->permalink = url('/items/' . $slug);
        });

        static::updating(function ($item) {
            if ($item->isDirty('title')) {
                $slug = self::generateUniqueSlug($item->title, $item->id);
                $item->slug = $slug;
                $item->permalink = url('/items/' . $slug);
            }
        });
    }
}
