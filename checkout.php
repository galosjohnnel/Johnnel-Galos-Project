<?php

include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
}
;

if (isset($_GET['logout'])) {
   unset($user_id);
   session_destroy();
   header('location:login.php');
}
;

if (isset($_POST['add_to_cart'])) {

   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if (mysqli_num_rows($select_cart) > 0) {
      $message = "Product is already added to the cart.";
      echo "<script>alert('$message');</script>";
   } else {
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, image, quantity) VALUES('$user_id', '$product_name', '$product_price', '$product_image', '$product_quantity')") or die('query failed');
      $message = "Product added to cart.";
      echo "<script>alert('$message');</script>";
   }
}
;

if (isset($_POST['update_cart'])) {
   $update_quantity = $_POST['cart_quantity'];
   $update_id = $_POST['cart_id'];
   mysqli_query($conn, "UPDATE `cart` SET quantity = '$update_quantity' WHERE id = '$update_id'") or die('query failed');
   $message = "Cart quantity updated.";
   echo "<script>alert('$message');</script>";
}

if (isset($_GET['remove'])) {
   $remove_id = $_GET['remove'];
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$remove_id'") or die('query failed');
   header('location:index.php');
}

if (isset($_GET['delete_all'])) {
   mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   header('location:index.php');
}

$sql = mysqli_query($conn, "SELECT * FROM `order` where user_id = '$user_id' ORDER BY status DESC");
if (isset($_POST['cancel_order_btn'])) {
   $id = $_POST['id'];
   $order_status = 'Cancelled';
   $cancelOrder_query = "UPDATE `order` SET status = '$order_status' WHERE id = '$id'";
   $cancelOrder_query_run = mysqli_query($conn, $cancelOrder_query);
   header('location:index.php');
}

if (isset($_POST['order_btn'])) {

   $name = $_POST['name'];
   $number = $_POST['number'];
   $email = $_POST['email'];
   $method = $_POST['method'];
   $flat = $_POST['flat'];
   $street = $_POST['street'];
   $city = $_POST['city'];
   $zip_code = $_POST['zip_code'];
   $gcash_no = $_POST['gcash_no'];

   $cart_query = mysqli_query($conn, "SELECT * FROM `cart`");
   $price_total = 0;
   if (mysqli_num_rows($cart_query) > 0) {
      while ($product_item = mysqli_fetch_assoc($cart_query)) {
         $product_name[] = $product_item['name'] . ' (' . $product_item['quantity'] . ') ';
         $product_price = (double) $product_item['price'] * (double) $product_item['quantity'];
         $price_total += (double) $product_price;
      }
      ;

      $total_product = implode(', ', $product_name);

      $detail_query = mysqli_query($conn, "INSERT INTO `order`(user_id,name, number, email, method, flat, street, city, zip_code, total_products, total_price,gcash_no) VALUES('$user_id','$name','$number','$email','$method','$flat','$street','$city','$zip_code','$total_product','$price_total', '$gcash_no')") or die('query failed');

      if ($cart_query && $detail_query) {
         $cart_query = mysqli_query($conn, "Delete FROM `cart`");
         $message = "Thank you for shopping! You can track your order in the Account > My Order tab.";
         $target_url = "index.php";
         echo "<script>alert('$message');</script>";
         header("refresh:0;url=$target_url");
         exit;
      }

   } else {
      $messageZeroCart = "Please select product first before checking out.";
      echo "<script>alert('$messageZeroCart');</script>";
   }
   ;


}

$sql = mysqli_query($conn, "SELECT * FROM `order` where user_id = '$user_id' ORDER BY status DESC");

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
   <link href="css/indexStyle.css" rel="stylesheet">
   <link href="css/media.css" rel="stylesheet">
   <link rel="icon" href="images/favicon.ico" type="image/x-icon">
   <title>brigade</title>
</head>

<body>
   <main>
      <header>
         <a href="#" class="navLogo">brigade</a>
         <ul class="navMenu" id="navMenu">
            <a href="javascript:void(0)" id="close"><i class="bx bx-x bx-lg"></i></a>
            <li class="navItem">
               <a href="index.php" class="navLink">Home</a>
            </li>
            <li class="navItem">
               <a href="javascript:void(0)" class="navLink" id="accountButton">Account</a>
            </li>
            <li class="navItem">
               <a href="javascript:void(0)" class="navLink" id="editButton">Cart</a>
            </li>
         </ul>
         <div id="menuBtn">
            <i id="menu" class="bx bx-menu bx-lg"></i>
         </div>
         <script>
            const menu = document.getElementById("menu");
            const close = document.getElementById("close");
            const nav = document.getElementById("navMenu");

            if (menu) {
               menu.addEventListener("click", () => {
                  nav.classList.add("active");
               });
            }

            if (close) {
               close.addEventListener("click", () => {
                  nav.classList.remove("active");
               });
            }
         </script>
      </header>


      <section class="checkOutContainer" id="checkOutContainer">
         <div class="heading">
            <h1>CHECKOUT</h1>
            <h2>Complete your order here!</h2>
         </div>
         <div class="checkOutForm">
            <form action="" method="post">
               <?php
               $sqlAccount = mysqli_query($conn, "SELECT * FROM `user_form` where id = '$user_id'");
               if (mysqli_num_rows($sqlAccount) > 0) {
                  while ($row = mysqli_fetch_assoc($sqlAccount)) { ?>
                     <div class="textField">
                        <input type="text" name="name" value="<?php echo $row['name']; ?>" required>
                        <span></span>
                        <label>Name</label>
                     </div>
                     <div class="textField">
                        <input type="tel" name="number" maxlength="11" value="<?php echo $row['number']; ?>" required>
                        <span></span>
                        <label>Contact Number</label>
                     </div>
                     <div class="textField">
                        <input type="email" name="email" value="<?php echo $row['email']; ?>" required>
                        <span></span>
                        <label>Email</label>
                     </div>

                  <?php }
                  ;
               }
               ; ?>

               <div class="textField">
                  <input type="text" name="flat" required>
                  <span></span>
                  <label>House No.</label>
               </div>
               <div class="textField">
                  <input type="text" name="street" required>
                  <span></span>
                  <label>Street Name</label>
               </div>
               <div class="textField">
                  <input type="text" name="city" required>
                  <span></span>
                  <label>City</label>
               </div>
               <div class="textField">
                  <input type="text" name="zip_code" required>
                  <span></span>
                  <label>Zip Code</label>
               </div>
               <select class="pMethod" id="pMethod" name="method">
                  <option value="COD">Cash on Delivery</option>
                  <option value="GCash">GCash</option>
               </select>
               <div class="textField" id="textBoxdiv" style="display:none;">
                  <input type="text" id="gcash_no" name="gcash_no">
                  <span class="qrIcon"><i class='bx bx-qr-scan'></i></span>
                  <span></span>
                  <label>Reference No.</label>
               </div>
               <div id="upload_field">
                  <div class="fileField">
                     <label>Own Design? Upload it here.</label><br>
                     <input type="file" id="upload_input" name="upload_picture">
                     <span></span>
                  </div>
               </div>
               <script>
                  $(document).ready(function () {
                     $('#pMethod').change(function () {
                        if ($(this).val() == 'GCash') {
                           $('#textBoxdiv').fadeIn();
                           $('#gcash_no').prop('required', true);
                        } else {
                           $('#textBoxdiv').fadeOut();
                           $('#gcash_no').prop('required', false);
                        }
                     });
                  });
               </script>

               <br>
               <input type="submit" value="ORDER NOW" name="order_btn" class="submitButton">
            </form>
         </div>
      </section>

      <?php
      $select_user = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die('query failed');
      if (mysqli_num_rows($select_user) > 0) {
         $fetch_user = mysqli_fetch_assoc($select_user);
      }
      ;
      ?>


      <section class="qrCodeContainer">
         <div id="qrModal">
            <div class="modalQR">
               <div class="topForm">
                  <div class="closeModal">
                     <i class='bx bx-x bx-md'></i>
                  </div>
               </div>
               <div class="qrCodeDiv">
                  <img src="images/qrCode.png" alt="QR Code">
               </div>
            </div>
         </div>
         <script type="text/javascript">
            $(function () {
               $('.qrIcon').click(function () {
                  $('#qrModal').fadeIn().css("display", "flex");
               });

               $('.closeModal').click(function () {
                  $('#qrModal').fadeOut();
               });
            });
         </script>
      </section>

      <section class="accountContainer">
         <div id="accountModal">
            <div class="modalAcc">
               <div class="topForm">
                  <div class="closeModal">
                     <i class='bx bx-x'></i>
                  </div>
               </div>
               <div>
                  <div class="account">
                     <p>Hi, <span>
                           <?php echo $fetch_user['name']; ?>
                        </span> </p>
                     <div class="flex">
                        <a href="javascript:void(0)" id="seeOrder" class="btn">My Order</a>
                        <a href="index.php?logout=<?php echo $user_id; ?>"
                           onclick="return confirm('Are you sure you want to logout?');" class="btn">Logout</a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <script type="text/javascript">
            $(function () {
               $('#accountButton').click(function () {
                  $('#accountModal').fadeIn().css("display", "flex");
               });

               $('.closeModal').click(function () {
                  $('#accountModal').fadeOut();
               });
            });
         </script>
      </section>

      <section class="orderContainer">
         <div id="orderModal">
            <div class="modal">
               <div class="topForm">
                  <div class="closeModal">
                     <i class='bx bx-x'></i>
                  </div>
               </div>
               <div class="addForm">
                  <table class="userTable">
                     <h1>My Order</h1><br>
                     <thead>
                        <tr>
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
                        </tr>
                     </thead>

                     <?php
                     $i = 1;
                     if (mysqli_num_rows($sql) > 0) {
                        while ($row = mysqli_fetch_assoc($sql)) { ?>
                           <tbody>
                              <tr>

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
                                    <form method="POST">
                                       <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                       <?php
                                       if ($row['status'] == 'Pending') {
                                          ?>
                                          <button type="submit" name="cancel_order_btn" class="btn btn-dark mt-1 w-50">Cancel
                                             Order</button>
                                          <?php
                                       }
                                       ?>
                                    </form>
                                 </td>

                              </tr>
                           </tbody>


                        <?php }
                     } ?>
                  </table>
               </div>
            </div>
         </div>
         <script type="text/javascript">
            $(function () {
               $('#seeOrder').click(function () {
                  $('#orderModal').fadeIn().css("display", "flex");
               });

               $('.closeModal').click(function () {
                  $('#orderModal').fadeOut();
               });
            });
         </script>
      </section>

      <section class="shoppingCart">
         <div id="addModal">
            <div class="modal">
               <div class="topForm">
                  <div class="closeModal">
                     <i class='bx bx-x'></i>
                  </div>
               </div>
               <div class="addForm">
                  <table class="userTable">
                     <thead>
                        <tr>
                           <th>Image</th>
                           <th>Name</th>
                           <th>Price</th>
                           <th>Quantity</th>
                           <th>Total Price</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                        $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
                        $grand_total = 0;
                        if (mysqli_num_rows($cart_query) > 0) {
                           while ($fetch_cart = mysqli_fetch_assoc($cart_query)) {
                              ?>
                              <tr>
                                 <td><img src="images/<?php echo $fetch_cart['image']; ?>" height="100" alt=""></td>
                                 <td>
                                    <?php echo $fetch_cart['name']; ?>
                                 </td>
                                 <td>₱
                                    <?php echo $fetch_cart['price']; ?>/-
                                 </td>
                                 <td>
                                    <form action="" method="post">
                                       <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                                       <input type="number" min="1" name="cart_quantity"
                                          value="<?php echo $fetch_cart['quantity']; ?>">
                                       <input type="submit" name="update_cart" value="Update" class="univBtn">
                                    </form>
                                 </td>
                                 <td>₱
                                    <?php echo $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>/-
                                 </td>
                                 <td><a href="index.php?remove=<?php echo $fetch_cart['id']; ?>" class="deleteBtn"
                                       onclick="return confirm('Remove item from cart?');">Remove</a></td>
                              </tr>
                              <?php
                              $grand_total += $sub_total;
                           }
                        } else {
                           echo '<tr><td style="padding:20px; text-transform:capitalize;" colspan="6">No item added.</td></tr>';
                        }
                        ?>
                        <tr>
                           <td class="grandTotal" colspan="4">Grand Total: </td>
                           <td>₱
                              <?php echo $grand_total; ?>/-
                           </td>
                           <td><a href="index.php?delete_all" onclick="return confirm('Delete all items from cart?');"
                                 class="deleteBtn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">Clear
                                 all</a>
                           </td>
                        </tr>
                        <tr>
                           <td colspan="6">
                              <a href="checkout.php"
                                 class="btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">CHECKOUT</a>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
         <script type="text/javascript">
            $(function () {
               $('#editButton').click(function () {
                  $('#addModal').fadeIn().css("display", "flex");
               });

               $('.closeModal').click(function () {
                  $('#addModal').fadeOut();
               });
            });
         </script>
      </section>
   </main>

</body>

</html>