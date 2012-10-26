<?php

class Page {
  public function __construct($items, $page, $total_entries, $total_pages) {
    $this->items = $items;
    $this->page = $page;
    $this->total_entries = $total_entries;
    $this->total_pages = $total_pages;
  }
}

?>
