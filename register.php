<?php

include 'config.php';

if (isset($_POST['submit'])) {

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $number = mysqli_real_escape_string($conn, $_POST['number']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));

   $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE email = '$email' AND password = '$pass'") or die('qfailed');

   if (mysqli_num_rows($select) > 0) {
      echo "<script>";
      echo "alert('User already exists!');";
      echo "</script>";
   } else {
      mysqli_query($conn, "INSERT INTO `user_form`(name, email, password, number) VALUES('$name', '$email', '$pass', '$number')") or die('query failed');
      $message[] = 'Registered successfully!';
      header('location:login.php');
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href="css/media.css" rel="stylesheet">
   <link href="css/style.css" rel="stylesheet">
   <link rel="icon" href="images/favicon.ico" type="image/x-icon">
   <title>Sign Up</title>
</head>

<body style="background: linear-gradient(to bottom, #FFA07A, #808080)">

   <div class="loginForm">
      <form action="" method="post">
         <div class="formLogo"><img src="images/brigadelogo.png"></div>
         <div class="textField">
            <input type="text" name="name" required>
            <span></span>
            <label>Username</label>
         </div>
         <div class="textField">
            <input type="tel" name="number" maxlength="11" required>
            <span></span>
            <label>Contact Number</label>
         </div>
         <div class="textField">
            <input type="email" name="email" required>
            <span></span>
            <label>Email</label>
         </div>
         <div class="textField">
            <input type="password" name="password" required>
            <span></span>
            <label>Password</label>
         </div>
         <input class="submitButton" type="submit" name="submit" value="REGISTER">
         <div class="signupLink">
            Have an account? <a href="login.php">Login</a>
         </div>
      </form>
   </div>

</body>
</html>