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
<title>Employee Details View</title>
<?php include '_header.php'; ?>
<style type="text/css">
table.text-left thead tr th, table.text-left tbody tr td {text-align: left!important;}
.faclist li{padding: 5px;background: #e2e2e2;margin: 2px;color: #fff;font-weight: 600;border-radius: 3px;background-image: linear-gradient(to right, #b8cbb8 0%, #b8cbb8 0%, #c17a9e 0%, #e260b4 33%, #ee609c 66%, #ee609c 100%);}
.includelist li{padding: 2px 8px;background: #2e7ba9;margin: 3px 2px;border-radius: 5px;color: #fff;font-weight: 600;}
.calbtn{margin-left:15px!important;width:100%!important;padding:10px 25px!important;}
.calbkpan{position:relative;margin-top:5px}.calbkpan input{height:42px;width:100%;padding-right:43px;padding-left:10px;}
.calbkpan button{position:absolute;right:0;top:0;background:#0b7bbd;outline:none;border:none;color:#fff;height:42px;width:40px;padding:0;border-radius:0px;}
.calbkpan button .fa{font-size:22px}
.empimg {position: absolute; left: 14px; top: 1px;}
.empimg img{width: 70px; border-radius: 50px;}
.empdet{margin-left: 110px;}
</style>
</head>  
<body>
<?php  include '_menu.php'; ?>
<div class="row">
<div class="col-md-12">
<div class="card card-statistics mb-2">
<div class="card-title">
	<a href="../images/employee/<?php echo (!empty($data->user_img))?$data->user_img:'placeholder.png'; ?>" target="/"> <div class="empimg"><img src="../images/employee/<?php echo (!empty($data->user_img))?$data->user_img:'placeholder.png'; ?>" ></div></a>
<div class="empdet"><h5>EMPLOYEE DETAILS - <span class="text-danger bold"><?php echo $data->user_name; ?></span> <a href="employee-list" class="button x-small pull-right"><i class="fa fa-long-arrow-left"></i> Back</a> </h5>
<h5>EMPLOYEE CATEGORY - <span class="text-primary bold"><tr><td><?php  echo $categoryname;?></td><tr></span></h5>
</div>
</div>
<div class="card-body">
<ul class="nav nav-tabs thmnavtab">
<li class="nav-item"><a class="nav-link active" href="employee-view?id=<?php echo $data->user_id; ?>">BRIEF INFO</a></li>
<li class="nav-item"><a class="nav-link" href="emp-document?id=<?php echo $data->user_id; ?>">EMPLOYEE DOCUMENTS</a></li>
<li class="nav-item"><a class="nav-link" href="emp-payment?id=<?php echo $data->user_id; ?>">PAYMENT DETAILS </a></li>
</ul>
<div class="table-responsive">          
<table class="table table-hover table-bordered text-left">
<tbody>
<tr>
<td>EMPLOYEE CODE</td>
<td><?php echo $data->user_code; ?></td>
</tr>
<tr>
<td>NAME</td>
<td><?php echo $data->user_name; ?></td>
</tr>
<tr>
<td>PHONE NO.</td>
<td><?php echo $data->user_phone; ?></td>
</tr>
<tr>
<td>EMAIL ID</td>
<td><?php echo $data->user_email; ?></td>
</tr>
<tr>
<td>RESIDENTIAL ADDRESS</td>
<td><?php echo $data->user_address; ?></td>
</tr>
<tr>
<td>DATE OF BIRTH</td>
<td><?php echo(date ("d-m-Y",strtotime("$data->user_dob"))); ?></td>
</tr>
<tr>
<td>JOINING DATE</td>
<td><?php echo(date ("d-M-Y",strtotime("$data->user_join"))); ?></td>
</tr>
<tr>
<td>LEAVING DATE</td>
<td><?php echo(date ("d-M-Y",strtotime("$data->user_leaving"))); ?></td>
</tr>
</tbody>
</table>
</div>
<?php  include '_footer.php'; ?>
</body>
</html>