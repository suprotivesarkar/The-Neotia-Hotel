<?php include '_auth.php'; ?>
<?php
$id=FilterInput($_GET['id']);
if(!is_numeric($id)){include '404.php';die();}
$chk_exists = CheckExists("emp_list","emp_id = '$id' AND emp_status<>2");
if(empty($chk_exists)){include '404.php';die();}

$dealnames =null;
if(!empty($chk_exists->pro_deal_id_ref)) {
$sizes = explode(',',$chk_exists->pro_deal_id_ref);
foreach ($sizes as $sizeval) {
  $size_chk = CheckExists("deals_list","deal_id = '$sizeval' AND deal_status<>2");
  if (!empty($size_chk)) {
    $name = $size_chk->deal_name;
    $txt  = "{id:".$sizeval.",name:".'"'.$name.'"'."},";
    $dealnames.=$txt;
  }
} 
$dealnames = rtrim($dealnames,",");
}  

$sectionnames =null;
if(!empty($chk_exists->pro_section_id_ref)) {
$sizes = explode(',',$chk_exists->pro_section_id_ref);
foreach ($sizes as $sizeval) {
  $size_chk = CheckExists("section_list","section_id = '$sizeval' AND section_status<>2");
  if (!empty($size_chk)) {
    $name = $size_chk->section_name;
    $txt  = "{id:".$sizeval.",name:".'"'.$name.'"'."},";
    $sectionnames.=$txt;
  }
} 
$sectionnames = rtrim($sectionnames,",");
}  
?>
<?php include '_auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head> 
<title>Employee Update</title>
<?php include '_header.php'; ?>
<link rel="stylesheet" type="text/css" href="assets/css/token-input.css">
<style type="text/css">
label {}
.custom-control-label::after{position:absolute;top:0rem;left:20px;display:block;width:1.5rem;height:1.5rem;content:"";background-repeat:no-repeat;background-position:center center;background-size:64% 100%}
.custom-control-label::before{position:absolute;top:0rem;left:20px;display:block;width:1.5rem;height:1.5rem;pointer-events:none;content:"";-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;background-color:#dee2e6}
.custom-control{position:relative;display:block;min-height:1.5rem;padding-left:3.5rem}
.masterbox ul.token-input-list {width:auto!important;border: 1px solid #d6d6d6;min-height:40px}
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
<h5><?php echo $chk_exists->emp_name; ?> 
  <a href="employee-list" class="button x-small pull-right"><i class="fa fa-long-arrow-left"></i> Employee List</a>
  <a href="product-view?id=<?php echo $id; ?>" class="button x-small pull-right mx-2"><i class="fa fa-eye"></i> View</a>
</h5>
</div> 
<div class="card-body">
<form action="" autocomplete="off" id="upfrm">
<input type="hidden" class="form-control" id="uptid" name="uptid" value="<?php echo $chk_exists->pro_id; ?>" />
<div class="row">
  <div class="form-group col-sm-12 masterbox">
<label for="section">Section:</label>
<input type="text" class="form-control" id="section" name="section">
</div>
  <div class="form-group col-sm-12 masterbox">
<label for="deal">Deal:</label>
<input type="text" class="form-control" id="deal" name="deal">
</div>
<div class="form-group col-sm-12">
<label for="high">Enter Highlights:</label>
<textarea class="form-control editor" rows="7" name="high"><?php echo $chk_exists->pro_highlight; ?></textarea>
</div>
<div class="form-group col-sm-12">
<label for="desc">Enter Description:</label>
<textarea class="form-control editor" rows="7" id="desc" name="desc"><?php echo $chk_exists->pro_description; ?></textarea>
</div>
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
<script src="assets/js/jquery.tokeninput.js"></script>
<script type="text/javascript">
$(document).ready(function(){
$(".editor").each(function(){CKEDITOR.replace($(this).attr("name"));});
$("#upfrm").on('submit',(function(e){
e.preventDefault();
if($("#upfrm").valid()){
for(instance in CKEDITOR.instances){CKEDITOR.instances[instance].updateElement();}
    var url="helper/master/product";
    var data = new FormData(this);
    data.append("operation","updatemore");
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
})); 
$("#deal").tokenInput("helper/search/search_deal", {
  hintText: "Search New",
  noResultsText: "Cant Find",
  searchingText: "Searching...",
  preventDuplicates: true,
  minChars:1,
  tokenLimit:20,
  method:"get",
  onResult: function(results){
    $.each(results,function(index,value){value.name = value.name;});
    return results;
  },
  prePopulate: [<?php echo $dealnames; ?>],
  onAdd: function (item) {/*alert("Added " + item.name);*/}
});
$("#section").tokenInput("helper/search/search_section", {
  hintText: "Search New",
  noResultsText: "Cant Find",
  searchingText: "Searching...",
  preventDuplicates: true,
  minChars:1,
  tokenLimit:20,
  method:"get",
  onResult: function(results){
    $.each(results,function(index,value){value.name = value.name;});
    return results;
  },
  prePopulate: [<?php echo $sectionnames; ?>],
  onAdd: function (item) {/*alert("Added " + item.name);*/}
});
});
</script>
</body>
</html>