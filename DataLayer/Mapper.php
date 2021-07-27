<?php
namespace dokumentenFreigabe\DataLayer;

use dokumentenFreigabe\DataLayer\Db;
use dokumentenFreigabe\DataLayer\Model;

abstract class Mapper {
    protected $pdo;


    public function __construct(){
        $pdo = Db::getInstance();
    }

    public function find(){
       $this->selectStmt();
    }

    public function findFirst($value) {
        $this->selectStmt($value);
    }

    public function insert($parameters = array()){
        $this->insertStmt($parameters);
    }

    public function update($parameter, $id){
        $this->updateStmt($parameter, $id);
    }

    public function delete($parameter, $id){
        $this->deleteStmt($parameter, $id);
    }

}