<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WisataAnnualVisit extends Model
{
    protected $fillable = [
        'year',
        'total_visits',
    ];
}
