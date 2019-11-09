<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $table = "shift";
    protected $primaryKey='shiftId';
    public $timestamps = false;
}
