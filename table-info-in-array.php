<table class="table-bordered" style="margin-top:0px; margin-bottom:0px;">



      <tr>

         <th rowspan="2" align="center"><b>Sr. No</b></th>

         <th rowspan="2" align="center">Activity <br></br> (Road Transportation Charges)</th>

         <th colspan="3" align="center">Trailers</th>

         <th rowspan="2" align="center">Rate (20)</th>

         <th rowspan="2" align="center">Rate (2*20)</th>

         <th rowspan="2" align="center">Rate (40)</th>

         <th align="center">Amount (INR)</th>

      </tr>

      <tr>

         <th align="center" style="height:20px;">20</th>

         <th align="center">2*20</th>

         <th align="center">40</th>

         <th></th>

      </tr>



   <tbody>

  ';

   $invoice_query_date = mysqli_query($conn, "SELECT * from billing where invoice_no ='$invoice_get'");

  $invoice_amount = 0;

  $Count_no =0;

  $complete_arr = [];

  $complete_arrn = [];

  while ($invoice_row_main = mysqli_fetch_assoc($invoice_query_date))

  {

  $invoice_gr_no= mysqli_real_escape_string($conn,$invoice_row_main['gr_no']);

  $to_station= mysqli_real_escape_string($conn,$invoice_row_main['to_station']);

  $invoice_destination = mysqli_query($conn, "SELECT from_station,to_station from main_table where gr_no ='$invoice_gr_no'");

   $fetch_dest_row = mysqli_fetch_assoc($invoice_destination);

   $from_station = $fetch_dest_row['from_station'];

  

  $invoice_cont_size= mysqli_real_escape_string($conn,$invoice_row_main['container_size']);

  $rate_amount1= mysqli_real_escape_string($conn,$invoice_row_main['rate']);

  $rate_amount2= mysqli_real_escape_string($conn,$invoice_row_main['rate_2']);

  $rate_amount = $rate_amount1 + $rate_amount2;

  $to_station = $from_station.'-'.$to_station;

  $Count_no++;

  $complete_arr[] = ['Destination'=>$to_station,'Size'=>$invoice_cont_size, 'Rate'=>$rate_amount,'Count_no' =>$Count_no];

  $complete_arrn[] = ['Destination'=>$to_station,'Size_Count'=>$invoice_cont_size, 'Rate'=>$rate_amount];

  $invoice_amount += $rate_amount;

  }

foreach ($complete_arr as $row) {

  if (! isset($out[$row['Destination']])) {

    $out[$row['Destination']] = array('Destination' => $row['Destination']); 

  }

  if (! isset($out[$row['Destination']][$row['Size']])) {

    $out[$row['Destination']][$row['Size']] = 0;  

   

  } 

    $out[$row['Destination']][$row['Size']] += $row['Rate'];



}

foreach ($complete_arrn as $row_next) {

  if (! isset($out_next[$row_next['Destination']])) {

    $out_next[$row_next['Destination']] = array('Destination' => $row_next['Destination']);

  }

  if (! isset($out_next[$row_next['Destination']][$row_next['Size_Count']])) {

    $out_next[$row_next['Destination']][$row_next['Size_Count']] = 0;  

  } 

    $out_next[$row_next['Destination']][$row_next['Size_Count']] += 1;

} 

$count_loop  = 1;
$count_loop_new  = 1;

foreach($out as $val) {

if ( ! isset($val["20"])) {

   $val["20"] = 0;

 }

 if ( ! isset($val["40"])) {

  $val["40"] = 0;

}

if ( ! isset($val["20-20"])) {

  $val["20-20"] = 0;

}

$cal_val = $val["20"] + $val["40"] +$val["20-20"];

 $valdestination = $val["Destination"];

 if ( ! isset($out_next[$valdestination]["20"])) {

  $out_next[$valdestination]["20"] = 0;

}

if ( ! isset($out_next[$valdestination]["40"])) {

  $out_next[$valdestination]["40"] = 0;

}

if ( ! isset($out_next[$valdestination]["20-20"])) {

  $out_next[$valdestination]["20-20"] = 0;

}
$to_station_st = substr($val["Destination"], strpos($val["Destination"], "-") + 1);
$arr_to = explode("-", $val["Destination"], 2);
$from_station_st = $arr_to[0];

$slt20 =mysqli_query($conn,"SELECT * from billing where invoice_no ='$invoice_get' and container_size='20' and to_station='$to_station_st' and from_station='$from_station_st' ");
$rate20 = array();
$ratsde20 = array();
while ($slt20row = mysqli_fetch_assoc($slt20))
  {
    $rate20[] = $slt20row['rate'];
  }
  $rate20 = array_count_values($rate20);
  $key_string20 = '';
  $row_string20 = '';

  foreach($rate20 as $key=>$row) {
   
    $key_string20 .=  $key."<br>";
    $row_string20 .=  $row."<br>";
   
 }

 $slt40 =mysqli_query($conn,"SELECT * from billing where invoice_no ='$invoice_get' and container_size='40' and to_station='$to_station_st' and from_station='$from_station_st' ");
$rate40 = array();
$ratsde40 = array();
while ($slt40row = mysqli_fetch_assoc($slt40))
  {
    $rate40[] = $slt40row['rate'];
  }
  $rate40 = array_count_values($rate40);
  $key_string40 = '';
  $row_string40 = '';

  foreach($rate40 as $key=>$row) {
   
    $key_string40 .=  $key."<br>";
    $row_string40 .=  $row."<br>";
 }

$slt220 =mysqli_query($conn,"SELECT * from billing where invoice_no ='$invoice_get' and container_size='20-20' and to_station='$to_station_st' and from_station='$from_station_st' ");
$rate220 = array();
$ratsde220 = array();
while ($slt220row = mysqli_fetch_assoc($slt220))
  {
    $rate220[] = $slt220row['rate'];
  }
  $rate220 = array_count_values($rate220);
  $key_string220 = '';
  $row_string220 = '';

  foreach($rate220 as $key=>$row) {
    $key_string220 .=  $key."<br>";
    $row_string220 .=  $row."<br>";
 }

 if($key_string20 == ''){
   $key_string20 = 0;
 }
 if($key_string40 == ''){
  $key_string40 = 0;
}
if($key_string220 == ''){
  $key_string220 = 0;
}
if($row_string20 == ''){
  $row_string20 = 0; 
}
if($row_string40 == ''){
  $row_string40 = 0;
}
if($row_string220 == ''){
  $row_string220 = 0;
}
$lcount40 = count($rate40);
$lcount20 = count($rate20);
$lcount220 = count($rate220);
$max_loop = max($lcount20, $lcount40, $lcount220);

$output .= '

<tr>

   <td class="border-bottom-0 border-top-0" align="center" valign="top"><span>'.$count_loop++.'</span></td>
   <td class="border-bottom-0 border-top-0"  style="font-size: 10px;  white-space:nowrap"  valign="top">Destination:'.$val["Destination"].'</td>

  ';

   $output .= '

   ';

   $output .= '<td class="border-bottom-0 border-top-0" align="center" valign="top">'.$row_string20.'</td>

   ';
   $output .= '<td class="border-bottom-0 border-top-0" align="center" valign="top">'. $row_string220.'</td>
   ';
   $output .= '<td class="border-bottom-0 border-top-0" align="center" valign="top">'. $row_string40.'</td>
   ';

  if($out_next[$valdestination]['20'] == 0){

    $out_next[$valdestination]['20'] = 1;

  }

  if($out_next[$valdestination]['20-20'] == 0){

    $out_next[$valdestination]['20-20'] = 1;

  }if($out_next[$valdestination]['40'] == 0){

    $out_next[$valdestination]['40'] = 1; 

   }

   $output .= '<td class="border-bottom-0 border-top-0" align="center" valign="top">'.$key_string20.'</td>
   ';
   $output .= '<td class="border-bottom-0 border-top-0" align="center" valign="top">'.$key_string220.'</td>
   ';
   $output .= '<td class="border-bottom-0 border-top-0" align="center" valign="top">'.$key_string40.'</td>
   ';

   $output .= '<td class="border-bottom-0 border-top-0" align="right" valign="top">'.$cal_val.'</td>

</tr>





';
for ($j = 1; $j <= $max_loop; $j++) {
 $count_loop_new++;
}
}
