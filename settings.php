<?php 
include '_top.php';
if(!isset($_SESSION['userid'])){header("Location:./");die();}
$memdet=UserDetails($_SESSION['userid']);
if (empty($memdet)){header("Location:./");}
$memberid = $memdet['mem_id'];
$memname  = $memdet['mem_name'];
$mememail  = $memdet['mem_email'];
$memphn  = $memdet['mem_phone'];
$mempass = $memdet['mem_password'];
?> 
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>The Neotia Hotel - Settings</title>
<?php include '_header.php'; ?>
<style type="text/css">
    .mb-10{
    margin-bottom: 10px!important;
  }
.panel-heading h4 a{
	color: white;
	float: right;
    position: relative;
    top: 142px;
    left: 10px;
    padding: 4px;
    background: #e49a15;
    border-radius: 4px;
}
</style>
</head>
<body>
<?php include '_menu.php'; ?>

<div class="page-title-area">
<div class="container">
<div class="page-title-content">
<h1>Settings</h1>
<div class="breadcrumb-inn">
<ol itemscope itemtype="http://schema.org/BreadcrumbList" class="">
<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
<a itemprop="item" href="<?php echo $root; ?>">
<span itemprop="name">Home</span></a>
<meta itemprop="position" content="1" />
</li>
<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
<a itemprop="item" href="<?php echo $actual_link; ?>" class="active">
<span itemprop="name">Settings</span></a>
<meta itemprop="position" content="2" />
</li>
</ol>
</div>
</div>
</div>
</div>

<section id="content">
<div id="content-wrap">
<div id="content-main" class="section-flat page-single page-dashboard">
<div class="section-content">
<div class="container">
<div class="row">
<div class="col-md-12">
<div class="page-single-content">
<div class="row">
<div class="col-md-12">
<div class="content">
<div class="row">
<div class="col-md-3 col-sm-4">
<ul class="list-dashboard-pages">
<li><a href="profile"><i class="fa fa-user"></i>Personal Profile</a></li>
<li><a href="my-bookings"><i class="fa fa-clipboard"></i>My Bookings</a></li>
<li class="active"><a href="settings"><i class="fa fa-cogs"></i>Settings</a></li>
<li class="user-logout"><a href="logout"><i class="fa fa-sign-out"></i>Logout</a></li>
</ul>
</div>
<div class="col-md-9 col-sm-8">
<div class="panel panel-default">
<div class="panel-heading">
<h4>Settings <a href="#" data-bs-toggle="modal" data-bs-target="#addMod"><i class="fa fa-pencil"></i></a></h4> 
</div>
<div class="panel-body">
<div class="table-responsive">          
<table class="table table-bordered table-hover">
<tbody>
<tr>
<td>Phone</td>
<td><?php echo $memphn; ?></td>
</tr>
<tr>
<td>Email ID</td>
<td><?php echo $mememail; ?></td>
</tr>
<tr>
<td>Password</td>
<td><?php echo $mempass; ?></td>
</tr>

</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
<div class="modal fade" id="addMod" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
<div class="modal-header">
<div class="modal-title"><h4>Update Details/ Password</h4></div>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="margin-top: -21px;"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
<form action="" autocomplete="off" id="settingfrm">
<div class="row">
<div class="form-group col-sm-6 mb-10">
<label for="phnno">Phone No.:</label>
<input type="number" class="form-control" id="phnno" name="phnno" value="<?php echo $memphn; ?>">
</div>
<div class="form-group col-sm-6 mb-10">
<label for="emails">Email ID:</label>
<input type="text" class="form-control" id="emails" name="emails" value="<?php echo $mememail; ?>">
</div>
<div class="form-group col-sm-6 mb-10">
<label for="password">Password:</label>
<input type="text" class="form-control" id="password" name="password" value="<?php echo $mempass; ?>">
</div>
<div class="form-group col-sm-12 mb-10">
<button type="submit" class="button btn-block btn-lg entrybtn col-12 btn-warning">Update Now <i class="fa fa-refresh loading"></i></button>
</div>
<div class="rh col-xs-12 outmsg"></div>
</div>
</form>
</div>
</div>
</div>
</div> 

<?php include '_footer.php'; ?>
<script type="text/javascript">
$("#settingfrm").on('submit',(function(e){
e.preventDefault();
    var url="_auth";
    var data = new FormData(this);
    data.append("operation","updateset");
    $.ajax({
      type:"POST",url:url,data:data,contentType:false,cache:false,processData:false,dataType:"json",
     beforeSend: function(){$('.entrybtn').addClass('is-loading');},
      error: function(res){$('.entrybtn').removeClass('is-loading');},
      success: function(res)
      {
        $(".outmsg").html(res.msg);
 		   if(res.status){
          location.reload();
          $("#updoc").val('');
          showToast(res.msg,"success");
        }else {showToast(res.msg,"error");}
      }
    }); 
})); 
</script>
</body>
</html>