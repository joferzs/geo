<?php

namespace Database;

defined('BASEPATH') OR exit('No direct script access allowed');

use PDO;

/**
 * 
 */
class Database {

    private $host = "ec2-44-209-158-64.compute-1.amazonaws.com";
    private $username = "sohfltzgsgknly";
    private $password = "eb99e9981a2793ff49899647cc32180d3018f971bbcecef26fa182e568006971";
    private $db_name = "d59nl8qflmseri";

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