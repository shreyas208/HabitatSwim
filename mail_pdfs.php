<?php
/**
 * Created by PhpStorm.
 * User: 33247
 * Date: 15-02-22
 * Time: 21:40
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
}

$distance_swum_string = number_format($distance_swum);

$mail = new PHPMailer;

// Specify mail server and sender information here
$mail->isSMTP();
$mail->Host = '';
$mail->SMTPAuth = true;
$mail->Username = '';
$mail->Password = '';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->From = '';
$mail->FromName = 'JIS Habitat for Humanity';

$mail->isHTML(false);

$mail->Subject = 'JIS H4H Sponsored Swim - '.$swimmer_first_name.' '.$swimmer_last_name;
$mail->Body = "Dear $swimmer_first_name $swimmer_last_name,
\n\nThank you for participating in the JIS Habitat for Humanity Sponsored Swim! Your final distance was $distance_swum_string meters. A separate document with details and amounts for each of your sponsors is attached.
\n\nThe easiest payment option for us is if you could collect money from your sponsors and hand it in to us at Dragon Dash on Saturday, 28 February 2015. Another option is to pay at the Business Office (JIS H-Module 3rd floor) to the Habitat account: B035267, JIS Peduli Core Project H4H.
\n\nIf you need a stamped invoice or other assistance, please contact us at the email addresses below and we can arrange it.
\n\nIf there are any sponsors that you are unable to collect from, please bring the attached document in on 28 Feb.
\n\nThank you,
\nJIS Habitat for Humanity Leadership Team
\n\nJIS H4H Admin";

while($stmt2->fetch()) {
    if ($sponsor_name == 'Aquadragons') {
        $swimmer_email = '';
    }
    $mail->addAttachment("pdf/$invoice.pdf");
}

$mail->addAddress($swimmer_email); // Add a recipient

if ($mail->send()) {
    header("Location: /sponsoredswim/swimmer.php?swimmer_id=$swimmer_id&mail_success=1");
} else {
    header("Location: /sponsoredswim/swimmer.php?swimmer_id=$swimmer_id&mail_success=0");
}