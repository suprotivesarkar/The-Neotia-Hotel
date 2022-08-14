<?php include '_auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Room Type</title>
<?php  include '_header.php'; ?>
<style type="text/css">
table.text-left thead tr th, table.text-left tbody tr td {text-align: left!important;}
.text-info {
    color: #084796!important;
}
</style>
</head> 
<body> 
<?php  include '_menu.php'; ?>
<div class="row">
<div class="col-md-12"> 
<div class="card card-statistics h-100">

<div class="card-body">

<div class="row">
<div class="col-xl-12">
<div class="card card-statistics h-100">
<div class="card-title"><h5>Room Type<a href="" data-toggle="modal" data-target="#addMod" class=" x-small pull-right"><i class="fa fa-plus"></i></a></h5></div>
<div class="card-body">
<div class="loadroom"></div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>        
</div>
<div class="modal fade" id="addMod" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
<div class="modal-header">
<div class="modal-title"><h4>Add New Room Type</h4></div>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
<form id="addfrm" autocomplete="off" enctype="multipart/form-data">
<div class="row">
<div class="form-group col-sm-12">
<label for="roomname">Enter Room Name:<span class="req">*</span></label>
<input type="text" class="form-control" id="roomname" name="roomname" required="" autofocus="">
</div>
<div class="form-group col-sm-12">
<label for="roomurl">Enter URL:</label>
<input type="text" class="form-control" id="roomurl" name="roomurl" required="">
</div>
<div class="form-group col-sm-3">
<label for="roomtype">Room Type:<span class="req">*</span></label>
<select name="roomtype" class="form-control valid" id="roomtype" aria-invalid="false">
<option value="1">Non AC</option>
<option value="2">AC</option>
</select>
</div>
<div class="form-group col-sm-2">
<label for="maxadult">Max Adult:</label>
<input type="number" class="form-control" id="maxadult" name="maxadult" required="">
</div>
<div class="form-group col-sm-2">
<label for="maxchild">Max Child:</label>
<input type="number" class="form-control" id="maxchild" name="maxchild" required="">
</div>
<div class="form-group col-sm-3">
<label for="rmsprice">Room Starting Price:</label>
<input type="number" class="form-control" id="rmsprice" name="rmsprice" required="">
</div>
<div class="form-group col-sm-2">
<label for="extrabedcost">Extra Bed Cost:</label>
<input type="number" class="form-control" id="extrabedcost" name="extrabedcost" required="">
</div>
<!-- <div class="form-group col-sm-2">
<label for="extratax">Tax:</label>
<input type="number" class="form-control" id="extratax" name="extratax" required="">
</div> -->
<div class="form-group col-sm-12">
<button type="submit" class="button btn-block btn-lg entrybtn">Add Now</button>
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
fetchRooms();
$.validator.messages.required = '';
$('#addfrm').validate({});
$("#addfrm").on('submit',(function(e){
e.preventDefault();
if($("#addfrm").valid()){
    var url="helper/master/room";
    var data = new FormData(this);
    data.append("operation","addRoom");
    $.ajax({
      type:"POST",url:url,data:data,contentType:false,cache:false,processData:false,dataType:"json",
      beforeSend: function(){$('.entrybtn').addClass('eventbtn');},
      error: function(res){$('.entrybtn').removeClass('eventbtn');showToast("Something Wrong Try Later","error");},
      success: function(res)
      {
        $('.entrybtn').removeClass('eventbtn');
        if(res.status){
          fetchRooms();
          $("#addfrm").trigger('reset');
          showToast(res.msg,"success");
        }else {showToast(res.msg,"error");}
      }
    }); 
}else{return false;}
})); 
$(".loadroom").delegate('.statusup',"click",function(){
  var id=$(this).data("id");
  var operation=$(this).data("operation");
  if (operation=="deleteroom"){
      swal({title: "Are you sure?",text: "Once deleted, you will not be able to recover",icon: "warning",buttons: true,dangerMode: true,
      }).then((willDelete)=>{if(willDelete){StatusUpdate(id,operation);}});
  }else {StatusUpdate(id,operation);} 
});
function StatusUpdate(id,type){
  var url = "helper/master/room";
  $.ajax({
    type:"POST",
    url:url,
    dataType:"json",
    data:{"id":id,"operation":type},
    beforeSend:function(){},
    error:function(res){$('.entrybtn').removeClass('eventbtn');showToast("Something Wrong Try Later","error");},
    success:function(res)
    { 
      if(res.status){fetchRooms();showToast(res.msg,"success");}
      else{showToast(res.msg,"error");}
    }
  });
}
$("#roomname").keyup(function(){$("#roomurl").val(convertToSlug($(this).val()));});
$("#upname").keyup(function(){$(this).val();$("#upnameurl").val(convertToSlug($(this).val()));});
function fetchRooms(){
  $.ajax({
    type: "POST",
    url: "helper/master/room",
    data: {"operation":"fetchRooms"},
    beforeSend: function(){},
    error: function(res){$('.loadroom').html('Something Wrong Try Later');},
    success: function(res)
    {
      $('.loadroom').html(res);
      $('#entry_table_room').DataTable({stateSave:true});
      $(".loadroom tbody").sortable({
        axis: 'y',
          update:function(event,ui){
          var data =$(".loadroom tbody").sortable('toArray');
          $.ajax({
          data:{data:data,"operation":"orderRoomList"},
          type:'POST',
          dataType:"json",
          url:'helper/master/room',
          success:function(res){if(res.status){fetchRooms();showToast(res.msg,"success");}else{showToast(res.msg,"error");}}
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