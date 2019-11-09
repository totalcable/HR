<?php

namespace App\Http\Controllers;

use App\EmployeeType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmployeeTypeController extends Controller
{
    public function get(){
        $empType=EmployeeType::get();

        return $empType;
    }
}
