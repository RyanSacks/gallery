<?php include("includes/init.php"); ?>

<!-- If the user is not signed in -->
<?php if(!$session->is_signed_in()) { redirect("login.php"); } ?>

<?php

// Check if the Get Request is empty i.e. if we can't find an id
if(empty($_GET['id'])) {

    redirect("../users.php");

}

// Find the id of that specific user
$user = User::find_by_id($_GET['id']);

// Check if the user exists
if($user) {

    $session->message("The user {$user->username} has been deleted");

    // If yes Delete it
    $user->delete_photo();

    //If no redirect the user
    redirect("users.php");

} else {

    $session->message("The user {$user->username} has been deleted");
    redirect("users.php");

}

?>