<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $table = 'languages';

    protected $fillable = ['name','file','image','direction','status'];}
