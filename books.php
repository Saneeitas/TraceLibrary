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
                                <th scope="col">Status</th>
                                <th scope="col">Location</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $id = $_SESSION["user"]["id"];
                            $sql = "SELECT books.id, books.title, books.author, books.isbn, books.location, book_ownership.ownership_status
                FROM books
                LEFT JOIN book_ownership ON books.id = book_ownership.book_id
                WHERE books.user_id = '$id'";
                            $query = mysqli_query($connection, $sql);
                            $counter = 1;

                            if (mysqli_num_rows($query) > 0) {
                                // Books are available
                                while ($result = mysqli_fetch_assoc($query)) {
                            ?>
                                    <tr class="table-active">
                                        <td scope="row"><?php echo $counter; ?></td>
                                        <td><?php echo $result["title"]; ?></td>
                                        <td><?php echo $result["author"]; ?></td>
                                        <td><?php echo $result["isbn"]; ?></td>
                                        <td><?php echo $result["ownership_status"] ?? 'Not Owned'; ?></td>
                                        <td><?php echo $result["location"] ?></td>
                                        <td>
                                            <a href="edit-book.php?edit_book_id=<?php echo $result["id"] ?>">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            |
                                            <a href="?delete_book=<?php echo $result["id"]; ?>">
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>
                            <?php
                                    $counter++;
                                }
                            } else {
                                // No books available
                                echo '<tr><td colspan="6">No books available.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>


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