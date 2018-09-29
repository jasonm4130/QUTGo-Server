<?php
function getLastWeekDates()
{
    $lastWeek = array();

    $prevMon = abs(strtotime("previous monday"));
    $currentDate = abs(strtotime("today"));
    $seconds = 86400; //86400 seconds in a day

    $dayDiff = ceil( ($currentDate-$prevMon)/$seconds ); 

    if( $dayDiff < 7 )
    {
        $dayDiff += 1; //if it's monday the difference will be 0, thus add 1 to it
        $prevMon = strtotime( "previous monday", strtotime("-$dayDiff day") );
    }

    $prevMon = date("Y-m-d",$prevMon);

    // create the dates from Monday to Sunday
    for($i=0; $i<7; $i++)
    {
        $d = date("Y-m-d", strtotime( $prevMon." + $i day") );
        $lastWeek[]=$d;
    }

    return $lastWeek;
}

function getThisWeekDates()
{
    $thisWeek = array();

    // create the dates from Monday to Sunday
    for($i=0; $i<7; $i++)
    {
        $thisMon = date("Y-m-d", strtotime("previous monday"));
        $d = date("Y-m-d", strtotime( $thisMon . " + $i day" ) );
        $thisWeek[]=$d;
    }

    return $thisWeek;
}
