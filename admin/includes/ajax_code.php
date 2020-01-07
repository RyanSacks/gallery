<?php require("init.php");

// Instantiate the User Class
$user = new User();


if(isset($_POST['image_name'])) {

    $user->ajax_save_user_image($_POST['image_name'], $_POST['user_id']);

}

if(isset($_POST['photo_id'])) {

    Photo::display_sidebar_data($_POST['photo_id']);

}

?>



