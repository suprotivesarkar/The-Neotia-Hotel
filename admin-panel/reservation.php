<?php 
include '_auth.php'; 
$s  = "SELECT COUNT(*) as count FROM reservation_list WHERE res_status = 0";
$s  = $PDO->prepare($s);
$s->execute(); 
$det     =  $s->fetch();
$pending = $det['count'];
$s = "SELECT COUNT(*) as count FROM reservation_list WHERE res_status = 1";
$s = $PDO->prepare($s);
$s->execute(); 
$det =  $s->fetch();
$confirm = $det['count'];

$s = "SELECT COUNT(*) as count FROM reservation_list WHERE res_status = 2";
$s = $PDO->prepare($s);
$s->execute(); 
$det =  $s->fetch();
$checkout = $det['count'];

$s = "SELECT COUNT(*) as count FROM reservation_list WHERE res_status = 3";
$s = $PDO->prepare($s);
$s->execute(); 
$det =  $s->fetch();
$cancel = $det['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head> 
<title>Reservation</title>
<?php  include '_header.php'; ?>
<style type="text/css">
table.text-left thead tr th, table.text-left tbody tr td {text-align: left!important;}
.faclist li{display: inline-block;background: #f60000;margin: 1px 2px;padding: 2px 6px;border-radius: 24px;color: #fff;font-weight: 600;}
.reloadimg{width: 50px;}
</style>
</head> 
<body> 
<?php  include '_menu.php'; ?>
<div class="row">
<div class="col-md-12">
<div class="card card-statistics h-100">
<div class="card-body">
<ul class="nav nav-tabs thmnavtab">
<li class="nav-item"><a class="nav-link active" href="reservation">Pending (<?php echo $pending; ?>)</a></li>
<li class="nav-item"><a class="nav-link " href="reservation-confirmed">Confirmed (<?php echo $confirm; ?>)</a></li>
<li class="nav-item"><a class="nav-link " href="reservation-checkout">Check-Out (<?php echo $checkout; ?>)</a></li>
<li class="nav-item"><a class="nav-link " href="reservation-cancel">Cancel(<?php echo $cancel; ?>)</a></li>
</ul>
<div class="row">
<div class="col-xl-12">
<div class="card card-statistics h-100">
<div class="card-title"><h5>Pending Reservations<a href="add-reservation" class="button x-small pull-right"><i class="fa fa-plus"></i> Add Reservation</a></h5>
</div>

<div class="card-body">
<div class="table-responsive minhight">
<table class="table table-striped table-bordered table-hover " id="entry_table">
<thead>
<tr>
<th>#</th>
<th>Book ID</th>
<th>Guest Name</th> 
<th>Phone No.</th>
<th>Room No.</th>
<th>Total Amount</th>
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
</div>
</div>
</div>        
</div>






<?php  include '_footer.php'; ?>
<script type="text/javascript">
var table =$('#entry_table').DataTable({
  "bStateSave": true,
  "bProcessing": true,
  "serverSide": true,
  "stateSave":true, 
  "paging": true,
  "pagingType": "full_numbers",
  "pageLength": 25,
  "aaSorting":[[0,"desc"]],
  "oLanguage":{sProcessing:"<img class='reloadimg' src='assets/img/spiner.gif'>","sEmptyTable": "Sorry..No Pending Found"},
  "fnCreatedRow": function( nRow, aData, iDataIndex ) {
    $(nRow).attr('id', "row"+aData[0]);
  },
  "ajax":{
    url :"helper/master/reservation-data", 
    type:"post",
    data:{"operation":"fetch","sdate":$("#sdate").val(),"edate":$("#edate").val()},
    error: function(){ $("#entry_table").css("display","none");}
  }
});
$(".loadpan").delegate('.copybtn',"click",function(){
  var id=$(this).data("id");
  $.ajax({
    type:"POST",
    url:"helper/master/reservation-data", 
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
  if (operation=="deletep"){
      swal({title: "Are you sure?",text: "Once deleted, you will not be able to recover",icon: "warning",buttons: true,dangerMode: true,
      }).then((willDelete)=>{if(willDelete){StatusUpdate(id,operation);}});
  }
  else if (operation=="confirm"){
      swal({title: "Are you sure to Confirm?",text: "Once Confirmed, you will not be able to recover",icon: "warning",buttons: true,dangerMode: true,
      }).then((willDelete)=>{if(willDelete){StatusUpdate(id,operation);}});
  }
  else {StatusUpdate(id,operation);} 
});
function StatusUpdate(id,type){
  var url = "helper/master/reservation-data";
  $.ajax({
    type:"POST",
    url:url,
    dataType:"json",
    data:{"id":id,"operation":type},
    beforeSend:function(){},
    error:function(res){showToast("Something Wrong Try Later","error");},
    success:function(res)
    { 
      if(res.status){location.reload();showToast(res.msg,"success");}
      else{showToast(res.msg,"error");}
    }
  });
}
</script>
</body>
</html>