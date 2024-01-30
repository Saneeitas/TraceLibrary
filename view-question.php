<?php

session_start();

//check if user is not logged in
if (!isset($_SESSION["user"])) {
    header("location: login.php");
}


require "inc/process.php";
require "inc/header.php";
if (isset($_GET["question_id"]) && !empty($_GET["question_id"])) {
    $id = $_GET["question_id"];
    //sql & query
    $sql = "SELECT * FROM questions WHERE id ='$id' ";
    $query = mysqli_query($connection, $sql);
    //result
    $result = mysqli_fetch_assoc($query);
} else {
    header("location: index.php");
}
//session to store url
$_SESSION["url"] = $_GET["question_id"];
?>
<div class="container">
    <?php require './pages/header-home.php'; ?>
    <div class="container-fluid my-3">
        <div class="row">
            <div class="col-8">
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
                <?php
                $cid = $result["course_id"];
                //sql & query to get course_id name
                $sql2 = "SELECT * FROM courses WHERE id='$cid' ";
                $query2 = mysqli_query($connection, $sql2);
                $result2 = mysqli_fetch_assoc($query2);
                ?>
                <div class="row">
                    <div class="col-10">
                        <img style="width:500px; height:500px;" src="<?php echo $result["image"] ?>" alt="">
                    </div>
                    <div class="col-2">
                        <h6>
                            COURSE: <?php echo $result2["name"] ?>
                        </h6>
                        <h6>
                            TITLE: <?php echo $result["course_title"] ?>
                        </h6>
                        <h6>
                            CODE: <?php echo $result["course_code"] ?>
                        </h6>
                        <h6>
                            SESSION: <?php echo $result["session"] ?>
                        </h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require './pages/footer-home.php'; ?>
</div>
<script>
    $(document).ready(function() {
        $("#submitform").submit(function(e) {
            e.preventDefault();
            let form = $(this);
            let formdata = form.serialize();
            //making my first jquery ajax
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: formdata,
                success: function(response) {
                    //iziToast nofification
                    iziToast.success({
                        title: 'info',
                        message: "Successfully added to cart <a href='cart.php'>Go to cart</a>",
                    });
                }
            });

        });
    });
</script>


<?php
require "inc/footer.php";
?>