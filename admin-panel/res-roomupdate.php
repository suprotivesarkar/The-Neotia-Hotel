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
<li class="nav-item"><a class="nav-link " href="res-payupdate?id=<?php echo $data->res_id; ?>">PAYMENT ADD/UPDATE</a></li>
<li class="nav-item"><a class="nav-link active" href="res-roomupdate?id=<?php echo $data->res_id; ?>">ROOM ADD/UPDATE</a></li>
</ul>
<div class="row">
<div class="col-md-12">
<div class="card card-statistics h-100">
<div class="card-title">
  <h5><?php echo $data->res_g_name; ?> - Room List <a href="res-roomadd?id=<?php echo $data->res_id; ?>" title ="Add Room" class="button x-small pull-right"><i class="fa fa-plus"> ADD ROOM</i></a></h5>
</div>
<div class="card-body pt-0">
<div class="loadpan"></div>
</div>
</div>
</div>        
</div>
<?php  include '_footer.php'; ?>
<script type="text/javascript">
	 fetchData();
$(".loadpan").delegate('.statusup',"click",function(){
  var id=$(this).data("id");
  var operation=$(this).data("operation");
  if (operation=="deleterom"){
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
function fetchData(){$.post("helper/master/reservation-data",{"operation":"fetchrom","id":"<?php echo $data->res_id; ?>"},function(data){$(".loadpan").html(data);$('#entry_table').DataTable({stateSave:true});});}
</script>
</body>
</html>