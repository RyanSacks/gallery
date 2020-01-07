<?php

class User extends Db_object {

    //Create Properties
    protected static $db_table = "users";
    protected static $db_table_fields = array('username', 'password', 'first_name', 'last_name', 'user_image');
    public $id;
    public $username;
    public $password;
    public $first_name;
    public $last_name;
    public $user_image;
    public $upload_directory = "images";
    public $image_placeholder = "http://placehold.it/400x400&text=image";


    // Save Images into our images directory

    public function upload_photo() {

            if(!empty($this->errors)) {

                return false;

            }

            // Check if the user_image or the temp_path are empty
            if(empty($this->user_image) || empty($this->tmp_path)) {

                $this->errors[] = "The file was not available";

                return false;

            }

            $target_path = SITE_ROOT . DS . 'admin' . DS . $this->upload_directory . DS . $this->user_image;

            // Check if the file already exists
            if(file_exists($target_path)) {

                $this->errors[] = "The file {$this->user_image} already exists";

                return false;

            }

            // Move Uploaded Files
            if(move_uploaded_file($this->tmp_path, $target_path)) {

                unset($this->tmp_path);
                return true;

            } else {

                $this->errors[] = "The file directory probably does not have permission";
                return false;

            }

        }


    // Image Placeholder
    public function image_path_and_placeholder() {

        return empty($this->user_image) ? $this->image_placeholder : $this->upload_directory.DS.$this->user_image;

    }


    // Verify the user
    public static function verify_user($username, $password) {

        global $database;

        $username = $database->escape_string($username);
        $password = $database->escape_string($password);

        $sql = "SELECT * FROM " . self::$db_table . " WHERE ";
        $sql .= "username = '{$username}' ";
        $sql .= "AND password = '{$password}' ";
        $sql .= "LIMIT 1";


        $the_result_array = self::find_by_query($sql);

        // If this is Not Empty use array_shift to get the first result of that item, if not return false
        return !empty($the_result_array) ? array_shift($the_result_array) : false;

    }


    // Save the User Image in our database with AJAX
    public function ajax_save_user_image($user_image, $user_id) {

        //
        global $database;

        $user_image = $database->escape_string($user_image);
        $user_id = $database->escape_string($user_id);

        // Assign our variables to our Object properties
        $this->user_image = $user_image;
        $this->id = $user_id;

        $sql = "UPDATE " .self::$db_table . " SET user_image = '{$this->user_image}' ";
        $sql.= " WHERE id = {$this->id} ";
        $update_image = $database->query($sql);

        echo $this->image_path_and_placeholder();


    }


    // Delete Files from the Database and from the server itself by using the unlink function

    public function delete_photo() {

        if($this->delete()) {

            $target_path = SITE_ROOT.DS. 'admin' . DS . $this->upload_directory . DS . $this->user_image;

            // Return true if you are able to delete the photo or false if you're not
            return unlink($target_path) ? true : false;

        } else {

            return false;

        }

    }


    //
    public function photos() {

        return Photo::find_by_query("SELECT * FROM photos WHERE user_id= " . $this->id);

    }


} // End of Class User