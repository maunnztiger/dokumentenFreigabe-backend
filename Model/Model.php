<?php
/** @license    MIT License
 * Model Class
 */

namespace dokumentenFreigabe\Model;

use dokumentenFreigabe\Model\Db;
use PDO;

@define(SQL_COMMAND_SELECT, 'SELECT');
@define(SQL_COMMAND_COUNT, 'COUNT');
@define(SQL_COMMAND_DISTINCT, 'DISTINCT');
@define(SQL_COMMAND_FROM, 'FROM');
@define(SQL_COMMAND_UPDATE, 'UPDATE');
@define(SQL_COMMAND_DELETE, 'DELETE');
@define(SQL_COMMAND_INSERT, 'INSERT INTO');
@define(SQL_COMMAND_VALUES, 'VALUES');
@define(SQL_COMMAND_SET, 'SET');

@define(SQL_CLAUSE_JOIN, 'JOIN');
@define(SQL_CLAUSE_ON, 'ON');
@define(SQL_CLAUSE_INNER, 'INNER');
@define(SQL_CLAUSE_LIMIT, 'LIMIT');

@define(JOIN_LEFT, 'LEFT');
@define(JOIN_RIGHT, 'RIGHT');
@define(JOIN_INNER, 'INNER');

@define(SQL_STATEMENT_WHERE, 'WHERE');
@define(SQL_STATEMENT_AND, 'AND');
@define(SQL_STATEMENT_OR, 'OR');
@define(SQL_STATEMENT_GROUP_BY, 'GROUP BY');
@define(SQL_STATEMENT_ORDER_BY, 'ORDER BY');

@define(OPERATOR_EQUAL, '=');
@define(OPERATOR_NOT_EQUAL, '!=');
@define(OPERATOR_GT, '>');
@define(OPERATOR_GT_OR_EQUAL, '>=');
@define(OPERATOR_LT, '<');
@define(OPERATOR_LT_OR_EQUAL, '<=');
@define(OPERATOR_BETWEEN, 'BETWEEN');
@define(OPERATOR_LIKE, 'LIKE');
@define(OPERATOR_IN, 'IN');

class Model extends Db
{

    protected $class;
    protected $table_names = array();
    protected $table_primary_keys = array();
    protected $table_properties = array();
    protected $update = array();
    protected $from_cache = false;

    private $id = null;
    private $data = array();
    private $select = null;
    private $insert = null;
    private $delete = null;
    private $count = null;
    private $set = array();
    private $operator = null;
    private $distinct = false;
    private $from = null;
    private $query = null;
    private $where = array();
    private $order_by = null;
    private $group_by = null;
    private $result = null;
    private $joins = null;
    private $join = array();
    private $on = null;
    private $limit = null;
    private $db = null;
    private $stmt = null;
    private $spalte = null;
    private $value = null;
    private $row = array();

    private $allowed_operators = array(
        OPERATOR_EQUAL,
        OPERATOR_NOT_EQUAL,
        OPERATOR_GT,
        OPERATOR_GT_OR_EQUAL,
        OPERATOR_LT,
        OPERATOR_LT_OR_EQUAL,
        OPERATOR_BETWEEN,
        OPERATOR_LIKE,
        OPERATOR_IN,
    );

    public function count($spalte = null)
    {

        if (empty($spalte)) {

            $this->count .= ' ' . SQL_COMMAND_COUNT . $this->
                formatiereOperator('*');
        }

        if (is_string($spalte)) {

            $this->count .= ' ' . SQL_COMMAND_COUNT .
            $this->formatiereOperator($spalte);
        }

        return $this;
    }

    /**
     *
     * @param $spalte
     * @return Model
     */
    public function select($spalte = null)
    {

        if (is_string($spalte)) {

            if ($spalte === SQL_COMMAND_COUNT) {

                $this->count();
                $this->select .= $this->count;

            } else {

                $this->select .= $spalte;
            }

        } elseif ((null === $spalte) && !is_array($spalte)) {

            $spalte .= '*';
            $this->select .= $spalte;

        } else if (is_array($spalte)) {

            $spalten = count($spalte);
            $count = 1;

            foreach ($spalte as $value) {

                if ($spalten == 1) {

                    $this->select .= $this->backticks($value);
                } else {

                    if ($spalten > $count) {

                        $this->select .= $this->backticks($value) . ',';
                    } else {

                        $this->select .= $this->backticks($value);
                    }

                }
                $count++;

            }
        }

        return $this;
    }

    /**
     *
     * @throws PDOException
     * @return mixed
     */
    public function table()
    {

        $controller = ucfirst(isset($_GET['controller']) ? $_GET['controller'] : 'benutzer');
        strtolower($klassenName = $controller);
        $objekt = new $klassenName;

        try
        {
            if (!is_array($this->table_names)) {
                throw new PDOException(__CLASS__ . '::' . __FUNCTION__ . '(): Model ' . $klassenName . ' has no Table property!');

            } else {
                $table_name = $objekt->table_names[0];
            }

        } catch (PDOException $e) {
            echo $e;
        }

        return $table_name;
    }

    /**
     *
     * @param $spalte
     * @param string $richtung
     * @return Model
     */
    public function order_by($spalte, $richtung = 'DESC')
    {
        $this->order_by = ' ' . SQL_STATEMENT_ORDER_BY . ' ' . $this->backticks($spalte) . ' ' . $richtung;

        return $this;
    }

    /**
     * Erstellt die From-Klausel mit den TabellenNamen in den backticks
     * @param $table_name
     * @return Model
     */
    public function from($table_name = null)
    {

        if (null === $table_name) {
            $this->from = ' ' . SQL_COMMAND_FROM . ' ' . $this->backticks($this->table());
        } else {

            if (is_array($table_name)) {
                $tables = count($table_name);
                $count = 1;
                foreach ($table_name as $value) {

                    if ($tables == 1) {

                        $this->from .= $this->backticks($value);

                    } else {

                        if ($tables > $count) {

                            $this->from .= $this->backticks($value) . ',';

                        } else {

                            $this->from .= $this->backticks($value);
                        }
                    }

                    $count++;

                }
            } else {

                $this->from .= $table_name;
            }

        }

        $this->from = ' ' . SQL_COMMAND_FROM . ' ' . $this->from;

        return $this;
    }

    public function where($spalte, $operator = null, $value = null)
    {

        if (func_num_args() === 2) {

            $value = $operator;
            $operator = '=';
        }

        if ($operator == OPERATOR_IN) {

            $bedingung = $this->formatiereOperator($value);

        } else {

            $bedingung = $this->formatiereValue($value);

        }

        if ($operator == OPERATOR_LIKE) {

            $bedingung = $this->formatiereOperator($value);

        } else {

            $bedingung = $this->formatiereValue($value);

        }

        if ($operator == OPERATOR_NOT_EQUAL) {

            $bedingung = $this->formatiereOperator($value);

        } else {

            $bedingung = $this->formatiereValue($value);

        }

        if (empty($this->where)) {

            $this->where[] = SQL_STATEMENT_WHERE . ' ' . $this->backticks($spalte) . ' ' . $operator . ' ' . $bedingung;

        } else {

            $this->where[] = ' ' . SQL_STATEMENT_AND . ' ' . $this->backticks($spalte) . ' ' . $operator . ' ' . $bedingung;
        }

        return $this;

    }

    /**
     * Pr�ft, ob ein Prim�rschl�ssel existiert und gibt vorhandenen zur�ck
     * @throws PDOException::
     * @return mixed
     */

    public function primary_key()
    {

        if (null === $this->class) {

            $this->class = get_called_class();

        }
        try {
            if (!array_key_exists($this->class, $this->table_primary_keys)) {
                if (property_exists($this->class, 'primary_keys')) {
                    $this->table_primary_keys[$this->class] = $this->table_primary_keys;
                } else {

                    throw new PDOException(_CLASS_ . '::' . __FUNCTION__ . '()Model' . $this->class . ' hat keinen ensprechenden Prim�rschl�ssel!');
                }

            }
        } catch (PDOException $e) {
            //Errorhandler::findeFehler($e);
        }

        return $this->table_primary_keys[$this->class];
    }

    /**
     * Pr�ft, ob das aktuelle Model die Eigenschaften besitzt und gibt sie zur�ck
     * @throws PDOException::
     * @return mixed
     */

    public function properties()
    {
        if (null === $this->class) {

            $this->class = get_called_class();

        }

        try {
            if (!array_key_exists($this->class, $this->table_properties)) {
                if (property_exists($this->class, 'primary_keys')) {
                    $this->table_properties[$this->class] = $this->table_properties;
                } else {

                    throw new PDOException(_CLASS_ . '::' . __FUNCTION__ . '()Model' . $this->class . ' hat keine entsprechenden Properties!');
                }

            }
        } catch (PDOException $e) {
            //Errorhandler::findeFehler($e);
        }

        return $this->table_properties[$this->class];
    }

    public function join($table, $richtung = JOIN_LEFT)
    {
        if (!empty($table)) {

            $this->join[] .= $richtung . ' ' . SQL_CLAUSE_JOIN . ' ' . $this->backticks($table);

        }
        return $this;
    }

    public function on($spalte1 = '', $spalte2 = '')
    {
        try {
            if (empty($spalte1) || empty($spalte2)) {

                throw new PDOException(__CLASS__ . '::' . __FUNCTION__ . '(): Erforderlicher Parameter nicht gegeben!');
            }

            if (!empty($spalte1) && !empty($spalte2)) {

                $spalten_array1 = explode('.', $spalte1);
                $spalten_array2 = explode('.', $spalte2);

                if (count($spalten_array1) == 1) {
                    throw new PDOException(__CLASS . '::' . __FUNCTION__ . '(): Wert f�r Spalte 1"' . $spalte1 . '" muss im Format "table_name.row" angegeben werden.');
                } else {
                    $spalte1 = $this->backticks($spalten_array1[0]) . '.' . $this->backticks($spalten_array1[1]);
                }

                if (count($spalten_array2) == 1) {
                    throw new PDOException(__CLASS . '::' . __FUNCTION__ . '(): Wert f�r Spalte 2"' . $spalte2 . '" muss im Format "table_name.row" angegeben werden.');
                } else {
                    $spalte2 = $this->backticks($spalten_array2[0]) . '.' . $this->backticks($spalten_array2[1]);
                }

                $this->on[] .= SQL_CLAUSE_ON . ' ' . $spalte1 . ' = ' . $spalte2;

            }

        } catch (PDOException $e) {
            echo $e;
        }

        return $this;
    }

    public function insert_into($spalte = '')
    {

        if (empty($spalte)) {

            $spalte = $this->table();
        }

        $this->insert = SQL_COMMAND_INSERT . ' ' . $spalte;

        return $this;
    }

    /**
     * Nimmt die Paramter f�r den SET-BEFEHL auf und bindet sie ein
     * @param array $spalte
     * @param $operator
     * @param array $value
     * @return Model
     */
    public function set($spalte = array(), $value = array())
    {
        $operator = '=';
        if ((null !== $spalte) && (null !== $value)) {

            $this->set = SQL_COMMAND_SET . ' ' . $this->prepare_data($spalte, $operator, $value);
        }

        return $this;
    }

    /**
     * Nimmt Werte für Spalten und Values entgegen und gibt Teil eines SQL-Strings zurück
     * @param $spalte
     * @param $operator
     * @param $value
     * @return string
     */
    public function prepare_data($spalte = array(), $operator = null, $value = array())
    {

        $operator = '=';
        $inhalt = "";

        if (!empty($spalte)) {

            $total_spalten = count($spalte);
            $count = 1;

            for ($i = 0; $i <= $total_spalten; $i++) {

                if ($total_spalten == 0) {

                    $inhalt .= $spalte[$i] . ' ' . $operator . ' ' . $value[$i];

                } else {

                    if ($count < $total_spalten) {

                        $inhalt .= $spalte[$i] . ' ' . $operator . ' ' . $value[$i] . ',';
                    } else if ($count == $total_spalten) {

                        $inhalt .= $spalte[$i] . ' ' . $operator . ' ' . $value[$i];
                    }
                }
                $count++;

            }
            $inhalt;
        }
        return $inhalt;

    }

    /**
     * Funktion gibt SQL-Commando 'UPDATE' aus
     * @param $spalte
     * @return Model
     */
    public function update($spalte)
    {
        if (empty($spalte)) {

            $spalte = $this->table();
        }

        $this->update = SQL_COMMAND_UPDATE . ' ' . $spalte;

        return $this;
    }

    /**
     * Funktion gibt SQL-Commando 'DELETE' aus
     * @return Model
     */
    public function delete()
    {

        $this->delete = SQL_COMMAND_DELETE . ' ';

        return $this;
    }

    /**
     * Compiliert die SQL-PARAMETER zu einer SQL-SELECT-QUERY
     * @return string
     */
    public function compiliereSelectQuery()
    {

        if (null === $this->query) {

            if (!empty($this->select)) {

                $this->query .= SQL_COMMAND_SELECT . ' ';

                if ($this->distinct) {

                    $this->query .= SQL_COMMAND_DISTINCT . ' ';
                }

                if (null === $this->from) {

                    $this->from();
                }

                $this->query .= $this->select . $this->from . ' ';

                if (null !== $this->join && (null !== $this->on)) {

                    $nr = count($this->join);

                    for ($i = 0; $i < $nr; $i++) {

                        $this->query .= $this->join[$i] . ' ' . $this->on[$i] . ' ';

                    }
                }

                if (!empty($this->where)) {

                    foreach ($this->where as $value) {

                        $this->query .= $value;
                    }
                }

                if (null !== $this->order_by) {

                    $this->query .= $this->order_by;
                }

                if (null !== $this->limit) {

                    $this->query .= $this->limit;
                }

                if (null !== $this->group_by) {

                    $this->query .= $this->group_by;
                }

            } else if (null !== $this->insert) {

                if (null !== $this->set) {

                    $this->query .= $this->insert . ' ' . $this->set;
                }

            } elseif (!empty($this->update)) {

                if (null === $this->update) {

                    $this->update = $this->update($spalte);
                }

                if (null === $this->set) {
                    $this->set = $this->set($spalte, $value);
                }

                $this->query .= $this->update . ' ' . $this->set . ' ';

                if (!empty($this->where)) {

                    foreach ($this->where as $value) {

                        $this->query .= $value;
                    }
                }
            } else if (!empty($this->delete)) {

                if (null !== $this->delete) {

                    $this->query .= $this->delete . ' ' . $this->from . ' ';

                    if (null === $this->from) {

                        $this->from();
                    }

                    if (!empty($this->where)) {

                        foreach ($this->where as $value) {

                            $this->query .= $value;
                        }
                    }
                }
            }
        }

        //var_dump($this->query);
        return $this->query;
    }

    /**
     * F�hrt eine prepare_methode mit dem $stmt-object durch und gibt dieses zur�ck
     * @param $query
     * @return
     */

    public function baueDBVerbindungAuf()
    {

        $this->db = Db::getInstance();
        return $this->db;

    }

    public function prepareQuery()
    {

        $this->db = $this->baueDBVerbindungAuf();
        $stmt = $this->db->prepare($this->compiliereSelectQuery());

        if (!$stmt) {

            print("PDO::errorInfo()\n");
        }

        return $stmt;

    }

    /**
     * Die Funktion f�hrt die compilierte Query aus
     * @param $parameter
     * @param $value
     * @return Model
     */
    public function executeQuery($parameter = array(), $value = array())
    {

        $stmt = $this->prepareQuery();
        try {
            if ((null !== $parameter) && (null !== $value)) {

                if (is_array($parameter)) {
                    $total_paramter = count($parameter);
                    for ($i = 0; $i < $total_paramter; $i++) {

                        if (is_int($value[$i])) {

                            $stmt->bindParam($parameter[$i], $value[$i], PDO::PARAM_INT);

                        } else if (is_string($value[$i])) {

                            $stmt->bindParam($parameter[$i], $value[$i], PDO::PARAM_STR);
                          
                        } else if (is_numeric($value[$i])) {

                            $stmt->bindParam($parameter[$i], $value[$i], PDO::PARAM_STR);
                        }
                    }
                } else if (!is_array($parameter)) {

                    if (is_int($value)) {

                        $stmt->bindParam($parameter, $value, PDO::PARAM_INT);

                    } else if (is_string($value)) {

                        $stmt->bindParam($parameter, $value, PDO::PARAM_STR);

                    } else if (is_numeric($value)) {

                        $stmt->bindParam($parameter, $value, PDO::PARAM_STR);
                    }
                }

            }
            $stmt->execute();

            $this->result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();

        } catch (PDOException $e) {
            echo $e;
        }

        //var_dump($this->result);
        return $this;

    }

    public function as_array()
    {
        if (!$this->result) {

            return false;
        }

        if (!is_array($this->result)) {

            return (array) $this->result;
        }

        return $this->result;

    }

    public function as_object()
    {
        if (!$this->result) {

            return false;
        }

        if (!is_object($this->result)) {

            foreach ($this->result as $this->row) {
                return (object) $this->row;
            }

        }

        return $this->result;
    }

    /**
     * Setzt den Wert $value in sch�ne backticks
     * @param $value
     * @return string
     */
    private function backticks($value)
    {

        return '`' . $value . '`';
    }

    /**
     *
     * @param $value
     * @return string
     */
    private function formatiereValue($value)
    {
        if (is_numeric($value)) {
            return $value;
        }

        return $value;
    }

    /**
     *
     * @param $value
     * @return string
     */
    private function formatiereOperator($value)
    {

        return '(' . $value . ')';
    }

}
