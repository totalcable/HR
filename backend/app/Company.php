<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'companyinformation';
}
