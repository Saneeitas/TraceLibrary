<?php

require "connection.php";

if (isset($_POST["register"])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Input Validation/Sanitization
    $name = sanitizeInput($name);
    $email = sanitizeInput($email);

    // Check if user exists
    if (userExists($email)) {
        $error = "User already exists";
    } else {
        // Hash the password
        $encrypt_password = md5($password);

        // Insert into DB
        if (insertUser($name, $email, $encrypt_password)) {
            $success = "Registration successful";
        } else {
            $error = "Registration failed";
        }
    }
}

// Function to sanitize input to prevent SQL injection and XSS
function sanitizeInput($input)
{
    return $input;
}

// Function to check if a user exists in the database
function userExists($email)
{
    global $connection;

    $email = mysqli_real_escape_string($connection, $email);
    $sql_check = "SELECT * FROM users WHERE email = '$email'";
    $query_check = mysqli_query($connection, $sql_check);

    return mysqli_fetch_assoc($query_check);
}

// Function to insert a new user into the database
function insertUser($name, $email, $password)
{
    global $connection;

    $name = mysqli_real_escape_string($connection, $name);
    $email = mysqli_real_escape_string($connection, $email);
    $password = mysqli_real_escape_string($connection, $password);

    $sql = "INSERT INTO users(name,email,password) VALUES('$name','$email','$password')";
    $query = mysqli_query($connection, $sql);

    return $query;
}


if (isset($_POST["login"])) {

    $email = $_POST["email"];
    $password = $_POST["password"];
    $encrypt_password = md5($password);

    //check if user exist
    $sql_check2 = "SELECT * FROM users WHERE email = '$email'";
    $query_check2 = mysqli_query($connection, $sql_check2);
    if (mysqli_fetch_assoc($query_check2)) {
        //check if email and password exist
        $sql_check = "SELECT * FROM users WHERE email = '$email' 
       AND password = '$encrypt_password'";
        $query_check = mysqli_query($connection, $sql_check);
        if ($result = mysqli_fetch_assoc($query_check)) {
            //Login to dashboard
            $_SESSION["user"] = $result;
            if ($result["role"] == "user") {
                if (isset($_SESSION["url"])) {
                    $question_id = $_SESSION["url"];
                    header("location: view-question.php?question_id=$question_id");
                }
                header("location: dashboard.php");
            } else {
                header("location: dashboard.php");
            }
            $success = "User logged in";
        } else {
            //user password wrong
            $error = "User password wrong";
        }
    } else {
        //user not found
        $error = "User email not found";
    }
}

if (isset($_POST["transfer"])) {

    // Retrieve form data
    $book_id = $_POST['book_id'];
    $user_id = $_SESSION['user']['id'];
    $ownership_status = $_POST['ownership_status'];

    // Check if ownership record already exists for the specified book and user
    $existingOwnershipSql = "SELECT id FROM book_ownership WHERE user_id = ? AND book_id = ?";
    $existingOwnershipStmt = mysqli_prepare($connection, $existingOwnershipSql);
    mysqli_stmt_bind_param($existingOwnershipStmt, "ii", $user_id, $book_id);
    mysqli_stmt_execute($existingOwnershipStmt);
    mysqli_stmt_store_result($existingOwnershipStmt);

    if (mysqli_stmt_num_rows($existingOwnershipStmt) > 0) {
        // Ownership record already exists, update the existing record
        $updateOwnershipSql = "UPDATE book_ownership SET ownership_status = ? WHERE user_id = ? AND book_id = ?";
        $updateOwnershipStmt = mysqli_prepare($connection, $updateOwnershipSql);
        mysqli_stmt_bind_param($updateOwnershipStmt, "sii", $ownership_status, $user_id, $book_id);
        $result = mysqli_stmt_execute($updateOwnershipStmt);

        if ($result) {
            $success =  "Ownership updated successfully!";
        } else {
            $error = "Error updating ownership: " . mysqli_error($connection);
        }

        // Close the statement
        mysqli_stmt_close($updateOwnershipStmt);
    } else {
        // Ownership record doesn't exist, insert a new record
        $insertOwnershipSql = "INSERT INTO book_ownership (user_id, book_id, ownership_status) VALUES (?, ?, ?)";
        $insertOwnershipStmt = mysqli_prepare($connection, $insertOwnershipSql);
        mysqli_stmt_bind_param($insertOwnershipStmt, "iss", $user_id, $book_id, $ownership_status);
        $result = mysqli_stmt_execute($insertOwnershipStmt);

        if ($result) {
            $success = "Ownership marked successfully!";
        } else {
            $error = "Error: " . mysqli_error($connection);
        }

        // Close the statement
        mysqli_stmt_close($insertOwnershipStmt);
    }

    // Close the statement for existing ownership check
    mysqli_stmt_close($existingOwnershipStmt);
}


if (isset($_POST["new_book"])) {
    // Retrieve form data
    $title = $_POST['title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $user_id = $_SESSION['user']['id'];
    $location = isset($_POST["location"]) ? $_POST["location"] : null;

    // Insert data into the 'books' table
    $sql = "INSERT INTO books (user_id,title, author, isbn, location) VALUES (?, ?, ?, ?,?)";
    $stmt = mysqli_prepare($connection, $sql);

    // Bind the parameters to the statement
    mysqli_stmt_bind_param($stmt, "sssis", $user_id, $title, $author, $isbn, $location);

    // Execute the statement
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $success = "Book added successfully!";
    } else {
        $error = "Unable to add Book";
    }

    // Close the statement
    mysqli_stmt_close($stmt);
}


if (isset($_POST["update_book"])) {
    $id = $_GET["edit_book_id"];

    $title = $_POST["title"];
    $author = $_POST["author"];
    $isbn = $_POST["isbn"];
    $location = $_POST["location"];
    //sql
    $sql = "UPDATE books SET title ='$title', author='$author',  isbn='$isbn', location='$location'
                    WHERE id='$id' ";
    $query = mysqli_query($connection, $sql);
    //check if
    if ($query) {
        $success = "Book updated Successfully";
    } else {
        $error = "Unable to update Book";
    }
}

if (isset($_GET["delete_book"]) && !empty($_GET["delete_book"])) {
    $id = $_GET["delete_book"];

    // Prepare and execute the SQL statement to delete from the 'book_ownership' table
    $sqlDeleteOwnership = "DELETE FROM book_ownership WHERE book_id = ?";
    $stmtOwnership = mysqli_prepare($connection, $sqlDeleteOwnership);
    mysqli_stmt_bind_param($stmtOwnership, "i", $id);
    $resultOwnership = mysqli_stmt_execute($stmtOwnership);
    mysqli_stmt_close($stmtOwnership);

    // Check if the deletion from 'book_ownership' was successful
    if ($resultOwnership) {
        // Now, proceed to delete from the 'books' table
        $sqlDeleteBook = "DELETE FROM books WHERE id = ?";
        $stmtBook = mysqli_prepare($connection, $sqlDeleteBook);
        mysqli_stmt_bind_param($stmtBook, "i", $id);
        $resultBook = mysqli_stmt_execute($stmtBook);
        mysqli_stmt_close($stmtBook);

        // Check if the deletion from 'books' was successful
        if ($resultBook) {
            $success = "Book deleted successfully";
        } else {
            $error = "Unable to delete book from 'books' table";
        }
    } else {
        $error = "Unable to delete book from 'book_ownership' table";
    }
}
