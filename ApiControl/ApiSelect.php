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
			//$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			//foreach ($result as $row) {
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
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
		/*exit;*/
		if ($x['anio'] == 2020) {
			$anio = 2020;
		}elseif ($x['anio'] == 2010) {
			$anio = 2010;
		}else {
			$anio = "2010_2020";
		}
		if ($x['indicadores'] == "") {
			$x['indicadores'] = '';
		}else {
			$x['indicadores'] = "," . $x['indicadores'];
		}

		//$sql = 'SELECT a."ID",a."CGLOC",b."CVE_ENT",b."CVE_MUN",b."NOM_LOC" ' . $x['indicadores'] . '  FROM ivp.loc_rur_' . $anio . ' a 
		$sql = 'SELECT a."ID",b."NOM_LOC" ' . $x['indicadores'] . '  FROM ivp.loc_rur_' . $anio . ' a 
		INNER JOIN loc.localidades b ON a."CGLOC" = b."CGLOC"
		';

		$sql_loc = "";
		if ($x['id_localidad'] != "") {
			$sql_loc = ' WHERE a."CGLOC" = :id_localidad';
		}

		//$sql.= $sql_loc . ' LIMIT :limit_in,:limit_data';
		$sql.= $sql_loc . ' ORDER BY a."ID" LIMIT :limit_data OFFSET :limit_in';
		//$sql.= $sql_loc . ' ';
		
		$sth = $this->conn->prepare($sql);

		if ($x['id_localidad'] != "") {
			$sth->bindValue(':id_localidad', $x['id_localidad'], PDO::PARAM_STR);
			
		}

		$sth->bindValue(':limit_in', intval($x['limit_in']), PDO::PARAM_INT);
		$sth->bindValue(':limit_data', intval($x['limit_data']), PDO::PARAM_INT);

		$sth->execute();
		$rows = $sth->rowCount();
		if ($x['debug'] == "debug") {
			$this->items_arr['sql'] = $sql;
			$this->items_arr['x'] = $x;
		}
		
		$this->items_arr['vulnerabilidad'] = array();//se debe llamar segun nuestro modulo
		if ($rows > 0) {
			//$result = $sth->fetchAll(PDO::FETCH_ASSOC);

			//$this->items_arr['vulnerabilidad'] = $sth->fetchAll(PDO::FETCH_ASSOC);//se debe llamar segun nuestro modulo

			while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
				/*$row['clave_estado'] = $row['CVE_ENT'];
				$row['clave_municipio'] = $row['CVE_MUN'];*/
				$this->items_arr['vulnerabilidad'][] = $row;
			}
			//$this->items_arr['number-records'] = $rows;
		}
		$sth = null;
	}

	public function getEstados($x) {
		$sql = 'SELECT "NOMGEO","CVE_ENT" FROM edo_mun.estados ORDER BY "NOMGEO" ASC';
		$sth = $this->conn->prepare($sql);
		$sth->execute();
		$rows = $sth->rowCount();
		if ($rows > 0) {
			$this->items_arr['estados'] = array();//se debe llamar segun nuestro modulo
			/*$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $row) {*/
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
				$this->items_arr['estados'][] = $row;
			}
		}else{
			$this->items_arr['estados'] = array("mensaje" => "Sin coincidencias encontradas.");
		}
		$sth = null;
	}

	public function getMunicipios($x) {
		$sql = 'SELECT "NOMGEO","CVE_MUN","CVE_ENT" FROM edo_mun.municipios ORDER BY "NOMGEO" ASC';
		$sth = $this->conn->prepare($sql);
		$sth->execute();
		$rows = $sth->rowCount();
		if ($rows > 0) {
			$this->items_arr['municipios'] = array();//se debe llamar segun nuestro modulo
			/*$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $row) {*/
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
				$this->items_arr['municipios'][] = $row;
			}
		}else{
			$this->items_arr['municipios'] = array("mensaje" => "Sin coincidencias encontradas.");
		}
		$sth = null;
	}

	public function getLocalidades($x) {
		//$sql = 'SELECT "NOM_LOC","CVE_MUN","CGLOC" FROM loc.localidades  WHERE "CVE_MUN" = :id_municipio ORDER BY "NOM_LOC" ASC';
		$sql = 'SELECT "NOM_LOC","CVE_MUN","CVE_ENT","CGLOC" FROM loc.localidades ORDER BY "NOM_LOC" ASC';
		$sth = $this->conn->prepare($sql);
		//$sth->bindValue(':id_municipio', $x['id_municipio'], PDO::PARAM_INT);
		$sth->execute();
		$rows = $sth->rowCount();
		if ($rows > 0) {
			$this->items_arr['localidades'] = array();//se debe llamar segun nuestro modulo
			/*$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $row) {*/
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
				$this->items_arr['localidades'][] = $row;
			}
		}else{
			$this->items_arr['localidades'] = array("mensaje" => "Sin coincidencias encontradas.");
		}
		$sth = null;
	}

	public function getTemas($x) {
		$sql = 'SELECT * FROM catalogo.tema WHERE tema IS NOT NULL';
		$sth = $this->conn->prepare($sql);
		$sth->execute();
		$rows = $sth->rowCount();
		if ($rows > 0) {
			$this->items_arr['temas'] = array();//se debe llamar segun nuestro modulo
			/*$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $row) {*/
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
				$this->items_arr['temas'][] = $row;
			}
		}else{
			$this->items_arr['temas'] = array("mensaje" => "Sin coincidencias encontradas.");
		}
		$sth = null;
	}

	public function getSubtemas($x) {
		$sql = 'SELECT * FROM catalogo.subtema WHERE subtema IS NOT NULL';
		$sth = $this->conn->prepare($sql);
		$sth->execute();
		$rows = $sth->rowCount();
		if ($rows > 0) {
			$this->items_arr['subtemas'] = array();//se debe llamar segun nuestro modulo
			/*$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $row) {*/
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
				$this->items_arr['subtemas'][] = $row;
			}
		}else{
			$this->items_arr['subtemas'] = array("mensaje" => "Sin coincidencias encontradas.");
		}
		$sth = null;
	}

	public function getIndicadores($x) {
		$sql = 'SELECT * FROM catalogo.indicadores WHERE indicadores IS NOT NULL';
		$sth = $this->conn->prepare($sql);
		$sth->execute();
		$rows = $sth->rowCount();
		if ($rows > 0) {
			$this->items_arr['indicadores'] = array();//se debe llamar segun nuestro modulo
			/*$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $row) {*/
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
				$this->items_arr['indicadores'][] = $row;
			}
		}else{
			$this->items_arr['indicadores'] = array("mensaje" => "Sin coincidencias encontradas.");
		}
		$sth = null;
	}

	public function getEstadosFormat() {
		$sql = 'SELECT "NOMGEO","CVE_ENT" FROM edo_mun.estados';
		$sth = $this->conn->prepare($sql);
		$sth->execute();
		$rows = $sth->rowCount();
		if ($rows > 0) {
			$estados_format = array();//se debe llamar segun nuestro modulo
			/*$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $row) {*/
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
				$estados_format[$row['CVE_ENT']] = $row['NOMGEO'];
			}
		}else{
			$estados_format = array("mensaje" => "Sin coincidencias encontradas.");
		}
		$sth = null;
		return $estados_format;
	}

	public function getMunicipiosFormat() {
		$sql = 'SELECT "NOMGEO","CVE_MUN","CVE_ENT" FROM edo_mun.municipios';
		$sth = $this->conn->prepare($sql);
		$sth->execute();
		$rows = $sth->rowCount();
		if ($rows > 0) {
			$municipios_format = array();//se debe llamar segun nuestro modulo
			/*$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $row) {*/
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
				$kee = $row['CVE_ENT'] . "-" . $row['CVE_MUN'];
				$municipios_format[$kee] = $row['NOMGEO'];
			}
		}else{
			$municipios_format = array("mensaje" => "Sin coincidencias encontradas.");
		}
		$sth = null;
		return $municipios_format;
	}

	public function getExportExcel($x) {

		if ($x['anio'] == 2020) {
			$anio = 2020;
		}elseif ($x['anio'] == 2010) {
			$anio = 2010;
		}else {
			$anio = "2010_2020";
		}
		if ($x['indicadores'] == "") {
			$x['indicadores'] = '';
		}else {
			$x['indicadores'] = "," . $x['indicadores'];
		}

		$sql = 'SELECT a."ID",b."NOM_LOC" ' . $x['indicadores'] . ', "CVE_ENT", "CVE_MUN"  FROM ivp.loc_rur_' . $anio . ' a 
		INNER JOIN loc.localidades b ON a."CGLOC" = b."CGLOC"
		';

		if ($x['id_localidad'] != "") {
			$sql.= ' WHERE a."CGLOC" = :id_localidad';
		}

		$sql.= ' ORDER BY a."ID" ';

		$sth = $this->conn->prepare($sql);

		if ($x['id_localidad'] != "") {
			$sth->bindValue(':id_localidad', $x['id_localidad'], PDO::PARAM_STR);
		}

		$estado = self::getEstadosFormat();
		$municipio = self::getMunicipiosFormat();;

		$sth->execute();
		$rows = $sth->rowCount();
		$this->items_arr['vulnerabilidad'] = array();//se debe llamar segun nuestro modulo
		if ($rows > 0) {
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
				$kee = $row['CVE_ENT'] . "-" . $row['CVE_MUN'];
				$row['Estado'] = $estado[$row['CVE_ENT']];
				$row['Municipio'] = $municipio[$kee];
				$row['Localidad'] = $row['NOM_LOC'];
				$this->items_arr['vulnerabilidad'][] = $row;
			}
		}
		$sth = null;

		$res = self::ExportFile($this->items_arr['vulnerabilidad']);
		$file = "geo-" . self::generateRandomString() .time() . ".xls";
		$filename = "../temp-excel/" . $file;
		$fileEndEnd = mb_convert_encoding($res, 'ISO-8859-1', "UTF-8");
		//file_put_contents($filename, $fileEndEnd);

		echo json_encode(array("file_name" => $file, "deb" => 1349));
	}

	public function ExportFile($records) {
		$heading = false;
		if(!empty($records)) {
			$a = "";
		  	foreach($records as $row) {
				if(!$heading) {
		  			$a.= implode("\t", array_keys($row)) . "\n";
		  			$heading = true;
				}
				$a.= implode("\t", array_values($row)) . "\n";
		  	}
		  	return $a;
		  }
	}

	public function generateRandomString($length = 20) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}
}