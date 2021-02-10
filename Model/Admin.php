<?php

namespace dokumentenFreigabe\Model;

use dokumentenFreigabe\Model\Model;


class Admin
{

  private $model;
  
  //do some administrative stuff here like user add, licence giving and so on

  public function __construct(){
    $this->model = new Model();
  }
  
  public function getUserParams(){
    $result = $this->model->select(
            array(
                'user_id',
                'name',
                'groupname',
                'dep_name'
              )
    )->from(
            array(
                'user',
                'usergroup',
                'department')
                )
    ->where('usergroup_id', 'usergroup_id_fk')
    ->where('department_id','department_id_frk')
    ->executeQuery()->as_array();
    
    return $result;
  }

  public function getUser($value){

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
                'department',
                )
            )
  ->where('usergroup_id', 'usergroup_id_fk')
  ->where('department_id','department_id_frk')
  ->where('name', ':name')
  ->executeQuery(':name', $value)->as_object();

  return $result;
  }

  public function updateUser($group, $department, $id){
    $groupID = $this->getGroupID($group);
    $deptID = $this->getDepartmentID($department);
    
    
    $model = new Model();
    $model->update('user')->set(
            array(
                'usergroup_id_fk',
                'department_id_frk',
            ),
            array(
                ':group_id',
                ':dept_id',
            )
      )->where('user_id', ':user_id')
      ->executeQuery(
            array(
                ':group_id',
                ':dept_id',
                ':user_id',
            ), 
            array(
                $groupID->usergroup_id,
                $deptID->department_id,
                $id
            )
      );
      
      $model = new Model();
      return $user = $model->select(
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
                'department',
            )
      )->where('usergroup_id', 'usergroup_id_fk')
      ->where('department_id','department_id_frk')
      ->where('user_id', ':user_id')
      ->executeQuery(':user_id', $id)->as_array();
    }

  public function saveUserData($group, $department, $username, $password){
    $groupID = $this->getGroupID($group)->usergroup_id;
    
    $deptID = !empty($department) ? $this->getDepartmentID($department)->department_id : null ;
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    if(!is_null($deptID)){
      $model = new Model(); 
      $model->insert_into('user')->set(
            array(
                'name',
                'password',
                'usergroup_id_fk',
                'department_id_frk',
              ),
            array(
                ':name',
                ':password',
                ':group_id',
                ':dept_id',
            )
        )->where('user_id', ':user_id')
        ->executeQuery(
            array(
                ':name',
                ':password',
                ':group_id',
                ':dept_id',
          ), 
            array(
                $username,
                $hash,  
                $groupID,
                $deptID,
            )
      );
    } else {
      $model = new Model(); 
      $model->insert_into('user')->set(
            array(
                'name',
                'password',
                'usergroup_id_fk',
              ),
            array(
                ':name',
                ':password',
                ':group_id',
            )
      )->where('user_id', ':user_id')
      ->executeQuery(
            array(
                ':name',
                ':password',
                ':group_id',
                ), 
            array(
                $username,
                $hash,  
                $groupID,
        
        ));
    }
    
      $model = new Model();
      
      return $user = $model->select(
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
              'department'
              )
      )->where('usergroup_id', 'usergroup_id_fk')
      ->where('department_id','department_id_frk')
      ->where('name', ':name')
      ->executeQuery(':name', $username)->as_array();
          
      var_dump($user);
  }
  
  private function getGroupID($group){
    return $group_id = $this->model->select('usergroup_id')->from('usergroup')
    ->where('groupname', ':groupname')
    ->executeQuery(':groupname', $group)->as_object();
  }
  
  private function getDepartmentID($department){
    $model = new Model();
    return $dept_id = $model->select('department_id')->from('department')
    ->where('dep_name', ':dep_name')
    ->executeQuery(':dep_name', $department)->as_object();
  }

  public function deleteUserData($name){
    $userID = $this->getUser($name)->user_id;
    $model = new Model();
    if($model->delete()->from('user')->where('user_id', ':user_id')
    ->executeQuery(':user_id', $userID)){
      return $msg = "User deleted succesfully";
    } else {
      throw new \Exception('User could not be deleted!');
    }
   

    
  }

}