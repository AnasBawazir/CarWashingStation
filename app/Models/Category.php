<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = ['category_name','image','status'];

    protected $appends = ['imagePath','completeImage'];

    public function getImagePathAttribute()
    {
        return url('images/upload') . '/';
    }

    public function getCompleteImageAttribute()
    {
        return url('images/upload') . '/'.$this->attributes['image'];
    }
}
