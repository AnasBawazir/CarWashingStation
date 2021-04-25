<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Coworkers extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'coworkers';

    protected $guard = 'coworker';

    protected $fillable = ['name','user_id','image','email','password','phone','start_time','end_time','experience','description','status'];

    protected $casts = [
        'experience' => 'integer',
    ];

    protected $hidden =
        [
            'password', 'remember_token',
        ];

    protected $appends = ['imagePath','completeImage','service','rate','review'];

    public function getServiceAttribute()
    {
        return Service::where('coworker_id',$this->attributes['id'])->get();
    }

    public function getRateAttribute()
    {
        $review = Review::where('coworker_id',$this->attributes['id'])->get();
        if (count($review) > 0) {
            $totalRate = 0;
            foreach ($review as $r)
            {
                $totalRate = $totalRate + $r->rate;
            }
            return round($totalRate / count($review), 1);
        }
        else
        {
            return 0;
        }
    }

    public function getReviewAttribute()
    {
        $reviews = Review::where('coworker_id',$this->attributes['id'])->get();
        foreach ($reviews as $review) {
            $review->date = Carbon::parse($review['created_at'])->format('d-m-Y');
        }
        return $reviews;
    }

    public function getImagePathAttribute()
    {
        return url('images/upload') . '/';
    }

    public function getCompleteImageAttribute()
    {
        return url('images/upload') . '/'.$this->attributes['image'];
    }

    public function Coworker()
    {
        return $this->hasMany('App\Coworkers','id');
    }
}
