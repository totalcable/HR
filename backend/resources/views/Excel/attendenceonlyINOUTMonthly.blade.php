
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

        @php

            $s=\Carbon\Carbon::parse($startDate);
            $e=\Carbon\Carbon::parse($endDate);
            $count1=1;
            $newS=0;
            $newE=0;



        @endphp

        @foreach($dates as $date)

            @if($count1==1)
                @php
                    $newS=$date['date'];
                    $newE=$date['date'];
                @endphp

            @endif

            @if($count1 ==7)

                @php

                    $count1=1;
                    $newE=$date['date'];

                @endphp

                <th class="Border" colspan="7" style="text-align: center;vertical-align: middle;">{{$newS}} To {{$newE}}</th>
            @else
                @if($date['date']==\Carbon\Carbon::parse($endDate)->format('Y-m-d'))

                        @php

                            $newS=\Carbon\Carbon::parse($newE)->addDay()->format('Y-m-d');
                            $newE=$date['date'];

                        @endphp

                    <th class="Border" colspan="7" style="text-align: center;vertical-align: middle;">{{$newS}} To {{$newE}}</th>

                @endif



            @endif

                @php

                    $count1++;

                @endphp






        @endforeach





    </tr>
    <tr>

    </tr>
    </thead>
    <tbody>
        @foreach($allEmp as $aE)
                <tr>

                    <td class="cell" width="10">{{$aE->attDeviceUserId}}</td>
                    <td class="cell" width="25">{{$aE->empFullname}}</td>
                    <td class="cell" width="25">{{$aE->departmentName}}</td>

                </tr>
            @endforeach





    </tbody>
</table>

</body>
</html>