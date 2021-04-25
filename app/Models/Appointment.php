<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'appointments';

    protected $fillable = ['appointment_id','user_id','coworker_id','service_id','coupen_id','date','start_time','end_time','amount','payment_token','payment_type','address','lat','duration','lang','service_type','payment_status','appointment_status','discount'];

    protected $appends = ['user','coworker','service'];

    protected function getUserAttribute()
    {
        return User::where('id',$this->user_id)->first(['name','image']);
    }

    public function getCoworkerAttribute()
    {
        return Coworkers::where('id',$this->coworker_id)->first(['name','id','image']);
    }

    public function getServiceAttribute()
    {
        $serviceIds = explode(",",$this->service_id);
        $service = [];
        foreach ($serviceIds as $id)
        {
            array_push($service, Service::where('id',$id)->first(['id','service_name','duration','description','price','image']));
        }
        return $service;

        // $services = Service::where('id',explode(',',$this->attributes['service_id']))->get();
        // $service = [];
        // foreach ($services as $value) {
        //     array_push($service,Service::find($value->id));
        // }
        // return $service;
    }
}
