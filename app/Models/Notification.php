<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notification';

    protected $fillable = ['user_id','user_type','order_id','title','message'];

    protected $appends = ['user','order'];

    public function getUserAttribute()
    {
        // return User::find($this->user_id);
        return User::where('id',$this->user_id)->first(['name','image']);
    }

    public function getOrderAttribute()
    {
        // return Appointment::find($this->order_id)->date;
        return Appointment::where('id',$this->order_id)->first(['date','start_time']);
    }
}
