<?php
/**
 * Created by PhpStorm.
 * User: 33247
 * Date: 15-02-27
 * Time: 22:21
 */

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require_once 'bin/db_config.php';

if (!isset($_POST['swimmer_id'])) {
    header('Location: /sponsoredswim/index.php');
}
$swimmer_id = $_POST['swimmer_id'];
if (!isset($_POST['sponsorship_id'])) {
    header("Location: /sponsoredswim/swimmer.php?swimmer_id=$swimmer_id");
}
$sponsorship_id = $_POST['sponsorship_id'];
if (!isset($_POST['amount_paid'])) {
    header("Location: /sponsoredswim/pay.php?sponsorship_id=$sponsorship_id");
}
$amount_paid = filter_var($_POST['amount_paid'], FILTER_SANITIZE_NUMBER_INT);

// Check connection
if ($conn->connect_error) {
    echo 'Error';
} else {
    if ($amount_paid == 0) {
        $query = "UPDATE sponsorships SET amount_paid=?, paid=0 WHERE sponsorship_id=?";
    } else {
        $query = "UPDATE sponsorships SET amount_paid=?, paid=1 WHERE sponsorship_id=?";
    }
    $stmt1 = $conn->prepare($query);
    $stmt1->bind_param('is', $amount_paid, $sponsorship_id);
    $stmt1->execute();
    header("Location: /sponsoredswim/swimmer.php?swimmer_id=$swimmer_id");
}