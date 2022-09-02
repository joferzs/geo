<?php

namespace Database;

defined('BASEPATH') OR exit('No direct script access allowed');

use PDO;

/**
 * 
 */
class Database {

    private $host = "ec2-3-217-113-25.compute-1.amazonaws.com";
    private $username = "whhkcobrqzscjy";
    private $password = "2237c5ae1d9a3ac2814f78bc7b441dc1e443bef6f1a7c1fcbe4c6c1342334cd4";
    private $db_name = "dfgeeam8oicb0b";

    /*private $host = "localhost";
    private $username = "postgres";
    private $password = "Zeppelin8(";
    private $db_name = "ade";*/

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