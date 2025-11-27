<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpeningTime extends Model
{
    use HasFactory;

    protected $table = 'opening_times';

    protected $fillable = [
        'item_id',
        'displayOpeningHours',
        'openingHoursMonday',
        'openingHoursTuesday',
        'openingHoursWednesday',
        'openingHoursThursday',
        'openingHoursFriday',
        'openingHoursSaturday',
        'openingHoursSunday',
        'openingHoursNote',
    ];

 protected $casts = [
        'displayOpeningHours' => 'boolean',
    ];
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
}
