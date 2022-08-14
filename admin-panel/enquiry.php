<?php include '_auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Enquiry</title>
<?php  include '_header.php'; ?>
</head>
<body> 
<?php  include '_menu.php'; ?>
<div class="row">
<div class="col-md-12">
<div class="card card-statistics h-100">
<div class="card-title">
<h5>Enquiry List</h5>
</div>
<div class="card-body pt-0">
<div class="loadpan"></div>
</div>
</div>
</div>        
</div>
<div class="modal fade" id="viewMod" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
<div class="modal-body">
<div class="enqfull"></div>
</div>
</div>
</div>
</div>
<?php  include '_footer.php'; ?>
<script type="text/javascript">
fetchData();
$("#viewMod").on('shown.bs.modal',function(e){
var button=$(e.relatedTarget);
$.post("helper/master/enquiry",{"operation":"viewmore","id":button.data("id")},function(data){
$(".enqfull").html(data);
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
  var url = "helper/master/enquiry";
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
function fetchData(){$.post("helper/master/enquiry",{"operation":"fetch"},function(data){$(".loadpan").html(data);$('#entry_table').DataTable({stateSave:true});});}
</script>
</body>
</html>