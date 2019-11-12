<?php

namespace App\Http\Controllers;

use App\AttendanceData;
use App\Duty;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DutyController extends Controller
{
    public function calculateDutyFromAttendance(Request $r){

     //   return $r->userId;

//        $fromDate = Carbon::parse($r->date)->subDays(1);
//        $toDate = Carbon::parse($r->date)->addDays(1);
        $currentDate = Carbon::parse($r->date);

        $currentDate=Carbon::now()->format('Y-m-d');

        $empINData = DB::select(DB::raw("select em.employeeId,ad.id,sl.inTime,sl.outTime,sl.adjustmentDate,ad.fkAttDevice,sl.holiday,sl.weekend,ad.fkAttDevice,sl.fkshiftId
            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
            , date_format(ad.accessTime,'%H:%i:%s') accessTime
            , date_format(ad.accessTime,'%Y-%m-%d %H:%i:%s') accessTime2
            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
            and date_format(ad.accessTime,'%Y-%m-%d') between '" . $currentDate . "' and '" . $currentDate . "'
            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
            left join employeeinfo emInfo on em.employeeId = emInfo.id and emInfo.fkDepartmentId is not null
            
            where  ad.attDeviceUserId='" . $r->userId . "' and ad.fkAttDevice=emInfo.inDeviceNo and date_format(ad.accessTime,'%Y-%m-%d') between '" . $currentDate . "' and '" . $currentDate . "'"));

       return $empINData = collect($empINData);

        $empout = DB::select(DB::raw("select em.employeeId,ad.id,sl.inTime,sl.outTime,sl.adjustmentDate,ad.fkAttDevice,sl.holiday,sl.weekend,ad.fkAttDevice,sl.fkshiftId
            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
            , date_format(ad.accessTime,'%H:%i:%s') accessTime
            , date_format(ad.accessTime,'%Y-%m-%d %H:%i:%s') accessTime2
            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
            and date_format(ad.accessTime,'%Y-%m-%d') between '" . $currentDate . "' and '" . $currentDate . "'
            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
            left join employeeinfo emInfo on em.employeeId = emInfo.id and emInfo.fkDepartmentId is not null
            
            where  ad.attDeviceUserId='" . $r->userId . "' and ad.fkAttDevice=emInfo.outDeviceNo and date_format(ad.accessTime,'%Y-%m-%d') between '" . $currentDate . "' and '" . $currentDate . "'"));

        $empout = collect($empout);


//        $empINData=DB::select(DB::raw("select ad.id,ad.fkAttDevice,ad.fkAttDevice,sl.fkshiftId
//            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
//            , date_format(ad.accessTime,'%H:%i:%s') accessTime
//            , date_format(ad.accessTime,'%Y-%m-%d %H:%i:%s') accessTime2
//            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
//            and date_format(ad.accessTime,'%Y-%m-%d') between '" . $currentDate . "' and '" . $currentDate . "'
//            left join employeeinfo emInfo on em.employeeId = emInfo.id and emInfo.fkDepartmentId is not null
//
//            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
//
//            where ad.attDeviceUserId='" . $r->userId . "'  and date_format(ad.accessTime,'%Y-%m-%d') between '" . $currentDate . "' and '" . $currentDate . "'"));
//
//        return $empINData = collect($empINData);

//        $empout  =DB::select(DB::raw("select ad.id,sl.inTime,sl.outTime,sl.adjustmentDate,ad.fkAttDevice,sl.holiday,sl.weekend,ad.fkAttDevice
//            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
//            , date_format(ad.accessTime,'%H:%i:%s') accessTime
//            , date_format(ad.accessTime,'%Y-%m-%d %H:%i:%s') accessTime2
//            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
//            and date_format(ad.accessTime,'%Y-%m-%d') between '" . $currentDate . "' and '" . $currentDate . "'
//            left join employeeinfo emInfo on em.employeeId = emInfo.id and emInfo.fkDepartmentId is not null
//
//            join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
//
//            where ad.attDeviceUserId='" . $r->userId . "' and emInfo.outDeviceNo=ad.fkAttDevice and date_format(ad.accessTime,'%Y-%m-%d') between '" . $currentDate . "' and '" . $currentDate . "'"));
//
//        $empout = collect($empout);

//
      foreach ($empINData as $resIN){

          try{

              $duty=new Duty();
              $duty->user_id=$r->userId;
              $duty->in_time=$resIN->accessTime;

              $duty->shift=$resIN->fkshiftId;

              $duty->device_id=$resIN->fkAttDevice;
              $duty->date=$currentDate;
              $duty->save();

          }catch (\Exception $exception){



          }




      }

      foreach ($empout as $resOut){

          try{

              $duty=new Duty();
              $duty->user_id=$r->userId;
              $duty->out_time=$resOut->accessTime;
              $duty->shift=$resOut->fkshiftId;
              $duty->device_id=$resOut->fkAttDevice;
              $duty->date=$currentDate;
              $duty->save();

          }catch (\Exception $exception){

          }




      }






    }
}
