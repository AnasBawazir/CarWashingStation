<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['name','station_id','phone','image'];

    public function Station(){

        return $this->belongsTo('App/Models/Station');
    }

}
