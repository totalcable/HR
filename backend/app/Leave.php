<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'leaves';
}
