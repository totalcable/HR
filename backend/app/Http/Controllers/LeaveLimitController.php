<?php

namespace App\Http\Controllers;

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
            ->where('applicationStatus','Approved')
            ->whereIn('fkLeaveCategory',[1,2,5])
            ->sum('noOfDays');


        return response()->json(['leaveTaken'=>$leaveTaken,'leaveLimit'=>$leaveLimit]);

    }

    public function post(Request $r){

        LeaveLimit::where('fkemployeeId',$r->id)
            ->where('year',date('Y'))
            ->update(['totalLeave'=>$r->totalLeave,'leaveTaken'=>$r->leaveTaken]);

    }
}
