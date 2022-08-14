<?php 
require_once("../../config/config.php");require_once("../../config/function.php");header("cache-control:no-cache");
if(empty($_SESSION['islogin'])){
  echo $response = json_encode(array(
      "status" =>false,
      "msg"  => "Unauthorized Access"
  ));
  die(); 
}
$operation  = (!empty($_POST['operation']))?FilterInput($_POST['operation']):null; 
if (empty($operation)){
  echo $response = json_encode(array(
      "status" => false,
      "msg"  => "Something Wrong"
  ));
  die();
} 

elseif ($operation=="fetchGallery"){
  $id = (!empty($_POST['id']))?FilterInput($_POST['id']):null; 
  if(empty($id) OR !(filter_var($id,FILTER_VALIDATE_INT))) {
    echo '<div class="alert alert-warning"><p>No Data Found</p></div>';
    die();
  }
  $chk_exists = CheckExists("users_list","user_id='$id' AND user_status<>2");
  if (empty($chk_exists)) {
    echo '<div class="alert alert-warning"><p>No Data Found</p></div>';
    die();
  }
  $stmt = $PDO->prepare("SELECT * FROM emp_doc WHERE doc_status<>2 AND doc_user_id_ref='$id'");
  $stmt->execute(); 
  if($stmt->rowCount()>0){ ?>
  <div class="table-responsive">
  <table class="table table-striped table-bordered table-hover" id="entry_table_gallery">
  <thead>
  <tr>
  <th>#</th>
  <th>DOCUMENT</th>
  <th>DOCUMENT TYPE</th>
  <th>STATUS</th>
  <th>ACTIONS</th>
  </tr>
  </thead>
  <tbody> 
  <?php   
  $i=1;
  while ($row=$stmt->fetch()){
  extract($row);  ?> 
  <tr id="<?php echo $doc_id; ?>">
  <td><?php echo $i++; ?></td>
  <td><a href="../images/employee/employee-doc/<?php echo $doc_img; ?>" target="/"><b style="color: #2b86a9;">VIEW</b></a></td>
  <td><?php echo $doc_name; ?></td>
  <td><?php echo StatusReport($doc_status);  ?></td>
  <td>
  <?php  
    if ($doc_status==0) { ?>
    <a href="javascript:void(0);" title="Make Active" class="text-success statusupgal" data-id="<?php echo htmlspecialchars($doc_id); ?>" data-operation="activegal"><i class="fa fa-check"></i> || </a>
    <?php }else if($doc_status==1) { ?>
    <a href="javascript:void(0);" title="Make Dective" class="text-danger statusupgal" data-id="<?php echo $doc_id; ?>" data-operation="deactivegal"><i class="fa fa-lock"></i> || </a>
    <?php } ?>
  <a href="" data-toggle="modal" data-target="#galup" data-id="<?php echo htmlspecialchars($doc_id); ?>" data-name="<?php echo htmlspecialchars($doc_name); ?>" class="editbtn" title="Update"><i class="fa fa-edit"></i></a> || 
  <a href="javascript:void(0);" class="statusupgal" title="Delete" data-id="<?php echo htmlspecialchars($doc_id); ?>" data-operation="deletegal"><i class="fa fa-trash"></i></a>
  </td>
  </tr>
  <?php } ?>
  </tbody>
  </table>
    </div>
  <?php }else{echo '<div class="alert alert-warning"><p>No Document Found</p></div>'; }
}

elseif ($operation=="addgalleryImg") {

  $docid      = (!empty($_POST['id']))?FilterInput($_POST['id']):null; 
  $imagename  = (!empty($_POST['galimgname']))?FilterInput($_POST['galimgname']):null; 

    if (empty($docid) OR !is_numeric($docid)) {
      echo $response = json_encode(array(
          "status" =>false,
          "msg"  =>"Document Not Found"
        ));
      die();
    }

    $chk_pro = CheckExists("users_list","user_id = '$docid' AND user_status<>2");
  if (empty($chk_pro)) {
    echo $response = json_encode(array(
        "status" => false,
        "msg"  => "Cant Find this Employee"
    ));
    die();
  }
 if(empty($_FILES['docfile']['name'])){
echo $response = json_encode(array(
"status" => false,
"msg"  => "Select Document"
));
die();
}
$valid_ext   = array('jpeg', 'jpg', 'png', 'pdf', 'doc', 'docx');
$mime_filter = array('image/jpeg', 'image/jpg', 'image/png', 'application/pdf', 'application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document');
$maxsize     = 10 * 1024 * 1024;

$FileName    = FilterInput($_FILES['docfile']['name']);
$tmpName     = $_FILES['docfile']['tmp_name'];
$FileTyp     = $_FILES['docfile']['type'];
$FileSize    = $_FILES['docfile']['size'];
$MimeType    = mime_content_type($_FILES['docfile']['tmp_name']);

$ext      = strtolower(pathinfo($FileName, PATHINFO_EXTENSION));
$FileName = basename(strtolower($FileName),".".$ext);
$FileName = FileName($chk_pro->user_name).'_'.time().rand(10000,999999999).'.'.$ext;

if(!in_array($ext, $valid_ext)) {
echo $response = json_encode(array(
"status" =>false, 
"msg"  =>"File Extention Not Allowed"
));
die();
}
if($FileSize>$maxsize){
echo $response = json_encode(array(
"status" =>false, 
"msg"  =>"Max file Size: 10MB"
));
die();
}
if(!in_array($FileTyp, $mime_filter)) {
echo $response = json_encode(array(
"status" =>false, 
"msg"  =>"File Not Supported"
));
die();
}
if(!in_array($MimeType, $mime_filter)) {
echo $response = json_encode(array(
"status" =>false, 
"msg"  =>"File Not Supported"
));
die();
}

$path = "../../../images/employee/employee-doc/".$FileName;
if (!move_uploaded_file($_FILES["docfile"]["tmp_name"],$path)) {
echo $response = json_encode(array(
"status" =>false, 
"msg"  =>"Cant Upload File"
));
die();
}
chmod($path,0644);

  $sql = "INSERT INTO emp_doc SET
          doc_user_id_ref = :doc_user_id_ref,
          doc_img     =:doc_img,
          doc_name     = :doc_name";
          $insert   = $PDO->prepare($sql);;
          $insert->bindParam(':doc_user_id_ref',$docid);
          $insert->bindParam(':doc_img',$FileName);
          $insert->bindParam(':doc_name',$imagename);
          $insert->execute();
          if($insert->rowCount() > 0){
            echo $response = json_encode(array(
          "status" => true, 
          "msg"  => "Successfully Updated"
        ));
          }else {
            echo $response = json_encode(array(
          "status" =>false,
          "msg"  =>"No Changes Done"
        ));
      }
}


elseif ($operation=="updategalleryImg") {
  $id           = (!empty($_POST['id']))?FilterInput($_POST['id']):null; 
  $gaiid        = (!empty($_POST['gaiid']))?FilterInput($_POST['gaiid']):null; 
  $galimgname   = (!empty($_POST['galimgnameup']))?FilterInput($_POST['galimgnameup']):''; 
  if(empty($id) OR !(filter_var($id,FILTER_VALIDATE_INT)) OR empty($gaiid) OR !(filter_var($gaiid,FILTER_VALIDATE_INT))) {
    echo $response = json_encode(array(
        "status" => false,
        "msg"  => "No data Found"
    ));
    die();
  }
  $chk_pro = CheckExists("users_list","user_id='$id' AND user_status = 1");
  if (empty($chk_pro)) {
    echo $response = json_encode(array(
        "status" => false,
        "msg"  => "No data Found"
    ));
    die();
  }
  $chk_gal_exists = CheckExists("emp_doc","doc_id='$gaiid' AND doc_user_id_ref='$id' AND doc_status<>2");
  if (empty($chk_gal_exists)) {
    echo $response = json_encode(array(
        "status" => false,
        "msg"  => "No Image Found"
    ));
    die();
  }

$FileName = $chk_gal_exists->doc_img;
  if(!empty($_FILES['updoc']['name'])){
$valid_ext   = array('jpeg', 'jpg', 'png', 'pdf', 'doc', 'docx');
$mime_filter = array('image/jpeg', 'image/jpg', 'image/png', 'application/pdf', 'application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document');
$maxsize     = 10 * 1024 * 1024;

$FileName    = FilterInput($_FILES['updoc']['name']);
$tmpName     = $_FILES['updoc']['tmp_name'];
$FileTyp     = $_FILES['updoc']['type'];
$FileSize    = $_FILES['updoc']['size'];
$MimeType    = mime_content_type($_FILES['updoc']['tmp_name']);

$ext      = strtolower(pathinfo($FileName, PATHINFO_EXTENSION));
$FileName = basename(strtolower($FileName),".".$ext);
$FileName = FileName($FileName).'_'.time().rand(10000,999999999).'.'.$ext;

if(!in_array($ext, $valid_ext)) {
echo $response = json_encode(array(
"status" =>false, 
"msg"  =>"File Extention Not Allowed"
));
die();
}
if($FileSize>$maxsize){
echo $response = json_encode(array(
"status" =>false, 
"msg"  =>"Max file Size: 10MB"
));
die();
}
if(!in_array($FileTyp, $mime_filter)) {
echo $response = json_encode(array(
"status" =>false, 
"msg"  =>"File Not Supported"
));
die();
}
if(!in_array($MimeType, $mime_filter)) {
echo $response = json_encode(array(
"status" =>false, 
"msg"  =>"File Not Supported"
));
die();
}

$path = "../../../images/employee/employee-doc/".$FileName;
if (!move_uploaded_file($_FILES["updoc"]["tmp_name"],$path)) {
echo $response = json_encode(array(
"status" =>false, 
"msg"  =>"Cant Upload File"
));
die();
}

chmod($path,0644);

    if (!empty($chk_gal_exists->doc_img) AND file_exists("../../../images/employee/employee-doc/".$chk_gal_exists->doc_img)){
      @unlink("../../../images/employee/employee-doc/".$chk_gal_exists->doc_img);
    }
//     if (!empty($chk_gal_exists->pg_img_fu) AND file_exists("../../../product/".$chk_gal_exists->pg_img_fu)){
//         @unlink("../../../product/".$chk_gal_exists->pg_img_fu);
//     }
//     if (!empty($chk_gal_exists->pg_img_lg) AND file_exists("../../../product/".$chk_gal_exists->pg_img_lg)){
//         @unlink("../../../product/".$chk_gal_exists->pg_img_lg);
//     }
//     if (!empty($chk_gal_exists->pg_img_md) AND file_exists("../../../product/".$chk_gal_exists->pg_img_md)){
//       @unlink("../../../product/".$chk_gal_exists->pg_img_md);
//     }
//     if (!empty($chk_gal_exists->pg_img_sm) AND file_exists("../../../product/".$chk_gal_exists->pg_img_sm)){
//       @unlink("../../../product/".$chk_gal_exists->pg_img_sm);
//     }
//     if (!empty($chk_gal_exists->pg_img_xs) AND file_exists("../../../product/".$chk_gal_exists->pg_img_xs)){
//       @unlink("../../../product/".$chk_gal_exists->pg_img_xs);
//     }
//   } 
  }
  $sql = "UPDATE emp_doc SET
          doc_user_id_ref = :doc_user_id_ref,
          doc_img        =:doc_img,
          doc_name       = :doc_name
          WHERE doc_id=:doc_id";
          $insert   = $PDO->prepare($sql); 
          $insert->bindParam(':doc_user_id_ref',$id);
          $insert->bindParam(':doc_img',$FileName);
          $insert->bindParam(':doc_name',$galimgname);
          $insert->bindParam(':doc_id',$gaiid);
          $insert->execute();
          if($insert->rowCount() > 0){
            echo $response = json_encode(array(
          "status" => true, 
          "msg"  => "Successfully Updated"
        ));
          }else {
            echo $response = json_encode(array(
          "status" =>false,
          "msg"  =>"No Changes Done"
        ));
      }
}



elseif ($operation=="activegal" OR $operation=="deactivegal" OR $operation=="deletegal") {

  $id = (!empty($_POST['id']))?FilterInput($_POST['id']):null; 
  if(empty($id) OR !(filter_var($id,FILTER_VALIDATE_INT))) {
    echo $response = json_encode(array(
        "status" => false,
        "msg"  => "Something Wrong"
    ));
    die();
  }
  $chk_id = CheckExists("emp_doc","doc_id = '$id' AND doc_status<>2");
  if (empty($chk_id)) {
    echo $response = json_encode(array(
        "status" => false,
        "msg"  => "Cant Find this Image"
    ));
    die();
  }
  switch ($operation) {
    case 'activegal':
      $sql = "UPDATE emp_doc SET doc_status=1 WHERE doc_id= '$id'";
      $insert = $PDO->prepare($sql);
      $insert->execute();
      if($insert->rowCount() > 0){
        echo $response = json_encode(array(
          "status" => true, 
          "msg"  => "Successfully Activated"
        ));
      }else {
        echo $response = json_encode(array(
          "status" => false,
          "msg"  => "No Change Done"
        ));
      }
      break;
    case 'deactivegal':
      $sql = "UPDATE emp_doc SET doc_status=0 WHERE doc_id= '$id'";
      $insert = $PDO->prepare($sql);
      $insert->execute();
      if($insert->rowCount() > 0){
        echo $response = json_encode(array(
          "status" => true, 
          "msg"  => "Successfully Deactivated"
        ));
      }else {
        echo $response = json_encode(array(
          "status" => false,
          "msg"  => "No Change Done"
        ));
      }
      break;
    case 'deletegal':
     
      $sql = "UPDATE emp_doc SET doc_status=2, doc_delete_at='$nowTime' WHERE doc_id= '$id'";
      $insert = $PDO->prepare($sql);
      $insert->execute();
      if($insert->rowCount() > 0){
        echo $response = json_encode(array(
          "status" => true, 
          "msg"  => "Successfully Deleted"
        ));
      }else {
        echo $response = json_encode(array(
          "status" => false,
          "msg"  => "No Change Done"
        ));
      }
      break;
    default:
      echo $response = json_encode(array(
        "status" => false,
        "msg"  => "No Change Done"
      ));
      break;
  }
}
