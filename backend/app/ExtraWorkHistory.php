<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExtraWorkHistory extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'extra_duty_history';
}
