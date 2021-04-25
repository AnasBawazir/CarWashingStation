<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';

    protected $fillable = ['user_id','order_id','service_id','rate','comment','coworker_id'];

    protected $appends = ['user'];

    public function getUserAttribute()
    {
        return User::find($this->attributes['user_id']);
    }
}
