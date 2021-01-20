<?php
namespace schoolyard\Model;

use schoolyard\Model\Db;
use schoolyard\Model\Model;
use schoolyard\Model\Session;
use PDO;

class MysqlSessionHandler extends Model
{

    private $tableNames = array(
        'php_session',
    );

    private $sessionId = 'session_id';
    private $userId;
    private $session;

    public function __construct()
    {

        $this->session = new Session();

    }
    public function populate()
    {

        if (!isset($_SESSION)) {
            session_start();
        }
        $password = $_SESSION['password'];
        var_dump($password);

        $model = new Model();
        $this->userId = $model->select('user_id')->from('user')
            ->where('password', ':loginVariable')
            ->executeQuery(':loginVariable', $password)->as_object()->user_id;

        if (null !== $this->userId) {
            return $this->userId;
        } else {
            throw new NotFoundException();
            echo 'Error: Result is NULL';
        }
    }

    public function findSessionId()
    {

        $this->populate();

        try {
            $model = new Model();
            $this->sessionId = $model->select('session_id')
                ->from('php_session')
                ->join('user')->on('php_session.session_value', 'user.password')
                ->where('user_id_fk', ':id')
                ->executeQuery(':id', $this->userId)
                ->as_object()->session_id;
        } catch (PDOException $e) {

            echo "Error : Session-id could not be investigated", $e->getMessage();
        }
    }

    public function saveSessionData($data)
    {
        try {
            $db = Db::getInstance();

            $user_id_fk = $this->populate();
            $sql = "REPLACE INTO

                        php_session
                SET
                        session_value=:data,

                        user_id_fk=:user_id_fk";

            $stmt = $db->prepare($sql);

            if (!$stmt) {
                echo "\nPDP::errorInfo()\n";
            }

            $stmt->bindParam(':user_id_fk', $user_id_fk, PDO::PARAM_INT);
            $stmt->bindParam(':data', $data, PDO::PARAM_STR);
            $stmt->execute();

            $newId = $db->lastInsertId();

            $count = $stmt->rowCount();
            echo '<br/><p>';

            print("$count Session registered.\n <br/>");

        } catch (PDOException $e) {
            echo "Ein Datenbankfehler ist aufgetreten", $e->getMessage();
        }
    }

    public function getSession()
    {
        try {

            $session_id = $this->findSessionId()->session_id;
            $model = new Model();
            $this->result = $model->select(array(
                'session_value',

            ))->from($this->tableNames)->where('session_id', ':session_id')
                ->executeQuery(':session_id', $session_id)->as_object();

            return $this->result;

        } catch (PDOException $e) {
            echo "Daten aus Tabelle php_session konnten nicht ausgelesen werden.", $e->getMessage();
        }
    }

    public function deleteSession()
    {

        try {

            $this->findSessionId();
            $model = new Model();
            return $model->delete()->from('php_session')
                ->where('session_id', ':session_id')
                ->executeQuery(':session_id', $this->sessionId);

        } catch (PDOException $e) {
            echo "Sesson could not be deleted", $e->getMessage();
        }

    }
}
