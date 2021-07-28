<?php 
ob_start();
session_start();
include"db.php";
date_default_timezone_set("Asia/Kolkata");
$date_time_curr = date('Y-m-d').' '.date("H:i:s");

if(isset($_GET['invoice_search'])){
     $invoice_search = $_GET['invoice_search'];
     if(!empty($invoice_search)){  $invoice_search_condition = "and invoice_no ='$invoice_search'"; } else{ $invoice_search_condition = ""; }
} else {
     $invoice_search = '';
     $invoice_search_condition = '';
}
 ?>
  
<script>
	$(document).ready(function(){  
		$('.search').click(function(e){  
               var company_name = $('#company_name').val();
			var from_date = $('#post_at').val();  
			var to_date = $('#post_at_to_date').val(); 
              if(from_date!= '' && to_date!= ''){  
				<?php  
                    if(isset($_GET['post_at'])){
                         $post_at = $_GET['post_at'];
                         $post_at_todate = $_GET['post_at_todate'];
                         $company_name =$_GET['company_name'];
                         if($company_name!=''){ $company_condition = "AND company_name='$company_name' "; }else{ $company_condition = ""; }
                         $consignee_name = $_GET['consignee_name'];
                         if($consignee_name!=''){ $consignee_condition = "AND acc_name ='$consignee_name' "; }else{ $consignee_condition = ""; }
                          
                    } else{ 
                      $post_at= date('01-m-Y');
                      $post_at_todate=  date('d-m-Y');
                      $consignee_name = ''; 
                      $consignee_condition = ''; 
                      $company_name ='';
                      $company_condition ='';
                     
                     }
				            

    				if(!empty($_POST["from_date"])) {			
    				  $post_at = $_POST["from_date"];

    					if(!empty($_POST["to_date"])) {
    						$post_at_todate = $_POST["to_date"];
    					}
                         if(!empty($_POST["company_name"])) {
						$company_name = $_POST["company_name"];
                              $company_condition = "AND company_name='$company_name' ";
					}else{
                              $company_condition = "";
                              $company_name = '';
                         }
                         if(!empty($_POST["consignee_name"])) {
						$consignee_name = $_POST["consignee_name"];
                              $consignee_condition = "AND acc_name ='$consignee_name' ";
					}else{
                              $consignee_condition = "";
                              $consignee_name = '';
                         }
              

				} ?>
			} else {
				
				alert("Please Select date option "); 
                                return false;
			}
		});
	});  
</script>

<?php 

if(isset($_POST['search'])){
	$search = $_POST['search'];
	header("Location: custom_listing.php?page=1&getkey=$search");
}
if(!empty($_GET['getkey'])){
	$search_key = $_GET['getkey'];
          $search_key = $_GET['getkey'];
          if($search_key =="GR INVOICE"){
               $search_key = 1;
               $search_key_query = "AND (invoice_type LIKE'%$search_key%')";
          } elseif($search_key =="M.Transport" || $search_key =="Transport"){
               $search_key = 2;
               $search_key_query = "AND (invoice_type LIKE'%$search_key%')";
          } elseif($search_key =="Handling"){
               $search_key = 3;
               $search_key_query = "AND (invoice_type LIKE'%$search_key%')";
           } else {
          if (DateTime::createFromFormat('d-m-Y H:i:s', $search_key) !== false) {
               $search_key1 = Date('Y-m-d', strtotime($search_key));
               $search_key2 = Date('H:i:s', strtotime($search_key));
               $search_key_query = "AND created_date LIKE'%$search_key1%' AND  created_time LIKE'%$search_key2%'";
             } elseif(DateTime::createFromFormat('d-m-Y', $search_key) !== false){
               $search_key1 = Date('Y-m-d', strtotime($search_key));
               $search_key_query = "AND created_date LIKE'%$search_key1%'";
             } else {
          $search_key_query = "AND (invoice_no LIKE'%$search_key%' OR acc_name LIKE'%$search_key%' OR company_name LIKE'%$search_key%' OR total_amount LIKE'%$search_key%'  OR created_date LIKE'%$search_key%')";
             }
          }
           
	}
	else{
	$search_key_query = "";
     $search_key = '';
}
if(isset($_GET['column'])){
     $columns_name = $_GET['column'];
     $order_name = $_GET['order'];
} else {
     $columns_name = '';
     $order_name = '';
}
$columns = array('id', 'invoice_type', 'invoice_no','acc_name','company_name','gst','toll_amount','add_charges','tax','total_amount','remarks','created_date','created_by');
$column = isset($_GET['column']) && in_array($_GET['column'], $columns) ? $_GET['column'] :   $columns[0];
$sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'ASC' : 'DESC';

$perpage = isset($_POST["limit-records"]) ? $_POST["limit-records"] : 10;
$per_page = $perpage;
if(isset($_GET['page']) & !empty($_GET['page'])){
$curpage = $_GET['page'];
}else{
$curpage = 1;
}
$start = ($curpage * $perpage) - $perpage;
$post_at = Date('Y-m-d', strtotime($post_at));
$post_at_todate = Date('Y-m-d', strtotime($post_at_todate));
if($invoice_search !=''){
     $date_condition = '';
} else {
    $date_condition ="AND (created_date BETWEEN '$post_at' AND '$post_at_todate')";
}

$PageSql = "SELECT * from `invoice` where id!=''  and invoice_status='2' $search_key_query $consignee_condition $company_condition  $date_condition $invoice_search_condition ";
$pageres = mysqli_query($conn, $PageSql);
$totalres = mysqli_num_rows($pageres);
$loop_total_open_count = ($curpage*$per_page) - ($per_page-1);
if($totalres <= $loop_total_open_count){
$curpage = 1;
$start = ($curpage * $perpage) - $perpage;
}
$endpage = ceil($totalres/$perpage);
$startpage = 1;
$nextpage = $curpage + 1;
$previouspage = $curpage - 1;
$ReadSql = "SELECT * from invoice where id!='' and invoice_status='2' $search_key_query $consignee_condition $company_condition  $date_condition $invoice_search_condition   ORDER BY $column $sort_order  LIMIT $start,    $perpage";
$invoice_result = mysqli_query($conn, $ReadSql);

$up_or_down = str_replace(array('ASC','DESC'), array('up','down'), $sort_order); 
$asc_or_desc = $sort_order == 'ASC' ? 'asc' : 'desc';
$add_class = ' class="highlight"';
if($search_key =="1"){
     $search_key = 'GR INVOICE';
} elseif($search_key =="2"){
     $search_key = 'Transport';
} elseif($search_key =="3"){
     $search_key = 'Handling';
 } else {
     $search_key = $search_key;
 }

 $post_at = Date('d-m-Y', strtotime($post_at));
 $post_at_todate = Date('d-m-Y', strtotime($post_at_todate));
?>
  <?php if($userrole == 'admin'){ ?>
 <div class="content-page">
 
  <?php } ?>

  <?php if($userrole == 'user' || $userrole == 'sub_admin'){?>
 <div class="content-page ms-0" style="left:0px margin-left:0px !important;">
  <?php } ?>
                <div class="content">
                    <!-- Start Content-->
                    <div class="container-fluid">                        
                        <!-- start page title -->  
                        <!-- end page title --> 
        <!-- end page title --> 
        <div class="card my-5">
          <div class="card-header">                    
            <h5 class="float-start mt-1 mb-0">Deleted Invoices</h5>
        
           
          </div>
          <div class="card-block p-3">
          <div class="col-12 bg-light pt-1 pb-0 mb-2 border">
               <form method="POST" class="row px-3" name="search" action="" id="sewarch"  autocomplete="off" >
               <div class="col-12 text-center  ">
          
         </div>
          <div class="col-6 col-md-4 col-lg-3 col-xxl-2 mb-2">
             <label for="" class="form-label">From Date</label>
               <input type="text" placeholder="From Date" id="post_at" name="from_date"  value="<?php echo @$post_at; ?>" class=" form-control form-control-sm" data-date-autoclose="true"/>
          </div>
          <div class="col-6 col-md-4 col-lg-3 col-xxl-2 mb-2">
             <label for="" class="form-label">To Date</label>
               <input type="text"  placeholder="To Date" id="post_at_to_date" name="to_date" value="<?php echo @$post_at_todate; ?>" class="  form-control form-control-sm"  data-date-autoclose="true"/>
          </div>
         
          <div class="col-3 col-md-2 col-lg-1 col-xxl-1 mb-2" style ="display:none" >
                <label for="" class="form-label">Comapny</label>
               <select type="text"  id="company_name" name="company_name"  class="form-control form-control-sm">
               <option value="">Select Company</option>
               <option value="pcs" <?php if($company_name=='pcs'){ echo "selected='selected'";} ?>>PCS</option>
               <option value="ddkl" <?php if($company_name=='ddkl'){ echo "selected='selected'";} ?>>DDKL</option>
               <option value="ddkt" <?php if($company_name=='ddkt'){ echo "selected='selected'";} ?>>DDKT</option>
               </select>
          </div>  
          <div class="col-sm-6 col-md-4 col-lg-3 mb-2">
               <label for="">Account Name</label>
               <input class="form-control form-control-sm" list="list_consignee_name" name="consignee_name" id="consignee_name" value="<?php echo $consignee_name; ?>"  placeholder="Enter Consignee Name">
                <datalist id="list_consignee_name">
               <option value="" selected>Select Consignee Name</option>
               <?php
               $select_consignee="SELECT `id`, `consignee_name` FROM `consignee_master`  where id !='' ORDER BY consignee_name ASC";
               $select_consignee_res=mysqli_query($conn, $select_consignee);
               while($row_consignee = mysqli_fetch_assoc($select_consignee_res)){
               $consignee_name_filter = mysqli_real_escape_string($conn,$row_consignee['consignee_name']);
              
               ?>
               <option value="<?php echo $consignee_name_filter; ?>"   <?php if($consignee_name_filter==$consignee_name){ echo "selected='selected'";} ?>><?php echo $consignee_name_filter; ?></option>
               <?php } ?>
               </datalist>
               </input>
          </div>
          <div class="col-3 col-md-2 col-lg-1 col-xxl-1 mb-2 mt-3 pt-sm-1 pt-lg-0 pt-xxl-2">
               <input type="submit" name="go" class="btn btn-primary btn-sm mt-1 search-button search float-start"  value="Search" id="search">
          </div>
     </form>
</div>
        <div class="dt-responsive table-responsive">
        <div class="row">
        <div class="col-sm-12 col-md-6">
        <div class="dataTables_length" id="simpletable_length">
        <form method="post" class="form-inline" id="limit-records-frm" action="#">
        <label>Show <select name="limit-records" id="limit-records" class="custom-select custom-select-sm form-control form-control-sm">
                 
							<?php foreach([10, 25,50,100] as $perpage): ?>
								<option <?php if( isset($_POST["limit-records"]) && $_POST["limit-records"] == $perpage) echo "selected" ?> value="<?php echo $perpage; ?>"><?php echo $perpage; ?></option>
							<?php endforeach; ?>
                                   </select> entries</label>
                                   </form>
                                   </div>
                                   </div>
                                   <div class="col-sm-12 col-md-6">
                                   <div id="simpletable_filter" class="dataTables_filter">
                         <form method="post" class="float-end" action="#" >
                          <label>Search:
                              <input  class="form-control form-control-sm" type="text" name="search" value="<?php echo $search_key; ?>" placeholder="Search-by-name" id="search_all" >
                              <input type="hidden" name="searchbtn" id="submit"></input>
                         </label>
						   
						</form>
                              </div>
                              </div>
                              </div>
        <table  width="100%" class="table table-sm table-striped border-bottom border-top dt-responsive">
            <thead>
                <tr bgcolor="#182f60" class="text-white">
<th  class="text-center"><a style="color:#f8f9fa;" href="custom_listing.php?page=<?php echo $curpage;?>&getkey=<?php echo $search_key;?>&column=id&order=<?php echo $asc_or_desc; ?>&post_at=<?php echo $post_at;?>&post_at_todate=<?php echo $post_at_todate;?>&company_name=<?php echo $company_name;?>&consignee_name=<?php echo $consignee_name;?>">S/no<i class="fas fa-sort<?php echo $column == 'id' ? '-' . $up_or_down : ''; ?>"></i></a></th>
<th  class="text-center"><a style="color:#f8f9fa;" href="custom_listing.php?page=<?php echo $curpage;?>&getkey=<?php echo $search_key;?>&column=created_date&order=<?php echo $asc_or_desc; ?>&post_at=<?php echo $post_at;?>&post_at_todate=<?php echo $post_at_todate;?>&company_name=<?php echo $company_name;?>&consignee_name=<?php echo $consignee_name;?>">Date/Time<i class="fas fa-sort<?php echo $column == 'created_date' ? '-' . $up_or_down : ''; ?>"></i></a></th>
<th  class="text-center"><a style="color:#f8f9fa;" href="custom_listing.php?page=<?php echo $curpage;?>&getkey=<?php echo $search_key;?>&column=company_name&order=<?php echo $asc_or_desc; ?>&post_at=<?php echo $post_at;?>&post_at_todate=<?php echo $post_at_todate;?>&company_name=<?php echo $company_name;?>&consignee_name=<?php echo $consignee_name;?>">Company<i class="fas fa-sort<?php echo $column == 'company_name' ? '-' . $up_or_down : ''; ?>"></i></a></th>
<th  class="text-center"><a style="color:#f8f9fa;" href="custom_listing.php?page=<?php echo $curpage;?>&getkey=<?php echo $search_key;?>&column=invoice_type&order=<?php echo $asc_or_desc; ?>&post_at=<?php echo $post_at;?>&post_at_todate=<?php echo $post_at_todate;?>&company_name=<?php echo $company_name;?>&consignee_name=<?php echo $consignee_name;?>">Type<i class="fas fa-sort<?php echo $column == 'invoice_type' ? '-' . $up_or_down : ''; ?>"></i></a></th>
<th  class="text-center"><a style="color:#f8f9fa;" href="custom_listing.php?page=<?php echo $curpage;?>&getkey=<?php echo $search_key;?>&column=invoice_no&order=<?php echo $asc_or_desc; ?>&post_at=<?php echo $post_at;?>&post_at_todate=<?php echo $post_at_todate;?>&company_name=<?php echo $company_name;?>&consignee_name=<?php echo $consignee_name;?>">Invoice No<i class="fas fa-sort<?php echo $column == 'invoice_no' ? '-' . $up_or_down : ''; ?>"></i></a></th>
<th  class="text-center"><a style="color:#f8f9fa;" href="custom_listing.php?page=<?php echo $curpage;?>&getkey=<?php echo $search_key;?>&column=acc_name&order=<?php echo $asc_or_desc; ?>&post_at=<?php echo $post_at;?>&post_at_todate=<?php echo $post_at_todate;?>&company_name=<?php echo $company_name;?>&consignee_name=<?php echo $consignee_name;?>">Billed To Name<i class="fas fa-sort<?php echo $column == 'acc_name' ? '-' . $up_or_down : ''; ?>"></i></a></th>
<th  class="text-center"><a style="color:#f8f9fa;" href="custom_listing.php?page=<?php echo $curpage;?>&getkey=<?php echo $search_key;?>&column=total_amount&order=<?php echo $asc_or_desc; ?>&post_at=<?php echo $post_at;?>&post_at_todate=<?php echo $post_at_todate;?>&company_name=<?php echo $company_name;?>&consignee_name=<?php echo $consignee_name;?>">Total Amount<i class="fas fa-sort<?php echo $column == 'total_amount' ? '-' . $up_or_down : ''; ?>"></i></a></th>
<th  class="text-center">Preview</th>
<th  class="text-center">Logs</th>
    
				</tr>
				</thead>
				<tbody>
			<?php
             
             if($sort_order =='desc' || $sort_order =='DESC'){
              $totalres_loop = $totalres - ($curpage*$per_page) +($per_page);
              } else {
              $totalres_loop = ($curpage*$per_page)+1 -($per_page);
              }
              $loop_total = 0;
             $inc_count = ($curpage*$per_page) - ($per_page-1);
			while($invoice_row = mysqli_fetch_assoc($invoice_result)){
                    $row_id = mysqli_real_escape_string($conn,$invoice_row['id']);
                    $row_invoice_type = mysqli_real_escape_string($conn,$invoice_row['invoice_type']);
                    $row_invoice_no = mysqli_real_escape_string($conn,$invoice_row['invoice_no']);
                    $id_get_hash = base64_encode($row_invoice_no);
                    $row_created_date = mysqli_real_escape_string($conn,$invoice_row['created_date']);
                    $company = mysqli_real_escape_string($conn,$invoice_row['company_name']);
                    $row_created_date = date("d-m-Y", strtotime($row_created_date));
                    $invoice_acc_name= mysqli_real_escape_string($conn,$invoice_row['acc_name']);
           $invoice_tax= mysqli_real_escape_string($conn,$invoice_row['tax']);
           $invoice_add_charges= mysqli_real_escape_string($conn,$invoice_row['add_charges']);
           $invoice_gst= mysqli_real_escape_string($conn,$invoice_row['gst']);
           $invoice_toll_amount= mysqli_real_escape_string($conn,$invoice_row['toll_amount']);
           $invoice_total_amount= mysqli_real_escape_string($conn,$invoice_row['total_amount']);
           $loop_total += $invoice_total_amount;
          
				?>

<tr id="tr<?php echo $row_id; ?>">
<td  class="text-center"><?php echo $totalres_loop; ?></td>
<td  class="text-center"><?php echo $row_created_date; ?></td>
<td  class="text-center"><?php echo $company; ?></td>
<td  class="text-center"><?php echo $invoice_type_name; ?></td>
<td  class="text-center">
<?php echo $row_invoice_no; ?> 
</td>

<td  class="text-center"><?php echo $invoice_acc_name; ?></td>
<td  class="text-center"><?php echo $invoice_total_amount; ?> </td>

<td  class="text-center">

</td>

</tr>
			<?php 
              if($sort_order =='desc' || $sort_order =='DESC'){
                  $totalres_loop--;
                  } else {
                  $totalres_loop++;
                  }


    } ?>
				</tbody>
                    <tfoot>
    <tr>
      <th scope="row"  class="text-center">Total</th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th  class="text-center"><?php echo $loop_total; ?> Rs</th>
      <th></th>
      <th></th>
    </tr>
  </tfoot>
			</table>
      </div>
      <div class="row">
      <div class="col-sm-6">
      Showing <?php if ($totalres ==0){ echo '0';} else { echo ($curpage*$per_page) - ($per_page-1);} ?> to <?php echo ($inc_count - 1); ?> of <?php echo $totalres; ?> entries
      </div>
      <div class="col-sm-6">
      <nav aria-label="Page navigation">
    <ul class="pagination float-end">
     <?php if($curpage != $startpage){ ?>
    <li class="page-item">
    <a class="page-link search" href="?page=<?php echo $startpage ?>&getkey=<?php echo $search_key;?>&column=<?php echo $columns_name;?>&order=<?php echo $order_name; ?>&post_at=<?php echo $post_at;?>&post_at_todate=<?php echo $post_at_todate;?>&company_name=<?php echo $company_name;?>&consignee_name=<?php echo $consignee_name;?>" tabindex="-1" aria-label="Previous">First
    <span aria-hidden="true">&laquo;</span>
    <span class="sr-only">First</span>
    </a>

    </li>
    <?php } ?>
    <?php if($curpage >= 2){ ?>
    <li class="page-item"><a class="page-link search" href="?page=<?php echo $previouspage ?>&getkey=<?php echo $search_key;?>&column=<?php echo $columns_name;?>&order=<?php echo $order_name; ?>&post_at=<?php echo $post_at;?>&post_at_todate=<?php echo $post_at_todate;?>&company_name=<?php echo $company_name;?>&consignee_name=<?php echo $consignee_name;?>"><?php echo $previouspage ?></a></li>
    <?php } if($totalres != 0){ ?>
    <li class="page-item active"><a class="page-link search" href="?page=<?php echo $curpage ?>&getkey=<?php echo $search_key;?>&column=<?php echo $columns_name;?>&order=<?php echo $order_name; ?>&post_at=<?php echo $post_at;?>&post_at_todate=<?php echo $post_at_todate;?>&company_name=<?php echo $company_name;?>&consignee_name=<?php echo $consignee_name;?>"><?php echo $curpage ?></a></li>
    <?php } if($curpage != $endpage && $totalres != 0){ ?>
    <li class="page-item"><a class="page-link search" href="?page=<?php echo $nextpage ?>&getkey=<?php echo $search_key;?>&column=<?php echo $columns_name;?>&order=<?php echo $order_name; ?>&post_at=<?php echo $post_at;?>&post_at_todate=<?php echo $post_at_todate;?>&company_name=<?php echo $company_name;?>&consignee_name=<?php echo $consignee_name;?>"><?php echo $nextpage ?></a></li>
    <li class="page-item">
    <a class="page-link search" href="?page=<?php echo $endpage ?>&getkey=<?php echo $search_key;?>&column=<?php echo $columns_name;?>&order=<?php echo $order_name; ?>&post_at=<?php echo $post_at;?>&post_at_todate=<?php echo $post_at_todate;?>&company_name=<?php echo $company_name;?>&consignee_name=<?php echo $consignee_name;?>" aria-label="Next">Last
    <span aria-hidden="true">&raquo;</span>
    <span class="sr-only">Last</span>
    </a>
    </li>
    <?php } ?>
    </ul>
    </nav>
      </div>
      </div>
     </div>
</div>
</div>
</div>
</div>
</div>
<input type ="hidden" value="<?php echo $columns_name; ?>" id="column"/>
<input type ="hidden" value="<?php echo $order_name; ?>" id="order"/>
</section>
<div class="modal fade" id="invoice_logs" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog   modal-xl" role="document">
          <div class="modal-content" id="modal_invoice_logs">
          
          </div>
     </div>
</div>

<script>
$(document).ready(function(){  
$("#post_at").datepicker({ format: 'dd-mm-yyyy'});
$("#post_at_to_date").datepicker({ format: 'dd-mm-yyyy'});
$('body').on('keydown', 'input, select', function(e) {
    if (e.key === "Enter") {
        var self = $(this), form = self.parents('form:eq(0)'), focusable, next;
        focusable = form.find('input,a,select,button,textarea').filter(':visible');
        next = focusable.eq(focusable.index(this)+1);
        if (next.length) {
            next.focus();
        } else {
            form.submit();
        }
        return false;
    }
});
});  

</script>
<!-- quick modal js -->
<script type="text/javascript">
	$(document).ready(function(){
     // $('#search').focus();
     var inputField = document.getElementById('search');
    if (inputField != null && inputField.value.length != 0){
        if (inputField.createTextRange){
            var FieldRange = inputField.createTextRange();
            FieldRange.moveStart('character',inputField.value.length);
            FieldRange.collapse();
            FieldRange.select();
        }else if (inputField.selectionStart || inputField.selectionStart == '0') {
            var elemLen = inputField.value.length;
            inputField.selectionStart = elemLen;
            inputField.selectionEnd = elemLen;
            inputField.focus();
        }
    }else{
        inputField.focus();
    }

   $('#limit-records').change(function() {
        this.form.submit();
    });
    $("#search_all").on('keyup', function() {
         var page = 1;
         var getkey = $('#search_all').val();
         var column = $('#column').val();
         var order = $('#order').val();
         var post_at = $('#post_at').val();
         var post_at_todate = $('#post_at_to_date').val();
         var company_name = $('#company_name').val();
         window.location.assign('custom_listing.php?page='+ page +'&getkey='+ getkey +'&column='+column +'&order='+ order + '&post_at=' + post_at + '&post_at_todate=' + post_at_todate + '&company_name=' + company_name + ''); 
     });
   
	});
</script>
