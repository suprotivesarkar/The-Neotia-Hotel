<?php include '_auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Dashboard</title>
<?php  include '_header.php'; ?>
<style type="text/css">
	.empimg {position: absolute; left: 115px; top: -1px;}
.empimg img{width: 85px; border-radius: 50px;}
</style>
</head>
<body> 
<?php  include '_menu.php'; ?>
<section>
<div class="container">
<div class="col-xl-4 mb-60 offset-4">
<div class="card-body">
<a href="../images/employee/<?php echo (!empty($chk_auth->user_img))?$chk_auth->user_img:'placeholder.png'; ?>" target="/"> <div class="empimg"><img src="../images/employee/<?php echo (!empty($chk_auth->user_img))?$chk_auth->user_img:'placeholder.png'; ?>" ></div></a>
</div>
</div>
<div class="col-xl-6 mb-30 offset-3">
<div class="card card-statistics h-100">
<div class="card-body">
<h5 class="card-title">Change Password
</h5>
<form action="" autocomplete="off" id="userfrm">
<div class="row">
<h5 class="card-title">User Name : <?php echo $chk_auth->user_name; ?></h5>
<h5 class="card-title">User Phone No. : <?php echo $chk_auth->user_phone; ?></h5>
<div class="form-group col-sm-12">
<label for="oldpass">Enter Current Password:<span class="req">*</span></label>
<input type="text" class="form-control" id="oldpass" name="oldpass" required="">
</div>
<div class="form-group col-sm-12">
<label for="newpass">Enter New Password:<span class="req">*</span></label>
<input type="text" class="form-control" id="newpass" name="newpass" required="">
</div>
<div class="form-group col-sm-12">
<button type="submit" class="button btn-block btn-lg entrybtn">Change Password<i class="fa fa-refresh fa-spin loading"></i></button>
</div>
</div>
</form>
</div>
<div id="sparkline2" class="scrollbar-x text-center"></div>
</div>
</div>
</div>
</section>
<?php  include '_footer.php'; ?>
<script type="text/javascript">
	$(document).ready(function(){
$("#userfrm").on('submit',(function(e){
e.preventDefault();
if($("#userfrm").valid()){
    var url="helper/master/setting";
    var data = new FormData(this);
    data.append("operation","updatepass");
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
          $("#userfrm").trigger('reset');
          showToast(res.msg,"success"); 
          setTimeout(function(){location.href ="logout";},300);
        }else {showToast(res.msg,"error");}
      }
    }); 
}else{return false;}
})); 
});
</script>
</body>
</html>