<?php

include ('connect.php');

$name = $_POST['name'];
$mobile = $_POST['mobile'];
$password = $_POST['password'];
$cpassword = $_POST['cpassword'];
$address = $_POST['address'];
$image = $_FILES['photo']['name'];
$tmp_img = $_FILES['photo']['tmp_name'];
$role = $_POST['role'];

if ($password == $cpassword) {
    move_uploaded_file($tmp_img, '../uploads/' . $image);

    $insert = mysqli_query(
        $connect,
        "
          INSERT INTO users
          (name, mobile, address, password, photo, role)
          values('$name','$mobile','$address','$password','$image','$role')
        "
    );

    if ($insert) {
        echo '
          <script>
            alert("Registration successfully");
            window.location = "../";
          </script>
        ';
    } else {
        echo '
          <script>
            alert("some error occured");
            window.location = "../routes/register.html";
          </script>
        ';
    }
} else {
    echo '
      <script>
        alret("password and confirm password does not match!");
        window.location = "../routes/register.html";
      </script>
    ';
}

?>
