<?php

namespace App\Http\Controllers;

use App\AttendanceData;
use App\Department;
use App\Employee;
use App\GovtHoliday;
use App\Leave;
use App\OrganizationCalander;
use App\Shift;
use App\ShiftLog;
use App\Swap;
use App\TimeSwap;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use DB;
use Excel;
use Yajra\DataTables\DataTables;

class AttendanceController extends Controller {

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

    public function getAttendenceDataForHR(Request $r) {




        $fromDate = $r->startDate;
        $toDate = $r->endDate;


        ini_set('max_execution_time', 0);

        $startDate = Carbon::parse($fromDate);
        $endDate = Carbon::parse($toDate);

        $dates = $this->getDatesFromRange($startDate, $endDate);

        $allEmp = Employee::select('employeeinfo.id', 'attemployeemap.attDeviceUserId', 'employeeinfo.fkDepartmentId', DB::raw("CONCAT(COALESCE(firstName,''),' ',COALESCE(middleName,''),' ',COALESCE(lastName,'')) AS empFullname"), 'actualJoinDate', 'weekend')
                ->leftJoin('attemployeemap', 'attemployeemap.employeeId', 'employeeinfo.id')
                ->whereNull('resignDate')
                ->get();

        $allLeave = Leave::leftJoin('leavecategories', 'leavecategories.id', '=', 'leaves.fkLeaveCategory')
                ->where('applicationStatus', "Approved")
                ->whereBetween('startDate', array($fromDate, $toDate))
                ->get();

        $allLeave = collect($allLeave);

        $allHoliday = OrganizationCalander::whereMonth('startDate', '=', date('m', strtotime($fromDate)))->orWhereMonth('endDate', '=', date('m', strtotime($toDate)))->get();


        $fromDate = Carbon::parse($fromDate)->subDays(1);
        $toDate = Carbon::parse($toDate)->addDays(1);


        $results = DB::select(DB::raw("select em.employeeId,ad.id,sl.inTime,sl.outTime,sl.multipleShift,sl.adjustmentDate
            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
            , date_format(ad.accessTime,'%H:%i:%s') accessTime
            , date_format(ad.accessTime,'%Y-%m-%d %H:%i:%s') accessTime2
            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
            and date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
            where date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            and em.employeeId is not null"));

        $results = collect($results);



        $excelName = "test";
        $filePath = public_path() . "/exportedExcel";

        $fileName = "HRTest" . date("Y-m-d_H-i-s");
        $fileInfo = array(
            'fileName' => $fileName,
            'filePath' => $fileName,
        );

        $check = Excel::create($fileName, function($excel)use ($allHoliday, $allLeave, $results, $dates, $allEmp, $fromDate, $toDate, $startDate, $endDate) {



                    $excel->sheet('first sheet', function ($sheet) use ($allHoliday, $allLeave, $results, $dates, $allEmp, $fromDate, $toDate, $startDate, $endDate) {

                        $sheet->freezePane('C4');
                        $sheet->setStyle(array(
                            'font' => array(
                                'name' => 'Calibri',
                                'size' => 10,
                                'bold' => false
                            )
                        ));

                        $sheet->loadView('Excel.attendenceTestRumiAnother', compact('allHoliday', 'allLeave', 'results', 'fromDate', 'toDate', 'dates', 'allEmp', 'startDate', 'endDate'));
                    });
                })->store('xls', $filePath);

        return response()->json($fileName);
    }

    public function getAttendenceDataForHRINOUT(Request $r) {


        $fromDate = $r->startDate;
        $toDate = $r->endDate;


        ini_set('max_execution_time', 0);

        $startDate = Carbon::parse($fromDate);
        $endDate = Carbon::parse($toDate);

        $dates = $this->getDatesFromRange($startDate, $endDate);

        $fromDate = Carbon::parse($fromDate)->subDays(1);
        $toDate = Carbon::parse($toDate)->addDays(1);

        $excelName = "test";
        $filePath = public_path() . "/exportedExcel";

        $fileName = "HRTest" . date("Y-m-d_H-i-s");
        $fileInfo = array(
            'fileName' => $fileName,
            'filePath' => $fileName,
        );


        if ($r->empId) {

            $allLeave = Leave::leftJoin('leavecategories', 'leavecategories.id', '=', 'leaves.fkLeaveCategory')
                    ->where('applicationStatus', "Approved")
                    ->whereIn('leaves.fkEmployeeId', $r->empId)
                    ->whereBetween('startDate', array($fromDate, $toDate))
                    ->get();

            $allLeave = collect($allLeave);

            $allWeekend = ShiftLog::whereNotNull('weekend')
                    ->whereIn('shiftlog.fkEmployeeId', $r->empId)
                    ->whereBetween('startDate', array($fromDate, $toDate))
                    ->get();

            $allWeekend = collect($allWeekend);

            $allHoliday = ShiftLog::whereNotNull('holiday')
                    ->whereIn('shiftlog.fkEmployeeId', $r->empId)
                    ->whereBetween('startDate', array($fromDate, $toDate))
                    ->get();

            $allHoliday = collect($allHoliday);

            $allEmp = Employee::select('employeeinfo.id', 'attemployeemap.attDeviceUserId', 'departments.departmentName', DB::raw("CONCAT(COALESCE(firstName,''),' ',COALESCE(middleName,''),' ',COALESCE(lastName,'')) AS empFullname"), 'employeeinfo.inDeviceNo', 'employeeinfo.outDeviceNo')
                    ->leftJoin('attemployeemap', 'attemployeemap.employeeId', 'employeeinfo.id')
                    ->leftJoin('departments', 'departments.id', 'employeeinfo.fkDepartmentId')
                    ->whereIn('employeeinfo.id', $r->empId)
                    ->orderBy('departments.orderBy', 'ASC')
                    ->orderBy('employeeinfo.id', 'ASC')
//                ->whereNotNull('employeeinfo.fkDepartmentId')
                    ->get();

            $List = implode(',', $r->empId);

            $results = DB::select(DB::raw("select em.employeeId,ad.id,sl.inTime,sl.outTime,sl.adjustmentDate,ad.fkAttDevice,sl.holiday,sl.weekend,ad.fkAttDevice
            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
            , date_format(ad.accessTime,'%H:%i:%s') accessTime
            , date_format(ad.accessTime,'%Y-%m-%d %H:%i:%s') accessTime2
            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
            and date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
            left join employeeinfo emInfo on em.employeeId = emInfo.id
            
            where date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            and emInfo.id IN (" . $List . ")"));

            $results = collect($results);

            if ($r->report && $r->report == 'dailyINOUT') {

                $check1 = Excel::create($fileName, function ($excel) use ($results, $dates, $allEmp, $fromDate, $toDate, $startDate, $endDate,
                                $allLeave, $allWeekend, $allHoliday) {

                            $excel->sheet('test', function ($sheet) use ($results, $dates, $allEmp, $fromDate, $toDate, $startDate, $endDate, $allLeave,
                                    $allWeekend, $allHoliday) {


                                $sheet->freezePane('C4');
                                $sheet->setStyle(array(
                                    'font' => array(
                                        'name' => 'Calibri',
                                        'size' => 10,
                                        'bold' => false
                                    )
                                ));

                                $sheet->loadView('Excel.testAttendence', compact('results', 'fromDate', 'toDate', 'dates', 'allEmp', 'startDate', 'endDate', 'allLeave', 'allWeekend', 'allHoliday'));
                            });
                        })->store('xls', $filePath);
            } elseif ($r->report && $r->report == 'monthlyINOUT') {


                $checkMonthly = Excel::create($fileName, function($excel)use ($results, $dates, $allEmp, $fromDate, $toDate, $startDate, $endDate, $allLeave,
                                $allWeekend, $allHoliday) {

                            $excel->sheet('Monthly', function ($sheet) use ($results, $dates, $allEmp, $fromDate, $toDate, $startDate, $endDate, $allLeave,
                                    $allWeekend, $allHoliday) {



                                $sheet->freezePane('C4');
                                $sheet->setStyle(array(
                                    'font' => array(
                                        'name' => 'Calibri',
                                        'size' => 10,
                                        'bold' => false
                                    )
                                ));

                                $sheet->loadView('Excel.attendenceonlyINOUTMonthly', compact('results', 'fromDate', 'toDate', 'dates', 'allEmp', 'startDate', 'endDate', 'allLeave', 'allWeekend', 'allHoliday'));
                            });
                        })->store('xls', $filePath);
            } else {

                $check = Excel::create($fileName, function($excel)use ($results, $dates, $allEmp, $fromDate, $toDate, $startDate, $endDate) {

                            foreach ($dates as $ad) {

                                $excel->sheet($ad['date'], function ($sheet) use ($results, $ad, $dates, $allEmp, $fromDate, $toDate, $startDate, $endDate) {

                                    $sheet->loadView('Excel.attendenceonlyINOUTForSigle', compact('results', 'ad', 'fromDate', 'toDate', 'dates', 'allEmp', 'startDate', 'endDate'));
                                });
                            }
                        })->store('xls', $filePath);
            }
        } else {

            if (($r->report && $r->report == 'notAssignedRoster')) {
                
            } else {

                $allLeave = Leave::leftJoin('leavecategories', 'leavecategories.id', '=', 'leaves.fkLeaveCategory')
                        ->where('applicationStatus', "Approved")
                        ->whereBetween('startDate', array($fromDate, $toDate))
                        ->get();

                $allLeave = collect($allLeave);

                $allWeekend = ShiftLog::whereNotNull('weekend')->whereBetween('startDate', array($fromDate, $toDate))->get();

                $allWeekend = collect($allWeekend);

                $allHoliday = ShiftLog::whereNotNull('holiday')
                        ->whereBetween('startDate', array($fromDate, $toDate))
                        ->get();

                $allHoliday = collect($allHoliday);


                $allEmp = Employee::select('employeeinfo.id', 'attemployeemap.attDeviceUserId', 'departments.departmentName', DB::raw("CONCAT(COALESCE(firstName,''),' ',COALESCE(middleName,''),' ',COALESCE(lastName,'')) AS empFullname"), 'employeeinfo.inDeviceNo', 'employeeinfo.outDeviceNo')
                        ->leftJoin('attemployeemap', 'attemployeemap.employeeId', 'employeeinfo.id')
                        ->leftJoin('departments', 'departments.id', 'employeeinfo.fkDepartmentId')
                        ->whereNotNull('employeeinfo.fkDepartmentId')
                        ->orderBy('departments.orderBy', 'ASC')
                        ->orderBy('employeeinfo.id', 'ASC')
                        ->whereNull('resignDate')
                        ->whereNotNull('employeeinfo.fkDepartmentId')
                        ->get();


                $results = DB::select(DB::raw("select em.employeeId,ad.id,sl.inTime,sl.outTime,sl.adjustmentDate,sl.holiday,sl.weekend,ad.fkAttDevice
            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
            , date_format(ad.accessTime,'%H:%i:%s') accessTime
            , date_format(ad.accessTime,'%Y-%m-%d %H:%i:%s') accessTime2
            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
            and date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
            left join employeeinfo emInfo on em.employeeId = emInfo.id and emInfo.fkDepartmentId is not null
            
            where date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            and em.employeeId is not null and emInfo.fkDepartmentId is not null"));

                $results = collect($results);


                if ($r->report && $r->report == 'monthlyINOUT') {

                    $check = Excel::create($fileName, function ($excel) use ($results, $dates, $allEmp, $fromDate, $toDate, $startDate, $endDate,
                                    $allLeave, $allWeekend, $allHoliday) {

                                $excel->sheet('Monthly', function ($sheet) use ($results, $dates, $allEmp, $fromDate, $toDate, $startDate, $endDate,
                                        $allLeave, $allWeekend, $allHoliday) {


                                    $sheet->freezePane('C4');
                                    $sheet->setStyle(array(
                                        'font' => array(
                                            'name' => 'Calibri',
                                            'size' => 10,
                                            'bold' => false
                                        )
                                    ));

                                    $sheet->loadView('Excel.attendenceonlyINOUTMonthly', compact('results', 'fromDate', 'toDate', 'dates', 'allEmp', 'startDate', 'endDate', 'allLeave', 'allWeekend', 'allHoliday'));
                                });
                            })->store('xls', $filePath);
                } elseif ($r->report && $r->report == 'dailyINOUT') {


                    $check = Excel::create($fileName, function ($excel) use ($results, $dates, $allEmp, $fromDate, $toDate, $startDate, $endDate,
                                    $allLeave, $allWeekend, $allHoliday) {

                                $excel->sheet('test', function ($sheet) use ($results, $dates, $allEmp, $fromDate, $toDate, $startDate, $endDate, $allLeave,
                                        $allWeekend, $allHoliday) {


                                    $sheet->freezePane('C4');
                                    $sheet->setStyle(array(
                                        'font' => array(
                                            'name' => 'Calibri',
                                            'size' => 10,
                                            'bold' => false
                                        )
                                    ));

                                    $sheet->loadView('Excel.testAttendence', compact('results', 'fromDate', 'toDate', 'dates', 'allEmp', 'startDate', 'endDate', 'allLeave', 'allWeekend', 'allHoliday'));
                                });
                            })->store('xls', $filePath);
                } else {

                    $check = Excel::create($fileName, function ($excel) use ($results, $dates, $allEmp, $fromDate, $toDate, $startDate, $endDate) {

                                foreach ($dates as $ad) {

                                    $excel->sheet($ad['date'], function ($sheet) use ($results, $ad, $dates, $allEmp, $fromDate, $toDate, $startDate, $endDate) {

                                        $sheet->loadView('Excel.attendenceonlyINOUTForSigle', compact('results', 'ad', 'fromDate', 'toDate', 'dates', 'allEmp', 'startDate', 'endDate'));
                                    });
                                }
                            })->store('xls', $filePath);
                }
            }
        }



        return response()->json($fileName);
    }

    public function getEmployeeAttendance(Request $r) {


        $empId = $r->empId;
        $start = Carbon::now()->startOfMonth()->format('Y-m-d');
        $end = Carbon::now()->endOfMonth()->format('Y-m-d');




        if ($r->startDate && $r->endDate) {
            $start = $r->startDate;
            $end = $r->endDate;
        }

        $dates = $this->getDatesFromRange($start, $end);

        $fromDate = Carbon::parse($start)->subDays(1);
        $toDate = Carbon::parse($end)->addDays(1);

        $List = implode(',', $r->empId);

        $allEmp = Employee::select('employeeinfo.id', 'attemployeemap.attDeviceUserId', 'departments.departmentName', DB::raw("CONCAT(COALESCE(firstName,''),' ',COALESCE(middleName,''),' ',COALESCE(lastName,'')) AS empFullname"), 'employeeinfo.inDeviceNo', 'employeeinfo.outDeviceNo')
                ->leftJoin('attemployeemap', 'attemployeemap.employeeId', 'employeeinfo.id')
                ->leftJoin('departments', 'departments.id', 'employeeinfo.fkDepartmentId')
                ->where('employeeinfo.id', $r->empId)
//            ->whereIn('employeeinfo.id',$r->empId)
                ->orderBy('departments.orderBy', 'ASC')
                ->orderBy('employeeinfo.id', 'ASC')
                ->first();

//            ->get();

        $results = DB::select(DB::raw("select em.employeeId,ad.id,sl.inTime,sl.outTime,sl.adjustmentDate,ad.fkAttDevice,sl.holiday,sl.weekend,ad.fkAttDevice
            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
            , date_format(ad.accessTime,'%H:%i:%s') accessTime
            , date_format(ad.accessTime,'%Y-%m-%d %H:%i:%s') accessTime2
            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
            and date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
            left join employeeinfo emInfo on em.employeeId = emInfo.id and emInfo.fkDepartmentId is not null
            
            where date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            and emInfo.id IN (" . $List . ")"));

        $newArray = array(
            'dates' => $dates,
            'result' => $results,
            'allEmp' => $allEmp,
        );



        return $newArray;
    }

    public function finalReport_1(Request $r) {

        $fromDate = $r->startDate;
        $toDate = $r->endDate;


        ini_set('max_execution_time', 0);

        $startDate = Carbon::parse($fromDate);
        $endDate = Carbon::parse($toDate);

        $dates = $this->getDatesFromRange($startDate, $endDate);

        $fromDate = Carbon::parse($fromDate)->subDays(1);
        $toDate = Carbon::parse($toDate)->addDays(1);


        $filePath = public_path() . "/exportedExcel";

        $fileName = "Final_Report_1" . date("Y-m-d_H-i-s");
        $fileInfo = array(
            'fileName' => $fileName,
            'filePath' => $fileName,
        );

        if ($r->empId) {

            $dutySwap=Swap::whereIn('swap_by', $r->empId)->whereBetween('swap_by_date', array($fromDate, $toDate))
                ->where(function ($query) {
                    $query->where('departmentHeadApproval', '!=', '0')
                        ->orWhere('departmentHeadApproval', '!=', null);
                })->where(function ($query) {
                    $query->where('HR_adminApproval', '!=', '0')
                        ->orWhere('HR_adminApproval', '!=', null);
                })->get();

            $allTimeSwap=TimeSwap::whereIn('fkEmployeeId', $r->empId)
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
                    ->whereIn('leaves.fkEmployeeId', $r->empId)
                    ->whereBetween('startDate', array($fromDate, $toDate))
                    ->get();

            $allLeave = collect($allLeave);

            $allWeekend = ShiftLog::whereNotNull('weekend')
                    ->whereIn('shiftlog.fkEmployeeId', $r->empId)
                    ->whereBetween('startDate', array($fromDate, $toDate))
                    ->get();

            $allWeekend = collect($allWeekend);

            $allHoliday = ShiftLog::whereNotNull('holiday')
                    ->whereIn('shiftlog.fkEmployeeId', $r->empId)
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
                    ->whereIn('employeeinfo.id', $r->empId)
                    ->orderBy('departments.orderBy', 'ASC')
                    ->orderBy('employeeinfo.id', 'ASC')
//                ->whereNotNull('employeeinfo.fkDepartmentId')
                    ->get();

            $List = implode(',', $r->empId);

            $results = DB::select(DB::raw("select em.employeeId,ad.id,sl.inTime,sl.outTime,sl.adjustmentDate,ad.fkAttDevice,sl.holiday,sl.weekend,ad.fkAttDevice
            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
            , date_format(ad.accessTime,'%H:%i:%s') accessTime
            , date_format(ad.accessTime,'%Y-%m-%d %H:%i:%s') accessTime2
            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
            and date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
            left join employeeinfo emInfo on em.employeeId = emInfo.id and emInfo.fkDepartmentId is not null
            
            where date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            and emInfo.id IN (" . $List . ")"));

            $results = collect($results);
        } else {

            $dutySwap=Swap::whereBetween('swap_by_date', array($fromDate, $toDate))
                ->where(function ($query) {
                    $query->where('departmentHeadApproval', '!=', '0')
                        ->orWhere('departmentHeadApproval', '!=', null);
                })->where(function ($query) {
                    $query->where('HR_adminApproval', '!=', '0')
                        ->orWhere('HR_adminApproval', '!=', null);
                })->get();

            $allTimeSwap=TimeSwap::whereBetween('date', array($fromDate, $toDate))
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
                    ->whereBetween('startDate', array($fromDate, $toDate))
                    ->get();

            $allLeave = collect($allLeave);

            $allWeekend = ShiftLog::whereNotNull('weekend')
                    ->whereBetween('startDate', array($fromDate, $toDate))
                    ->get();


            $allWeekend = collect($allWeekend);

            $allHoliday = ShiftLog::whereNotNull('holiday')
                    ->whereBetween('startDate', array($fromDate, $toDate))
                    ->get();

            $allHoliday = collect($allHoliday);

            $govtHoliday=GovtHoliday::where('startDate','>=',$fromDate)->where('endDate','<=',$toDate)->where('status','Approved')->get();

            $govtHoliday=collect($govtHoliday);

            $allEmp = Employee::select('employeeinfo.id', 'attemployeemap.attDeviceUserId', 'departments.departmentName', DB::raw("CONCAT(COALESCE(firstName,''),' ',COALESCE(middleName,''),' ',COALESCE(lastName,'')) AS empFullname"), 'employeeinfo.inDeviceNo', 'employeeinfo.outDeviceNo')
                    ->leftJoin('attemployeemap', 'attemployeemap.employeeId', 'employeeinfo.id')
                    ->leftJoin('departments', 'departments.id', 'employeeinfo.fkDepartmentId')
                    ->orderBy('departments.orderBy', 'ASC')
                    ->orderBy('employeeinfo.id', 'ASC')
//                ->whereNotNull('employeeinfo.fkDepartmentId')
                    ->get();

            $results = DB::select(DB::raw("select em.employeeId,ad.id,sl.inTime,sl.outTime,sl.adjustmentDate,ad.fkAttDevice,sl.holiday,sl.weekend,ad.fkAttDevice
            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
            , date_format(ad.accessTime,'%H:%i:%s') accessTime
            , date_format(ad.accessTime,'%Y-%m-%d %H:%i:%s') accessTime2
            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
            and date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
            left join employeeinfo emInfo on em.employeeId = emInfo.id and emInfo.fkDepartmentId is not null
            
            where date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'"));

            $results = collect($results);
        }

        $check = Excel::create($fileName, function ($excel) use ($results, $dates, $allEmp, $fromDate, $toDate, $startDate, $endDate, $allLeave, $allHoliday,
            $allWeekend,$govtHoliday,$allTimeSwap,$dutySwap) {

                    foreach ($allEmp as $allE) {

                        $excel->sheet($allE->attDeviceUserId, function ($sheet) use ($results, $allE, $dates, $allEmp, $fromDate, $toDate, $startDate,
                                $endDate, $allLeave, $allHoliday, $allWeekend,$govtHoliday,$allTimeSwap,$dutySwap) {

                            $sheet->freezePane('B5');
//                            $sheet->setpaperSize(5);
//                            $sheet->setOrientation('landscape');

                            $sheet->setStyle(array(
                                'font' => array(
                                    'name' => 'Calibri',
                                    'size' => 10,
                                    'bold' => false
                                )
                            ));

                            $sheet->loadView('Excel.Final_Report_1', compact('results', 'allE', 'fromDate', 'toDate', 'dates', 'allEmp',
                                'startDate', 'endDate', 'allLeave', 'allWeekend', 'allHoliday','govtHoliday','allTimeSwap','dutySwap'));
                        });
                    }
                })->store('xls', $filePath);

        return response()->json($fileName);
    }
    public function finalReportWithSalary(Request $r) {

        $fromDate = $r->startDate;
        $toDate = $r->endDate;


        ini_set('max_execution_time', 0);

        $startDate = Carbon::parse($fromDate);
        $endDate = Carbon::parse($toDate);

        $dates = $this->getDatesFromRange($startDate, $endDate);

        $fromDate = Carbon::parse($fromDate)->subDays(1);
        $toDate = Carbon::parse($toDate)->addDays(1);


        $filePath = public_path() . "/exportedExcel";

        $fileName = "finalReportWithSalary" . date("Y-m-d_H-i-s");
        $fileInfo = array(
            'fileName' => $fileName,
            'filePath' => $fileName,
        );



        if ($r->empId) {

            $dutySwap=Swap::whereIn('swap_by', $r->empId)->whereBetween('swap_by_date', array($fromDate, $toDate))
                ->where(function ($query) {
                    $query->where('departmentHeadApproval', '!=', '0')
                        ->orWhere('departmentHeadApproval', '!=', null);
                })->where(function ($query) {
                    $query->where('HR_adminApproval', '!=', '0')
                        ->orWhere('HR_adminApproval', '!=', null);
                })->get();

            $allTimeSwap=TimeSwap::whereIn('fkEmployeeId', $r->empId)
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
                    ->whereIn('leaves.fkEmployeeId', $r->empId)
                    ->whereBetween('startDate', array($fromDate, $toDate))
                    ->get();

            $allLeave = collect($allLeave);

            $allWeekend = ShiftLog::whereNotNull('weekend')
                    ->whereIn('shiftlog.fkEmployeeId', $r->empId)
                    ->whereBetween('startDate', array($fromDate, $toDate))
                    ->get();

            $allWeekend = collect($allWeekend);

            $allHoliday = ShiftLog::whereNotNull('holiday')
                    ->whereIn('shiftlog.fkEmployeeId', $r->empId)
                    ->whereBetween('startDate', array($fromDate, $toDate))
                    ->get();

            $allHoliday = collect($allHoliday);

            $govtHoliday=GovtHoliday::where('startDate','>=',$fromDate)->where('endDate','<=',$toDate)->where('status','Approved')->get();

            $govtHoliday=collect($govtHoliday);

            $allEmp = Employee::select('employeeinfo.id', 'attemployeemap.attDeviceUserId', 'departments.departmentName','designations.title as designationTitle',
                DB::raw("CONCAT(COALESCE(firstName,''),' ',COALESCE(middleName,''),' ',COALESCE(lastName,'')) AS empFullname"),
                'employeeinfo.inDeviceNo', 'employeeinfo.outDeviceNo','employeeinfo.salary','employeeinfo.pf_fund')
                    ->leftJoin('attemployeemap', 'attemployeemap.employeeId', 'employeeinfo.id')
                    ->leftJoin('departments', 'departments.id', 'employeeinfo.fkDepartmentId')
                    ->leftJoin('designations', 'designations.id', 'employeeinfo.fkDesignation')
                    ->whereIn('employeeinfo.id', $r->empId)
                    ->orderBy('departments.orderBy', 'ASC')
                    ->orderBy('employeeinfo.id', 'ASC')
//                ->whereNotNull('employeeinfo.fkDepartmentId')
                    ->get();

            $List = implode(',', $r->empId);

            $results = DB::select(DB::raw("select em.employeeId,ad.id,sl.inTime,sl.outTime,sl.adjustmentDate,ad.fkAttDevice,sl.holiday,sl.weekend,ad.fkAttDevice
            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
            , date_format(ad.accessTime,'%H:%i:%s') accessTime
            , date_format(ad.accessTime,'%Y-%m-%d %H:%i:%s') accessTime2
            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
            and date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
            left join employeeinfo emInfo on em.employeeId = emInfo.id and emInfo.fkDepartmentId is not null

            where date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            and emInfo.id IN (" . $List . ")"));

            $results = collect($results);
        } else {

            $dutySwap=Swap::whereBetween('swap_by_date', array($fromDate, $toDate))
                ->where(function ($query) {
                    $query->where('departmentHeadApproval', '!=', '0')
                        ->orWhere('departmentHeadApproval', '!=', null);
                })->where(function ($query) {
                    $query->where('HR_adminApproval', '!=', '0')
                        ->orWhere('HR_adminApproval', '!=', null);
                })->get();

            $allTimeSwap=TimeSwap::whereBetween('date', array($fromDate, $toDate))
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
                    ->whereBetween('startDate', array($fromDate, $toDate))
                    ->get();

            $allLeave = collect($allLeave);

            $allWeekend = ShiftLog::whereNotNull('weekend')
                    ->whereBetween('startDate', array($fromDate, $toDate))
                    ->get();


            $allWeekend = collect($allWeekend);

            $allHoliday = ShiftLog::whereNotNull('holiday')
                    ->whereBetween('startDate', array($fromDate, $toDate))
                    ->get();

            $allHoliday = collect($allHoliday);

            $govtHoliday=GovtHoliday::where('startDate','>=',$fromDate)->where('endDate','<=',$toDate)->where('status','Approved')->get();

            $govtHoliday=collect($govtHoliday);

            $allEmp = Employee::select('employeeinfo.id', 'attemployeemap.attDeviceUserId', 'departments.departmentName',
                DB::raw("CONCAT(COALESCE(firstName,''),' ',COALESCE(middleName,''),' ',COALESCE(lastName,'')) AS empFullname"),
                'employeeinfo.inDeviceNo', 'employeeinfo.outDeviceNo','employeeinfo.salary','employeeinfo.pf_fund')
                    ->leftJoin('attemployeemap', 'attemployeemap.employeeId', 'employeeinfo.id')
                    ->leftJoin('departments', 'departments.id', 'employeeinfo.fkDepartmentId')
                    ->orderBy('departments.orderBy', 'ASC')
                    ->orderBy('employeeinfo.id', 'ASC')
//                ->whereNotNull('employeeinfo.fkDepartmentId')
                    ->get();

            $results = DB::select(DB::raw("select em.employeeId,ad.id,sl.inTime,sl.outTime,sl.adjustmentDate,ad.fkAttDevice,sl.holiday,sl.weekend,ad.fkAttDevice
            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
            , date_format(ad.accessTime,'%H:%i:%s') accessTime
            , date_format(ad.accessTime,'%Y-%m-%d %H:%i:%s') accessTime2
            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
            and date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
            left join employeeinfo emInfo on em.employeeId = emInfo.id and emInfo.fkDepartmentId is not null

            where date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'"));

            $results = collect($results);
        }

       // return $allEmp;

        $check = Excel::create($fileName, function ($excel) use ($results, $dates, $allEmp, $fromDate, $toDate, $startDate, $endDate, $allLeave, $allHoliday,
            $allWeekend,$govtHoliday,$allTimeSwap,$dutySwap) {

                    foreach ($allEmp as $allE) {

                        $excel->sheet($allE->attDeviceUserId, function ($sheet) use ($results, $allE, $dates, $allEmp, $fromDate, $toDate, $startDate,
                                $endDate, $allLeave, $allHoliday, $allWeekend,$govtHoliday,$allTimeSwap,$dutySwap) {

                            $sheet->freezePane('B5');

                            $sheet->setStyle(array(
                                'font' => array(
                                    'name' => 'Calibri',
                                    'size' => 10,
                                    'bold' => false
                                )
                            ));

                            $sheet->loadView('Excel.finalReportWithSalary', compact('results', 'allE', 'fromDate', 'toDate', 'dates', 'allEmp',
                                'startDate', 'endDate', 'allLeave', 'allWeekend', 'allHoliday','govtHoliday','allTimeSwap','dutySwap'));
                        });
                    }
                })->store('xls', $filePath);

        return response()->json($fileName);
    }

    public function finalReport_2(Request $r) {

        $fromDate = $r->startDate;
        $toDate = $r->endDate;


        ini_set('max_execution_time', 0);

        $startDate = Carbon::parse($fromDate);
        $endDate = Carbon::parse($toDate);

        $dates = $this->getDatesFromRange($startDate, $endDate);

        $fromDate = Carbon::parse($fromDate)->subDays(1);
        $toDate = Carbon::parse($toDate)->addDays(1);


        $filePath = public_path() . "/exportedExcel";

        $fileName = "Final_Report_1" . date("Y-m-d_H-i-s");
        $fileInfo = array(
            'fileName' => $fileName,
            'filePath' => $fileName,
        );




        if ($r->empId) {

            $allLeave = Leave::leftJoin('leavecategories', 'leavecategories.id', '=', 'leaves.fkLeaveCategory')
                ->where('applicationStatus', "Approved")
                ->whereIn('leaves.fkEmployeeId', $r->empId)
                ->whereBetween('startDate', array($fromDate, $toDate))
                ->get();

            $allLeave = collect($allLeave);

            $allWeekend = ShiftLog::whereNotNull('weekend')
                ->whereIn('shiftlog.fkEmployeeId', $r->empId)
                ->whereBetween('startDate', array($fromDate, $toDate))
                ->get();

            $allWeekend = collect($allWeekend);

            $allHoliday = ShiftLog::whereNotNull('holiday')
                ->whereIn('shiftlog.fkEmployeeId', $r->empId)
                ->whereBetween('startDate', array($fromDate, $toDate))
                ->get();

            $allHoliday = collect($allHoliday);

            $allEmp = Employee::select('employeeinfo.id', 'attemployeemap.attDeviceUserId', 'departments.departmentName', DB::raw("CONCAT(COALESCE(firstName,''),' ',COALESCE(middleName,''),' ',COALESCE(lastName,'')) AS empFullname"), 'employeeinfo.inDeviceNo', 'employeeinfo.outDeviceNo')
                ->leftJoin('attemployeemap', 'attemployeemap.employeeId', 'employeeinfo.id')
                ->leftJoin('departments', 'departments.id', 'employeeinfo.fkDepartmentId')
                ->whereIn('employeeinfo.id', $r->empId)
                ->orderBy('departments.orderBy', 'ASC')
                ->orderBy('employeeinfo.id', 'ASC')
//                ->whereNotNull('employeeinfo.fkDepartmentId')
                ->get();

            $List = implode(',', $r->empId);

            $results = DB::select(DB::raw("select em.employeeId,ad.id,sl.inTime,sl.outTime,sl.adjustmentDate,ad.fkAttDevice,sl.holiday,sl.weekend,ad.fkAttDevice
            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
            , date_format(ad.accessTime,'%H:%i:%s') accessTime
            , date_format(ad.accessTime,'%Y-%m-%d %H:%i:%s') accessTime2
            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
            and date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
            left join employeeinfo emInfo on em.employeeId = emInfo.id and emInfo.fkDepartmentId is not null
            
            where date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            and emInfo.id IN (" . $List . ")"));

            $results = collect($results);


        } else {

            $allLeave = Leave::leftJoin('leavecategories', 'leavecategories.id', '=', 'leaves.fkLeaveCategory')
                ->where('applicationStatus', "Approved")
                ->whereBetween('startDate', array($fromDate, $toDate))
                ->get();

            $allLeave = collect($allLeave);

            $allWeekend = ShiftLog::whereNotNull('weekend')
                ->whereBetween('startDate', array($fromDate, $toDate))
                ->get();

            $allWeekend = collect($allWeekend);

            $allHoliday = ShiftLog::whereNotNull('holiday')
                ->whereBetween('startDate', array($fromDate, $toDate))
                ->get();

            $allHoliday = collect($allHoliday);

            $allEmp = Employee::select('employeeinfo.id', 'attemployeemap.attDeviceUserId', 'departments.departmentName', DB::raw("CONCAT(COALESCE(firstName,''),' ',COALESCE(middleName,''),' ',COALESCE(lastName,'')) AS empFullname"), 'employeeinfo.inDeviceNo', 'employeeinfo.outDeviceNo')
                ->leftJoin('attemployeemap', 'attemployeemap.employeeId', 'employeeinfo.id')
                ->leftJoin('departments', 'departments.id', 'employeeinfo.fkDepartmentId')
                ->orderBy('departments.orderBy', 'ASC')
                ->orderBy('employeeinfo.id', 'ASC')
//                ->whereNotNull('employeeinfo.fkDepartmentId')
                ->get();



            $results = DB::select(DB::raw("select em.employeeId,ad.id,sl.inTime,sl.outTime,sl.adjustmentDate,ad.fkAttDevice,sl.holiday,sl.weekend,ad.fkAttDevice
            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
            , date_format(ad.accessTime,'%H:%i:%s') accessTime
            , date_format(ad.accessTime,'%Y-%m-%d %H:%i:%s') accessTime2
            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
            and date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
            left join employeeinfo emInfo on em.employeeId = emInfo.id and emInfo.fkDepartmentId is not null
            
            where date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'"));

            $results = collect($results);



        }

        $check = Excel::create($fileName, function ($excel) use ($results, $dates, $allEmp, $fromDate, $toDate, $startDate, $endDate, $allLeave, $allHoliday, $allWeekend) {

            foreach ($allEmp as $allE) {

                $excel->sheet($allE->attDeviceUserId, function ($sheet) use ($results, $allE, $dates, $allEmp, $fromDate, $toDate, $startDate,
                    $endDate, $allLeave, $allHoliday, $allWeekend) {

                    $sheet->freezePane('B5');
                    $sheet->setStyle(array(
                        'font' => array(
                            'name' => 'Calibri',
                            'size' => 10,
                            'bold' => false
                        )
                    ));

                    $sheet->loadView('Excel.Final_Report_2', compact('results', 'allE', 'fromDate', 'toDate', 'dates', 'allEmp', 'startDate', 'endDate', 'allLeave', 'allWeekend', 'allHoliday'));
                });
            }
        })->store('xls', $filePath);


        return response()->json($fileName);
    }

    public function finalReport_3(Request $r)
    {

        $fromDate = $r->startDate;
        $toDate = $r->endDate;


        ini_set('max_execution_time', 0);

        $startDate = Carbon::parse($fromDate);
        $endDate = Carbon::parse($toDate);

        $dates = $this->getDatesFromRange($startDate, $endDate);

        $fromDate = Carbon::parse($fromDate)->subDays(1);
        $toDate = Carbon::parse($toDate)->addDays(1);


        $filePath = public_path() . "/exportedExcel";

        $fileName = "Final_Report_1" . date("Y-m-d_H-i-s");
        $fileInfo = array(
            'fileName' => $fileName,
            'filePath' => $fileName,
        );




        if ($r->empId) {

            $govtHoliday=GovtHoliday::where('startDate','>=',$fromDate)->where('endDate','<=',$toDate)->where('status','Approved')->get();

            $govtHoliday=collect($govtHoliday);

            $allLeave = Leave::leftJoin('leavecategories', 'leavecategories.id', '=', 'leaves.fkLeaveCategory')
                    ->where('applicationStatus', "Approved")
                    ->whereIn('leaves.fkEmployeeId', $r->empId)
                    ->whereBetween('startDate', array($fromDate, $toDate))
                    ->get();

            $allLeave = collect($allLeave);

            $allWeekend = ShiftLog::whereNotNull('weekend')
                    ->whereIn('shiftlog.fkEmployeeId', $r->empId)
                    ->whereBetween('startDate', array($fromDate, $toDate))
                    ->get();

            $allWeekend = collect($allWeekend);

            $allHoliday = ShiftLog::whereNotNull('holiday')
                    ->whereIn('shiftlog.fkEmployeeId', $r->empId)
                    ->whereBetween('startDate', array($fromDate, $toDate))
                    ->get();

            $allHoliday = collect($allHoliday);

            $allEmp = Employee::select('employeeinfo.id', 'attemployeemap.attDeviceUserId', 'departments.departmentName', DB::raw("CONCAT(COALESCE(firstName,''),' ',COALESCE(middleName,''),' ',COALESCE(lastName,'')) AS empFullname"), 'employeeinfo.inDeviceNo', 'employeeinfo.outDeviceNo')
                    ->leftJoin('attemployeemap', 'attemployeemap.employeeId', 'employeeinfo.id')
                    ->leftJoin('departments', 'departments.id', 'employeeinfo.fkDepartmentId')
                    ->whereIn('employeeinfo.id', $r->empId)
                    ->orderBy('departments.orderBy', 'ASC')
                    ->orderBy('employeeinfo.id', 'ASC')
//                ->whereNotNull('employeeinfo.fkDepartmentId')
                    ->get();

            $List = implode(',', $r->empId);

            $results = DB::select(DB::raw("select em.employeeId,ad.id,sl.inTime,sl.outTime,sl.adjustmentDate,ad.fkAttDevice,sl.holiday,sl.weekend,ad.fkAttDevice
            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
            , date_format(ad.accessTime,'%H:%i:%s') accessTime
            , date_format(ad.accessTime,'%Y-%m-%d %H:%i:%s') accessTime2
            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
            and date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
            left join employeeinfo emInfo on em.employeeId = emInfo.id and emInfo.fkDepartmentId is not null
            
            where date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            and emInfo.id IN (" . $List . ")"));

            $results = collect($results);

            $check = Excel::create($fileName, function ($excel) use ($results, $dates, $allEmp, $fromDate, $toDate, $startDate, $endDate,
                $allLeave, $allHoliday, $allWeekend,$govtHoliday) {



                        $excel->sheet('emp', function ($sheet) use ($results, $dates, $allEmp, $fromDate, $toDate, $startDate,
                                $endDate, $allLeave, $allHoliday, $allWeekend,$govtHoliday) {

                            $sheet->freezePane('C4');
//                            $sheet->setpaperSize(5);
//                            $sheet->setOrientation('landscape');
                            $sheet->setStyle(array(
                                'font' => array(
                                    'name' => 'Calibri',
                                    'size' => 10,
                                    'bold' => false
                                )
                            ));

                            $sheet->loadView('Excel.Final_Report_3', compact('results', 'fromDate', 'toDate', 'dates', 'allEmp',
                                'startDate', 'endDate', 'allLeave', 'allWeekend', 'allHoliday','govtHoliday'));
                        });
                    })->store('xls', $filePath);
        } else {

            $govtHoliday=GovtHoliday::where('startDate','>=',$fromDate)->where('endDate','<=',$toDate)->where('status','Approved')->get();

            $govtHoliday=collect($govtHoliday);

            $allLeave = Leave::leftJoin('leavecategories', 'leavecategories.id', '=', 'leaves.fkLeaveCategory')
                    ->where('applicationStatus', "Approved")
                    ->whereBetween('startDate', array($fromDate, $toDate))
                    ->get();

            $allLeave = collect($allLeave);

            $allWeekend = ShiftLog::whereNotNull('weekend')
                    ->whereBetween('startDate', array($fromDate, $toDate))
                    ->get();

            $allWeekend = collect($allWeekend);

            $allHoliday = ShiftLog::whereNotNull('holiday')
                    ->whereBetween('startDate', array($fromDate, $toDate))
                    ->get();

            $allHoliday = collect($allHoliday);

            $allEmp = Employee::select('employeeinfo.id', 'attemployeemap.attDeviceUserId', 'departments.departmentName', DB::raw("CONCAT(COALESCE(firstName,''),' ',COALESCE(middleName,''),' ',COALESCE(lastName,'')) AS empFullname"), 'employeeinfo.inDeviceNo', 'employeeinfo.outDeviceNo')
                    ->leftJoin('attemployeemap', 'attemployeemap.employeeId', 'employeeinfo.id')
                    ->leftJoin('departments', 'departments.id', 'employeeinfo.fkDepartmentId')
                    ->orderBy('departments.orderBy', 'ASC')
                    ->orderBy('employeeinfo.id', 'ASC')
//                ->whereNotNull('employeeinfo.fkDepartmentId')
                    ->get();



            $results = DB::select(DB::raw("select em.employeeId,ad.id,sl.inTime,sl.outTime,sl.adjustmentDate,ad.fkAttDevice,sl.holiday,sl.weekend,ad.fkAttDevice
            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
            , date_format(ad.accessTime,'%H:%i:%s') accessTime
            , date_format(ad.accessTime,'%Y-%m-%d %H:%i:%s') accessTime2
            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
            and date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
            left join employeeinfo emInfo on em.employeeId = emInfo.id and emInfo.fkDepartmentId is not null
            
            where date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'"));

            $results = collect($results);


            $check = Excel::create($fileName, function ($excel) use ($results, $dates, $allEmp, $fromDate, $toDate, $startDate, $endDate,
                $allLeave, $allHoliday, $allWeekend,$govtHoliday) {



                        $excel->sheet('All Emp', function ($sheet) use ($results, $dates, $allEmp, $fromDate, $toDate, $startDate,
                                $endDate, $allLeave, $allHoliday, $allWeekend,$govtHoliday) {

                            $sheet->freezePane('C4');
//                            $sheet->setpaperSize(5);
//                            $sheet->setOrientation('landscape');
                            $sheet->setStyle(array(
                                'font' => array(
                                    'name' => 'Calibri',
                                    'size' => 10,
                                    'bold' => false
                                )
                            ));

                            $sheet->loadView('Excel.Final_Report_3', compact('results', 'fromDate', 'toDate', 'dates', 'allEmp',
                                'startDate', 'endDate', 'allLeave', 'allWeekend', 'allHoliday','govtHoliday'));
                        });
                    })->store('xls', $filePath);
        }

        // return $allEmp;



        return response()->json($fileName);
    }
    public function finalReportSummeryWithSalary(Request $r)
    {

        $fromDate = $r->startDate;
        $toDate = $r->endDate;


        ini_set('max_execution_time', 0);

        $startDate = Carbon::parse($fromDate);
        $endDate = Carbon::parse($toDate);

        $dates = $this->getDatesFromRange($startDate, $endDate);

        $fromDate = Carbon::parse($fromDate)->subDays(1);
        $toDate = Carbon::parse($toDate)->addDays(1);


        $filePath = public_path() . "/exportedExcel";

        $fileName = "Final_Report_1" . date("Y-m-d_H-i-s");
        $fileInfo = array(
            'fileName' => $fileName,
            'filePath' => $fileName,
        );




        if ($r->empId) {

            $govtHoliday=GovtHoliday::where('startDate','>=',$fromDate)->where('endDate','<=',$toDate)->where('status','Approved')->get();

            $govtHoliday=collect($govtHoliday);

            $allLeave = Leave::leftJoin('leavecategories', 'leavecategories.id', '=', 'leaves.fkLeaveCategory')
                    ->where('applicationStatus', "Approved")
                    ->whereIn('leaves.fkEmployeeId', $r->empId)
                    ->whereBetween('startDate', array($fromDate, $toDate))
                    ->get();

            $allLeave = collect($allLeave);

            $allWeekend = ShiftLog::whereNotNull('weekend')
                    ->whereIn('shiftlog.fkEmployeeId', $r->empId)
                    ->whereBetween('startDate', array($fromDate, $toDate))
                    ->get();

            $allWeekend = collect($allWeekend);

            $allHoliday = ShiftLog::whereNotNull('holiday')
                    ->whereIn('shiftlog.fkEmployeeId', $r->empId)
                    ->whereBetween('startDate', array($fromDate, $toDate))
                    ->get();

            $allHoliday = collect($allHoliday);

            $allEmp = Employee::select('employeeinfo.id', 'attemployeemap.attDeviceUserId', 'departments.departmentName',
                DB::raw("CONCAT(COALESCE(firstName,''),' ',COALESCE(middleName,''),' ',COALESCE(lastName,'')) AS empFullname"),
                'employeeinfo.inDeviceNo', 'employeeinfo.outDeviceNo','employeeinfo.salary','employeeinfo.pf_fund')
                    ->leftJoin('attemployeemap', 'attemployeemap.employeeId', 'employeeinfo.id')
                    ->leftJoin('departments', 'departments.id', 'employeeinfo.fkDepartmentId')
                    ->whereIn('employeeinfo.id', $r->empId)
                    ->orderBy('departments.orderBy', 'ASC')
                    ->orderBy('employeeinfo.id', 'ASC')
//                ->whereNotNull('employeeinfo.fkDepartmentId')
                    ->get();

            $List = implode(',', $r->empId);

            $results = DB::select(DB::raw("select em.employeeId,ad.id,sl.inTime,sl.outTime,sl.adjustmentDate,ad.fkAttDevice,sl.holiday,sl.weekend,ad.fkAttDevice
            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
            , date_format(ad.accessTime,'%H:%i:%s') accessTime
            , date_format(ad.accessTime,'%Y-%m-%d %H:%i:%s') accessTime2
            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
            and date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
            left join employeeinfo emInfo on em.employeeId = emInfo.id and emInfo.fkDepartmentId is not null
            
            where date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            and emInfo.id IN (" . $List . ")"));

            $results = collect($results);

            $check = Excel::create($fileName, function ($excel) use ($results, $dates, $allEmp, $fromDate, $toDate, $startDate, $endDate,
                $allLeave, $allHoliday, $allWeekend,$govtHoliday) {



                        $excel->sheet('emp', function ($sheet) use ($results, $dates, $allEmp, $fromDate, $toDate, $startDate,
                                $endDate, $allLeave, $allHoliday, $allWeekend,$govtHoliday) {

                            $sheet->freezePane('C4');
//                            $sheet->setpaperSize(5);
//                            $sheet->setOrientation('landscape');
                            $sheet->setStyle(array(
                                'font' => array(
                                    'name' => 'Calibri',
                                    'size' => 10,
                                    'bold' => false
                                )
                            ));

                            $sheet->loadView('Excel.FinalReportSummeryWithSalary', compact('results', 'fromDate', 'toDate', 'dates', 'allEmp',
                                'startDate', 'endDate', 'allLeave', 'allWeekend', 'allHoliday','govtHoliday'));
                        });
                    })->store('xls', $filePath);
        } else {

            $govtHoliday=GovtHoliday::where('startDate','>=',$fromDate)->where('endDate','<=',$toDate)->where('status','Approved')->get();

            $govtHoliday=collect($govtHoliday);

            $allLeave = Leave::leftJoin('leavecategories', 'leavecategories.id', '=', 'leaves.fkLeaveCategory')
                    ->where('applicationStatus', "Approved")
                    ->whereBetween('startDate', array($fromDate, $toDate))
                    ->get();

            $allLeave = collect($allLeave);

            $allWeekend = ShiftLog::whereNotNull('weekend')
                    ->whereBetween('startDate', array($fromDate, $toDate))
                    ->get();

            $allWeekend = collect($allWeekend);

            $allHoliday = ShiftLog::whereNotNull('holiday')
                    ->whereBetween('startDate', array($fromDate, $toDate))
                    ->get();

            $allHoliday = collect($allHoliday);

            $allEmp = Employee::select('employeeinfo.id', 'attemployeemap.attDeviceUserId', 'departments.departmentName',
                DB::raw("CONCAT(COALESCE(firstName,''),' ',COALESCE(middleName,''),' ',COALESCE(lastName,'')) AS empFullname"),
                'employeeinfo.inDeviceNo', 'employeeinfo.outDeviceNo','employeeinfo.salary','employeeinfo.pf_fund')
                    ->leftJoin('attemployeemap', 'attemployeemap.employeeId', 'employeeinfo.id')
                    ->leftJoin('departments', 'departments.id', 'employeeinfo.fkDepartmentId')
                    ->orderBy('departments.orderBy', 'ASC')
                    ->orderBy('employeeinfo.id', 'ASC')
//                ->whereNotNull('employeeinfo.fkDepartmentId')
                    ->get();



            $results = DB::select(DB::raw("select em.employeeId,ad.id,sl.inTime,sl.outTime,sl.adjustmentDate,ad.fkAttDevice,sl.holiday,sl.weekend,ad.fkAttDevice
            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
            , date_format(ad.accessTime,'%H:%i:%s') accessTime
            , date_format(ad.accessTime,'%Y-%m-%d %H:%i:%s') accessTime2
            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
            and date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
            left join employeeinfo emInfo on em.employeeId = emInfo.id and emInfo.fkDepartmentId is not null
            
            where date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'"));

            $results = collect($results);


            $check = Excel::create($fileName, function ($excel) use ($results, $dates, $allEmp, $fromDate, $toDate, $startDate, $endDate,
                $allLeave, $allHoliday, $allWeekend,$govtHoliday) {



                        $excel->sheet('All Emp', function ($sheet) use ($results, $dates, $allEmp, $fromDate, $toDate, $startDate,
                                $endDate, $allLeave, $allHoliday, $allWeekend,$govtHoliday) {

                            $sheet->freezePane('C4');
//                            $sheet->setpaperSize(5);
//                            $sheet->setOrientation('landscape');
                            $sheet->setStyle(array(
                                'font' => array(
                                    'name' => 'Calibri',
                                    'size' => 10,
                                    'bold' => false
                                )
                            ));

                            $sheet->loadView('Excel.FinalReportSummeryWithSalary', compact('results', 'fromDate', 'toDate', 'dates', 'allEmp',
                                'startDate', 'endDate', 'allLeave', 'allWeekend', 'allHoliday','govtHoliday'));
                        });
                    })->store('xls', $filePath);
        }

        // return $allEmp;



        return response()->json($fileName);
    }
    public function getRosterWiseReport_1(Request $r){

      //  return $r->empId;

        $fromDate = $r->startDate;
        $toDate = $r->endDate;


        ini_set('max_execution_time', 0);

        $startDate = Carbon::parse($fromDate);
        $endDate = Carbon::parse($toDate);

        $dates = $this->getDatesFromRange($startDate, $endDate);

        $fromDate = Carbon::parse($fromDate)->subDays(1);
        $toDate = Carbon::parse($toDate)->addDays(1);


        $filePath = public_path() . "/exportedExcel";

        $fileName = "Roster_Wise_Report" . date("Y-m-d_H-i-s");
        $fileInfo = array(
            'fileName' => $fileName,
            'filePath' => $fileName,
        );




        if ($r->empId) {

            $allTimeSwap=TimeSwap::whereBetween('date', array($fromDate, $toDate))
                ->where(function ($query) {
                    $query->where('departmentHeadApproval', '!=', '0')
                        ->orWhere('departmentHeadApproval', '!=', null);
                })->where(function ($query) {
                    $query->where('HR_adminApproval', '!=', '0')
                        ->orWhere('HR_adminApproval', '!=', null);
                })
                ->whereIn('time_swap.fkEmployeeId', $r->empId)
                ->get();

            $allTimeSwap=collect($allTimeSwap);

            $allLeave = Leave::leftJoin('leavecategories', 'leavecategories.id', '=', 'leaves.fkLeaveCategory')
                ->where('applicationStatus', "Approved")
                ->whereIn('leaves.fkEmployeeId', $r->empId)
                ->whereBetween('startDate', array($fromDate, $toDate))
                ->get();

            $allLeave = collect($allLeave);

            $allWeekend = ShiftLog::whereNotNull('weekend')
                ->whereIn('shiftlog.fkEmployeeId', $r->empId)
                ->whereBetween('startDate', array($fromDate, $toDate))
                ->get();

            $allWeekend = collect($allWeekend);

            $allHoliday = ShiftLog::whereNotNull('holiday')
                ->whereIn('shiftlog.fkEmployeeId', $r->empId)
                ->whereBetween('startDate', array($fromDate, $toDate))
                ->get();

            $allHoliday = collect($allHoliday);

            $govtHoliday=GovtHoliday::where('startDate','>=',$fromDate)->where('endDate','<=',$toDate)->where('status','Approved')->get();

            $govtHoliday=collect($govtHoliday);

            $allEmp = Employee::select('employeeinfo.id', 'attemployeemap.attDeviceUserId', 'departments.departmentName','designations.title as designationTitle',
                DB::raw("CONCAT(COALESCE(firstName,''),' ',COALESCE(middleName,''),' ',COALESCE(lastName,'')) AS empFullname"),
                'employeeinfo.inDeviceNo', 'employeeinfo.outDeviceNo','departments.id as deptId')
                ->leftJoin('attemployeemap', 'attemployeemap.employeeId', 'employeeinfo.id')
                ->leftJoin('departments', 'departments.id', 'employeeinfo.fkDepartmentId')
                ->leftJoin('designations', 'designations.id', 'employeeinfo.fkDesignation')
                ->whereIn('employeeinfo.id', $r->empId)
                ->orderBy('departments.orderBy', 'ASC')
                ->orderBy('employeeinfo.id', 'ASC')
//                ->whereNotNull('employeeinfo.fkDepartmentId')
                ->get();

          //  return $allEmp;

            $List = implode(',', $r->empId);

            if ($r->rosterId =='allRoster'){


                $RosterInfo=Shift::where('fkDepartmentId',$allEmp[0]['deptId'])->get();

                $newRosterArray=array();
               foreach ($RosterInfo as $RI){

                   $a=array($RI['shiftId']);
                   $newRosterArray=array_merge($newRosterArray,$a);
               }
                $roster= implode(',', $newRosterArray);


            }else{
                $RosterInfo=Shift::where('shiftId',$r->rosterId)->get();
                $roster= $r->rosterId;
            }



            $results = DB::select(DB::raw("select em.employeeId,ad.id,sl.inTime,sl.outTime,sl.adjustmentDate,ad.fkAttDevice,sl.holiday,sl.weekend,ad.fkAttDevice
            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
            , date_format(ad.accessTime,'%H:%i:%s') accessTime
            , date_format(ad.accessTime,'%Y-%m-%d %H:%i:%s') accessTime2
            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
            and date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            left join shiftlog sl on em.employeeId = sl.fkemployeeId 
            and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
            and date_format(ad.accessTime,'%H:%i:%s') between sl.inTime and sl.outTime
            left join employeeinfo emInfo on em.employeeId = emInfo.id and emInfo.fkDepartmentId is not null
            
            where sl.fkshiftId IN (" . $roster . ") and date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            and emInfo.id IN (" . $List . ")"));

            $results = collect($results);
        }

      //  return $RosterInfo;

//        $check = Excel::create($fileName, function ($excel) use ($results, $dates, $allEmp, $fromDate, $toDate, $startDate, $endDate, $allLeave, $allHoliday,
//            $allWeekend,$govtHoliday,$RosterInfo) {
//
//            foreach ($allEmp as $allE) {
//
//                $excel->sheet($allE->attDeviceUserId, function ($sheet) use ($results, $allE, $dates, $allEmp, $fromDate, $toDate, $startDate,
//                    $endDate, $allLeave, $allHoliday, $allWeekend,$govtHoliday,$RosterInfo) {
//
//                    $sheet->freezePane('B5');
////                    $sheet->setpaperSize(5);
////                    $sheet->setOrientation('landscape');
//
//                    $sheet->setStyle(array(
//                        'font' => array(
//                            'name' => 'Calibri',
//                            'size' => 10,
//                            'bold' => false
//                        )
//                    ));
//
//                    $sheet->loadView('Excel.Roster_Wise_Report_1', compact('results', 'allE', 'fromDate', 'toDate', 'dates', 'allEmp',
//                        'startDate', 'endDate', 'allLeave', 'allWeekend', 'allHoliday','govtHoliday','RosterInfo'));
//                });
//            }
//        })->store('xls', $filePath);


        $check = Excel::create($fileName, function ($excel) use ($results, $dates, $allEmp, $fromDate, $toDate, $startDate, $endDate, $allLeave, $allHoliday,
            $allWeekend,$govtHoliday,$RosterInfo,$allTimeSwap) {

            foreach ($allEmp as $allE) {

                $excel->sheet($allE->attDeviceUserId, function ($sheet) use ($results, $allE, $dates, $allEmp, $fromDate, $toDate, $startDate,
                    $endDate, $allLeave, $allHoliday, $allWeekend,$govtHoliday,$RosterInfo,$allTimeSwap) {

                    $sheet->freezePane('B5');
//                    $sheet->setpaperSize(5);
//                    $sheet->setOrientation('landscape');

                    $sheet->setStyle(array(
                        'font' => array(
                            'name' => 'Calibri',
                            'size' => 10,
                            'bold' => false
                        )
                    ));

                    $sheet->loadView('Excel.Multiple_Roster_Wise_Report_1', compact('results', 'allE', 'fromDate', 'toDate', 'dates', 'allEmp',
                        'startDate', 'endDate', 'allLeave', 'allWeekend', 'allHoliday','govtHoliday','RosterInfo','allTimeSwap'));
                });
            }
        })->store('xls', $filePath);






        return response()->json($fileName);



    }
    public function getRosterWiseReport_3(Request $r)
    {

        $fromDate = $r->startDate;
        $toDate = $r->endDate;


        ini_set('max_execution_time', 0);

        $startDate = Carbon::parse($fromDate);
        $endDate = Carbon::parse($toDate);

        $dates = $this->getDatesFromRange($startDate, $endDate);

        $fromDate = Carbon::parse($fromDate)->subDays(1);
        $toDate = Carbon::parse($toDate)->addDays(1);


        $filePath = public_path() . "/exportedExcel";

        $fileName = "Roster_Wise_Report" . date("Y-m-d_H-i-s");
        $fileInfo = array(
            'fileName' => $fileName,
            'filePath' => $fileName,
        );



         $RosterInfo=Shift::findOrFail($r->rosterId);


        if ($r->empId) {

            $allLeave = Leave::leftJoin('leavecategories', 'leavecategories.id', '=', 'leaves.fkLeaveCategory')
                ->where('applicationStatus', "Approved")
                ->whereIn('leaves.fkEmployeeId', $r->empId)
                ->whereBetween('startDate', array($fromDate, $toDate))
                ->get();

            $allLeave = collect($allLeave);

            $allWeekend = ShiftLog::whereNotNull('weekend')
                ->whereIn('shiftlog.fkEmployeeId', $r->empId)
                ->whereBetween('startDate', array($fromDate, $toDate))
                ->get();

            $allWeekend = collect($allWeekend);

            $allHoliday = ShiftLog::whereNotNull('holiday')
                ->whereIn('shiftlog.fkEmployeeId', $r->empId)
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
                ->whereIn('employeeinfo.id', $r->empId)

                ->orderBy('departments.orderBy', 'ASC')
                ->orderBy('employeeinfo.id', 'ASC')
//                ->whereNotNull('employeeinfo.fkDepartmentId')
                ->get();

            $List = implode(',', $r->empId);

            $results = DB::select(DB::raw("select em.employeeId,ad.id,sl.inTime,sl.outTime,sl.adjustmentDate,ad.fkAttDevice,sl.holiday,sl.weekend,ad.fkAttDevice
            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
            , date_format(ad.accessTime,'%H:%i:%s') accessTime
            , date_format(ad.accessTime,'%Y-%m-%d %H:%i:%s') accessTime2
            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
            and date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
            left join employeeinfo emInfo on em.employeeId = emInfo.id and emInfo.fkDepartmentId is not null
            
            where sl.fkshiftId= '" . $r->rosterId . "' and date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            and emInfo.id IN (" . $List . ")"));

            $results = collect($results);
        }

        $check = Excel::create($fileName, function ($excel) use ($results, $dates, $allEmp, $fromDate, $toDate, $startDate, $endDate, $allLeave, $allHoliday,
            $allWeekend,$govtHoliday,$RosterInfo) {



                $excel->sheet('emp', function ($sheet) use ($results, $dates, $allEmp, $fromDate, $toDate, $startDate,
                    $endDate, $allLeave, $allHoliday, $allWeekend,$govtHoliday,$RosterInfo) {

                    $sheet->freezePane('B5');

                    $sheet->setStyle(array(
                        'font' => array(
                            'name' => 'Calibri',
                            'size' => 10,
                            'bold' => false
                        )
                    ));

                    $sheet->loadView('Excel.Roster_Wise_Report_3', compact('results',  'fromDate', 'toDate', 'dates', 'allEmp',
                        'startDate', 'endDate', 'allLeave', 'allWeekend', 'allHoliday','govtHoliday','RosterInfo'));
                });

        })->store('xls', $filePath);



        return response()->json($fileName);



    }

    public function getRosterWiseReport_2(Request $r) {

        $fromDate = $r->startDate;
        $toDate = $r->endDate;


        ini_set('max_execution_time', 0);

        $startDate = Carbon::parse($fromDate);
        $endDate = Carbon::parse($toDate);

        $dates = $this->getDatesFromRange($startDate, $endDate);

        $fromDate = Carbon::parse($fromDate)->subDays(1);
        $toDate = Carbon::parse($toDate)->addDays(1);


        $filePath = public_path() . "/exportedExcel";

        $fileName = "Final_Report_1" . date("Y-m-d_H-i-s");
        $fileInfo = array(
            'fileName' => $fileName,
            'filePath' => $fileName,
        );



        $RosterInfo=Shift::findOrFail($r->rosterId);

        if ($r->empId) {



            $allEmp = Employee::select('employeeinfo.id', 'designations.title as designationTitle','attemployeemap.attDeviceUserId', 'departments.departmentName', DB::raw("CONCAT(COALESCE(firstName,''),' ',COALESCE(middleName,''),' ',COALESCE(lastName,'')) AS empFullname"), 'employeeinfo.inDeviceNo', 'employeeinfo.outDeviceNo')
                ->leftJoin('attemployeemap', 'attemployeemap.employeeId', 'employeeinfo.id')
                ->leftJoin('departments', 'departments.id', 'employeeinfo.fkDepartmentId')
                ->leftJoin('designations', 'designations.id', 'employeeinfo.fkDesignation')
                ->whereIn('employeeinfo.id', $r->empId)
                ->orderBy('departments.orderBy', 'ASC')
                ->orderBy('employeeinfo.id', 'ASC')
//                ->whereNotNull('employeeinfo.fkDepartmentId')
                ->get();

            $List = implode(',', $r->empId);

            $results = DB::select(DB::raw("select em.employeeId,ad.id,sl.inTime,sl.outTime,sl.adjustmentDate,ad.fkAttDevice,sl.holiday,sl.weekend,ad.fkAttDevice
            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
            , date_format(ad.accessTime,'%H:%i:%s') accessTime
            , date_format(ad.accessTime,'%Y-%m-%d %H:%i:%s') accessTime2
            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
            and date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
            left join employeeinfo emInfo on em.employeeId = emInfo.id and emInfo.fkDepartmentId is not null
            
            where sl.fkshiftId= '" . $r->rosterId . "' and date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            and emInfo.id IN (" . $List . ")"));

            $results = collect($results);


        }

        $check = Excel::create($fileName, function ($excel) use ($results, $dates, $allEmp, $fromDate, $toDate, $startDate, $endDate,$RosterInfo) {

            foreach ($allEmp as $allE) {

                $excel->sheet($allE->attDeviceUserId, function ($sheet) use ($results, $allE, $dates, $allEmp, $fromDate, $toDate, $startDate,
                    $endDate,$RosterInfo) {

                    $sheet->freezePane('B5');
                    $sheet->setStyle(array(
                        'font' => array(
                            'name' => 'Calibri',
                            'size' => 10,
                            'bold' => false
                        )
                    ));

                    $sheet->loadView('Excel.Roster_Wise_Report_2', compact('results', 'allE', 'fromDate', 'toDate', 'dates', 'allEmp',
                        'startDate', 'endDate','RosterInfo'));
                });
            }
        })->store('xls', $filePath);





        return response()->json($fileName);
    }

    public function multipleRosterWiseReport_1(Request $r){


        $fromDate = $r->startDate;
        $toDate = $r->endDate;


        ini_set('max_execution_time', 0);

        $startDate = Carbon::parse($fromDate);
        $endDate = Carbon::parse($toDate);

        $dates = $this->getDatesFromRange($startDate, $endDate);

        $fromDate = Carbon::parse($fromDate)->subDays(1);
        $toDate = Carbon::parse($toDate)->addDays(1);


        $filePath = public_path() . "/exportedExcel";

        $fileName = "Multiple_Roster_Wise_Report" . date("Y-m-d_H-i-s");
        $fileInfo = array(
            'fileName' => $fileName,
            'filePath' => $fileName,
        );


        if ($r->empId) {

            $rosrLog=ShiftLog::whereIn('fkemployeeId', $r->empId)
                ->whereBetween('startDate', array($fromDate, $toDate))
                ->get();

            $rosrLog=collect($rosrLog);

            $allLeave = Leave::leftJoin('leavecategories', 'leavecategories.id', '=', 'leaves.fkLeaveCategory')
                ->where('applicationStatus', "Approved")
                ->whereIn('leaves.fkEmployeeId', $r->empId)
                ->whereBetween('startDate', array($fromDate, $toDate))
                ->get();

            $allLeave = collect($allLeave);

            $allWeekend = ShiftLog::whereNotNull('weekend')
                ->whereIn('shiftlog.fkEmployeeId', $r->empId)
                ->whereBetween('startDate', array($fromDate, $toDate))
                ->get();

            $allWeekend = collect($allWeekend);

            $allHoliday = ShiftLog::whereNotNull('holiday')
                ->whereIn('shiftlog.fkEmployeeId', $r->empId)
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
                ->whereIn('employeeinfo.id', $r->empId)
                ->orderBy('departments.orderBy', 'ASC')
                ->orderBy('employeeinfo.id', 'ASC')
//                ->whereNotNull('employeeinfo.fkDepartmentId')
                ->get();

            $List = implode(',', $r->empId);

            $results = DB::select(DB::raw("select em.employeeId,ad.id,sl.inTime,sl.outTime,sl.adjustmentDate,ad.fkAttDevice,sl.holiday,sl.weekend,ad.fkAttDevice
            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
            , date_format(ad.accessTime,'%H:%i:%s') accessTime
            , date_format(ad.accessTime,'%Y-%m-%d %H:%i:%s') accessTime2
            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
            and date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
            left join employeeinfo emInfo on em.employeeId = emInfo.id and emInfo.fkDepartmentId is not null
            
            where date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            and emInfo.id IN (" . $List . ")"));

            $results = collect($results);

        } else {

            $rosrLog=ShiftLog::whereBetween('startDate', array($fromDate, $toDate))
                ->get();
            $rosrLog=collect($rosrLog);

            $allLeave = Leave::leftJoin('leavecategories', 'leavecategories.id', '=', 'leaves.fkLeaveCategory')
                ->where('applicationStatus', "Approved")
                ->whereBetween('startDate', array($fromDate, $toDate))
                ->get();

            $allLeave = collect($allLeave);

            $allWeekend = ShiftLog::whereNotNull('weekend')
                ->whereBetween('startDate', array($fromDate, $toDate))
                ->get();


            $allWeekend = collect($allWeekend);

            $allHoliday = ShiftLog::whereNotNull('holiday')
                ->whereBetween('startDate', array($fromDate, $toDate))
                ->get();

            $allHoliday = collect($allHoliday);

            $govtHoliday=GovtHoliday::where('startDate','>=',$fromDate)->where('endDate','<=',$toDate)->where('status','Approved')->get();

            $govtHoliday=collect($govtHoliday);

            $allEmp = Employee::select('employeeinfo.id', 'attemployeemap.attDeviceUserId', 'departments.departmentName', DB::raw("CONCAT(COALESCE(firstName,''),' ',COALESCE(middleName,''),' ',COALESCE(lastName,'')) AS empFullname"), 'employeeinfo.inDeviceNo', 'employeeinfo.outDeviceNo')
                ->leftJoin('attemployeemap', 'attemployeemap.employeeId', 'employeeinfo.id')
                ->leftJoin('departments', 'departments.id', 'employeeinfo.fkDepartmentId')
                ->orderBy('departments.orderBy', 'ASC')
                ->orderBy('employeeinfo.id', 'ASC')
//                ->whereNotNull('employeeinfo.fkDepartmentId')
                ->get();

            $results = DB::select(DB::raw("select em.employeeId,ad.id,sl.inTime,sl.outTime,sl.adjustmentDate,ad.fkAttDevice,sl.holiday,sl.weekend,ad.fkAttDevice
            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
            , date_format(ad.accessTime,'%H:%i:%s') accessTime
            , date_format(ad.accessTime,'%Y-%m-%d %H:%i:%s') accessTime2
            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
            and date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
            left join employeeinfo emInfo on em.employeeId = emInfo.id and emInfo.fkDepartmentId is not null
            
            where  date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'"));

            $results = collect($results);

        }

        $check = Excel::create($fileName, function ($excel) use ($results, $dates, $allEmp, $fromDate, $toDate, $startDate, $endDate, $allLeave, $allHoliday,
            $allWeekend,$govtHoliday,$rosrLog) {

            foreach ($allEmp as $allE) {

                $excel->sheet($allE->attDeviceUserId, function ($sheet) use ($results, $allE, $dates, $allEmp, $fromDate, $toDate, $startDate,
                    $endDate, $allLeave, $allHoliday, $allWeekend,$govtHoliday,$rosrLog) {

                    $sheet->freezePane('B5');
//                    $sheet->setpaperSize(5);
//                    $sheet->setOrientation('landscape');

                    $sheet->setStyle(array(
                        'font' => array(
                            'name' => 'Calibri',
                            'size' => 10,
                            'bold' => false
                        )
                    ));

                    $sheet->loadView('Excel.Multiple_Roster_Wise_Report_1', compact('results', 'allE', 'fromDate', 'toDate', 'dates', 'allEmp',
                        'startDate', 'endDate', 'allLeave', 'allWeekend', 'allHoliday','govtHoliday','rosrLog'));
                });
            }
        })->store('xls', $filePath);



        return response()->json($fileName);



    }

}
