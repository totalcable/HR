
<html>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="{{url('public/css/exceltable.css')}}" rel="stylesheet">


<body>

<table class="blueTable">
    <thead>
    <tr>
        <td style="vertical-align: middle;text-align: center;"></td>
        <th colspan="7" style="vertical-align: middle;text-align: center;" >Roster Wise Report 2 -( {{\Carbon\Carbon::parse($startDate)->format('Y-m-d')}} - {{\Carbon\Carbon::parse($endDate)->format('Y-m-d')}} ) || {{$RosterInfo->shiftName}}->({{\Carbon\Carbon::parse($RosterInfo->inTime)->format('H:m A')}}-{{\Carbon\Carbon::parse($RosterInfo->outTime)->format('H:m A')}})</th>
    </tr>
    <tr>
        <td style="vertical-align: middle;text-align: center;"></td>
        <th style="text-align: center;vertical-align: middle;" colspan="4">Name: {{$allE->empFullname}}</th>
        <th style="text-align: center;vertical-align: middle;" colspan="3">ID: {{$allE->attDeviceUserId}}</th>
    </tr>
    <tr>
        <td style="vertical-align: middle;text-align: center;"></td>
        <th style="text-align: center;vertical-align: middle;" colspan="4">Department: {{$allE->departmentName}}</th>
        <th style="text-align: center;vertical-align: middle;" colspan="3">Designation: {{$allE->designationTitle}}</th>
    </tr>

    <tr>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="25">Date</th>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="15">IN Time</th>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="15">OUT Time</th>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="15">WorkTime</th>


    </tr>


    </thead>
    <tbody>
    @php

        $T_FINALWORKINGHOUR=0;

    @endphp
    @foreach($dates as $date)
        <tr>

            <td class="Border"style="text-align: center;vertical-align: middle;" width="25">
                {{$date['date']}}({{$date['day']}})
            </td>
            <td class="Border"style="text-align: center;vertical-align: middle;" width="15">

                @foreach($results->where('attendanceDate',$date['date'])->where('employeeId',$allE->id)->where('fkAttDevice',$allE->inDeviceNo) as $O)
                    @php
                        $FINALIN=\Carbon\Carbon::parse($O->accessTime2);
                    @endphp

                    {{$FINALIN->format('H:i')}}
                    <br>

                @endforeach

            </td>
            <td class="Border"style="text-align: center;vertical-align: middle;" width="15">

                @foreach($results->where('attendanceDate',$date['date'])->where('employeeId',$allE->id)->where('fkAttDevice',$allE->outDeviceNo) as $O)
                    @php
                        $FINALIN=\Carbon\Carbon::parse($O->accessTime2);
                    @endphp

                    {{$FINALIN->format('H:i')}}
                    <br>

                @endforeach

            </td>
            <td class="Border"style="text-align: center;vertical-align: middle;" width="15">

                @php
                    $tIn=count($results->where('attendanceDate',$date['date'])->where('employeeId',$allE->id)->where('fkAttDevice',$allE->inDeviceNo));
                    $tOut=count($results->where('attendanceDate',$date['date'])->where('employeeId',$allE->id)->where('fkAttDevice',$allE->outDeviceNo));
                    $in=false;
                    $out=false;
                    $FINALIN=null;
                    $FINALOUT=null;
                    $FINALWORKINGHOUR=0;

                @endphp

                @for($i=0;$i<=$tIn;$i++)



                    @if($results->where('attendanceDate',$date['date'])->where('employeeId',$allE->id)->where('fkAttDevice',$allE->inDeviceNo)->get($i))

                        @php
                            $out=true;
                            $FINALIN=\Carbon\Carbon::parse($results->where('attendanceDate',$date['date'])->where('employeeId',$allE->id)->where('fkAttDevice',$allE->inDeviceNo)->get($i)->accessTime2);
                        @endphp

                        @if($out)

                            @if($results->where('attendanceDate',$date['date'])->where('employeeId',$allE->id)->where('fkAttDevice',$allE->outDeviceNo)->get($i))

                                @php
                                    $out=false;
                                    $FINALOUT=\Carbon\Carbon::parse($results->where('attendanceDate',$date['date'])->where('employeeId',$allE->id)->where('fkAttDevice',$allE->outDeviceNo)->get($i)->accessTime2);
                                @endphp

                            @endif

                            @if($FINALIN != null && $FINALOUT != null)

                                @php
                                    $FINALWORKINGHOUR=$FINALOUT->diff($FINALIN);
                                    $T_FINALWORKINGHOUR=($FINALWORKINGHOUR+$T_FINALWORKINGHOUR);
                                @endphp



                            @endif

                        @endif

                    @endif



                    @php
                        $FINALWORKINGHOUR=0;
                    @endphp


                @endfor

                {{$T_FINALWORKINGHOUR}}







            </td>

        </tr>
    @endforeach





    </tbody>
</table>
</body>
</html>