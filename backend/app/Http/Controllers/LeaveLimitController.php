<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Leave;
use App\LeaveLimit;
use Illuminate\Http\Request;
use DB;
class LeaveLimitController extends Controller
{
    public function get(Request $r){
        $leaveLimit=LeaveLimit::where('fkemployeeId',$r->id)
            ->where('year',date('Y'))
            ->get();

        if($leaveLimit->isEmpty()){

            $leaveLimit=new LeaveLimit();
            $leaveLimit->fkemployeeId=$r->id;
            $leaveLimit->year=date('Y');
            $leaveLimit->save();

        }

        else{
            $leaveLimit=LeaveLimit::where('fkemployeeId',$r->id)
                ->where('year',date('Y'))
                ->first();

        }
        $leaveTaken=Leave::where('fkemployeeId',$r->id)
            ->where(DB::raw('YEAR(applicationDate)'),date('Y'))
//            ->where('applicationStatus','Approved')
//            ->whereIn('fkLeaveCategory',[1,2,5])
            ->where(function ($query) {
                $query->where('leaves.departmentHeadApproval', '!=', 0)
                    ->Where('leaves.departmentHeadApproval', '!=', null);
            })->where(function ($query) {
                $query->where('leaves.HR_adminApproval', '!=', 0)
                    ->Where('leaves.HR_adminApproval', '!=', null);
            })
            ->whereNotIn('fkLeaveCategory',[4])
            ->sum('noOfDays');


        return response()->json(['leaveTaken'=>$leaveTaken,'leaveLimit'=>$leaveLimit]);

    }
    public function getlimit(){

        $emp=Employee::where('fkUserId',auth()->user()->id)->first();

        $leaveLimit=LeaveLimit::where('fkemployeeId',$emp['id'])
            ->where('year',date('Y'))
            ->get();

        if($leaveLimit->isEmpty()){

            $leaveLimit=new LeaveLimit();
            $leaveLimit->fkemployeeId=$emp['id'];
            $leaveLimit->year=date('Y');
            $leaveLimit->save();

        }

        else{
            $leaveLimit=LeaveLimit::where('fkemployeeId',$emp['id'])
                ->where('year',date('Y'))
                ->first();

        }
        $leaveTaken=Leave::where('fkemployeeId',$emp['id'])
            ->where(DB::raw('YEAR(applicationDate)'),date('Y'))
//            ->where('applicationStatus','Approved')
//            ->whereIn('fkLeaveCategory',[1,2,5])
            ->where(function ($query) {
                $query->where('leaves.departmentHeadApproval', '!=', 0)
                    ->Where('leaves.departmentHeadApproval', '!=', null);
            })->where(function ($query) {
                $query->where('leaves.HR_adminApproval', '!=', 0)
                    ->Where('leaves.HR_adminApproval', '!=', null);
            })
            ->whereNotIn('fkLeaveCategory',[4])
            ->sum('noOfDays');


        return response()->json(['leaveTaken'=>$leaveTaken,'leaveLimit'=>$leaveLimit]);

    }

    public function post(Request $r){

        LeaveLimit::where('fkemployeeId',$r->id)
            ->where('year',date('Y'))
            ->update(['totalLeave'=>$r->totalLeave,'leaveTaken'=>$r->leaveTaken]);

    }
}
