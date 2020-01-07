<?php

class Photo extends Db_object {

    //Create Properties
    protected static $db_table = "photos";
    protected static $db_table_fields = array('id', 'description', 'filename', 'size', 'title', 'type', 'alternate_text', 'caption', 'author', 'user_id');
    public $id;
    public $description;
    public $filename;
    public $size;
    public $title;
    public $type;
    public $alternate_text;
    public $caption;
    public $author;

    // Create a Directory for our images
    public $tmp_path;
    public $upload_directory = "images";

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

    // This is passing $_FILES['uploaded_file'] as an argument

    public function set_file($file) {

        // Check if the File is empty, or if it is not a file, or if it is not an array
        // If there are errors, save them inside the errors[] array
        // Set some properties
        if(empty($file) || !$file || !is_array($file)) {

            $this->errors[] = "There was no file uploaded here";

            return false;

        } else if ($file['error'] !=0) {

            $this->errors[] = $this->upload_errors_array[$file['error']];

            return false;

        } else {

            $this->filename = basename($file['name']);
            $this->tmp_path = $file['tmp_name'];
            $this->type     = $file['type'];
            $this->size     = $file['size'];

        }

    }


    // Dynamic Image path

    public function picture_path() {

        return $this->upload_directory.DS.$this->filename;

    }


    // Save Images into our images directory

    public function save() {

        // Check if the id is there
        if($this->id) {

            $this->update();

        } else {

            if(!empty($this->errors)) {

                return false;

            }

            // Check if the filename or the temp_path are empty
            if(empty($this->filename) || empty($this->tmp_path)) {

                $this->errors[] = "The file was not available";

                return false;

            }

            $target_path = SITE_ROOT . DS . 'admin' . DS . $this->upload_directory . DS . $this->filename;

            // Check if the file already exists
            if(file_exists($target_path)) {

                $this->errors[] = "The file {$this->filename} already exists";

                return false;

            }

            // Move Uploaded Files
            if(move_uploaded_file($this->tmp_path, $target_path)) {

                if($this->create()) {

                    unset($this->tmp_path);
                    return true;

                }

            } else {

                $this->errors[] = "The file directory probably does not have permission";
                return false;

            }

        }

    }


    // Delete Files from the Database and from the server itself by using the unlink function

    public function delete_photo() {

        if($this->delete()) {

            $target_path = SITE_ROOT.DS. 'admin' . DS . $this->picture_path();

            // Return true if you are able to delete the photo or false if you're not
            return unlink($target_path) ? true : false;

        } else {

            return false;

        }

     }


    // Display Sidebar Data
    public static function display_sidebar_data($photo_id) {

        $photo = Photo::find_by_id($photo_id);

        $output = "<a class='thumbnail' href='#'><img width='100' src='{$photo->picture_path()}' ></a> ";
        $output.= "<p>{$photo->filename}</p>";
        $output.= "<p>{$photo->type}</p>";
        $output.= "<p>{$photo->size}</p>";

        echo $output;

    }

} // End Photo Class