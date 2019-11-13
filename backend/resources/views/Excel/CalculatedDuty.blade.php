
<html>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="{{url('public/css/exceltable.css')}}" rel="stylesheet">


<body>

<table class="blueTable">
    <thead>
    <tr>

        <th colspan="8" style="text-align: center;">Duty Calculation - {{\Carbon\Carbon::parse($startDate)->format('Y-m-d')}}</th>

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

        <th class="Border"style="text-align: center;vertical-align: middle;" width="15">Working Hour</th>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="35">Round Working Hour</th>



    </tr>
    <tr>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="25"></th>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>

        <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="35"></th>



    </tr>


    </thead>
    <tbody>
    @php
    $totalIn=count($empINData);
    $totalOut=count($empout);

    $totalLoop=0;
    if ($totalIn>$totalOut){

        $totalLoop=$totalOut;
        $othersData=($totalIn-$totalOut);
        $loop='in';

    }if ($totalIn==$totalOut){

        $totalLoop=$totalOut;
        $loop='';


    }elseif ($totalIn<$totalOut){

        $totalLoop=$totalIn;
        $othersData=($totalOut-$totalIn);
        $loop='out';
    }
    $workStart=null;
    $workEnd=null;
    $FINALWORKINGHOUR=null;
    $ROUNDFINALWORKINGHOUR=null;
    @endphp
    @for($i=0;$i<$totalLoop;$i++)
        <tr>
            <td class="Border"style="text-align: center;vertical-align: middle;" width="25"></td>
            <td class="Border"style="text-align: center;vertical-align: middle;" width="15">
                @php
                    $workStart=\Carbon\Carbon::parse($empINData[$i]->accessTime);
                @endphp
                {{$workStart->format('H:i')}}

            </td>
            <td class="Border"style="text-align: center;vertical-align: middle;" width="15">
                @php
                    $workEnd=\Carbon\Carbon::parse($empout[$i]->accessTime);
                @endphp
                {{$workEnd->format('H:i')}}
            </td>

            <td class="Border"style="text-align: center;vertical-align: middle;" width="15">

                @if($workStart!=null && $workEnd != null)
                    @php
                        $FINALWORKINGHOUR=$workEnd->diff($workStart);

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

                        @endphp

                    @else

                        @php
                            $ROUNDFINALWORKINGHOUR->minute(0);


                        @endphp

                    @endif

                    {{$ROUNDFINALWORKINGHOUR->format('H:i')}}

                @endif

            </td>

            @php
                $workStart=null;
                $workEnd=null;
                $FINALWORKINGHOUR=null;
                $ROUNDFINALWORKINGHOUR=null;

            @endphp



        </tr>
    @endfor
    <tr>

        <td class="Border"style="text-align: center;vertical-align: middle;" width="25"></td>
        <td class="Border"style="text-align: center;vertical-align: middle;" width="15">

            @if($loop=='in')


                @for($ii=$othersData+1;$ii<$totalIn;$ii++)



                {{\Carbon\Carbon::parse($empINData[$i]->accessTime)->format('H:i')}}



                @endfor

            @endif



        </td>
        <td class="Border"style="text-align: center;vertical-align: middle;" width="15">

            @if($loop=='out')


                @for($ii=$othersData+1;$ii<$totalOut;$ii++)



                {{\Carbon\Carbon::parse($empout[$i]->accessTime)->format('H:i')}}



                @endfor

            @endif



        </td><td class="Border"style="text-align: center;vertical-align: middle;" width="15">





        </td><td class="Border"style="text-align: center;vertical-align: middle;" width="15">





        </td>

    </tr>

    </tbody>
</table>

</body>
</html>