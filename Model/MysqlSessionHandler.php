<?php
namespace ISPComplaintsCRM\Model;

use ISPComplaintsCRM\Model\Session;
use ISPComplaintsCRM\Model\Model;
use ISPComplaintsCRM\Model\Db;
use PDO;
 

class MysqlSessionHandler extends Model{
    
    private $tableNames = array(
            'php_session'
    );
    
    private $sessionId;
    private $userId;
    public function populate(){
        
        $session = new Session();
        $login = $session->getSessionName('password');
        var_dump($login);

        $model = new Model();
        $this->userId = $model->select(array('user_id',
                            'name',
                            ))
        ->from('user')
        ->where('password',':loginVariable')
        ->executeQuery(':loginVariable',$login)->as_object();
        
       
        if(null !== $this->userId){
            return $this->userId;
        } else {
            throw new PDOException($e);
            echo 'Error: Result is NULL';
        }
    }
   
    /*public function findSessionId(){
        
            
            $this->userId = $this->populate()->user_id;
            
            try{
                $model = new Model();
                $this->result = $model->select(SESSION_TABLE_ID)
                    ->from(SESSION_TABLE_NAME)
                    ->join('mitarbeiter')->on('php_session.data','mitarbeiter.passwort')
                    ->where('mitarbeiter_id_fk',':id')
                    ->executeQuery(':id',$mitarbeiter_id)
                    ->as_object();
        
                    return $this->result;
        
             } catch(PDOException $e){
        
            echo "Session_id konnte nicht ermittelt werden" , $e->getMessage(); 
        }
    }*/

    public function schreibeNeueSessionDaten($data){
        try{
            $db = Db::getInstance();
            $user_id_fk = $this->populate()->user_id;
            
            $session = new Session();
            var_dump($data = $session->getSessionName('password'));
            $sql="REPLACE INTO
                       
                        php_session 
                SET
                        session_value=:data,

                        user_id_fk=:mitarbeiter_id_fk";
        
            $stmt=$db->prepare($sql);
        
            if(!$stmt){
             echo"\nPDP::errorInfo()\n";
              }
        
            $stmt->bindParam(':mitarbeiter_id_fk', $user_id_fk, PDO::PARAM_INT);
            $stmt->bindParam(':data', $data, PDO::PARAM_STR);
            $stmt->execute();
        
            $newId = $db->lastInsertId();
        
            $count = $stmt->rowCount();
            echo '<br/><p>';
        
           
            print("$count Session registered.\n <br/>");
         
        }
            catch (PDOException $e){
            echo "Ein Datenbankfehler ist aufgetreten", $e->getMessage();
        }
    }
    
    /*public function deleteSessionRanOff(){
        
        try{
       
            
            
            $session_id = $this->findSessionId()->session_id;
            $model = new Model();
            return $model->delete()->from(SESSION_TABLE_NAME)
            ->where(SESSION_TABLE_ID,':session_id')
            ->executeQuery(':session_id',$session_id);
            
            
            } catch (PDOException $e){
                echo "SEssion konnte nicht gel�scht werden", $e->getMessage();
            }
            
    }
    
    public function leseSessionDatenAus($session_id){
        try{
           
            $session_id = $this->findeSessionId()->session_id;
            $model = new Model();
            $this->result = $model->select(array(
                SESSION_NAME_VAR,
                SESSION_START_TIME
            ))->from(SESSION_TABLE_NAME)->where(SESSION_TABLE_ID,':session_id')
            ->executeQuery(':session_id', $session_id)->as_object();
            
            
        
            return $this->result;
        
        } catch (PDOException $e){
            echo "Daten aus Tabelle php_session konnten nicht ausgelesen werden.", $e->getMessage();
        }
    }*/
    
   
        
        public function l�scheSessionAusDb($session_id){
            try{
                
                $db = Db::getInstance();
                $session_id = $this->findeSessionId()->session_id;
                
                $sql = "DELETE FROM "
                            .SESSION_TABLE_NAME.
                        " WHERE "
                            .SESSION_TABLE_ID." =:session_id";
                
                            $stmt=$db->prepare($sql);
                            
                if(!$stmt){
                echo"\nPDO::errorInfo()\n";
                }
            
               $stmt->bindParam(':session_id', $session_id, PDO::PARAM_INT);
               $stmt->execute();
            
            }catch (PDOException $e){
                echo "Session konnte nicht gel�scht werden", $e->getMessage();
            }
        }
        
        public function findeGespeicherteSessionVariablen(){
            
            $db=Db::getInstance();
           
            
           $sql = " SELECT "
                
                .SESSION_NAME_VAR.
                
                 " FROM "
                    
                    .SESSION_TABLE_NAME.";";
                    
             
             $stmt=$db->prepare($sql);
                            
             if(!$stmt){
             
                     echo "\nPDO::errorInfo()\n";
                }
                         
            
             $stmt->execute();
             
             $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
             
             
             
             return $result;
            
        }
    
    
    
}