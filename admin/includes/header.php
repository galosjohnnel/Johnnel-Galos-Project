<?php

$page = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);



?>

<nav class="navBar">

      <ul class="navMenu">

         <img src="../images/brigadelogo.png">

         <li class="navItem">

            <a href="admindashboard.php" class="navLink <?= $page =="admindashboard.php" ? 'active': ''?>">

               <i class="bx bx-home-alt-2"></i>

               <span class="navTitle">Dashboard</span>

            </a>

         </li>



         <li class="navItem">

            <a href="addprod.php" class="navLink <?= $page =="addprod.php" ? 'active': ''?>">

               <i class="bx bx-food-menu"></i>

               <span class="navTitle">Products</span>

            </a>

         </li>



         <li class="navItem">

            <a href="useracc.php" class="navLink <?= $page =="useracc.php" ? 'active': ''?>">

               <i class="bx bx-user"></i>

               <span class="navTitle">User Accounts</span>

            </a>

         </li>



         <li class="navItem">

            <a href="vieworder.php" class="navLink <?= $page =="vieworder.php" ? 'active': ''?>">

               <i class="bx bxs-folder-open"></i>

               <span class="navTitle">Order Status</span>

            </a>

         </li>



         <li class="navItem">

            <a href="#" class="navLink" id="logoutLink">

               <i class="bx bx-log-out"></i>

               <span class="navTitle">Logout</span>

               <script>

                  document.getElementById("logoutLink").addEventListener("click", function (event) {

                     event.preventDefault();

                     if (confirm("Are you sure you want to log out?")) {

                        window.location.href = "admin_logout.php";

                     }

                  });

               </script>

            </a>

         </li>

      </ul>

   </nav>