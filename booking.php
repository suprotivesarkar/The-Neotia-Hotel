<?php 
include '_top.php'; 
if(!isset($_SESSION['userid'])){header("Location:./");die();}
$memdet=UserDetails($_SESSION['userid']);
if (empty($memdet)){header("Location:./");}
$memberid = $memdet['mem_id'];
$memname  = $memdet['mem_name'];
$memphone = $memdet['mem_phone'];
$mememail = $memdet['mem_email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>The Neotia Hotel - Booking</title>
<?php include '_header.php'; ?>
<style type="text/css">
	.mb-10{
		margin-bottom: 10px!important;
	}
	.eachhigh::before {
		border-top: 15px solid #d27313!important;
	}
	 .flatpickr-day.selected,.flatpickr-day.selected:hover{
        background: #d5a677;
    border-color: #d5a677;
    }
    .flatpickr-day.nextMonthDay{
    	color: rgb(58 57 57);
    }
	.arrowhide::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type=number] {
  -moz-appearance: textfield;
}
label {}
.counter-add-item {
    margin: 0;
}
.counter-add-item a {
display: inline-block;
    padding: 0px 6px;
    font-size: 20px;
    font-weight: 600;
    border: 1px solid #62cff3;
    border-radius: 999px;
    color: blue;
    background: #fff;
}
.counter-add-item input[type="text"] {
      width: 55px;
    margin: 0 10px;
    padding: 0;
    font-size: 16px;
    font-weight: 600;
    text-align: center;
    border-radius: 71px;
    border-color: #62cff3;
}
.label.label-info {
    background: #156315!important;
}
.custom-control-label::after{position:absolute;top:0rem;left:20px;display:block;width:1.5rem;height:1.5rem;content:"";background-repeat:no-repeat;background-position:center center;background-size:64% 100%}
.custom-control-label::before{position:absolute;top:0rem;left:20px;display:block;width:1.5rem;height:1.5rem;pointer-events:none;content:"";-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;background-color:#dee2e6}
.custom-control{position:relative;display:block;min-height:1.5rem;padding-left:3.5rem}
.desnm{font-weight:600;color:red}
.tolvalue{font-size: 20px; color:green;}
.duevalue{font-size: 17px; color:red;}
.eachhigh{position:relative; margin-left: 20px;}
.eachhigh::before{position:absolute;content:'';width:0;height:0;border-top:15px solid red;border-right:15px solid transparent;left:-20px;top:0}
.card{margin-bottom:8px}
.input-group-addon {
background: #84ba3f;}
.roomned{
	text-align: center;
}
table td{
	font-size: 16px;
	font-weight: 600;
	color: #8a4908;
}
.card-title h4{
font-size: 30px;
}
.rmar{
	margin-top: 20px;
	margin-bottom: 20px;
}
#gdetail{
	display: none;
}
.outmsg .alert{
margin-top: 10px;
}
@media(min-width: 420px){
.roomned {
    margin-left: 419px;
}
}
@media(max-width: 420px){
	.btn-pad{
		padding: .5rem 2.7rem!important;
	}
	table td{
	font-size: 14px!important;}
}
.entrybtn.is-loading{color:transparent!important;pointer-events:none;position:relative}
.entrybtn.is-loading::after{position:absolute;left:calc(50% - (1em / 2));top:calc(50% - (1em / 2));position:absolute!important;-webkit-animation:spinAround .5s infinite linear;animation:spinAround .5s infinite linear;border:2px solid #dbdbdb;border-radius:290486px;border-right-color:transparent;border-top-color:transparent;content:"";display:block;height:1em;position:relative;width:1em}
@keyframes spinAround {
from{-webkit-transform:rotate(0);transform:rotate(0)}
to{-webkit-transform:rotate(359deg);transform:rotate(359deg)}
}
</style>
</head>
<body>
<?php include '_menu.php'; ?>

<div class="page-title-area">
<div class="container">
<div class="page-title-content">
<h1>Booking</h1>
<div class="breadcrumb-inn">
<ol itemscope itemtype="http://schema.org/BreadcrumbList" class="">
<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
<a itemprop="item" href="<?php echo $root; ?>">
<span itemprop="name">Home</span></a>
<meta itemprop="position" content="1" />
</li>
<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
<a itemprop="item" href="<?php echo $actual_link; ?>" class="active">
<span itemprop="name">Booking</span></a>
<meta itemprop="position" content="2" />
</li>
</ol>
</div>
</div>
</div>
</div>

<div class="container">
<div class="row rmar">
<div class="col-md-12">
<form id="bookin" autocomplete="off" enctype="multipart/form-data">
<div class="card card-statistics">
<div class="card-title eachhigh">
<h4>Basic Info</h4>
</div>
<div class="card-body">
<div class="row">
  <div class="form-group col-sm-3 mb-10">
<label for="checkin">Check In:<span class="req">*</span></label>
<input type="text" class="form-control mydatepicker" id="checkin" name="checkin" placeholder="Select Check In ">
</div>
<div class="form-group col-sm-3 mb-10">
<label for="checkout">Check Out:</label>
<input type="text" class="form-control mydatepicker" id="checkout" name="checkout" placeholder="Select Check Out ">
</div>
<div class="form-group col-sm-3 mb-10">
<label for="rcategory">Select Room Type:<span class="req">*</span></label>
<select id="rcategory" name="rcategory" class="form-control">
<option value="">-- Select Type --</option>
<?php
$qu=$PDO->prepare("SELECT roomcat_id,roomcat_name,roomcat_adult,roomcat_child FROM room_category WHERE roomcat_status=1 ORDER BY roomcat_id DESC");
$qu->execute(); 
while ($desdet=$qu->fetch()){
echo '<option value="'.$desdet['roomcat_id'].'">'.$desdet['roomcat_name'].'(A '.$desdet['roomcat_adult'].'||C '.$desdet['roomcat_child'].')'.'</option>';
} ?>
</select> 
</div>
<div class="form-group col-sm-3 mb-10" style="margin-top: 20px;">
<!-- <label for="chkavl">Click Here</label> -->
<button type="button" class="button btn-block btn-lg btn-warning entrybtn chkavl">Check Availabilty</button>
</div>
</div>
<div class="roompan row mb-10"></div>
<div class="roomned row mb-10"></div>
</div>
</div>
<div class="roomtable"></div>
<div class="card card-statistics" id="gdetail">
<div class="card-title eachhigh">
<h4>Guest Details</h4>
</div>
<div class="card-body">
<div class="row">
<div class="form-group col-sm-6">
<label for="name">Name</label>
<input type="text" class="form-control" id="uname" name="name" placeholder="Enter Name" value="<?php echo $memname; ?>">
</div>
<div class="form-group col-sm-6">
<label for="phone">Phone</label>
<input type="text" class="form-control tagify" id="uphone" name="phone" placeholder="Enter Phone Number" value="<?php echo $memphone; ?>">
</div>
<div class="form-group col-sm-6">
<label for="emails">Email ID</label>
<input type="text" class="form-control tagify" id="uemail" name="email" placeholder="Enter Email" value="<?php echo $mememail; ?>">
</div>
<div class="form-group col-sm-6">
<label for="address">Residential Address</label>
<input type="text" class="form-control" id="uaddress" name="address" placeholder="Enter Address" value="">
</div>
<div class="form-group col-sm-4">
<label for="city">City</label>
<input type="text" class="form-control" id="ucity" name="city" placeholder="Enter City" value="">
</div>
<div class="form-group col-sm-4">
<label for="zipcode">Zipcode</label>
<input type="number" class="form-control" id="uzipcode" name="zipcode" placeholder="Enter Zipcode" value="">
</div>
<div class="form-group col-sm-4">
<label for="country">Country <span>[optional]</span></label>
<input type="text" class="form-control" id="ucountry" name="country" placeholder="Enter Country" value="">
</div>
<div class="form-group col-sm-6 ">
<label for="docfile">Select Document:[Aadhar/PAN/Voter card etc.]</label>
<input type="file" class="form-control" id="docfile" name="docfile">
</div>
</div>
</div>
<div class="form-group col-sm-12 btn-pad">
<?php if(empty($_SESSION['userid'])){ ?>
<a class="button btn-block btn-lg" href="#" data-bs-toggle="modal" data-bs-target="#loginreg">Proceed to payment</a>
<?php }else{?>
<button type="submit" class="button btn-block btn-lg btn-warning entrybtn col-md-12">Proceed To Payment</button>
<?php } ?>
<div class="col-sm-12 outmsg">   </div>
</div>
</div>

</form>
</div>
</div>
</div>

<?php include '_footer.php'; ?>
<script type="text/javascript">
$(".mydatepicker").flatpickr({dateFormat: "d-m-Y",minDate: "today"});

$(".chkavl").on('click',(function(e){
var indate= $('#checkin').val();
var outdate= $('#checkout').val();
var cat= $('#rcategory').val();
if(indate!='' && cat!=''){
$.ajax({
url:"_user_reservation",
type:"POST", 
data: {"indate":indate,"outdate":outdate,"cat":cat,"operation":"fetcrom"}, 
error: function(result){},
beforeSend: function(){$('.roompan').addClass('loader');},
success: function(result){$('.roompan').removeClass('loader');$(".roompan").html(result);}
});
}
else{
  showToast("Please Fill Check In/Category","error");
}
}));
$(".roompan").delegate('#uadult',"keyup",function(e){
var ad= $(this).val();
var ch = $(".roompan").find('#uchild').val();
roomneed(ad,ch);
});
$(".roompan").delegate('#uchild',"keyup",function(e){
var ch= $(this).val();
var ad = $(".roompan").find('#uadult').val();
roomneed(ad,ch);
});
function roomneed(ad,ch){
	var url="_user_reservation";
  $.ajax({
    type: "POST",
    url: url,
    data: {"ad":ad,"ch":ch,"operation":"romneed"},
    success: function(res)
    {$(".roomned").html(res);}
});
}
$(".roomned").delegate('#booknowp',"click",function(e){
	console.log('click');
        $("#gdetail").show();
        });

$("#bookin").on('submit',(function(e){
e.preventDefault();
    var url="_user_reservation";
    var data = new FormData(this);
    data.append("operation","savebookin");
    data.append("ad",$('#uadult').val());
    data.append("ch",$('#uchild').val());
    $.ajax({
      type:"POST",url:url,data:data,contentType:false,cache:false,processData:false,dataType:"json",
     beforeSend: function(){$('.entrybtn').addClass('is-loading');},
      error: function(res){$('.entrybtn').removeClass('is-loading');},
      success: function(res)
      {
         $('.entrybtn').removeClass('is-loading');
        if(res.status){location.href=res.msg;}else{$(".outmsg").html(res.msg);}
      }
    }); 
})); 
if (localStorage.getItem("category")!='' || localStorage.getItem("category")!=null ) {
	$('#rcategory').val(localStorage.getItem("category"));
}
</script>
</body>
</html>