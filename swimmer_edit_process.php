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

if (!isset($_POST['swimmer_id'])) {
    header('Location: /sponsoredswim/index.php');
}
$swimmer_id = $_POST['swimmer_id'];
if (!isset($_POST['swimmer_email'])) {
    header("Location: /sponsoredswim/swimmer.php?swimmer_id=$swimmer_id");
}
$swimmer_email = filter_var($_POST['swimmer_email'], FILTER_SANITIZE_EMAIL);
if (!isset($_POST['distance_swum'])) {
    header("Location: /sponsoredswim/swimmer.php?swimmer_id=$swimmer_id");
}
$distance_swum = filter_var($_POST['distance_swum'], FILTER_SANITIZE_NUMBER_INT);

// Check connection
if ($conn->connect_error) {
    echo 'Error';
} else {
    $stmt1 = $conn->prepare('SELECT sponsorship_id, sponsor_type, sponsor_amount FROM sponsorships WHERE swimmer_id=?');
    $stmt1->bind_param('s', $swimmer_id);
    $stmt1->execute();
    $stmt1->bind_result($sponsorship_id, $sponsor_type, $sponsor_amount);
    $stmt1->store_result();

    $swimmer_total_amount = 0;

    while($stmt1->fetch()) {

        set_time_limit(10);

        $sponsor_total_amount = 0;

        if ($sponsor_type == 0) {
            $sponsor_total_amount = $sponsor_amount * $distance_swum;
        } else if ($sponsor_type == 1) {
            $sponsor_total_amount = $sponsor_amount;
        }

        $swimmer_total_amount += $sponsor_total_amount;

        $stmt2 = $conn->prepare("UPDATE sponsorships SET sponsor_total_amount=? WHERE sponsorship_id=?");
        $stmt2->bind_param('ii', $sponsor_total_amount, $sponsorship_id);
        $stmt2->execute();
    }

    $stmt3 = $conn->prepare("UPDATE swimmers SET swimmer_email=?, distance_swum=?, swimmer_total_amount=? WHERE swimmer_id=?");
    $stmt3->bind_param('siis', $swimmer_email, $distance_swum, $swimmer_total_amount, $swimmer_id);
    $stmt3->execute();

    header("Location: /sponsoredswim/swimmer.php?swimmer_id=$swimmer_id");
}