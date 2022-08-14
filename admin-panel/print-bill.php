<?php
require 'fpdf.php';
include '_auth.php'; 
if (!is_numeric($_GET['id'])) {header("Location:index");}
include '_print_mod.php';
$oid = trim($_REQUEST['id']);
$orderdet = $PDO->prepare("SELECT * FROM reservation_list
                           WHERE res_id='$oid' AND res_status<>3");
$orderdet->execute();
if ($orderdet->rowCount() != 1) {
    $pdf = new PDF('P', 'mm', 'A4');
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->Cell(0, 10, 'Not Found', 0, 0, 'C');
    $pdf->Output();
    die();
} else { 
    $orderdet = $orderdet->fetch();
} 
$orderdetails  = "SELECT * FROM reservation_rooms  
                  WHERE resrooms_res_id_ref ='$oid' AND resrooms_status<>2";
$orderdetails = $PDO->prepare($orderdetails);
$orderdetails->execute();
if ($orderdetails->rowCount() <= 0) {
    $pdf = new PDF('P', 'mm', 'A4');
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->Cell(0, 10, 'Room Details', 0, 0, 'C');
    $pdf->Output();
    die();
}
 
$orderlist = $orderdetails->fetchAll();
$paydetails  = "SELECT * FROM reservation_pay  
                  WHERE pay_res_id_ref ='$oid' AND pay_status<>2";
$paydetails = $PDO->prepare($paydetails);
$paydetails->execute();
$paylist = $paydetails->fetchAll();

$pdf = new PDF('P', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->SetProtection(array('print'));
$pdf->AddPage();
$pdf->SetCompression(true);
$pdf->SetDisplayMode('fullwidth');
$doch=$pdf->GetPageHeight();
$pdf->AddFont('Calibri-Bold', '', 'Calibri-Bold.php');
$pdf->AddFont('Calibri', '', 'Calibri.php');
$pdf->AddFont('Calibri-BoldItalic', '', 'CALIBRIZ.php');
$pdf->LN(4);
$pdf->SetFont('Calibri-BoldItalic', '', 14);
$pdf->MultiCell(0, 3, "Name:", 0, 'L');

$pdf->Text(26,38,$orderdet['res_g_name']);
$pdf->Text(160,38,$orderdet['res_id']."/".date("d-M-Y", strtotime($nowTime)));

$pdf->SetFont('Calibri', '', 11);
$pdf->SetTextColor(0, 0, 0);

$pdf->LN(4);
$ad = $orderdet['res_g_phone'];
$st = $orderdet['res_status'];
$stat = null;
if($st == 0){ $stat = "PENDING";}
elseif ($st == 1) {$stat = "CONFIRMED";}
elseif ($st == 2) {$stat = "CHECKED-OUT";}
else{$stat = "Invalid Data"; }

$pdf->MultiCell(0, 5, $ad, 0, 'J');
$pdf->SetFont('Calibri-Bold','',12);
$pdf->SetTextColor(217,167,4);
$pdf->MultiCell(0, 5, $stat, 0, 'J');
$pdf->SetTextColor(0,0,0);
$pdf->Cell(0, 12, 'Room Details', 0, 0, 'C');

$pdf->LN(4);
$pdf->SetY($pdf->GetY()+10);
$pdf->SetFont('Calibri-Bold', '', 14);
$pdf->Text(10,65,  "Sl");
$pdf->Text(20,65,  "Check-In");
$pdf->Text(45,65,  "Check-Out");
$pdf->Text(70,65, "Days");
$pdf->Text(85,65,  "Room-Type");
$pdf->Text(125,65,  "Room");
$pdf->Text(145,65,  "Room Price");
$pdf->Text(175,65, "Extra Bed");
$pdf->Line(10,67,200,67);
$pdf->Ln(6);


$pdf->SetFont('Calibri', '', 10);
$count = 1;
  $total =0;
foreach($orderlist as $eachorder){ 
    extract($eachorder);

    $recenth = $pdf->GetY();
    $remh    = $doch - $recenth;
    if ($remh < 41) {
        $pdf->AddPage();
        $pdf->Ln(5);
    }

    $nowh = $pdf->GetY();
    $diff = abs(strtotime($resrooms_out) - strtotime($resrooms_in));
    $days = ($diff/(60*60*24));

    $pdf->Text(10,$nowh,  $count);
    $pdf->Text(20,$nowh,  date("d-M-Y", strtotime($resrooms_in)));
    $pdf->Text(47,$nowh,  date("d-M-Y", strtotime($resrooms_out)));
    $pdf->Text(75,$nowh,  $days);
    $pdf->Text(85,$nowh,  $resrooms_roomtype);
    $pdf->Text(130,$nowh,  $resrooms_room_no);
    $pdf->Text(147,$nowh,  "Rs.".$resrooms_roomprice);
    $pdf->Text(177,$nowh,"Rs.". $resrooms_extrabed_price." x ".$resrooms_exbed_qty);
    // $pdf->Text(180,$nowh, $total_qty * $per_price);
     $total = $total + ($resrooms_roomprice*$days) + ($resrooms_exbed_qty*$resrooms_extrabed_price);
    $pdf->Ln(7);
    $count++;
} 
$pdf->Line(10,$nowh+5,200,$nowh+5);

$pdf->SetFont('Calibri-Bold','',12);
$pdf->Cell(0, 8, 'Payment Details', 0, 0, 'C');
$nowh = $nowh + 20;
$pdf->SetY($pdf->GetY()+10);

$pdf->Line(10,$nowh + 2,200,$nowh + 2);
$pdf->SetY($pdf->GetY()+2);

$pdf->SetFont('Calibri-Bold', '', 14);
$pdf->Text(55,$nowh,  "Sl");
$pdf->Text(83,$nowh,  "Payment At");
$pdf->Text(120,$nowh, "Amount");
$pdf->Ln(8);


$pdf->SetFont('Calibri', '', 10);
$count = 1;
 $sum = 0;

foreach($paylist as $eachpay){ 
    extract($eachpay);

    $recenth = $pdf->GetY();
    $remh    = $doch - $recenth;
    if ($remh < 41) {
        $pdf->AddPage();
        $pdf->Ln(5);
    }

       $sum = $sum + $pay_amount;
    $nowh = $pdf->GetY();
    $pdf->Text(55,$nowh,  $count);
    $pdf->Text(85,$nowh,  date("d-M-Y", strtotime($pay_update_at)));
    $pdf->Text(120,$nowh,  "Rs.".$pay_amount);
    $pdf->Ln(7);
    $count++;
} 
$pdf->Line(10,$nowh+5,200,$nowh+5);


$tax = 0; if($total>1000 && $total<=7500){ $tax = 12;}
elseif ($total>7500) {
  $tax = 18;
}
$tottax = $total+Tax($total);
$due = $tottax - $sum;

$pdf->SetFont('Calibri-Bold','',10);
$pdf->Text(140,$nowh+12, "Total Amount: ");
$pdf->Text(175,$nowh+12, "Rs.".$total); 
$pdf->Text(140,$nowh+18, "GST: (".$tax."%)");
$pdf->Text(175,$nowh+18, "Rs.".Tax($total));


$pdf->SetFont('Calibri-Bold','',15);
$pdf->SetTextColor(18,92,30);

$pdf->Text(130,$nowh+30, "Grand Total = Rs. ".$tottax);
$pdf->Line(120,$nowh+42,200,$nowh+42);
$pdf->SetTextColor(24,70,166);
$pdf->Text(130,$nowh+40, "Amount Paid = Rs. ".$sum);

$pdf->SetTextColor(194,8,23);
$pdf->Text(150,$nowh+50, "Due = Rs. ".$due);
 
$fileName = $orderdet['res_g_name'] . "_" . $orderdet['res_id'] . '.pdf';
$pdf->Output("I", $fileName, 'S');
