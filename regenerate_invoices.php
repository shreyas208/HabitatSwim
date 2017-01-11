<?php
/**
 * Created by PhpStorm.
 * User: 33247
 * Date: 15-02-27
 * Time: 23:08
 */

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require_once 'bin/db_config.php';
require 'generate_pdfs.php';

if (!isset($_GET['swimmer_id'])) {
    header('Location: /sponsoredswim/index.php');
}
$swimmer_id = $_GET['swimmer_id'];

// Check connection
if ($conn->connect_error) {
    echo 'Error';
} else {
    $stmt1 = $conn->prepare("SELECT swimmer_last_name, swimmer_first_name, distance_swum FROM swimmers WHERE swimmer_id=?");
    $stmt1->bind_param('s',$swimmer_id);
    $stmt1->execute();
    $stmt1->bind_result($swimmer_last_name, $swimmer_first_name, $distance_swum);
    $stmt1->store_result();
    $stmt1->fetch();

    $stmt2 = $conn->prepare('SELECT sponsorship_id, sponsor_name, sponsor_type, sponsor_amount, paid, amount_paid FROM sponsorships WHERE swimmer_id=?');
    $stmt2->bind_param('s', $swimmer_id);
    $stmt2->execute();
    $stmt2->bind_result($sponsorship_id, $sponsor_name, $sponsor_type, $sponsor_amount, $paid, $amount_paid);
    $stmt2->store_result();

    $swimmer_total_amount = 0;

    while($stmt2->fetch()) {

        set_time_limit(10);

        $sponsor_total_amount = 0;
        $sponsor_type_text = '';

        if ($sponsor_type == 0) {
            $sponsor_type_text = 'Per Meter';
            $sponsor_total_amount = $sponsor_amount * $distance_swum;
        } else if ($sponsor_type == 1) {
            $sponsor_type_text = 'Fixed Amount';
            $sponsor_total_amount = $sponsor_amount;
        }

        $sponsor_amount_string = number_format($sponsor_amount);
        $sponsor_total_amount_string = number_format($sponsor_total_amount);
        $distance_swum_string = number_format($distance_swum);
        $amount_paid_string = number_format($amount_paid);

        $invoice = str_pad($sponsorship_id,4,'0',STR_PAD_LEFT).'_'.$swimmer_id;

        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Helvetica','',12);
        $pdf->Ln(10);
        $pdf->Cell(0,10,"Dear $sponsor_name,",0,1);
        $pdf->Cell(0,10,'Thank you for sponsoring a swimmer at the JIS Habitat for Humanity Sponsored Swim!',0,1);
        $pdf->Cell(0,10,'The details of your sponsorship are below:',0,1);
        $pdf->Cell(0,10,"Swimmer Name: $swimmer_first_name $swimmer_last_name",0,1);
        $pdf->Cell(0,10,"Sponsorship Type: $sponsor_type_text",0,1);
        $pdf->Cell(0,10,"Sponsorship Amount: IDR $sponsor_amount_string",0,1);
        $pdf->Cell(0,10,"Distance Swum: $distance_swum_string meters",0,1);
        $pdf->SetFont('Helvetica','B',12);
        $pdf->Cell(0,10,"Total Amount: IDR $sponsor_total_amount_string",0,1);
        if ($paid == 1) {
            $pdf->Cell(0,10,"Status: PAID (Rp. $amount_paid_string)",0,1);
        }
        $pdf->Output("pdf/$invoice.pdf",'F');

        $stmt3 = $conn->prepare("UPDATE sponsorships SET invoice=? WHERE sponsorship_id=?");
        $stmt3->bind_param('si', $invoice, $sponsorship_id);
        $stmt3->execute();
    }

    //echo $swimmer_id;
    header("Location: /sponsoredswim/swimmer.php?swimmer_id=$swimmer_id");
}