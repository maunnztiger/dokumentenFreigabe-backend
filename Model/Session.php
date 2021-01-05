<?php
namespace ISPComplaintsCRM\Model;

class Session
{

    /*
     * Session Konstruktor
     * @access public
     */

    public function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

    }

    /*
     * Setzt eine Sessionvariable
     * @param string Name der Session
     * return void
     * @access public
     */
    public function setSessionName($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /*
     * Holt eine Sessionvaribale
     * @param string Name der Variable
     * return mixed
     */

    public function getSessionName($key)
    {
        if (isset($_SESSION[$key])) {

            return $_SESSION[$key];

        } else {

            return false;
        }
    }

    /*
     * L�scht eine Sessionvariable
     * @param string Name der Varibalen
     * return void
     */

    public function deleteSession($key)
    {
        unset($_SESSION[$key]);
    }

    /*
     * Zerst�rt eine Sesssion insgesamt
     * return void
     */

    public function destroyCompleteSession()
    {
        $_SESSION = array();
        session_destroy();
    }

}
