<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'reference_id', 'title', 'subtitle', 'content', 'item_featured',
        'collection_date', 'permalink', 'image', 'author_username', 'author_email', 'author_first_name',
        'author_last_name', 'slug', 'parent', 'parent_slug', 'telephone', 'phone1', 'phone2',
        'email', 'contactOwnerBtn', 'web', 'webLinkLabel', 'address', 'latitude', 'longitude',
        'streetview', 'swheading', 'swpitch', 'swzoom', 'displayOpeningHours', 'openingHoursMonday',
        'openingHoursTuesday', 'openingHoursWednesday', 'openingHoursThursday', 'openingHoursFriday',
        'openingHoursSaturday', 'openingHoursSunday', 'openingHoursNote', 'displaySocialIcons',
        'socialIconsOpenInNewWindow', 'socialIcons', 'socialIcons_url', 'displayGallery', 'gallery'
    ];

    protected $dates = ['collection_date']; // Automatically cast 'collection_date' to DateTime

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'item_id', 'id');
    }

    public function opening_Time()
    {
        return $this->hasOne(Opening_Time::class, 'item_id', 'id');
    }

    public function socialIcons()
    {
        return $this->hasMany(SocialIcon::class, 'item_id', 'id');
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class, 'item_id', 'id');
    }

    public function getFormattedDateAttribute()
    {
        return $this->collection_date ? $this->collection_date->format('d-m-Y') : 'N/A';
    }
}
