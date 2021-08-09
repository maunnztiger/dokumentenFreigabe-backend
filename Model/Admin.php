<?php

namespace dokumentenFreigabe\Model;

use dokumentenFreigabe\DataLayer\Model;
use dokumentenFreigabe\DataLayer\AdminMapper;
use PDOException;
use PDO;

class Admin
{

    private $admin;

    public function getUserParams(){
        return $userParams = (new AdminMapper())->getUserParams();
    }

    public function getUser($value){
        return $user = (new AdminMapper())->getUser($value);
    }          

    public function updateUser($group, $department, $id){
        return $updatedUser = (new AdminMapper())->updateUser($group, $department, $id);
    }

    public function saveUserData($group, $department, $username, $password){
        return $savedUser =  (new AdminMapper())->saveUserData($group, $department, $username, $password);
        
    }

    public function deleteUserData($name){
        if((new AdminMapper())->deleteUserData($name)){
            return true;
        }

        return false;
     

    }

    public function getNonAdminUsers(){
        return $nonAdminUsers = (new AdminMapper())->getNonAdminUsers();
   
    }

    public function getVideoId($video){
        return $videoID = (new AdminMapper())->getVideoId($video);
    }

    public function changePermission($name, $video){
        if((new AdminMapper())->changePermission($name, $video)){
                return true;
            }
        return false;
    }

    public function getVideoPermissions($user, $video){
        if($videoPermissions = (new AdminMapper())->getVideoPermissions($user, $video)){
            return $videoPermissions;
        } else {
            return null;
        }
       
    }

    public function setPDFPermission($userName,$pdfName){
        if((new AdminMapper())->setPDFPermission($userName,$pdfName)){
            return true;
        }
        return false;

    }

   

    public function getPDFPermissions($user, $pdfName){
        if($pdfPermissions = (new AdminMapper())->getPDFPermissions($user, $pdfName)){
            return $pdfPermissions;
        } else {
            return null;
        }
    }

    public function addPDFToDatabase($pdfName){
        if((new AdminMapper())->addPDFToDatabase($pdfName)){
            return true;
        }
        return false;
 
    }

    public function addVideoNameToDatabase($videoName) {
        if((new AdminMapper())->addVideoNameToDatabase($videoName)){
         return true;
        }
        return false;
 
    }

    public function addDocumentNameToDatabase($documentName) {

        if((new AdminMapper())->addDocumentNameToDatabase($documentName)
        ){
            return true;
        }
        return false;
    }

    public function getDocumentID($docxName){
      
        $documentID = (new AdminMapper())->getDocumentID($docxName);
        return $documentID;
        
    }
   
    public function removeFileNameFromDatabase($docxName){
        
    try {
        (new AdminMapper())->removeFileNameFromDatabase($docxName);
            return true;
        } catch (PDOException $e) {
            echo "Ein Datenbankfehler ist aufgetreten", $e->getMessage();
            return false;
        }
    }

    public function setDocxPermission($user, $docxName){
        
        if((new AdminMapper())->setDocxPermission($user, $docxName)){
            return true;
        }
        return false;

    }


    public function getDocxPermissions($user, $docxName){
        
        if($docxPermissions = (new AdminMapper())->getDocxPermissions($user, $docxName)){
            return $docxPermissions;
        } else {
            return null;
        }
    }

}
