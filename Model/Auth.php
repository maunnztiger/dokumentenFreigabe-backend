<?php

namespace ISPComplaintsCRM\Model;

use ISPComplaintsCRM\Model\Session;
use ISPComplaintsCRM\Model\MysqlSessionHandler;
use ISPComplaintsCRM\Model\Model;
use ISPComplaintsCRM\Model\Db;

class Auth extends Model {
   
    protected $table_names;
    private $db;
    private $session;
    private $redirect;
    private $sessionHandler;
    private $userId;
    
    public function __construct($user, $pass){
        $this->tableNames = array('user');
        $this->db        = Db::getInstance();
        $this->session   = new Session();
        $this->redirect = 'http://localhost/ISPComplaintsCRM/';
        $this->sessionHandler = new MysqlSessionHandler();
        $this->login($user,$pass);
    }
    
    /*
     * Pr�ft Benutzername und Passwort gegen die Datenbank
     * return void
     * @access private
     */
    private function login($user, $pass) {
         
             
        $model = new Model();
        $password = $model->select('password')->from('user')
        ->where('name', ':name')
        ->executeQuery(':name', $user)->as_object()->password; 
        //var_dump($password);
      
        if(password_verify($pass, $password)) {
            $this->speichereSession($user, $password);
            echo 'Ihr Login war erfolgreich';
        }              
       
    }
    
    /*
     * Setzt die SessionVariable nach erfolgreichem Login
     * @return void
     * @access protected
     */
    
    protected function speichereSession($login, $passwort){
        
        $this->session->setSessionName('login', $login);
        $this->session->setSessionName('password', $passwort);
        $data = $this->session->setSessionName('login_hash', $passwort);
        $this->sessionHandler->schreibeNeueSessionDaten($data);
        
    }
    
    /*
     * Best�tigt, ob ein bestehender Login noch g�ltig ist.
     * return void
     * @access private
     */
    
    private function validateAuth(){
        $passwort = $this->session->getSessionName('password');
        $hashKey = $this->session->getSessionName('login_hash');
        
        if($passwort !== $hashKey ){
            echo 'logout';
            $this->logout(true);
            
        }
         echo 'Auth::validateAuth()';
    }
    
    /*
     * Meldet den Benutzer ab
     * @access public
     */
    
    public function logout($from  = false){
        
        $this->session->deleteSession('name');
        $this->session->deleteSession('password');
        $this->session->deleteSession('login_hash');
        $this->redirect($from);
    }

   
    public function getUserGroup($pass){
        
        $model = new Model();
        $this->userId = $model->select('user_id')
        ->from('user')
        ->where('name',':name')
        ->executeQuery(':name',$pass)->as_object()->user_id;
       
        
        $model = new Model();
        $result = $model->select('groupname')->from(
            array(
                'usergroup',
                'user'
                )
        )->where('usergroup_id_fk', 'user_id')
        ->where('user_id', ':userId')
        ->executeQuery(':userId', $this->userId)->as_object();
     
        return $result;
    }
    
    /*
     * Leitet den Browser um und beendet die Ausfuehrung des Scripts
     * @ param boolean die URL, von der dieser Benutzer kam
     */

    private function redirect($from = true){
        
        if($from){
            header('Location:'. $this->redirect.'?from'.
                $_SERVER['REQUEST_URI']);
        } else {
            header('Location:'.$this->redirect);
        
        }
        echo 'Auth::redirect()';
        exit();
    }


}