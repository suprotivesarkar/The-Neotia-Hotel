<?php include '_auth.php'; ?>
<?php
$id=FilterInput($_GET['id']);
if(!is_numeric($id)){include '404.php';die();}
$stmt = $PDO->prepare("SELECT * FROM room_category WHERE roomcat_id='$id' AND roomcat_status<>2");
$stmt->execute(); 
$data = $stmt->fetch(PDO::FETCH_OBJ);
if(empty($data)){include '404.php';die();}
?>
<!DOCTYPE html>
<html lang="en">
<head> 
<title>Room Update</title>
<?php include '_header.php'; ?>
<style type="text/css">
label {}
.custom-control-label::after{position:absolute;top:0rem;left:20px;display:block;width:1.5rem;height:1.5rem;content:"";background-repeat:no-repeat;background-position:center center;background-size:64% 100%}
.custom-control-label::before{position:absolute;top:0rem;left:20px;display:block;width:1.5rem;height:1.5rem;pointer-events:none;content:"";-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;background-color:#dee2e6}
.custom-control{position:relative;display:block;min-height:1.5rem;padding-left:3.5rem}
.masterbox ul.token-input-list {width:auto!important;border: 1px solid #d6d6d6;}
.masterbox ul.token-input-list li {display:inline-block;float: left;}
.masterbox ul.token-input-list li input {width:100%;padding:6px 8px;}
.masterbox ul.token-input-list li.token-input-highlighted-token{}
.masterbox li.token-input-token span {margin-left: 5px;}
.masterbox .selectize-input{padding: 6px 8px!important}
.masterbox .dropdown {position: relative;}
.masterbox .locsign {position: absolute;top:8px;right:8px;opacity:.5}
</style>
</head>
<body> 
<?php  include '_menu.php'; ?>
<div class="row">
<div class="col-md-12">
<div class="card card-statistics h-100">
<div class="card-title">
<h5>Update Room - <?= $data->roomcat_name; ?> <a href="rooms" class="button x-small pull-right"><i class="fa fa-long-arrow-left"></i> Back</a></h5>
</div> 
<div class="card-body">
<form id="addfrm" autocomplete="off" enctype="multipart/form-data">
<div class="row">
<div class="form-group col-sm-6">
<label for="roomname">Enter Room Name:</label>
<input type="text" class="form-control" id="roomname" name="roomname" required="" autofocus="" value="<?php echo $data->roomcat_name; ?>">
</div>
<div class="form-group col-sm-6">
<label for="roomname">Enter Room Url:</label>
<input type="text" class="form-control" id="roomurl" name="roomurl" required="" value="<?php echo $data->roomcat_slug; ?>">
</div>
<div class="form-group col-sm-2">
<label for="roomtype">Room Type:</label>
<select name="roomtype" class="form-control valid" id="roomtype" aria-invalid="false">
<option value="1" <?php echo ($data->roomcat_type==1)?"selected":null; ?>>Non AC</option>
<option value="2" <?php echo ($data->roomcat_type==2)?"selected":null; ?>>AC</option>
</select>
</div>
<div class="form-group col-sm-2">
<label for="maxadult">Max Adult:</label>
<input type="number" class="form-control" id="maxadult" name="maxadult" required="" value="<?php echo $data->roomcat_adult; ?>">
</div>
<div class="form-group col-sm-2">
<label for="maxchild">Max Child:</label>
<input type="number" class="form-control" id="maxchild" name="maxchild" required="" value="<?php echo $data->roomcat_child; ?>">
</div>
<div class="form-group col-sm-3">
<label for="rmsprice">Room Starting Price:</label>
<input type="number" class="form-control" id="rmsprice" name="rmsprice" required="" value="<?php echo $data->roomcat_price; ?>">
</div>
<div class="form-group col-sm-3">
<label for="extrabedcost">Extra Bed Cost:</label>
<input type="number" class="form-control" id="extrabedcost" name="extrabedcost" value="<?php echo $data->roomcat_extrabed; ?>">
</div>
<!-- <div class="form-group col-sm-2">
<label for="extratax">Tax:</label>
<input type="number" class="form-control" id="extratax" name="extratax" value="<?php echo $data->roomcat_tax; ?>">
</div> -->
<div class="form-group col-sm-4">
<label for="roomimg">Room Image:[730X705]</label>
<input type="file" class="form-control" id="roomimg" name="timage">
</div>
<div class="form-group col-sm-4">
<label for="coverimg">Cover Image:[1920X1080]</label>
<input type="file" class="form-control" id="coverimg" name="image">
</div>
<div class="form-group col-sm-8">
<label for="imgdesc">Image Description:</label>
<input type="text" class="form-control" id="imgdesc" name="imgdesc" value="<?php echo $data->roomcat_thumb_alt; ?>">
</div>
<div class="form-group col-sm-12">
<label for="smalldesc">Room Small-Description</label>
<textarea class="form-control" id="smalldesc" name="smalldesc" rows="3"><?php echo $data->roomcat_smalldesc; ?></textarea>
</div>
<div class="form-group col-sm-12">
<label for="fulldesc">Room Full-Description</label>
<textarea class="form-control" id="fulldesc" name="fulldesc" rows="3"><?php echo $data->roomcat_fulldesc; ?></textarea>
</div>

<label class="col-sm-12"><b>Room Amenities</b></label>
<?php
$amenilist = array();
if(!empty($data->roomcat_amenities)) {$amenilist = explode(',', $data->roomcat_amenities);}
$k=1;
$qu=$PDO->prepare("SELECT * FROM room_amenities WHERE am_status<>2");
$qu->execute(); 
while ($catdet=$qu->fetch()){ ?>
<div class="custom-control custom-checkbox mb-30 form-group col-sm-2">
<input type="checkbox" class="custom-control-input" name="roomamenities[]"  <?php echo (in_array($catdet['am_id'],$amenilist))?"checked":null; ?> value="<?php echo $catdet['am_id']; ?>" id="catid<?php echo $k; ?>">
<label class="custom-control-label" for="catid<?php echo $k; ?>"><?php echo $catdet['am_name']; ?></label>
</div>
<?php $k++;} ?>
<div class="form-group col-sm-12">
<button type="submit" class="button btn-block btn-lg entrybtn floatbtn">Update Now</button>
</div>
</div>
</form>
</div>
</div>
</div>         
</div>
<?php  include '_footer.php'; ?>
<script type="text/javascript" src="assets/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
$(document).ready(function(){
$.validator.messages.required = '';
$('#addfrm').validate({});
$(".editor").each(function(){CKEDITOR.replace($(this).attr("name"));});
$("#addfrm").on('submit',(function(e){
for(instance in CKEDITOR.instances){CKEDITOR.instances[instance].updateElement();}
e.preventDefault();
if($("#addfrm").valid()){
    var url="helper/master/room";
    var data = new FormData(this);
    data.append("operation","updateRoom");
    data.append("acmrid","<?php echo $id; ?>");
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
          $("#roomimg").val('');
          $("#coverimg").val('');
          showToast(res.msg,"success");
        }else {showToast(res.msg,"error");}
      }
    }); 
}else{return false;}
}));  


});
$("#roomname").keyup(function(){$("#roomurl").val(convertToSlug($(this).val()));});
</script>
</body>
</html>