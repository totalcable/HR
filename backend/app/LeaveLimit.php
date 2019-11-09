<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveLimit extends Model
{
    protected $table = "leavelimit";
    protected $primaryKey='leavelimitId';
    public $timestamps = false;
}
