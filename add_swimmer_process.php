<?php
/**
 * Created by PhpStorm.
 * User: 33247
 * Date: 16-02-12
 * Time: 0:46
 */

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require_once 'bin/db_config.php';

if (!isset($_POST['swimmer_first_name'])) {
    header('Location: /sponsoredswim/index.php');
}
$swimmer_first_name = filter_var($_POST['swimmer_first_name'], FILTER_SANITIZE_STRING);

if (!isset($_POST['swimmer_last_name'])) {
    header('Location: /sponsoredswim/index.php');
}
$swimmer_last_name = filter_var($_POST['swimmer_last_name'], FILTER_SANITIZE_STRING);

if (!isset($_POST['jis'])) {
    header('Location: /sponsoredswim/index.php');
}
$jis = filter_var($_POST['jis'], FILTER_SANITIZE_NUMBER_INT);

if (!isset($_POST['swimmer_email'])) {
    header('Location: /sponsoredswim/index.php');
}
$swimmer_email = filter_var($_POST['swimmer_email'], FILTER_SANITIZE_EMAIL);

$distance_swum = 0;
if (isset($_POST['distance_swum'])) {
    $distance_swum = filter_var($_POST['distance_swum'], FILTER_SANITIZE_NUMBER_INT);
}

// Check connection
if ($conn->connect_error) {
    echo 'Error';
} else {
    $swimmer_id = uniqid("$swimmer_last_name");
    $stmt1 = $conn->prepare('INSERT INTO swimmers (swimmer_id, swimmer_last_name, swimmer_first_name, jis, swimmer_email, distance_swum) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt1->bind_param('sssisi', $swimmer_id, $swimmer_last_name, $swimmer_first_name, $jis, $swimmer_email, $distance_swum);
    $stmt1->execute();
    if ($conn->errno != 0) {
        die($conn->error);
    }

    header("Location: /sponsoredswim/swimmer.php?swimmer_id=$swimmer_id");
}
