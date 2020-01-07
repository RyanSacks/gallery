<?php include("includes/header.php"); ?>

<?php

require_once("admin/includes/init.php");

if(empty($_GET['id'])) {

    redirect("index.php");

}

$photo = Photo::find_by_id($_GET['id']);

$comments = Comment::find_all();

if(isset($_POST['submit'])) {

    $author = trim($_POST['author']);
    $body   = trim($_POST['body']);

    $new_comment = Comment::create_comment($photo->id, $author, $body);

    if($new_comment && $new_comment->save()) {

        $session->message("A new comment has been added");
        redirect("photo.php?id={$photo->id}");

    } else {

        $message = "There was some problems saving";

    }


} else {

    $author = "";
    $body   = "";

}

$comments = Comment::find_the_comments($photo->id);

//Time function
date_default_timezone_set("America/New_York");

?>


<div class="row">

    <!-- Blog Post Content Column -->
    <div class="col-lg-12">

        <!-- Blog Post -->

        <!-- Title -->
        <h1>
            <?php
                if($photo->title) {
                    echo $photo->title;
                } else {
                    echo "Title goes here";
                }
            ?>
        </h1>
        <p class="bg-success"><?php echo $message; ?></p>

        <!-- Author -->
        <p class="lead">
            by <?php
                    if($photo->author) {
                        echo $photo->author;
                    } else {
                        echo "Author name";
                    }
                ?>
        </p>

        <hr>

        <!-- Date/Time -->
        <p><span class="glyphicon glyphicon-time"></span> Posted on <?php echo date('l jS \of F Y h:i:s A'); ?></p>

        <hr>

        <!-- Preview Image -->
        <img class="img-responsive" src="admin/<?php echo $photo->picture_path(); ?>" alt="<?php echo $photo->alternate_text; ?>">

        <hr>

        <!-- Post Content -->
        <p class="lead"><?php echo $photo->caption; ?></p>
        <p><?php echo $photo->description; ?></p>

        <hr>

        <!-- Blog Comments -->

        <!-- Comments Form -->
        <div class="well">
            <h4>Leave a Comment:</h4>
            <form role="form" method="post">
                <div class="form-group">
                    <label for="author">Author</label>
                    <input type="text" name="author" class="form-control">
                </div>
                <div class="form-group">
                    <textarea name="body" class="form-control" rows="3"></textarea>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>

        <hr>

        <!-- Posted Comments -->


        <?php foreach($comments as $comment) : ?>

        <!-- Comment -->
        <div class="media">
            <a class="pull-left" href="#">
                <img class="media-object" src="http://placehold.it/64x64" alt="">
            </a>
            <div class="media-body">
                <h4 class="media-heading"><?php echo $comment->author; ?>
                </h4>
                <?php echo $comment->body; ?>
            </div>
        </div>

        <?php endforeach; ?>

    </div>


    <!-- Blog Sidebar Widgets Column -->
    <!--<div class="col-md-4">-->
    <!---->
    <!--    --><?php //include("includes/sidebar.php"); ?>
    <!---->
    <!--</div>-->
    <!-- /.row -->

</div>

<?php include("includes/footer.php"); ?>
