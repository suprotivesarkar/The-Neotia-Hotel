<?php include '_auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Employee List</title>
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
<h5>Employee List <a href="" data-toggle="modal" data-target="#addMod" class="button x-small pull-right"><i class="fa fa-plus"></i> Add New</a></h5>
</div>
<div class="card-body">
<div class="table-responsive minhight">
<table class="table table-striped table-bordered table-hover " id="entry_table">
<thead>
<tr>
<th>#</th>
<th>EMPLOYEE CODE</th> 
<th>ROLE</th>
<th>CATEGORY</th>
<th>NAME</th>
<th>PASSWORD</th>
<th>PHONE NO</th>
<th>EMAIL ID</th>
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
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
<div class="modal-header">
<div class="modal-title"><h4>Add New Employee</h4></div>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
<form action="" autocomplete="off" id="addfrm">
<div class="row">
<div class="form-group col-sm-6">
<label for="empcode">EMPLOYEE CODE<span class="req">*</span></label>
<input type="text" class="form-control" id="empcode" name="empcode">
</div>
<div class="form-group col-sm-6">
<label for="role">Select Role:<span class="req">*</span></label>
<select id="role" name="role" class="form-control">
<option value="">-- Select Role --</option>
<?php
$qu=$PDO->prepare("SELECT role_id,role_name FROM users_role WHERE role_status=1");
$qu->execute(); 
while ($desdet=$qu->fetch()){
echo '<option value="'.$desdet['role_id'].'">'.$desdet['role_name'].'</option>';
} ?>
</select> 
</div>
<div class="form-group col-sm-6">
<label for="category">Select Category:<span class="req">*</span></label>
<select id="category" name="category" class="form-control">
<option value="">-- Select Category --</option>
<?php
$qu=$PDO->prepare("SELECT cat_id,cat_name FROM emp_cat WHERE cat_status=1");
$qu->execute(); 
while ($desdet=$qu->fetch()){
echo '<option value="'.$desdet['cat_id'].'">'.$desdet['cat_name'].'</option>';
} ?>
</select> 
</div>
<div class="form-group col-sm-6">
<label for="empname">NAME:<span class="req">*</span></label>
<input type="text" class="form-control" id="empname" name="empname">
</div>
<div class="form-group col-sm-6">
<label for="phno">PHONE NO.:<span class="req">*</span></label>
<input type="number" class="form-control" id="phno" name="phno">
</div>
<div class="form-group col-sm-6">
<label for="mail">EMAIL ID:<span class="req">*</span></label>
<input type="mail" class="form-control" id="mail" name="mail">
</div>
<div class="form-group col-sm-6">
<label for="address">ADDRESS:<span class="req">*</span></label>
<input type="text" class="form-control" id="address" name="address">
</div>
<div class="form-group col-sm-6">
<label for="dob">DATE OF BIRTH:<span class="req">*</span></label>
<input type="text" class="form-control datepicker" id="dob" name="dob">
</div>
<div class="form-group col-sm-6">
<label for="join">JOINING DATE:<span class="req">*</span></label>
<input type="text" class="form-control datepicker" id="join" name="join">
</div>
<div class="form-group col-sm-6">
<label for="salary">EMPLOYEE SALARY:<span class="req">*</span></label>
<input type="number" class="form-control" id="salary" name="salary">
</div>
<div class="form-group col-sm-6">
<label for="password">SET PASSWORD:<span class="req">*</span></label>
<input type="text" class="form-control" id="password" name="password">
</div>
<div class="form-group col-sm-6">
<label for="empimg">EMPLOYEE IMAGE:</label>
<input type="file" class="form-control" id="empimg" name="image">
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
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
<div class="modal-header">
<div class="modal-title"><h4>Update Employee Details</h4></div>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
<form action="" autocomplete="off" id="upfrm">
<input type="hidden" class="form-control" id="uptid" name="uptid">
<div class="row">
<div class="form-group col-sm-4">
<label for="upempcode">EMPLOYEE CODE<span class="req">*</span></label>
<input type="text" class="form-control" id="upempcode" name="upempcode">
</div>
<div class="form-group col-sm-4">
<label for="uprole">Select Role:<span class="req">*</span></label>
<select id="uprole" name="uprole" class="form-control">
<option value="">-- Select Role --</option>
<?php
$qu=$PDO->prepare("SELECT role_id,role_name FROM users_role WHERE role_status=1");
$qu->execute(); 
while ($desdet=$qu->fetch()){
echo '<option value="'.$desdet['role_id'].'">'.$desdet['role_name'].'</option>';
} ?>
</select> 
</div>
<div class="form-group col-sm-4">
<label for="upcategory">Select Category:<span class="req">*</span></label>
<select id="upcategory" name="upcategory" class="form-control">
<option value="">-- Select Category --</option>
<?php
$qu=$PDO->prepare("SELECT cat_id,cat_name FROM emp_cat WHERE cat_status=1 ORDER BY cat_name ASC");
$qu->execute(); 
while ($desdet=$qu->fetch()){
echo '<option value="'.$desdet['cat_id'].'">'.$desdet['cat_name'].'</option>';
} ?>
</select> 
</div>
<div class="form-group col-sm-6">
<label for="upempname">NAME:<span class="req">*</span></label>
<input type="text" class="form-control" id="upempname" name="upempname">
</div>
<div class="form-group col-sm-6">
<label for="upphno">PHONE NO:<span class="req">*</span></label>
<input type="text" class="form-control" id="upphno" name="upphno">
</div>
<div class="form-group col-sm-6">
<label for="upmail">EMAIL ID:<span class="req">*</span></label>
<input type="mail" class="form-control" id="upmail" name="upmail">
</div>
<div class="form-group col-sm-6">
<label for="upaddress">ADDRESS:<span class="req">*</span></label>
<input type="text" class="form-control" id="upaddress" name="upaddress">
</div>
<div class="form-group col-sm-6">
<label for="updob">DATE OF BIRTH:<span class="req">*</span></label>
<input type="text" class="form-control datepicker" id="updob" name="updob">
</div>
<div class="form-group col-sm-6">
<label for="upjoin">JOINING DATE:<span class="req">*</span></label>
<input type="text" class="form-control datepicker" id="upjoin" name="upjoin">
</div>

<div class="form-group col-sm-6">
<label for="uppassword">UPDATE PASSWORD:<span class="req">*</span></label>
<input type="text" class="form-control" id="uppassword" name="uppassword">
</div>
<div class="form-group col-sm-6">
<label for="upsalary">EMPLOYEE SALARY:<span class="req">*</span></label>
<input type="number" class="form-control" id="upsalary" name="upsalary">
</div>
<div class="form-group col-sm-6">
<label for="empimg">EMPLOYEE IMAGE:<span class="req">*</span> [square image]</label>
<input type="file" class="form-control" id="upimg" name="upimage">
</div>
<div class="form-group col-sm-6">
<label for="upleave">LEAVING DATE:</label>
<input type="text" class="form-control datepicker" id="upleave" name="upleave" >
</div>
<div class="form-group col-sm-12">
<button type="submit" class="button btn-block btn-lg entrybtn">Update Now</button>
</div>
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
    var url="helper/master/employee";
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
$("#upcategory").val(button.data("catid"));
$("#upempcode").val(button.data("code"));
$("#uprole").val(button.data("role"));
$("#upempname").val(button.data("name"));
$("#upphno").val(button.data("phone"));
$("#upmail").val(button.data("mail"));
$("#upaddress").val(button.data("address"));
$("#updob").val(button.data("birth"));
$("#upjoin").val(button.data("join"));
$("#upsalary").val(button.data("salary"));
$("#uppassword").val(button.data("password"));
$("#upleave").val(button.data("leave"));

});
$('#upfrm').validate({});
$("#upfrm").on('submit',(function(e){
e.preventDefault();
if($("#upfrm").valid()){
    var url="helper/master/employee";
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
// $("#pname").keyup(function(){$("#purl").val(convertToSlug($(this).val()));});
// $("#uppname").keyup(function(){$(this).val();$("#uppurl").val(convertToSlug($(this).val()));});

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
    url :"helper/master/employee", 
    type:"post",
    data:{"operation":"fetch","sdate":$("#sdate").val(),"edate":$("#edate").val()},
    error: function(){ $("#entry_table").css("display","none");}
  }
});
$(".loadpan").delegate('.copybtn',"click",function(){
  var id=$(this).data("id");
  $.ajax({
    type:"POST",
    url:"helper/master/employee", 
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
  var url = "helper/master/employee";
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