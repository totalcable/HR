<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaticRosterLog extends Model
{
    public $timestamps = false;
    protected $table = "static_rosterlog";
    protected $primaryKey='rosterLogId';

}
