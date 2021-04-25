<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoworkerPortfolio extends Model
{
    protected $table = 'coworker_portfolios';

    protected $fillable = ['coworker_id','image'];

    protected $appends = ['image'];

    public function getImageAttribute()
    {
        return url('images/upload') .'/'. $this->attributes['image'];
    }
}
