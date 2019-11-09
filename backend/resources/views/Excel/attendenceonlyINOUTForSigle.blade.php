
<html>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="{{url('public/css/exceltable.css')}}" rel="stylesheet">


<body>

<table class="blueTable">
    <thead>
    <tr>
        <td></td>


    </tr>

    <tr>


        @foreach($allEmp as $aE)
            <th class="Border" colspan="4" style="text-align: center;vertical-align: middle;">{{$aE->empFullname}}({{$aE->attDeviceUserId}})</th>
        @endforeach

    </tr>
    </thead>
    <tbody>
    <tr>

        @foreach($allEmp as $aE)
            <th class="Border" width="15"style="text-align: center;vertical-align: middle;">In Time</th>
            <th class="Border" width="15"style="text-align: center;vertical-align: middle;">Out Time</th>
            <th class="Border" width="15"style="text-align: center;vertical-align: middle;">All punch</th>
            <th class="Border" width="15"style="text-align: center;vertical-align: middle;">Total Hour</th>
        @endforeach


    </tr>

        <tr>
            @foreach($allEmp as $aE)
                    <td>


                        @foreach($results->where('attendanceDate',$ad['date'])->where('employeeId',$aE->id)->where('fkAttDevice',$aE->inDeviceNo) as $O)
                            @php
                                $FINALIN=\Carbon\Carbon::parse($O->accessTime2);
                            @endphp

                                {{$FINALIN->format('H:i')}}
                            <br>

                        @endforeach


                    </td>
                <td>


                        @foreach($results->where('attendanceDate',$ad['date'])->where('employeeId',$aE->id)->where('fkAttDevice',$aE->outDeviceNo) as $O)
                            @php
                                $FINALIN=\Carbon\Carbon::parse($O->accessTime2);
                            @endphp

                                {{$FINALIN->format('H:i')}}
                            <br>

                        @endforeach


                 </td>


                <td>


                    @foreach($results->where('attendanceDate',$ad['date'])->where('employeeId',$aE->id) as $O)
                        @php
                            $FINALIN=\Carbon\Carbon::parse($O->accessTime2);
                        @endphp

                        {{$FINALIN->format('H:i')}}-{{$O->fkAttDevice}}
                        <br>

                    @endforeach


                </td>
                <td>

                    @if($results->where('employeeId',$aE->id)->where('attendanceDate',$ad['date'])->first())

                        @php

                            $FINALIN=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$ad['date'])
                            ->first()->accessTime2);
                            $FINALOUT=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$ad['date'])
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

                    @endif



                 </td>
                @endforeach
        </tr>

    </tbody>
</table>

</body>
</html>