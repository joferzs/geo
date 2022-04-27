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
		$sql = 'SELECT 
			*
			FROM ivp.vulnerabilidad_loc_rur_2020
			WHERE "CGLOC" = :id';
		$sth = $this->conn->prepare($sql);
		$sth->bindValue(':id', $x['id'], PDO::PARAM_INT);
		$sth->execute();
		$rows = $sth->rowCount();
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

	public function getAddBase($x) {
		/*
			print_r($x);
			echo "----livelover----";
			print_r($_FILES);
			echo "----kalmah----";
			echo "<br>key: " . key($_FILES);
			exit;
		*/
		$this->items_arr['data-success'] = array();
		try {
			$sql = "
			INSERT INTO
				bases (
					base,
					direccion,
					activo,
					fecha_registro
				)
			VALUES
				(?, ?, ?, NOW())";
			$sth1 = $this->conn->prepare($sql);
			$sth1->execute(array(
				strtoupper($x['base']),
				strtoupper($x['direccion']),
				$x['activo']
			));
			$sth1 = null;
			$id_insert = $this->conn->lastInsertId();

			//self::logAccess("Agregó cat_bases", $id_insert);

			$this->conn->commit();

			self::getManyBases(array($id_insert), 0);//Cambiamos el nombre de acuerdo a nuestro modulo

			$this->items_arr['data-success'] = 'Done';
			
		} catch (PDOException $e) {
			$this->conn->rollback();
			$this->items_arr['data-success'] = array("mensaje" => 'err');
		}
	}

	public function getUpdateBase($x){
		//$data_id = self::decUrlData($data['token'], $data['id']);
		/*print_r($x);
		echo "----livelover----";
		print_r($_FILES);
		echo "----kalmah----";
		exit;*/
		$this->items_arr['data-success'] = array();
		try {
			$sql = "
			UPDATE
				bases
			SET
				base = ?,
				direccion = ?,
				activo = ?
			WHERE
				id_base = ?";

			$sth1 = $this->conn->prepare($sql);
			//$inst_data = $this->asa->adminPrivilege() ? $data['id_institucion'] : $_SESSION['idUserAdoptInst'];
			$sth1->execute(	array(
				strtoupper($x['base']),
				strtoupper($x['direccion']),
				$x['activo'],
				$x['id_base']
			));

			//self::logAccess("Editó cat_partes", $x['id_parte']);
			
			$this->conn->commit();

			self::getManyBases(array($x['id_base']), $x['id_base']);

			$this->items_arr['data-success'] = 'Done';

		} catch (PDOException $e) {
			$this->conn->rollback();
			$this->items_arr['data-success'] = array("mensaje" => 'errr');
		}
	}

	private function addDataToArr($x, $row) {
		foreach ($x as $k) {
			$row['data_edit'][$k] = $row[$k];
			//unset($row[$k]);
		}
		return $row;
	}

	public function getManyBases($x, $last_id = "") {
		$in  = str_repeat('?,', count($x) - 1) . '?';
		$sql = '
		SELECT 
			*
		FROM bases
		WHERE id_base IN ('. $in . ')';
		$sth = $this->conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute($x);
		if ($sth->rowCount() > 0) {

			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $row) {
				$arr_row['editar'] = "";
				if ($row['id_base'] > $last_id) {
					$this->items_arr['base-add'][] = $arr_row + $row;
				}else {
					$this->items_arr['base-up'][] = $arr_row + $row;
				}
		    }
		}else {
			$this->items_arr['none-last'] = "none";
		}
		$sth = null;
		/*$res = $this->conn->query("SELECT count(*) AS total, max(id_trolebus) AS max FROM cat_trolebuses")->fetch(PDO::FETCH_ASSOC);
		$this->items_arr['number-records'] = $res['total'];
		$this->items_arr['last-id'] = $res['max'];*/
	}

	public function getEstados($x) {
		$sql = 'SELECT * FROM edo_mun.estados';
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
		$sql = 'SELECT nomgeo,cve_mun,cve_ent FROM edo_mun.municipios';
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
		$sql = 'SELECT nom_loc,cve_mun,"CGLOC" FROM loc.localidades';
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