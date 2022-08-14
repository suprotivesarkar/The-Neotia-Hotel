<?php include '_auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Login Activity</title>
<?php  include '_header.php'; ?>
<style type="text/css">
.dataTables_processing{box-shadow: 0 1px 1px rgba(0,0,0,.05);background: transparent;border:none;}
.dataTables_processing img{height:40px;}
.minhight{min-height:100vh}
#filterload{position:fixed;z-index:1000;top:0;left:0;height:100%;width:100%;background:rgba(255,255,255,0.4) url('assets/img/spiner.gif') 50% 50% no-repeat;background-size:50px;display:none}
.successbold{font-weight:600;color:green}
</style>
</head>
<body>   
<?php  include '_menu.php'; ?>
<div id="filterload"></div>
<div class="row">
<div class="col-md-12">
<div class="card card-statistics">
<div class="card-title">
<h5>Login Activity</h5>
</div>
<div class="card-body">
<div class="table-responsive minhight">
<table class="table table-striped table-bordered table-hover " id="entry_table">
<thead>
<tr>
<th>#</th>
<th>USER NAME</th> 
<th>PASSWORD</th>
<th>ACTIVITY TIME</th>
<th>DATE</th>
<th>ACTIVITY STATUS</th>
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
  "oLanguage":{sProcessing:"<img class='reloadimg' src='assets/img/spiner.gif'>","sEmptyTable": "Sorry..No Record Found"},
  "fnCreatedRow": function( nRow, aData, iDataIndex ) {
    $(nRow).attr('id', "row"+aData[0]);
  },
  "ajax":{
    url :"helper/master/login_activity", 
    type:"post",
    data:{"operation":"fetch","sdate":$("#sdate").val(),"edate":$("#edate").val()},
    error: function(){ $("#entry_table").css("display","none");}
  }
});
$(".loadpan").delegate('.copybtn',"click",function(){
  var id=$(this).data("id");
  $.ajax({
    type:"POST",
    url:"helper/master/login_activity", 
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
  if (operation=="delete"){
      swal({title: "Are you sure?",text: "Once deleted, you will not be able to recover",icon: "warning",buttons: true,dangerMode: true,
      }).then((willDelete)=>{if(willDelete){StatusUpdate(id,operation);}});
  }else {StatusUpdate(id,operation);} 
});
function StatusUpdate(id,type){
  var url = "helper/master/login_activity";
  $.ajax({
    type:"POST",
    url:url,
    dataType:"json",
    data:{"id":id,"operation":type},
    beforeSend:function(){},
    error:function(res){showToast("Something Wrong Try Later","error");},
    success:function(res)
    { 
      if(res.status){table.ajax.reload();showToast(res.msg,"success");}
      else{showToast(res.msg,"error");}
    }
  });
}
</script>
</body>
</html>