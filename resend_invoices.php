<?php
/**
 * Created by PhpStorm.
 * User: 33247
 * Date: 15-02-27
 * Time: 23:56
 */

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require('PHPMailer/PHPMailerAutoload.php');
require_once('bin/db_config.php');

if (!isset($_GET['swimmer_id'])) {
    header('Location: /sponsoredswim/index.php');
}
$swimmer_id = $_GET['swimmer_id'];

$stmt1 = $conn->prepare("SELECT swimmer_last_name, swimmer_first_name, swimmer_email, distance_swum FROM swimmers WHERE swimmer_id=?");
$stmt1->bind_param('s', $swimmer_id);
$stmt1->execute();
$stmt1->bind_result($swimmer_last_name, $swimmer_first_name, $swimmer_email, $distance_swum);
$stmt1->store_result();
$stmt1->fetch();

set_time_limit(10);

$stmt2 = $conn->prepare("SELECT sponsor_name, sponsor_total_amount, invoice FROM sponsorships WHERE swimmer_id=?");

$stmt2->bind_param('s',$swimmer_id);
$stmt2->execute();
$stmt2->bind_result($sponsor_name, $sponsor_total_amount, $invoice);
$stmt2->store_result();

if ($stmt2->num_rows == 0) {
    header("Location: /sponsoredswim/swimmer.php?swimmer_id=$swimmer_id&mail_success=0");
} else {
    $distance_swum_string = number_format($distance_swum);

    $mail = new PHPMailer;

    require('bin/smtp_config.php');

    $mail->isHTML(false);

    $mail->Subject = 'Sponsored Swim - '.$swimmer_first_name.' '.$swimmer_last_name;
    $mail->Body = "Dear $swimmer_first_name $swimmer_last_name,
    \n\nThank you for participating in the Aquadragons Habitat for Humanity & #JusticeForTheInnocent Sponsored Swim! The latest invoices with details and amounts for each of your sponsors are attached.
    \n\nIf you need a stamped invoice or other assistance, please contact us at the email addresses below and we can arrange it.
    \n\nThank you,
    \nJIS Habitat for Humanity & #JusticeForTheInnocent\";
    \n\n41792@jisedu.or.id / 42157@jisedu.or.id / crose@jisedu.or.id";

    while ($stmt2->fetch()) {
        if ($sponsor_name == 'Aquadragons') {
            $swimmer_email = 'sponsoredswim@shreyas208.com';
        }
        $mail->addAttachment("pdf/$invoice.pdf");
    }

    $mail->addAddress($swimmer_email); // Add a recipient

    if ($mail->send()) {
        header("Location: /sponsoredswim/swimmer.php?swimmer_id=$swimmer_id&mail_success=1");
    } else {
        //echo $mail->ErrorInfo;
        header("Location: /sponsoredswim/swimmer.php?swimmer_id=$swimmer_id&mail_success=0");
    }

}