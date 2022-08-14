<?php include '_auth.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Testimonials</title>
<?php  include '_header.php'; ?>
</head>
<body> 
<?php  include '_menu.php'; ?>
<div class="row">
<div class="col-md-12">
<div class="card card-statistics h-100">
<div class="card-title">
<h5>Testimonial List <a href="" data-toggle="modal" data-target="#addMod" class="button x-small pull-right"><i class="fa fa-plus"></i> Add New</a></h5>
</div>
<div class="card-body">
<div class="loadpan"></div>
</div>
</div>
</div>				
</div>
<div class="modal fade" id="addMod" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
<div class="modal-header">
<div class="modal-title"><h4>Add New Testimonial</h4></div>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
<form action="" autocomplete="off" id="addfrm">
<div class="row">
<div class="form-group col-sm-6">
<label for="name">Enter Name:</label>
<input type="text" class="form-control" id="name" name="name" required="" autofocus="">
</div>
<div class="form-group col-sm-6">
<label for="img">Select Image:[90X90]</label>
<input type="file" class="form-control" id="img" name="image">
</div>
<div class="form-group col-sm-12">
<label for="msg">Message:</label>
<textarea class="form-control" rows="5" id="msg" name="msg" required=""></textarea>
</div>
<div class="form-group col-12">
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
<div class="modal-title"><h4>Update Testimonials</h4></div>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
<form autocomplete="off" id="upfrm">
<input type="hidden" class="form-control" id="uptid" name="uptid" required="">
<div class="row">
<div class="form-group col-sm-6">
<label for="upname">Enter Name:</label>
<input type="text" class="form-control" id="upname" name="upname" required="" autofocus="">
</div>
<div class="form-group col-sm-6">
<label for="upimg">Select Image:</label>
<input type="file" class="form-control" id="upimg" name="image">
</div>
<div class="form-group col-sm-12">
<label for="upmsg">Message:</label>
<textarea class="form-control" rows="5" id="upmsg" name="upmsg" required=""></textarea>
</div>
<div class="form-group col-12">
<button type="submit" class="button btn-block btn-lg entrybtn">Update Now <i class="fa fa-refresh fa-spin loading"></i></button>
</div>
</div>
</form>
</div>
</div>
</div>
</div>
<?php  include '_footer.php'; ?>
<script type="text/javascript" src="assets/js/datepicker.js"></script>
<script type="text/javascript">
fetchData();
$.validator.messages.required = '';
$('#addfrm').validate({});
$("#addfrm").on('submit',(function(e){
e.preventDefault();
if($("#addfrm").valid()){
    var url="helper/master/testimonial";
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
      beforeSend: function(){$('.loading').show();},
      error: function(res){$('.loading').hide();showToast("Something Wrong Try Later","error");},
      success: function(res)
      {
        $('.loading').hide();
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
$("#upname").val(button.data("name"));
$("#upmsg").val(button.data("txt"));
});
$('#upfrm').validate({});
$("#upfrm").on('submit',(function(e){
e.preventDefault();
if($("#upfrm").valid()){
    var url="helper/master/testimonial";
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
      beforeSend: function(){$('.loading').show();},
      error: function(res){$('.loading').hide();showToast("Something Wrong Try Later","error");},
      success: function(res)
      {
        $('.loading').hide();
        if(res.status){fetchData();showToast(res.msg,"success");$("#upimg").val('');}
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
  var url = "helper/master/testimonial";
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
function fetchData(){$.post("helper/master/testimonial",{"operation":"fetch"},function(data){$(".loadpan").html(data);$('#entry_table').DataTable({stateSave:true});});}
</script>
</body>
</html>