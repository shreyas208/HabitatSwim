<?php
/**
 * Created by PhpStorm.
 * User: 33247
 * Date: 15-03-03
 * Time: 20:16
 */

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require_once 'bin/db_config.php';

$stmt1 = $conn->prepare("SELECT swimmer_id, swimmer_first_name, swimmer_last_name FROM swimmers ORDER BY swimmer_last_name");
$stmt1->execute();
$stmt1->bind_result($swimmer_id, $swimmer_first_name, $swimmer_last_name);
$stmt1->store_result();

$stmt2 = $conn->prepare("SELECT sponsor_name, paid FROM sponsorships WHERE swimmer_id=?");

while ($stmt1->fetch()) {
    $stmt2->bind_param('s', $swimmer_id);
    $stmt2->execute();
    $sponsors = $stmt2->get_result();
    if ($sponsors->num_rows > 0) {
        echo "$swimmer_first_name $swimmer_last_name <br>";
        while ($row = $sponsors->fetch_array(MYSQLI_ASSOC)) {
            echo '- '.$row['sponsor_name'];
            if ($row['paid'] == 1) echo ' - PAID';
            echo '<br>';
        }
        echo '<br>';
    }
}

