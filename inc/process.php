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
    // Implement your input sanitization logic here
    // For example, use mysqli_real_escape_string() for SQL injection prevention
    // Use htmlspecialchars() for XSS prevention
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


// if(isset($_POST["register"])){

//     $name = $_POST["name"];
//     $email = $_POST["email"];
//     $password = $_POST["password"];
//     $encrypt_password = md5($password);

//     //check if user exist
//     $sql_check = "SELECT * FROM users WHERE email = '$email'";
//     $query_check = mysqli_query($connection,$sql_check);
//     if(mysqli_fetch_assoc($query_check)){
//         //user exists
//         $error = "User already exist";
//     }else{
//          //insert into DB
//         $sql = "INSERT INTO users(name,email,password) VALUES('$name','$email','$encrypt_password')";
//         $query = mysqli_query($connection,$sql) or die("Cant save data");
//         $success = "Registration successfully";
//     }  
// }

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
    
    $bookId = $_POST['book_id']; 
    $newOwnerId = $_POST['new_owner_id']; 

    // Update ownership in the database
    $sqlTransferOwnership = "UPDATE book_ownership SET user_id = '$newOwnerId', ownership_status = 'borrowed' WHERE book_id = '$bookId'";

   

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    if ($connection->query($sqlTransferOwnership) === TRUE) {
        $success = "Ownership transferred successfully!";
    } else {
        echo "Error: " . $sqlTransferOwnership . "<br>" . $connection->error;
    }

    $connection->close();
}

if (isset($_GET["delete_course"]) && !empty($_GET["delete_course"])) {
    $id = $_GET["delete_course"];
    //sql
    $sql = "DELETE FROM courses WHERE id = '$id'";
    $query = mysqli_query($connection, $sql);

    if ($query) {
        $success = "course deleted";
    } else {
        $error = "Unable to delete course";
    }
}

if (isset($_POST["edit_course"])) {
    $name = $_POST["name"];
    $edit_id = $_GET["edit_id"];
    //sql
    $sql = "UPDATE courses SET name = '$name' WHERE id = '$edit_id'";
    $query = mysqli_query($connection, $sql);
    if ($query) {
        $success = "course updated";
    } else {
        $error = "Unable to update course";
    }
}


if (isset($_POST["new_book"])) {
    $title = $_POST["title"];
    $author = $_POST["author"];
    $isbn = $_POST["isbn"];

    // Insert data into the 'books' table
    $sql = "INSERT INTO books(title, author, isbn) VALUES('$title', '$author', '$isbn')";
    $query = mysqli_query($connection, $sql);

    if ($query) {
        // Get the ID of the inserted book
        $bookId = mysqli_insert_id($connection);

        $userId = $_SESSION["user"]["id"];


        $sqlOwnership = "INSERT INTO book_ownership (user_id, book_id, ownership_status) VALUES ('$userId', '$bookId', 'owned')";
        $queryOwnership = mysqli_query($connection, $sqlOwnership);

        if ($queryOwnership) {
            $success = "Book Added Successfully";
        } else {

            $error = "Unable to add Book (Ownership Error)";
        }
    } else {

        $error = "Unable to add Book";
    }
}


if (isset($_POST["update_book"])) {
    $id = $_GET["edit_book_id"];

    $title = $_POST["title"];
    $author = $_POST["author"];
    $isbn = $_POST["isbn"];
    //sql
    $sql = "UPDATE books SET title ='$title', author='$author',  isbn='$isbn'
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
