<?php include '_auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Payments</title>
<?php  include '_header.php'; ?>
<style type="text/css">
.dataTables_processing{box-shadow: 0 1px 1px rgba(0,0,0,.05);background: transparent;border:none;}
.dataTables_processing img{height:40px;}
.minhight{min-height:100vh}
#filterload{position:fixed;z-index:1000;top:0;left:0;height:100%;width:100%;background:rgba(255,255,255,0.4) url('assets/img/spiner.gif') 50% 50% no-repeat;background-size:50px;display:none}
.successbold{font-weight:600;color:green}
.mycolor{height:5px;width:5px;display:inline-block;margin-left:8px;border-radius:50%;}
.sbtn{border-radius:0;padding:8px 15px;}
.maxwidth{height:39px}
</style>
</head>
<body>   
<?php  include '_menu.php'; ?>
<div id="filterload"></div>
<div class="row">
<div class="col-md-12">
<div class="card card-statistics">
<div class="card-title">
<div class="row">
<div class="col-sm-3"> 
<h5>Payment List</h5>
</div>
<div class="col-sm-9">
<form class="form-inline" id="filterform">
<input type="text" class="form-control maxwidth datepicker" id="sdate" autocomplete="off" placeholder="FROM">
<input type="text" class="form-control maxwidth datepicker" id="edate" autocomplete="off" placeholder="TO"> 
<button type="button" class="btn btn-primary sbtn" style="background: #289e28;">Search</button>
</form>
</div>
</div>
</div>
<div class="card-body">
<div class="table-responsive minhight">
<table class="table table-striped table-bordered table-hover " id="entry_table">
<thead>
<tr>
<th>#</th>
<th>GUEST NAME</th>
<th>GUEST PHONE</th>
<th>PAYMENT CREATE AT</th>
<th>PAYMENT UPDATE AT</th>
<th>PAYMENT AMOUNT</th>
<th>PAYMENT MODE</th>
<th>TRANSACTION ID</th>
<th>PAYMENT RECIEVED</th>
<th>VIEW</th>
</tr>
</thead>
<tbody class="loadpan"></tbody>
</table>
</div>
</div>
</div>
</div>        
</div>
<div class="modal fade" id="viewMod" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
<div class="modal-body">
<div class="enqfull divloader"></div>
</div>
</div>
</div>
</div>
<?php  include '_footer.php'; ?>
<script type="text/javascript">
$(".datepicker").datepicker({autoclose: true,dateFormat:'dd-mm-yy'});
FetchData();
$("#viewMod").on('shown.bs.modal',function(e){
var button=$(e.relatedTarget);
$.ajax({
type:"POST",
url:"helper/master/payment",
data:{"operation":"viewmore","id":button.data("id")},
beforeSend:function(){$(".enqfull").addClass("divloader");},
error:function(res){showToast("Something Wrong Try Later","error");},
success:function(res){$(".enqfull").removeClass("divloader");$(".enqfull").html(res);}
});
});
function FetchData(){
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
    url :"helper/master/payment", 
    type:"post",
    data:{"operation":"fetch","sdate":$("#sdate").val(),"edate":$("#edate").val()},
    error: function(){ $("#entry_table").css("display","none");}
  }
});
}
$(".sbtn").on("click",function(){$('#entry_table').DataTable().destroy();FetchData();});
$(".loadpan").delegate('.statusup',"click",function(){
  var id=$(this).data("id");
  var operation=$(this).data("operation");
  if (operation=="delete"){
      swal({title: "Are you sure?",text: "Once deleted, you will not be able to recover",icon: "warning",buttons: true,dangerMode: true,
      }).then((willDelete)=>{if(willDelete){StatusUpdate(id,operation);}});
  }else {StatusUpdate(id,operation);} 
});
function StatusUpdate(id,type){
  var url = "helper/master/payment";
  $.ajax({
    type:"POST",
    url:url,
    dataType:"json",
    data:{"id":id,"operation":type},
    beforeSend:function(){},
    error:function(res){showToast("Something Wrong Try Later","error");},
    success:function(res)
    {
      if(res.status){$('#entry_table').DataTable().destroy();FetchData();showToast(res.msg,"success");}
      else{showToast(res.msg,"error");}
    }
  });
}
</script>
</body>
</html>