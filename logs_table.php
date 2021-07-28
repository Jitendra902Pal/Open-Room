<?php include("../db.php");

     echo ' <div class="dt-responsive table-responsive">
                     <table id="simpletable" width="100%" class="table-sm table-striped table-bordered dt-responsive nowrap">
                                                          
				<thead>
				<tr bgcolor="#182f60" class="text-white">
                    <th  class="text-center">S/no</th>
                    <th  class="text-center">Previous </th>
                    <th  class="text-center">Updated</th>
                    <th  class="text-center">User</th>
                    <th  class="text-center">Date</th>
				</tr>
				</thead>
				<tbody>'
                    ?>
			<?php
                $invoice_get = $_POST['invoice_get'];
                $invoice_logs =  mysqli_query($conn,"SELECT * FROM `logs_pcs_invoice` WHERE id!='' and invoice_no='$invoice_get' order by id ASC" );
                $invoice_no_pre = '';
                $pre_loop = 0;
                $count = 1;
                
function array_diff_assoc_recursive($array1, $array2)
{
	foreach($array1 as $key => $value)
	{
		if(is_array($value))
		{
			if(!isset($array2[$key]))
			{
				$difference[$key] = $value;
			}
			elseif(!is_array($array2[$key]))
			{
				$difference[$key] = $value;
			}
			else
			{
				$new_diff = array_diff_assoc_recursive($value, $array2[$key]);
				if($new_diff != FALSE)
				{
					$difference[$key] = $new_diff;
				}
			}
		}
		elseif(!isset($array2[$key]) || $array2[$key] != $value)
		{
			$difference[$key] = $value;
		}
	}
	return !isset($difference) ? 0 : $difference;
};
			while($rowNew = mysqli_fetch_assoc($invoice_logs)){
                    $curOutArray = array();
                    $preOutArray = array();
                    $result1 = array();
                    $result2 = array();
                    $curOutArray = $rowNew;
                    $curOutArray_gr_data = json_decode($rowNew['logs_data']);
                    
                    unset($curOutArray['date']);
                    unset($curOutArray['created_time']);
                    unset($curOutArray['id']);
                    unset($curOutArray['created_by']);
			            	$id_get =mysqli_real_escape_string($conn, $rowNew['id']);
			            	$invoice_no =mysqli_real_escape_string($conn, $rowNew['invoice_no']);
                    $date =mysqli_real_escape_string($conn, $rowNew['date']);
                    $date = date("d-m-Y H:i:s", strtotime($date));
		            		$created_time =mysqli_real_escape_string($conn, $rowNew['created_time']);
                    $created_by =mysqli_real_escape_string($conn, $rowNew['created_by']);
                    $pre_loop++;
                    $invoice_logs_num =  mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `logs_table` WHERE  invoice_no='$invoice_no'" ));
                    $invoice_logs_num = $invoice_logs_num - 1;
                    if($invoice_no == $invoice_no_pre){
                         $condition = " AND id = '$id_get1' ";
                       
                        } else {
                         $condition = " and invoice_no='$invoice_no' order by id desc LIMIT $invoice_logs_num,1 ";
                    
                        }
                   
                    $invoice_logs_pre = mysqli_query($conn,"SELECT * from logs_table where id!='' $condition");
                    $rowpreNew = mysqli_fetch_assoc($invoice_logs_pre);
                    $id_get1 =mysqli_real_escape_string($conn, $rowpreNew['id']);
                    $id_get1 = $id_get;
                    $preOutArray = $rowpreNew;
                    $preOutArray_gr_data = json_decode($rowpreNew['logs_data']);
                    $arry_len =  sizeof($preOutArray);
                    if($arry_len == 0){
                         $preOutArray = $curOutArray;
                    } else {
                         unset($preOutArray['date']);
                         unset($preOutArray['created_time']);
                         unset($preOutArray['id']);
                         unset($preOutArray['created_by']);
                    }
                   
                    $result1 = array_diff($preOutArray, $curOutArray);
                    $result2 = array_diff($curOutArray, $preOutArray);
                    $result1_len =  sizeof($result1);
                    $result2_len =  sizeof($result2);
                    if($result1_len == 0 && $result2_len == 0){
                       echo  $hide_cond = "";
                    } else {
                         $hide_cond = "hii";
                    }
                   
                    $invoice_no_pre = $invoice_no;
                  if($hide_cond =='hii'){

                     foreach($result1 as $key=>$row) {
                         if (array_key_exists($key, $result2)) {
                          } else {
                              $result2[$key] = 'Empty';
                          }
                    }
                    foreach($result2 as $key2=>$row2) {
                         if (array_key_exists($key2, $result1)) {
                          } else {
                              $result1[$key2] = 'Empty';
                          }
                    }
                    $result22 = array() ;
                    foreach (array_keys($result1) as $key) {
                         $result22[$key] = $result2[$key] ;
                    }
			?>
				<tr>   
                         <td  class="text-center"><?php echo $count++; ?></td>
                         <td  class="text-center"><?php 
                         echo "<table class=' table-sm ' width='100%' border='0'> ";
                         foreach($result1 as $key=>$row) {
                              $key =  strtoupper($key);
                              $key = str_replace("_", " ", $key);
                             echo "<tr>";
                                 echo "<th>" .$key. ":</th>";
                             echo "</tr>";
                              echo "<tr>";
                              
                              if($key == 'LOGS DATA'){
                                   $b1 = json_decode($row, true) ;
                                   $b2 = json_decode($result22['logs_data'], true);
                                   $a1 = array_column($b1, null, 'ID');
                                   $a2 = array_column($b2, null, 'ID');
                                   $new_array1 = array_diff_assoc_recursive($a1, $a2);
                                   $new_array2 = array_diff_assoc_recursive($a2, $a1);
                                   echo "<td>";
                                   if(is_array($new_array1) == 1){
                                        echo "<table class='table table-sm' width='100%'><tr><th>Fields</th><th>Data</th></tr>";
                                   foreach ($new_array1 as $mks){
                                       foreach ($mks as $qid=>$rate){
                                          echo "<tr><td>".$qid."</td><td>".$rate."</td></tr>";
                                        }
                                    }
                                    echo "</table>";
                                   }
                                   echo "</td>";
                               } else {
                                   echo "<td>". $row . "</td>";
                               }
                                



                             echo "</tr>";
                           
                         }
                         echo "</table>";
                         ?></td>
                         <td  class="text-center"><?php 
                         echo "<table class=' table-sm ' width='100%' border='0'> ";
                         foreach($result22 as $key2=>$row2) {
                              $key2 =  strtoupper($key2);
                              $key2 = str_replace("_", " ", $key2);
                             
                             echo "<tr>";
                                 echo "<th>" . $key2 . ":</br></th>";
                                
                             echo "</tr>";
                              echo "<tr>";
                              if($key2 == 'LOGS DATA'){
                                   $b3 = json_decode($row2, true) ;
                                   $b4 = json_decode($result1['logs_data'], true);
                                   $a3 = array_column($b3, null, 'ID');
                                   $a4 = array_column($b4, null, 'ID');
                                   $new_array3 = array_diff_assoc_recursive($a3, $a4);
                                   
                                   echo "<td>";
                                   if(is_array($new_array3) == 1){
                                   echo "<table class='table table-sm' width='100%'><tr><th>Fields</th><th>Data</th></tr>";
                                   foreach ($new_array3 as $mks){
                                       
                                       foreach ($mks as $qid=>$rate){
                                        echo "<tr><td>".$qid."</td><td>".$rate."</td></tr>";
                                        }
                                    }
                                  echo "</table>";
                                   }
                                  echo  "</td>";
                                  
                               } else {
                                   echo "<td>". $row2 . "</td>";
                               }


                             echo "</tr>";
                             
                             
                         }
                         echo "</table>";
                         
                         ?>
                         </td>
                         <td  class="text-center"><?php echo $created_by ; ?></td>
                         <td  class="text-center"><?php echo $date; ?></td>
				</tr>
			<?php } else { }
               }
               
				echo '</tbody>
			</table>
               </div>'
               ?>
