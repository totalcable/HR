<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group([

    'middleware' => 'api',

], function (){
    Route::get('/', function () {
        return response()->json(['message' => 'Successfully Working Get','flag'=>'true']);
    });

    Route::post('/', function (Request $r) {
        return $r;

    });


    //Test
    Route::post('/dateRanges','shiftController@getDatesFromRange');
    Route::get('/test','TestController@test');



    Route::post('login','AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('profile/password/change', 'AuthController@changePassword');

    /*Employee Info*/
    Route::post('employee/get','EmployeeController@getAllEmployee');
    Route::get('employee/getAll','EmployeeController@getAllEmployeeInfo');
    Route::post('employee/getAllEmpForDepartment','EmployeeController@getAllEmployeeInfoForDepartment');

    Route::get('employee/getAllSingleRosterDepartmentEployee','EmployeeController@getAllSingleRosterDepartmentEployee');

    Route::get('employee/getTotalActiveEmp','EmployeeController@getTotalActiveEmp');
    Route::get('employee/getTotalInActiveEmp','EmployeeController@getTotalInActiveEmp');

    Route::post('employee/viewEmpInfoPdf','EmployeeController@viewEmpInfoPdf');


    //Employee basicinfo
    Route::post('employee/basicinfo','EmployeeController@getBasicinfo');
    Route::post('employee/storeBasicInfo','EmployeeController@storeBasicInfo');
    Route::post('employee/cvDelete','EmployeeController@cvDelete');
    Route::post('employee/imageDelete','EmployeeController@imageDelete');



    //===============================Leave Limit==================================
    Route::post('leave/limit/get','LeaveLimitController@get');
    Route::post('leave/limit/post','LeaveLimitController@post');



    //EmployeeType Info
    Route::get('employee_type/get','EmployeeTypeController@get');



    //employee Join Info
    Route::post('joinInfo/get','EmployeeController@getJoinInfo');

    Route::post('joinInfo/post','EmployeeController@updateJoinInfo');


    //department Info
    Route::get('department/getAllMultipleRosterDepartment','DepartmentController@getAllMultipleRosterDepartment');

    Route::get('department/get','DepartmentController@get');
    Route::get('departments/get-AllLevels','DepartmentController@getAllLevels');
    Route::post('department/post','DepartmentController@postDepartment');

    //Email
    Route::get('email/get','EmailController@get');
    Route::post('email/post','EmailController@postEmail');

    //Designation Info
    Route::get('designation/get','DesignationController@get');
    Route::post('designationinfo/post','DesignationController@postDesignationInfo');

    //Company Info
    Route::get('company/get','CompanyController@get');

    //Shift
    Route::post('employee/shift/get','EmployeeController@getAllEmployeeForAttendance');
    Route::post('employee/leaveteam/get','EmployeeController@leaveTeam');
    Route::get('shift/get','shiftController@getShiftName');
    Route::post('shift/post','shiftController@createShift');
    Route::post('user/shift/get','shiftController@getUserShift');
    Route::post('shift/assigned-shift-show','shiftController@getEmpShiftForUpdate');
    Route::post('/getAllShift','shiftController@getAllShift');
    Route::post('shift/assign','shiftController@assignToShift');
    Route::post('dateRanges/AssignedShift','shiftController@getDatesFromRangeAssignedShift');
    Route::post('dateRanges/NotAssignedShift','shiftController@getEmpNameFromRangeNotAssignedShift');
    Route::post('dateRanges/NotAssignedShiftPerEmp','shiftController@getEmpRangeNotAssignedShift');
    Route::post('shift/assigned-shift-update','shiftController@updateShiftAssignedLog');
    Route::post('shift/assigned-shift-delete','shiftController@deleteShiftAssignedLog');

    Route::get('shift/getInfo/{id}','shiftController@getShiftInfo');
    Route::post('shift/adjustmentAdd','shiftController@addjustmentShiftLog');
    Route::post('shift/AssignFutureShift','shiftController@AssignFutureShift');

    Route::post('roster/checkMainRoster','shiftController@checkMainRoster');

    //weekend
    Route::post('shiftLogWeekend/setWeekend','shiftController@setshiftLogweekend');
    //Holiday
    Route::post('shiftLogHoliday/setHoliday','shiftController@setshiftLogholiday');

    //Attendance
    Route::post('report/getEmployeeAttendance','AttendanceController@getEmployeeAttendance');
    Route::post('report/attendanceHR','AttendanceController@getAttendenceDataForHR');
    Route::post('report/attendanceHRINOUT','AttendanceController@getAttendenceDataForHRINOUT');
    Route::post('report/finalReport-1','AttendanceController@finalReport_1');

    Route::post('report/finalReport-2','AttendanceController@finalReport_2');

    Route::post('report/finalReport-3','AttendanceController@finalReport_3');


    //Leave Apply
    Route::get('leave/getLeaveCategory','LeaveController@getLeaveCategory');
    Route::post('leave/assignLeave','LeaveController@assignLeave');
    Route::post('leave/assignLeavePersonal','LeaveController@assignLeavePersonal');

    Route::post('leave/assignLeavePersonal','LeaveController@assignLeavePersonal');

    //Show Leave Requests
    Route::post('leave/getLeaveRequests','LeaveController@getLeaveRequests');
    Route::post('leave/summery','LeaveController@getLeaveSummery');
    Route::post('leave/getLeaveRequests/{id}','LeaveController@getLeaveRequestsIndividual');

    Route::post('leave/get/individual','LeaveController@getIndividual');
    Route::post('leave/get/myleave','LeaveController@getMyLeave');
    Route::post('leave/summery/details','LeaveController@getLeaveSummeryDetails');
    Route::post('leave/change/status','LeaveController@changeStatus');


    Route::post('leave/individual/update','LeaveController@updateIndividual');

    /* govt Holiday */

    Route::post('govtHoliday/getAllGovtHoliday','GovtHolidayController@getAllGovtHoliday');
    Route::post('govtHoliday/insertNewGovtHoliday','GovtHolidayController@insertNewGovtHoliday');
    Route::post('govtHoliday/getGovtHolidayInfo','GovtHolidayController@getGovtHolidayInfo');
    Route::post('govtHoliday/updateGovtHoliday','GovtHolidayController@updateGovtHolidayInfo');


    /* extra work History */

    Route::post('extraWorkHistory/getAll','ExtraWorkHistoryController@getAllExtraWorkHistory');
    Route::post('ExtraWork/calculateextraWork','ExtraWorkHistoryController@calculateExtraWork');

    /* get RosterInfo by dept */
    Route::post('department/getRosterInfo','shiftController@getRosterInfo');

    /* report Roster Wise */

    Route::post('report/RoserWiseReport-1','AttendanceController@getRosterWiseReport_1');
    Route::post('report/RoserWiseReport-2','AttendanceController@getRosterWiseReport_2');
    Route::post('report/RoserWiseReport-3','AttendanceController@getRosterWiseReport_3');

    /* multiple Roster Report */

    Route::post('report/MultipleRoserWiseReport-1','AttendanceController@multipleRosterWiseReport_1');

    /*delete File */

    Route::post('deleteFile','FileControllerController@deleteFile');

    /*swap */

    Route::post('swap/getAllSwapReq','swapController@getAllswapRequest');
    Route::get('swap/getAllShiftByRequesterDepartment','swapController@getAllShiftByRequesterDepartment');
    Route::get('swap/getAllemployeeByRequesterDepartment','swapController@getAllemployeeByRequesterDepartment');
    Route::post('swap/submitNewSwapRequestByEmp','swapController@submitNewSwapRequestByEmp');
    Route::post('swap/getEmpSwapReq','swapController@getEmpSwapReq');
    Route::post('swap/editSwapRequest','swapController@editSwapRequest');
    Route::post('swap/acceptSwapReq','swapController@acceptSwapReq');
    Route::post('swap/rejectSwapReq','swapController@rejectSwapReq');


    /* time swap */

    Route::post('swap/getAllTimeSwapReq','TimeSwapController@getAllEmpTimeSwap');
    Route::post('swap/acceptTimeSwap','TimeSwapController@acceptTimeSwap');
    Route::post('swap/rejectTimeSwap','TimeSwapController@rejectTimeSwap');

    /*empDesignation */

    Route::post('getEmpDesignation','EmployeeController@getempDesignation');

    /* department wise Roste */

    Route::post('department/getStaticRosterAndEmpInfo','shiftController@getEmpAndStaticRoster');



    Route::post('department/getRosterAndEmpInfo','shiftController@getEmpAndRoster');
    Route::post('department/shift/getRosterAndEmpInfo','shiftController@getDepartmentShiftEmpAndRoster');


    Route::post('rosterLog/getStaticRosterInfo','StaticRosterController@getStaticRosterInfo');
    Route::post('rosterLog/setStaticRosterInfo','StaticRosterController@setStaticRosterInfo');


    Route::post('rosterLog/getDataFromStaticRoster','StaticRosterController@getDataFromStaticRoster');

    Route::post('rosterLog/getDataFromRoster','StaticRosterController@findDepartmentWiseRosterByShift');

    Route::post('roster/setDepartmentWiseRosterByShift','StaticRosterController@setDepartmentWiseRosterByShift');
    Route::post('roster/findDepartmentWiseRosterByShift','StaticRosterController@findDepartmentWiseRosterByShift');


    /* pasword Change */

    Route::post('password/changePasswordFromUser','PasswordChangeController@changePasswordFromUser');
    Route::post('password/changePasswordFromAdmin','PasswordChangeController@changePasswordFromAdmin');


    /* database backup */


    Route::get('database/backup','DatabaseController@wholeDbBackup');


    /* punch */

    Route::post('punch/getEmpRosterAndPunches','PunchController@getEmpRosterAndPunches');

   // Route::post('punch/getEmpPunches','PunchController@getEmpRoster');



});


