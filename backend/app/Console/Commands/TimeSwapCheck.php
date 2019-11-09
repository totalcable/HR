<?php

namespace App\Console\Commands;

use App\AttendanceData;
use App\Employee;
use App\TimeSwap;
use Carbon\Carbon;
use Illuminate\Console\Command;
use DB;

class TimeSwapCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'TimeSwap:Twice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a Request to HR/Admin to change Roster Time';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
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
                        ['old_inTime'=>$ar['old_inTime']]
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
