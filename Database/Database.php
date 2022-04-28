<?php

namespace Database;

defined('BASEPATH') OR exit('No direct script access allowed');

use PDO;

/**
 * 
 */
class Database {

    private $host = "ec2-3-223-213-207.compute-1.amazonaws.com";
    private $username = "bpmfwulcnpxlbn";
    private $password = "f455b01066c4fd268970adc23f29debbe0d65b3fe81aa0182bd36dee9d41dda9";
    private $db_name = "d1vc15a7k9rv4a";

    /*private $host = "localhost";
    private $username = "postgres";
    private $password = "zeppelin";
    private $db_name = "adesur";*/

    private $db_driver = "pgsql";
    public $pdo;
	
	function __construct() {
		$dsn = $this->db_driver . ':host=' . $this->host . ';dbname=' . $this->db_name . '';
		$options = array(
		 	PDO::ATTR_EMULATE_PREPARES => false,
		 	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		 	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
		);
		try {
		    $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
		} catch (PDOException $e){
		    echo "Conexión falló: ".$e->getMessage();
		}
	}
}