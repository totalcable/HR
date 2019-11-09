<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveCategory extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'leavecategories';
}
