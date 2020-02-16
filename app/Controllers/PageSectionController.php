<?php

namespace App\Controllers;
use App\Models\PageSection;
use App\Models\DB;

class PageSectionController
{
    private $model;
    private $table;

    public function __construct(DB $db, string $table)
    {
        $this->table=$table;
        $this->model= new PageSection( $db , $this->table);
    }

    public function getAll()
    {
        $all=$this->model->getAll();

       return $all;
    }

}