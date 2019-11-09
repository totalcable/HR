
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
        <th style="text-align: center;vertical-align: middle;" width="25" ></th>

        @foreach($dates as $date)
            <th class="Border" colspan="10" style="text-align: center;vertical-align: middle;">{{$date['date']}}({{$date['day']}})</th>
        @endforeach

    </tr>
    <tr >

        <th style="text-align: center;vertical-align: middle;"width="10">ID</th>
        <th style="text-align: center;vertical-align: middle;"width="25">Name</th>
        <th style="text-align: center;vertical-align: middle;"width="25">Department</th>
        @foreach($dates as $date)

            <th style="text-align: center;vertical-align: middle;background-color: #92D050"width="10">In Time</th>
            <th style="text-align: center;vertical-align: middle;background-color: #00B050"width="10">Out Time</th>

            <th style="text-align: center;vertical-align: middle;"width="10">Late Time</th>

            <th style="text-align: center;vertical-align: middle;"width="20">Total Hours Worked</th>

            <th style="text-align: center;vertical-align: middle;"width="15">Round Hour</th>
            <th style="text-align: center;vertical-align: middle;"width="15">Adjustment</th>

            <th style="text-align: center;vertical-align: middle;"width="10">Leave</th>
            <th style="text-align: center;vertical-align: middle;"width="10">Weekend</th>
            <th style="text-align: center;vertical-align: middle;"width="10">Holiday</th>
            <th style="text-align: center;vertical-align: middle;background-color:#757171"width="15">Attendence</th>
        @endforeach



    </tr>
    </thead>
    <tbody>
    <tr>

        <td width="10" ></td>
        <td width="25" ></td>
        <td width="25" ></td>
        <td width="10" ></td>
        <td width="10" ></td>

        <td width="10" ></td>

        <td width="20" ></td>


        <td width="15" ></td>
        <td width="15" ></td>
        <td width="10" ></td>
        <td width="10" ></td>
        <td width="10" ></td>
        <td width="15" ></td>






    </tr>


    @php
        $T_roundworkinghour=null;$T_weekendcount=0;$T_adjustment=0;$finalholiDay=0;$T_weekend=0;
    @endphp
    @foreach($allEmp as $aE)

        <tr>


            <td class="cell" width="10">{{$aE->attDeviceUserId}}</td>
            <td class="cell" width="25">{{$aE->empFullname}}</td>
            <td class="cell" width="25">{{$aE->departmentName}}</td>
            @php
                $FINALIN=null;$FINALOUT=null;$FINALWORKINGHOUR=null;$ROUNDFINALWORKINGHOUR=null;$weekendCount=0;$adjustment=0;$holiDay=0;$next=false;
                $weekend=0;

            @endphp
            @foreach($dates as $date)

                @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first())
                    <td class="cell" width="10">

                        @php
                            $nextday=\Carbon\Carbon::parse($date['date'])->addDays(1)->format('Y-m-d');
                            $previousday=\Carbon\Carbon::parse($date['date'])->subDays(1)->format('Y-m-d');

                        @endphp

                        @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                        ->first()->inTime == null)


                            {{\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                            ->first()->accessTime2)->format('H:i')}}


                        @elseif($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime != null  &&
                                    $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->outTime !=null &&
                                    $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime <
                                    $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->outTime
                                )

                            @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime =='00:00:00')

                                @if($results->where('employeeId',$aE->id)->where('attendanceDate',$previousday)
                                ->where('accessTime','>=','21:00:00')->where('fkAttDevice',$aE->inDeviceNo)->first())

                                    @php
                                        $FINALIN=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$previousday)
                                            ->where('accessTime','>=','21:00:00')->where('fkAttDevice',$aE->inDeviceNo)->first()->accessTime2);
                                    @endphp

                                    {{$FINALIN->format('H:i')}}

                                @elseif($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                                ->where('accessTime','<=','04:00:00')->where('fkAttDevice',$aE->inDeviceNo)->first())

                                    @php
                                        $FINALIN=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                                            ->where('accessTime','<=','04:00:00')->where('fkAttDevice',$aE->inDeviceNo)->first()->accessTime2);
                                    @endphp

                                    {{$FINALIN->format('H:i')}}

                                @endif

                            @else



                                    @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->where('fkAttDevice',$aE->inDeviceNo)
                                        ->first())

                                        @php
                                            $FINALIN=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                                            ->where('fkAttDevice',$aE->inDeviceNo)
                                                ->first()->accessTime2);
                                        @endphp

                                        {{$FINALIN->format('H:i')}}
                                    @endif





                            @endif

                        @else

                            @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->where('fkAttDevice',$aE->inDeviceNo)
                                ->where('accessTime','>=','19:00:00')->first())

                                @php
                                    $FINALIN=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                                    ->where('fkAttDevice',$aE->inDeviceNo)
                                        ->where('accessTime','>=','19:00:00')->first()->accessTime2);
                                @endphp

                                {{$FINALIN->format('H:i')}}

                            @endif

                        @endif






                    </td>
                    <td class="cell" width="10">

                        @php
                            $nextday=\Carbon\Carbon::parse($date['date'])->addDays(1)->format('Y-m-d');
                            $previousday=\Carbon\Carbon::parse($date['date'])->subDays(1)->format('Y-m-d');



                        @endphp

                        @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->outTime == null)

                            {{\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                                    ->last()->accessTime2)->format('H:i')}}



                        @elseif(    $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime != null  &&
                                    $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->outTime !=null &&
                                    $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime <
                                    $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->outTime
                               )

                            @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime =='00:00:00')

                                @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                                ->where('accessTime','<=','18:00:00')->where('fkAttDevice',$aE->outDeviceNo)->first())

                                    @php
                                        $FINALOUT=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                                            ->where('accessTime','<=','18:00:00')->where('fkAttDevice',$aE->outDeviceNo)->last()->accessTime2);
                                    @endphp

                                    {{$FINALOUT->format('H:i')}}


                                @endif

                            @else

                                @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime < '11:00:00')



                                    @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                                    ->where('accessTime','<=','23:59:59')->where('fkAttDevice',$aE->outDeviceNo)
                                        ->first())

                                        @php
                                            $FINALOUT=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                                            ->where('accessTime','<=','23:59:59')->where('fkAttDevice',$aE->outDeviceNo)
                                                ->last()->accessTime2);
                                        @endphp

                                        {{$FINALOUT->format('H:i')}}
                                    @endif

                                @else

                                    @if($results->where('employeeId',$aE->id)->where('attendanceDate',$nextday)
                                    ->where('accessTime','<=','04:00:00')->where('fkAttDevice',$aE->outDeviceNo)
                                        ->first())

                                        @php
                                            $FINALOUT=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$nextday)
                                            ->where('accessTime','<=','04:00:00')->where('fkAttDevice',$aE->outDeviceNo)
                                                ->last()->accessTime2);
                                        @endphp

                                        {{$FINALOUT->format('H:i')}}
                                    @elseif($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                                    ->where('accessTime','<=','23:59:59')->where('fkAttDevice',$aE->outDeviceNo)
                                        ->first())

                                        @php
                                            $FINALOUT=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                                            ->where('accessTime','<=','23:59:59')->where('fkAttDevice',$aE->outDeviceNo)
                                                ->last()->accessTime2);
                                        @endphp

                                        {{$FINALOUT->format('H:i')}}

                                    @endif


                                @endif




                            @endif
                        @else

                            @if($results->where('employeeId',$aE->id)->where('attendanceDate',$nextday)
                                ->where('accessTime','<=','13:00:00')->where('fkAttDevice',$aE->outDeviceNo)->first())

                                @php
                                    $FINALOUT=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$nextday)
                                        ->where('accessTime','<=','13:00:00')->where('fkAttDevice',$aE->outDeviceNo)->last()->accessTime2);
                                @endphp

                                {{$FINALOUT->format('H:i')}}


                            @endif



                        @endif



                    </td>

                    <td class="cell" style="color: #ff0505"  width="10">



                    </td>
                    <td class="cell" style="color: #ff0505"  width="20">

                        @if($FINALIN != null && $FINALOUT != null)

                            @php
                                $FINALWORKINGHOUR=$FINALOUT->diff($FINALIN);

                            @endphp

                            {{$FINALWORKINGHOUR->format('%H:%i')}}

                        @endif



                    </td>

                    <td class="cell" width="15">

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

                    <td class="cell" width="15">

                        @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->adjustmentDate != null)
                            @php

                                $adjustment++;
                                $T_adjustment=($adjustment+$T_adjustment);
                            @endphp
                            {{$results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->adjustmentDate}}
                        @endif



                    </td>

                        <td class="cell" width="10">
                            @if($allLeave->where('fkEmployeeId',$aE->id)->where('startDate','<=',$date['date'])->where('endDate','>=',$date['date'])->first())
                                {{$allLeave->where('fkEmployeeId',$aE->id)->where('startDate','<=',$date['date'])->where('endDate','>=',$date['date'])->first()->categoryName}}
                            @endif
                        </td>
                    <td class="cell" width="10">
                        @if($allWeekend->where('fkemployeeId',$aE->id)->where('startDate','<=',$date['date'])->where('endDate','>=',$date['date'])->first())
                                Weekend
                        @endif
                    </td>



                        <td class="cell" width="10">

                            @if($allHoliday->where('fkemployeeId',$aE->id)->where('startDate','<=',$date['date'])->where('endDate','>=',$date['date'])->first())

                                holiday
                            @endif

                        </td>



                    @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime == null)
                        <td class="cell" style="color: firebrick"  width="15">
                            roster not found
                            <br>

                            @php

                                $FINALIN=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                                ->first()->accessTime2);
                                $FINALOUT=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
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

                        <td class="cell" style="color: firebrick" width="15">
                                P
                        </td>


                    @endif






                @else

                    <td class="cell" width="10">







                    </td>
                    <td class="cell" width="10">



                    </td>

                    <td class="cell"  width="10">





                    </td>

                    <td class="cell" width="20">






                    </td>

                    <td class="cell" width="15">



                    </td>
                    <td class="cell" width="15">



                    </td>








                    <td class="cell" width="10" ></td>
                    <td class="cell" width="10" ></td>
                    <td class="cell" width="10" ></td>



                        <td class="cell" style="color: #ffa811;" width="15">

                            A

                        </td>




                @endif



                @php
                    $FINALIN=null;$FINALOUT=null;$FINALWORKINGHOUR=null;$ROUNDFINALWORKINGHOUR=null;$weekendCount=0;$adjustment=0;$holiDay=0;$next=false;

                @endphp


            @endforeach


        </tr>

        @php
            $T_roundworkinghour=null;$T_weekendcount=0;$T_adjustment=0;$finalholiDay=0;
        @endphp



    @endforeach

    </tbody>
</table>

</body>
</html>