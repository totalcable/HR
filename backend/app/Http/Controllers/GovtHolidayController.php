<?php

namespace App\Http\Controllers;

use App\GovtHoliday;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use DB;

class GovtHolidayController extends Controller
{
   public function getAllGovtHoliday(Request $r){

       $allGovtHoliday=GovtHoliday::select('holiday_calander.*',DB::raw("CONCAT(COALESCE(firstName,''),' ',COALESCE(middleName,''),' ',COALESCE(lastName,'')) AS empFullname"))
           ->leftJoin('employeeinfo','employeeinfo.fkUserId','holiday_calander.createdBy');

       if ($r->startDate){

           $allGovtHoliday=$allGovtHoliday->whereDate('holiday_calander.startDate','>=',Carbon::parse($r->startDate)->format('Y-m-d'));
       }
       if ($r->endDate){

           $allGovtHoliday=$allGovtHoliday->whereDate('holiday_calander.endDate','<=',Carbon::parse($r->endDate)->format('Y-m-d'));
       }
       if ($r->HolidayStatus){

           $allGovtHoliday=$allGovtHoliday->where('holiday_calander.status',$r->HolidayStatus);
       }

       $datatables = Datatables::of($allGovtHoliday);
       return $datatables->make(true);

   }

   public function insertNewGovtHoliday(Request $r){

       $newGovtH=new GovtHoliday();
       $newGovtH->holidayName=$r->holidayName;
       $newGovtH->startDate=$r->startDate;
       $newGovtH->endDate=$r->endDate;
       $newGovtH->noOfDays=$r->noOfDays;
       $newGovtH->purpose=$r->purpose;
       $newGovtH->status=$r->status;
       $newGovtH->createdBy=auth()->user()->id;
       $newGovtH->save();




   }
   public function updateGovtHolidayInfo(Request $r){

       $newGovtH=GovtHoliday::findOrFail($r->id);
       $newGovtH->holidayName=$r->holidayName;
       $newGovtH->startDate=$r->startDate;
       $newGovtH->endDate=$r->endDate;
       $newGovtH->noOfDays=$r->noOfDays;
       $newGovtH->purpose=$r->purpose;
       $newGovtH->status=$r->status;
       $newGovtH->createdBy=auth()->user()->id;
       $newGovtH->save();




   }
   public function getGovtHolidayInfo(Request $r){

       return $newGovtHoliday=GovtHoliday::findOrFail($r->id);




   }
}
