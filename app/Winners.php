<?php

namespace App;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Winners extends Model
{
    protected $table = 'winners';

    protected $guarded = [
        'id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
