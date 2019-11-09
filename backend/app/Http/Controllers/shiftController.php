<?php

namespace App\Http\Controllers;

use App\Shift;
use App\ShiftLog;
use DateTime;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use DB;

class shiftController extends Controller
{
    function getDatesFromRange(Request $r, $format = 'Y-m-d') {
        $array = array();
        $start=$r->startDate;
        $end=$r->endDate;

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
    function getDatesFromRanges($startDate, $endDate, $format = 'Y-m-d') {
        $array = array();

        $interval = new \DateInterval('P1D');
        $realEnd = new DateTime($endDate);
        $realEnd->add($interval);
        $anotherFormat='l';
        $period = new \DatePeriod(new DateTime($startDate), $interval, $realEnd);
        foreach($period as $date) {
            $newArray=array(
                'date'=>  $date->format($format),
                'day'=>$date->format($anotherFormat),
            );
            array_push($array,$newArray);

        }
        return $array;
    }
    function getDatesFromRangeAssignedShift(Request $r, $format = 'Y-m-d') {




        $array = array();
        $start=$r->startDate;
        $end=$r->endDate;

        $interval = new \DateInterval('P1D');
        $realEnd = new DateTime($end);
        $realEnd->add($interval);
        $anotherFormat='l';
        $period = new \DatePeriod(new DateTime($start), $interval, $realEnd);
        foreach($period as $date) {


        /*    $shiftName = ShiftLog::select('shiftlog.*','attemployeemap.attDeviceUserId',DB::raw("CASE When shiftlog.fkshiftId is null then CONCAT(shiftlog.inTime,'-',shiftlog.outTime) else GROUP_CONCAT(shift.shiftName) end shiftName"))
                ->leftJoin('shift',"shift.shiftId",'shiftlog.fkshiftId')
                ->leftJoin('attemployeemap',"attemployeemap.employeeId",'shiftlog.fkemployeeId')
                ->where('shiftlog.fkemployeeId','=',$r->empId)
                ->whereDate('shiftlog.startDate', '=', $date->format($format))
                ->where(function ($query) use ($format,$date){
                    $query->whereDate('shiftlog.endDate', '=', $date->format($format));
//                    ->orWhere('endDate', '=', 1);
                })
                ->orderBy('shiftlog.shiftlogId','ASC')
                ->first();
        */

            $shiftName = ShiftLog::select('shiftlog.*','attemployeemap.attDeviceUserId',DB::raw("CONCAT(shiftlog.inTime,'-',shiftlog.outTime) as shiftName"))
                ->leftJoin('shift',"shift.shiftId",'shiftlog.fkshiftId')
                ->leftJoin('attemployeemap',"attemployeemap.employeeId",'shiftlog.fkemployeeId')
                ->where('shiftlog.fkemployeeId','=',$r->empId)
                ->whereDate('shiftlog.startDate', '=', $date->format($format))
                ->where(function ($query) use ($format,$date){
                    $query->whereDate('shiftlog.endDate', '=', $date->format($format));
//                    ->orWhere('endDate', '=', 1);
                })
                ->orderBy('shiftlog.shiftlogId','ASC')
                ->first();


            $newArray=array(
                'date'=>  $date->format($format),
                'day'=>$date->format($anotherFormat),
                'shiftName'=>$shiftName['shiftName'],
                'shiftLogId'=>$shiftName['shiftlogId'],
                'empId'=>$shiftName['fkemployeeId'],
                'attDeviceUserId'=>$shiftName['attDeviceUserId'],
                'inTime'=>$shiftName['inTime'],
                'outTime'=>$shiftName['outTime'],
                'shiftId'=>$shiftName['fkshiftId'],
                'adjustmentDate'=>$shiftName['adjustmentDate'],
                'weekend'=>$shiftName['weekend'],
                'holiday'=>$shiftName['holiday']
            );
            array_push($array,$newArray);
//            $array['date'] = $date->format($format);
//            $array['day'] = $date->format($anotherFormat);
        }
        return $array;
    }
    function getEmpRangeNotAssignedShift(Request $r, $format = 'Y-m-d') {


        $array = array();
        $start=$r->startDate;
        $end=$r->endDate;

        $interval = new \DateInterval('P1D');
        $realEnd = new DateTime($end);
        $realEnd->add($interval);
        $anotherFormat='l';
        $period = new \DatePeriod(new DateTime($start), $interval, $realEnd);
        foreach($period as $date) {

            $shiftName = \DB::table('employeeinfo')
                ->select(
                    'employeeinfo.id',
                    'employeeinfo.firstName',
                    'attemployeemap.attDeviceUserId'

                )
                ->leftJoin('attemployeemap',"attemployeemap.employeeId",'employeeinfo.id')

                ->whereNotExists( function ($query) use ($date,$format,$r) {
                    $query->select(DB::raw(1))
                        ->from('shiftlog')
                        ->whereRaw('attemployeemap.employeeId = shiftlog.fkemployeeId')
                        ->where('shiftlog.fkemployeeId','=',$r->empId)
                        ->whereDate('shiftlog.startDate', '=', $date->format($format))
                        ->where(function ($query) use ($format,$date){
                            $query->whereDate('shiftlog.endDate', '=', $date->format($format));

                        })
                        ->orderBy('shiftlog.shiftlogId','ASC');
                })
                ->where('employeeinfo.id',$r->empId)
                ->first();

            if ($shiftName){

                $newArray=array(
                    'date'=>  $date->format($format),
                    'day'=>$date->format($anotherFormat),
                    'empId'=>$r->empId,
                    'attDeviceUserId'=>$shiftName->attDeviceUserId,
                    'shiftName'=>null,
                    'adjustmentDate'=>null,
                    'weekend'=>null

                );
                array_push($array,$newArray);

            }

        }
        return $array;
    }

    function getEmpNameFromRangeNotAssignedShift(Request $r, $format = 'Y-m-d')
    {


        $array = array();
        $start=$r->startDate;
        $end=$r->endDate;

        $interval = new \DateInterval('P1D');
        $realEnd = new DateTime($end);
        $realEnd->add($interval);
        $anotherFormat='l';
       // $period = new \DatePeriod(new DateTime($start), $interval, $realEnd);

            $emp = \DB::table('employeeinfo')
                ->select(
                    'employeeinfo.id',
                    'employeeinfo.firstName',
                    'attemployeemap.attDeviceUserId'

                )
                ->leftJoin('attemployeemap',"attemployeemap.employeeId",'employeeinfo.id')
                ->whereNotExists( function ($query) use ($start,$end) {
                    $query->select(DB::raw(1))
                        ->from('shiftlog')
                        ->whereRaw('employeeinfo.id = shiftlog.fkemployeeId')
                        ->groupBy('shiftlog.fkemployeeId')
                        ->whereDate('shiftlog.startDate', '>=', $start)
                        ->where(function ($query) use ($end){
                            $query->whereDate('shiftlog.endDate', '<=', $end);

                        });
                })
                ->whereNotNull('employeeinfo.fkDepartmentId')
                ->get();



        return $emp;
    }

    public function getShiftName(){
        $shift = Shift::select('shift.*','departments.departmentName','departments.id as deptId')->leftJoin('departments','departments.id','shift.fkDepartmentId')->orderBy('shiftId','desc')->get();

        return response()->json($shift);
    }
    public function createShift(Request $r){


        $this->validate($r,[
            'shiftName' => 'required|max:20',
            'inTime' => 'required',
            'outTime' => 'required',
        ]);
        if($r->shiftId ==null){
            $shift = new Shift();
        }
        else{
            $shift = Shift::findOrFail($r->shiftId);
        }

        $shift->shiftName= $r->shiftName;
        $shift->inTime = $r->inTime  ;
        $shift->outTime = $r->outTime;
        $shift->crateBy= auth()->user()->id;
        $shift->fkcompanyId= auth()->user()->fkCompany;
        $shift->fkDepartmentId= $r->fkDepartmentId;
        $shift->save();

        return Response()->json("Success");
    }
    public function getUserShift(Request $r){
        $shiftName = ShiftLog::where('fkemployeeId','=',$r->fkemployeeId)->orderBy('shiftlogId','desc')->first();
        return response()->json($shiftName);
    }

    public function getAllShift(Request $r){
        $shift = Shift::where('fkcompanyId',auth()->user()->fkCompany);
        $datatables = Datatables::of($shift);
        return $datatables->make(true);
    }
    public function getEmpShiftForUpdate(Request $r){


        return $shift = ShiftLog::where('fkemployeeId','=',$r->empId)
            ->whereDate('startDate', '>=', Carbon::parse($r->startDate)->format('y-m-d'))
            ->where(function ($query) use ($r){
                $query->where('endDate', '<=', Carbon::parse($r->endDate)->format('y-m-d'));
//                    ->orWhere('endDate', '=', 1);
            })
//            ->whereDate('endDate', '<=', Carbon::parse($r->endDate)->format('y-m-d'))
            ->get();

    }
    public function assignToShift(Request $r){


        $days=array();
        for ($i=0;$i<count($r->weekends);$i++){
            array_push($days,$r->weekends[$i]['item_id']);
        }

        $tags = implode(',',$days);
        $date= Carbon::parse($r->startDate)->format('y-m-d');
        $subDate=  Carbon::parse($r->startDate)->subDays(1)->format('y-m-d');



        foreach ($r->allEmp as $empId){

            foreach ($r->newShiftLog as $sfL){

                $log=new ShiftLog();

                $log->fkemployeeId=$empId;
                $log->fkshiftId=$sfL[0]['shift'];
                $log->startDate=$sfL[0]['date'];
                $log->endDate=$sfL[0]['date'];
                $log->weekend=$tags;

                $log->save();

            }

        }
        return Response()->json("Success");
    }
    public function updateShiftAssignedLog(Request $r){

        /* for multiple shift

        $shiftDelete=ShiftLog::where('fkemployeeId',$r->empId)->whereDate('startDate',$r->date)->whereDate('endDate',$r->date)->whereIn('fkshiftId',$r->shiftId)->delete();

        if (count($r->shiftId)>0){

           for ($i=0;$i<count($r->shiftId);$i++){

               $shift=Shift::select('inTime','outTime')->findOrFail($r->shiftId[$i]);

               $shiftLog=new ShiftLog();

               $shiftLog->fkemployeeId=$r->empId;
               $shiftLog->startDate=$r->date;
               $shiftLog->endDate=$r->date;
               $shiftLog->inTime=$shift->inTime;
               $shiftLog->outTime=$shift->outTime;

               if ($r->adjustment=='true'){
                   $shiftLog->adjustmentDate=Carbon::parse($r->adjustmentDate);
               }

               $shiftLog->fkshiftId=$r->shiftId[$i];

               if ($i==0 && count($r->shiftId)>1){

                   $shiftLog->multipleShift=$r->shiftId[(count($r->shiftId)-1)];
               }

               $shiftLog->save();

           }



        }else{

            $shiftLog=new ShiftLog();

            $shiftLog->fkemployeeId=$r->empId;
            $shiftLog->startDate=$r->date;
            $shiftLog->endDate=$r->date;
            $shiftLog->inTime=$r->inTime;
            $shiftLog->outTime=$r->outTime;
            if ($r->adjustment=='true'){
                $shiftLog->adjustmentDate=Carbon::parse($r->adjustmentDate);
            }


            $shiftLog->save();



        }

        */


        /* single shift */

        if ($r->shiftLogId !=null){
            $shiftLog=ShiftLog::findOrFail($r->shiftLogId);
        }else{
            $shiftLog=new ShiftLog();
        }

        $shiftLog->fkemployeeId=$r->empId;
        $shiftLog->startDate=$r->date;
        $shiftLog->endDate=$r->date;
        $shiftLog->inTime=$r->inTime;
        $shiftLog->outTime=$r->outTime;

        $shiftLog->fkshiftId=$r->shiftId;

        if ($r->adjustment=='true'){
            $shiftLog->adjustmentDate=Carbon::parse($r->adjustmentDate);
        }

        $shiftLog->save();


        return Response()->json("Success");
    }
    public function deleteShiftAssignedLog(Request $r){


//        $shiftLog=ShiftLog::destroy($r->shiftLogId);

        $shiftLog=ShiftLog::where('fkemployeeId',$r->empId)->whereDate('startDate',$r->date)->whereDate('endDate',$r->date)->delete();

        return Response()->json("Success");
    }
    public function getShiftInfo($shiftId)
    {
       return $shift=Shift::findOrFail($shiftId);
    }
    public function AssignFutureShift(Request $r)
    {


        $start=Carbon::parse($r->startDate);
        $end=Carbon::parse($r->endDate);

        $futureStart=Carbon::parse($r->futureStartDate);
        $futureEnd=Carbon::parse($r->futureEndDate);

      //  return $r;

         $dates1 = $this->getDatesFromRanges($start, $end);
         $dates2 = $this->getDatesFromRanges($futureStart, $futureEnd);

        $dates2 = collect($dates2);
        $dates1 = collect($dates1);

        foreach ($dates1 as $d1){

            foreach ($dates2 as $d2){

                if ($d1['day']==$d2['day'])
                {

                    $deleteOldS=ShiftLog::whereDate('startDate',$d2['date'])->whereDate('endDate',$d2['date'])->where('fkemployeeId',$r->empId)->first();
                       if ($deleteOldS)
                       {

                           $deleteOldS->delete();
                       }

                    $oldLog=ShiftLog::whereDate('startDate',$d1['date'])->whereDate('endDate',$d1['date'])->where('fkemployeeId',$r->empId)->first();

                    if ($oldLog)
                    {

                        if (!$oldLog->holiday){

                            $newLog=new ShiftLog();

                            $newLog->fkemployeeId=$r->empId;
                            $newLog->startDate=$d2['date'];
                            $newLog->endDate=$d2['date'];
                            $newLog->inTime=$oldLog->inTime;
                            $newLog->outTime=$oldLog->outTime;
                            $newLog->fkshiftId=$oldLog->fkshiftId;
                            //$newLog->adjustmentDate=$oldLog->adjustmentDate;
                            if($oldLog->weekend){

                                $newLog->weekend=$d2['date'];

                            }


                            $newLog->save();


                        }

                    }



                }

            }

        }

//        $result = $dates2->map(function ($a) use ($dates1,$r) {
//            $day = $a['day'];
//            $date = $a['date'];
//
//            $res = $dates1->map(function ($val) use ($day,$r,$date) {
//               if ($val['day'] == $day){
//
//                   $deleteOldS=ShiftLog::where('startDate',$date)->where('endDate',$date)->where('fkemployeeId',$r->empId)->first();
//                   if ($deleteOldS)
//                   {
//
//                       $deleteOldS->delete();
//                   }
//
//
//                   $oldLog=ShiftLog::where('startDate',$val['date'])->where('endDate',$val['date'])->where('fkemployeeId',$r->empId)->first();
//
//                   if ($oldLog)
//                   {
//
//                       if (!$oldLog->holiday){
//
//                           $newLog=new ShiftLog();
//
//                           $newLog->fkemployeeId=$r->empId;
//                           $newLog->startDate=$date;
//                           $newLog->endDate=$date;
//                           $newLog->inTime=$oldLog->inTime;
//                           $newLog->outTime=$oldLog->outTime;
//                           $newLog->fkshiftId=$oldLog->fkshiftId;
//                           //$newLog->adjustmentDate=$oldLog->adjustmentDate;
//                           if($oldLog->weekend){
//
//                               $newLog->weekend=$date;
//
//                           }
//
//
//                           $newLog->save();
//
//
//                       }
//
//                   }
//
//
//               }
//            });
//
//        });

    }
    public function setshiftLogweekend(Request $r){



        if ($r->direction=='Add'){

            if ($r->shiftLogId=="" || $r->shiftLogId==null){
                $shiftLog=new ShiftLog();

            }else {
                $shiftLog = ShiftLog::findOrFail($r->shiftLogId);
            }
            $shiftLog->fkemployeeId=$r->empId;
            $shiftLog->startDate=$r->date;
            $shiftLog->endDate=$r->date;

            $shiftLog->weekend=$r->date;
            $shiftLog->save();

        }elseif ($r->direction=='Remove'){

            $shiftLog = ShiftLog::findOrFail($r->shiftLogId);

            if ($shiftLog->inTime==null && $shiftLog->outTime==null){

                $shiftLog->delete();

            }else{
                $shiftLog->weekend=null;
                $shiftLog->save();
            }

        }


    }

    public function setshiftLogholiday(Request $r){



        if ($r->direction=='Add'){

            if ($r->shiftLogId=="" || $r->shiftLogId==null){
                $shiftLog=new ShiftLog();

            }else {
                $shiftLog = ShiftLog::findOrFail($r->shiftLogId);
            }
            $shiftLog->fkemployeeId=$r->empId;
            $shiftLog->startDate=$r->date;
            $shiftLog->endDate=$r->date;

            $shiftLog->holiday=$r->date;
            $shiftLog->save();

        }elseif ($r->direction=='Remove'){

            $shiftLog = ShiftLog::findOrFail($r->shiftLogId);

            if ($shiftLog->inTime==null && $shiftLog->outTime==null){

                $shiftLog->delete();

            }else{
                $shiftLog->holiday=null;
                $shiftLog->save();
            }

        }


    }

    public function addjustmentShiftLog(Request $r){

        $shiftLog=ShiftLog::findOrFail($r->shiftLogId);

        if($r->adjustmentDate==""){
            $shiftLog->adjustmentDate=null;
        }
        else{

            $shiftLog->adjustmentDate=Carbon::parse($r->adjustmentDate);
        }
        if (count($r->shiftId)==0){
            $shiftLog->fkshiftId=null;

        }else{
            $shiftLog->fkshiftId=$r->shiftId[0];
        }

        $shiftLog->inTime=$r->inTime;
        $shiftLog->outTime=$r->outTime;

        $shiftLog->save();

        return Response()->json("Success");


    }
    public function getRosterInfo(Request $r){

        return $Roster=Shift::where('fkDepartmentId',$r->departments)->get();



    }
    public function getEmpAndRoster(Request $r){

       return $roster=Shift::select('shift.shiftId',DB::raw("GROUP_CONCAT(shiftlog.fkemployeeId) as EmpRosterId"),
           DB::raw("CONCAT(shift.shiftname,'(',shift.inTime,'-',shift.outTime,')') as shiftName"),
           DB::raw("TRIM(BOTH '  ,  ' FROM GROUP_CONCAT(COALESCE(EmpDuty.firstName,''),' ',COALESCE(EmpDuty.middleName,''),' ',COALESCE(EmpDuty.lastName,''))) as EmpRosterNames"),
           DB::raw("TRIM(BOTH '  ,' FROM GROUP_CONCAT(COALESCE(EmpOffDuty.firstName,''),' ',COALESCE(EmpOffDuty.middleName,''),' ',COALESCE(EmpOffDuty.lastName,''))) as EmpRosterOffDutyNames"))
           ->where('shift.fkDepartmentId',$r->departments)

           ->leftJoin('shiftlog',function($join) use($r) {
               $join->on('shiftlog.fkshiftId', '=', 'shift.shiftId')
                   ->where('shiftlog.startDate',$r->date)->where('shiftlog.endDate',$r->date);
           })
           ->leftJoin('employeeinfo as EmpDuty',function($join) {
               $join->on('EmpDuty.id', '=', 'shiftlog.fkemployeeId')
                   ->whereNull('shiftlog.weekend');
           })
            ->leftJoin('employeeinfo as EmpOffDuty',function($join) {
               $join->on('EmpOffDuty.id', '=', 'shiftlog.fkemployeeId')
                   ->whereNotNull('shiftlog.weekend');
           })


           ->groupBy('shift.shiftId')
           ->get();

    }
    public function getEmpAndStaticRoster(Request $r){

       return $roster=Shift::select('static_rosterlog.day','shift.shiftId',DB::raw("GROUP_CONCAT(static_rosterlog.fkemployeeId) as EmpRosterId"),
           DB::raw("CONCAT(shift.shiftname,'(',shift.inTime,'-',shift.outTime,')') as shiftName"),
           DB::raw("TRIM(BOTH '  ,' FROM GROUP_CONCAT(COALESCE(EmpDuty.firstName,''),' ',COALESCE(EmpDuty.middleName,''),' ',COALESCE(EmpDuty.lastName,''))) as EmpRosterNames"),
           DB::raw("TRIM(BOTH '  ,' FROM GROUP_CONCAT(COALESCE(EmpOffDuty.firstName,''),' ',COALESCE(EmpOffDuty.middleName,''),' ',COALESCE(EmpOffDuty.lastName,''))) as EmpRosterOffDutyNames"))
           ->where('shift.fkDepartmentId',$r->departments)

           ->join('static_rosterlog',function($join) use($r) {
               $join->on('static_rosterlog.fkshiftId', '=', 'shift.shiftId')
                   ->whereIn('static_rosterlog.day',WEEK_Days);
           })
           ->leftJoin('employeeinfo as EmpDuty',function($join) {
               $join->on('EmpDuty.id', '=', 'static_rosterlog.fkemployeeId')
                   ->whereNull('static_rosterlog.weekend');
           })
            ->leftJoin('employeeinfo as EmpOffDuty',function($join) {
               $join->on('EmpOffDuty.id', '=', 'static_rosterlog.fkemployeeId')
                   ->whereNotNull('static_rosterlog.weekend');
           })

           ->groupBy('static_rosterlog.day')
           ->groupBy('shift.shiftId')


           ->get();

    }

    public function getDepartmentShiftEmpAndRoster(Request $r)
    {

        return $empList=ShiftLog::select('employeeinfo.id as EmployeeId',DB::raw("CONCAT(COALESCE(firstName,''),' ',COALESCE(middleName,''),' ',COALESCE(lastName,'')) AS empFullname"),
            DB::raw("CONCAT(shift.shiftname,'(',shift.inTime,'-',shift.outTime,')') as shiftName"))
            ->leftJoin('employeeinfo','employeeinfo.id','shiftlog.fkemployeeId')
            ->leftJoin('shift',function($join) use($r) {
                $join->on('shiftlog.fkshiftId', '=', 'shift.shiftId');

            })
            ->where('fkshiftId',$r->shift)
            ->where('shiftlog.startDate',$r->date)
            ->where('shiftlog.endDate',$r->date)
            ->where('employeeinfo.fkDepartmentId',$r->departments)
            ->get();

    }

    public function checkMainRoster(Request $r){

        $roster=ShiftLog::where('startDate',$r->date)->where('endDate',$r->date)->where('fkshiftId',$r->shiftId)->first();

        if ($roster){
            return 1;
        }else{
            return 0;
        }

    }

}