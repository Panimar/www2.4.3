<?php
$time = microtime();
$time = explode(" ", $time);
$time = $time[1] + $time[0];
$finish = $time;
$totaltime = ($finish - $start);
print "



<center><small>Page generated in ".round($totaltime,3)." seconds.</small></center>";
?>