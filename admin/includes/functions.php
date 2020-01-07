<?php

// Undeclared Object Backup Function
// AutoLoad Classes that are not included in our application
function classAutoLoader($class) {

    // Make sure everything is lowercase
    $class = strtolower($class);

    // Include the path
    $the_path = "includes/{$class}.php";

    //Check if the file exists
    if(is_file($the_path) && !class_exists($class)){

        include $the_path;

    }

}

spl_autoload_register('classAutoLoader');

//
function redirect($location){

    header("Location: {$location}");

}