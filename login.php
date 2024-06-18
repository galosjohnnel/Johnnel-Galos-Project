<?php

include 'config.php';
session_start();

if (isset($_POST['submit'])) {

   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
   $pass1 = mysqli_real_escape_string($conn, ($_POST['password']));

   $user_select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE email = '$email' AND password = '$pass'") or die('query failed');
   $admin_select = mysqli_query($conn, "SELECT * FROM `adminloc` WHERE email = '$email' AND password = '$pass1'") or die('query failed');

   if (mysqli_num_rows($user_select) > 0) {
      $row = mysqli_fetch_assoc($user_select);
      $_SESSION['user_id'] = $row['id'];
      header('location:index.php');
   } elseif (mysqli_num_rows($admin_select) > 0) {
      $row = mysqli_fetch_assoc($admin_select);
      $_SESSION['is_admin'] = true;
      header('location:admin/admindashboard.php');
   } else {
      $message = "Incorrect username and password.";
      echo "<script>alert('$message');</script>";
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href="css/style.css" rel="stylesheet">
   <link href="css/media.css" rel="stylesheet">
   <link rel="icon" href="images/favicon.ico" type="image/x-icon">
   <title>Login</title>
</head>

<body style="background: linear-gradient(to bottom, #FFA07A, #808080)">

   <div class="loginForm">
      <form action="" method="post">
         <div class="formLogo"><img src="images/brigadelogo.png"></div>
         <div class="textField">
            <input type="email" required name="email">
            <span></span>
            <label>Email</label>
         </div>
         <div class="textField">
            <input type="password" required name="password">
            <span></span>
            <label>Password</label>
         </div>
         <input class="submitButton" type="submit" name="submit" value="LOGIN">
         <div class="signupLink">
            Don't have an account? <a href="register.php">Sign up</a>
         </div>
      </form>
   </div>

</body>

</html>