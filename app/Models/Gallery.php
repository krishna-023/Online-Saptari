<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'image_url',
        'image_path',
        'display_gallery',
        'sort_order',
        'original_filename',
        'file_size',
        'file_extension',
        'download_status',
        'download_error',
        'downloaded_at',
    ];

    protected $casts = [
        'display_gallery' => 'boolean',
        'sort_order' => 'integer',
        'file_size' => 'integer',
        'downloaded_at' => 'datetime',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }

        return $this->image_url;
    }

    public function getIsDownloadedAttribute(): bool
    {
        return !empty($this->image_path) && $this->download_status === 'completed';
    }
}
