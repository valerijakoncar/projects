<?php


namespace App\Models;

class PageSection
{
    private $database;
    private $table;

    public function __construct(DB $database, string $table){
        $this->database=$database;
        $this->table=$table;
    }

    public function getAll(){
        if($this->table == 'author'){
            return $this->database->conn->query("SELECT * FROM author")->fetch();
        }
        return $this->database->executeQuery("SELECT * FROM {$this->table}");
    }
}