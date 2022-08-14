<?php include '_auth.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Room No.</title>
<?php  include '_header.php'; ?>
</head>
<body> 
<?php  include '_menu.php'; ?>
<div class="row">
<div class="col-md-12">
<div class="card card-statistics h-100">
<div class="card-title">
<h5>Room No.<a href="" data-toggle="modal" data-target="#addMod" class="button x-small pull-right"><i class="fa fa-plus"></i> Add New</a></h5>
</div>
<div class="card-body">
<div class="loadpan"></div>
</div>
</div>
</div>        
</div>
<div class="modal fade" id="addMod" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<div class="modal-title"><h4>Add New Room No.</h4></div>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
<form action="" autocomplete="off" id="addfrm">
<div class="row">
<div class="form-group col-sm-12">
<label for="category">Select Room Name:<span class="req">*</span></label>
<select id="room" name="room" class="form-control" required="">
<option value="">-- Select Room --</option>
<?php
$qu=$PDO->prepare("SELECT roomcat_id,roomcat_name FROM room_category WHERE roomcat_status=1 ORDER BY roomcat_name ASC");
$qu->execute(); 
while ($desdet=$qu->fetch()){
echo '<option value="'.$desdet['roomcat_id'].'">'.$desdet['roomcat_name'].'</option>';
} ?>
</select> 
</div>
<div class="form-group col-sm-12">
<label for="roomnum">Enter Room No.:</label>
<input type="text" class="form-control" id="roomnum" name="roomnum" required="">
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
<div class="modal-title"><h4>Update Room No.</h4></div>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
<form autocomplete="off" id="upfrm">
<input type="hidden" class="form-control" id="uptid" name="uptid" required="">
<div class="form-group col-sm-12">
<label for="uproom">Select Room Name:<span class="req">*</span></label>
<select id="uproom" name="uproom" class="form-control" required="">
<option value="">-- Select Room --</option>
<?php
$qu=$PDO->prepare("SELECT roomcat_id,roomcat_name FROM room_category WHERE roomcat_status=1 ORDER BY roomcat_name ASC");
$qu->execute(); 
while ($desdet=$qu->fetch()){
echo '<option value="'.$desdet['roomcat_id'].'">'.$desdet['roomcat_name'].'</option>';
} ?>
</select> 
</div>
<div class="form-group col-sm-12">
<label for="uproomno">Enter Room No.:</label>
<input type="number" class="form-control" id="uproomno" name="uproomno" required="">
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
    var url="helper/master/room_cat";
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
          fetchData();
          $("#addfrm").trigger('reset');
          showToast(res.msg,"success");
        }else {showToast(res.msg,"error");}
      }
    }); 
}else{return false;}
})); 
$("#upMod").on('shown.bs.modal',function(e){
var button=$(e.relatedTarget);
$("#uptid").val(button.data("id"));
$("#uproom").val(button.data("name"));
$("#uproomno").val(button.data("url"));
});
$('#upfrm').validate({});
$("#upfrm").on('submit',(function(e){
e.preventDefault();
if($("#upfrm").valid()){
    var url="helper/master/room_cat";
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
        if(res.status){fetchData();showToast(res.msg,"success");}
        else{showToast(res.msg,"error");}
      }
    }); 
}else{return false;}
}));
$(".loadpan").delegate('.statusup',"click",function(){
  var id=$(this).data("id");
  var operation=$(this).data("operation");
  if (operation=="delete"){
      swal({title: "Are you sure?",text: "Once deleted, you will not be able to recover",icon: "warning",buttons: true,dangerMode: true,
      }).then((willDelete)=>{if(willDelete){StatusUpdate(id,operation);}});
  }else {StatusUpdate(id,operation);} 
});
function StatusUpdate(id,type){
  var url = "helper/master/room_cat";
  $.ajax({
    type:"POST",
    url:url,
    dataType:"json",
    data:{"id":id,"operation":type},
    beforeSend:function(){},
    error:function(res){$('.entrybtn').removeClass('eventbtn');showToast("Something Wrong Try Later","error");},
    success:function(res)
    { 
      if(res.status){fetchData();showToast(res.msg,"success");}
      else{showToast(res.msg,"error");}
    }
  });
}
$("#name").keyup(function(){$("#nameurl").val(convertToSlug($(this).val()));});
$("#upname").keyup(function(){$(this).val();$("#upnameurl").val(convertToSlug($(this).val()));});

function fetchData(){
  $.ajax({
    type: "POST",
    url: "helper/master/room_cat",
    data: {"operation":"fetch"},
    beforeSend: function(){},
    error: function(res){$('.loadpan').html('Something Wrong Try Later');},
    success: function(res)
    {
      $('.loadpan').html(res);
      $('#entry_table').DataTable({stateSave:true});
      $(".loadpan tbody").sortable({
        axis: 'y',
          update:function(event,ui){
          var data =$(".loadpan tbody").sortable('toArray');
          $.ajax({
          data:{data:data,"operation":"orderType"},
          type:'POST',
          dataType:"json",
          url:'helper/master/room_cat',
          success:function(res){if(res.status){fetchData();showToast(res.msg,"success");}else{showToast(res.msg,"error");}}
          });
        }   
      });
    }
  }); 
}
</script>
</body>
</html>