<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttendanceData extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'attendancedata';
}
