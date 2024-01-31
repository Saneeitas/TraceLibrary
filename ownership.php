<?php
session_start();

//check if user is not logged in
if (!isset($_SESSION["user"])) {
    header("location: login.php");
} //check if logged in as user
// if($_SESSION["user"]["role"] == "user"){
//     header("location: all-questions.php");
// }

include 'inc/connection.php';

// Retrieve books belonging to the current user
$userId = $_SESSION['user']["id"];
$sqlGetUserBooks = "SELECT books.id, books.title FROM books
                    JOIN book_ownership ON books.id = book_ownership.book_id
                    WHERE book_ownership.user_id = '$userId'";
$resultUserBooks = mysqli_query($connection, $sqlGetUserBooks);

if ($resultUserBooks) {
    $userBooks = mysqli_fetch_all($resultUserBooks, MYSQLI_ASSOC);
} else {
    $error = "Error fetching user's books: " . mysqli_error($connection);
}


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
                        <li class="list-group-item" style="color:darkgreen;">
                            <a href="ownership.php" class="btn text-danger">
                                <i class="fas fa-grip-vertical" style="color:darkgreen;"></i> Transfer Onwership</a>
                        </li>
                        <li class="list-group-item">
                            <a href="books.php" class="btn">
                                <i class="fas fa-boxes" style="color:darkgreen;"></i> BOOKS</a>
                        </li class="list-group-item">
                        <li class="list-group-item">
                            <a href="new-book.php" class="btn ">
                                <i class="fas fa-plus" style="color:darkgreen;"></i> ADD BOOK</a>
                        </li>
                    </div>
                </ul>
            </div>


            <div class="col-9">
                <div class="container">
                    <h6>Transfer Boook Onwership</h6>
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
                                    <select name="book_id" class="form-select" selected required>
                                        <?php if (empty($userBooks)) : ?>
                                            <option value="" selected disabled>No books found</option>
                                        <?php else : ?>
                                            <option value="" selected disabled hidden>Select a book</option>
                                            <?php foreach ($userBooks as $book) : ?>
                                                <option value="<?php echo $book['id']; ?>"><?php echo $book['title']; ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>

                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">User: </label>
                                    <select name="new_owner_id" class="form-select" required>
                                        <?php foreach ($allUsers as $user) : ?>
                                            <option value="<?php echo $user['id']; ?>"><?php echo $user['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="form-group">
                            <button type="submit" name="transfer" class="btn btn-sm text-light my-2" style="background-color:darkgreen;">
                                Transfer <i class="fas fa-plus"></i></button>
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