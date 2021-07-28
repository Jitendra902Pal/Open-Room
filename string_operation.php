$string = 'hshfdsdshiuhsdf  234234bd3#2dd4, ssdfhj';
$starting_two_charcter = substr($string, 0,2);
<!-- string replace more spaces -->
$stringbefore  = preg_replace('/\s{1,}/', ' ', $string);
$stringbefore  = trim($stringbefore, " ");
<!-- string after first comma -->
$new_string = strtoupper(mysqli_real_escape_string($conn,$_POST['string']));
$new_string=preg_replace('/^([^,]*).*$/', '$1', $new_string);
<!-- timer -->
 
$timer_date1 = $timer_query_fetch1['date'];
$timer_time1 = $timer_query_fetch1['time'];
$timer_gr_date1 =$timer_date1.' '.$timer_time1;
$diff1 = abs(strtotime($date_time_curr) - strtotime($timer_gr_date1));
$years1 = floor($diff1 / (365*60*60*24));
$months1 = floor(($diff1 - $years1 * 365*60*60*24) / (30*60*60*24));
$days1 = floor(($diff1 - $years1 * 365*60*60*24 - $months1*30*60*60*24)/ (60*60*24));
$hours1 = floor(($diff1 - $years1 * 365*60*60*24  - $months1*30*60*60*24 - $days1*60*60*24) / (60*60));
$minutes1 = floor(($diff1 - $years1 * 365*60*60*24  - $months1*30*60*60*24 - $days1*60*60*24 - $hours1*60*60)/ 60);
