
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

        <th colspan="12"style="vertical-align: middle;text-align: center;" >Final Report</th>
    </tr>
    <tr>
        <th colspan="12" style="text-align: center;vertical-align: middle;">Date : {{\Carbon\Carbon::parse($startDate)->format('Y-m-d')}} - {{\Carbon\Carbon::parse($endDate)->format('Y-m-d')}}</th>
    </tr>
    <tr >

        <th style="text-align: center;vertical-align: middle;"width="10">ID</th>
        <th style="text-align: center;vertical-align: middle;"width="25">Name</th>

        <th style="text-align: center;vertical-align: middle;"width="15"></th>
        <th style="text-align: center;vertical-align: middle;"width="15">Hour Expected</th>
        <th style="text-align: center;vertical-align: middle;"width="15">Total Hour</th>
        <th style="text-align: center;vertical-align: middle;"width="15">Total Leave</th>
        <th style="text-align: center;vertical-align: middle;"width="15">Total Off Day</th>
        <th style="text-align: center;vertical-align: middle;"width="15">Total Holiday</th>
        <th style="text-align: center;vertical-align: middle;"width="25">Total Addjustment</th>
        <th style="text-align: center;vertical-align: middle;"width="15">Total Present</th>
        <th style="text-align: center;vertical-align: middle;"width="15">Total Absent</th>
        <th style="text-align: center;vertical-align: middle;"width="25">S / E (Hour)</th>
        <th style="text-align: center;vertical-align: middle;"width="25">Gross Salary</th>
        <th style="text-align: center;vertical-align: middle;"width="25">Basic salary</th>
        <th style="text-align: center;vertical-align: middle;"width="25">Salary others option</th>
        <th style="text-align: center;vertical-align: middle;"width="25">Daily</th>
        <th style="text-align: center;vertical-align: middle;"width="25">Total Payable</th>

    </tr>
    </thead>
    <tbody>

    @php
        $T_roundworkinghour=null;
        $T_weekendcount=0;
        $T_adjustment=0;
        $finalholiDay=0;
        $T_FINALWORKINGHOUR=null;
        $T_govHoliday=0;
        $T_dateCount=0;
    @endphp

    @foreach($allEmp as $aE)

        @php
            $FINALIN=null;
            $FINALOUT=null;
            $FINALWORKINGHOUR=null;
            $ROUNDFINALWORKINGHOUR=null;
            $adjustment=0;
            $holiDay=0;
            $next=false;
            $weekend=0;
            $T_present=0;
            $govHoliday=0;
            $dateCount=0;
            $basicSalary=0;

            $houseRent=0;
            $medical=0;
            $CompanyProvident=0;
            $PersonalProvident=0;
            $dailySalary=0;
            $payAbleSalary=0;

        @endphp

        @foreach($dates as $date)

            @if($date['date'] <= \Carbon\Carbon::now()->format('Y-m-d'))

                @php
                    $nextday=\Carbon\Carbon::parse($date['date'])->addDays(1)->format('Y-m-d');
                    $previousday=\Carbon\Carbon::parse($date['date'])->subDays(1)->format('Y-m-d');
                    $present=0;

                  $dateCount++;
                  $T_dateCount=($T_dateCount+$dateCount);

                @endphp

                @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first())

                    @php

                        $present++;
                        $T_present=($T_present+$present);
                    @endphp

                    @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime != null  &&
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



                            @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime < '09:00:00')

                                @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                                            ->where('fkAttDevice',$aE->inDeviceNo)->where('accessTime','<','09:00:00')->first())

                                    @php
                                        $FINALIN=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                                            ->where('fkAttDevice',$aE->inDeviceNo)->where('accessTime','<','09:00:00')->first()->accessTime2);
                                    @endphp

                                    {{$FINALIN->format('H:i')}}

                                @endif
                            @elseif($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime >= '09:01:00' && $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime <= '18:00:00')

                                @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                                            ->where('fkAttDevice',$aE->inDeviceNo)->where('accessTime','>=','09:01:00')->where('accessTime','<=','18:00:00')->first())

                                    @php
                                        $FINALIN=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                                            ->where('fkAttDevice',$aE->inDeviceNo)->where('accessTime','>=','09:01:00')->where('accessTime','<=','18:00:00')->first()->accessTime2);
                                    @endphp

                                    {{$FINALIN->format('H:i')}}

                                @endif


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
                @endif

                @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first())

                    @if(    $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime != null  &&
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
                @endif

                @if($FINALIN != null && $FINALOUT != null)

                    @php
                        $FINALWORKINGHOUR=$FINALOUT->diff($FINALIN);

                    @endphp



                @endif

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



                @endif





                @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first())

                    @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->adjustmentDate != null)
                        @php

                            $adjustment++;
                            $T_adjustment=($adjustment+$T_adjustment);
                        @endphp

                    @endif
                @endif

                @if($allWeekend->where('fkemployeeId',$aE->id)->where('startDate','<=',$date['date'])->where('endDate','>=',$date['date'])->first())
                    @php

                        $weekend++;
                        $T_weekendcount=($weekend+$T_weekendcount);
                    @endphp
                @endif

                @if($govtHoliday->where('startDate','<=',$date['date'])->where('endDate','>=',$date['date'])->first())

                    @php

                        $govHoliday++;
                      $T_govHoliday=($T_govHoliday+$govHoliday);
                    @endphp


                @endif

                @php
                    $FINALIN=null;$FINALOUT=null;$FINALWORKINGHOUR=null;$ROUNDFINALWORKINGHOUR=null;$weekend=0;$adjustment=0;$holiDay=0;$next=false;$present=0;$govHoliday=0;$dateCount=0;

                @endphp

            @endif

        @endforeach
        <tr>

            <td class="cell" width="10">{{$aE->attDeviceUserId}}</td>
            <td class="cell" width="25">{{$aE->empFullname}}</td>
            <td style="text-align: center;vertical-align: middle;"width="15">

            </td>
            <td style="text-align: center;vertical-align: middle;"width="15">
                {{ ( 8*($T_dateCount-( $T_weekendcount + $allLeave->where('fkEmployeeId',$aE->id)->where('startDate','>=',$startDate)->where('endDate','<=',$endDate)->sum('noOfDays') + $T_govHoliday + $T_adjustment)))}}
            </td>
            <td style="text-align: center;vertical-align: middle;"width="15">


                {{$T_roundworkinghour}}

            </td>
            <td style="text-align: center;vertical-align: middle;"width="15">


                {{$allLeave->where('fkEmployeeId',$aE->id)->where('startDate','>=',$startDate)->where('endDate','<=',$endDate)->sum('noOfDays')}}


            </td>
            <td style="text-align: center;vertical-align: middle;"width="15">{{$T_weekendcount}}</td>
            <td style="text-align: center;vertical-align: middle;"width="15">{{$T_govHoliday}}</td>
            <td style="text-align: center;vertical-align: middle;"width="25">
                {{$T_adjustment}}
            </td>
            <td style="text-align: center;vertical-align: middle;"width="15">

                {{$T_present}}

            </td>
            <td style="text-align: center;vertical-align: middle;"width="15">

                {{$T_dateCount-($T_present + $T_weekendcount + $allLeave->where('fkEmployeeId',$aE->id)->where('startDate','>=',$startDate)->where('endDate','<=',$endDate)->sum('noOfDays') + $T_govHoliday + $T_adjustment)}}


            </td>
            <td style="text-align: center;vertical-align: middle;"width="25">
                @if(( 8*($T_dateCount-( $T_weekendcount + $allLeave->where('fkEmployeeId',$aE->id)->where('startDate','>=',$startDate)->where('endDate','<=',$endDate)->sum('noOfDays') + $T_govHoliday + $T_adjustment))) > $T_roundworkinghour)

                    {{(( 8*($T_dateCount-( $T_weekendcount + $allLeave->where('fkEmployeeId',$aE->id)->where('startDate','>=',$startDate)->where('endDate','<=',$endDate)->sum('noOfDays') + $T_govHoliday + $T_adjustment))) - $T_roundworkinghour) }}

                @else

                    0


                @endif
                /
                @if(( 8*($T_dateCount-( $T_weekendcount + $allLeave->where('fkEmployeeId',$aE->id)->where('startDate','>=',$startDate)->where('endDate','<=',$endDate)->sum('noOfDays') + $T_govHoliday + $T_adjustment))) < $T_roundworkinghour)

                    {{($T_roundworkinghour-( 8*($T_dateCount-( $T_weekendcount + $allLeave->where('fkEmployeeId',$aE->id)->where('startDate','>=',$startDate)->where('endDate','<=',$endDate)->sum('noOfDays') + $T_govHoliday + $T_adjustment))))}}

                @else
                    0
                @endif
            </td>
            <td class="Border"style="text-align: center;vertical-align: middle;" width="25">Gross: {{$aE->salary}}</td>
            <td class="Border"style="text-align: center;vertical-align: middle;" width="25">
                @php
                    $basicSalary=round((((int)$aE->salary-2000)*.64103),2)
                @endphp
                Basic: {{$basicSalary}}
            </td>
            <td class="Border"style="text-align: center;vertical-align: middle;wrap-text:true;" height="100">


                @php
                    $houseRent=round(($basicSalary*.5),2)
                @endphp



                @php
                    $medical=round(($basicSalary*.06),2)
                @endphp
                @if($aE->pf_fund ==1)

                    @php
                        $CompanyProvident=round(($basicSalary*.04),2);
                        $PersonalProvident=round(($basicSalary*.06),2);

                    @endphp



                @else
                    @php
                        $CompanyProvident=0;
                        $PersonalProvident=0;
                    @endphp

                @endif
                convence: 2000<br>
                house rent :{{$houseRent}}<br>
                Company provident:{{$CompanyProvident}}<br>
                personal provident:{{$PersonalProvident}}<br>

                Medical:{{$medical}}
            </td>
            <td class="Border"style="text-align: center;vertical-align: middle;wrap-text:true;" height="100">
                @php
                    $dailySalary=round((((int)$aE->salary)/30),2)
                @endphp

                Daily:{{$dailySalary}}
            </td>
            <td class="Border"style="text-align: center;vertical-align: middle;wrap-text:true;" height="100">
                @php
                    $payAbleSalary=round(($dailySalary*($T_present+
                    ( $T_weekendcount + $allLeave->where('fkEmployeeId',$aE->id)->where('categoryCode','!=','LWP')->where('startDate','>=',$startDate)->where('endDate','<=',$endDate)->sum('noOfDays') + $T_govHoliday + $T_adjustment))))


                @endphp

                Total Payable Salary :<br>
                {{$payAbleSalary}}

            </td>


        </tr>

        @php
            $T_roundworkinghour=null;$T_weekendcount=0;$T_adjustment=0;$finalholiDay=0;$T_FINALWORKINGHOUR=null;$T_present=0;$T_govHoliday=0;$T_dateCount=0;
        @endphp
    @endforeach

    </tbody>
</table>

</body>
</html>