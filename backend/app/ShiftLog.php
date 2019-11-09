<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShiftLog extends Model
{
    public $timestamps = false;
    protected $table = "shiftlog";
    protected $primaryKey='shiftlogId';
    protected $fillable =[
      'fkemployeeId','startDate','endDate', 'fkshiftId'
    ];

}
