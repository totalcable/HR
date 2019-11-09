<?php

namespace App\Http\Controllers;

use App\AttendanceData;
use App\ShiftLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class PunchController extends Controller
{
    public function getEmpRosterAndPunches(Request $r){


        $array=array();

         $roster=ShiftLog::where('fkemployeeId',$r->empId)->where('startDate',$r->date)->where('endDate',$r->date)->get();

        $roster=collect($roster);

        $fromDate = Carbon::parse($r->date)->subDays(1);
        $toDate = Carbon::parse($r->date)->addDays(1);
        $currentDate = Carbon::parse($r->date);

        $results = DB::select(DB::raw("select em.employeeId,ad.id,sl.inTime,sl.outTime,sl.adjustmentDate,ad.fkAttDevice,sl.holiday,sl.weekend,ad.fkAttDevice
            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
            , date_format(ad.accessTime,'%H:%i:%s') accessTime
            , date_format(ad.accessTime,'%Y-%m-%d %H:%i:%s') accessTime2
            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
            and date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            
            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
            left join employeeinfo emInfo on em.employeeId = emInfo.id and emInfo.fkDepartmentId is not null
            
            where emInfo.id='" . $r->empId . "' and date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'"));

        $results = collect($results);


        if ($results->where('attendanceDate',$currentDate)->first()) {

            if ($results->where('attendanceDate',$currentDate)->first()->inTime == null){

                $newArray=array(
                    'date'=>$currentDate,
                    'fkEmployeeId'=>$allE->id,
                    'old_inTime'=>$ins,
                    'accessTime'=>$access,
                );
                array_push($array,$newArray);
            }

        }











    }
}
