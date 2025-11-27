<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialIcon extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_id',
        'displaySocialIcons',
        'socialIconsOpenInNewWindow',
        'socialIcons',
        'socialIcons_url',
    ];
 protected $casts = [
        'displaySocialIcons' => 'boolean',
        'socialIconsOpenInNewWindow' => 'boolean',
    ];
    // Define the relationship with Category
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
}
