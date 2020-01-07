<?php include("includes/header.php"); ?>

<!-- If the user is not signed in -->
<?php if(!$session->is_signed_in()) { redirect("login.php"); } ?>

<?php

// Set empty variables
$message = "";

// The key file is coming from the dropzone website and input with name file
if(isset($_FILES['file'])) {

    $photo = new Photo();
    $photo->user_id = $_SESSION['user_id'];
    $photo->title = $_POST['title'];
    $photo->set_file($_FILES['file']);

    if($photo->save()) {

        $message = "Photo uploaded successfully";

    } else {

        $message = join("<br>", $photo->errors);

    }

}


?>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <?php include("includes/top_nav.php") ?>


        <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
        <?php include("includes/side_nav.php") ?>


        <!-- /.navbar-collapse -->
    </nav>


    <div id="page-wrapper">

        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        Upload
                    </h1>
                </div> <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">
                <div class="col-md-6">

                    <p class="bg-success"><?php echo $message; ?></p>

                    <form action="upload.php" method="post" enctype="multipart/form-data">

                        <div class="form-group">

                            <input type="text" name="title" class="form-control">

                        </div>

                        <div class="form-group">

                            <input type="file" name="file">

                        </div>

                        <input type="submit" name="submit">

                    </form>

                </div> <!-- /.col -->
            </div> <!-- /.row -->

            <div class="row">

                <br>
                <div class="col-lg-12">

                    <form action="upload.php" class="dropzone"></form>

                </div>

            </div>

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->

<?php include("includes/footer.php"); ?>