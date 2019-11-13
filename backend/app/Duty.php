<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Duty extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'duty';

//    protected $fillable = [
//        'user_id'
//    ];
}
