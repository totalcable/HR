<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Mail\PasswordChange;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PasswordChangeController extends Controller
{
    public function changePasswordFromUser(Request $r){

        $user=User::findOrFail($r->userId);
        $user->password=Hash::make($r->new_password);
        $user->save();

        $newPass=$r->new_password;

       // $emp=Employee::where('fkUserId',$r->userId)->first();

        $emp=Employee::select('employeeinfo.fkDepartmentId','employeeinfo.id','employeeinfo.fkDesignation',
            'designations.title as designationTitle','departments.departmentName','attemployeemap.attDeviceUserId','employeeinfo.email',
            DB::raw("CONCAT(COALESCE(firstName,''),' ',COALESCE(middleName,''),' ',COALESCE(lastName,'')) AS empFullname"))
            ->leftJoin('departments','departments.id','employeeinfo.fkDepartmentId')
            ->leftJoin('designations','designations.id','employeeinfo.fkDesignation')
            ->leftJoin('attemployeemap','attemployeemap.employeeId','employeeinfo.id')
            ->where('fkUserId',$r->userId)->first();

        $otherReceipant=Employee::
        leftJoin('designations',function ($query) use($emp){

            $query->on('designations.id','=','employeeinfo.fkDesignation')
                ->where('fkDepartmentId',$emp['fkDepartmentId']);


        })->leftJoin('departments',function ($query) use($emp){

            $query->on('departments.id','=','employeeinfo.fkDepartmentId');



        })
            ->where(function ($query) {
                $query->where('departments.departmentName', '=', Leave_Accept_Access['Hr']);
            })



            ->get();

        Mail::to('faruk.totaltvs@gmail.com')->send(new PasswordChange($emp,$newPass));

        foreach ($otherReceipant as $oR){

            if ($oR['email']!='' && $oR['email']!= null){

                Mail::to($oR['email'])->send(new PasswordChange($emp,$newPass));

            }
        }


    }
    public function changePasswordFromAdmin(Request $r){


        $emp=Employee::select('employeeinfo.fkDepartmentId','employeeinfo.id','employeeinfo.fkDesignation','employeeinfo.fkUserId',
            'designations.title as designationTitle','departments.departmentName','attemployeemap.attDeviceUserId','employeeinfo.email',
            DB::raw("CONCAT(COALESCE(firstName,''),' ',COALESCE(middleName,''),' ',COALESCE(lastName,'')) AS empFullname"))
            ->leftJoin('departments','departments.id','employeeinfo.fkDepartmentId')
            ->leftJoin('designations','designations.id','employeeinfo.fkDesignation')
            ->leftJoin('attemployeemap','attemployeemap.employeeId','employeeinfo.id')

            ->where('employeeinfo.id',$r->empId)->first();

        $user=User::findOrFail($emp['fkUserId']);
        $user->password=Hash::make($r->new_password);
        $user->save();

        $newPass=$r->new_password;


        $otherReceipant=Employee::
        leftJoin('designations',function ($query) use($emp){

            $query->on('designations.id','=','employeeinfo.fkDesignation')
                ->where('fkDepartmentId',$emp['fkDepartmentId']);


        })->leftJoin('departments',function ($query) use($emp){

            $query->on('departments.id','=','employeeinfo.fkDepartmentId');



        })
            ->where(function ($query) {
                $query->where('departments.departmentName', '=', Leave_Accept_Access['Hr']);
            })



            ->get();

        Mail::to('faruk.totaltvs@gmail.com')->send(new PasswordChange($emp,$newPass));


        foreach ($otherReceipant as $oR){

            if ($oR['email']!='' && $oR['email']!= null){

                Mail::to($oR['email'])->send(new PasswordChange($emp,$newPass));

            }
        }


    }
}
