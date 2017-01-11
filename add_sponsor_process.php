<?php
/**
 * Created by PhpStorm.
 * User: 33247
 * Date: 15-02-28
 * Time: 0:46
 */

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require_once 'bin/db_config.php';

if (!isset($_POST['swimmer_id'])) {
    header('Location: /sponsoredswim/index.php');
}
$swimmer_id = $_POST['swimmer_id'];
if (!isset($_POST['sponsor_name'])) {
    header("Location: /sponsoredswim/swimmer.php?swimmer_id=$swimmer_id");
}
$sponsor_name = filter_var($_POST['sponsor_name'], FILTER_SANITIZE_STRING);
if (!isset($_POST['sponsor_type'])) {
    header("Location: /sponsoredswim/swimmer.php?swimmer_id=$swimmer_id");
}
$sponsor_type = filter_var($_POST['sponsor_type'], FILTER_SANITIZE_NUMBER_INT);
if (!isset($_POST['sponsor_amount'])) {
    header("Location: /sponsoredswim/swimmer.php?swimmer_id=$swimmer_id");
}
$sponsor_amount = filter_var($_POST['sponsor_amount'], FILTER_SANITIZE_NUMBER_INT);

// Check connection
if ($conn->connect_error) {
    echo 'Error';
} else {
    $stmt1 = $conn->prepare('SELECT distance_swum, swimmer_total_amount FROM swimmers WHERE swimmer_id=?');
    $stmt1->bind_param('s', $swimmer_id);
    $stmt1->execute();
    $stmt1->bind_result($distance_swum, $swimmer_total_amount);
    $stmt1->store_result();
    $stmt1->fetch();

    $sponsor_total_amount = 0;

    if ($sponsor_type == 0) {
        $sponsor_total_amount = $sponsor_amount * $distance_swum;
    } else if ($sponsor_type == 1) {
        $sponsor_total_amount = $sponsor_amount;
    }

    $swimmer_total_amount += $sponsor_total_amount;

    $stmt2 = $conn->prepare('INSERT INTO sponsorships (swimmer_id, sponsor_name, sponsor_type, sponsor_amount, sponsor_total_amount) VALUES (?, ?, ?, ?, ?)');
    echo $conn->error;
    $stmt2->bind_param('ssiii', $swimmer_id, $sponsor_name, $sponsor_type, $sponsor_amount, $sponsor_total_amount);
    $stmt2->execute();

    $stmt3 = $conn->prepare("UPDATE swimmers SET swimmer_total_amount=? WHERE swimmer_id=?");
    $stmt3->bind_param('is', $swimmer_total_amount, $swimmer_id);
    $stmt3->execute();

    header("Location: /sponsoredswim/swimmer.php?swimmer_id=$swimmer_id");
}