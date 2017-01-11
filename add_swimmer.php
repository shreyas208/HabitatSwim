<?php
/**
 * Created by PhpStorm.
 * User: shreyas
 * Date: 16-02-03
 * Time: 01:16
 */

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require_once 'bin/db_config.php';

// Check connection
/*if ($conn->connect_error) {
    echo 'Error';
} else {
    $stmt1 = $conn->prepare("SELECT swimmer_last_name, swimmer_first_name, distance_swum FROM swimmers WHERE swimmer_id=?");
    $stmt1->bind_param('s', $swimmer_id);
    $stmt1->execute();
    $stmt1->bind_result($swimmer_last_name, $swimmer_first_name, $distance_swum);
    $stmt1->store_result();
    $stmt1->fetch();
}*/

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

    <title>Add Swimmer | JIS H4H Sponsored Swim</title>

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
                <i class="fa fa-fw fa-plus"></i> Add Swimmer
            </h3>
        </div>
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form action="add_swimmer_process.php" method="POST">
                        <div class="form-group">
                            <label for="inputSwimmerFirstName">Swimmer First Name</label>
                            <input type="text" class="form-control" id="inputSwimmerFirstName" name="swimmer_first_name" required>
                        </div>
                        <div class="form-group">
                            <label for="inputSwimmerLastName">Swimmer Last Name</label>
                            <input type="text" class="form-control" id="inputSwimmerLastName" name="swimmer_last_name" required>
                        </div>
                        <div class="form-group">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="jis" id="jis" value="1" checked>
                                    JIS Student
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="jis" id="nonJis" value="0">
                                    Non-JIS Student
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputSwimmerEmail">Swimmer Email Address</label>
                            <input type="email" class="form-control" id="inputSwimmerEmail" name="swimmer_email" required>
                        </div>
                        <div class="form-group">
                            <label for="inputDistanceSwum">Distance Swum (m) <i>optional</i></label>
                            <input type="number" class="form-control" id="inputDistanceSwum" name="distance_swum">
                        </div>
                        <a href="index.php"><span class="label label-default">CANCEL</span></a>
                        <button type="submit" class="btn btn-primary">Save</button>
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
