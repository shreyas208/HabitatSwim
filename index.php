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

$all = true;

if (isset($_GET['all'])) {
    $all = $_GET['all'];
}

// Check connection
if ($conn->connect_error) {
    echo 'Error';
} else {
    $query = "SELECT swimmer_id, swimmer_last_name, swimmer_first_name, jis, swimmer_email, beneficiary, distance_swum, swimmer_total_amount FROM swimmers";
    if (!$all) {
        $query = "SELECT swimmer_id, swimmer_last_name, swimmer_first_name, jis, swimmer_email, beneficiary, distance_swum, swimmer_total_amount FROM swimmers WHERE distance_swum!=0";
    }
    $stmt1 = $conn->prepare($query);
    $stmt1->execute();
    $result = $stmt1->get_result();
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

    <title>Swimmers | Aquadragons Sponsored Swim</title>

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
            <h3 class="page-header">
                <i class="fa fa-fw fa-user"></i> Swimmers
            </h3>
            <h4>
                <?php
                if ($all) {
                    echo '<a href="index.php?all=0"><button class="btn btn-info">Hide Blanks</button></a>';
                } else {
                    echo '<a href="index.php"><button class="btn btn-info">Show All</button></a>';
                }
                ?>
                <a href="add_swimmer.php"><button class="btn btn-success">Add Swimmer</button></a>
            </h4>
        </div>
        <div class="col-md-12">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>JIS</th>
                    <th>Email Address</th>
                    <th>Beneficiary</th>
                    <th>Distance</th>
                    <th>Total Amount</th>
                    <th>Open</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                    echo '<tr><td>'.$row['swimmer_first_name'].'</td><td>'.$row['swimmer_last_name'].'</td><td class="text-center">';
                    if ($row['jis'] == 1) {
                        echo 'Yes';
                    } else {
                        echo 'No';
                    }
                    echo '</td><td class="text-center">'.$row['swimmer_email'].'</td><td class="text-center">';
                    if ($row['beneficiary'] == 0) {
                        echo 'Both';
                    } else if ($row['beneficiary'] == 1) {
                        echo 'H4H';
                    } else if ($row['beneficiary'] == 2) {
                        echo '#JFTI';
                    }
                    echo '</td><td class="text-center">'.number_format($row['distance_swum']).'</td><td class="text-center">'.number_format($row['swimmer_total_amount']).'</td>
                    <td class="text-center"><a href="swimmer.php?swimmer_id='.$row['swimmer_id'].'"><span class="label label-info"><i class="fa fa-chevron-right"></i></span></a></td></tr>';
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