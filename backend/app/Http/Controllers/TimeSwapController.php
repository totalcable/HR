<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Swap;
use App\TimeSwap;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use Auth;
use DB;

class TimeSwapController extends Controller
{
    public function getAllEmpTimeSwap(Request $r){

        $emp=Employee::select('fkDepartmentId','id')->where('fkUserId',Auth::user()->id)->first();


        $getAllTimeswapRequest=TimeSwap::select('designations.title as userDesignationTitle','time_swap.*',
            DB::raw("CONCAT(COALESCE(empinfo.firstName,''),' ',COALESCE(empinfo.middleName,''),' ',COALESCE(empinfo.lastName,'')) AS empFullname")
            )

            ->leftJoin('employeeinfo as empinfoUser',function($join) use($emp) {
                $join->where('empinfoUser.id', '=', $emp['id']);
            })

            ->leftJoin('designations','designations.id','empinfoUser.fkDesignation')

            ->leftJoin('employeeinfo as empinfo','empinfo.id','time_swap.fkEmployeeId');




        $datatables = Datatables::of($getAllTimeswapRequest);
        return $datatables->make(true);


    }

    public function acceptTimeSwap(Request $r)
    {
        $msg='';

        $emp=Employee::select('employeeinfo.fkDepartmentId','employeeinfo.id','employeeinfo.fkDesignation',
            'designations.title as designationTitle','departments.departmentName')
            ->leftJoin('departments','departments.id','employeeinfo.fkDepartmentId')
            ->leftJoin('designations','designations.id','employeeinfo.fkDesignation')
            ->where('fkUserId',Auth::user()->id)->first();


        $getSwapRequest=TimeSwap::findOrFail($r->id);




        if ($emp['designationTitle']==Swap_Accept_Access['Manager']){

            if ($getSwapRequest->departmentHeadApproval!=null)
            {
                $msg='You Already Approved This Req';
            }else{
                $getSwapRequest->departmentHeadApproval=$emp['id'];
                $getSwapRequest->save();
                $msg='Request Accepted';
            }



        }elseif ($emp['designationTitle']==Swap_Accept_Access['Hr']){

            if ($getSwapRequest->HR_adminApproval == null || $getSwapRequest->HR_adminApproval == '0'){

                if ($getSwapRequest->departmentHeadApproval!=null || $getSwapRequest->departmentHeadApproval!='0')
                {
                    $getSwapRequest->HR_adminApproval=$emp['id'];
                    $getSwapRequest->save();
                    $msg='Request Accepted';
                }else{
                    $msg='Department Head did not Approved this req Yet';
                }

            }else{
                $msg='You Already Approved This Req';
            }

        }


        return response()->json($msg);






    }

    public function rejectTimeSwap(Request $r)
    {
        $msg='';

        $emp=Employee::select('employeeinfo.fkDepartmentId','employeeinfo.id','employeeinfo.fkDesignation',
            'designations.title as designationTitle','departments.departmentName')
            ->leftJoin('departments','departments.id','employeeinfo.fkDepartmentId')
            ->leftJoin('designations','designations.id','employeeinfo.fkDesignation')
            ->where('fkUserId',Auth::user()->id)->first();


        $getSwapRequest=TimeSwap::findOrFail($r->id);




        if ($emp['designationTitle']==Swap_Accept_Access['Manager']) {

            if ($getSwapRequest->departmentHeadApproval == '0') {
                $msg = 'You Already Rejected This Req';
            } else {
                $getSwapRequest->departmentHeadApproval = 0;
                $getSwapRequest->save();
                $msg = 'Request Rejected';
            }

        }elseif ($emp['designationTitle']==Swap_Accept_Access['Hr']){

            if ($getSwapRequest->HR_adminApproval == null || $getSwapRequest->departmentHeadApproval != 0){

                if ($getSwapRequest->departmentHeadApproval!=null)
                {
                    $getSwapRequest->HR_adminApproval=0;
                    $getSwapRequest->save();
                    $msg='Request Rejrcted';
                }else{
                    $msg='Department Head did not Approved this req Yet';
                }

            }elseif($getSwapRequest->HR_adminApproval=='0'){
                $msg='You Already Rejected This Req';
            }

        }


        return response()->json($msg);



    }
}
