<?php

namespace dokumentenFreigabe\DataLayer;

use dokumentenFreigabe\DataLayer\Mapper;
use dokumentenFreigabe\DataLayer\Model;

class AdminMapper extends Mapper{

    private $selectStmt;
    private $insertStmt;
    private $upateStmt;
    private $deleteStmt;
    /**
     * Laden mehrerer Datensätze
     * @param string $model Klasse des Models
     * @param mixed $parameters Suchkriterien
     * @return object
     */
    
    public function find(){
        
        $this->model = new Model();
        $result = $this->model->select(
            array(
                'user_id',
                'name',
                'groupname',
                'dep_name',
            )
        )->from(
            array(
                'user',
                'usergroup',
                'department')
        )
            ->where('usergroup_id', 'usergroup_id_fk')
            ->where('department_id', 'department_id_frk')
            ->executeQuery()->as_array();

        return $result;
    }

    /**
     * Laden eines Datensatzes
     * @param string $model Klasse des Models
     * @param mixed $parameters Suchkriterien
     * @return object
     */

    public function findFirst($model, $parameters = null){

    }

    /**
     * Speichern eines Datensatzes
     */

    public function persist(){

    }

    /**
     * Löschen eines Datensatzes
     */


    public function remove(){

    }
    /**
     * Ausführen der Datenbankoperationen
     */

    public function flush(){ 
        
    }
}