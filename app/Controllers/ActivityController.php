<?php


namespace App\Controllers;
use App\Models\Activity;

class ActivityController
{
    private $model;

    public function __construct(){
        $this->model = new Activity();
    }

    public function write($activity){
        return $this->model->write($activity);
    }

    public function loggedInUser(){
        return $this->model->loggedInUser();
    }

    public function loggedOutUser(){
        return $this->model->loggedOutUser();
    }
}