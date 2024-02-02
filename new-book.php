<?php
session_start();

//check if user is not logged in
if (!isset($_SESSION["user"])) {
    header("location: login.php");
} //check if logged in as user
// if($_SESSION["user"]["role"] == "user"){
//     header("location: all-questions.php");
// }

//header links
require "inc/header.php"; ?>

<div class="container">

    <?php
    //header content
    require './pages/header-home.php';
    include 'inc/process.php';
    ?>

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
                            <a href="dashboard.php" class="btn">
                                <i class="fas fa-grip-vertical" style="color:darkgreen;"></i> HOME</a>
                        </li>
                        <li class="list-group-item" style="color:darkgreen;">
                            <a href="ownership.php" class="btn">
                                <i class="fas fa-grip-vertical" style="color:darkgreen;"></i> MARK OWNERSHIP</a>
                        </li>
                        <li class="list-group-item">
                            <a href="books.php" class="btn">
                                <i class="fas fa-boxes" style="color:darkgreen;"></i> BOOKS</a>
                        </li class="list-group-item">
                        <li class="list-group-item">
                            <a href="new-book.php" class="btn text-danger">
                                <i class="fas fa-plus" style="color:darkgreen;"></i> ADD BOOK</a>
                        </li>
                    </div>
                </ul>
            </div>


            <div class="col-9">
                <div class="container">
                    <h6>New Book</h6>
                    <?php
                    if (isset($error)) {
                    ?>
                        <div class="alert alert-danger">
                            <strong><?php echo $error ?></strong>
                        </div>
                    <?php
                    } elseif (isset($success)) {
                    ?>
                        <div class="alert alert-success">
                            <strong><?php echo $success ?></strong>
                        </div>
                    <?php
                    }
                    ?>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">Title: </label>
                                    <input type="text" name="title" placeholder="Enter Book Title" class="form-control" id="" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="location">Location:</label>
                                    <input type="text" name="location" class="form-control" placeholder="Enter location ">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">Author: </label>
                                    <input type="text" name="author" placeholder="Enter author name" class="form-control" id="" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">ISBN: </label>
                                    <input type="text" name="isbn" placeholder="Enter isbn" class="form-control" id="" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" name="new_book" class="btn btn-sm text-light my-2" style="background-color:darkgreen;">
                                Add Book <i class="fas fa-plus"></i></button>
                        </div>
                </div>
                </form>
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