<?php
session_start();

require "inc/process.php";
require "inc/header.php";
?>

<div class="container">
    <?php require './pages/header-home.php'; ?>
    <div class="container-fluid my-3">
        <img class="d-block mx-auto mb-4" src="./img/traceLibrary2.PNG" style="border-radius: 15px" alt="" width="950" height="450">
        <div class="row">
            <div class="col-6">
                <div class="p-3 my-3 text-center">
                    <h3 class="display-5 fw-bold" style="color: #74d7ad;">TraceLibrary</h3>
                    <div class="col-lg-6 mx-auto">
                        <p class="lead mb-4">An innovative Book Ownership Tracking System designed to simplify the process of finding book owners, including libraries and individuals.</p>
                        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="p-3 my-3 text-center">
                    <h3 class="display-5 fw-bold" style="color: #74d7ad;">Why Us</h3>
                    <div class="col-lg-6 mx-auto">
                        <p class="lead mb-4">Enhance the accessibility and sharing of books within communities. Whether you're searching for a library that has a specific book or trying to find the current owner of a borrowed book </p>
                        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">

                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <div class="container-fluid my-3 " id="#question">
        <div class="row">

            <div class="col">
                <div class="border p-3 my-3">
                    <h4 class="list-group-item" style="color:#74d7ad;;">
                        <i class="fas fa-grip-vertical"></i> BOOKS
                    </h4>

                    <?php
                    // Include your database connection script here
                    // Example: include 'db_connection.php';

                    function getUserById($connection, $userId)
                    {
                        $sql = "SELECT name FROM users WHERE id = ?";
                        $stmt = mysqli_prepare($connection, $sql);
                        mysqli_stmt_bind_param($stmt, "i", $userId);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        if ($row = mysqli_fetch_assoc($result)) {
                            return $row['name'];
                        } else {
                            return 'N/A';
                        }
                    }

                    $sql = "SELECT books.id, books.title, books.author, books.isbn, books.location, 
                       book_ownership.user_id, book_ownership.ownership_status
                FROM books
                LEFT JOIN book_ownership ON books.id = book_ownership.book_id";
                    $result = mysqli_query($connection, $sql);

                    if ($result) {
                        if (mysqli_num_rows($result) > 0) {
                            echo '<table class="table">';
                            echo '<thead>';
                            echo '<tr>';
                            echo '<th>#</th>';
                            echo '<th>Title</th>';
                            echo '<th>Author</th>';
                            echo '<th>ISBN</th>';
                            echo '<th>Owner</th>';
                            echo '<th>Status</th>';
                            echo '<th>Location</th>';
                            echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';

                            $counter = 1; // Initialize counter

                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<tr>';
                                echo '<td>' . $counter++ . '</td>'; // Display and increment the counter
                                echo '<td>' . $row['title'] . '</td>';
                                echo '<td>' . $row['author'] . '</td>';
                                echo '<td>' . $row['isbn'] . '</td>';
                                echo '<td>' . getUserById($connection, $row['user_id']) . '</td>';
                                echo '<td>' . $row['ownership_status'] . '</td>';
                                echo '<td>' . $row['location'] . '</td>';
                                echo '</tr>';
                            }

                            echo '</tbody>';
                            echo '</table>';
                        } else {
                            echo '<p>No books found.</p>';
                        }
                    } else {
                        echo '<p>Error fetching books: ' . mysqli_error($connection) . '</p>';
                    }

                    // Close the database connection
                    mysqli_close($connection);
                    ?>

                </div>
            </div>
            <div class="col-4">
                <!--Side bar--->
                <div class="border p-3">
                    <form action="search.php" method="get">
                        <div class="form-group">
                            <h5>Search a Book</h5>
                            <input type="text" class="form-control" name="search_term" placeholder="Enter Search Keyword " id="" required>

                            <label for="search_category">Search Category:</label>
                            <select name="search_category" class="form-select" required>
                                <option value="title">Title</option>
                                <option value="author">Author</option>
                                <option value="isbn">ISBN</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-outline-secondary btn-md px-4 mt-2">Search</button>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php require './pages/footer-home.php'; ?>

</div>


<?php require "inc/footer.php"; ?>