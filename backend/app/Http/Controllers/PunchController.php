<?php

namespace App\Http\Controllers;

use App\AttEmployeeMap;
use App\AttendanceData;
use App\Employee;
use App\ShiftLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class PunchController extends Controller
{
    public function getEmpRosterAndPunches(Request $r)
    {
        return $roster=ShiftLog::where('fkemployeeId',$r->empId)->where('startDate',$r->date)->where('endDate',$r->date)->get();



//        $array=array();
//
//         $roster=ShiftLog::where('fkemployeeId',$r->empId)->where('startDate',$r->date)->where('endDate',$r->date)->get();
//
//        $roster=collect($roster);
//
//        $fromDate = Carbon::parse($r->date)->subDays(1);
//        $toDate = Carbon::parse($r->date)->addDays(1);
//        $currentDate = Carbon::parse($r->date);
//
//        $results = DB::select(DB::raw("select em.employeeId,ad.id,sl.inTime,sl.outTime,sl.adjustmentDate,ad.fkAttDevice,sl.holiday,sl.weekend,ad.fkAttDevice
//            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
//            , date_format(ad.accessTime,'%H:%i:%s') accessTime
//            , date_format(ad.accessTime,'%Y-%m-%d %H:%i:%s') accessTime2
//            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
//            and date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
//
//            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
//            left join employeeinfo emInfo on em.employeeId = emInfo.id and emInfo.fkDepartmentId is not null
//
//            where emInfo.id='" . $r->empId . "' and date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'"));
//
//        $results = collect($results);
//
//
//        if ($results->where('attendanceDate',$currentDate)->first()) {
//
//            if ($results->where('attendanceDate',$currentDate)->first()->inTime == null){
//
//                $newArray=array(
//                    'date'=>$currentDate,
//                    'fkEmployeeId'=>$allE->id,
//                    'old_inTime'=>$ins,
//                    'accessTime'=>$access,
//                );
//                array_push($array,$newArray);
//            }
//
//        }


    }
    public function addPunches(Request $r)
    {

        $indateTime=Carbon::createFromTimestamp(strtotime($r->dateFormate.$r->inTime))->subHours(2);

        $outdateTime=Carbon::createFromTimestamp(strtotime($r->dateFormate.$r->outTime))->addHours(2);
        $dateTime=Carbon::createFromTimestamp(strtotime($r->dateFormate.$r->timeFormate.":00"));

        if ($r->deviceChk=='in'){

            if ($dateTime>=$indateTime ){

                $emp=AttEmployeeMap::where('employeeId',$r->empId)->first();

                try {

                    $punch = new AttendanceData();
                    $punch->attDeviceUserId = $emp['attDeviceUserId'];

                    $punch->accessTime = $dateTime;
                    $punch->fkAttDevice = $r->deviceNumber;
                    $punch->save();

                    return 1;
                }
                catch(\Exception $e){

                   // $msg= $e->getMessage();

                    $msg= 'Allready there was a punch on this time for this employee';

                    return response()->json($msg);
                    }

            }else{
                $msg='In Time must be maximum 2 hours before roster time';

                return response()->json($msg);
            }

        }elseif ($r->deviceChk=='out'){

            if ($dateTime<=$outdateTime ){

                $emp=AttEmployeeMap::where('employeeId',$r->empId)->first();

                try {

                    $punch = new AttendanceData();
                    $punch->attDeviceUserId = $emp['attDeviceUserId'];

                    $punch->accessTime = $dateTime;
                    $punch->fkAttDevice = $r->deviceNumber;
                    $punch->save();
                    return 1;
                }
                catch(\Exception $e){

                    //$e->getMessage();
                    $msg= 'Allready there was a punch on this time for this employee';
                    return response()->json($msg);
                }

            }else{
                $msg='out Time must be maximum 2 hours after roster time';

                return response()->json($msg);
            }

        }







    }
    public function getEmployeeINandOUTdevice(Request $r){
        return $empInOutDevice=Employee::select('inDeviceNo','outDeviceNo')->findOrFail($r->empId);
    }
}
