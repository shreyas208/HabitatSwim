<?php
/**
 * Created by PhpStorm.
 * User: 33247
 * Date: 15-02-27
 * Time: 22:59
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
    $stmt1 = $conn->prepare("SELECT swimmer_last_name, swimmer_first_name, jis, swimmer_email, beneficiary, distance_swum, swimmer_total_amount FROM swimmers WHERE swimmer_id=?");
    $stmt1->bind_param('s', $swimmer_id);
    $stmt1->execute();
    $stmt1->bind_result($swimmer_last_name, $swimmer_first_name, $jis, $swimmer_email, $beneficiary, $distance_swum, $swimmer_total_amount);
    $stmt1->store_result();
    $stmt1->fetch();
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

    <title>Edit Swimmer: <?php echo $swimmer_first_name.' '.$swimmer_last_name?> | Aquadragons Sponsored Swim</title>

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
                <i class="fa fa-fw fa-user"></i> Edit Swimmer
            </h3>
        </div>
        <div class="col-md-4 col-md-offset-4">
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
                    echo '<p>Current Total Amount: <strong>'.number_format($swimmer_total_amount).'</strong></p>';
                    ?>
                    <form action="swimmer_edit_process.php" method="POST">
                        <div class="form-group">
                            <label for="inputSwimmerEmail">Swimmer Email Address</label>
                            <input type="email" class="form-control" id="inputSwimmerEmail" name="swimmer_email" value="<?php echo $swimmer_email ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Beneficiary</label>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="beneficiary" id="both" value="0" <?php if ($beneficiary == 0) {echo "checked";} ?>>
                                    Both (50/50 Split)
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="beneficiary" id="h4h" value="1" <?php if ($beneficiary == 1) {echo "checked";} ?>>
                                    Habitat for Humanity
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="beneficiary" id="jfti" value="2" <?php if ($beneficiary == 2) {echo "checked";} ?>>
                                    #JusticeForTheInnocent
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputDistanceSwum">Distance Swum (m)</label>
                            <input type="number" class="form-control" id="inputDistanceSwum" name="distance_swum" value="<?php echo $distance_swum ?>" required>
                        </div>
                        <input type="hidden" value="<?php echo $swimmer_id ?>" name="swimmer_id">
                        <a href="swimmer.php?swimmer_id=<?php echo $swimmer_id ?>"><span class="label label-default">CANCEL</span></a>
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
