<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paylater extends Model
{
    protected $guarded = ['id'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    protected $casts = [
        'status' => 'array',
    ];
}
