<?php

session_start();
include ('connect.php');

$votes = $_POST['gvotes'];
$total_votes = $votes + 1;
$gid = $_POST['gid'];
$uid = $_SESSION['userdata']['id'];

$update_votes = mysqli_query($connect, "UPDATE users SET votes='$total_votes' WHERE id='$gid' ");
$update_user_status = mysqli_query($connect, "UPDATE users SET status=1 WHERE id='$uid' ");

if ($update_votes and $update_user_status) {
    $_SESSION['userdata']['status'] = 1;
    echo '
      <script>
        alert("Voting Successfully!");
        window.location = "../routes/dashboard.php";
      </script>
    ';
} else {
    echo '
      <script>
        alert("Some error occured!!");
        window.location = "../routes/dashboard.php";
      </script>
    ';
}

?>
