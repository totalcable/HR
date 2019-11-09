
<html>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="{{url('public/css/exceltable.css')}}" rel="stylesheet">


<body>

<table class="blueTable">
    <thead>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>

    </tr>
    <tr>
        <th style="text-align: center;vertical-align: middle;" width="10" ></th>
        <th style="text-align: center;vertical-align: middle;" width="25" >Date</th>

        @foreach($dates as $date)
            <th class="Border" colspan="7" style="text-align: center;vertical-align: middle;">{{$date['date']}}({{$date['day']}})</th>
        @endforeach

    </tr>
    <tr >

        <th style="text-align: center;vertical-align: middle;"width="10">ID</th>
        <th style="text-align: center;vertical-align: middle;"width="25">Name</th>
        @foreach($dates as $date)

            <th style="text-align: center;vertical-align: middle;background-color: #92D050"width="10">In Time</th>
            <th style="text-align: center;vertical-align: middle;background-color: #00B050"width="10">Out Time</th>

            <th style="text-align: center;vertical-align: middle;"width="10">Late Time</th>

            <th style="text-align: center;vertical-align: middle;"width="20">Total Hours Worked</th>

            <th style="text-align: center;vertical-align: middle;background-color:#757171"width="15">Attendence</th>
            <th style="text-align: center;vertical-align: middle;"width="15">Round Hour</th>
            <th style="text-align: center;vertical-align: middle;"width="15">Adjustment</th>
        @endforeach
        <th style="text-align: center;vertical-align: middle;"width="5"></th>
        <th style="text-align: center;vertical-align: middle;"width="15">Hour Expected</th>
        <th style="text-align: center;vertical-align: middle;"width="15">Total Hour</th>
        <th style="text-align: center;vertical-align: middle;"width="15">Total Leave</th>
        <th style="text-align: center;vertical-align: middle;"width="15">Total Weekend</th>
        <th style="text-align: center;vertical-align: middle;"width="15">Total Holiday</th>
        <th style="text-align: center;vertical-align: middle;"width="15">Total Addjustment</th>


    </tr>
    </thead>
    <tbody>
    <tr>

        <td width="10" ></td>
        <td width="25" ></td>
        <td width="10" ></td>
        <td width="10" ></td>

        <td width="10" ></td>

        <td width="20" ></td>


        <td width="15" ></td>
        <td width="15" ></td>
        <td width="15" ></td>
        <td style="text-align: center;vertical-align: middle;"width="5"></td>
        <td style="text-align: center;vertical-align: middle;"width="15"></td>
        <td style="text-align: center;vertical-align: middle;"width="15"></td>
        <td style="text-align: center;vertical-align: middle;"width="15"></td>
        <td style="text-align: center;vertical-align: middle;"width="15"></td>
        <td style="text-align: center;vertical-align: middle;"width="15"></td>
        <td style="text-align: center;vertical-align: middle;"width="15"></td>





    </tr>


    @php
        $T_roundworkinghour=null;$T_weekendcount=0;$T_adjustment=0;$finalholiDay=0;
    @endphp
    @foreach($allEmp as $aE)

        <tr>


            <td class="cell" width="10">{{$aE->attDeviceUserId}}</td>
            <td class="cell" width="25">{{$aE->empFullname}}</td>
            @php
                $FINALIN=null;$FINALOUT=null;$FINALWORKINGHOUR=null;$ROUNDFINALWORKINGHOUR=null;$weekendCount=0;$adjustment=0;$holiDay=0;$next=false;

            @endphp
            @foreach($dates as $date)

                @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first())
                    <td class="cell" width="10">


                        @php
                                $nextday=\Carbon\Carbon::parse($date['date'])->addDays(1)->format('Y-m-d');
                                $previousday=\Carbon\Carbon::parse($date['date'])->subDays(1)->format('Y-m-d');
                                $in=$results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime;
                                $nextIn=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime)->subHours(3)->format('H:i');
                        @endphp

                        @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime == null)


                        @elseif($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime != null &&
                        $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime <
                        $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->outTime )

                            @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                            ->where('accessTime','>=',$nextIn)->first())

                                @php
                                $FINALIN=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                                    ->where('accessTime','>=',$nextIn)->first()->accessTime2);
                                @endphp

                                {{$FINALIN->format('H:i')}}

                            @endif

                        @else



                                @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->where('accessTime','>=',$nextIn)->first())

                                @php
                                    $FINALIN=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                                        ->where('accessTime','>=',$nextIn)->first()->accessTime2);
                                @endphp

                                {{$FINALIN->format('H:i')}}

                            @elseif($results->where('employeeId',$aE->id)->where('attendanceDate',$nextday)->first())

                                @php
                                    $FINALIN=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$nextday)
                                        ->first()->accessTime2);
                                @endphp

                                {{$FINALIN->format('H:i')}}




                                @endif

                        @endif




                    </td>
                    <td class="cell" width="10">

                        @php
                            $nextday=\Carbon\Carbon::parse($date['date'])->addDays(1)->format('Y-m-d');
                            $previousday=\Carbon\Carbon::parse($date['date'])->subDays(1)->format('Y-m-d');

                            $out=$results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->outTime;
                            if ((\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->outTime))->hour <= 21)
                            { $nextOut=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->outTime)->addHours(3)->format('H:i');}
                            elseif((\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->outTime))->hour >= 22 &&
                            (\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->outTime))->hour < 23 )
                            { $nextOut=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->outTime)->addHours(1)->addMinutes(59)->format('H:i');
                            $next=true;
                            }
                             else
                           { $nextOut=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->outTime)->addMinutes(59)->format('H:i');
                            $next=true;
                           }
                            $nextOut2=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->outTime)->subHours(3)->format('H:i');
                        @endphp



                        @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime == null)



                        @elseif($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime != null &&
                        $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime <
                        $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->outTime )

                            @if(!$next)

                                @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                                ->where('accessTime','>=',$nextOut2)->where('accessTime','<=',$nextOut)->first())

                                    @php

                                        $FINALOUT=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                                        ->where('accessTime','>=',$nextOut2)->where('accessTime','<=',$nextOut)->last()->accessTime2);
                                    @endphp

                                    {{$FINALOUT->format('H:i')}}

                                @endif
                            @else
                                @if($results->where('employeeId',$aE->id)->where('attendanceDate',$nextday)
                                ->where('accessTime','>=','00:00')->where('accessTime','<=',"04:00")->first())

                                    @php

                                        $FINALOUT=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$nextday)
                                        ->where('accessTime','>=','00:00')->where('accessTime','<=',"04:00")->last()->accessTime2);
                                    @endphp

                                    {{$FINALOUT->format('H:i')}}

                                @elseif($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                                ->where('accessTime','>=',$nextOut2)->where('accessTime','<=',$nextOut)->first())
                                    @php

                                        $FINALOUT=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                                        ->where('accessTime','>=',$nextOut2)->where('accessTime','<=',$nextOut)->last()->accessTime2);
                                    @endphp

                                    {{$FINALOUT->format('H:i')}}

                                @endif


                            @endif


                        @else

                            @php
                                $nextOut=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->outTime)->addHours(3)->format('H:i');

                            @endphp



                            @if($results->where('employeeId',$aE->id)->where('attendanceDate',$nextday)
                            ->where('accessTime','>=',$nextOut2)->where('accessTime','<=',$nextOut)->first())

                                @php

                                       $FINALOUT=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$nextday)
                                       ->where('accessTime','>=',$nextOut2)->where('accessTime','<=',$nextOut)->last()->accessTime2);
                                @endphp

                                {{$FINALOUT->format('H:i')}}



                            @endif

                        @endif


                    </td>

                    <td class="cell" style="color: #ff0505"  width="10">

                        @php
                            $nextday=\Carbon\Carbon::parse($date['date'])->addDays(1)->format('Y-m-d');
                            $previousday=\Carbon\Carbon::parse($date['date'])->subDays(1)->format('Y-m-d');
                            $in=$results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime;
                            $nextIn=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime)->subHours(3)->format('H:i');
                        @endphp

                        @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime == null)


                        @elseif($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime != null &&
                        $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime <
                        $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->outTime )

                            @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                            ->where('accessTime','>=',$nextIn)->first())

                                @php
                                $access=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                                    ->where('accessTime','>=',$nextIn)->first()->accessTime);
                                $ins=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                                        ->first()->inTime)
                                @endphp

                            @if($access->diffInMinutes($ins) >= 21 )

                                {{$access->diff($ins)->format('%H:%i')}}

                            @endif




                            @endif

                        @else



                            @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->where('accessTime','>=',$nextIn)->first())

                                @php
                                    $access=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                                        ->where('accessTime','>=',$nextIn)->first()->accessTime);
                                    $ins=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                                            ->first()->inTime)
                                @endphp

                                @if($access->diffInMinutes($ins) >= 21 )

                                    {{$access->diff($ins)->format('%H:%i')}}

                                @endif



                            @elseif($results->where('employeeId',$aE->id)->where('attendanceDate',$nextday)->first())

                                @php
                                    $access=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$nextday)
                                        ->first()->accessTime);
                                    $ins=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                                            ->first()->inTime)
                                @endphp

                                @if($access->diffInMinutes($ins) >= 21 )

                                    {{$access->diff($ins)->format('%H:%i')}}

                                @endif




                            @endif

                        @endif





                    </td>

                    <td class="cell" width="20">

                        @if($FINALIN != null && $FINALOUT != null)

                            @php
                                $FINALWORKINGHOUR=$FINALOUT->diff($FINALIN);

                            @endphp

                            {{$FINALWORKINGHOUR->format('%H:%i')}}

                        @endif


                    </td>
                    <td class="cell"  width="15">

                        P


                    </td>
                    <td class="cell"  width="15">

                        @if($FINALWORKINGHOUR != null)
                            @php
                                $ROUNDFINALWORKINGHOUR=\Carbon\Carbon::createFromTime($FINALWORKINGHOUR->format('%H'),$FINALWORKINGHOUR->format('%i'),0);
                            @endphp

                            @if($ROUNDFINALWORKINGHOUR->minute >=30)

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

                    <td class="cell" width="15">

                            @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->adjustmentDate != null)
                                @php

                                    $adjustment++;
                                    $T_adjustment=($adjustment+$T_adjustment);
                                @endphp
                                {{$results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->adjustmentDate}}
                            @endif

                    </td>

                @else

                    <td class="cell" width="10">







                    </td>
                    <td class="cell" width="10">



                    </td>

                    <td class="cell"  width="10">





                    </td>

                    <td class="cell" width="20">






                    </td>
                    @if($allLeave->where('fkEmployeeId',$aE->id)->where('startDate','<=',$date['date'])->where('endDate','>=',$date['date'])->first())
                        <td class="cell"style="color: #ffffff;background-color: #0070C0" width="15">
                            {{$allLeave->where('fkEmployeeId',$aE->id)->where('startDate','<=',$date['date'])->where('endDate','>=',$date['date'])->first()->categoryName}}
                        </td>
                    @elseif($allHoliday->where('startDate','<=',$date['date'])->where('endDate','>=',$date['date'])->first())

                        @php
                            $holiDay++;$finalholiDay=($holiDay+$finalholiDay);
                        @endphp

                        <td class="cell"style="color: #ffffff;background-color: #00ff00" width="15">
                            Holiday:{{$allHoliday->where('startDate','<=',$date['date'])->where('endDate','>=',$date['date'])->first()->purpose}}
                        </td>

                    @else

                            @php
                                $allWeekend=explode(',',strtolower($aE->weekend));
                            @endphp
                            @if(in_array(strtolower($date['day']), $allWeekend))
                                <td class="cell" style="color: #ffffff;background-color: #f7aec2" width="15">

                                    @php
                                        $weekendCount++;$T_weekendcount=($T_weekendcount+$weekendCount);
                                    @endphp

                                    WeekEnd
                                </td>
                            @else
                                <td class="cell" style="color: #ffffff;background-color: #ff0000" width="15">

                                    A
                                </td>
                            @endif
                    @endif
                    <td class="cell" width="15">



                    </td>
                    <td class="cell" width="15">



                    </td>

                @endif



                    @php
                        $FINALIN=null;$FINALOUT=null;$FINALWORKINGHOUR=null;$ROUNDFINALWORKINGHOUR=null;$weekendCount=0;$adjustment=0;$holiDay=0;$next=false;

                    @endphp


            @endforeach

            <td style="text-align: center;vertical-align: middle;"width="5"></td>
            <td style="text-align: center;vertical-align: middle;"width="15">



                {{ ( 8*(count($dates)-( $T_weekendcount + $allLeave->where('fkEmployeeId',$aE->id)->sum('noOfDays') + $finalholiDay)))}}

            </td>
            <td style="text-align: center;vertical-align: middle;"width="15">

                @if( $T_roundworkinghour != null)
                    {{ $T_roundworkinghour }}
                @endif


            </td>
            <td style="text-align: center;vertical-align: middle;"width="15">

                {{$allLeave->where('fkEmployeeId',$aE->id)->sum('noOfDays')}}

            </td>
            <td style="text-align: center;vertical-align: middle;"width="15">

                {{$T_weekendcount}}

            </td>
            <td style="text-align: center;vertical-align: middle;"width="15">

                {{$finalholiDay}}

            </td>
            <td style="text-align: center;vertical-align: middle;"width="15">

                {{$T_adjustment}}

            </td>



        </tr>

        @php
            $T_roundworkinghour=null;$T_weekendcount=0;$T_adjustment=0;$finalholiDay=0;
        @endphp



    @endforeach







    </tbody>
</table>

</body>
</html>