<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GovtHoliday extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'holiday_calander';
}
