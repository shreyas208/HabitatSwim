<?php
/**
 * Created by PhpStorm.
 * User: 33247
 * Date: 15-02-22
 * Time: 19:21
 */

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require('fpdf/fpdf.php');

class PDF extends FPDF
{
// Page header
    function Header()
    {
        // Logo
        $this->Image('img/logo.png',15,10,30);
        // Arial bold 15
        $this->SetFont('Helvetica','B',15);
        // Move to the right
        $this->Cell(100);
        // Title
        $this->Cell(30,15,"Jakarta Intercultural School Habitat for Humanity",0,0,'C');
        $this->Cell(15,35,"Aquadragons Sponsored Swim",0,0,'C');
        // Line break
        $this->Ln(30);
    }

// Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-30);
        // Arial italic 8
        $this->SetFont('Helvetica','I',12);
        // Page number
        $this->Cell(0,10,'Jakarta International School Habitat for Humanity',0,0,'C');
        $this->Ln(10);
        $this->Cell(0,10,'tdonohue@jisedu.or.id / 33247@jisedu.or.id',0,0,'C');
    }
}