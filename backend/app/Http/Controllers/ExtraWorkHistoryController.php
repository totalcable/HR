<?php

namespace App\Http\Controllers;
use App\Employee;
use App\ExtraWorkHistory;
use App\GovtHoliday;
use App\ShiftLog;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use DB;

class ExtraWorkHistoryController extends Controller
{
   public function getAllExtraWorkHistory(Request $r){


       $allExtraWork=ExtraWorkHistory::select('extra_duty_history.*',DB::raw("CONCAT(COALESCE(firstName,''),' ',COALESCE(middleName,''),' ',COALESCE(lastName,'')) AS empFullname"))
           ->leftJoin('employeeinfo','employeeinfo.id','extra_duty_history.fkEmpId');

       if ($r->startDate){

           $allExtraWork=$allExtraWork->whereDate('extra_duty_history.startDate','>=',Carbon::parse($r->startDate)->format('Y-m-d'));
       }
       if ($r->endDate){

           $allExtraWork=$allExtraWork->whereDate('extra_duty_history.endDate','<=',Carbon::parse($r->endDate)->format('Y-m-d'));
       }
       if ($r->ExtraWorkStatus){

           $allExtraWork=$allExtraWork->where('extra_duty_history.status',$r->ExtraWorkStatus);
       }


       $datatables = Datatables::of($allExtraWork);
       return $datatables->make(true);


   }
   public function calculateExtraWork(Request $r,$format='Y-m-d'){

       $startDate=$r->startDate;
       $endDate=$r->endDate;
       $array = array();
       $interval = new \DateInterval('P1D');
       $realEnd = new DateTime($endDate);
       $realEnd->add($interval);
       $anotherFormat='l';
       $period = new \DatePeriod(new DateTime($startDate), $interval, $realEnd);


           foreach ($period as $date) {

               $ExtraWork = ShiftLog::select('shiftlog.*')
                   ->leftJoin('attemployeemap',"attemployeemap.employeeId",'shiftlog.fkemployeeId')
                   ->where(function ($query) {
                       $query->whereNotNull('shiftlog.weekend')
                           ->orWhereNotNull('shiftlog.holiday');
                   })

                   ->whereDate('shiftlog.startDate', '=', $date->format($format))
                   ->where(function ($query) use ($endDate, $date, $format) {
                       $query->whereDate('shiftlog.endDate', '=', $date->format($format));

                   })
                   ->whereExists(function ($query) use ($date, $r, $format) {
                       $query->select(DB::raw(1))
                           ->from('attendancedata')
                           ->whereRaw('attemployeemap.attDeviceUserId = attendancedata.attDeviceUserId')
                           ->whereDate("attendancedata.accessTime",$date->format($format));
                   })
                   ->orderBy('shiftlog.shiftlogId', 'ASC')
                   ->get();

               $newArray = array(
                   'data' => $ExtraWork,

               );
               array_push($array, $newArray);

           }


       return $array;









   }
}
