<?php

namespace dokumentenFreigabe\DataLayer;

use dokumentenFreigabe\DataLayer\Model;
use PDOException;
use PDO;
class AdminMapper
{

    private $model;
    private $increasedPlays;
    private $plays;

    public function getUserParams(){
        
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
    public function getUser($value)
    {
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
                'department',
            )
        )
            ->where('usergroup_id', 'usergroup_id_fk')
            ->where('name', ':name')
            ->executeQuery(':name', $value)->as_object();
              
        return $result;
    }

    public function updateUser($group, $department, $id)
    {
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
                    $id,
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
            ->where('department_id', 'department_id_frk')
            ->where('user_id', ':user_id')
            ->executeQuery(':user_id', $id)->as_array();
    }

    public function saveUserData($group, $department, $username, $password)
    {
        $groupID = $this->getGroupID($group)->usergroup_id;

        $deptID = !empty($department) ? $this->getDepartmentID($department)->department_id : null;
        $hash = password_hash($password, PASSWORD_DEFAULT);

        if (!is_null($deptID)) {
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
                'department',
            )
        )->where('usergroup_id', 'usergroup_id_fk')
            ->where('department_id', 'department_id_frk')
            ->where('name', ':name')
            ->executeQuery(':name', $username)->as_array();

        
    }

    private function getGroupID($group)
    {
        return $group_id = $this->model->select('usergroup_id')->from('usergroup')
            ->where('groupname', ':groupname')
            ->executeQuery(':groupname', $group)->as_object();
    }

    private function getDepartmentID($department)
    {
        $model = new Model();
        return $dept_id = $model->select('department_id')->from('department')
            ->where('dep_name', ':dep_name')
            ->executeQuery(':dep_name', $department)->as_object();
    }

    public function deleteUserData($name)
    {
        $userID = $this->getUser($name)->user_id;
        $model = new Model();
        if (!($model->delete()->from('user')->where('user_id', ':user_id')
            ->executeQuery(':user_id', $userID))) {
            throw new \Exception('User could not be deleted!');
        }
        return true;

    }

    public function getVideoParams($videoName){
        $model = new Model();
        return $dept_id = $model->select(
                array(
                    'video_id',
                    'plays',
                ))->from('video')
            ->where('video_name', ':video_name')
            ->executeQuery(':video_name', $videoName)->as_object();
    }

    public function getPlays($videoName){
        $model = new Model();
        return(!empty($this->plays = $model->select('plays')->from('video')
        ->where('video_name', ':name')
        ->executeQuery(':name', $videoName)->as_object()))?$this->plays = $model->select('plays')->from('video')
        ->where('video_name', ':name')
        ->executeQuery(':name', $videoName)->as_object()->plays:0;
    }

     public function setPlays($videoName){
        $numberOfPlays = $this->getPlays($videoName);
      
        if(is_int($numberOfPlays)){
            $this->increasedPlays = $numberOfPlays+1;
        } else {
            return false;
        }
     }

    

     public function savePlays($videoName){
        $this->setPlays($videoName);
            $model = new Model();
            if($model->update('video')->set(array(
                'plays'
            ),
            array(
                ':plays')
            )->where('video_name', ':name')
            ->executeQuery(
                array(
                ':plays',
                ':name'
                ),
                array(
                    $this->increasedPlays,
                    $videoName
                )
            )){
                return true;
            }
            return false;
          
      
        
        
     }

    public function getNonAdminUsers(){
        $model = new Model();
        $result = $model->select(
            array(
                'name',
             )
        )->from(
            array(
                'user',
            )
        )->join('department')->on('department.department_id' ,'user.department_id_frk')
        ->where('usergroup_id_fk', '!=', 1)
         ->executeQuery()->as_array();
        return $result;
    }

    public function getVideoId($video){
        $model = new Model();
        return $result = $model->select('video_id')->from('video')
        ->where('video_name', ':video_name')
         ->executeQuery(':video_name', $video)->as_object();
    }

    public function changePermission($name, $video){
      
   
        $userID = $this->getUser($name)->user_id;
        $videoID = $this->getVideoId($video)->video_id;
        $model = new Model();
        if($model->insert_into('videoPermissions')->set(
                array(
                    'user_id_frk',
                    'video_id_frk',
                ),
                array(
            
                    ':user_id_fk',
                    ':video_id_fk',
                ))
                ->executeQuery(
                    array(
                        
                        ':user_id_fk',
                        ':video_id_fk',
                    ),
                    array(
                        $userID,
                        $videoID,
                    )
                )
            ){
                return true;
            }

            return false;
    }

    public function getVideoPermissions($user, $video){

        
        $userID = $this->getUser($user)->user_id;
        var_dump($video);
        $videoID = $this->getVideoId($video)->video_id;
     
        $model = new Model();

        if(is_object($result = $model->select('user_video_id')->from('videopermissions')
        ->where('video_id_frk', ':video_id_frk')
        ->where('user_id_frk', ':user_id_frk')
        ->executeQuery(
            array(
                
                ':video_id_frk',
                ':user_id_frk',
                
            ),
            array(
                $videoID,
                $userID
              
            )
        )->as_object())){
            return $result->user_video_id;
        } else {
            return false;
        }
       
    }

    public function setPDFPermission($userName,$pdfName){
       $pdfID = $this->getPDFID($pdfName)->pdf_id;
       $userID = $this->getUser($userName)->user_id;

       $model = new Model();
       if($model->insert_into('pdfpermissions')->set(
        array(
            'user_id_fk',
            'pdf_id_fk',
        ),
        array(
            ':user_id_fk',
            ':pdf_id_fk',
        ))
        ->executeQuery(
            array(
                ':user_id_fk',
                ':pdf_id_fk',
            ),
            array(
                $userID,
                $pdfID,
            )
        )
    ){
        return true;
    }

    return false;

    }

    public function getPDFID($pdfName){
        
        if(strpos($pdfName,'.pdf' ) !== false){
            $pdf_db_entry = trim($pdfName, '.pdf');
            
            $model = new Model();
            $result = $model->select('pdf_id')->from('pdfs')
            ->where('pdf_name', ':pdfName')
            ->executeQuery(':pdfName', $pdf_db_entry)->as_object();
            return $result;
        } else {
            $model = new Model();
            var_dump($pdfName);
            $result = $model->select('pdf_id')->from('pdfs')
            ->where('pdf_name', ':pdf_name')
            ->executeQuery(':pdf_name', $pdfName)->as_object();
            var_dump($result);
             return $result;
        }
       
    }

    public function getPDFPermissions($user, $pdfName){
        
        $userID = $this->getUser($user)->user_id;
        $pdfID = $this->getPDFID($pdfName)->pdf_id;
     
        $model = new Model();

        if(is_object($result = $model->select('pdfPermissions_id')->from('pdfpermissions')
        ->where('pdf_id_fk', ':pdf_id_fk')
        ->where('user_id_fk', ':user_id_fk')
        ->executeQuery(
            array(
                ':pdf_id_fk',
                ':user_id_fk',
                ),
            array(
                $pdfID,
                $userID
              )
        )->as_object())){
            return $result->pdfPermissions_id;
        } else {
            return false;
        }
    }

    public function addPDFToDatabase($pdfName){
         
        $model = new Model();
        if($model->insert_into('pdfs')->set(
            array(
             'pdf_name',
            ),
            array(
             ':pdf_name',
            ))
            ->executeQuery(
            array(
             ':pdf_name',
             ),
            array(
             $pdfName,
            ))
        ){
         return true;
        }
 
        return false;
 
    }

    public function addVideoNameToDatabase($videoName) {
        $model = new Model();
        if($model->insert_into('video')->set(
            array(
             'video_name',
            ),
            array(
             ':video_name',
            ))
            ->executeQuery(
            array(
             ':video_name',
             ),
            array(
             $videoName,
            ))
        ){
         return true;
        }
 
        return false;
 
    }

    public function addDocumentNameToDatabase($documentName) {
        $model = new Model();
        if($model->insert_into('document')->set(
            array(
                'document_name',
            ),
            array(
                ':document_name',
        ))
        ->executeQuery(
            array(
                ':document_name',
            ),
            array(
                $documentName,
            )) 
        ){
            return true;
        }
        return false;
    }

    public function getDocumentID($docxName){
      
            $model = new Model();
            $result = $model->select('document_id')->from('document')
            ->where('document_name', ':docxName')
            ->executeQuery(':docxName', $docxName)->as_object();
             return $result;
        
    }
   
    public function removeFileNameFromDatabase($docxName){
        
        $document_id = $this->getDocumentID($docxName)->document_id;
        
        try {
            $model = new Model();
            $result = $model->delete()->from('document')
            ->where('document_id', ':doc_id')
            ->executeQuery(':doc_id', $document_id);
            return true;

        } catch (PDOException $e) {
            echo "Ein Datenbankfehler ist aufgetreten", $e->getMessage();
            return false;
        }
        
       
    }

    public function setDocxPermission($user, $docxName){
        
        $document_id = $this->getDocumentID($docxName)->document_id;
        $userID = $this->getUser($user)->user_id;

        $model = new Model();
        if($model->insert_into('docxpermissions')->set(
         array(
             'user_id_frk',
             'docx_id_fk',
         ),
         array(
             ':user_id_frk',
             ':docx_id_fk',
         ))
         ->executeQuery(
             array(
                 ':user_id_frk',
                 ':docx_id_fk',
             ),
             array(
                 $userID,
                 $document_id,
             )
         )
     ){
         return true;
     }
 
     return false;

    }


    public function getDocxPermissions($user, $docxName){
        
        $userID = $this->getUser($user)->user_id;
        $document_id = $this->getDocumentID($docxName)->document_id;
     
        $model = new Model();

        if(is_object($result = $model->select('docx_id_fk')->from('docxpermissions')
        ->where('docx_id_fk', ':docx_id_fk')
        ->where('user_id_frk', ':user_id_fk')
        ->executeQuery(
            array(
                ':docx_id_fk',
                ':user_id_fk',
                ),
            array(
                $document_id,
                $userID
              )
        )->as_object())){
            return $result->docx_id_fk;
        } else {
            return false;
        }
    }

}
