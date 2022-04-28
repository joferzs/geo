<?php

namespace ApiControl;//namespace define el nombre de la carpeta "padre" este archivo, en este caso el nombre es: "ApiControl"

defined('BASEPATH') OR exit('No direct script access allowed');

use PDO;//inicializa clase PDO para usar funciones PDO
use ApiControl\ApiSessionSecurity;//el "use" se refiere al archivo que contiene la clase que se necesita, en este caso se necesita la clase "ApiSessionSecurity" que está en el archivo ApiSessionSecurity.php

/**
 * 
 */
class ApiSelect extends ApiMain {

	//private $conn;
	private $asa;
	
	function __construct() {
		//$this->asa = new ApiSessionSecurity();
		/*$this->asa->sessionValidator();
		$this->items_arr['security_data_apply'] = $this->asa->evaluatePrivilege(array(1,2));*/
		parent::__construct();
	}

	public function getAllSelect() {
		$sql = 'SELECT 
			*
			FROM ivp.vulnerabilidad_loc_rur_2020
			WHERE id_cliente = :id_cliente GROUP BY direccion ORDER BY a.fecha_registro DESC';
		$sth = $this->conn->prepare($sql);
		$sth->bindValue(':id_cliente', $x['id_cliente'], PDO::PARAM_INT);
		$sth->execute();
		$rows = $sth->rowCount();
		if ($rows > 0) {
			$this->items_arr['bases'] = array();//se debe llamar segun nuestro modulo
			//$this->items_arr['last-update'] = "";
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $row) {
				/*if (!isset($this->items_arr['last-id'])) {
					$this->items_arr['last-id'] = $row['id_entrada'];
				}*/
				$arr_row['editar'] = "";
				//$data_edit = array("id_parte");//Se agregan id que se deben editar
				//$row = self::addDataToArr($data_edit, $row);
				/*$arr_fotos = self::getFotosFooTab($row['id_entrada']);
				$arr_row['data_fotos'] = "<div class='swiper-fotos' data-fotos='" . $arr_fotos . "'></div>";*/
				$this->items_arr['bases'][] = $arr_row + $row;
				/*if ($this->items_arr['last-update'] < $row['fecha_actualizacion']) {
					$this->items_arr['last-update'] = $row['fecha_actualizacion'];
				}*/
			}
			//$this->items_arr['number-records'] = $rows;
		}else{
			$this->items_arr['bases'] = array("mensaje" => "Sin coincidencias encontradas.");
		}
		$sth = null;
	}

	public function getAllFilterSelect($x) {
		/*echo "<br>data: "; print_r($x);
		exit;*/
		if ($x['anio'] == 2020) {
			$anio = 2020;
		}else {
			$anio = 2010;
		}
		if ($x['indicadores'] == "") {
			$x['indicadores'] = "*";
		}

		$sql = 'SELECT ' . $x['indicadores'] . ' FROM ivp.vulnerabilidad_loc_rur_' . $anio;

		$sql_loc = "";
		if ($x['id_localidad'] != "") {
			$sql_loc = ' WHERE "CGLOC" = :id_localidad';
		}

		$sql.= $sql_loc . ' LIMIT 1000';
		
		$sth = $this->conn->prepare($sql);

		if ($x['id_localidad'] != "") {
			$sth->bindValue(':id_localidad', $x['id_localidad'], PDO::PARAM_INT);
		}

		$sth->execute();
		$rows = $sth->rowCount();
		if ($x['debug'] == "debug") {
			$this->items_arr['sql'] = $sql;
			$this->items_arr['x'] = $x;
		}
		
		if ($rows > 0) {
			$this->items_arr['vulnerabilidad'] = array();//se debe llamar segun nuestro modulo
			//$this->items_arr['last-update'] = "";
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $row) {
				/*if (!isset($this->items_arr['last-id'])) {
					$this->items_arr['last-id'] = $row['id_entrada'];
				}*/
				$arr_row['editar'] = "";
				//$data_edit = array("id_parte");//Se agregan id que se deben editar
				//$row = self::addDataToArr($data_edit, $row);
				/*$arr_fotos = self::getFotosFooTab($row['id_entrada']);
				$arr_row['data_fotos'] = "<div class='swiper-fotos' data-fotos='" . $arr_fotos . "'></div>";*/
				$this->items_arr['vulnerabilidad'][] = $arr_row + $row;
				/*if ($this->items_arr['last-update'] < $row['fecha_actualizacion']) {
					$this->items_arr['last-update'] = $row['fecha_actualizacion'];
				}*/
			}
			//$this->items_arr['number-records'] = $rows;
		}else{
			$this->items_arr['vulnerabilidad'] = array("mensaje" => "Sin coincidencias encontradas.");
		}
		$sth = null;
	}

	public function getEstados($x) {
		$sql = 'SELECT * FROM edo_mun.estados ORDER BY nomgeo ASC';
		$sth = $this->conn->prepare($sql);
		$sth->execute();
		$rows = $sth->rowCount();
		if ($rows > 0) {
			$this->items_arr['estados'] = array();//se debe llamar segun nuestro modulo
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $row) {
				$this->items_arr['estados'][] = $row;
			}
		}else{
			$this->items_arr['estados'] = array("mensaje" => "Sin coincidencias encontradas.");
		}
		$sth = null;
	}

	public function getMunicipios($x) {
		$sql = 'SELECT nomgeo,cve_mun,cve_ent FROM edo_mun.municipios ORDER BY nomgeo ASC';
		$sth = $this->conn->prepare($sql);
		$sth->execute();
		$rows = $sth->rowCount();
		if ($rows > 0) {
			$this->items_arr['municipios'] = array();//se debe llamar segun nuestro modulo
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $row) {
				$this->items_arr['municipios'][] = $row;
			}
		}else{
			$this->items_arr['municipios'] = array("mensaje" => "Sin coincidencias encontradas.");
		}
		$sth = null;
	}

	public function getLocalidades($x) {
		$sql = 'SELECT nom_loc,cve_mun,"CGLOC" FROM loc.localidades ORDER BY nom_loc ASC';
		$sth = $this->conn->prepare($sql);
		$sth->execute();
		$rows = $sth->rowCount();
		if ($rows > 0) {
			$this->items_arr['localidades'] = array();//se debe llamar segun nuestro modulo
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $row) {
				$this->items_arr['localidades'][] = $row;
			}
		}else{
			$this->items_arr['localidades'] = array("mensaje" => "Sin coincidencias encontradas.");
		}
		$sth = null;
	}

	public function getTemas($x) {
		$sql = 'SELECT * FROM catalogo.tema';
		$sth = $this->conn->prepare($sql);
		$sth->execute();
		$rows = $sth->rowCount();
		if ($rows > 0) {
			$this->items_arr['temas'] = array();//se debe llamar segun nuestro modulo
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $row) {
				$this->items_arr['temas'][] = $row;
			}
		}else{
			$this->items_arr['temas'] = array("mensaje" => "Sin coincidencias encontradas.");
		}
		$sth = null;
	}

	public function getSubtemas($x) {
		$sql = 'SELECT * FROM catalogo.subtema';
		$sth = $this->conn->prepare($sql);
		$sth->execute();
		$rows = $sth->rowCount();
		if ($rows > 0) {
			$this->items_arr['subtemas'] = array();//se debe llamar segun nuestro modulo
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $row) {
				$this->items_arr['subtemas'][] = $row;
			}
		}else{
			$this->items_arr['subtemas'] = array("mensaje" => "Sin coincidencias encontradas.");
		}
		$sth = null;
	}

	public function getIndicadores($x) {
		$sql = 'SELECT * FROM catalogo.indicadores';
		$sth = $this->conn->prepare($sql);
		$sth->execute();
		$rows = $sth->rowCount();
		if ($rows > 0) {
			$this->items_arr['indicadores'] = array();//se debe llamar segun nuestro modulo
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $row) {
				$this->items_arr['indicadores'][] = $row;
			}
		}else{
			$this->items_arr['indicadores'] = array("mensaje" => "Sin coincidencias encontradas.");
		}
		$sth = null;
	}

	public function getRealTimeRequest($x) {
		/*print_r($x);
		exit;*/
		$sql = '
			SELECT id_animal,fecha_actualizacion
			FROM animales  WHERE fecha_actualizacion > :fecha_actualizacion ORDER BY fecha_actualizacion ASC';
		$sth = $this->conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->bindValue(':fecha_actualizacion', $x['last_update'], PDO::PARAM_STR);
		$sth->execute();
		if ($sth->rowCount() > 0) {
			$id_updated = array();
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $row) {

				array_push($id_updated, $row['id_animal']);

				$this->items_arr['last-update'] = $row['fecha_actualizacion'];
			}

			//self::getManySalida($id_updated, $x['last_id']);

		}else{
			$this->items_arr['none-last'] = "none";
		}
		$sth = null;
	}

	public function getSitesAccess() {
		$this->items_arr['sites_access'] = $this->asa->scriptsUserAccessData();
	}
}