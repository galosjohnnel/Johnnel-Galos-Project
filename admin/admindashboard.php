<!DOCTYPE html>
<?php
include_once '../config.php';
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
  header("Location: ../login.php");
  exit;
}


?>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <link href="../css/style.css" rel="stylesheet">
  <link href="../css/adminMedia.css" rel="stylesheet">
  <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
  <title>Admin Landing Page</title>
</head>

<body>

  <?php
  include('includes/header.php');
  ?>

  <main>
    <h1>Dashboard</h1>
    <h2>Hello, Admin</h2>

    <section class="dashboard">
      <div class="card">
        <div class="cardIcon">
          <i class='bx bxs-dollar-circle'></i>
        </div>
        <div class="cardContent">
          <h3 class="cardTitle">Total Sales</h3>
          <?php
          $result = mysqli_query($conn, "SELECT id,total_price FROM `order` WHERE status <> 'cancelled'");
          $total_sales = 0;
          while ($row = mysqli_fetch_assoc($result)) {
            $total_sales += $row['total_price'];
          }
          echo '<p class="cardValue">₱' . number_format($total_sales, 2) . '</p>';
          ?>
        </div>
      </div>

      <div class="card">
        <div class="cardIcon">
          <i class='bx bxs-check-circle'></i>
        </div>
        <div class="cardContent">
          <h3 class="cardTitle">Completed Sales</h3>
          <?php
          $order_status = 'Completed';
          $total_sales = 0;
          $result = mysqli_query($conn, "SELECT id,total_price FROM `order` WHERE status = '$order_status'");
          while ($row = mysqli_fetch_assoc($result)) {
            $total_sales += $row['total_price'];
          }
          echo '<p class="cardValue">₱' . number_format($total_sales, 2) . '</p>';
          ?>
        </div>
      </div>

      <div class="card">
        <div class="cardIcon">
          <i class='bx bxs-check-circle'></i>
        </div>
        <div class="cardContent">
          <h3 class="cardTitle">Completed Orders</h3>
          <?php
          $order_status = 'Completed';
          $result = mysqli_query($conn, "SELECT id,total_price FROM `order` WHERE status = '$order_status'");
          $total_orders = mysqli_num_rows($result);
          echo '<p class="cardValue">' . $total_orders . '</p>';
          ?>
        </div>
      </div>

      <div class="card">
        <div class="cardIcon">
          <i class='bx bxs-error-circle'></i>
        </div>
        <div class="cardContent">
          <h3 class="cardTitle">Pending Orders</h3>
          <?php
          $order_status = 'Pending';
          $result = mysqli_query($conn, "SELECT id,total_price FROM `order` WHERE status = '$order_status'");
          $total_orders = mysqli_num_rows($result);
          echo '<p class="cardValue">' . $total_orders . '</p>';
          ?>
        </div>
      </div>

      <div class="card">
        <div class="cardIcon">
          <i class='bx bxs-user'></i>
        </div>
        <div class="cardContent">
          <h3 class="cardTitle">Customers</h3>
          <?php
          $result = mysqli_query($conn, "SELECT COUNT(*) as total_accounts FROM user_form");
          $row = mysqli_fetch_assoc($result);
          $total_accounts = $row['total_accounts'];
          echo '<p class="cardValue">' . $total_accounts . '</p>';
          ?>
        </div>
      </div>
    </section>

  </main>
</body>

</html>