<?php include '_auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Employee Salary List</title>
<?php  include '_header.php'; ?>
<style type="text/css">
.dataTables_processing{box-shadow: 0 1px 1px rgba(0,0,0,.05);background: transparent;border:none;}
.dataTables_processing img{height:40px;}
.minhight{min-height:100vh}
#filterload{position:fixed;z-index:1000;top:0;left:0;height:100%;width:100%;background:rgba(255,255,255,0.4) url('assets/img/spiner.gif') 50% 50% no-repeat;background-size:50px;display:none}
.successbold{font-weight:600;color:green}
</style>
</head>
<body>   
<?php  include '_menu.php'; ?>
<div id="filterload"></div>
<div class="row">
<div class="col-md-12">
<div class="card card-statistics">
<div class="card-title">
<h5>Employee Salary List <a href="" data-toggle="modal" data-target="#addMod" class="button x-small pull-right"><i class="fa fa-plus"></i> Add New</a></h5>
</div>
<div class="card-body">
<div class="table-responsive minhight">
<table class="table table-striped table-bordered table-hover " id="entry_table">
<thead>
<tr>
<th>#</th>
<th>EMPLOYEE NAME</th> 
<th>AMOUNT</th>
<th>MONTH</th>
<th>DATE</th>
<th>STATUS</th>
<th>&nbsp;</th>
</tr>
</thead>
<tbody class="loadpan"></tbody>
</table>
</div>
</div>
</div>
</div>        
</div> 
<div class="modal fade" id="addMod" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<div class="modal-title"><h4>Add New Employee Salary Details</h4></div>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
<form action="" autocomplete="off" id="addfrm">
<div class="row">
<div class="form-group col-sm-12">
<label for="eid">Select Employee Name:<span class="req">*</span></label>
<select id="eid" name="eid" class="form-control" required="">
<option value="">-- Slect Employee Name --</option>
<?php
$qu=$PDO->prepare("SELECT user_id,user_name FROM users_list WHERE user_status=1");
$qu->execute(); 
while ($desdet=$qu->fetch()){
echo '<option value="'.$desdet['user_id'].'">'.$desdet['user_name'].'</option>';
} ?>
</select> 
</div>
<!-- <div class="form-group col-sm-12">
<label for="amount"> SALARY AMOUNT:</label>
<input type="number" class="form-control" id="amount" name="amount">
</div> -->
<div class="form-group col-sm-12">
<label for="date">DATE:</label>
<input type="text" class="form-control datepicker" id="date" name="date" required="">
</div>
<div class="form-group col-sm-12">
<button type="submit" class="button btn-block btn-lg entrybtn">Add Now <i class="fa fa-refresh fa-spin loading"></i></button>
</div>
</div>
</form>
</div>
</div>
</div>
</div> 
<div class="modal fade" id="upMod" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<div class="modal-title"><h4>Update Salary Details</h4></div>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
<form autocomplete="off" id="upfrm">
<input type="hidden" class="form-control" id="uptid" name="uptid" required="">
<div class="form-group col-sm-12">
<label for="upeid">Select Employee Name:<span class="req">*</span></label>
<select id="upeid" name="upeid" class="form-control" required="">
<option value="">-- Slect Employee Name --</option>
<?php
$qu=$PDO->prepare("SELECT user_id,user_name FROM users_list WHERE user_status=1");
$qu->execute(); 
while ($desdet=$qu->fetch()){
echo '<option value="'.$desdet['user_id'].'">'.$desdet['user_name'].'</option>';
} ?>
</select> 
</div>
<div class="form-group col-sm-12">
<label for="upamount">Update Salary:</label>
<input type="number" class="form-control" id="upamount" name="upamount" required="">
</div>
<div class="form-group col-sm-12">
<label for="uppdate">Updated Date:</label>
<input type="text" class="form-control datepicker" id="uppdate" name="uppdate" required="">
</div>
<div class="form-group">
<button type="submit" class="button btn-block btn-lg entrybtn">Update Now <i class="fa fa-refresh fa-spin loading"></i></button>
</div>
</form>
</div> 
</div>
</div>
</div> 
<?php  include '_footer.php'; ?>
<script type="text/javascript">
$(".datepicker").datepicker({autoclose: true,dateFormat:'dd-mm-yy'});
$.validator.messages.required = '';
$('#addfrm').validate({});
$("#addfrm").on('submit',(function(e){
e.preventDefault();
if($("#addfrm").valid()){
    var url="helper/master/emp_salary";
    var data = new FormData(this);
    data.append("operation","addnew");
    $.ajax({
      type: "POST",
      url: url,
      data: data,
      contentType: false,
      cache: false,
      processData:false,  
      dataType:"json",
      beforeSend: function(){$('.entrybtn').addClass('eventbtn');},
      error: function(res){$('.entrybtn').removeClass('eventbtn');showToast("Something Wrong Try Later","error");},
      success: function(res)
      {
        $('.entrybtn').removeClass('eventbtn');
        if(res.status){
          table.ajax.reload(null,false);
          $("#addfrm").trigger('reset');
          showToast(res.msg,"success");
          showToast(res.msg,"success");
        }else {showToast(res.msg,"error");}
      }
    }); 
}else{return false;}
}));         
$("#upMod").on('shown.bs.modal',function(e){
var button=$(e.relatedTarget);
$("#uptid").val(button.data("id"));
$("#upeid").val(button.data("eid"));
$("#upamount").val(button.data("amount"));
$("#uppdate").val(button.data("dt"));
});
$('#upfrm').validate({});
$("#upfrm").on('submit',(function(e){
e.preventDefault();
if($("#upfrm").valid()){
    var url="helper/master/emp_salary";
    var data = new FormData(this);
    data.append("operation","update");
    $.ajax({
      type: "POST",
      url: url,
      data: data,
      contentType: false,
      cache: false,
      processData:false, 
      dataType:"json",
      beforeSend: function(){$('.entrybtn').addClass('eventbtn');},
      error: function(res){$('.entrybtn').removeClass('eventbtn');showToast("Something Wrong Try Later","error");},
      success: function(res)
      {
        $('.entrybtn').removeClass('eventbtn');
        if(res.status){table.ajax.reload(null,false);showToast(res.msg,"success");}
        else{showToast(res.msg,"error");}
      }
    }); 
}else{return false;}
}));

var table =$('#entry_table').DataTable({
  "bStateSave": true,
  "bProcessing": true,
  "serverSide": true,
  "stateSave":true, 
  "paging": true,
  "pagingType": "full_numbers",
  "pageLength": 25,
  "aaSorting":[[0,"desc"]],
  "oLanguage":{sProcessing:"<img class='reloadimg' src='assets/img/spiner.gif'>","sEmptyTable": "Sorry..No Record Found"},
  "fnCreatedRow": function( nRow, aData, iDataIndex ) {
    $(nRow).attr('id', "row"+aData[0]);
  },
  "ajax":{
    url :"helper/master/emp_salary", 
    type:"post",
    data:{"operation":"fetch","sdate":$("#sdate").val(),"edate":$("#edate").val()},
    error: function(){ $("#entry_table").css("display","none");}
  }
});
$(".loadpan").delegate('.copybtn',"click",function(){
  var id=$(this).data("id");
  $.ajax({
    type:"POST",
    url:"helper/master/emp_salary", 
    dataType:"json",
    timeout:15000, 
    data:{"id":id,"operation":"copydata"},
    beforeSend:function(){$('#filterload').show();},
    error:function(res){$('#filterload').hide();showToast("Something Wrong Try Later","error");},
    success:function(res)
    { 
      $('#filterload').hide();
      if(res.status){table.ajax.reload();showToast(res.msg,"success");}
      else{showToast(res.msg,"error");}
    }
  });
});
$(".loadpan").delegate('.statusup',"click",function(){
  var id=$(this).data("id");
  var operation=$(this).data("operation");
  if (operation=="delete"){
      swal({title: "Are you sure?",text: "Once deleted, you will not be able to recover",icon: "warning",buttons: true,dangerMode: true,
      }).then((willDelete)=>{if(willDelete){StatusUpdate(id,operation);}});
  }else {StatusUpdate(id,operation);} 
});
function StatusUpdate(id,type){
  var url = "helper/master/emp_salary";
  $.ajax({
    type:"POST",
    url:url,
    dataType:"json",
    data:{"id":id,"operation":type},
    beforeSend:function(){},
    error:function(res){showToast("Something Wrong Try Later","error");},
    success:function(res)
    { 
      if(res.status){table.ajax.reload();showToast(res.msg,"success");}
      else{showToast(res.msg,"error");}
    }
  });
}
</script>
</body>
</html>