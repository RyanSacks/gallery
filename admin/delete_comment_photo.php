<?php include("includes/init.php"); ?>

<!-- If the user is not signed in -->
<?php if(!$session->is_signed_in()) { redirect("login.php"); } ?>

<?php

// Check if the Get Request is empty i.e. if we can't find an id
if(empty($_GET['id'])) {

    redirect("../comments.php");

}

// Find the id of that specific user
$comment = Comment::find_by_id($_GET['id']);

// Check if the user exists
if($comment) {

    // If yes Delete it
    $comment->delete();

    $session->message("The comment with id {$comment->id} has been deleted");

    //If no redirect the user
    redirect("comment_photo.php?id={$comment->photo_id}");

} else {

    redirect("comment_photo.php?id={$comment->photo_id}");

}

?>