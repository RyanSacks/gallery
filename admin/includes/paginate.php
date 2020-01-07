<?php

class Paginate {

    //declare variables
    public $current_page;
    public $items_per_page;
    public $items_total_count;

    // Assign Values to Object Properties
    public function __construct($page=1, $items_per_page=4, $items_total_count=0) {

        $this->current_page = (int)$page;
        $this->items_per_page = (int)$items_per_page;
        $this->items_total_count = (int)$items_total_count;

    }

    // Next Method
    public function next() {

        return $this->current_page + 1;

    }

    // Previous Method
    public function previous() {

        return $this->current_page - 1;

    }

    // Find the Total number of pages
    // Use the Ceil Function to round up
    public function page_total() {

        return ceil($this->items_total_count/$this->items_per_page);

    }

    // Detect Previous
    public function has_previous() {

        return $this->previous() >= 1 ? true : false;

    }

    // Detect Next
    public function has_next() {

        return $this->next() <= $this->page_total() ? true : false;

    }

    // offset
    public function offset() {

        return($this->current_page -1) * $this->items_per_page;

    }

}