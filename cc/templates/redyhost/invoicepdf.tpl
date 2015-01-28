<?php

# Logo
$pdf->Image(ROOTDIR.'/images/RedyHOST-logo.jpg',15,25, 75, 12.6);

# Company Details
$pdf->SetFont('freesans','',13);
$pdf->Cell(0,6,trim($companyaddress[0]),0,1,'R');
$pdf->SetFont('freesans','',9);
for ( $i = 1; $i <= count($companyaddress); $i += 1) {
	$pdf->Cell(0,4,trim($companyaddress[$i]),0,1,'R');
}
$pdf->SetFont('freesans','',8);
$pdf->Cell(0,6,"ABN: 63 133 456 005",0,1,'R');
$pdf->Ln(6);

# Header Bar
$invoiceprefix = $_LANG["invoicenumber"];
/*
** This code should be uncommented for EU companies using the sequential invoice numbering so that when unpaid it is shown as a proforma invoice **
if ($status!="Paid") {
	$invoiceprefix = $_LANG["proformainvoicenumber"];
}
*/
$pdf->SetFont('freesans','B',15);
$pdf->SetFillColor(239);
$pdf->Cell(0,8,$invoiceprefix.$invoicenum,0,1,'L','1');
$pdf->SetFont('freesans','',10);
$pdf->Cell(0,6,$_LANG["invoicesdatecreated"].': '.$datecreated.'',0,1,'L','1');
$pdf->Cell(0,6,$_LANG["invoicesdatedue"].': '.$duedate.'',0,1,'L','1');
$pdf->Ln(10);

$startpage = $pdf->GetPage();

# Clients Details
$pdf->SetFont('freesans','B',10);
$pdf->Cell(0,4,$_LANG["invoicesinvoicedto"],0,1);
$pdf->SetFont('freesans','',9);
if ($clientsdetails["companyname"]) {
	$pdf->Cell(0,4,$clientsdetails["companyname"],0,1,'L');
	$pdf->Cell(0,4,$_LANG["invoicesattn"].": ".$clientsdetails["firstname"]." ".$clientsdetails["lastname"],0,1,'L');
} else {
	$pdf->Cell(0,4,$clientsdetails["firstname"]." ".$clientsdetails["lastname"],0,1,'L');
}
$pdf->SetFont('helvetica','',8);
$pdf->Cell(0,4,$clientsdetails["address1"],0,1,'L');
if ($clientsdetails["address2"]) {
	$pdf->Cell(0,4,$clientsdetails["address2"],0,1,'L');
}
$pdf->Cell(0,4,$clientsdetails["city"].", ".$clientsdetails["state"].", ".$clientsdetails["postcode"],0,1,'L');
$pdf->Cell(0,4,$clientsdetails["country"],0,1,'L');
if ($customfields) {
    $pdf->Ln();
    foreach ($customfields AS $customfield) {
        $pdf->Cell(0,4,$customfield['fieldname'].': '.$customfield['value'],0,1,'L');
    }
}
$pdf->Ln(10);

$pdf->SetDrawColor(200);

$pdf->SetFont('freesans','B',10);
$pdf->SetFillColor(239);
$pdf->Cell(140,7,$_LANG["invoicesdescription"],1,0,'C','1');
$pdf->Cell(40,7,$_LANG["invoicesamount"],1,0,'C','1');
$pdf->Ln();

$pdf->SetFont('freesans','',10);

foreach ($invoiceitems AS $item) {

	$rowcount = $pdf->getNumLines($item['description'], 140)+1;

	$pdf->MultiCell(140,$rowcount * 5,$item['description'],1,'L',0,0);
	$pdf->MultiCell(40,$rowcount * 5,$item['amount'],1,'C',0,0);

	$pdf->Ln();
}

$pdf->SetFont('freesans','B',10);

$pdf->Cell(140,7,$_LANG["invoicessubtotal"],1,0,'R','1');
$pdf->Cell(40,7,$subtotal,1,0,'C','1');
$pdf->Ln();

if ($taxname) {
	$pdf->Cell(140,7,$taxrate."% ".$taxname,1,0,'R','1');
	$pdf->Cell(40,7,$tax,1,0,'C','1');
	$pdf->Ln();
}

if ($taxname2) {
	$pdf->Cell(140,7,$taxrate2."% ".$taxname2,1,0,'R','1');
	$pdf->Cell(40,7,$tax2,1,0,'C','1');
	$pdf->Ln();
}

$pdf->Cell(140,7,$_LANG["invoicescredit"],1,0,'R','1');
$pdf->Cell(40,7,$credit,1,0,'C','1');
$pdf->Ln();

$pdf->Cell(140,7,$_LANG["invoicestotal"],1,0,'R','1');
$pdf->Cell(40,7,$total,1,0,'C','1');
$pdf->Ln();

$pdf->Ln();

if ($notes) {
	$pdf->SetFont('freesans','',8);
	$pdf->MultiCell(170,5,$_LANG["invoicesnotes"].": $notes");
}

// Adding payment details
    $pdf->SetFont('helvetica','B',12);
    $pdf->SetFillColor(239);
    $pdf->Cell(0,4,"How to pay your invoice",1,1,'L','1');

    $pdf->SetFont('helvetica','B',10);
    $pdf->Cell(0,4,'Bank transfer/EFT',1,1,'L','1');
    $pdf->SetFillColor(255);
    $pdf->Cell(0,4,'Our bank details',1,1,'L','1');
    $pdf->SetFont('helvetica','',10);
    $pdf->Cell(0,4,'Account name: TRUECMS PTY LTD, Account number: 338633, BSB: 033181, Bank name: Westpac',1,1,'L','1');

$endpage = $pdf->GetPage();

$pdf->setPage($startpage);
$pdf->SetXY(70,90);
if ($status=="Cancelled") {
	$statustext = $_LANG["invoicescancelled"];
    $pdf->SetTextColor(245,245,245);
} elseif ($status=="Unpaid") {
	$statustext = $_LANG["invoicesunpaid"];
    $pdf->SetTextColor(204,0,0);
} elseif ($status=="Paid") {
	$statustext = $_LANG["invoicespaid"];
    $pdf->SetTextColor(153,204,0);
} elseif ($status=="Refunded") {
	$statustext = $_LANG["invoicesrefunded"];
    $pdf->SetTextColor(34,68,136);
} elseif ($status=="Collections") {
	$statustext = $_LANG["invoicescollections"];
    $pdf->SetTextColor(255,204,0);
}
$pdf->SetFont('freesans','B',40);
$pdf->Cell(120,20,strtoupper($statustext),0,0,'C');
$pdf->setPage($endpage);

?>
