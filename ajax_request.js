// request for modal data using row id
$('.sent').on("click",function(){
      var getData = $(this).attr('idGet');
      $('#modal_info').html(" ");
       $.ajax({
        method:"post",  
		    data: {getData_cha: getData_cha},
		    url: "../all_master_modal.php",
		   success:function(data){  
             $('#modal_info').html(data);  
		    }
		  });
});
// Status Update Request Using Id
$('.update_status').on("click",function(){
      var getStatus = $(this).attr('idGet');
      swal({
        title: "Activate or Deactivate CHA",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Update Now",
        closeOnConfirm: false
    }, function (isConfirm) {
        if (!isConfirm) return;
          $.ajax({
        	method:"post",  
		    data: {getStatus_cha: getStatus_cha},
		    url: "../allajx_master.php",
		    success:function(data){  
                  var lastdigit = data.slice(-1);
                  var idclass = data.slice(0,-1)
                   if(lastdigit == 1){
                    $('#update_status'+idclass).removeClass('btn-success').addClass('btn-danger');
                    $('#update_statusicon'+idclass).removeClass('fa fa-unlock-alt').addClass('fa fa-lock');
                    
                    swal("","Succesfully Deactivated ","success");
                    // swal.close();
                   } else {
                    $('#update_status'+idclass).removeClass('btn-danger').addClass('btn-success');
                    $('#update_statusicon'+idclass).removeClass('fa fa-lock').addClass('fa fa-unlock-alt');
                    swal("","Succesfully Activated ","success","2500");
                    //  tr.hide();
                   } 
		    }
		  });
      });
 });
// form submit using ajax
$('#submit_btn').on('click', function(e){
$("#submit_btn").attr("disabled", false);
e.preventDefault();
var $form = $(this).closest("#form_id");
var formData =  $form.serializeArray();             
var URL = "submit.php";
$.post(URL, formData).done(function(data) { 
     if(data == 1){
          swal({title: "Success", text: "", type: "success"},
          function(){ 
               location.reload();
          }
          );
     
     }
     if(data == 2){
        swal(' Failed');
     return false;
     }
});
}); 
// Specific Class Send In Array Using Ajax and respoce in json fetch on specific class
$('#send_btn').click(function() {
     var $form = $(this).closest("#form_id");
     var formData =  $form.serializeArray(); 
     var map_location = $('input:text.map_location').serialize();            
     var URL = "submit.php?from_to_station_send=";
     $.post(URL, map_location).done(function(data) { 
          var response = JSON.parse(data);
          var inc_id = parseInt($('#inc_id').val());
               for (let i = 0; i < inc_id; i++) {
                    var responce_inc = i + 1;
                    $(".sned_"+responce_inc).val(response[0][i]);
                    $(".sned2_"+responce_inc).val(response[1][i]);
               } 
     });
 });
// Same Page Request date filter
		$('.search').click(function(e){  
			var from_date = $('#post_at').val();  
			var to_date = $('#post_at_to_date').val(); 
              if(from_date!= '' && to_date!= ''){  
				<?php  
                    if(isset($_GET['post_at'])){
                         $post_at = $_GET['post_at'];
                         $post_at_todate = $_GET['post_at_todate'];
                    } else{ 
                      $post_at= date('01-m-Y');
                      $post_at_todate=  date('d-m-Y');
                     }
    				if(!empty($_POST["from_date"])) {			
    				  $post_at = $_POST["from_date"];

    					if(!empty($_POST["to_date"])) {
    						$post_at_todate = $_POST["to_date"];
    					}  
				} ?>
			} else {
				
				alert("Please Select date option "); 
                                return false;
			}
		});
