<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BuildMembers extends Model
{
    //
    protected $table = 'build_members';
    protected $casts = [
        'registered_at' => 'date',
    ];
}
