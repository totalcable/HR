<?php

namespace App\Http\Controllers;

use App\AttendanceData;
use App\Duty;
use App\Employee;
use App\GovtHoliday;
use App\Leave;
use App\ShiftLog;
use App\Swap;
use App\TimeSwap;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Excel;

class DutyController extends Controller
{
    public function calculateDutyFromAttendance(Request $r){

     //   return $r->userId;

        $emp=Employee::select('inDeviceNo','outDeviceNo')->leftjoin('attemployeemap','attemployeemap.employeeId','employeeinfo.id')
            ->where('attemployeemap.attDeviceUserId',$r->userId)->first();

        $currentDate = Carbon::parse($r->date)->format('Y-m-d');


        $empINData = DB::select(DB::raw("select em.employeeId,ad.id,sl.inTime,sl.outTime,sl.adjustmentDate,ad.fkAttDevice,sl.holiday,sl.weekend,ad.fkAttDevice,sl.fkshiftId
            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
            , date_format(ad.accessTime,'%H:%i:%s') accessTime
            , date_format(ad.accessTime,'%Y-%m-%d %H:%i:%s') accessTime2
            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
            and date_format(ad.accessTime,'%Y-%m-%d') between '" . $currentDate . "' and '" . $currentDate . "'
            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
            
            
            where  ad.attDeviceUserId='" . $r->userId . "' and ad.fkAttDevice='".$emp["inDeviceNo"]."' and date_format(ad.accessTime,'%Y-%m-%d') between '" . $currentDate . "' and '" . $currentDate . "'
            "));



        $empout = DB::select(DB::raw("select em.employeeId,ad.id,sl.inTime,sl.outTime,sl.adjustmentDate,ad.fkAttDevice,sl.fkshiftId
            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
            , date_format(ad.accessTime,'%H:%i:%s') accessTime
            , date_format(ad.accessTime,'%Y-%m-%d %H:%i:%s') accessTime2
            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
            and date_format(ad.accessTime,'%Y-%m-%d') between '" . $currentDate . "' and '" . $currentDate . "'
            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
            
            
            where  ad.attDeviceUserId='" . $r->userId . "' and ad.fkAttDevice='".$emp["outDeviceNo"]."' and date_format(ad.accessTime,'%Y-%m-%d') between '" . $currentDate . "' and '" . $currentDate . "'
            "));

        $deleteOldData=Duty::where('user_id',$r->userId)->where('date',$currentDate)->delete();

      foreach ($empINData as $resIN){


          try{

              $duty=new Duty();
              $duty->user_id=$r->userId;
              $duty->in_time=$resIN->accessTime;

              $duty->shift=$resIN->fkshiftId;

              $duty->device_id=$resIN->fkAttDevice;
              $duty->date=$currentDate;
              $duty->save();

          }catch (QueryException $e) {

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
    function getDatesFromRange($start, $end, $format = 'Y-m-d') {
        $array = array();
        $interval = new \DateInterval('P1D');
        $realEnd = new DateTime($end);
        $realEnd->add($interval);
        $anotherFormat = 'l';
        $period = new \DatePeriod(new DateTime($start), $interval, $realEnd);
        foreach ($period as $date) {
            $newArray = array(
                'date' => $date->format($format),
                'day' => $date->format($anotherFormat),
            );
            array_push($array, $newArray);
//            $array['date'] = $date->format($format);
//            $array['day'] = $date->format($anotherFormat);
        }
        return $array;
    }
    public function calculateDutyAndDownload(Request $r){

        ini_set('max_execution_time', 0);
        $emp=Employee::select('inDeviceNo','outDeviceNo','attemployeemap.employeeId')->leftjoin('attemployeemap','attemployeemap.employeeId','employeeinfo.id')
            ->where('attemployeemap.attDeviceUserId',$r->userId)->first();

        $currentDate = Carbon::parse($r->date)->format('Y-m-d');

        $startDate = Carbon::parse($currentDate);
        $endDate = Carbon::parse($currentDate);

        $fromDate = Carbon::parse($currentDate)->subDays(1);
        $toDate = Carbon::parse($currentDate)->addDays(1);

        $dates = $this->getDatesFromRange($startDate, $endDate);

        $filePath = public_path() . "/exportedExcel";

        $fileName = "DutyCalculation" . date("Y-m-d_H-i-s");
        $fileInfo = array(
            'fileName' => $fileName,
            'filePath' => $fileName,
        );

        $dutySwap=Swap::where('swap_by', $emp['employeeId'])->whereBetween('swap_by_date', array($fromDate, $toDate))
            ->where(function ($query) {
                $query->where('departmentHeadApproval', '!=', '0')
                    ->orWhere('departmentHeadApproval', '!=', null);
            })->where(function ($query) {
                $query->where('HR_adminApproval', '!=', '0')
                    ->orWhere('HR_adminApproval', '!=', null);
            })->get();

        $allTimeSwap=TimeSwap::where('fkEmployeeId', $emp['employeeId'])
            ->whereBetween('date', array($fromDate, $toDate))
            ->where(function ($query) {
                $query->where('departmentHeadApproval', '!=', '0')
                    ->orWhere('departmentHeadApproval', '!=', null);
            })->where(function ($query) {
                $query->where('HR_adminApproval', '!=', '0')
                    ->orWhere('HR_adminApproval', '!=', null);
            })->get();

        $allTimeSwap=collect($allTimeSwap);

        $allLeave = Leave::leftJoin('leavecategories', 'leavecategories.id', '=', 'leaves.fkLeaveCategory')
            ->where('applicationStatus', "Approved")
            ->where('leaves.fkEmployeeId', $emp['employeeId'])
            ->whereBetween('startDate', array($fromDate, $toDate))
            ->get();

        $allLeave = collect($allLeave);

        $allWeekend = ShiftLog::whereNotNull('weekend')
            ->where('shiftlog.fkEmployeeId', $emp['employeeId'])
            ->whereBetween('startDate', array($fromDate, $toDate))
            ->get();

        $allWeekend = collect($allWeekend);

        $allHoliday = ShiftLog::whereNotNull('holiday')
            ->where('shiftlog.fkEmployeeId', $emp['employeeId'])
            ->whereBetween('startDate', array($fromDate, $toDate))
            ->get();

        $allHoliday = collect($allHoliday);

        $govtHoliday=GovtHoliday::where('startDate','>=',$fromDate)->where('endDate','<=',$toDate)->where('status','Approved')->get();

        $govtHoliday=collect($govtHoliday);

        $allEmp = Employee::select('employeeinfo.id', 'attemployeemap.attDeviceUserId', 'departments.departmentName','designations.title as designationTitle',
            DB::raw("CONCAT(COALESCE(firstName,''),' ',COALESCE(middleName,''),' ',COALESCE(lastName,'')) AS empFullname"), 'employeeinfo.inDeviceNo', 'employeeinfo.outDeviceNo')
            ->leftJoin('attemployeemap', 'attemployeemap.employeeId', 'employeeinfo.id')
            ->leftJoin('departments', 'departments.id', 'employeeinfo.fkDepartmentId')
            ->leftJoin('designations', 'designations.id', 'employeeinfo.fkDesignation')
            ->where('employeeinfo.id', $emp['employeeId'])
            ->orderBy('departments.orderBy', 'ASC')
            ->orderBy('employeeinfo.id', 'ASC')
//                ->whereNotNull('employeeinfo.fkDepartmentId')
            ->get();

       // $List = implode(',', $r->empId);

         $empINData = DB::select(DB::raw("select em.employeeId,ad.id,sl.inTime,sl.outTime,sl.adjustmentDate,ad.device_id,sl.fkshiftId
            , date_format(ad.date,'%Y-%m-%d') attendanceDate
            , date_format(ad.in_time,'%H:%i:%s') accessTime
            
            from duty ad left join attemployeemap em on ad.user_id = em.attDeviceUserId
            and date_format(ad.date,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.date,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
            
            
            where  ad.user_id='" . $r->userId . "' and ad.device_id='".$emp["inDeviceNo"]."' and date_format(ad.date,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "' 
            ORDER BY ad.in_time ASC"));

        $empINData=collect($empINData);


        $empout = DB::select(DB::raw("select em.employeeId,ad.id,sl.inTime,sl.outTime,sl.adjustmentDate,ad.device_id,sl.fkshiftId
            , date_format(ad.date,'%Y-%m-%d') attendanceDate
            , date_format(ad.out_time,'%H:%i:%s') accessTime
            
            from duty ad left join attemployeemap em on ad.user_id = em.attDeviceUserId
            and date_format(ad.date,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.date,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
            
            
            where  ad.user_id='" . $r->userId . "' and ad.device_id='".$emp["outDeviceNo"]."' and date_format(ad.date,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            ORDER BY ad.in_time ASC"));

        $empout=collect($empout);


        $check = Excel::create($fileName, function ($excel) use ($empINData,$empout, $dates, $allEmp, $fromDate, $toDate, $startDate, $endDate, $allLeave, $allHoliday,
            $allWeekend,$govtHoliday,$allTimeSwap,$dutySwap) {

            foreach ($allEmp as $allE) {

                $excel->sheet($allE->attDeviceUserId, function ($sheet) use ($empINData,$empout, $allE, $dates, $allEmp, $fromDate, $toDate, $startDate,
                    $endDate, $allLeave, $allHoliday, $allWeekend,$govtHoliday,$allTimeSwap,$dutySwap) {

                    $sheet->freezePane('B5');


                    $sheet->setStyle(array(
                        'font' => array(
                            'name' => 'Calibri',
                            'size' => 10,
                            'bold' => false
                        )
                    ));

                    $sheet->loadView('Excel.CalculatedDuty', compact('empINData','empout', 'allE', 'fromDate', 'toDate', 'dates', 'allEmp',
                        'startDate', 'endDate', 'allLeave', 'allWeekend', 'allHoliday','govtHoliday','allTimeSwap','dutySwap'));
                });
            }
        })->store('xls', $filePath);

        return response()->json($fileName);


    }
}
