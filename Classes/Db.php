<?php
namespace Cariola\Classes;

class Db
{
    private static $config = array(
        'default' => array(
            'user' => 'root',
            'pass' => '',
            'host' => 'localhost',
            'dbname' => 'more',
        ) ,
        'test' => array(
            'user' => 'root',
            'pass' => '',
            'host' => 'localhost',
            'dbname' => '',
        ),
    );
    private static  $con = null;
    private static  $selectedDb = 'default';

    // The clone and wakeup methods prevents external instantiation of copies of the Singleton clASs,
    // thus eliminating the possibility of duplicate objects.
    public function __clone()
    {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    public function __wakeup()
    {
        trigger_error('Deserializing is not allowed.', E_USER_ERROR);
    }

    // private constructor
    private function __construct()
    {
    }

    private static function connection($selectedDb = null)
    {
        if($selectedDb) {
            if(Db::$con) {
                Db::$con->connection = null;
                Db::$con = null;
            }
        }
        else {
            $selectedDb = Db::$selectedDb;
        }

        if(!Db::$con) {
            extract( Db::$config[$selectedDb], EXTR_OVERWRITE);
            $dsn = 'mysql:host=' . $host . ';dbname=' . $dbname;
            $options = array(
                \PDO::ATTR_PERSISTENT => true,
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
            );

            try {
                //throw new \PDOException('Test');
                Db::$con= new \PDO($dsn, $user, $pass, $options);
                Db:$selectedDb = $selectedDb;
            }// Catch any errors
            catch (\PDOException $e) {
                print $e->getMessage();
            }
        }
    }
    
    /**
    * @param $paramValue
    *
    * @return int
    * @throws PDOException
    */
    private static function getParamType($paramValue)
    {
        switch ($paramValue) {
            case is_int($paramValue):
                return \PDO::PARAM_INT;
                break;
            case is_bool($paramValue):
                return \PDO::PARAM_INT;
                break;
            case is_string($paramValue):
                return \PDO::PARAM_STR;
                break;
            case is_float($paramValue):
                return \PDO::PARAM_STR;
                break;
            case is_double($paramValue):
                return \PDO::PARAM_STR;
                break;
            case is_null($paramValue):
                return \PDO::PARAM_NULL;
                break;
            default:
                throw new \PDOException("Invalid param type: {$paramValue} with type {gettype($paramValue)}");
        }
    }    

    public static function dbSetActive($selectedDb)
    {
        Db::connection($selectedDb);
    }
    
    public static function lastInsertId() 
    {
        if(isset(Db::$con)) {
            return Db::$con->lastInsertId();
        } else {
            return null;    
        }
    }

    public static function query($query, $params = array())
    {
        if(!Db::$con) {
            Db::connection();
        }
        if(Db::$con) {
            try {
                $stmt = Db::$con->prepare($query);
                foreach ($params as $key=> $value) {
                    $stmt->bindValue($key, $value, Db::getParamType($value));
                }
                $stmt->execute();
            } catch (\PDOException $e) {
                print $e->getMessage();
            }
            return $stmt;
        } else {
            die('No database connection!');
        }
    }
}
