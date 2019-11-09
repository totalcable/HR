<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimeSwap extends Model
{
    protected $table = "time_swap";
    protected $primaryKey='id';
    public $timestamps = false;

    protected $fillable=['fkEmployeeId','date','accessTime','old_inTime'];
}
