<?php



include '../config.php';

session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {

   header("Location: ../login.php");

   exit;

}







if (isset($_POST['add_product'])) {

   $p_name = $_POST['p_name'];

   $p_price = $_POST['p_price'];

   $p_image = $_FILES['p_image']['name'];

   $p_image_tmp_name = $_FILES['p_image']['tmp_name'];

   $p_image_folder = '../images/' . $p_image;



   $insert_query = mysqli_query($conn, "INSERT INTO `products`(name, price, image) VALUES('$p_name', '$p_price', '$p_image')") or die('query failed');



   if ($insert_query) {

      move_uploaded_file($p_image_tmp_name, $p_image_folder);

      $message = "Product added successfully!";

      echo "<script>alert('$message');</script>";

   } else {

      $message = "Operation failed.";

      echo "<script>alert('$message');</script>";

      ;

   }

}

;



if (isset($_GET['delete'])) {

   $delete_id = $_GET['delete'];

   $delete_query = mysqli_query($conn, "DELETE FROM `products` WHERE id = $delete_id ") or die('query failed');

   if ($delete_query) {

      header('location:addprod.php');

      $message[] = 'Product has been deleted.';

   } else {

      header('location:addprod.php');

      $message[] = 'Operation failed';

   }

   ;

}

;



if (isset($_POST['update_product'])) {

   $update_p_id = $_POST['update_p_id'];

   $update_p_name = $_POST['update_p_name'];

   $update_p_price = $_POST['update_p_price'];

   $update_p_image = $_FILES['update_p_image']['name'];

   $update_p_image_tmp_name = $_FILES['update_p_image']['tmp_name'];

   $update_p_image_folder = '../images/' . $update_p_image;



   $update_query = mysqli_query($conn, "UPDATE `products` SET name = '$update_p_name', price = '$update_p_price', image = '$update_p_image' WHERE id = '$update_p_id'");



   if ($update_query) {

      move_uploaded_file($update_p_image_tmp_name, $update_p_image_folder);

      $message = "Product updated.";

      echo "<script>alert('$message');</script>";

   } else {

      $message = "Operation failed.";

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

   <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

   <link href="../css/style.css" rel="stylesheet">

   <link rel="icon" href="../images/favicon.ico" type="image/x-icon">

   <link href="../css/adminMedia.css" rel="stylesheet">

   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

   <title>Manage Products</title>

</head>



<body>



   <?php

   include('includes/header.php');

   ?>



   <main>

      <h1>Manage Products</h1>

      <div class="displayProductTable">

         <table class="adminTable">

            <thead>

               <th>Product Image</th>

               <th>Product Name</th>

               <th>Product Price</th>

               <th>Action</th>

            </thead>



            <tbody>

               <?php

               $select_products = mysqli_query($conn, "SELECT * FROM `products`");

               if (mysqli_num_rows($select_products) > 0) {

                  while ($row = mysqli_fetch_assoc($select_products)) {

                     ?>



                     <tr>

                        <td><img src="../images/<?php echo $row['image']; ?>" height="100" alt=""></td>

                        <td>

                           <?php echo $row['name']; ?>

                        </td>

                        <td>₱

                           <?php echo $row['price']; ?>/-

                        </td>

                        <td>

                           <a title="Delete Product" href="addprod.php?delete=<?php echo $row['id']; ?>" class="univBtn"

                              onclick="return confirm('Are you sure you want to delete this product?');"> <i

                                 class="bx bx-trash"></i></a>

                           <a title="Update Details" href="addprod.php?edit=<?php echo $row['id']; ?>" id="editButton"

                              class="univBtn"> <i class="bx bx-upload"></i></a>

                        </td>

                     </tr>



                     <?php

                  }

                  ;

               } else {

                  $message = "No existing products.";

                  echo "<script>alert('$message');</script>";

                  ;

               }

               ;

               ?>

            </tbody>

         </table>

      </div>



      <section class="addProductContainer">

         <div class="button">

            <button class="addButton">Add a Product</button>

         </div>

         <div id="addModal">

            <div class="modal">

               <div class="topForm">

                  <div class="closeModal">

                     <i class='bx bx-x'></i>

                  </div>

               </div>

               <div class="addForm">

                  <form action="" method="post" enctype="multipart/form-data">

                     <h3>Add a new product</h3>

                     <input type="text" name="p_name" placeholder="Enter the product name" required>

                     <input type="number" name="p_price" min="0" placeholder="Enter the product price" required>

                     <input type="file" name="p_image" accept="image/png, image/jpg, image/jpeg" required>

                     <input type="submit" value="SUBMIT" name="add_product" class="btn">

                  </form>

               </div>

            </div>

         </div>

         <script type="text/javascript">

            $(function () {

               $('.addButton').click(function () {

                  $('#addModal').fadeIn().css("display", "flex");

               });



               $('.closeModal').click(function () {

                  $('#addModal').fadeOut();

               });

            });

         </script>

      </section>



      <section class="editFormContainer">

         <?php

         if (isset($_GET['edit'])) {

            $edit_id = $_GET['edit'];

            $edit_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = $edit_id");

            if (mysqli_num_rows($edit_query) > 0) {

               while ($fetch_edit = mysqli_fetch_assoc($edit_query)) {

                  ?>

                  <div id="editModal">

                     <div class="modal">

                        <div class="addForm">

                           <h3>Edit Product</h3>

                           <form action="" method="post" enctype="multipart/form-data">

                              <div class="imgBox"><img src="../images/<?php echo $fetch_edit['image']; ?>" alt=""></div><br>

                              <input type="hidden" name="update_p_id" value="<?php echo $fetch_edit['id']; ?>">

                              <input type="text" required name="update_p_name" value="<?php echo $fetch_edit['name']; ?>">

                              <input type="number" min="0" required name="update_p_price"

                                 value="<?php echo $fetch_edit['price']; ?>">

                              <input type="file" required name="update_p_image" accept="image/png, image/jpg, image/jpeg">

                              <input type="submit" value="UPDATE" name="update_product" class="btn">

                              <input type="reset" value="CANCEL" id="closeEdit" class="btn">

                           </form>

                        </div>

                     </div>

                  </div>

                  <?php

               }

               ;

            }

            ;

         }

         ;

         ?>

         <script type="text/javascript">

            $(function () {

               $('#editButton').click(function () {

                  $('#editModal').fadeIn().css("display", "flex");

               });



               $('#closeEdit').click(function () {

                  $('#editModal').fadeOut();

               });

            });

         </script>

      </section>

   </main>



</body>



</html>