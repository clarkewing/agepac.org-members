<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'name',
        'street_line_1',
        'street_line_2',
        'municipality',
        'administrative_area',
        'sub_administrative_area',
        'postal_code',
        'country',
        'country_code',
    ];

    /**
     * Get the owning locatable model.
     */
    public function locatable()
    {
        return $this->morphTo();
    }
}
