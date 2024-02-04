<?php
session_start();

//check if user is not logged in
if (!isset($_SESSION["user"])) {
    header("location: login.php");
} 

include 'inc/connection.php';




// Retrieve all users
$sqlGetAllUsers = "SELECT id, name FROM users";
$resultAllUsers = mysqli_query($connection, $sqlGetAllUsers);

if ($resultAllUsers) {
    $allUsers = mysqli_fetch_all($resultAllUsers, MYSQLI_ASSOC);
} else {
    $error = "Error fetching all users: " . mysqli_error($connection);
}



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
            <div class="col-3">
                <ul class="list-group">
                    <div>
                        <li class="list-group-item" style="color:#74d7ad;">
                            <a href="dashboard.php" class="btn">
                                <i class="fas fa-grip-vertical" style="color:#74d7ad;"></i> HOME</a>
                        </li>
                        <li class="list-group-item" style="color:#74d7ad;">
                            <a href="ownership.php" class="btn text-danger">
                                <i class="fas fa-map-marked-alt" style="color:#74d7ad;"></i> MARK OWNERSHIP</a>
                        </li>
                        <li class="list-group-item">
                            <a href="books.php" class="btn">
                                <i class="fas fa-book" style="color:#74d7ad;"></i> BOOKS</a>
                        </li class="list-group-item">
                        <li class="list-group-item">
                            <a href="new-book.php" class="btn ">
                                <i class="fas fa-plus" style="color:#74d7ad;"></i> ADD BOOK</a>
                        </li>
                    </div>
                </ul>
            </div>


            <div class="col-9">
                <div class="container">
                    <h6>Mark Book Ownership</h6>
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
                                    <label for="">Book: </label>
                                    <select name="book_id" class="form-select" required>
                                        <?php
                                        // Include your database connection script here
                                        // Example: include 'db_connection.php';

                                        session_start();
                                        $user_id = $_SESSION['user']['id'];

                                        $sql = "SELECT id, title FROM books WHERE user_id = ?";
                                        $stmt = mysqli_prepare($connection, $sql);
                                        mysqli_stmt_bind_param($stmt, "i", $user_id);
                                        mysqli_stmt_execute($stmt);
                                        $result = mysqli_stmt_get_result($stmt);

                                        // Check if there are no books found
                                        if (mysqli_num_rows($result) === 0) {
                                            echo "<option value='' disabled>No books found</option>";
                                        } else {
                                            // Display books in the dropdown
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo "<option value='{$row['id']}'>{$row['title']}</option>";
                                            }
                                        }

                                        // Close the statement
                                        mysqli_stmt_close($stmt);
                                        ?>
                                    </select>

                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="ownership_status">Select Status:</label>
                                    <select name="ownership_status" class="form-select" required>
                                        <option value="available">Available</option>
                                        <option value="owned">Owned</option>
                                        <option value="borrowed">Borrowed</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="form-group">
                            <button type="submit" name="transfer" class="btn btn-sm text-light my-2" style="background-color:#74d7ad;">
                                Mark Ownership <i class="fas fa-plus"></i></button>
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