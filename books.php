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
                            <a href="course.php" class="btn">
                                <i class="fas fa-grip-vertical" style="color:darkgreen;"></i> Transfer Ownership</a>
                        </li>
                        <li class="list-group-item">
                            <a href="books.php" class="btn text-danger">
                                <i class="fas fa-boxes" style="color:darkgreen;"></i> BOOKS</a>
                        </li class="list-group-item">
                        <li class="list-group-item">
                            <a href="new-book.php" class="btn">
                                <i class="fas fa-plus" style="color:darkgreen;"></i> ADD BOOK</a>
                        </li>
                    </div>
                </ul>
            </div>
            <div class="col-9">
                <div class="container">
                    <h6>All Books</h6>
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
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Title</th>
                                <th scope="col">Author</th>
                                <th scope="col">ISBN</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM books";
                            $query = mysqli_query($connection, $sql);
                            $counter = 1;
                            while ($result = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr class="table-active">
                                    <td scope="row"><?php echo $counter; ?></td>
                                    <td><?php echo $result["title"]; ?></td>
                                    <td><?php echo $result["author"]; ?></td>
                                    <td><?php echo $result["isbn"]; ?></td>
                                    <td>
                                        <a href="edit-book.php? edit_book_id=<?php echo $result["id"] ?>">
                                            <i class="fas fa-edit"></i></a>
                                        |
                                        <a href="?delete_book=<?php echo $result["id"]; ?>">
                                            <i class="fas fa-trash-alt text-danger"></i></a>
                                    </td>
                                </tr>
                            <?php
                                $counter++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        <label for="">Title</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" placeholder="Enter title" id="" required>
                        </div>
                        <div class="my-3">
                            <button type="submit" class="btn btn-primary" name="category">Submit</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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