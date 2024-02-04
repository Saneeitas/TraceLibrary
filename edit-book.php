<?php
session_start();

//check if user is not logged in
if (!isset($_SESSION["user"])) {
    header("location: login.php");
} 

//header links
require "inc/header.php"; ?>

<div class="container">

    <?php
    //header content
    require './pages/header-home.php';
    include 'inc/process.php';

    //if user click edit
    if (isset($_GET["edit_book_id"]) && !empty($_GET["edit_book_id"])) {
        $edit_book_id = $_GET["edit_book_id"];
        //GET data
        $sql = "SELECT * FROM books WHERE id = '$edit_book_id'";
        $query = mysqli_query($connection, $sql);
        $result = mysqli_fetch_assoc($query);
    } else {
        header("location: books.php");
    }
    ?>

    <div class="container p-3">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-6">
                        <h4> DASHBOARD</h4>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <ul class="list-group">
                    <div>
                        <li class="list-group-item" style="color:#74d7ad;">
                            <a href="dashboard.php" class="btn">
                                <i class="fas fa-grip-vertical" style="color:#74d7ad;"></i> HOME</a>
                        </li>
                        <li class="list-group-item" style="color:#74d7ad;">
                            <a href="ownership.php" class="btn">
                                <i class="fas fa-map-marked-alt" style="color:#74d7ad;"></i> MARK OWNERSHIP</a>
                        </li>
                        <li class="list-group-item">
                            <a href="books.php" class="btn text-danger">
                                <i class="fas fa-book" style="color:#74d7ad;"></i> BOOKS</a>
                        </li class="list-group-item">
                        <li class="list-group-item">
                            <a href="new-book.php" class="btn">
                                <i class="fas fa-plus" style="color:#74d7ad;"></i> ADD BOOK</a>
                        </li>
                    </div>
                </ul>
            </div>
            <div class="col-9">
                <div class="container">
                    <h6>Edit Book</h6>
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
                                    <label for="">Title</label>
                                    <input type="text" name="title" placeholder="Enter title" value="<?php echo $result["title"] ?>" class="form-control" id="">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">Location</label>
                                    <input type="text" name="location" placeholder="Enter location" value="<?php echo $result["location"] ?>" class="form-control" id="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">Author</label>
                                    <input type="text" name="author" placeholder="Enter author" value="<?php echo $result["author"] ?>" class="form-control" id="">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">ISBN</label>
                                    <input type="text" name="isbn" placeholder="Enter isbn" value="<?php echo $result["isbn"] ?>" class="form-control" id="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" name="update_book" class="btn btn-sm my-2 text-light" style="background-color:#74d7ad;">
                                <i class="far fa-edit"></i> Update Book</button>
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