<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_id',
        'telephone',
        'phone1',
        'phone2',
        'email',
        'showemail',
        'contactOwnerBtn',
        'web',
        'webLinkLabel',
        'address',
        'latitude',
        'longitude',
        'streetview',
        'swheading',
        'swpitch',
        'swzoom',

    ];
     protected $casts = [
        'showemail' => 'boolean',
        'contactOwnerBtn' => 'boolean',
        'streetview' => 'boolean',
        'swheading' => 'integer',
        'swpitch' => 'integer',
        'swzoom' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Contact.php
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }



}
