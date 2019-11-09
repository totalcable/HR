<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrganizationCalander extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'organizationcalander';
}
