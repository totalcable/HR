<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeType extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'employeetypes';
}
