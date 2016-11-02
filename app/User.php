<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Winners;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];
    public function winner()
    {
        return $this->hasMany(Winners::class, 'user_id');
    }


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

}
