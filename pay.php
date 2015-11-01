<?php
/**
 * Created by PhpStorm.
 * User: 33247
 * Date: 15-02-27
 * Time: 22:00
 */

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require_once 'bin/db_config.php';

if (!isset($_GET['sponsorship_id'])) {
    header('Location: /sponsoredswim/index.php');
}

$sponsorship_id = $_GET['sponsorship_id'];

// Check connection
if ($conn->connect_error) {
    echo 'Error';
} else {
    $stmt1 = $conn->prepare("SELECT swimmer_id, sponsor_name, sponsor_type, sponsor_amount, sponsor_total_amount FROM sponsorships WHERE sponsorship_id=?");
    $stmt1->bind_param('s', $sponsorship_id);
    $stmt1->execute();
    $stmt1->bind_result($swimmer_id, $sponsor_name, $sponsor_type, $sponsor_amount, $sponsor_total_amount);
    $stmt1->store_result();
    $stmt1->fetch();
    $sponsor_amount = number_format($sponsor_amount);
    $sponsor_total_amount_string = number_format($sponsor_total_amount);
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

    <title>Enter Payment | JIS H4H Sponsored Swim</title>

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
        <div class="col-md-4 col-md-offset-4">
            <h3 class="page-header">
                <i class="fa fa-fw fa-plus"></i> Payment
            </h3>
        </div>
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <?php
                    echo "<h4>Sponsor Name: $sponsor_name</h4><h5>";
                    if ($sponsor_type == 0) {
                        echo "Rp. $sponsor_amount per meter";
                    } else if ($sponsor_type == 1) {
                        echo "Rp. $sponsor_amount (fixed amount)";
                    }
                    echo "</h5><h5>Total Amount: Rp. $sponsor_total_amount_string</h5>"
                    ?>
                    <form action="pay_process.php" method="POST">
                        <div class="form-group">
                            <label for="inputAmountPaid">Amount Paid</label>
                            <input type="number" class="form-control" id="inputAmountPaid" name="amount_paid" value="<?php
                            if (!isset($_GET['remove'])) { echo $sponsor_total_amount; }
                            else { echo 0; }
                            ?>" required>
                        </div>
                        <input type="hidden" value="<?php echo $sponsorship_id ?>" name="sponsorship_id">
                        <input type="hidden" value="<?php echo $swimmer_id ?>" name="swimmer_id">
                        <a href="swimmer.php?swimmer_id=<?php echo $swimmer_id ?>"><span class="label label-default">CANCEL</span></a>
                        <?php
                        if (!isset($_GET['remove'])) {
                            echo '<button type="submit" class="btn btn-primary">Add Payment</button>';
                        } else {
                            echo '<button type="submit" class="btn btn-danger">Remove Payment</button>';
                        }
                        ?>

                    </form>
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