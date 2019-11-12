
<html>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="{{url('public/css/exceltable.css')}}" rel="stylesheet">


<body>

<table class="blueTable">
    <thead>
    <tr>

        <th colspan="8" style="text-align: center;">Final Report 1 -( {{\Carbon\Carbon::parse($startDate)->format('Y-m-d')}} - {{\Carbon\Carbon::parse($endDate)->format('Y-m-d')}} )</th>

    </tr>
    <tr>
        <td style="vertical-align: middle;text-align: center;"></td>
        <th colspan="4"style="text-align: center;vertical-align: middle;" >Name: {{$allE->empFullname}}</th>
        <th colspan="3"style="text-align: center;vertical-align: middle;" >ID: {{$allE->attDeviceUserId}}</th>
    </tr>
    <tr>
        <td style="vertical-align: middle;text-align: center;"></td>
        <th colspan="4"style="text-align: center;vertical-align: middle;" >Department: {{$allE->departmentName}}</th>
        <th colspan="3"style="text-align: center;vertical-align: middle;" >Designation: {{$allE->designationTitle}}</th>
    </tr>
    <tr>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="25">Date</th>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="15">IN Time</th>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="15">OUT Time</th>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="20">Late Day / Hours</th>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="15">Working Hour</th>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="35">Round Working Hour</th>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="15">Adjustment</th>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="15">Attendance</th>

    </tr>
    <tr>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="25"></th>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="20"></th>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="35"></th>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>

    </tr>


    </thead>
    <tbody>

    @php
        $T_roundworkinghour=null;$T_weekendcount=0;$T_adjustment=0;$finalholiDay=0;$T_weekend=0;$T_late=0;$T_LateHour=0;$T_FinalWorkHour=0;
    $T_offDay=0;$T_govHoliday=0;$T_leave=0;$T_absent=0;$T_present=0;$T_Swap=0;$T_timeSwap=0;$T_salaryDay=0;$T_salaryLeave=0;
    @endphp

    @foreach($dates as $date)

        @php
            $FINALIN=null;$FINALOUT=null;$FINALWORKINGHOUR=null;$ROUNDFINALWORKINGHOUR=null;$weekendCount=0;$adjustment=0;$holiDay=0;$next=false;
            $weekend=0;$late=0;$LateHour=0;$FINALWORKINGHOUR2=0;$offDay=0;$govHoliday=0;$leave=0;$absent=0;$present=0;$Swap=0;$timeSwap=0;$salaryDay=0;
            $salaryLeave=0;


        @endphp

        <tr >

            <td style="text-align: left;vertical-align: middle;" width="25" class="Border">
                {{$date['date']}}({{$date['day']}})
            </td>

            @if($date['date'] <= \Carbon\Carbon::now()->format('Y-m-d'))

                @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first())
                    @php
                        $nextday=\Carbon\Carbon::parse($date['date'])->addDays(1)->format('Y-m-d');
                        $previousday=\Carbon\Carbon::parse($date['date'])->subDays(1)->format('Y-m-d');

                    @endphp

                    <td class="Border"style="text-align: center;vertical-align: middle;" width="15">



                        @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime == null)

                            {{
                                \Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                ->first()->accessTime2)->format('H:i')
                            }}
                            @php
                                $salaryDay++;
                                $T_salaryDay=($T_salaryDay+$salaryDay);
                            @endphp

                        @elseif(
                                        $results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime != null  &&
                                        $results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->outTime !=null &&
                                        $results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime <
                                        $results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->outTime
                               )

                            @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime =='00:00:00')

                                @if($results->where('employeeId',$allE->id)->where('attendanceDate',$previousday)
                                ->where('accessTime','>=','21:00:00')->where('fkAttDevice',$allE->inDeviceNo)->first())

                                    @php
                                        $FINALIN=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$previousday)
                                            ->where('accessTime','>=','21:00:00')->where('fkAttDevice',$allE->inDeviceNo)->first()->accessTime2);
                                    @endphp

                                    {{$FINALIN->format('H:i')}}

                                @elseif($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                            ->where('fkAttDevice',$allE->inDeviceNo)->first())

                                    @php
                                        $FINALIN=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                            ->where('fkAttDevice',$allE->inDeviceNo)->first()->accessTime2);
                                    @endphp

                                    {{$FINALIN->format('H:i')}}

                                @endif

                            @else

                                @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime < '09:00:00')

                                    @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                                ->where('fkAttDevice',$allE->inDeviceNo)->where('accessTime','<','09:00:00')->first())

                                        @php
                                            $FINALIN=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                                ->where('fkAttDevice',$allE->inDeviceNo)->where('accessTime','<','09:00:00')->first()->accessTime2);
                                        @endphp

                                        {{$FINALIN->format('H:i')}}

                                    @endif
                                @elseif($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime >= '09:01:00' && $results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime <= '18:00:00')

                                    @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                                ->where('fkAttDevice',$allE->inDeviceNo)->where('accessTime','>=','09:01:00')->where('accessTime','<=','18:00:00')->first())

                                        @php
                                            $FINALIN=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                                ->where('fkAttDevice',$allE->inDeviceNo)->where('accessTime','>=','09:01:00')->where('accessTime','<=','18:00:00')->first()->accessTime2);
                                        @endphp

                                        {{$FINALIN->format('H:i')}}

                                    @endif


                                @endif

                            @endif

                        @else

                            @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->where('fkAttDevice',$allE->inDeviceNo)
                                    ->where('accessTime','>=','19:00:00')->first())

                                @php
                                    $FINALIN=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                    ->where('fkAttDevice',$allE->inDeviceNo)
                                        ->where('accessTime','>=','19:00:00')->first()->accessTime2);
                                @endphp

                                {{$FINALIN->format('H:i')}}

                            @endif

                        @endif


                    </td>
                    <td class="Border"style="text-align: center;vertical-align: middle;" width="15">

                        @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->outTime == null)

                            {{
                                \Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                        ->last()->accessTime2)->format('H:i')
                            }}
                        @elseif(
                                        $results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime != null  &&
                                        $results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->outTime !=null &&
                                        $results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime <
                                        $results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->outTime
                        )

                            @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime =='00:00:00')

                                @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                ->where('accessTime','<=','18:00:00')->where('fkAttDevice',$allE->outDeviceNo)->first())

                                    @php
                                        $FINALOUT=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                            ->where('accessTime','<=','18:00:00')->where('fkAttDevice',$allE->outDeviceNo)->last()->accessTime2);
                                    @endphp

                                    {{$FINALOUT->format('H:i')}}


                                @endif

                            @else

                                @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime < '11:00:00')



                                    @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                    ->where('accessTime','<=','23:59:59')->where('fkAttDevice',$allE->outDeviceNo)
                                        ->first())

                                        @php
                                            $FINALOUT=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                            ->where('accessTime','<=','23:59:59')->where('fkAttDevice',$allE->outDeviceNo)
                                                ->last()->accessTime2);
                                        @endphp

                                        {{$FINALOUT->format('H:i')}}
                                    @endif

                                @else

                                    @if($results->where('employeeId',$allE->id)->where('attendanceDate',$nextday)
                                    ->where('accessTime','<=','04:00:00')->where('fkAttDevice',$allE->outDeviceNo)
                                        ->first())

                                        @php
                                            $FINALOUT=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$nextday)
                                            ->where('accessTime','<=','04:00:00')->where('fkAttDevice',$allE->outDeviceNo)
                                                ->last()->accessTime2);
                                        @endphp

                                        {{$FINALOUT->format('H:i')}}

                                    @elseif($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                    ->where('accessTime','<=','23:59:59')->where('fkAttDevice',$allE->outDeviceNo)
                                        ->first())

                                        @php
                                            $FINALOUT=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                            ->where('accessTime','<=','23:59:59')->where('fkAttDevice',$allE->outDeviceNo)
                                                ->last()->accessTime2);
                                        @endphp

                                        {{$FINALOUT->format('H:i')}}

                                    @endif


                                @endif

                            @endif
                        @else
                            @if($results->where('employeeId',$allE->id)->where('attendanceDate',$nextday)
                                    ->where('accessTime','<=','13:00:00')->where('fkAttDevice',$allE->outDeviceNo)->first())

                                @php
                                    $FINALOUT=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$nextday)
                                        ->where('accessTime','<=','13:00:00')->where('fkAttDevice',$allE->outDeviceNo)->last()->accessTime2);
                                @endphp

                                {{$FINALOUT->format('H:i')}}


                            @endif
                        @endif
                    </td>
                    <td class="Border"style="text-align: center;vertical-align: middle;" width="20">


                        @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime == null)


                        @elseif(
                            $results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime != null &&
                           $results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime <
                           $results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->outTime
                       )

                            @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime=='00:00:00')

                                @if($results->where('employeeId',$allE->id)->where('attendanceDate',$previousday)
                                            ->where('accessTime','>=','20:00:00')->where('accessTime','<=','23:59:59')
                                            ->where('fkAttDevice',$allE->inDeviceNo)->first()
                                   )

                                @elseif($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                            ->where('accessTime','>=','00:00:00')->where('accessTime','<=','3:59:59')
                                            ->where('fkAttDevice',$allE->inDeviceNo)->first())

                                    @php
                                        $access=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                            ->where('accessTime','>=','00:00:00')->where('accessTime','<=','3:59:59')
                                            ->where('fkAttDevice',$allE->inDeviceNo)->first()->accessTime);
                                         $ins=\Carbon\Carbon::createFromFormat('H:i:s','00:00:00');

                                    @endphp

                                    @if($access >'00:00:00' && $access < '3:59:59')

                                        @if($access->diffInMinutes($ins) >= 21 )

                                            @php
                                                $late++;
                                                $T_late=($T_late+$late);
                                                $LateHour=$access->diffInMinutes($ins);
                                                $T_LateHour=($LateHour+$T_LateHour);
                                            @endphp

                                            {{$access->diff($ins)->format('%H:%i')}}

                                        @endif

                                    @endif
                                @endif

                            @else



                                @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime < '09:00:00')

                                    @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                                ->where('fkAttDevice',$allE->inDeviceNo)->where('accessTime','<','09:00:00')->first())

                                        @php
                                            $access=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                                ->where('fkAttDevice',$allE->inDeviceNo)->where('accessTime','<','09:00:00')->first()->accessTime);
                                            $ins=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                                    ->first()->inTime)
                                        @endphp

                                        @if($access > $ins)

                                            @if($access->diffInMinutes($ins) >= 21 )

                                                @php
                                                    $late++;
                                                    $T_late=($T_late+$late);
                                                    $LateHour=$access->diffInMinutes($ins);
                                                    $T_LateHour=($LateHour+$T_LateHour);
                                                @endphp

                                                {{$access->diff($ins)->format('%H:%i')}}

                                            @endif
                                        @endif

                                    @endif
                                @elseif($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime >= '09:01:00' && $results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime <= '18:00:00')

                                    @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                                ->where('fkAttDevice',$allE->inDeviceNo)->where('accessTime','>=','09:01:00')->where('accessTime','<=','18:00:00')->first())

                                        @php
                                            $access=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                                ->where('fkAttDevice',$allE->inDeviceNo)->where('accessTime','>=','09:01:00')->where('accessTime','<=','18:00:00')->first()->accessTime);
                                            $ins=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                                    ->first()->inTime)
                                        @endphp

                                        @if($access > $ins)

                                            @if($access->diffInMinutes($ins) >= 21 )

                                                @php
                                                    $late++;
                                                    $T_late=($T_late+$late);
                                                    $LateHour=$access->diffInMinutes($ins);
                                                    $T_LateHour=($LateHour+$T_LateHour);
                                                @endphp

                                                {{$access->diff($ins)->format('%H:%i')}}

                                            @endif
                                        @endif

                                    @endif


                                @endif






                            @endif

                        @else

                            @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                ->where('fkAttDevice',$allE->inDeviceNo)->where('accessTime','>=','19:00:00')->where('accessTime','<=','23:59:59')->first())

                                @php
                                    $access=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                        ->where('fkAttDevice',$allE->inDeviceNo)->where('accessTime','>=','19:00:00')->where('accessTime','<=','23:59:59')->first()->accessTime);
                                    $ins=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                            ->first()->inTime)
                                @endphp

                                @if($access > $ins)

                                    @if($access->diffInMinutes($ins) >= 21 )

                                        @php
                                            $late++;
                                            $T_late=($T_late+$late);
                                        $LateHour=$access->diffInMinutes($ins);
                                            $T_LateHour=($LateHour+$T_LateHour);
                                        @endphp

                                        {{$access->diff($ins)->format('%H:%i')}}

                                    @endif
                                @endif



                            @elseif($results->where('employeeId',$allE->id)->where('attendanceDate',$nextday)->first())

                                @php
                                    $access=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$nextday)
                                        ->first()->accessTime);
                                    $ins=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$nextday)
                                            ->first()->inTime)
                                @endphp

                                @if($access->diffInMinutes($ins) >= 21 )

                                    @php
                                        $late++;
                                        $T_late=($T_late+$late);
                                        $LateHour=$access->diffInMinutes($ins);
                                        $T_LateHour=($LateHour+$T_LateHour);
                                    @endphp

                                    {{$access->diff($ins)->format('%H:%i')}}

                                @endif




                            @endif


                        @endif



                    </td>

                    <td class="Border"style="text-align: center;vertical-align: middle;" width="15">

                        @if($FINALIN != null && $FINALOUT != null)

                            @php
                                $FINALWORKINGHOUR=$FINALOUT->diff($FINALIN);
                                $FINALWORKINGHOUR2=$FINALOUT->diffInMinutes($FINALIN);
                                $T_FinalWorkHour=($FINALWORKINGHOUR2+$T_FinalWorkHour);

                            @endphp

                            {{$FINALWORKINGHOUR->format('%H:%i')}}

                        @endif

                    </td>

                    <td class="Border"style="text-align: center;vertical-align: middle;" width="35">

                        @if($FINALWORKINGHOUR != null)
                            @php
                                $ROUNDFINALWORKINGHOUR=\Carbon\Carbon::createFromTime($FINALWORKINGHOUR->format('%H'),$FINALWORKINGHOUR->format('%i'),0);
                            @endphp

                            @if($ROUNDFINALWORKINGHOUR->minute >=25)

                                @php
                                    $ROUNDFINALWORKINGHOUR->minute(0);
                                    $ROUNDFINALWORKINGHOUR->addHour();
                                    $T_roundworkinghour=($T_roundworkinghour+$ROUNDFINALWORKINGHOUR->hour);
                                @endphp

                            @else

                                @php
                                    $ROUNDFINALWORKINGHOUR->minute(0);
                                    $T_roundworkinghour=($T_roundworkinghour+$ROUNDFINALWORKINGHOUR->hour);

                                @endphp

                            @endif

                            {{$ROUNDFINALWORKINGHOUR->format('H:i')}}

                        @endif



                    </td>
                    <td class="Border"style="text-align: center;vertical-align: middle;" width="15">

                        @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->adjustmentDate != null)
                            @php

                                $adjustment++;
                                $T_adjustment=($adjustment+$T_adjustment);
                            @endphp
                            {{$results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->adjustmentDate}}
                        @endif



                    </td>

                    @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime == null)
                        <td class="Border"class="cell" style="color: firebrick"  width="15">
                            roster not found
                            <br>

                            @php

                                $FINALIN=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                ->first()->accessTime2);
                                $FINALOUT=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                ->last()->accessTime2);
                            @endphp

                            First: {{$FINALIN->format('H:i')}}<br>
                            Last: {{$FINALOUT->format('H:i')}}<br>
                            @if($FINALIN != null && $FINALOUT != null)

                                @php
                                    $FINALWORKINGHOUR=$FINALOUT->diff($FINALIN);

                                @endphp

                                WorkingHour: {{$FINALWORKINGHOUR->format('%H:%i')}}

                            @endif


                        </td>

                    @else

                        <td class="Border"class="cell" width="15">

                            @php

                                $present++;
                                $T_present=($T_present+$present);
                            @endphp

                            @if($allTimeSwap->where('fkEmployeeId',$allE->id)->where('date',$date['date'])->first())



                                Time Swaped

                            @else

                                P
                            @endif
                        </td>


                    @endif
                @else
                    <td class="Border"style="text-align: center;vertical-align: middle;" width="15"></td>
                    <td class="Border"style="text-align: center;vertical-align: middle;" width="15"></td>
                    <td class="Border"style="text-align: center;vertical-align: middle;" width="20"></td>
                    <td class="Border"style="text-align: center;vertical-align: middle;" width="15"></td>
                    <td class="Border"style="text-align: center;vertical-align: middle;" width="35"></td>
                    <td class="Border"style="text-align: center;vertical-align: middle;" width="15"></td>


                    @if($allLeave->where('fkEmployeeId',$allE->id)->where('startDate','<=',$date['date'])->where('endDate','>=',$date['date'])->first())
                        <td class="cell Border"style="color: #ffffff;background-color: #003300" width="15">
                            @if($allLeave->where('fkEmployeeId',$allE->id)->where('categoryCode','!=','LWP')->where('startDate','<=',$date['date'])->where('endDate','>=',$date['date'])->first())
                                @php
                                    $salaryLeave++;
                                $T_salaryLeave=($salaryLeave+$T_salaryLeave);
                                @endphp
                            @endif
                            @php


                                $leave++;
                            $T_leave=($T_leave+$leave);
                            @endphp

                            {{$allLeave->where('fkEmployeeId',$allE->id)->where('startDate','<=',$date['date'])->where('endDate','>=',$date['date'])->first()->categoryName}}
                        </td>


                    @elseif($allWeekend->where('fkemployeeId',$allE->id)->where('startDate','<=',$date['date'])->where('endDate','>=',$date['date'])->first())

                        <td class="cell Border" style="background-color:#000000;color: #ffffff;" width="15">

                            @php

                                $offDay++;
                            $T_offDay=($T_offDay+$offDay);
                            @endphp

                            Day Off

                        </td>


                    @elseif($govtHoliday->where('startDate','<=',$date['date'])->where('endDate','>=',$date['date'])->first())

                        <td class="cell Border" style="background-color:#FF5733;color: #ffffff;" width="15">

                            @php

                                $govHoliday++;
                                $T_govHoliday=($T_govHoliday+$govHoliday);
                            @endphp

                            Govt Holiday

                        </td>

                    @elseif($dutySwap->where('swap_by',$allE->id)->where('swap_by_date',$date['date'])->first())

                        <td class="cell Border" width="15">

                            @php
                                $Swap++;
                                $T_Swap=($Swap+$T_Swap)
                            @endphp


                            Duty swapped To {{Carbon\Carbon::parse($dutySwap->where('swap_by_date',$date['date'])->where('swap_by',$allE->id)->first()->swap_for_date)->format('Y-m-d')}}

                        </td>
                    @elseif($dutySwap->where('swap_for',$allE->id)->where('swap_for_date',$date['date'])->first())

                        <td class="cell Border" width="15">

                            @php
                                $Swap++;
                                $T_Swap=($Swap+$T_Swap)
                            @endphp


                            Duty swapped To {{Carbon\Carbon::parse($dutySwap->where('swap_for_date',$date['date'])->where('swap_for',$allE->id)->first()->swap_by_date)->format('Y-m-d')}}

                        </td>




                    @else


                        <td class="cell Border" style="background-color:#ff0000;color: #ffffff;" width="15">

                            @php
                                $timeSwap++;
                                $T_timeSwap=($timeSwap+$T_timeSwap)
                            @endphp

                            Absent

                        </td>
                    @endif

                @endif

            @else


                <td class="Border"style="text-align: center;vertical-align: middle;" width="15"></td>
                <td class="Border"style="text-align: center;vertical-align: middle;" width="15"></td>
                <td class="Border"style="text-align: center;vertical-align: middle;" width="20"></td>
                <td class="Border"style="text-align: center;vertical-align: middle;" width="15"></td>
                <td class="Border"style="text-align: center;vertical-align: middle;" width="35"></td>
                <td class="Border"style="text-align: center;vertical-align: middle;" width="15"></td>
                <td class="Border"style="text-align: center;vertical-align: middle;" width="15">Future Date</td>

            @endif

        </tr>

        @php
            $late=0;$LateHour=0;
        $ROUNDFINALWORKINGHOUR=null;$adjustment=0;
        $FINALWORKINGHOUR2=0;

        $offDay=0;$govHoliday=0;
        $leave=0;
$present=0;$absent=0;$Swap=0;$timeSwap=0;

$salaryLeave=0;




        @endphp

    @endforeach

    <tr>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="25">Total :</th>
        <td class="Border"style="text-align: center;vertical-align: middle;" width="15"></td>
        <td class="Border"style="text-align: center;vertical-align: middle;" width="15"></td>
        <td class="Border"style="text-align: center;vertical-align: middle;" width="20">

            {{$T_late}} /
            {{\Carbon\Carbon::createFromFormat('H:i:s','00:00:00')->diff(\Carbon\Carbon::createFromFormat('H:i:s','00:00:00')->addMinutes($T_LateHour))->format('%H:%i')}}
        </td>
        <td class="Border"style="text-align: center;vertical-align: middle;" width="15">


            @php
                $hours = intval($T_FinalWorkHour/60);
                $minutes = $T_FinalWorkHour - ($hours * 60);
            @endphp

            {{$hours}}:{{$minutes}}


        </td>
        <td class="Border"style="text-align: center;vertical-align: middle;" width="35">
            {{$T_roundworkinghour}}
        </td>
        <td class="Border"style="text-align: center;vertical-align: middle;" width="15">
            {{$T_adjustment}}
        </td>
        <td class="Border"style="text-align: center;vertical-align: middle;wrap-text:true;" height="100">

            Total Off Day={{$T_offDay}}<br>
            Total Leave={{$T_leave}}<br>
            Total Govt Holiday={{$T_govHoliday}}<br>

            Total Absent ={{$T_absent}}<br>
            Total Present ={{$T_present}}<br>
            Total Duty Swap ={{$T_Swap}}<br>
            Total Time Swap ={{$T_timeSwap}}<br>




        </td>

    </tr>
    <tr>

        <th class="Border"style="text-align: center;vertical-align: middle;" width="25">Salary :</th>
        <td class="Border"style="text-align: center;vertical-align: middle;" width="15">Gross: {{$allE->salary}}</td>
        <td class="Border"style="text-align: center;vertical-align: middle;" width="15">convence: 2000 </td>
        <td class="Border"style="text-align: center;vertical-align: middle;" width="20">
            @php
            $basicSalary=round((((int)$allE->salary-2000)*.64103),2)
            @endphp
            Basic: {{$basicSalary}}

        </td>
        <td class="Border"style="text-align: center;vertical-align: middle;wrap-text:true;" height="100">

            @php
                $houseRent=round(($basicSalary*.5),2)
            @endphp

            house rent :<br> {{$houseRent}}


        </td>
        <td class="Border"style="text-align: center;vertical-align: middle;wrap-text:true;" height="100">
            @php
                $medical=round(($basicSalary*.06),2)
            @endphp
            @if($allE->pf_fund != null || $allE->pf_fund != '')

                @php
                    $CompanyProvident=round(($basicSalary*.04),2);

                @endphp



            @else
                @php
                    $CompanyProvident=0;
                    $PersonalProvident=0;
                @endphp

            @endif

            Company provident:{{$CompanyProvident}}<br>
            personal provident:{{$PersonalProvident}}<br>

            Medical:{{$medical}}


        </td>
        <td class="Border"style="text-align: center;vertical-align: middle;wrap-text:true;" height="100">

            @php
                $dailySalary=round((((int)$allE->salary)/30),2)
            @endphp

            Daily:<br>{{$dailySalary}}




        </td>
        <td class="Border"style="text-align: center;vertical-align: middle;wrap-text:true;" height="100">


            @php
                $payAbleSalary=round(($dailySalary*($T_present+$T_salaryLeave+$T_govHoliday+$T_offDay+$T_Swap)),2);

            @endphp

            Total Payable Salary :<br>
            {{$payAbleSalary}}<br>

        </td>

    </tr>




    </tbody>
</table>

</body>
</html>