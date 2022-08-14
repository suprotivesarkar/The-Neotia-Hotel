<?php include '_auth.php';
$id=FilterInput($_GET['id']);
if(!is_numeric($id)){include '404.php';die();}
$data = CheckExists("users_list","user_id = '$id' AND user_status<>2");
if(empty($data)){include '404.php';die();}

$categoryname = null;
if (!empty($data->user_cat_id_ref)) {
  $itlist = explode(',', $data->user_cat_id_ref);
  $categoryname ;
  foreach ($itlist as $itinc) {
    $category_chk = CheckExists("emp_cat","cat_id = '$itinc' AND cat_status<>2");
    if(!empty($category_chk)){
      $name = $category_chk->cat_name;
      $categoryname =$name;
    }
  }
}
?> 

<!DOCTYPE html>
<html lang="en"> 
<head>
<title>Employee Documents</title>
<?php include '_header.php'; ?>
<style type="text/css">
table.text-left thead tr th, table.text-left tbody tr td {text-align: left!important;}
.faclist li{padding: 5px;background: #e2e2e2;margin: 2px;color: #fff;font-weight: 600;border-radius: 3px;background-image: linear-gradient(to right, #b8cbb8 0%, #b8cbb8 0%, #c17a9e 0%, #e260b4 33%, #ee609c 66%, #ee609c 100%);}
.includelist li{padding: 2px 8px;background: #2e7ba9;margin: 3px 2px;border-radius: 5px;color: #fff;font-weight: 600;}
.calbtn{margin-left:15px!important;width:100%!important;padding:10px 25px!important;}
.calbkpan{position:relative;margin-top:5px}.calbkpan input{height:42px;width:100%;padding-right:43px;padding-left:10px;}
.calbkpan button{position:absolute;right:0;top:0;background:#0b7bbd;outline:none;border:none;color:#fff;height:42px;width:40px;padding:0;border-radius:0px;}
.calbkpan button .fa{font-size:22px}
</style>
</head> 
<body>
<?php  include '_menu.php'; ?>
<div class="row">
<div class="col-md-12">
<div class="card card-statistics mb-2">
<div class="card-title">
<h5>EMPLOYEE DETAILS - <span class="text-danger bold"><?php echo $data->user_name; ?></span> <a href="employee-list" class="button x-small pull-right"><i class="fa fa-long-arrow-left"></i> Back</a> </h5>
<h5>EMPLOYEE CATEGORY :- <span class="text-primary bold"><tr><td><?php  echo $categoryname;?></td><tr></span></h5>
</div>
<div class="card-body">
 <ul class="nav nav-tabs thmnavtab">
<li class="nav-item"><a class="nav-link" href="employee-view?id=<?php echo $data->user_id; ?>">BRIEF INFO</a></li>
<li class="nav-item"><a class="nav-link active" href="emp-document?id=<?php echo $data->user_id; ?>">EMPLOYEE DOCUMENTS</a></li>
<li class="nav-item"><a class="nav-link" href="emp-Payment?id=<?php echo $data->user_id; ?>">PAYMENT DETAILS </a></li>
</ul>
<div class="row">
<div class="col-md-12">
<div class="card card-statistics h-100">
<div class="card-title">
<h5>Employee Documents 
<a href="" data-toggle="modal" data-target="#addGallery" class="button x-small pull-right"><i class="fa fa-plus"> ADD DOCUMENT</i></a>
</h5>
</div>
<div class="card-body">
<div class="loadgallery"></div>
</div>
</div>
</div>        
</div>
</div>
</div>
</div>
</div>
<div class="modal fade" id="addGallery" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<div class="modal-title"><h4>Add New Document</h4></div>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
<form id="galadfrm" autocomplete="off" enctype="multipart/form-data">
<div class="row">
<div class="form-group col-sm-12">
<label for="galimgname">Document Name:</label>
<input type="text" class="form-control" id="galimgname" name="galimgname">
</div>
<div class="form-group col-sm-12">
<label for="docfile">Select File:</label>
<input type="file" class="form-control" id="docfile" name="docfile" required="">
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
<div class="modal fade" id="galup" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<div class="modal-title"><h4>Update Document </h4></div>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
<form id="galupfrm" autocomplete="off" enctype="multipart/form-data">
<input type="hidden" class="form-control" id="gaiid" name="gaiid">
<div class="row">
<div class="form-group col-sm-12">
<label for="galimgnameup">Document Name:</label>
<input type="text" class="form-control" id="galimgnameup" name="galimgnameup">
</div>
<div class="form-group col-sm-12">
<label for="galimgup">Select File:</label>
<input type="file" class="form-control" id="galimgup" name="updoc">
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
fetchGallery();
$(".fetchgallerybtn").on("click",function(){fetchGallery();});
function fetchGallery(){
  $.ajax({
    type: "POST",
    url: "helper/master/employee_doc",
    data: {"operation":"fetchGallery","id":"<?php echo $id ?>"},
    beforeSend: function(){},
    error: function(res){$('.loadgallery').html('Something Wrong Try Later');},
    success: function(res) 
    {
      $('.loadgallery').html(res);
      $('#entry_table_gallery').DataTable({stateSave:true});
      $(".loadgallery tbody").sortable({
        axis: 'y',
          update:function(event,ui){
          var data =$(".loadgallery tbody").sortable('toArray');
          $.ajax({
          data:{data:data,operation:"orderGalleryImg"},
          type:'POST',
          dataType:"json",
          url:'helper/master/employee_doc',
          success:function(res){if(res.status){fetchGallery();showToast(res.msg,"success");}else{showToast(res.msg,"error");}}
          });
        }   
      });
    }
  }); 
}
$.validator.messages.required = '';
$('#galadfrm').validate({});
$("#galadfrm").on('submit',(function(e){
e.preventDefault();
if($("#galadfrm").valid()){
    var url="helper/master/employee_doc";
    var data = new FormData(this);
    data.append("operation","addgalleryImg");
    data.append("id","<?php echo $id; ?>");
    $.ajax({
      type:"POST",url:url,data:data,contentType:false,cache:false,processData:false,dataType:"json",
      beforeSend: function(){$('.entrybtn').addClass('eventbtn');},
      error: function(res){$('.entrybtn').removeClass('eventbtn');showToast("Something Wrong Try Later","error");},
      success: function(res)
      {
        $('.entrybtn').removeClass('eventbtn');
        if(res.status){
          fetchGallery();
          $("#galadfrm").trigger('reset');
          $("#galimg").val('');
          showToast(res.msg,"success");
        }else {showToast(res.msg,"error");}
      }
    }); 
}else{return false;}
})); 
$("#galup").on('shown.bs.modal',function(e){
  var button=$(e.relatedTarget);
  $("#gaiid").val(button.data("id"));
  $("#galimgnameup").val(button.data("name"));
});
$.validator.messages.required = '';
$('#galupfrm').validate({});
$("#galupfrm").on('submit',(function(e){
e.preventDefault();
if($("#galupfrm").valid()){
    var url="helper/master/employee_doc";
    var data = new FormData(this);
    data.append("operation","updategalleryImg");
    data.append("id","<?php echo $id; ?>");
    $.ajax({
      type:"POST",url:url,data:data,contentType:false,cache:false,processData:false,dataType:"json",
      beforeSend: function(){$('.entrybtn').addClass('eventbtn');},
      error: function(res){$('.entrybtn').removeClass('eventbtn');showToast("Something Wrong Try Later","error");},
      success: function(res)
      {
        $('.entrybtn').removeClass('eventbtn');
        if(res.status){
          fetchGallery();
          $("#galupfrm").trigger('reset');
          $("#galimgup").val('');
          showToast(res.msg,"success");
        }else {showToast(res.msg,"error");}
      }
    }); 
}else{return false;}
})); 
$(".loadgallery").delegate('.statusupgal',"click",function(){
  var id=$(this).data("id");
  var operation=$(this).data("operation");
  if (operation=="deletegal"){
      swal({title: "Are you sure?",text: "Once deleted, you will not be able to recover",icon: "warning",buttons: true,dangerMode: true,
      }).then((willDelete)=>{if(willDelete){StatusUpdate(id,operation);}});
  }else {StatusUpdate(id,operation);} 
});
function StatusUpdate(id,type){
  var url = "helper/master/employee_doc";
  $.ajax({
    type:"POST",
    url:url,
    dataType:"json",
    data:{"id":id,"operation":type},
    beforeSend:function(){},
    error:function(res){$('.loading').hide();showToast("Something Wrong Try Later","error");},
    success:function(res)
    { 
      if(res.status){fetchGallery();showToast(res.msg,"success");}
      else{showToast(res.msg,"error");}
    }
  });
}
 
</script>
</body>
</html>