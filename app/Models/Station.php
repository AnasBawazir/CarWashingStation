<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{

    protected $table = 'stations';

    protected $fillable =
        [
           'id','name','user_id','image','email','password','phone','Location','description','status'
        ];
    protected $hidden =
        [
            'password', 'remember_token',
        ];

    public function Employee(){

        return $this->hasMany('App/Models/Employee');
    }

}
