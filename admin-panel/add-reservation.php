<?php include '_auth.php'; ?>
<?php
// $id=FilterInput($_GET['id']);
// if(!is_numeric($id)){include '404.php';die();}
// $data = CheckExists("accommodations","acm_id = '$id' AND acm_status<>2");
// if(empty($data)){include '404.php';die();}
?>
<!DOCTYPE html>
<html lang="en">
<head> 
<title>New Reservation</title>
<?php include '_header.php'; ?>
<link rel="stylesheet" type="text/css" href="assets/css/bootstrap-clockpicker.min.css">
<style type="text/css">
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
.eachhigh{position:relative;}
.eachhigh::before{position:absolute;content:'';width:0;height:0;border-top:15px solid red;border-right:15px solid transparent;left:0;top:0}
.card{margin-bottom:8px}
.input-group-addon {
background: #84ba3f;
}
/*.card.card-statistics p {
  font-size: 21px;
}*/
</style>
</head>
<body>
<?php  include '_menu.php'; ?>
<div class="row">
<div class="col-md-12">
<div class="card card-statistics">
<div class="card-title">
<h5>New Reservation<a href="reservation" class="button x-small pull-right"><i class="fa fa-long-arrow-left"></i> Back</a></h5>
</div> 
</div>
<form id="addfrm" autocomplete="off" enctype="multipart/form-data">
<input type="hidden" class="form-control" id="uptid" name="uptid" value="">
<div class="card card-statistics">
<div class="card-title eachhigh">
<h5>Basic Info</h5>
</div>
<div class="card-body">
<div class="row">
  <div class="form-group col-sm-3">
<label for="chkin">Check In:<span class="req">*</span></label>
<input type="text" class="form-control datepicker" id="chkin" name="chkin" placeholder="Select Check In Date">
</div>
<div class="form-group col-sm-3">
<label for="chkout">Check Out:</label>
<input type="text" class="form-control datepicker" id="chkout" name="chkout" placeholder="Select Check Out Date">
</div>
<div class="form-group col-sm-3">
<label for="category">Select Room Type:<span class="req">*</span></label>
<select id="category" name="category" class="form-control">
<option value="">-- Select Type --</option>
<?php
$qu=$PDO->prepare("SELECT roomcat_id,roomcat_name,roomcat_adult,roomcat_child FROM room_category WHERE roomcat_status=1 ORDER BY roomcat_id DESC");
$qu->execute(); 
while ($desdet=$qu->fetch()){
echo '<option value="'.$desdet['roomcat_id'].'">'.$desdet['roomcat_name'].'(A '.$desdet['roomcat_adult'].'||C '.$desdet['roomcat_child'].')'.'</option>';
} ?>
</select> 
</div>
<div class="form-group col-sm-3">
<label for="chkbtn">&nbsp;</label>
<button type="button" class="button btn-block btn-lg entrybtn chkbtn">Check Availabilty</button>
</div>
<div class="form-group col-sm-12 ">

<div class="roompan row"></div>
</div>
</div>
</div>
</div>
<div class="roomtable"></div>
<div class="card card-statistics">
<div class="card-title eachhigh">
<h5>Guest Details</h5>
</div>
<div class="card-body">
<div class="row">
  <div class="form-group col-sm-4"> 
<label for="chkintime">Check In Time:<span class="reqfield">*</span></label>
<div class="input-group clockpicker " data-placement="bottom" data-align="top" data-autoclose="true">
<input type="text" class="form-control valid" value="" name="chkintime" id="chkintime" aria-invalid="false">
<span class="input-group-addon"><span class="fa fa-clock-o"></span></span>
</div>
</div>
<div class="form-group col-sm-6">
<label for="name">Name</label>
<input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="">
</div>
<div class="form-group col-sm-6">
<label for="phone">Phone</label>
<input type="text" class="form-control tagify" id="phone" name="phone" placeholder="Enter Phone" value="">
</div>
<div class="form-group col-sm-6">
<label for="emails">Email ID</label>
<input type="text" class="form-control tagify" id="emails" name="emails" placeholder="Enter Email" value="">
</div>
<div class="form-group col-sm-12">
<label for="address">Residential Address</label>
<input type="text" class="form-control" id="address" name="address" placeholder="Enter Address" value="">
</div>
<div class="form-group col-sm-4">
<label for="city">City</label>
<input type="text" class="form-control" id="city" name="city" placeholder="Enter City" value="">
</div>
<div class="form-group col-sm-4">
<label for="zipcode">Zipcode</label>
<input type="text" class="form-control" id="zipcode" name="zipcode" placeholder="Enter Zipcode" value="">
</div>
<div class="form-group col-sm-4">
<label for="country">Country <span>[optional]</span></label>
<input type="text" class="form-control" id="country" name="country" placeholder="Enter Country" value="">
</div>
<div class="form-group col-sm-3">
<label for="adult">Adult</label>
<input type="number" class="form-control" id="adult" name="adult" placeholder="Enter no. of Adult" value="">
</div>
<div class="form-group col-sm-3">
<label for="child">Child</label>
<input type="number" class="form-control" id="child" name="child" placeholder="Enter no. of Child" value="">
</div>
<div class="form-group col-sm-6">
<label for="docfile">Select Document:[Aadhar/PAN/Voter card etc.]</label>
<input type="file" class="form-control" id="docfile" name="docfile">
</div>
<div class="form-group col-sm-2">
<label for="status">Status:</label>
<select name="status" class="form-control valid" id="status" aria-invalid="false">
<option value="1">CONFIRMED</option>
<option value="0">PENDING</option>
</select>
</div>
</div>
</div>
</div>
<div class="card card-statistics">
<div class="card-title eachhigh">
<h5>Payments</h5>
</div>
<div class="card-body">
<div class="row">
<div class="col-sm-4 offset-4">
  <p class="tolvalue">Total Amount: ₹<span id="totalvalue" name="totalvalue">0</span></p>
  <div class="form-group ">
<label for="advance">Advance Payment:</label>
<input type="number" class="form-control arrowhide" id="advance" name="advance" placeholder="Enter Advance Payment" value="">
</div>
<p class="duevalue">Due Amount:₹<span id="dueamt">0</span></p>
</div>

</div>
</div>
</div>
<div class="card card-statistics">
<div class="card-title eachhigh">
<h5>Notes</h5>
</div>
<div class="card-body">
<div class="row">
<div class="form-group col-sm-12">
<textarea class="form-control " id="notes" name="notes" rows="3"></textarea>
</div>
</div>
</div>
</div>
<div class="form-group col-sm-12">
<button type="submit" class="button btn-block btn-lg entrybtn floatbtn">Book Now</button>
</div>
</form>
</div>        
</div> 
<?php  include '_footer.php'; ?>
<script type="text/javascript" src="assets/js/bootstrap-clockpicker.min.js"></script>
<script type="text/javascript" src="assets/js/bootstrap-tagsinput.min.js"></script>
<script type="text/javascript">

$(document).ready(function(){
fetchData();

$(".datepicker").datepicker({autoclose: true,dateFormat:'dd-mm-yy'});
$('.clockpicker').clockpicker();
$('.tagify').tagsinput({});
$(".editor").each(function(){CKEDITOR.replace($(this).attr("name"));});

$(".chkbtn").on('click',(function(e){
var indate= $('#chkin').val();
var outdate= $('#chkout').val();
var cat= $('#category').val();
if(indate!='' && cat!=''){
$.ajax({
url:"_reservation",
type:"POST", 
data: {"indate":indate,"outdate":outdate,"cat":cat,"operation":"fetcrooms"}, 
error: function(result){},
beforeSend: function(){$('.roompan').addClass('loader');},
success: function(result){$('.roompan').removeClass('loader');$(".roompan").html(result);}
});
}
else{
  showToast("Please Fill Check In/Category","error");
}
}));

$(".roompan").delegate('#addtoroom',"click",function(){
var i = 0;
var arr = [];
var indate= $('#chkin').val();
var outdate= $('#chkout').val();
$('.roompan .listcheckbox:checked').each(function () {
   arr[i++] = $(this).val();
});
if(arr!=''){
$.ajax({
url:"_reservation",
type:"POST", 
data: {"indate":indate,"outdate":outdate,"arr":arr,"operation":"addtoroom"}, 
dataType:"json",
error: function(result){},
beforeSend: function(){$('.roompan').addClass('loader');},
success: function(result){
  if (result.status) {
    fetchData();
  }else{
    showToast("No Data Found","error");
  }
}
});
}
else{
  showToast("Please Select Rooms","error");
}

  });

$("#addfrm").on('submit',(function(e){
e.preventDefault();
swal({
        title: "Are you sure to Book?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
})
.then((willDelete) => {
  if (willDelete){
    if($("#addfrm").valid()){
        var url="_reservation";
        var data = new FormData(this);
        data.append("operation","addreserv");
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
              showToast(res.msg,"success");
            }else {showToast(res.msg,"error");}
          }
        }); 
    }else{return false;}
} });
}));  
});

$(".roomtable").delegate('.delbtn',"click",function(){
swal({
        title: "Are you sure?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
})
.then((willDelete) => {
      if (willDelete){
        var id=$(this).data("id");
        var url="_reservation";
        $.ajax({
          type: "POST",
          url: url,
          data: {"id":id,"operation":"deleteroom"},
          dataType:"json",
          success: function(res)
          { 
            if(res.status==1){
              fetchData();
              swal("Successfully Deleted",{
                icon: "success",
                timer:1000,
              });
            }else{showToast(res.msg,"error");}
          }
        });
    }
}); 
}); 

$(".roomtable").delegate('.qtyupdatebtn',"click",function(){
var id=$(this).data("id");
var action=$(this).data("action");
var url="_reservation";
  $.ajax({
    type: "POST",
    url: url,
    data: {"id":id,"action":action,"operation":"quantityupdate"},
    dataType:"json",
    success: function(res)
    {if(res.status){fetchData();}}
});
});
$(".roomtable").delegate('.increase-btn',"click",function(e){
  e.preventDefault();
  var inputField = $(this).prev("input");
  var currentInputValue = parseInt(inputField.val());
  inputField.val(currentInputValue + 1);
});
$(".roomtable").delegate('.decrease-btn',"click",function(e){
  e.preventDefault();
  var inputField = $(this).next("input");
  var currentInputValue = parseInt(inputField.val());
  if (currentInputValue<=0) {currentInputValue=0;}
  inputField.val(currentInputValue - 1);
  if (currentInputValue <= 0) { inputField.val("0"); }
});
$("#advance").on('keyup',(function(e){
var ad= $(this).val();
var total = parseInt($('#totalvalue').html());
var due= total - ad;
$("#dueamt").html(due);
}));
function fetchData(){
  $.post("_reservation",{"operation":"fetchrooms"},function(data){$(".roomtable").html(data);});
  $.post("_reservation",{"operation":"fetchtotal"},function(data){$("#totalvalue").html(data);});
}
</script>
</body>
</html>