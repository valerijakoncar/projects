<?php


namespace App\Controllers;


class PageController extends Controller
{
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }
    public function home(){
        $this->view("home", [
            "title" => "Home",
        ]);
    }

    public function contact(){
        $this->view("contact", [
            "title" => "Contact",
        ]);
    }

    public function author(){
        $this->view("author", [
            "title" => "Author",
        ]);
    }

    public function admin(){
        $this->view("admin", [
            "title" => "Admin",
        ]);
    }
}