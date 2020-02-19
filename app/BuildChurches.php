<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BuildChurches extends Model
{
    //
    protected $table = 'build_churches';
    protected $casts = [
        'registered_at' => 'date',
    ];
}
