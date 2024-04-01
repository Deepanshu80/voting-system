<?php

session_start();
include ('connect.php');

$mobile = $_POST['mobile'];
$password = $_POST['password'];
$role = $_POST['role'];

$check = mysqli_query(
    $connect,
    "
      SELECT * FROM users
      WHERE
      mobile='$mobile'
      AND password='$password'
      AND role='$role'
    "
);

if (mysqli_num_rows($check) > 0) {
    $userdata = mysqli_fetch_array($check);
    $_SESSION['userdata'] = $userdata;

    if ($role != 3) {
        echo '
          <script>
             window.location = "../routes/dashboard.php";
          </script>
        ';
    } else {
        echo '
          <script>
             window.location = "../routes/admin.php";
          </script>
        ';
    }
} else {
    echo '
      <script>
         alert("Invalid Credentials or user not found!");
         window.location = "../";
      </script>
    ';
}

?>
