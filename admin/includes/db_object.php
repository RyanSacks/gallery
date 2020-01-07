<?php

class Db_object {

    // Create a custom errors array
    public $errors = array();

    // Create a upload errors array
    public $upload_errors_array = array(

        UPLOAD_ERR_OK            => "There is no error.",
        UPLOAD_ERR_INI_SIZE      => "The uploaded file exceeds the upload_max_filesize.",
        UPLOAD_ERR_FORM_SIZE     => "The uploaded file exceeds the MAX_FILE_SIZE directive.",
        UPLOAD_ERR_PARTIAL       => "The uploaded file was only partially uploaded.",
        UPLOAD_ERR_NO_FILE       => "No file was uploaded.",
        UPLOAD_ERR_NO_TMP_DIR    => "Missing a temporary folder.",
        UPLOAD_ERR_CANT_WRITE    => "Failed to write file to disk.",
        UPLOAD_ERR_EXTENSION     => "A PHP extension stopped the file upload."

    );


    // This is passing $_FILES['user_image'] as an argument

    public function set_file($file) {

        // Check if the File is empty, or if it is not a file, or if it is not an array
        // If there are errors, save them inside the errors[] array
        if(empty($file) || !$file || !is_array($file)) {

            $this->errors[] = "There was no file uploaded here";

            return false;

        } else if ($file['error'] !=0) {

            $this->errors[] = $this->upload_errors_array[$file['error']];

            return false;

        } else {

            // Set some properties
            $this->user_image = basename($file['name']);
            $this->tmp_path = $file['tmp_name'];
            $this->type     = $file['type'];
            $this->size     = $file['size'];

        }

    }


    //Create Static Method
    public static function find_all() {

        // Call Static Method with Late Static Binding by using the static keyword instead of the static keyword
        return static::find_by_query("SELECT * FROM " . static::$db_table . " ");

    }


    //Create Static Method
    public static function find_by_id($id) {

        // Make the Object Global so we can use it
        global $database;

        $the_result_array = static::find_by_query("SELECT * FROM " . static::$db_table . " WHERE id=$id LIMIT 1");

        // If this is Not Empty use array_shift else display false
        return !empty($the_result_array) ? array_shift($the_result_array) : false;

    }


    // Find by Query
    public static function find_by_query($sql) {

        global $database;

        $result_set = $database->query($sql);

        $the_object_array = array();

        while($row = mysqli_fetch_array($result_set)) {

            $the_object_array[] = static::instantiation($row);

        }

        return $the_object_array;

    }


    //
    public static function instantiation($the_record) {

        // Use this Late Static Binding Class instead of the static keyword
        $calling_class = get_called_class();

        //Instantiate the Object to its static
        $the_object = new $calling_class;

        foreach ($the_record as $the_attribute => $value) {

            if($the_object->has_the_attribute($the_attribute)) {

                $the_object->$the_attribute = $value;

            }

        }

        return $the_object;

    }


    //
    private function has_the_attribute($the_attribute) {

        // Use this PHP Function to get all of the Properties from the Object
        $object_properties = get_object_vars($this);

        // Find out if the Key exists
        return array_key_exists($the_attribute, $object_properties);

    }

    
    //

    protected function properties() {

        $properties = array();

        foreach (static::$db_table_fields as $db_field) {

            if(property_exists($this, $db_field)) {

                $properties[$db_field] = $this->$db_field;

            }

        }

        return $properties;

    }
    
    
    // Clean the Properties

    protected function clean_properties() {

        global $database;

        $clean_properties = array();

        foreach ($this->properties() as $key => $value) {

            $clean_properties[$key] = $database->escape_string($value);

        }

        return $clean_properties;

    }


    // If a User already exists update the data, otherwise this will create a new record

    public function save() {

        return isset($this->id) ? $this->update() : $this->create();

    }


    // Create Data

    public function create() {

        global $database;

        $properties = $this->clean_properties();

        $sql = "INSERT INTO " . static::$db_table . "(" . implode(",", array_keys($properties)) . ")";
        $sql .= "VALUES ('". implode("','", array_values($properties)) ."')";

        // Test to see if it works
        if($database->query($sql)) {

            $this->id = $database->the_insert_id();

            return true;

        } else {

            return false;

        }

    } // End of Create Method


    // Update Data

    public function update() {

        global $database;

        $properties = $this->clean_properties();

        $properties_pairs = array();

        foreach($properties as $key => $value) {

            $properties_pairs[] = "{$key}='{$value}'";

        }

        $sql = "UPDATE " . static::$db_table . " SET ";
        $sql .= implode(", ", $properties_pairs);
        $sql .= " WHERE id= " . $database->escape_string($this->id);

        $database->query($sql);

        // Test to see if it works
        return (mysqli_affected_rows($database->connection) == 1) ? true : false;

    } // End of Update Method


    // Delete Data

    public function delete() {

        global $database;

        $sql = "DELETE FROM  " . static::$db_table . " ";
        $sql .= "WHERE id=" . $database->escape_string($this->id);
        $sql .= " LIMIT 1";

        $database->query($sql);

        // Test to see if it works
        return (mysqli_affected_rows($database->connection) == 1) ? true : false;

    }


    // Count the number of records in each Table
    public static function count_all() {

        global $database;

        $sql = "SELECT COUNT(*) FROM " . static::$db_table;
        $result_set = $database->query($sql);
        $row = mysqli_fetch_array($result_set);

        // pull out just the number
        return array_shift($row);

    }

}