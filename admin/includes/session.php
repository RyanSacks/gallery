<?php

class Session {

    // Create Properties
    private $signed_in = false;
    public  $user_id;
    public $message;
    public $count;

    //
    function __construct() {

        // Start Session
        session_start();
        $this->visitor_count();
        $this->check_the_login();
        $this->check_message();

    }

    // Count the number of page views
    public function visitor_count() {

        if(isset($_SESSION['count'])) {

            return $this->count = $_SESSION['count']++;

        } else {

            return $_SESSION['count'] = 1;

        }

    }

    // Create a Session for $msg
    public function message($msg="") {

        // Set the information
        if(!empty($msg)) {

            $_SESSION['message'] = $msg;

        } else { // Get the information

            return $this->message;

        }

    }

    // If the message session isset, assign it to the ->message property
    private function check_message(){

        if(isset($_SESSION['message'])) {

            $this->message = $_SESSION['message'];
            unset($_SESSION['message']);

        } else {

            // Set to empty string so we get no errors
            $this->message = "";

        }

    }

    // Check if the User is signed in
    public function is_signed_in() {

        return $this->signed_in;

    }

    //Login
    public function login($user) {

        if($user) {

            $this->user_id = $_SESSION['user_id'] = $user->id;
            $this->signed_in = true;

        }

    }

    //Logout
    public function logout() {

        unset($_SESSION['user_id']);
        unset($this->user_id);
        $this->signed_in = false;

    }

    private function check_the_login() {

        // If the Session isset...
        if(isset($_SESSION['user_id'])) {

            $this->user_id = $_SESSION['user_id'];
            $this->signed_in = true;

        // If the Session is not set...
        } else {

            unset($this->user_id);
            $this->signed_in = false;

        }

    }

}

//Instantiate the Session
$session = new Session();
$message = $session->message();