<?php

namespace ApiControl;//namespace define el nombre de la carpeta "padre" este archivo, en este caso el nombre es: "ApiControl"

defined('BASEPATH') OR exit('No direct script access allowed');

use PDO;//inicializa clase PDO para usar funciones PDO
use modules\PDF;
use ApiControl\ApiSessionSecurity;//el "use" se refiere al archivo que contiene la clase que se necesita, en este caso se necesita la clase "ApiSessionSecurity" que está en el archivo ApiSessionSecurity.php


/**
 * 
 */
class ApiServicioMap extends ApiMain {

	//private $conn;
	private $asa;
	
	function __construct() {
		//$this->asa = new ApiSessionSecurity();
		/*$this->asa->sessionValidator();
		$this->items_arr['security_data_apply'] = $this->asa->evaluatePrivilege(array(1,2));*/
		parent::__construct();
	}

	public function getAllFilterServicioMap($x) {
		/*print_r($x);
		
		exit;*/
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

		if ($x['tab'] == 'desarrollo_local') {
			$tab = ' ivp.des_local_' . $anio;
			$id = '"id"';
			$filed_d = ' ,"PRP_0101" ';
		}else {
			$tab = ' ivp.loc_rur_' . $anio;
			$id = '"ID"';
			$filed_d = '';
		}

		//$sql = 'SELECT a."ID",a."CGLOC",b."ID_ENT",b."ID_MUN",b."NOM_LOC" ' . $x['indicadores'] . '  FROM ivp.loc_rur_' . $anio . ' a 
		$sql = 'SELECT a.' . $id . ' AS "ID",b."NOM_LOC" ' . $filed_d . ' ' . $x['indicadores'] . '   FROM ' . $tab . ' a 
		INNER JOIN loc.localidades b ON a."CGLOC" = b."CGLOC"
		';

		$sql_loc = "";
		/*if ($x['id_localidad'] != "") {
			$sql_loc = ' WHERE a."CGLOC" = :id_localidad';
		}*/

		if ($x['localidades'] != "") {

			if (isset($x['localidades'][0]) AND $x['localidades'][0] == "-1") {
				# code...
			}else {
				$lll = array();
				foreach ($x['localidades'] as $key => $value) {
					$lll[] = "'" . $value ."'";
				}
				$loc_join = join(",", $lll);
				$sql_loc.= ' WHERE a."CGLOC" IN (' . $loc_join . ')';
			}
		}

		//$sql.= $sql_loc . ' LIMIT :limit_in,:limit_data';
		$sql.= $sql_loc . ' ORDER BY a.' . $id . ' LIMIT :limit_data OFFSET :limit_in';
		//$sql.= $sql_loc . ' ';
		
		$sth = $this->conn->prepare($sql);

		/*if ($x['id_localidad'] != "") {
			$sth->bindValue(':id_localidad', $x['id_localidad'], PDO::PARAM_STR);
			
		}*/

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
				/*$row['clave_estado'] = $row['ID_ENT'];
				$row['clave_municipio'] = $row['ID_MUN'];*/
				$this->items_arr['vulnerabilidad'][] = $row;
			}
			//$this->items_arr['number-records'] = $rows;
		}
		$sth = null;
	}

	public function getEstados($x) {
		$sql = 'SELECT "NOMGEO","ID_ENT" FROM edo_mun.estados ORDER BY "NOMGEO" ASC';
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
		$sql = 'SELECT "NOMGEO","ID_MUN","ID_ENT" FROM edo_mun.municipios ORDER BY "NOMGEO" ASC';
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

	public function getSomeLocalidades($x) {
		$sql = 'SELECT "CGLOC" FROM loc.localidades WHERE "ID_LOC" = :id';
		$sth = $this->conn->prepare($sql);
		$sth->bindValue(':id', $x['id'], PDO::PARAM_INT);
		$sth->execute();
		$rows = $sth->rowCount();
		if ($rows > 0) {
			$this->items_arr['localidades'] = array();//se debe llamar segun nuestro modulo
			/*$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $row) {*/
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
				$this->items_arr['localidades'][] = $row['CGLOC'];
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

		/*$sql = 'SELECT * FROM catalogo.des_soc_tema WHERE tema IS NOT NULL';
		$sth = $this->conn->prepare($sql);
		$sth->execute();
		$rows = $sth->rowCount();
		if ($rows > 0) {
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
				$this->items_arr['temas'][] = $row;
			}
		}else{
			$this->items_arr['temas'] = array("mensaje" => "Sin coincidencias encontradas.");
		}
		$sth = null;*/
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

	public function getNa($x) {
		if ($x['anio'] == 2010) {
			$ann = "na_" . 2010;
		}else {
			$ann = "na_" . 2020;
		}
		$sql = 'SELECT a."ID","NOM_NUCLEO" FROM ivp.' . $ann . ' a INNER JOIN public.na b ON a."ID_NA" = b."ID_NA" WHERE "ID_MUN" = :id_municipio';
		$sth = $this->conn->prepare($sql);
		$sth->bindValue(':id_municipio', $x['id_municipio'], PDO::PARAM_STR);

		$sth->execute();
		$rows = $sth->rowCount();
		if ($rows > 0) {
			$this->items_arr["na"] = array();//se debe llamar segun nuestro modulo
			/*$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $row) {*/
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
				$this->items_arr["na"][] = $row;
			}
		}else{
			$this->items_arr["na"] = array("mensaje" => "Sin coincidencias encontradass.");
		}
		$sth = null;
	}

	public function getInfografias($x) {
		/*print_r($x);
		exit;*/
		if ($x['anio'] == 2010) {
			$ann = "na_" . 2010;
		}else {
			$ann = "na_" . 2020;
		}
		$sql = 'SELECT a.*,b."NOM_NUCLEO" FROM ivp.' . $ann . ' a INNER JOIN public.na b ON a."ID_NA" = b."ID_NA" WHERE "ID" = :id';
		$sth = $this->conn->prepare($sql);
		$sth->bindValue(':id', $x['id'], PDO::PARAM_STR);

		$sth->execute();
		$rows = $sth->rowCount();
		if ($rows > 0) {
			$res = array();
			$row = $sth->fetch(PDO::FETCH_ASSOC);

			//$this->items_arr["indi"] = $row;

			foreach ($row as $key => $value) {

				$sql1 = 'SELECT tema,indicadores FROM catalogo.indicadores a INNER JOIN catalogo.tema b ON a."cve_tem" = b."cve_tem" WHERE "cve_ind" = :key AND b."cve_tem" != 1';
				$sth_t = $this->conn->prepare($sql1);
				$sth_t->bindValue(':key', $key, PDO::PARAM_STR);
				$sth_t->execute();
				$row_t = $sth_t->fetch(PDO::FETCH_ASSOC);
				$rows_t = $sth_t->rowCount();
				if ($rows_t > 0) {
					if (isset($res[$row_t['tema']])) {
						array_push($res[$row_t['tema']], array("label" => $row_t['indicadores'], "value" => $value));
					}else {
						$res[$row_t['tema']][] = array("label" => $row_t['indicadores'], "value" => $value);
					}
				}
				$this->items_arr["infografia"] = $res;
			}
		}else{
			$this->items_arr["infografia"] = array("mensaje" => "Sin coincidencias encontradass.");
		}
		$sth = null;
	}

	public function getCoords($x) {
		$sql = 'SELECT "NOMGEO","ID_ENT",ST_AsGeoJSON(ST_Transform("GEOM",4326)) AS "COORDS"
		FROM edo_mun.estados WHERE "ID_ENT" = :id_estado ORDER BY "NOMGEO" ASC';
		$sth = $this->conn->prepare($sql);
		$sth->bindValue(':id_estado', $x['id_estado'], PDO::PARAM_STR);
		$sth->execute();
		$rows = $sth->rowCount();
		if ($rows > 0) {
			$count_id = 1001349;
			$features = array();
			$count = 0;
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
				$row['COORDS'] = json_decode($row['COORDS']);
				$test = $row['COORDS']->coordinates;
				$c_test = count($test);
				if ($c_test > 1) {
					for ($i=0; $i < $c_test; $i++) { 
						$features[$count] = array(
							"type" => "Feature",
					        "properties" => array(
					            "id_poligono" => $count_id,
					            "featureclass" => "Urban area",
					            "agalloch" => 93
					        ),
					        "geometry" => array(
					            "type" => "Polygon",
					            "coordinates" => array(
					            	$test[$i][0]
					            )
					        )
						);
						$count++;
					}
				}else {
					$features[$count] = array(
						"type" => "Feature",
				        "properties" => array(
				            "id_poligono" => $count_id,
				            "featureclass" => "Urban area",
				            "agalloch" => 93
				        ),
				        "geometry" => array(
				            "type" => "Polygon",
				            "coordinates" => array(
				            	$row['COORDS']->coordinates[0][0]
				            )
				        )
					);
					$count++;
				}
				$count_id++;
			}
			$this->items_arr['estados']['features'] = $features;
		}else{
			$this->items_arr['estados'] = array("mensaje" => "Sin coincidencias encontradas.");
		}
		$sth = null;


		$sql = 'SELECT "NOMGEO","ID_MUN",ST_AsGeoJSON(ST_Transform("GEOM",4326)) AS "COORDS"
		FROM edo_mun.municipios WHERE "ID_ENT" = :id_estado AND "ID_MUN" = :id_municipio ORDER BY "NOMGEO" ASC';
		$sth = $this->conn->prepare($sql);
		$sth->bindValue(':id_estado', $x['id_estado'], PDO::PARAM_STR);
		$sth->bindValue(':id_municipio', $x['id_municipio'], PDO::PARAM_STR);
		$sth->execute();
		$rows = $sth->rowCount();
		if ($rows > 0) {
			$count_id = 2001349;
			$features = array();
			$count = 0;
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
				$row['COORDS'] = json_decode($row['COORDS']);
				$test = $row['COORDS']->coordinates;
				$c_test = count($test);
				if ($c_test > 1) {
					for ($i=0; $i < $c_test; $i++) { 
						$features[$count] = array(
							"type" => "Feature",
					        "properties" => array(
					            "id_poligono" => $count_id,
					            "featureclass" => "Urban area",
					            "agalloch" => 1349
					        ),
					        "geometry" => array(
					            "type" => "Polygon",
					            "coordinates" => array(
					            	$test[$i][0]
					            )
					        )
						);
						$count++;
					}
					$center = $row['COORDS']->coordinates[0][0][0];
				}else {
					$features[$count] = array(
						"type" => "Feature",
				        "properties" => array(
				            "id_poligono" => $count_id,
				            "featureclass" => "Urban area",
				            "agalloch" => 1349
				        ),
				        "geometry" => array(
				            "type" => "Polygon",
				            "coordinates" => array(
				            	$row['COORDS']->coordinates[0][0]
				            )
				        )
					);
					$count++;
					$center = $row['COORDS']->coordinates[0][0][0];
				}

				$count_id++;
			}

			$this->items_arr['municipios_center'] = $center;


			$this->items_arr['municipios']['features'] = $features;

		}else{
			$this->items_arr['municipios'] = array("mensaje" => "Sin coincidencias encontradas.");
		}
		$sth = null;

		self::propiedadPrivada($x['id_estado'], $x['id_municipio']);
	}

	public function propiedadPrivada($estado, $municipio) {
		$sql = 'SELECT "ID_LOC","LABEL",ST_AsGeoJSON(ST_Transform("GEOM",4326)) AS "COORDS"
		FROM public.agrupaciones_pp a
		INNER JOIN loc.loc_in_agrupaciones b ON a."ID_GROUPS" = b."ID_GROUPS"
		WHERE "ID_ENT" = :id_estado AND "ID_MUN" = :id_municipio';
		$sth = $this->conn->prepare($sql);
		$sth->bindValue(':id_estado', $estado, PDO::PARAM_STR);
		$sth->bindValue(':id_municipio', $municipio, PDO::PARAM_STR);
		$sth->execute();
		$rows = $sth->rowCount();
		if ($rows > 0) {
			$features = array();
			$count = 0;

			$peps = ["C" => 1,
					"E" => 2,
					"N" => 3,
					"NE" => 4,
					"NO" => 5,
					"O" => 6,
					"S" => 7,
					"SE" => 8,
					"SO" => 9];
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
				$row['COORDS'] = json_decode($row['COORDS']);
				$test = $row['COORDS']->coordinates;
				$c_test = count($test);
				if ($c_test > 1) {
					for ($i=0; $i < $c_test; $i++) {
						$features[$count] = array(
							"type" => "Feature",
					        "properties" => array(
					            "id_poligono_pp" => $row['ID_LOC'] . "-" .$row['LABEL'],
					            "featureclass" => "Urban area",
					            //"isPepe": true,
					            "agalloch" => $peps[$row['LABEL']]
					        ),
					        "geometry" => array(
					            "type" => "Polygon",
					            "coordinates" => array(
					            	$test[$i][0]
					            )
					        )
						);
						$count++;
					}
				}else {
					$features[$count] = array(
						"type" => "Feature",
				        "properties" => array(
				            "id_poligono_pp" => $row['ID_LOC'] . "-" .$row['LABEL'],
				            "featureclass" => "Urban area",
				            //"isPepe": true,
				            "agalloch" => $peps[$row['LABEL']]
				        ),
				        "geometry" => array(
				            "type" => "Polygon",
				            "coordinates" => array(
				            	$row['COORDS']->coordinates[0]
				            )
				        )
					);
					$count++;
				}
			}
			$this->items_arr['agrupaciones']['features'] = $features;
		}else{
			$this->items_arr['agrupaciones'] = array("mensaje" => "Sin coincidencias encontradas.");
		}
		$sth = null;

	}

	public function getDescSubtemas($x) {
		$sql = 'SELECT * FROM catalogo.des_soc_subtema WHERE subtema IS NOT NULL';
		$sth = $this->conn->prepare($sql);
		$sth->execute();
		$rows = $sth->rowCount();
		if ($rows > 0) {
			$this->items_arr['descsubtemas'] = array();//se debe llamar segun nuestro modulo
			/*$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $row) {*/
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
				$this->items_arr['descsubtemas'][] = $row;
			}
		}else{
			$this->items_arr['descsubtemas'] = array("mensaje" => "Sin coincidencias encontradas.");
		}
		$sth = null;
	}

	public function getDescIndicadores($x) {
		$sql = 'SELECT * FROM catalogo.des_soc_indicadores WHERE indicadores IS NOT NULL';
		$sth = $this->conn->prepare($sql);
		$sth->execute();
		$rows = $sth->rowCount();
		if ($rows > 0) {
			$this->items_arr['descindicadores'] = array();//se debe llamar segun nuestro modulo
			/*$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $row) {*/
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
				$this->items_arr['descindicadores'][] = $row;
			}
		}else{
			$this->items_arr['descindicadores'] = array("mensaje" => "Sin coincidencias encontradas.");
		}
		$sth = null;
	}

	public function getEstadosFormat() {
		$sql = 'SELECT "NOMGEO","ID_ENT" FROM edo_mun.estados';
		$sth = $this->conn->prepare($sql);
		$sth->execute();
		$rows = $sth->rowCount();
		if ($rows > 0) {
			$estados_format = array();//se debe llamar segun nuestro modulo
			/*$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $row) {*/
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
				$estados_format[$row['ID_ENT']] = $row['NOMGEO'];
			}
		}else{
			$estados_format = array("mensaje" => "Sin coincidencias encontradas.");
		}
		$sth = null;
		return $estados_format;
	}

	public function getMunicipiosFormat() {
		$sql = 'SELECT "NOMGEO","ID_MUN","ID_ENT" FROM edo_mun.municipios';
		$sth = $this->conn->prepare($sql);
		$sth->execute();
		$rows = $sth->rowCount();
		if ($rows > 0) {
			$municipios_format = array();//se debe llamar segun nuestro modulo
			/*$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $row) {*/
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
				$kee = $row['ID_ENT'] . "-" . $row['ID_MUN'];
				$municipios_format[$kee] = $row['NOMGEO'];
			}
		}else{
			$municipios_format = array("mensaje" => "Sin coincidencias encontradas.");
		}
		$sth = null;
		return $municipios_format;
	}

	public function getExport($x) {
		$this->items_arr['export'] = array();
		$this->items_arr['export']['excel'] = self::getExportExcel($x);
		$this->items_arr['export']['pdf'] = self::getExportPdf($x);
		echo json_encode($this->items_arr['export']);
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
			$ind_fi = $x['indicadores'];
			$x['indicadores'] = "," . $x['indicadores'];
		}

		$sql = 'SELECT a."ID",d."ID_ENT" AS "Estado",c."ID_MUN" AS "Municipio",b."NOM_LOC" AS "Localidad" ' . $x['indicadores'] . '  FROM ivp.loc_rur_' . $anio . ' a 
		INNER JOIN loc.localidades b ON a."CGLOC" = b."CGLOC"
		INNER JOIN edo_mun.municipios c ON b."ID_MUN" = c."ID_MUN"
		INNER JOIN edo_mun.estados d ON c."ID_ENT" = d."ID_ENT"
		
		';

		/*if ($x['id_localidad'] != "") {
			$sql.= ' WHERE a."CGLOC" = :id_localidad';
		}*/

		if ($x['localidades'] != "") {

			if ($x['localidades'][0] == "-1") {
				# code...
			}else {
				$lll = array();
				foreach ($x['localidades'] as $key => $value) {
					$lll[] = "'" . $value ."'";
				}
				$loc_join = join(",", $lll);
				$sql.= ' WHERE a."CGLOC" IN (' . $loc_join . ')';
			}
		}


		$sql.= ' ORDER BY a."ID" ';

		$sth = $this->conn->prepare($sql);

		/*if ($x['id_localidad'] != "") {
			$sth->bindValue(':id_localidad', $x['id_localidad'], PDO::PARAM_STR);
		}*/

		$estado = self::getEstadosFormat();
		$municipio = self::getMunicipiosFormat();;

		$sth->execute();
		$rows = $sth->rowCount();
		$this->items_arr['vulnerabilidad'] = array();//se debe llamar segun nuestro modulo
		if ($rows > 0) {
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
				$kee = $row['Estado'] . "-" . $row['Municipio'];
				$row['Estado'] = utf8_decode($estado[$row['Estado']]);
				$row['Municipio'] = utf8_decode($municipio[$kee]);
				$row['Localidad'] = utf8_decode($row['Localidad']);
				$this->items_arr['vulnerabilidad'][] = $row;
			}
		}
		$sth = null;

		$empty = array("");
		array_push($this->items_arr['vulnerabilidad'], $empty, $empty);

		
		$arrayName = array('', '', utf8_decode(''));
		/*$this->items_arr['vulnerabilidad'][] = $arrayName;


		$this->items_arr['vulnerabilidad'][] = array('ID', 'ID', '');
		$this->items_arr['vulnerabilidad'][] = array('Estado', 'Estado', '');
		$this->items_arr['vulnerabilidad'][] = array('Municipio', 'Municipio', '');
		$this->items_arr['vulnerabilidad'][] = array('Localidad', 'Localidad', '');
		$this->items_arr['vulnerabilidad'][] = array('NOM_LOC', 'Localidad', '');
		$this->items_arr['vulnerabilidad'][] = array('ID_ENT', 'Estado', '');
		$this->items_arr['vulnerabilidad'][] = array('ID_MUN', 'Municipio', '');

		if ($x['indicadores'] != "") {
			$ind = explode(",", $ind_fi);

			$res_in = self::getIndicadoresRing();

			foreach ($ind as $key => $value) {
				$bet = $res_in[$value];
				$this->items_arr['vulnerabilidad'][] = array($value, utf8_decode($bet), '');
			}
		}*/

		$res = self::ExportFile($this->items_arr['vulnerabilidad']);
		$file = "geo-" . self::generateRandomString() .time() . ".xls";
		$filename = "../temp-excel/" . $file;
		/*$fileEndEnd = mb_convert_encoding($res, 'ISO-8859-1', "UTF-8");*/
		file_put_contents($filename, $res);

		//echo json_encode(array("file_name" => $file, "deb" =>1349));
		return array("file_name" => $file, "deb" =>1349);
	}

	public function ExportFile($records) {
		$res_in = self::getIndicadoresRing();
		$heading = false;
		if(!empty($records)) {
			$a = "";
		  	foreach($records as $row) {
				if(!$heading) {
					//$a.= implode("\t", array_keys($row)) . "\n";
					$asd = array();
					for ($i=0; $i < count(array_keys($row)); $i++) {
						if (array_keys($row)[$i] != "ID" && array_keys($row)[$i] != "Estado" && array_keys($row)[$i] != "Municipio" && array_keys($row)[$i] != "Localidad") {
							$asd[] = utf8_decode($res_in['"'.array_keys($row)[$i].'"']);
						}else {
							$asd[] = array_keys($row)[$i];
						}
						
					}
		  			$a.= implode("\t",  $asd) . "\n";
		  			$heading = true;
				}
				$a.= implode("\t", array_values($row)) . "\n";
		  	}
		  	return $a;
		  }
	}

	public function getIndicadoresRing() {
		$sql = 'SELECT * FROM catalogo.indicadores WHERE indicadores IS NOT NULL';
		$sth = $this->conn->prepare($sql);
		$sth->execute();
		$rows = $sth->rowCount();
		if ($rows > 0) {
			$res = array();//se debe llamar segun nuestro modulo
			/*$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $row) {*/
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
				$res['"'.$row['cve_ind'].'"'] = $row['indicadores'];
			}
		}
		$sth = null;
		return $res;
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

	public function getExportPdf($x) {
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
			$ind_fi = $x['indicadores'];
			$x['indicadores'] = "," . $x['indicadores'];
		}

		$sql = 'SELECT a."ID",b."NOM_LOC" , d."ID_ENT", c."ID_MUN" ' . $x['indicadores'] . '  FROM ivp.loc_rur_' . $anio . ' a 
		INNER JOIN loc.localidades b ON a."CGLOC" = b."CGLOC"
		INNER JOIN edo_mun.municipios c ON b."ID_MUN" = c."ID_MUN"
		INNER JOIN edo_mun.estados d ON c."ID_ENT" = d."ID_ENT"
		';

		/*if ($x['id_localidad'] != "") {
			$sql.= ' WHERE a."CGLOC" = :id_localidad';
		}*/

		$sql.= ' ORDER BY a."ID" ';

		$sth = $this->conn->prepare($sql);

		$sth2 = $this->conn->prepare($sql);

		/*if ($x['id_localidad'] != "") {
			$sth->bindValue(':id_localidad', $x['id_localidad'], PDO::PARAM_STR);
			$sth2->bindValue(':id_localidad', $x['id_localidad'], PDO::PARAM_STR);
		}*/

		$estado = self::getEstadosFormat();
		$municipio = self::getMunicipiosFormat();;

		$sth->execute();
		$rows = $sth->rowCount();
		$sth2->execute();
		$rows2 = $sth2->rowCount();
		$this->items_arr['vulnerabilidad'] = array();//se debe llamar segun nuestro modulo
		/*$this->items_arr['header'] = array();//se debe llamar segun nuestro modulo
		$this->items_arr['vals'] = array();//se debe llamar segun nuestro modulo
		if ($rows > 0) {
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
				$kee = $row['ID_ENT'] . "-" . $row['ID_MUN'];
				$row['Estado'] = utf8_decode($estado[$row['ID_ENT']]);
				$row['Municipio'] = utf8_decode($municipio[$kee]);
				$row['Localidad'] = utf8_decode($row['NOM_LOC']);
				$row['NOM_LOC'] = utf8_decode($row['NOM_LOC']);
				$this->items_arr['vulnerabilidad'][] = $row;
				$this->items_arr['header'] = array_keys($row);
			}
		}
		//$sth = null;

		if ($rows2 > 0) {
			while ($row = $sth2->fetch(PDO::FETCH_NUM)) {
				$kee = $row[2] . "-" . $row[3];
				$row[] = utf8_decode($estado[$row[2]]);
				$row[] = utf8_decode($municipio[$kee]);
				$row[] = utf8_decode($row[1]);
				$row[1] = utf8_decode($row[1]);
				$this->items_arr['vals'][] = $row;
			}
		}
		$sth = null;*/

		/*$empty = array("");
		array_push($this->items_arr['vulnerabilidad'], $empty, $empty);*/

		
		$headeer = array('NEMONICO', 'NOMBRE', utf8_decode('DESCRIPCIÓN'));
		//$this->items_arr['vulnerabilidad'][] = $arrayName;


		$this->items_arr['vulnerabilidad'][] = array('ID', 'ID', '');
		$this->items_arr['vulnerabilidad'][] = array('Estado', 'Estado', '');
		$this->items_arr['vulnerabilidad'][] = array('Municipio', 'Municipio', '');
		$this->items_arr['vulnerabilidad'][] = array('Localidad', 'Localidad', '');
		/*$this->items_arr['vulnerabilidad'][] = array('NOM_LOC', 'Localidad', '');
		$this->items_arr['vulnerabilidad'][] = array('ID_ENT', 'Estado', '');
		$this->items_arr['vulnerabilidad'][] = array('ID_MUN', 'Municipio', '');*/

		if ($x['indicadores'] != "") {
			$ind = explode(",", $ind_fi);

			$res_in = self::getIndicadoresRing();

			foreach ($ind as $key => $value) {
				$bet = $res_in[$value];
				$this->items_arr['vulnerabilidad'][] = array($value, utf8_decode($bet), '');
			}
		}
		

		$pdffile = time()."-sdf.pdf";
		$urlFile = "../temp-pdf/" . $pdffile;

	   	$pdf = new PDF();
		$header = $headeer;
		$pdf->SetFont('Arial','',10);
		$pdf->AddPage();
		$pdf->BasicTable($header,$this->items_arr['vulnerabilidad']);
		$pdf->AddPage();
		$pdf->AddPage();
		$pdf->Output($urlFile,'F');

		//echo json_encode(array("file_name" => $urlFile, "deb" =>1349));
		return array("file_name" => $urlFile, "deb" =>1349);
	}

	var $B=0;
    var $I=0;
    var $U=0;
    var $HREF='';
    var $ALIGN='';

    function WriteHTML($html)
    {
        //HTML parser
        $html=str_replace("\n",' ',$html);
        $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
        foreach($a as $i=>$e)
        {
            if($i%2==0)
            {
                //Text
                if($this->HREF)
                    $this->PutLink($this->HREF,$e);
                elseif($this->ALIGN=='center')
                    $this->Cell(0,5,$e,0,1,'C');
                else
                    $this->Write(5,$e);
            }
            else
            {
                //Tag
                if($e[0]=='/')
                    $this->CloseTag(strtoupper(substr($e,1)));
                else
                {
                    //Extract properties
                    $a2=explode(' ',$e);
                    $tag=strtoupper(array_shift($a2));
                    $prop=array();
                    foreach($a2 as $v)
                    {
                        if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                            $prop[strtoupper($a3[1])]=$a3[2];
                    }
                    $this->OpenTag($tag,$prop);
                }
            }
        }
    }

    function OpenTag($tag,$prop)
    {
        //Opening tag
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,true);
        if($tag=='A')
            $this->HREF=$prop['HREF'];
        if($tag=='BR')
            $this->Ln(5);
        if($tag=='P')
            $this->ALIGN=$prop['ALIGN'];
        if($tag=='HR')
        {
            if( !empty($prop['WIDTH']) )
                $Width = $prop['WIDTH'];
            else
                $Width = $this->w - $this->lMargin-$this->rMargin;
            $this->Ln(2);
            $x = $this->GetX();
            $y = $this->GetY();
            $this->SetLineWidth(0.4);
            $this->Line($x,$y,$x+$Width,$y);
            $this->SetLineWidth(0.2);
            $this->Ln(2);
        }
    }

    function CloseTag($tag)
    {
        //Closing tag
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,false);
        if($tag=='A')
            $this->HREF='';
        if($tag=='P')
            $this->ALIGN='';
    }

    function SetStyle($tag,$enable)
    {
        //Modify style and select corresponding font
        $this->$tag+=($enable ? 1 : -1);
        $style='';
        foreach(array('B','I','U') as $s)
            if($this->$s>0)
                $style.=$s;
        $this->SetFont('',$style);
    }

    function PutLink($URL,$txt)
    {
        //Put a hyperlink
        $this->SetTextColor(0,0,255);
        $this->SetStyle('U',true);
        $this->Write(5,$txt,$URL);
        $this->SetStyle('U',false);
        $this->SetTextColor(0);
    }
}