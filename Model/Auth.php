<?php

namespace dokumentenFreigabe\Model;

use dokumentenFreigabe\Model\Db;
use dokumentenFreigabe\Model\Model;
use dokumentenFreigabe\Model\MysqlSessionHandler;
use dokumentenFreigabe\Model\Session;

class Auth extends Model
{

    protected $table_names;
    private $db;
    private $session;
    private $redirect;
    private $sessionHandler;
    private $userId;

    public function __construct($user, $pass)
    {
        $this->tableNames = array('user');
        $this->db = Db::getInstance();
        $this->session = new Session();
        $this->redirect = 'http://localhost/dokumentenFreigabe-backend/';
        $this->sessionHandler = new MysqlSessionHandler();
        $this->user = $user;
        $this->pass = $pass;

    }

    /*
     * Pr�ft Benutzername und Passwort gegen die Datenbank
     * return void
     * @access private
     */
    public function login()
    {

        if ($this->session->getSessionName('password')) {
            $this->validateAuth();

        } else {
            $model = new Model();
            $password = $model->select('password')->from($this->tableNames)
                ->where('name', ':name')
                ->executeQuery(':name', $this->user)->as_object()->password;

            if (password_verify($this->pass, $password)) {

                $this->saveSession($password);
            }
        }

    }

    /*
     * Setzt die SessionVariable nach erfolgreichem Login
     * @return void
     * @access protected
     */

    protected function saveSession($password)
    {
        $this->session->setSessionName('login_hash', $password);
        $this->session->setSessionName('password', $password);
        $this->sessionHandler->saveSessionData($password);

    }

    /*
     * Bestaetigt, ob ein bestehender Login noch gueltig ist.
     * return void
     * @access private
     */

    private function validateAuth()
    {
        $hashKey = $this->session->getSessionName('login_hash');
        $passwort = $this->session->getSessionName('password');

        if ($passwort !== $hashKey) {

            $this->logout(true);

        }

    }

    /*
     * Meldet den Benutzer ab
     * @access public
     */

    public function logout($from = false)
    {

        $this->sessionHandler->deleteSession();
        $this->session->deleteSession('password');
        $this->session->deleteSession('login_hash');

        $this->session->destroyCompleteSession();

        //$this->redirect($from);
        return true;
    }

    public function getUserGroup($pass)
    {

        $model = new Model();
        $this->userId = $model->select('user_id')
            ->from('user')
            ->where('name', ':name')
            ->executeQuery(':name', $pass)->as_object()->user_id;

        //var_dump($this->userId);
        $model = new Model();
        $result = $model->select('groupname')->from('usergroup')
            ->join('user')->on('user.usergroup_id_fk', 'usergroup.usergroup_id')
            ->where('user_id', ':userId')
            ->executeQuery(':userId', $this->userId)->as_object();

        return $result;
    }

    /*
     * Leitet den Browser um und beendet die Ausfuehrung des Scripts
     * @ param boolean die URL, von der dieser Benutzer kam
     */

    private function redirect($from = true)
    {

        if ($from) {
            header('Location:' . $this->redirect . '?from' .
                $_SERVER['REQUEST_URI']);
        } else {
            header('Location:' . $this->redirect);

        }

        exit();
    }

    public function destroySession()
    {
        $_SESSION = array();
        session_destroy();
    }

}
