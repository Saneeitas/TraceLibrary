<?php  
session_start();

//check if user is not logged in
if(!isset($_SESSION["user"])){
    header("location: login.php");
}
//check if logged in as user
// if($_SESSION["user"]["role"] == "user"){
//     header("location: index.php");
// }
//header links
 require "inc/header.php"; ?>

 <div class="container">

 <?php
 //header content
 require './pages/header-home.php';
 include 'inc/process.php'; ?>

 <div class="container p-3">
     <div class="row">
         <div class="col-12">
             <div class="row">
                 <div class="col-6"> 
                     <h4>DASHBOARD</h4>  
                 </div>
             </div>
         </div>
         <div class="col-3">
             <ul class="list-group">
                 <div> 
                 <li class="list-group-item" style="color:darkgreen;">
                     <a href="ownership.php" class="btn">
                         <i class="fas fa-grip-vertical"style="color:darkgreen;"></i> Transfer Ownership</a>
                 </li>    
                 <li  class="list-group-item">
                     <a href="books.php" class="btn">
                         <i class="fas fa-boxes" style="color:darkgreen;"></i> BOOKS</a>
                 </li  class="list-group-item">
                 <li  class="list-group-item">
                      <a href="new-book.php" class="btn">
                          <i class="fas fa-plus" style="color:darkgreen;"></i> ADD BOOK</a>
                 </li>
                 </div>
             </ul>
         </div>
         <div class="col-9">
         <div class="container">
            <?php 
                if(isset($error)) {
                ?>
                <div class="alert alert-danger">
                    <strong><?php echo $error ?></strong>
                </div>
                <?php
                    }elseif (isset($success)) {
                ?>
                <div class="alert alert-success">
                <strong><?php echo $success ?></strong>
                </div>
                <?php
            }
            ?>      
                </div> 
         </div>
     </div>
 </div>



<?php  
//footer content
require './pages/footer-home.php'; ?>

 </div>


 <?php
 //footer script
  require "inc/footer.php";  ?>