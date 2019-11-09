<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Swap extends Model
{
    protected $table = "swap_details";
    protected $primaryKey='id';
    public $timestamps = false;

    protected $fillable = ['inTime', 'outTime'];
}
