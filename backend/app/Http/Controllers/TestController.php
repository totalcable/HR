<?php

namespace App\Http\Controllers;

use App\AttendanceData;
use App\Employee;
use App\GovtHoliday;
use App\Leave;
use App\OrganizationCalander;
use App\ShiftLog;
use App\StaticRosterLog;
use App\Swap;
use App\TimeSwap;
use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;


class TestController extends Controller
{

    function getDatesFromRange($start, $end, $format = 'Y-m-d') {
        $array = array();
        $interval = new \DateInterval('P1D');
        $realEnd = new DateTime($end);
        $realEnd->add($interval);
        $anotherFormat='l';
        $period = new \DatePeriod(new DateTime($start), $interval, $realEnd);
        foreach($period as $date) {
            $newArray=array(
                'date'=>  $date->format($format),
                'day'=>$date->format($anotherFormat),
            );
            array_push($array,$newArray);
//            $array['date'] = $date->format($format);
//            $array['day'] = $date->format($anotherFormat);
        }
        return $array;
    }
    public function testRumi($fromDate,$toDate){
        ini_set('max_execution_time', 0);
        $start = microtime(true);

        $startDate=Carbon::parse($fromDate);
        $endDate=Carbon::parse($toDate);

        $dates = $this->getDatesFromRange($startDate, $endDate);


         $allEmp=Employee::select('employeeinfo.id','attemployeemap.attDeviceUserId',
            DB::raw("CONCAT(COALESCE(firstName,''),' ',COALESCE(middleName,''),' ',COALESCE(lastName,'')) AS empFullname"),
            'actualJoinDate','practice','weekend')
             ->leftJoin('attemployeemap','attemployeemap.employeeId','employeeinfo.id')
            ->whereNull('resignDate')
            ->get();

        $allLeave=Leave::leftJoin('leavecategories', 'leavecategories.id', '=', 'leaves.fkLeaveCategory')
            ->where('applicationStatus',"Approved")
            ->whereBetween('startDate',array($fromDate, $toDate))
            ->get();

        $allLeave=collect($allLeave);

        $allHoliday=OrganizationCalander::whereMonth('startDate', '=', date('m',strtotime($fromDate)))->orWhereMonth('endDate', '=', date('m',strtotime($toDate)))->get();

        $fromDate = Carbon::parse($fromDate)->subDays(1);
        $toDate = Carbon::parse($toDate)->addDays(1);


        $results = DB::select( DB::raw("select em.employeeId,ad.id,sl.inTime,sl.outTime,sl.multipleShift,sl.adjustmentDate
            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
            , date_format(ad.accessTime,'%H:%i:%s') accessTime
            , date_format(ad.accessTime,'%Y-%m-%d %H:%i:%s') accessTime2
            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
            and date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
            where date_format(ad.accessTime,'%Y-%m-%d') between '".$fromDate."' and '".$toDate."'
            and em.employeeId is not null"));
//
//
         $results=collect($results);

//        return $results;


        $excelName="test";
        $filePath=public_path ()."/exportedExcel";
//        $fileName="AppliedCandidateList".date("Y-m-d_H-i-s");
        $fileName="HRTest".date("Y-m-d_H-i-s");
        $fileInfo=array(
            'fileName'=>$fileName,
            'filePath'=>$fileName,
        );


//        $check=Excel::create($fileName,function($excel)use ($results,$dates,$allEmp,$fromDate,$toDate, $startDate, $endDate) {
//
//                $excel->sheet('test', function ($sheet) use ($results,$dates,$allEmp, $fromDate,$toDate,$startDate, $endDate) {
//                    $sheet->freezePane('B4');
//                    $sheet->setStyle(array(
//                        'font' => array(
//                            'name' => 'Calibri',
//                            'size' => 10,
//                            'bold' => false
//                        )
//                    ));
//                    $sheet->loadView('Excel.attendenceTestRumi', compact('results','fromDate', 'toDate','dates','allEmp',
//                       'startDate','endDate'));
//                });
//
//        })->store('xls',$filePath);

        $check=Excel::create($fileName,function($excel)use ($allHoliday,$allLeave,$results,$dates,$allEmp,$fromDate,$toDate, $startDate, $endDate) {

                $excel->sheet('test', function ($sheet) use ($allHoliday,$allLeave,$results,$dates,$allEmp, $fromDate,$toDate,$startDate, $endDate) {

                    $sheet->loadView('Excel.attendenceTestRumiAnother', compact('allHoliday','allLeave','results','fromDate', 'toDate','dates','allEmp',
                       'startDate','endDate'));
                });

        })->store('xls',$filePath);

        return $time = microtime(true) - $start;
    }

    public function Rumi()
    {

//        $results = DB::select(DB::raw("select em.employeeId,ad.id,sl.inTime,sl.outTime,sl.adjustmentDate,ad.fkAttDevice,sl.holiday,sl.weekend,ad.fkAttDevice
//            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
//            , date_format(ad.accessTime,'%H:%i:%s') accessTime
//            , date_format(ad.accessTime,'%Y-%m-%d %H:%i:%s') accessTime2
//            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
//            and date_format(ad.accessTime,'%Y-%m-%d') between '2019-10-01' and '2019-10-30'
//            left join shiftlog sl on em.employeeId = sl.fkemployeeId and
//            date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
//            and date_format(ad.accessTime,'%H:%i:%s') between sl.inTime and sl.outTime
//            left join employeeinfo emInfo on em.employeeId = emInfo.id and emInfo.fkDepartmentId is not null
//
//            where sl.fkshiftId IN (1,2) and date_format(ad.accessTime,'%Y-%m-%d') between '2019-10-01' and '2019-10-30'
//            and emInfo.id = 116 "));
//
//       return $results = collect($results);

        return $staticRoster=StaticRosterLog::select(DB::raw("TRIM(BOTH '  ,  ' FROM GROUP_CONCAT(COALESCE(EmpDuty.firstName,''),' ',COALESCE(EmpDuty.middleName,''),' ',COALESCE(EmpDuty.lastName,''))) as EmpRosterNames"),
            DB::raw("TRIM(BOTH '  ,' FROM GROUP_CONCAT(COALESCE(EmpOffDuty.firstName,''),' ',COALESCE(EmpOffDuty.middleName,''),' ',COALESCE(EmpOffDuty.lastName,''))) as EmpRosterOffDutyNames")
        )
            ->leftJoin('employeeinfo as EmpDuty',function($join) {
                $join->on('EmpDuty.id', '=', 'static_rosterlog.fkemployeeId')
                    ->whereNull('static_rosterlog.weekend');
            })
            ->leftJoin('employeeinfo as EmpOffDuty',function($join) {
                $join->on('EmpOffDuty.id', '=', 'static_rosterlog.fkemployeeId')
                    ->whereNotNull('static_rosterlog.weekend');
            })
            ->where('day','Sunday')->where('static_rosterlog.fkshiftId',2)->get();


    }
    public function Rumi1(Request $r)
    {

        $array=array();


         $currentDate=Carbon::now()->format('Y-m-d');

          $results = DB::select(DB::raw("select em.employeeId,ad.id,sl.inTime,sl.outTime,sl.adjustmentDate,ad.fkAttDevice,sl.holiday,sl.weekend,ad.fkAttDevice
            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
            , date_format(ad.accessTime,'%H:%i:%s') accessTime
            , date_format(ad.accessTime,'%Y-%m-%d %H:%i:%s') accessTime2
            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
            and date_format(ad.accessTime,'%Y-%m-%d') between '" . $currentDate . "' and '" . $currentDate . "'
            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
            left join employeeinfo emInfo on em.employeeId = emInfo.id and emInfo.fkDepartmentId is not null
            
            where date_format(ad.accessTime,'%Y-%m-%d') between '" . $currentDate . "' and '" . $currentDate . "'"));

        $results = collect($results);

         $allEmp = Employee::select('employeeinfo.id' ,'attemployeemap.attDeviceUserId', 'employeeinfo.inDeviceNo', 'employeeinfo.outDeviceNo')
            ->leftJoin('attemployeemap', 'attemployeemap.employeeId', 'employeeinfo.id')
//            ->leftJoin('shiftlog',function($join) use($currentDate) {
//
//                $join->on('shiftlog.fkemployeeId', '=', 'employeeinfo.id');
//                $join->where('shiftlog.startDate', '=', $currentDate);
//                $join->where('shiftlog.endDate', '=', $currentDate);
//            })

            ->orderBy('employeeinfo.id', 'ASC')
            ->whereNotNull('employeeinfo.fkDepartmentId')
            ->get();

        foreach ($allEmp as $allE){

            if ($results->where('employeeId',$allE->id)->where('attendanceDate',$currentDate)->first()){



                if($results->where('employeeId',$allE->id)->where('attendanceDate',$currentDate)->first()->inTime <'20:00:00'){

                    if ($results->where('employeeId',$allE->id)->where('attendanceDate',$currentDate)
                        ->where('fkAttDevice',$allE->inDeviceNo)->first()){

                        $access=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$currentDate)
                            ->where('fkAttDevice',$allE->inDeviceNo)->first()->accessTime);
                        $ins=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$currentDate)
                            ->where('fkAttDevice',$allE->inDeviceNo)->first()->inTime);

                        if ($access->diffInHours($ins) >= 4) {

                            $newArray=array(
                              'date'=>$currentDate,
                                'fkEmployeeId'=>$allE->id,
                                'old_inTime'=>$ins,
                                'accessTime'=>$access,
                            );
                            array_push($array,$newArray);

                        }
                    }
                }elseif($results->where('employeeId',$allE->id)->where('attendanceDate',$currentDate)->first()->inTime >='20:01:00' &&
                    $results->where('employeeId',$allE->id)->where('attendanceDate',$currentDate)->first()->inTime <= '23:59:00'){

                    if ($results->where('employeeId',$allE->id)->where('attendanceDate',$currentDate)
                        ->where('fkAttDevice',$allE->inDeviceNo)->where('accessTime','>=','20:01:00')
                        ->where('accessTime','<=','23:59:00')->first()){

                        $access=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$currentDate)
                            ->where('fkAttDevice',$allE->inDeviceNo)->where('accessTime','>=','20:01:00')->where('accessTime','<=','23:59:00')->first()->accessTime);
                        $ins=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$currentDate)
                            ->where('fkAttDevice',$allE->inDeviceNo)->first()->inTime);

                        if ($access->diffInHours($ins) >= 4){
                            $newArray=array(
                                'date'=>$currentDate,
                                'fkEmployeeId'=>$allE->id,
                                'old_inTime'=>$ins,
                                'accessTime'=>$access,
                            );
                            array_push($array,$newArray);
                        }



                    }elseif($results->where('employeeId',$allE->id)->where('attendanceDate',\Carbon\Carbon::parse($currentDate)->addDay())
                        ->where('fkAttDevice',$allE->inDeviceNo)->where('accessTime','>=','02:01:00')
                        ->where('accessTime','<=','03:59:00')->first())
                    {
                        $access=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',\Carbon\Carbon::parse($currentDate)->addDay())
                            ->where('fkAttDevice',$allE->inDeviceNo)->where('accessTime','>=','2:01:00')
                            ->where('accessTime','<=','03:59:00')->first()->accessTime2);
                        $ins=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$currentDate)
                            ->where('fkAttDevice',$allE->inDeviceNo)->first()->inTime);

                        if ($access->diffInHours($ins) >= 3){

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

        }



        if (count($array)>0){

            foreach ($array as $ar){

                 try {

                 //   $save=new TimeSwap();

                    TimeSwap::firstOrCreate(
                      ['fkEmployeeId'=>$ar['fkEmployeeId'],'date'=>$ar['date'],'accessTime'=>$ar['accessTime']],
                      ['old_inTime'=>$ar['old_inTime'], 'status'=> '0']
                    );

//                    $save->fkEmployeeId=$ar['fkEmployeeId'];
//                    $save->date=$ar['date'];
//                    $save->old_inTime=$ar['old_inTime'];
//                    $save->accessTime=$ar['accessTime'];
//                    $save->status=0;
//                    $save->save();



                } catch (Exception $e) {



                }


            }





        }


    }

}
