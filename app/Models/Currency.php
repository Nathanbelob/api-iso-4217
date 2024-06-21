<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'number', 'decimal'];

    public function countries()
    {
        return $this->belongsToMany(Country::class, 'currencies_countries');
    }
}
