<?php

include_once '../config.php';

session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {

  header("Location: ../login.php");

  exit;

}



$result = mysqli_query($conn, "SELECT * FROM `user_form`");

?>



<!DOCTYPE html>

<html lang="en">



<head>

  <meta charset="UTF-8">

  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

  <link href="../css/style.css" rel="stylesheet">
  <link href="../css/adminMedia.css" rel="stylesheet">

  <link rel="icon" href="../images/favicon.ico" type="image/x-icon">

  <title>User Accounts</title>

</head>





<body>



  <?php

  include('includes/header.php');

  ?>



  <main>



    <?php

    if (mysqli_num_rows($result) > 0) {

      ?>



      <h1>User Accounts</h1>

      <table class="adminTable">

        <thead>

          <tr>

            <th>User ID</th>

            <th>Name</th>

            <th>Email</th>

            <th>Contact Number</th>

          </tr>

        </thead>

        <?php

        $i = 0;

        while ($row = mysqli_fetch_array($result)) {

          ?>

          <tbody>

            <tr>

              <td>

                <?php echo $row["id"]; ?>

              </td>

              <td>

                <?php echo $row["name"]; ?>

              </td>

              <td>

                <?php echo $row["email"]; ?>

              </td>

              <td>

                <?php echo $row["number"]; ?>

              </td>

            </tr>

          </tbody>

          <?php

          $i++;

        }

        ?>

        <?php

    } else {

      echo "No result found";

    }

    ?>

  </main>



</body>



</html>