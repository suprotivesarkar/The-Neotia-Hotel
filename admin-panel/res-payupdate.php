<?php include '_auth.php';
$id=FilterInput($_GET['id']);
if(!is_numeric($id)){include '404.php';die();}
$data = CheckExists("reservation_list","res_id = '$id' AND res_status<>3");
if(empty($data)){include '404.php';die();}
?> 
<!DOCTYPE html>
<html lang="en"> 
<head> 
<title>Guest Details</title>
<?php include '_header.php'; ?>
<style type="text/css">
table.text-left thead tr th, table.text-left tbody tr td {text-align: left!important;}
.faclist li{padding: 5px;background: #e2e2e2;margin: 2px;color: #fff;font-weight: 600;border-radius: 3px;background-image: linear-gradient(to right, #b8cbb8 0%, #b8cbb8 0%, #c17a9e 0%, #e260b4 33%, #ee609c 66%, #ee609c 100%);}
.includelist li{padding: 2px 8px;background: #2e7ba9;margin: 3px 2px;border-radius: 5px;color: #fff;font-weight: 600;}
.calbtn{margin-left:15px!important;width:100%!important;padding:10px 25px!important;}
.calbkpan{position:relative;margin-top:5px}.calbkpan input{height:42px;width:100%;padding-right:43px;padding-left:10px;}
.calbkpan button{position:absolute;right:0;top:0;background:#0b7bbd;outline:none;border:none;color:#fff;height:42px;width:40px;padding:0;border-radius:0px;}
.calbkpan button .fa{font-size:22px}
.stat{display: inline-block;
    background: #f69300;
    margin: 1px 2px;
    padding: 2px 6px;
    color: #fff;
    font-weight: 600;}
</style>
</head>  
<body>
<?php  include '_menu.php'; ?>
<div class="row">
<div class="col-md-12">
<div class="card card-statistics mb-2">
<div class="card-title">
<h5>GUEST NAME - <span class="text-danger bold"><?php echo $data->res_g_name; ?></span> <a href="reservation" class="button x-small pull-right"><i class="fa fa-long-arrow-left"></i> Back</a> </h5>
<h5>GUEST PHONE - <span class="text-primary bold"><tr><td><?php  echo $data->res_g_phone;?></td><tr></span></h5>
  <div class="stat">
<?php 
$stat = null;
if($data->res_status == 0){ $stat = "PENDING";}
elseif ($data->res_status == 1) {$stat = "CONFIRMED";}
elseif ($data->res_status == 2) {$stat = "CHECKED OUT";}
else{$stat = "Invalid Data"; }

echo $stat;
 ?> 
</div>
</div>
<div class="card-body">
<ul class="nav nav-tabs thmnavtab">
<li class="nav-item"><a class="nav-link " href="reservation-view?id=<?php echo $data->res_id; ?>">BRIEF INFO</a></li>
<li class="nav-item"><a class="nav-link active" href="res-payupdate?id=<?php echo $data->res_id; ?>">PAYMENT ADD/UPDATE</a></li>
<li class="nav-item"><a class="nav-link" href="res-roomupdate?id=<?php echo $data->res_id; ?>">ROOM ADD/UPDATE</a></li>
</ul>
<div class="row">
<div class="col-md-12">
<div class="card card-statistics h-100">
<div class="card-title">
  <h5><?php echo $data->res_g_name; ?> - Payment List <a href="" data-toggle="modal" data-target="#addMod" class="button x-small pull-right"><i class="fa fa-plus"></i> Add Payment</a></h5>
</div>
<div class="card-body pt-0">
<div class="loadpan"></div>
</div>
</div>
</div>        
</div>
<div class="modal fade" id="addMod" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<div class="modal-title"><h4>Add New Amount:</h4></div>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
<form action="" autocomplete="off" id="addfrm">
<div class="row">
<div class="form-group col-sm-12">
<label for="amount">Amount:</label>
<input type="number" class="form-control" id="amount" name="amount" >
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
<div class="modal-title"><h4>Update Amount</h4></div>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
<form autocomplete="off" id="upfrm">
<input type="hidden" class="form-control" id="uptid" name="uptid" required="">
<div class="form-group col-sm-12">
<label for="upamount">Update Amount:</label>
<input type="number" class="form-control" id="upamount" name="upamount" >
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
fetchData();
$.validator.messages.required = '';
$('#addfrm').validate({});
$("#addfrm").on('submit',(function(e){
e.preventDefault();
if($("#addfrm").valid()){
    var url="helper/master/reservation-data";
    var data = new FormData(this);
    data.append("operation","addpay");
    data.append("id","<?php echo $data->res_id; ?>");
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
          $("#addfrm").trigger('reset');
          showToast(res.msg,"success");
          fetchData();
        }else {showToast(res.msg,"error");}
      }
    }); 
}else{return false;}
}));   

$("#upMod").on('shown.bs.modal',function(e){
var button=$(e.relatedTarget);
$("#uptid").val(button.data("id"));
$("#upamount").val(button.data("amount"));
});
$('#upfrm').validate({});
$("#upfrm").on('submit',(function(e){
e.preventDefault();
if($("#upfrm").valid()){
    var url="helper/master/reservation-data";
    var data = new FormData(this);
    data.append("operation","updatepay");
    data.append("id","<?php echo $data->res_id; ?>");
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
        if(res.status){fetchData();showToast(res.msg,"success");}
        else{showToast(res.msg,"error");}
      }
    }); 
}else{return false;}
}));
$(".loadpan").delegate('.statusup',"click",function(){
  var id=$(this).data("id");
  var operation=$(this).data("operation");
  if (operation=="deletepay"){
      swal({title: "Are you sure?",text: "Once deleted, you will not be able to recover",icon: "warning",buttons: true,dangerMode: true,
      }).then((willDelete)=>{if(willDelete){StatusUpdate(id,operation);}});
  }else {StatusUpdate(id,operation);} 
});
function StatusUpdate(id,type){
  var url = "helper/master/reservation-data";
  $.ajax({
    type:"POST",
    url:url,
    dataType:"json",
    data:{"id":id,"operation":type},
    beforeSend:function(){},
    error:function(res){$('.loading').hide();showToast("Something Wrong Try Later","error");},
    success:function(res)
    { 
      if(res.status){fetchData();showToast(res.msg,"success");}
      else{showToast(res.msg,"error");}
    }
  });
}
function fetchData(){$.post("helper/master/reservation-data",{"operation":"fetchpay","id":"<?php echo $data->res_id; ?>"},function(data){$(".loadpan").html(data);$('#entry_table').DataTable({stateSave:true});});}
</script>
</body>
</html>