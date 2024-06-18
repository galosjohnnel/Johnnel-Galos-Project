<?php

include_once '../config.php';
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
     header("Location: ../login.php");
     exit;
}

$sql = mysqli_query($conn, "SELECT * FROM `order` ORDER BY status DESC");
if (isset($_GET['id']) && isset($_GET['status'])) {
     $id = $_GET['id'];
     $status = $_GET['status'];
     mysqli_query($conn, "update `order` set status='$status' where id='$id'");
     header("location:vieworder.php");
     die();
}
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
     <title>Order Status</title>
</head>

<body>

     <?php
     include('includes/header.php');
     ?>

     <main>
          <h1>Order Status</h1>
          <table class="adminTable">
               <thead>
                    <tr>
                         <th>User ID</th>
                         <th>Order ID</th>
                         <th>Name</th>
                         <th>Email</th>
                         <th>Number</th>
                         <th>Method</th>
                         <th>Reference No.</th>
                         <th>City</th>
                         <th>Product(s)</th>
                         <th>Price</th>
                         <th>Status</th>
                         <th>Action</th>
                         <th>Order Created</th>

                    </tr>
               </thead>

               <?php
               $i = 1;
               if (mysqli_num_rows($sql) > 0) {
                    while ($row = mysqli_fetch_assoc($sql)) { ?>
                         <tbody>
                              <tr>
                                   <td>
                                        <?php echo $row["user_id"]; ?>
                                   </td>
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
                                   <td>
                                        <?php echo $row["method"]; ?>
                                   </td>
                                   <td>
                                        <?php echo $row["gcash_no"]; ?>
                                   </td>
                                   <td>
                                        <?php echo $row["city"]; ?>
                                   </td>
                                   <td>
                                        <?php echo $row["total_products"]; ?>
                                   </td>
                                   <td>
                                        <?php echo $row["total_price"]; ?>
                                   </td>
                                   <td>
                                        <?php echo $row["status"]; ?>
                                   </td>
                                   <td>
                                        <?php echo $row["action"]; ?>
                                        <select
                                             onchange="status_update(this.options[this.selectedIndex].value,'<?php echo $row['id'] ?>')" <?php echo ($row['status'] == 'Cancelled' || $row['status'] == 'Completed') ? 'disabled' : '' ?>>
                                             <option value="">Update Status</option>
                                             <option value="Pending">Pending</option>
											 <option value="Preparing">Preparing</option>
											 <option value="Shipped">Shipped</option>
                                             <option value="Completed">Completed</option>
											 <option value="Cancelled">Cancelled</option>

                                        </select>
                                   </td>
                                   <td>
                                        <?php echo $row["created_date"]; ?>
                                   </td>
                              </tr>
                         </tbody>


                    <?php }
               } ?>
          </table>
          <script type="text/javascript">
               function status_update(value, id) {
                    let url = "http://foodzillamg.byethost6.com/admin/vieworder.php";
                    window.location.href = url + "?id=" + id + "&status=" + value;
               }
          </script>
     </main>
</body>

</html>