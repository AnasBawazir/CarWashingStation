<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';

    protected $fillable = ['service_name', 'image', 'category_id', 'coworker_id', 'price', 'duration', 'description', 'status'];

    protected $appends = ['category', 'rate' ,'imagePath','completeImage'];

    protected function getCategoryAttribute()
    {
        $categoryIds = explode(',', $this->category_id);
        $category = [];
        foreach ($categoryIds as $value) {
            array_push($category, Category::find($value));
        }
        return $category;
    }

    public function getRateAttribute()
    {
        $reviews = Review::all();
        $rIds = [];
        foreach ($reviews as $value)
        {
            $serviceId = explode(',',$value->service_id);
            if (($key = array_search($this->attributes['id'], $serviceId)) !== false)
            {
                array_push($rIds,$value->id);
            }
        }

        $Reviews = Review::whereIn('id',$rIds)->get();
        if (count($Reviews) > 0) {
            $totalRate = 0;
            foreach ($Reviews as $r)
            {
                $totalRate = $totalRate + $r->rate;
            }
            return round($totalRate / count($Reviews), 1);
        }
        else
        {
            return 0;
        }
    }

    // protected function getCoworkerAttribute()
    // {
    //     return Coworkers::find($this->coworker_id);
    // }

    public function Coworker()
    {
        return $this->belongsTo('App\Models\Coworkers','coworker_id');
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
