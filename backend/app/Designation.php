<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'designations';
}
