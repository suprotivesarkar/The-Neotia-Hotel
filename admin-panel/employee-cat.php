<?php include '_auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Employee Category</title>
<?php  include '_header.php'; ?>
</head>
<body> 
<?php  include '_menu.php'; ?>
<div class="row">
<div class="col-md-12">
<div class="card card-statistics h-100">
<div class="card-title">
<h5> Employee Category List <a href="" data-toggle="modal" data-target="#addMod" class="button x-small pull-right"><i class="fa fa-plus"></i> Add New</a></h5>
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
<div class="modal-title"><h4>Add Employee Category</h4></div>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
<form action="" autocomplete="off" id="addfrm">
<div class="row">
<div class="form-group col-sm-12">
<label for="name">Enter Employee Category :</label>
<input type="text" class="form-control" id="name" name="name" required="">
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
<div class="modal-title"><h4>Update Category</h4></div>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
<form autocomplete="off" id="upfrm">
<input type="hidden" class="form-control" id="uptid" name="uptid" required="">
<div class="form-group col-sm-12">
<label for="upname">Enter Updated Category Name:</label>
<input type="text" class="form-control" id="upname" name="upname" required="">
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
<script type="text/javascript" src="assets/js/croppie.js"></script>
<script type="text/javascript">
fetchData();
$('#pname').on('input',function(){if(!$('#pname').val()){$('#pid').val('');}});
url = 'helper/search/search_category';
$("#pname").autocomplete({
  source: function(request,response){
      $.ajax({
          url:url,
          dataType:"json",
          data:{term:request.term},
          success:function(data){response(data);}
      });
  },
  minLength:0,
  limit:2,
  autoFocus:true,
  select: function(event,ui){
    $('#pname').val(ui.item.value);
    $('#pid').val(ui.item.id);
    return false;
  } 
}).click(function(){$(this).autocomplete('search');});
$('#uppname').on('input',function(){if(!$('#uppname').val()){$('#uppid').val('');}});
url = 'helper/search/search_category';
$("#uppname").autocomplete({
  source: function(request,response){
      $.ajax({
          url:url,
          dataType:"json",
          data:{term:request.term},
          success:function(data){response(data);}
      });
  },
  minLength:0,
  limit:2,
  autoFocus:true,
  select: function(event,ui){
    $('#uppname').val(ui.item.value);
    $('#uppid').val(ui.item.id);
    return false;
  } 
}).click(function(){$(this).autocomplete('search');});
$.validator.messages.required = '';
$('#addfrm').validate({});
$("#addfrm").on('submit',(function(e){
e.preventDefault();
if($("#addfrm").valid()){
    var url="helper/master/emp_category";
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
          $("#name").val('');
          $("#nameurl").val('');
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
$("#upnameurl").val(button.data("url"));
$("#uppname").val(button.data("pname"));
$("#uppid").val(button.data("pid"));
});
$('#upfrm').validate({});
$("#upfrm").on('submit',(function(e){
e.preventDefault();
if($("#upfrm").valid()){
    var url="helper/master/emp_category";
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
  var url = "helper/master/emp_category";
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
$("#name").keyup(function(){$("#nameurl").val(convertToSlug($(this).val()));});
$("#upname").keyup(function(){$(this).val();$("#upnameurl").val(convertToSlug($(this).val()));});
function fetchData(){$.post("helper/master/emp_category",{"operation":"fetch"},function(data){$(".loadpan").html(data);$('#entry_table').DataTable({stateSave:true});});}
</script>
</body>
</html>