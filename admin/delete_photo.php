<?php include("includes/init.php"); ?>

<!-- If the user is not signed in -->
<?php if(!$session->is_signed_in()) { redirect("login.php"); } ?>

<?php

// Check if the Get Request is empty i.e. if we can't find an id
if(empty($_GET['id'])) {

    redirect("../photos.php");

}

// Find the id of that specific photo
$photo = Photo::find_by_id($_GET['id']);

// Check if the photo exists
if($photo) {

    // If yes Delete it
    $photo->delete_photo();

    $session->message("The {$photo->filename} has been deleted");

    //If no redirect the user
    redirect("../admin/photos.php");

} else {

    $session->message("The {$photo->filename} has been deleted");
    redirect("../admin/photos.php");

}

?>