<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttEmployeeMap extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'attemployeemap';
    protected $fillable = [
        'attDeviceUserId', 'employeeId'
    ];
}
