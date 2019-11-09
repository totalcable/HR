<?php

namespace App\Console\Commands;

use App\Employee;
use App\ShiftLog;
use Carbon\Carbon;
use Illuminate\Console\Command;

class getAttendenceData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendence:hourly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the hourly attendence data';

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


        $currentDate = Carbon::now()->format('Y-m-d');
        $fromDate = Carbon::now()->subDays(1)->format('Y-m-d');
        $toDate = Carbon::now()->addDays(1)->format('Y-m-d');

        $roster=ShiftLog::where('startDate',$currentDate)->where('endDate',$currentDate)->get();

        $roster=collect($roster);

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

        $allEmp = Employee::select('employeeinfo.id' ,'attemployeemap.attDeviceUserId', 'employeeinfo.inDeviceNo', 'employeeinfo.outDeviceNo')
            ->leftJoin('attemployeemap', 'attemployeemap.employeeId', 'employeeinfo.id')

            ->orderBy('employeeinfo.id', 'ASC')
            ->whereNotNull('employeeinfo.fkDepartmentId')
            ->get();


        foreach ($allEmp as $allE){

        }

    }
}
