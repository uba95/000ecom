<?php

function free($work_time, $schedule) {

    $free_time = [];

    if ($work_time[0] < $schedule[0][0]) {

        array_push($free_time, array($work_time[0], $schedule[0][0]));

    }

    for ($i = 0; $i < count($schedule); $i++) {

        if (isset($schedule[$i+1])) {

            if ($schedule[$i][1] < $schedule[$i+1][0]) {

                array_push($free_time, array($schedule[$i][1],  $schedule[$i+1][0]));

            }
        }

    }

    if ($work_time[1] > $schedule[count($schedule)-1][1]) {

        array_push($free_time, array($schedule[count($schedule)-1][1], $work_time[1]));
    
    }
    echo '<pre>';
        print_r($free_time);
    echo '</pre>';
    return $free_time;

}

$work_time1 = [10, 16];
$schedule1 = [[10, 11], [12.5, 13], [13, 15]];
$free1 = free($work_time1, $schedule1);

$work_time2 = [8, 14];
$schedule2 = [[8, 9], [11.5, 12], [13, 14]];
$free2 = free($work_time2, $schedule2);

$meeting = [];

for ($i = 0; $i < count($free1); $i++) {

    for ($ii = 0; $ii < count($free2); $ii++) {

        if ($free1[$i][1] > $free2[$ii][0] && $free1[$i][0] < $free2[$ii][1]) {

            $meeting_start = $free1[$i][0] <= $free2[$ii][0] ? $free2[$ii][0] : $free1[$i][0];

            $meeting_end = $free1[$i][1] <= $free2[$ii][1] ? $free1[$i][1] : $free2[$ii][1];

            array_push($meeting, array($meeting_start, $meeting_end));
        }
    }

}
echo '<pre>';
print_r($meeting);
echo '</pre>';

####################################################################################################

function re($str ,$num) {
    for ($i = 1; $i <= $num; $i++) {
        echo "$str\n";
    }
}

re('hi',2);
####################################################################################################

