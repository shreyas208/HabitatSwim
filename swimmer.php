<?php
/**
 * Created by PhpStorm.
 * User: 33247
 * Date: 15-02-27
 * Time: 10:33
 */

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require_once 'bin/db_config.php';

if (!isset($_GET['swimmer_id'])) {
    header('Location: /sponsoredswim/index.php');
}

$swimmer_id = $_GET['swimmer_id'];

// Check connection
if ($conn->connect_error) {
    echo 'Error';
} else {
    $stmt1 = $conn->prepare("SELECT swimmer_last_name, swimmer_first_name, jis, swimmer_email, distance_swum, swimmer_total_amount FROM swimmers WHERE swimmer_id=?");
    $stmt1->bind_param('s', $swimmer_id);
    $stmt1->execute();
    $stmt1->bind_result($swimmer_last_name, $swimmer_first_name, $jis, $swimmer_email, $distance_swum, $swimmer_total_amount);
    $stmt1->store_result();
    $stmt1->fetch();

    $stmt2 = $conn->prepare("SELECT sponsorship_id, sponsor_name, sponsor_type, sponsor_amount, sponsor_total_amount, paid, invoice FROM sponsorships WHERE swimmer_id=?");
    $stmt2->bind_param('s', $swimmer_id);
    $stmt2->execute();
    $result = $stmt2->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Shreyas Patil">

    <link rel="icon" href="/img/icon-64.png" sizes="64x64" type="image/png">

    <title><?php echo "$swimmer_first_name $swimmer_last_name" ?> | JIS H4H Sponsored Swim</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/modern-business.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

</head>

<body>

<!-- Page Content -->
<div class="container">

    <!-- Marketing Icons Section -->
    <div class="row">
        <div class="col-lg-12">
            <br><br>
            <a href="index.php"><button class="btn btn-info">Back</button></a>
            <br><br>
        </div>
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4><i class="fa fa-fw fa-info"></i> <?php echo "$swimmer_first_name $swimmer_last_name" ?></h4>
                </div>
                <div class="panel-body">
                    <?php
                    if ($jis == 1) {
                        echo '<p>JIS</p>';
                    } else {
                        echo '<p>Non-JIS</p>';
                    }
                    echo '</h4>';
                    echo '<p>Email Address: <strong>'.$swimmer_email.'</strong></p>';
                    echo '<p>Distance Swum: <strong>'.number_format($distance_swum).'</strong></p>';
                    echo '<p>Total Amount: <strong>'.number_format($swimmer_total_amount).'</strong></p>';
                    echo '<p><a href="swimmer_edit.php?swimmer_id='.$swimmer_id.'"><span class="label btn-danger">EDIT SWIMMER DETAILS</span></a></p>';
                    echo '<p><a href="regenerate_invoices.php?swimmer_id='.$swimmer_id.'"><span class="label btn-danger">REGENERATE INVOICES</span></a></p>';
                    echo '<p><a href="resend_invoices.php?swimmer_id='.$swimmer_id.'"><span class="label btn-danger">RESEND INVOICES</span></a></p>';
                    if (isset($_GET['mail_success'])) {
                        if ($_GET['mail_success'] == 1) {
                            echo '<p class="text-success">Invoices Sent Successfully</p>';
                        } else {
                            echo '<p class="text-danger">Invoices Failed to Send</p>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4><i class="fa fa-fw fa-user"></i> Sponsors</h4>
                </div>
                <div class="panel-body">
                    <p><a href="add_sponsor.php?swimmer_id=<?php echo $swimmer_id ?>"><span class="label label-info">ADD SPONSOR</span></a></p>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Total Amount</th>
                            <th>Invoice</th>
                            <th>Payment</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                            echo '<tr><td>'.$row['sponsor_name'].'</td><td class="text-center">';
                            if ($row['sponsor_type'] == 0) {
                                echo 'Per Meter';
                            } else if ($row['sponsor_type'] == 1) {
                                echo 'Fixed Amount';
                            }
                            echo '</td><td class="text-center">'.number_format($row['sponsor_amount']).'</td><td class="text-center">'.number_format($row['sponsor_total_amount']).'</td><td class="text-center"><a target="_blank" href="pdf/'.$row['invoice'].'.pdf"><span class="label label-info">VIEW</span></a></td><td class="text-center">';
                            if ($row['paid'] == 1) {
                                echo '<span class="label label-success">PAID <a href="pay.php?sponsorship_id='.$row['sponsorship_id'].'&remove=1"><i class="fa fa-times"></i></a></span>';
                            } else if ($row['paid'] == 0) {
                                echo '<span class="label label-default"><a href="pay.php?sponsorship_id='.$row['sponsorship_id'].'">PAY <i class="fa fa-chevron-right"></i></a></span>';
                            }
                            echo '</td></tr>';
                        }
                        ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td>Total: </td>
                            <td><?php echo $result->num_rows; ?></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->

    <!-- Footer -->
    <!--<footer>
        <div class="row">
            <div class="col-lg-12">
                <p>Copyright &copy; <a href="mailto:habitat@shreyas208.com">Shreyas Patil</a> 2015</p>
            </div>
        </div>
    </footer>-->

</div>
<!-- /.container -->

<!-- jQuery -->
<script src="/js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="/js/bootstrap.min.js"></script>

</body>

</html>