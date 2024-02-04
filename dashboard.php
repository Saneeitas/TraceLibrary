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

                    </div>
                </div>
            </div>
            <div class="col-3">
                <ul class="list-group">
                    <div>
                        <li class="list-group-item" style="color:#74d7ad;">
                            <a href="dashboard.php" class="btn text-danger">
                                <i class="fas fa-grip-vertical" style="color:#74d7ad;"></i> HOME</a>
                        </li>
                        <li class="list-group-item" style="color:#74d7ad;">
                            <a href="ownership.php" class="btn">
                                <i class="fas fa-map-marked-alt" style="color:#74d7ad;"></i> MARK OWNERSHIP</a>

                        </li>
                        <li class="list-group-item">
                            <a href="books.php" class="btn">
                                <i class="fas fa-book" style="color:#74d7ad;"></i> BOOKS</a>

                        </li class="list-group-item">
                        <li class="list-group-item">
                            <a href="new-book.php" class="btn">
                                <i class="fas fa-plus" style="color:#74d7ad;"></i> ADD BOOK</a>
                        </li>
                    </div>
                </ul>
            </div>
            <div class="col-6">
                <?php
                // Include your database connection script here
                // Example: include 'db_connection.php';

                // Function to fetch user information by ID
                function getUserById($connection, $userId)
                {
                    $sql = "SELECT name, email FROM users WHERE id = ?";
                    $stmt = mysqli_prepare($connection, $sql);
                    mysqli_stmt_bind_param($stmt, "i", $userId);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    return mysqli_fetch_assoc($result);
                }

                // Function to fetch owned books by user ID
                function getOwnedBooks($connection, $userId)
                {
                    $sql = "SELECT books.id, books.title, books.author, books.isbn
            FROM books
            JOIN book_ownership ON books.id = book_ownership.book_id
            WHERE book_ownership.user_id = ? AND book_ownership.ownership_status = 'owned'";
                    $stmt = mysqli_prepare($connection, $sql);
                    mysqli_stmt_bind_param($stmt, "i", $userId);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    return mysqli_fetch_all($result, MYSQLI_ASSOC);
                }



                // Function to fetch borrowing history by user ID
                function getBorrowingHistory($connection, $userId)
                {
                    $sql = "SELECT books.id, books.title, books.author, books.isbn, book_ownership.ownership_status
            FROM books
            JOIN book_ownership ON books.id = book_ownership.book_id
            WHERE book_ownership.user_id = ? AND book_ownership.ownership_status = 'borrowed'";
                    $stmt = mysqli_prepare($connection, $sql);
                    mysqli_stmt_bind_param($stmt, "i", $userId);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    return mysqli_fetch_all($result, MYSQLI_ASSOC);
                }

                // Function to fetch available history by user ID
                function getAvailableHistory($connection, $userId)
                {
                    $sql = "SELECT books.id, books.title, books.author, books.isbn, book_ownership.ownership_status
            FROM books
            JOIN book_ownership ON books.id = book_ownership.book_id
            WHERE book_ownership.user_id = ? AND book_ownership.ownership_status = 'available'";
                    $stmt = mysqli_prepare($connection, $sql);
                    mysqli_stmt_bind_param($stmt, "i", $userId);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    return mysqli_fetch_all($result, MYSQLI_ASSOC);
                }

                // Check if the user is logged in
                if (isset($_SESSION['user']) && isset($_SESSION['user']['id'])) {
                    $userId = $_SESSION['user']['id'];
                    $userProfile = getUserById($connection, $userId);
                    $ownedBooks = getOwnedBooks($connection, $userId);
                    $borrowingHistory = getBorrowingHistory($connection, $userId);
                    $availableHistory = getAvailableHistory($connection, $userId);

                    if ($userProfile && $ownedBooks !== null && $borrowingHistory !== null && $availableHistory !== null) {
                        // Display user information
                        echo '<div class="card">';
                        echo '    <div class="card-body">';
                        echo '        <h4 class="card-title">User Profile</h4>';
                        echo '        <p class="card-text"><strong>Name:</strong> ' . $userProfile['name'] . '</p>';
                        echo '        <p class="card-text"><strong>Email:</strong> ' . $userProfile['email'] . '</p>';
                        echo '    </div>';
                        echo '</div>';


                        // Display owned books in a table
                        echo '<h3>Owned Books</h3>';
                        if (count($ownedBooks) > 0) {
                            echo '<table class="table">';
                            echo '<thead>';
                            echo '<tr>';
                            echo '<th>#</th>';
                            echo '<th>Title</th>';
                            echo '<th>Author</th>';
                            echo '<th>ISBN</th>';
                            echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';

                            $counter = 1; // Initialize counter

                            foreach ($ownedBooks as $book) {
                                echo '<tr>';
                                echo '<td>' . $counter++ . '</td>'; // Display and increment the counter
                                echo '<td>' . $book['title'] . '</td>';
                                echo '<td>' . $book['author'] . '</td>';
                                echo '<td>' . $book['isbn'] . '</td>';
                                echo '</tr>';
                            }

                            echo '</tbody>';
                            echo '</table>';
                        } else {
                            echo '<p>No owned books yet.</p>';
                        }

                        // Display available history in a table
                        echo '<h3>Available History</h3>';
                        if (count($availableHistory) > 0) {
                            echo '<table class="table">';
                            echo '<thead>';
                            echo '<tr>';
                            echo '<th>#</th>';
                            echo '<th>Title</th>';
                            echo '<th>Author</th>';
                            echo '<th>ISBN</th>';
                            echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';

                            $counter = 1; // Initialize counter

                            foreach ($availableHistory as $availabledBook) {
                                echo '<tr>';
                                echo '<td>' . $counter++ . '</td>'; // Display and increment the counter
                                echo '<td>' . $availabledBook['title'] . '</td>';
                                echo '<td>' . $availabledBook['author'] . '</td>';
                                echo '<td>' . $availabledBook['isbn'] . '</td>';
                                echo '</tr>';
                            }

                            echo '</tbody>';
                            echo '</table>';
                        } else {
                            echo '<p>No available history.</p>';
                        }

                        // Display borrowing history in a table
                        echo '<h3>Borrowing History</h3>';
                        if (count($borrowingHistory) > 0) {
                            echo '<table class="table">';
                            echo '<thead>';
                            echo '<tr>';
                            echo '<th>#</th>';
                            echo '<th>Title</th>';
                            echo '<th>Author</th>';
                            echo '<th>ISBN</th>';
                            echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';

                            $counter = 1; // Initialize counter

                            foreach ($borrowingHistory as $borrowedBook) {
                                echo '<tr>';
                                echo '<td>' . $counter++ . '</td>'; // Display and increment the counter
                                echo '<td>' . $borrowedBook['title'] . '</td>';
                                echo '<td>' . $borrowedBook['author'] . '</td>';
                                echo '<td>' . $borrowedBook['isbn'] . '</td>';
                                echo '</tr>';
                            }

                            echo '</tbody>';
                            echo '</table>';
                        } else {
                            echo '<p>No borrowing history.</p>';
                        }
                    } else {
                        echo '<p>Error fetching user profile data.</p>';
                    }
                } else {
                    echo '<p>User not logged in.</p>';
                }
                ?>


            </div>
            <div class="col-9">
                <div class="container">
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