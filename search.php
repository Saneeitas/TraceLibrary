<?php
session_start();
require "inc/process.php";
require "inc/header.php";

if (isset($_GET["search_term"])) {
    $search = $_GET["search_term"];
} else {
    $search = '';
}
?>

<div class="container">
    <?php require './pages/header-home.php'; ?>
    <div class="container-fluid my-3">
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="border p-3">
                    <form action="search.php" method="get">
                        <div class="form-group">
                            <h4>Search result for: <?php echo $search; ?></h4>
                            <input type="text" class="form-control" name="search_term" placeholder="Enter Search Keyword " id="" required>

                            <label for="search_category">Search Category:</label>
                            <select name="search_category" class="form-select" required>
                                <option value="title">Title</option>
                                <option value="author">Author</option>
                                <option value="isbn">ISBN</option>
                            </select>
                        </div>
                        <button type="submit" class="btn text-dark mt-2" style="background-color:#74d7ad;">Search</button>
                    </form>
                </div>
            </div>
            <div class="col-8">
                <div class="row">
                    <?php

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

                    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                        // Retrieve search parameters
                        $search_term = $_GET['search_term'];
                        $search_category = $_GET['search_category'];

                        // Build the SQL query based on the selected category
                        $sql = "SELECT books.id, books.title, books.author, books.isbn, books.location,
                           book_ownership.user_id, book_ownership.ownership_status
                    FROM books
                    LEFT JOIN book_ownership ON books.id = book_ownership.book_id
                    WHERE $search_category LIKE ?";
                        $stmt = mysqli_prepare($connection, $sql);

                        // Add '%' to the search term for a partial match
                        $search_term = '%' . $search_term . '%';

                        // Bind the parameter to the statement
                        mysqli_stmt_bind_param($stmt, "s", $search_term);

                        // Execute the statement
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        if (mysqli_num_rows($result) > 0) {
                            // Display search results in a Bootstrap-styled table
                            echo '<table class="table">';
                            echo '<thead>';
                            echo '<tr>';
                            echo '<th>Title</th>';
                            echo '<th>Author</th>';
                            echo '<th>ISBN</th>';
                            echo '<th>Owner</th>';
                            echo '<th>Status</th>';
                            echo '<th>Location</th>';
                            echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';

                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<tr>';
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

                        // Close the statement con
                        
                        mysqli_stmt_close($stmt);
                    }
                    ?>
                </div>
            </div>

        </div>
    </div>
    <?php require './pages/footer-home.php'; ?>
</div>

<?php
require "inc/footer.php";
?>