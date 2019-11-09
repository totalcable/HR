
<html>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="{{url('public/css/exceltable.css')}}" rel="stylesheet"> <!-- Css file call-->


<body>

<table class="blueTable">
    <thead>
    <tr>
        <!-- date renge wise show-->
        <th colspan="12" style="text-align: center;">Multiple Roster Report 1 -( {{\Carbon\Carbon::parse($startDate)->format('Y-m-d')}} - {{\Carbon\Carbon::parse($endDate)->format('Y-m-d')}} )</th>

    </tr>
    <!-- employee information view-->
    <tr>
        <td style="vertical-align: middle;text-align: center;"></td>
        <th colspan="4"style="text-align: center;vertical-align: middle;" >Name: {{$allE->empFullname}}</th><!-- employee Name-->
        <th colspan="3"style="text-align: center;vertical-align: middle;" >ID: {{$allE->attDeviceUserId}}</th><!-- employee Device Name-->
    </tr>
    <tr>
        <td style="vertical-align: middle;text-align: center;"></td>
        <th colspan="4"style="text-align: center;vertical-align: middle;" >Department: {{$allE->departmentName}}</th>
        <th colspan="3"style="text-align: center;vertical-align: middle;" >Designation: {{$allE->designationTitle}}</th>
    </tr>
    <tr>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="25">Date</th>

        <!-- shift wise Roster view-->
        @foreach($RosterInfo as $RI)
            <th class="Border" colspan="5" style="text-align: center;vertical-align: middle;" >{{$RI->inTime}}-{{$RI->outTime}}</th><!-- Roster in and out time -->
        @endforeach



    </tr>
    <tr>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="25"></th>
        @foreach($RosterInfo as $RI)<!-- 1 roster data show table head-->

            <th class="Border"style="text-align: center;vertical-align: middle;" width="15">IN Time</th>
            <th class="Border"style="text-align: center;vertical-align: middle;" width="15">OUT Time</th>
            <th class="Border"style="text-align: center;vertical-align: middle;" width="20">Late Day / Hours</th>
            <th class="Border"style="text-align: center;vertical-align: middle;" width="15">Working Hour</th>
            <th class="Border"style="text-align: center;vertical-align: middle;" width="25">Round Working Hour</th>


        @endforeach
        <th class="Border"style="text-align: center;vertical-align: middle;" width="25">Total Hour</th>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="25">Attendance</th>

    </tr>
    <tr>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="25"></th>
        @foreach($RosterInfo as $RI)

            <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>
            <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>
            <th class="Border"style="text-align: center;vertical-align: middle;" width="20"></th>
            <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>
            <th class="Border"style="text-align: center;vertical-align: middle;" width="25"></th>



        @endforeach
        <th class="Border"style="text-align: center;vertical-align: middle;" width="25"></th>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="25"></th>
    </tr>


    </thead>
    <tbody>



    <!-- date loop-->
    @foreach($dates as $date)

        @php
            $T_roundworkinghour=null;$T_weekendcount=0;$T_adjustment=0;$finalholiDay=0;$T_weekend=0;$T_late=0;$T_LateHour=0;$T_FinalWorkHour=0;
        $T_offDay=0;$T_govHoliday=0;$T_leave=0;$T_present=0;$C_RL=0;
        @endphp



        <tr>
            <td style="text-align: left;vertical-align: middle;" width="25" class="Border">
                {{$date['date']}}({{$date['day']}})
            </td>



            <!-- Roster loop-->
            @foreach($RosterInfo as $RI)
                @php
                    $FINALIN=null;$FINALOUT=null;$FINALWORKINGHOUR=null;$ROUNDFINALWORKINGHOUR=null;$weekendCount=0;$adjustment=0;$holiDay=0;$next=false;
                    $weekend=0;$late=0;$LateHour=0;$FINALWORKINGHOUR2=0;$offDay=0;$govHoliday=0;$leave=0;$present=0;$access=null;$ins=null;
                    $C_RL++;

                @endphp

                @if($date['date'] <= \Carbon\Carbon::now()->format('Y-m-d')) <!-- check date less or equal current date-->

                    @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()) <!-- check if punch exist or not by employee id and date-->

                        @php
                            $nextday=\Carbon\Carbon::parse($date['date'])->addDays(1)->format('Y-m-d');
                            $previousday=\Carbon\Carbon::parse($date['date'])->subDays(1)->format('Y-m-d');


                        @endphp
                        <td class="Border"style="text-align: center;vertical-align: middle;" width="15">

                            @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime == null)
                                <!-- check if inTime null or not  by employee id and date-->

                                {{
                                    \Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                    ->first()->accessTime2)->format('H:i')
                                }}
                                <!-- show the first punch as intime of the date-->
                            @else


                                <!-- check if inTime 12Am  by employee id and date-->
                                @if($RI->inTime == '00:00:00')

                                    @if($results->where('employeeId',$allE->id)->where('attendanceDate',$previousday)
                                       ->where('accessTime','>=','21:00:00')->where('fkAttDevice',$allE->inDeviceNo)->first())
                                        <!-- check if there is any punch after 9 pm in previous day  by employee id and date-->


                                        @php
                                            $i=0;
                                        @endphp

                                        @foreach($results->where('attendanceDate',$previousday)->where('employeeId',$allE->id)->where('fkAttDevice',$allE->inDeviceNo)
                                            ->where('accessTime','>=','21:00:00') as $in)



                                            @if($i==0)

                                                @php
                                                    $FINALIN=\Carbon\Carbon::parse($in->accessTime2);
                                                @endphp

                                                {{$FINALIN->format('H:i')}}

                                            @endif
                                            <!-- check if there is any punch after 9 pm in previous day then show first punch as intime  by employee id and date-->
                                            @php
                                                $i++;
                                            @endphp

                                        @endforeach

                                        @php
                                            $i=0;
                                        @endphp



                                    @elseif($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                                     ->where('fkAttDevice',$allE->inDeviceNo)->first())
                                        <!-- check if there is any punch of the day by employee id and date-->
                                        @php
                                            $i=0;
                                        @endphp

                                        @foreach($results->where('attendanceDate',$previousday)->where('employeeId',$allE->id)->where('fkAttDevice',$allE->inDeviceNo)
                                            ->where('accessTime','>=' ,$RI->inTime)->where('accessTime','<=', $RI->outTime) as $in)



                                            @if($i==0)

                                                @php
                                                    $FINALIN=\Carbon\Carbon::parse($in->accessTime2);
                                                @endphp

                                                {{$FINALIN->format('H:i')}}

                                            @endif
                                            <!-- check if there is any punch of the day show the first punch as in Time by employee id and date-->
                                            @php
                                                $i++;
                                            @endphp

                                        @endforeach

                                        @php
                                            $i=0;
                                        @endphp



                                    @endif




                                 <!-- check if there is any punch before 2 hours of roster in Time  by employee id and date-->
                                @elseif($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                                     ->where('accessTime','>=' ,\Carbon\Carbon::parse($RI->inTime)->subHours(2)->format('H:i:s'))
                                                     ->where('accessTime','<=', $RI->outTime)
                                                     ->where('fkAttDevice',$allE->inDeviceNo)->first())



                                    @php
                                        $i=0;
                                    @endphp

                                    @foreach($results->where('attendanceDate',$date['date'])->where('employeeId',$allE->id)->where('fkAttDevice',$allE->inDeviceNo)
                                        ->where('accessTime','>=' ,\Carbon\Carbon::parse($RI->inTime)->subHours(2)->format('H:i:s'))
                                                     ->where('accessTime','<=', $RI->outTime)->sortBy('accessTime') as $in)



                                        @if($i==0)

                                            @php
                                                $FINALIN=\Carbon\Carbon::parse($in->accessTime2);
                                            @endphp

                                            {{$FINALIN->format('H:i')}}

                                        @endif
                                        <!-- check if there is any punch before 2 hours of roster in Time then show first punch as in Time  by employee id and date-->
                                        @php
                                            $i++;
                                        @endphp

                                    @endforeach

                                    @php
                                        $i=0;
                                    @endphp



                                @endif



                            @endif



                        </td>
                        <td class="Border"style="text-align: center;vertical-align: middle;" width="15">
                            <!-- check if in Time null or not  by employee id and date-->
                            @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->outTime == null)

                                {{
                                    \Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                    ->last()->accessTime2)->format('H:i')
                                }}
                                <!-- show the last punch as intime of the date-->
                            @else


                                <!-- check if out Time 12Am  by employee id and date-->
                                @if($RI->outTime == '00:00:00')

                                    @if($results->where('employeeId',$allE->id)->where('attendanceDate',$nextday)
                                       ->where('accessTime','<=','03:00:00')->where('fkAttDevice',$allE->outDeviceNo)->first())
                                        <!-- check if there is any punch before 3 am in next day  by employee id and date-->
                                        @php
                                            $ii=0;
                                            $len=count($results->where('employeeId',$allE->id)
                                            ->where('fkAttDevice',$allE->outDeviceNo)->where('attendanceDate',$nextday)
                                            ->where('accessTime','<=','03:00:00'));
                                        @endphp

                                        @foreach($results->where('employeeId',$allE->id)->where('fkAttDevice',$allE->outDeviceNo)
                                         ->where('attendanceDate',$nextday)
                                            ->where('accessTime','<=','03:00:00') as $out)

                                            @if($ii==($len-1))

                                                @php
                                                    $FINALOUT=\Carbon\Carbon::parse($out->accessTime2);
                                                @endphp

                                                {{$FINALOUT->format('H:i')}}

                                                <!-- check if there is any punch before 3 am in next day then show the last punch as out Time by employee id and date-->


                                            @endif

                                            @php
                                                $ii++;
                                            @endphp





                                        @endforeach


                                        @php
                                            $ii=0;
                                        @endphp
                                        <!-- check if there is any punch between roster in time and out time + 1 hour  of that day  by employee id and date-->
                                    @elseif($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                                     ->where('fkAttDevice',$allE->outDeviceNo)
                                                     ->where('accessTime','>=' ,$RI->inTime)
                                                     ->where('accessTime','<=', \Carbon\Carbon::parse($RI->outTime)->addHours(1)->format('H:i:s'))->first())

                                        @php
                                            $ii=0;
                                            $len=count($results->where('employeeId',$allE->id)
                                            ->where('fkAttDevice',$allE->outDeviceNo)->where('attendanceDate',$date['date'])
                                            ->where('accessTime','>=' ,$RI->inTime)
                                                     ->where('accessTime','<=', \Carbon\Carbon::parse($RI->outTime)->addHours(1)->format('H:i:s')));
                                        @endphp

                                        @foreach($results->where('employeeId',$allE->id)->where('fkAttDevice',$allE->outDeviceNo)
                                         ->where('attendanceDate',$date['date'])
                                            ->where('accessTime','>=' ,$RI->inTime)
                                                     ->where('accessTime','<=', \Carbon\Carbon::parse($RI->outTime)->addHours(1)->format('H:i:s')) as $out)

                                            @if($ii==($len-1))

                                                @php
                                                    $FINALOUT=\Carbon\Carbon::parse($out->accessTime2);
                                                @endphp

                                                {{$FINALOUT->format('H:i')}}

                                                <!-- check if there is any punch between roster in time and out time + 1 hour of that day then show last punch as out Time  by employee id and date-->


                                            @endif

                                            @php
                                                $ii++;
                                            @endphp





                                        @endforeach


                                        @php
                                            $ii=0;
                                        @endphp


                                    @endif
                                    <!-- check if out Time is equal or before 9 pm-->
                                @elseif($RI->outTime <= '21:00:00')

                                    @php
                                        $ii=0;
                                        $len=count($results->where('employeeId',$allE->id)
                                        ->where('fkAttDevice',$allE->outDeviceNo)->where('attendanceDate',$date['date'])
                                        ->where('accessTime','>=' ,$RI->inTime)
                                        ->where('accessTime','<=', \Carbon\Carbon::parse($RI->outTime)->addHours(2)->format('H:i:s')));
                                    @endphp


                                    <!-- check if there is any punch between roster in time and out time + 2 hour  of that day  by employee id and date-->
                                    @foreach($results->where('employeeId',$allE->id)->where('fkAttDevice',$allE->outDeviceNo)
                                     ->where('attendanceDate',$date['date'])
                                        ->where('accessTime','>=' ,$RI->inTime)
                                                 ->where('accessTime','<=', \Carbon\Carbon::parse($RI->outTime)->addHours(2)->format('H:i:s')) as $out)

                                        @if($ii==($len-1))

                                            @php
                                                $FINALOUT=\Carbon\Carbon::parse($out->accessTime2);
                                            @endphp

                                            {{$FINALOUT->format('H:i')}}


                                            <!-- check if there is any punch between roster in time and out time + 2 hour  of that day then show the last punch as out Time by employee id and date-->

                                        @endif

                                        @php
                                            $ii++;
                                        @endphp





                                    @endforeach


                                    @php
                                        $ii=0;
                                    @endphp

                                    <!-- check if out Time is after 9 pm-->
                                @elseif($RI->outTime > '21:00:00')

                                    @if($results->where('employeeId',$allE->id)->where('attendanceDate',$nextday)
                                      ->where('accessTime','<=','03:00:00')->where('fkAttDevice',$allE->outDeviceNo)->first())
                                        <!-- check if there is any punch before 3 am in next day  by employee id and date-->

                                            @php
                                                $ii=0;
                                                $len=count($results->where('employeeId',$allE->id)->where('attendanceDate',$nextday)
                                                    ->where('accessTime','<=','03:00:00')->where('fkAttDevice',$allE->outDeviceNo));
                                            @endphp


                                            <!-- check if there is any punch before 3 am in next day  by employee id and date-->
                                            @foreach($results->where('employeeId',$allE->id)->where('attendanceDate',$nextday)
                                                    ->where('accessTime','<=','03:00:00')->where('fkAttDevice',$allE->outDeviceNo) as $out)

                                                @if($ii==($len-1))

                                                    @php
                                                        $FINALOUT=\Carbon\Carbon::parse($out->accessTime2);
                                                    @endphp

                                                    {{$FINALOUT->format('H:i')}}


                                                    <!-- check if there is any punch before 3 am in next day then show out time by employee id and date-->
                                                @endif

                                                @php
                                                    $ii++;
                                                @endphp





                                            @endforeach


                                            @php
                                                $ii=0;
                                            @endphp
                                     @else

                                        @php
                                            $ii=0;
                                            $len=count($results->where('employeeId',$allE->id)
                                            ->where('fkAttDevice',$allE->outDeviceNo)->where('attendanceDate',$date['date'])
                                            ->where('accessTime','>=' ,$RI->inTime)
                                            ->where('accessTime','<=', \Carbon\Carbon::parse($RI->outTime)->addMinutes(59)->format('H:i:s')));
                                        @endphp


                                        <!-- check if there is any punch between roster in time and out time + 59 minutes  of that day  by employee id and date-->
                                        @foreach($results->where('employeeId',$allE->id)->where('fkAttDevice',$allE->outDeviceNo)
                                         ->where('attendanceDate',$date['date'])
                                            ->where('accessTime','>=' ,$RI->inTime)
                                                     ->where('accessTime','<=', \Carbon\Carbon::parse($RI->outTime)->addMinutes(59)->format('H:i:s')) as $out)

                                            @if($ii==($len-1))

                                                @php
                                                    $FINALOUT=\Carbon\Carbon::parse($out->accessTime2);
                                                @endphp

                                                {{$FINALOUT->format('H:i')}}


                                                <!-- check if there is any punch between roster in time and out time + 59 minutes  of that day then show the last punch as out Time by employee id and date-->

                                                @endif

                                                @php
                                                    $ii++;
                                                @endphp





                                            @endforeach


                                            @php
                                                $ii=0;
                                            @endphp

                                     @endif



                                @endif
                            @endif


                        </td>
                        <td class="Border"style="text-align: center;vertical-align: middle;" width="20">


                                <!-- check if inTime null or not  by employee id and date-->
                            @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime == null)

                                {{
                                    \Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                    ->first()->accessTime2)->format('H:i')
                                }}
                                <!-- show the first punch as intime of the date-->
                            @else


                                <!-- check if inTime 12Am  by employee id and date-->
                                @if($RI->inTime == '00:00:00')

                                    @if($results->where('employeeId',$allE->id)->where('attendanceDate',$previousday)
                                       ->where('accessTime','>=','21:00:00')->where('fkAttDevice',$allE->inDeviceNo)->first())
                                        <!-- check if there is any punch after 9 pm in previous day  by employee id and date-->

                                        @php
                                            $i=0;
                                        @endphp

                                        @foreach($results->where('attendanceDate',$previousday)->where('employeeId',$allE->id)->where('fkAttDevice',$allE->inDeviceNo)
                                            ->where('accessTime','>=','21:00:00') as $in)



                                            @if($i==0)

                                                @php
                                                    $access=\Carbon\Carbon::parse($in->accessTime);
                                                    $ins=\Carbon\Carbon::parse($in->inTime);
                                                @endphp
                                                <!-- check if there is any punch after 9 pm in previous day then calculate punch time and roster inTime  by employee id and date-->
                                            @endif

                                            @php
                                                $i++;
                                            @endphp

                                        @endforeach

                                        @php
                                            $i=0;
                                        @endphp



                                    @elseif($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                                     ->where('fkAttDevice',$allE->inDeviceNo)->first())
                                        <!-- check if there is any punch in that day  by employee id and date-->
                                        @php
                                            $i=0;
                                        @endphp

                                        @foreach($results->where('attendanceDate',$previousday)->where('employeeId',$allE->id)->where('fkAttDevice',$allE->inDeviceNo)
                                            ->where('accessTime','>=' ,$RI->inTime)->where('accessTime','<=', $RI->outTime) as $in)

                                            <!-- check if there is any punch between roster in Time and out Time on that day then calculate punch time and roster inTime  by employee id and date-->

                                            @if($i==0)

                                                @php
                                                    $access=\Carbon\Carbon::parse($in->accessTime);
                                                    $ins=\Carbon\Carbon::parse($in->inTime);
                                                @endphp
                                                <!-- check if there is any punch between roster in Time and out Time on that day then calculate punch time and roster inTime  by employee id and date-->
                                            @endif

                                            @php
                                                $i++;
                                            @endphp

                                        @endforeach

                                        @php
                                            $i=0;
                                        @endphp



                                    @endif




                                    <!-- check if there is any punch before 2 hours of roster in Time  by employee id and date-->
                                @elseif($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                                     ->where('accessTime','>=' ,\Carbon\Carbon::parse($RI->inTime)->subHours(2)->format('H:i:s'))
                                                     ->where('accessTime','<=', $RI->outTime)
                                                     ->where('fkAttDevice',$allE->inDeviceNo)->first())



                                    @php
                                        $i=0;
                                    @endphp

                                    @foreach($results->where('attendanceDate',$date['date'])->where('employeeId',$allE->id)->where('fkAttDevice',$allE->inDeviceNo)
                                        ->where('accessTime','>=' ,\Carbon\Carbon::parse($RI->inTime)->subHours(2)->format('H:i:s'))
                                                     ->where('accessTime','<=', $RI->outTime)->sortBy('accessTime') as $in)



                                        @if($i==0)

                                            @php
                                                $access=\Carbon\Carbon::parse($in->accessTime);
                                                $ins=\Carbon\Carbon::parse($in->inTime);
                                            @endphp
                                            <!-- check if there is any punch before 2 hours of roster in Time then calculate pumch time and Roster in time  by employee id and date-->
                                        @endif

                                        @php
                                            $i++;
                                        @endphp

                                    @endforeach

                                    @php
                                        $i=0;
                                    @endphp



                                @endif



                            @endif



                        <!-- Calculate late if punch time is grater then 21 miniutes  -->
                        @if($access !=null && $ins!=null && $access > $ins)

                                @if($access->diffInMinutes($ins) >= 21 )


                                    {{$access->diff($ins)->format('%H:%i')}}
                                    <!-- Calculate late if punch time is grater then 21 miniutes  -->
                                @endif
                            @endif


                        </td>
                        <td class="Border"style="text-align: center;vertical-align: middle;" width="15">
                            <!-- Calculate Total working hour  -->
                            @if($FINALIN != null && $FINALOUT != null)
                                <!-- Calculate diffrence in in time and out time & calculate total working time in miniute  -->
                                @php
                                    $FINALWORKINGHOUR=$FINALOUT->diff($FINALIN);
                                    $FINALWORKINGHOUR2=$FINALOUT->diffInMinutes($FINALIN);
                                    $T_FinalWorkHour=($FINALWORKINGHOUR2+$T_FinalWorkHour);

                                @endphp

                                {{$FINALWORKINGHOUR->format('%H:%i')}}
                                <!-- show final working hour  -->
                            @endif





                        </td>
                        <td class="Border"style="text-align: center;vertical-align: middle;" width="25">

                            @if($FINALWORKINGHOUR != null)
                                @php
                                    $ROUNDFINALWORKINGHOUR=\Carbon\Carbon::createFromTime($FINALWORKINGHOUR->format('%H'),$FINALWORKINGHOUR->format('%i'),0);
                                @endphp
                                <!-- if  final working houre (if miniute greater then or equal 25 then increase 1 hour and miniute gets round to 0 )  -->
                                @if($ROUNDFINALWORKINGHOUR->minute >=25)

                                    @php
                                        $ROUNDFINALWORKINGHOUR->minute(0);
                                        $ROUNDFINALWORKINGHOUR->addHour();
                                        $T_roundworkinghour=($T_roundworkinghour+$ROUNDFINALWORKINGHOUR->hour);
                                    @endphp

                                @else
                                    <!-- else only  ( miniute gets round to 0 )  -->
                                    @php
                                        $ROUNDFINALWORKINGHOUR->minute(0);
                                        $T_roundworkinghour=($T_roundworkinghour+$ROUNDFINALWORKINGHOUR->hour);

                                    @endphp

                                @endif
                                <!-- show round total working hour  -->
                                {{$ROUNDFINALWORKINGHOUR->format('H:i')}}

                            @endif

                        </td>



                    @else

                        <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>
                        <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>
                        <th class="Border"style="text-align: center;vertical-align: middle;" width="20"></th>
                        <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>
                        <th class="Border"style="text-align: center;vertical-align: middle;" width="25"></th>


                    @endif


                @else

                    <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>
                    <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>
                    <th class="Border"style="text-align: center;vertical-align: middle;" width="20"></th>
                    <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>
                    <th class="Border"style="text-align: center;vertical-align: middle;" width="25"></th>




                @endif

                @php
                    $late=0;$LateHour=0;
                   $ROUNDFINALWORKINGHOUR=null;$adjustment=0;
                   $FINALWORKINGHOUR2=0;

                   $offDay=0;$govHoliday=0;
                   $leave=0;
                   $present=0;$access=null;$ins=null;




                @endphp




            @endforeach

            <th class="Border"style="text-align: center;vertical-align: middle;" width="25">
                {{$T_roundworkinghour}}
                <!-- Total of  round total working hour  -->
            </th>
            <th class="Border"style="text-align: center;vertical-align: middle;" width="15">

                @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first())

                    p
                    <!-- if there is a punch of that day then shows present  -->

                @else
                    <!-- check if there is any leave of that day by emp    -->
                    @if($allLeave->where('fkEmployeeId',$allE->id)->where('startDate','<=',$date['date'])->where('endDate','>=',$date['date'])->first())


                            {{$allLeave->where('fkEmployeeId',$allE->id)->where('startDate','<=',$date['date'])->where('endDate','>=',$date['date'])->first()->categoryName}}


                        <!-- check if there is any off day of that day by emp    -->
                    @elseif($allWeekend->where('fkemployeeId',$allE->id)->where('startDate','<=',$date['date'])->where('endDate','>=',$date['date'])->first())



                            Day Off



                            <!-- check if there is any govt. holiday of that day by emp    -->
                    @elseif($govtHoliday->where('startDate','<=',$date['date'])->where('endDate','>=',$date['date'])->first())



                            Govt Holiday

                    @else
                        <!-- other wise show absent    -->
                        Absent
                    @endif



                @endif

            </th>
        </tr>



    @endforeach




    </tbody>
</table>

</body>
</html>