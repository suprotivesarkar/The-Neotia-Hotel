<?php include '_auth.php'; ?>
<?php
$id=FilterInput($_GET['id']);
if(!is_numeric($id)){include '404.php';die();}
$stmt = $PDO->prepare("SELECT * FROM room_category WHERE roomcat_id='$id' AND roomcat_status<>2");
$stmt->execute(); 
$data = $stmt->fetch(PDO::FETCH_OBJ);
if(empty($data)){include '404.php';die();}
?>
<!DOCTYPE html>
<html lang="en">
<head> 
<title>Room - Gallery</title>
<?php  include '_header.php'; ?>
<style type="text/css">
table.text-left thead tr th, table.text-left tbody tr td {text-align: left!important;}
</style>
</head> 
<body> 
<?php  include '_menu.php'; ?>
<div class="row">
<div class="col-md-12">
<div class="card card-statistics h-100">
<div class="card-title">
<h5>Room Gallery - <?= $data->roomcat_name; ?> <a href="rooms-brief?id=<?php echo $data->roomcat_id; ?>" class="button x-small pull-right"><i class="fa fa-long-arrow-left"></i> Back</a> <a href="room-update?id=<?php echo $id; ?>" class="button x-small pull-right mx-2"><i class="fa fa-pencil"></i> Update</a></h5></div>
<div class="card-body">
<ul class="nav nav-tabs thmnavtab">
<li class="nav-item"><a class="nav-link" href="rooms-brief?id=<?= $data->roomcat_id; ?>">Brief Info</a></li>
<li class="nav-item"><a class="nav-link active" href="rooms-gallery?id=<?= $data->roomcat_id; ?>">Gallery</a></li>
<li class="nav-item"><a class="nav-link " href="room-facilities?id=<?= $data->roomcat_id; ?>">Facility</a></li>
</ul>
<div class="row">
<div class="col-xl-12">
<div class="card card-statistics h-100">
<div class="card-title"><h5>Gallery Image<a href="" data-toggle="modal" data-target="#addThumb" class="button x-small pull-right"><i class="fa fa-plus"></i> ADD IMAGE</a></h5></div>
<div class="card-body">
<div class="loadcover"></div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>        
</div>
<div class="modal fade" id="addThumb" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<div class="modal-title"><h4>Add Gallery Image</h4></div>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
<form id="thumbAdd" autocomplete="off" enctype="multipart/form-data">
<div class="row">
<div class="form-group col-sm-12">
<label for="thumbimg">Select Image: [760X400]</label>
<input type="file" class="form-control" id="thumbimg" name="image" required="">
</div>
<div class="form-group col-sm-12">
<label for="thumbimgname">Image Name:</label>
<input type="text" class="form-control" id="thumbimgname" name="thumbimgname">
</div>
<div class="form-group col-sm-12">
<label for="thumbimgalt">Image ALT:</label>
<input type="text" class="form-control" id="thumbimgalt" name="thumbimgalt">
</div>
<div class="form-group col-sm-12">
<button type="submit" class="button btn-block btn-lg entrybtn">Add Now</button>
</div>
</div>
</form>
</div>
</div>
</div>
</div>
<div class="modal fade" id="upThumb" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<div class="modal-title"><h4>Update Thumb Image</h4></div>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
<form id="thumbUp" autocomplete="off" enctype="multipart/form-data">
<input type="hidden" class="form-control" id="uptid" name="uptid" required="">
<div class="row">
<div class="form-group col-sm-12">
<label for="upthumbimg">Select Image: [760X400]</label>
<input type="file" class="form-control" id="upthumbimg" name="image">
</div>
<div class="form-group col-sm-12">
<label for="upthumbimgname">Image Name:</label>
<input type="text" class="form-control" id="upthumbimgname" name="upthumbimgname">
</div>
<div class="form-group col-sm-12">
<label for="upthumbimgalt">Image ALT:</label>
<input type="text" class="form-control" id="upthumbimgalt" name="upthumbimgalt">
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
$(document).ready(function(){
fetchCover();
$.validator.messages.required = '';
$('#thumbAdd').validate({});
$("#thumbAdd").on('submit',(function(e){
e.preventDefault();
if($("#thumbAdd").valid()){
    var url="helper/master/room-gallery";
    var data = new FormData(this);
    data.append("operation","addGal");
    data.append("id","<?= $data->roomcat_id; ?>");
    $.ajax({
      type:"POST",url:url,data:data,contentType:false,cache:false,processData:false,dataType:"json",
      beforeSend: function(){$('.entrybtn').addClass('eventbtn');},
      error: function(res){$('.entrybtn').removeClass('eventbtn');showToast("Something Wrong Try Later","error");},
      success: function(res)
      {
        $('.entrybtn').removeClass('eventbtn');
        if(res.status){
          fetchCover();
          $("#thumbAdd").trigger('reset');
          $("#thumbimg").val('');
          showToast(res.msg,"success");
        }else {showToast(res.msg,"error");}
      }
    }); 
}else{return false;}
})); 
$("#upThumb").on('shown.bs.modal',function(e){
var button=$(e.relatedTarget);
$("#uptid").val(button.data("id"));
$("#upthumbimgname").val(button.data("name"));
$("#upthumbimgalt").val(button.data("alt"));
});
$("#thumbUp").on('submit',(function(e){
e.preventDefault();
if($("#thumbUp").valid()){
    var url="helper/master/room-gallery";
    var data = new FormData(this);
    data.append("operation","upGal");
    data.append("id","<?= $data->roomcat_id; ?>");
    $.ajax({
      type:"POST",url:url,data:data,contentType:false,cache:false,processData:false,dataType:"json",
      beforeSend: function(){$('.entrybtn').addClass('eventbtn');},
      error: function(res){$('.entrybtn').removeClass('eventbtn');showToast("Something Wrong Try Later","error");},
      success: function(res)
      {
        $('.entrybtn').removeClass('eventbtn');
        if(res.status){
          fetchCover();
          $("#upthumbimg").val('');
          showToast(res.msg,"success");
        }else {showToast(res.msg,"error");}
      }
    }); 
}else{return false;}
}));
$(".loadcover").delegate('.statusup',"click",function(){
  var id=$(this).data("id");
  var operation=$(this).data("operation");
  if (operation=="deletethumb"){
      swal({title: "Are you sure?",text: "Once deleted, you will not be able to recover",icon: "warning",buttons: true,dangerMode: true,
      }).then((willDelete)=>{if(willDelete){StatusUpdate(id,operation);}});
  }else {StatusUpdate(id,operation);} 
});
function StatusUpdate(id,type){
  var url = "helper/master/room-gallery";
  $.ajax({
    type:"POST",
    url:url,
    dataType:"json",
    data:{"id":id,"operation":type},
    beforeSend:function(){},
    error:function(res){$('.entrybtn').removeClass('eventbtn');showToast("Something Wrong Try Later","error");},
    success:function(res)
    { 
      if(res.status){fetchCover();showToast(res.msg,"success");}
      else{showToast(res.msg,"error");}
    }
  });
}
function fetchCover(){
  $.ajax({
    type: "POST",
    url: "helper/master/room-gallery",
    data: {"acmrid":<?php echo $data->roomcat_id; ?>,"operation":"fetchGal"},
    beforeSend: function(){},
    error: function(res){$('.loadcover').html('Something Wrong Try Later');},
    success: function(res){
      $('.loadcover').html(res);
      $('#entry_table_thumb').DataTable({stateSave:true});
      $(".loadcover tbody").sortable({
        axis: 'y',
          update:function(event,ui){
          var data =$(".loadcover tbody").sortable('toArray');
          $.ajax({
          data:{data:data,"operation":"orderThumb"},
          type:'POST',
          dataType:"json",
          url:'helper/master/room-gallery',
          success:function(res){if(res.status){fetchCover();showToast(res.msg,"success");}else{showToast(res.msg,"error");}}
          });
        }   
      });
    }
  }); 
}
});
</script>
</body>
</html>