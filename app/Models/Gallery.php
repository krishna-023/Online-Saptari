<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_id',
        'displayGallery',
        'gallery',

    ];

    // Define the relationship with Category
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
}
