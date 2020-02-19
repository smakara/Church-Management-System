<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BuildCountry extends Model
{
    //
    protected $table = 'build_country';
    protected $casts = [
        'registered_at' => 'date',
    ];
}
