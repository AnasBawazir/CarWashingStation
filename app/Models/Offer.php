<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $table = 'offers';

    protected $fillable = ['code','type','image','discount','service_id','category_id','start_date','end_date','description'];

    protected $appends = ['service','category','imagePath','completeImage'];

    public function getServiceAttribute()
    {
        $serviceIds = explode(',',$this->service_id);
        $service = [];
        foreach ($serviceIds as $serviceId)
        {
            array_push($service,Service::find($serviceId));
        }
        return $service;
    }

    public function getCategoryAttribute()
    {
        $categoryIds = explode(',',$this->category_id);
        $category = [];
        foreach ($categoryIds as $categoryId) {
            array_push($category,Category::find($categoryId));
        }
        return $category;
    }

    public function getImagePathAttribute()
    {
        return url('images/upload') . '/';
    }

    public function getCompleteImageAttribute()
    {
        return url('images/upload') . '/'.$this->attributes['image'];
    }
}
