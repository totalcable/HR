<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Shift;
use App\ShiftLog;
use App\StaticRosterLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class StaticRosterController extends Controller
{

    public function getStaticRosterInfo(Request $r){

//        return StaticRosterLog::select(
//
//            DB::raw("TRIM(BOTH '  ,  ' FROM GROUP_CONCAT(EmpDuty.id)) as EmpRosterIds"),
//            DB::raw("TRIM(BOTH '  ,' FROM GROUP_CONCAT(EmpOffDuty.id)) as EmpRosterOffDutyIds"),
//            DB::raw("TRIM(BOTH '  ,  ' FROM GROUP_CONCAT(COALESCE(EmpDuty.firstName,''),' ',COALESCE(EmpDuty.middleName,''),' ',COALESCE(EmpDuty.lastName,''))) as EmpRosterNames"),
//            DB::raw("TRIM(BOTH '  ,' FROM GROUP_CONCAT(COALESCE(EmpOffDuty.firstName,''),' ',COALESCE(EmpOffDuty.middleName,''),' ',COALESCE(EmpOffDuty.lastName,''))) as EmpRosterOffDutyNames")
//
//            ,'static_rosterlog.rosterLogId')
//            ->leftJoin('employeeinfo as EmpDuty',function($join) {
//                $join->on('EmpDuty.id', '=', 'static_rosterlog.fkemployeeId')
//                    ->whereNull('static_rosterlog.weekend');
//            })
//            ->leftJoin('employeeinfo as EmpOffDuty',function($join) {
//                $join->on('EmpOffDuty.id', '=', 'static_rosterlog.fkemployeeId')
//                    ->whereNotNull('static_rosterlog.weekend');
//            })
//            ->where('day',$r->day)
//            ->where('static_rosterlog.fkshiftId',$r->shiftId)
//
//            ->get();

        $dutyEmp= StaticRosterLog::select('static_rosterlog.fkemployeeId as EmployeeId',
            DB::raw("CONCAT(COALESCE(EmpDuty.firstName,''),' ',COALESCE(EmpDuty.middleName,''),' ',COALESCE(EmpDuty.lastName,'')) AS empFullname"))
            ->leftJoin('employeeinfo as EmpDuty',function($join) {
                $join->on('EmpDuty.id', '=', 'static_rosterlog.fkemployeeId')
                    ->whereNull('static_rosterlog.weekend');
            })
            ->where('day',$r->day)
            ->where('static_rosterlog.fkshiftId',$r->shiftId)
            ->whereNull('static_rosterlog.weekend')

            ->get();

        $offDutyEmp= StaticRosterLog::select('static_rosterlog.fkemployeeId as EmployeeId',
            DB::raw("CONCAT(COALESCE(EmpOffDuty.firstName,''),' ',COALESCE(EmpOffDuty.middleName,''),' ',COALESCE(EmpOffDuty.lastName,'')) AS empFullname"))
            ->leftJoin('employeeinfo as EmpOffDuty',function($join) {
                $join->on('EmpOffDuty.id', '=', 'static_rosterlog.fkemployeeId')
                    ->whereNotNull('static_rosterlog.weekend');
            })
            ->where('day',$r->day)
            ->where('static_rosterlog.fkshiftId',$r->shiftId)
            ->whereNotNull('static_rosterlog.weekend')

            ->get();

        $result=array(
            'duty'=> $dutyEmp,
            'Offduty'=> $offDutyEmp,
        );



        return $result;

    }
    public function setStaticRosterInfo(Request $r){

      //  return $r;


        $deletePreviousRoster=StaticRosterLog::select('rosterLogId')
//            ->where(function ($query) use($r){
//                $query->whereIn('fkemployeeId',$r->dutyempIds)
//                    ->orWhereIn('fkemployeeId',$r->offdutyempIds);
//            })
            ->where('day',$r->dayName)
           ->where('fkshiftId',($r->shiftId))
           ->get();

       foreach ($deletePreviousRoster as $dSR){
           StaticRosterLog::destroy($dSR->rosterLogId);
       }

        $findShift=Shift::findOrFail($r->shiftId);

        if (count($r->dutyempIds)>0){

            foreach ($r->dutyempIds as $empIds){

                $newStaticRoster=new StaticRosterLog();
                $newStaticRoster->fkemployeeId=$empIds['empid'];
                $newStaticRoster->fkshiftId=$r->shiftId;
                $newStaticRoster->day=$r->dayName;
                $newStaticRoster->inTime=$findShift->inTime;
                $newStaticRoster->outTime=$findShift->outTime;
                $newStaticRoster->save();

            }

        }
        if (count($r->offdutyempIds)>0){

            foreach ($r->offdutyempIds as $OffempIds){

                $newStaticRoster=new StaticRosterLog();

                $newStaticRoster->fkemployeeId=$OffempIds['empid'];
                $newStaticRoster->fkshiftId=$r->shiftId;
                $newStaticRoster->day=$r->dayName;
                $newStaticRoster->weekend=$r->dayName;
                $newStaticRoster->inTime=$findShift->inTime;
                $newStaticRoster->outTime=$findShift->outTime;
                $newStaticRoster->save();

            }

        }


    }
    public function getDataFromStaticRoster(Request $r){



//        return StaticRosterLog::select(
//
//            DB::raw("TRIM(BOTH '  ,  ' FROM GROUP_CONCAT(EmpDuty.id)) as EmpRosterIds"),
//            DB::raw("TRIM(BOTH '  ,' FROM GROUP_CONCAT(EmpOffDuty.id)) as EmpRosterOffDutyIds"),
//            DB::raw("TRIM(BOTH '  ,  ' FROM GROUP_CONCAT(COALESCE(EmpDuty.firstName,''),' ',COALESCE(EmpDuty.middleName,''),' ',COALESCE(EmpDuty.lastName,''))) as EmpRosterNames"),
//            DB::raw("TRIM(BOTH '  ,' FROM GROUP_CONCAT(COALESCE(EmpOffDuty.firstName,''),' ',COALESCE(EmpOffDuty.middleName,''),' ',COALESCE(EmpOffDuty.lastName,''))) as EmpRosterOffDutyNames")
//
//            ,'static_rosterlog.rosterLogId')
//            ->leftJoin('employeeinfo as EmpDuty',function($join) {
//                $join->on('EmpDuty.id', '=', 'static_rosterlog.fkemployeeId')
//                    ->whereNull('static_rosterlog.weekend');
//            })
//            ->leftJoin('employeeinfo as EmpOffDuty',function($join) {
//                $join->on('EmpOffDuty.id', '=', 'static_rosterlog.fkemployeeId')
//                    ->whereNotNull('static_rosterlog.weekend');
//            })
//            ->where('day',$r->day)
//            ->where('static_rosterlog.fkshiftId',$r->shiftId)
//
//            ->get();

         $dutyEmp= StaticRosterLog::select('static_rosterlog.fkemployeeId as EmployeeId',
            DB::raw("CONCAT(COALESCE(EmpDuty.firstName,''),' ',COALESCE(EmpDuty.middleName,''),' ',COALESCE(EmpDuty.lastName,'')) AS empFullname"))
            ->leftJoin('employeeinfo as EmpDuty',function($join) {
                $join->on('EmpDuty.id', '=', 'static_rosterlog.fkemployeeId')
                    ->whereNull('static_rosterlog.weekend');
            })
            ->where('day',Carbon::parse($r->date)->format('l'))
            ->where('static_rosterlog.fkshiftId',$r->shiftId)
            ->whereNull('static_rosterlog.weekend')

            ->get();

        $offDutyEmp= StaticRosterLog::select('static_rosterlog.fkemployeeId as EmployeeId',
            DB::raw("CONCAT(COALESCE(EmpOffDuty.firstName,''),' ',COALESCE(EmpOffDuty.middleName,''),' ',COALESCE(EmpOffDuty.lastName,'')) AS empFullname"))
            ->leftJoin('employeeinfo as EmpOffDuty',function($join) {
                $join->on('EmpOffDuty.id', '=', 'static_rosterlog.fkemployeeId')
                    ->whereNotNull('static_rosterlog.weekend');
            })
            ->where('day',Carbon::parse($r->date)->format('l'))
            ->where('static_rosterlog.fkshiftId',$r->shiftId)
            ->whereNotNull('static_rosterlog.weekend')

            ->get();

        $result=array(
          'duty'=> $dutyEmp,
          'Offduty'=> $offDutyEmp,
        );



        return $result;






    }
    public function setDepartmentWiseRosterByShift(Request $r){

        $dE=array();
        $ofdE=array();

        foreach ($r->dutyEmp as $e){
            array_push($dE,$e['empId']);
        }
        $List1 = implode(',', $dE);

        foreach ($r->offdutyEmp as $oE){
            array_push($ofdE,$oE['empId']);
        }
        $List2 = implode(',', $ofdE);




        $oldRoster=ShiftLog::where('startDate',$r->date)
            ->where('endDate',$r->date)
            ->where('fkshiftId',$r->shiftId)
//            ->where(function ($query) use ($List1,$List2) {
//                $query->whereIn('fkemployeeId', [$List1])
//                    ->orWhereIn('fkemployeeId', [$List2]);
//            })
            ->delete();



        $shift=Shift::findOrFail($r->shiftId);

        if (count($r->dutyEmp) > 0){

            foreach ($r->dutyEmp as $e){

                $rosterLog=new ShiftLog();

                $rosterLog->fkemployeeId=$e['empId'];
                $rosterLog->startDate=$r->date;
                $rosterLog->endDate=$r->date;
                $rosterLog->fkshiftId=$r->shiftId;

                $rosterLog->inTime=$shift['inTime'];
                $rosterLog->outTime=$shift['outTime'];

                $rosterLog->save();

            }

        }

        if (count($r->offdutyEmp)>0){

            foreach ($r->offdutyEmp as $oE){

                $rosterLog=new ShiftLog();

                $rosterLog->fkemployeeId=$oE['empId'];
                $rosterLog->startDate=$r->date;
                $rosterLog->endDate=$r->date;
                $rosterLog->weekend=$r->date;
                $rosterLog->fkshiftId=$r->shiftId;

                $rosterLog->inTime=$shift['inTime'];
                $rosterLog->outTime=$shift['outTime'];

                $rosterLog->save();

            }

        }



    }
    public function findDepartmentWiseRosterByShift(Request $r){

        //return $r;


//           return ShiftLog::select(
//
//            DB::raw("TRIM(BOTH '  ,  ' FROM GROUP_CONCAT(EmpDuty.id)) as EmpRosterIds"),
//            DB::raw("TRIM(BOTH '  ,' FROM GROUP_CONCAT(EmpOffDuty.id)) as EmpRosterOffDutyIds"),
//            DB::raw("TRIM(BOTH '  ,  ' FROM GROUP_CONCAT(COALESCE(EmpDuty.firstName,''),' ',COALESCE(EmpDuty.middleName,''),' ',COALESCE(EmpDuty.lastName,''))) as EmpRosterNames"),
//            DB::raw("TRIM(BOTH '  ,' FROM GROUP_CONCAT(COALESCE(EmpOffDuty.firstName,''),' ',COALESCE(EmpOffDuty.middleName,''),' ',COALESCE(EmpOffDuty.lastName,''))) as EmpRosterOffDutyNames")
//
//            ,'shiftlog.shiftlogId')
//            ->leftJoin('employeeinfo as EmpDuty',function($join) {
//                $join->on('EmpDuty.id', '=', 'shiftlog.fkemployeeId')
//                    ->whereNull('shiftlog.weekend');
//            })
//            ->leftJoin('employeeinfo as EmpOffDuty',function($join) {
//                $join->on('EmpOffDuty.id', '=', 'shiftlog.fkemployeeId')
//                    ->whereNotNull('shiftlog.weekend');
//            })
//            ->where('startDate',$r->date)
//            ->where('endDate',$r->date)
//            ->where('shiftlog.fkshiftId',$r->shiftId)
//
//            ->get();


        $dutyEmp= ShiftLog::select('shiftlog.fkemployeeId as EmployeeId',
            DB::raw("CONCAT(COALESCE(EmpDuty.firstName,''),' ',COALESCE(EmpDuty.middleName,''),' ',COALESCE(EmpDuty.lastName,'')) AS empFullname"))
            ->leftJoin('employeeinfo as EmpDuty',function($join) {
                $join->on('EmpDuty.id', '=', 'shiftlog.fkemployeeId')
                    ->whereNull('shiftlog.weekend');
            })
            ->where('startDate',$r->date)
            ->where('endDate',$r->date)
            ->where('shiftlog.fkshiftId',$r->shiftId)
            ->whereNull('shiftlog.weekend')

            ->get();

        $offDutyEmp= ShiftLog::select('shiftlog.fkemployeeId as EmployeeId',
            DB::raw("CONCAT(COALESCE(EmpOffDuty.firstName,''),' ',COALESCE(EmpOffDuty.middleName,''),' ',COALESCE(EmpOffDuty.lastName,'')) AS empFullname"))
            ->leftJoin('employeeinfo as EmpOffDuty',function($join) {
                $join->on('EmpOffDuty.id', '=', 'shiftlog.fkemployeeId')
                    ->whereNotNull('shiftlog.weekend');
            })
            ->where('startDate',$r->date)
            ->where('endDate',$r->date)
            ->where('shiftlog.fkshiftId',$r->shiftId)
            ->whereNotNull('shiftlog.weekend')

            ->get();

        $result=array(
            'duty'=> $dutyEmp,
            'Offduty'=> $offDutyEmp,
        );


        return $result;



    }

    public function getDataFromRoster(Request $r){

    }

}
