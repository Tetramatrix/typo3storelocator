<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2010 Chi Hoang (info@chihoang.de)
*  All rights reserved
*
***************************************************************/

define('OFFSET', 268435456);
define('RADIUS', 85445659.4471); /* $offset / pi() */
define('ERDRADIUS',6378.388);
define('FALSEEASTING',-1*85445659.4471/2);
define('FALSENORTHING',85445659.4471/2);

require_once("JSON.php");
require_once("hilbert.php");
require_once("debug.php");

class haendlersuche {

	var  $falseEasting, $falseNorthing, $radius, $json, $col, $sqlParameter, $buffer, $cluster, $country = array ( "DE" => "Deutschland", "AT" => "Oesterreich", "BE" => "Belgien", "NL" => "Nederland", "CH" => "Schweiz" );

	var $zipcode = array ( 
						"0, 0" => array ( 'X', 'Y' ),
						"0, 1" => array ( 'X', 'Locality'), 
						"1, 0" => array ('SubAdministrativeArea', 'Y' ),
						"1, 1" => array ('SubAdministrativeArea', 'Locality')
					);
				
	function kreisKollision($x1, $y1, $x2,  $y2,  $umkreis = '15') {
		if ($x1 == $x2 && $y1 == $y2) return true;
		return ( sqrt(pow(($x1-$x2),2) + pow(($y1-$y2),2)) <= $umkreis ) ? true : false;
	}
	
	function entfernung($ursprungLat,  $ursprungLng,  $lat,  $lng,  $umkreis = '15') {
		return (acos(SIN($ursprungLat /180*pi())*SIN($lat / 180*pi())+COS($ursprungLat /180*pi())*COS($lat / 180*pi())*COS($lng / 180*pi() - $ursprungLng / 180*pi()))*ERDRADIUS <= $umkreis) ? true : false;
	}
	
	function falseeasting ($radius) {
		return -1*$radius / 2;
	}
	
	function falsenorthing ($radius) {
		return $radius / 2;
	}
	
	function circumference($zoom) {
		$tiles = pow(2, $zoom);
		return 256 * $tiles / (2 * pi());
	}
	
	function deg2rad($rad) {
		return $rad * pi() / 180;
	}
	
	function lngToX($lng) {       
		return round($this->radius * $this->deg2rad($lng)-$this->falseEasting);        
	}

	function latToY($lat) {
		$lat = $this->deg2rad($lat);
		return round(($this->radius/2 * log((1 + sin($lat )) / (1 - sin($lat)))) - $this->falseNorthing) * -1;
	}

	function pixelDistance($lat1, $lng1, $lat2, $lng2, $zoomlevel, $umkreis = '15') {
		if ($lat1==$lat2 && $lon1 == $lon2) return true;
		$x1 = $this->lngToX($lng1);
		$y1 = $this->latToY($lat1);
		$x2 = $this->lngToX($lng2);
		$y2 = $this->latToY($lat2);		
		return (sqrt(pow(($x1-$x2),2) + pow(($y1-$y2),2)) >> (21 - $zoomlevel) <= $umkreis) ? true : false;
	}
	
		// Another, simpler, implementation to test if mulitarrays are empty
	function array_empty($mixed) {
		if (is_array($mixed)) {
			foreach ($mixed as $value) {
				if (!$this->array_empty($value)) {
					return false;
				}
			}
		}
		elseif (!empty($mixed)) {
			return false;
		}
		return true;
	}	 

	function standort($standort, $cluster) {
		if ( !$this->array_empty($cluster)) {
			foreach ($cluster as $key => $item) {
				if ($this->kreisKollision($item[0]['x'], $item[0]['y'], $standort['x'], $standort['y'], 30)) {
				//~ if ($item[0]['x'] == $standort['x'] && $item[0]['y'] == $standort['y']) {
					unset($cluster[$key]); $i=0;
					foreach ($cluster as $z => $y) {
						$new[$i] = $y; ++$i;
					}
					return array($item, $new);
					break;
				}
			}
			return array(array( '0' => array ( 'r' => $standort)), $cluster);
		} else {
			return array(array( '0' => array ( 'r' => $standort)), $cluster);
		}
	}
	
	function cluster($markers, $distance) {
		$clustered = array();
		/* Loop until all markers have been compared. */
		while (count($markers)) {
			$marker = array_shift($markers);
			$cluster = array();
			/* Compare against all markers which are left. */
			foreach ($markers as $key => $item) {
				/* If two markers are closer than given distance remove */
				/* target marker from array and add it to cluster.      */
				if ($this->kreisKollision($marker['x'], $marker['y'], $item['x'], $item['y'], $distance)) {
					unset($markers[$key]);
					$cluster[] = $item;
				}
			}
			/* If a marker has been added to cluster, add also the one  */
			/* we were comparing to and remove the original from array. */
			if (count($cluster) > 0) {
				array_unshift($cluster, $marker);
				$clustered[] = $cluster;
			} else {
				$clustered[] = array ( $marker ) ;
			}
		}
		return $clustered;
	}
  
	function menu() {
		echo "Bitte waehlen Sie ein Kommando aus (0: DB mit Testwerte fuellen, 1: Lesen)";
			/* hier ist das Ende */
		break;     
	}
	
	function geocoder($query, $apikey) {
		
		if ($query->query != "map") {
			$q = $query->db_query[0][1][0]." ".$this->country[$query->db_query[0][1][1]];
		} else {
			$q = $query->db_query[0][1][0];
		}
		
		//$url = "http://maps.google.com/maps/geo?q=".urlencode($q)."&key=$apikey";
		$url = "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($q)."&sensor=false";
		
			// sendRequest
			// note how referer is set manually
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			//curl_setopt($ch, CURLOPT_REFERER,"http://www.tgb.com");
		$body = curl_exec($ch);
		curl_close($ch);

		//if (!$handle = fopen("/home/chi/flashbug", "a")) {
		//	print "Kann die Datei $filename nicht öffnen";
		//	exit;
		//}
		//
		//	// Schreibe $somecontent in die geöffnete Datei.
		//if ( !fwrite ( $handle, "body:" . $body ) ) {
		//	print "Kann in die Datei $filename nicht schreiben";
		//	exit;
		//}
		//fclose($handle);
		
			// now, process the JSON string
		$this->json = json_decode($body, FALSE);
	}
	
	function buildQuery($query) {
	
		$tmp=array();
		for ($i=0; $end=sizeof($query->db_query[$query->select][2]),$i<$end ; $i++) {
			$tmp[] = 'coo.'.$query->db_query[$query->select][2][$i];
		}
		$this->col = implode(',',$tmp); $tmp=array();
		for ($i=0; $end=sizeof($query->db_static[$query->select][0]),$i<$end; $i++) {
			if ($query->db_static[$query->select][1][$i] != '*') {
				$tmp[] = $query->db_static[$query->select][0][$i].' IN (\''.$query->db_static[$query->select][1][$i].'\')';
			}
		}
		$this->sqlParameter = str_replace("''","'",str_replace('|',"'",str_replace('/',"','",implode(' AND ',$tmp))));	
		$tmp=array();
		for ($i=0; $end=sizeof($query->db_static[$query->select][2]),$i <$end; $i++) {
			$tmp[] = $query->db_static[$query->select][2][$i];
		}
		$this->col .= ','.implode(',',$tmp);
	}
	
	function lesen_01($plz, $kunde) {
	
		$feUserObj = tslib_eidtools::initFeUser(); // Initialize FE user object		
		tslib_eidtools::connectDB(); //Connect to database

		$debugOutput = new debug(); 
		
		if (empty($plz->db_query[0][1][0])) {
			return '<div class="headline">Ergebnisübersicht</div><div class="ergebnis">Keine Treffer.</div></div>';
		};
		
		$this->radius = $this->circumference($plz->zoomlevel);
		$this->falseEasting = $this->falseEasting($this->radius);
		$this->falseNorthing = $this->falseNorthing($this->radius);
		
		switch ($plz->query) {

			case "dynamic" :
				for ($i =0; $i < $plz->dynamicFactor; $i++) { 
					$plz->umkreis += $plz->umkreis;
				}
			case "zipcode" :
			case "name" :
			case "map" :
			
				if ($plz->dynamic == "1" || $plz->dynamic == "2") {
					for ($i =0; $i < $plz->dynamicFactor; $i++) { 
						$plz->umkreis += $plz->umkreis;
					}
				}
				
				$this->geocoder($plz, $plz->apikey);
			
				if (empty($this->json->results) || $this->json->results[0]->address_components[5]->short_name != $plz->db_query[$plz->select][1][1]) {
				
					return '<div class="headline">Ergebnisübersicht</div><div class="ergebnis">Keine Treffer.</div></div>';
				
				} else  {
				
					//~ $code['ortsname'] = $this->json->Placemark[0]->AddressDetails->Country->AdministrativeArea->SubAdministrativeArea->Locality->LocalityName;
					//if ($this->json->Placemark[0]->AddressDetails->Country->AdministrativeArea->Locality->LocalityName != NULL) {
					//	$code['ortsname'] = $this->json->Placemark[0]->AddressDetails->Country->AdministrativeArea->Locality->LocalityName;
					//} elseif ($this->json->Placemark[0]->AddressDetails->Country->AdministrativeArea->SubAdministrativeArea->Locality->LocalityName != NULL) {
					//	$code['ortsname'] = $this->json->Placemark[0]->AddressDetails->Country->AdministrativeArea->SubAdministrativeArea->Locality->LocalityName;
					//} 
					//list($code['lng'], $code['lat']) = $this->json->Placemark[0]->Point->coordinates ? $this->json->Placemark[0]->Point->coordinates : array();
					
					$code['ortsname']=$this->json->results[0]->address_components[2]->long_name;
					$code['lng']=$this->json->results[0]->geometry->location->lng;
					$code['lat']=$this->json->results[0]->geometry->location->lat;
					
					$code['lambda'] =  $this->deg2rad($code['lng']);
					$code['phi'] =  $this->deg2rad($code['lat']);
					$code['x'] = $this->lngToX($code['lng']);
					$code['y'] = $this->latToY($code['lat']);
					$code['e'] = 0;
					
					//~ foreach ($this->zipcode as $k => $v) {
						//~ list($left, $right) = $v;
						//~ $zipcode = "$left->$right";
						//~ if ($left == "X" && $right == "Y") {
							//~ $zipcode = "PostalCode->PostalCodeNumber";
						//~ } elseif ($left == "X") {
							//~ $zipcode = str_replace("X->", "", $zipcode);
							//~ $zipcode .= "->PostalCode->PostalCodeNumber";
						//~ } elseif ($right == "Y") {
							//~ $zipcode = str_replace("Y", "", $zipcode);
							//~ $zipcode .= "->PostalCode->PostalCodeNumber";
						//~ } else {
							//~ $zipcode .= "->PostalCode->PostalCodeNumber";
						//~ }
				
						//~ if ($this->json->Placemark[0]->AddressDetails->Country->AdministrativeArea->{$zipcode} != NULL) {
							//~ $code['plz'] = $this->json->Placemark[0]->AddressDetails->Country->AdministrativeArea->{$zipcode};
						//~ }
					//~ }	
					
					//if ($this->json->Placemark[0]->AddressDetails->Country->AdministrativeArea->PostalCode->PostalCodeNumber != NULL) {
					//	$code['plz'] = $this->json->Placemark[0]->AddressDetails->Country->AdministrativeArea->PostalCode->PostalCodeNumber;
					//} elseif ($this->json->Placemark[0]->AddressDetails->Country->AdministrativeArea->Locality->PostalCode->PostalCodeNumber != NULL) {
					//	$code['plz'] = $this->json->Placemark[0]->AddressDetails->Country->AdministrativeArea->Locality->PostalCode->PostalCodeNumber;
					//} elseif ($this->json->Placemark[0]->AddressDetails->Country->AdministrativeArea->SubAdministrativeArea->PostalCode->PostalCodeNumber != NULL) {
					//	$code['plz'] = $this->json->Placemark[0]->AddressDetails->Country->AdministrativeArea->SubAdministrativeArea->PostalCode->PostalCodeNumber;
					//} elseif ($this->json->Placemark[0]->AddressDetails->Country->AdministrativeArea->SubAdministrativeArea->Locality->PostalCode->PostalCodeNumber != NULL) {
					//	$code['plz'] = $this->json->Placemark[0]->AddressDetails->Country->AdministrativeArea->SubAdministrativeArea->Locality->PostalCode->PostalCodeNumber;
					//} 
			 
					$code['plz']=$this->json->results[0]->address_components[0]->long->name;
			
					$this->buildQuery($plz);
					//$q = "SELECT $this->col,
					//	ACOS(SIN(".$code['phi'].") * SIN(breitengrad/180*pi()) + COS(".$code['phi'].") * COS(breitengrad/180*pi()) * COS(laengengrad/180*pi() - ".$code['lambda'].")) * ".ERDRADIUS." as entfernung
					//	FROM $kunde->db_table 
					//	INNER JOIN $plz->db_table AS coo
					//	ON (coo.plz = ZIPCODE AND ( coo.ortsname = CITY OR coo.altName = CITY ) )
					//	WHERE  $this->sqlParameter HAVING entfernung <= ".$plz->umkreis." ORDER BY entfernung ASC 
					//";
					$q = "SELECT $this->col,
						ACOS(SIN(".$code['phi'].") * SIN(breitengrad/180*pi()) + COS(".$code['phi'].") * COS(breitengrad/180*pi()) * COS(laengengrad/180*pi() - ".$code['lambda'].")) * ".ERDRADIUS." as entfernung
						FROM $kunde->db_table 
						INNER JOIN $plz->db_table AS coo
						ON (coo.plz = ZIPCODE  )
						WHERE  $this->sqlParameter HAVING entfernung <= ".$plz->umkreis." ORDER BY entfernung ASC 
					";
				}				
				break;
				
			case "dealer" :
		
				$this->buildQuery($plz);
				$this->sqlParameter .= " AND NAME LIKE '%".$plz->db_query[0][1][0]."%'";
				
				$q = "SELECT $this->col 
						FROM $kunde->db_table 
						INNER JOIN $plz->db_table AS coo
						ON (coo.plz = ZIPCODE AND ( coo.ortsname = CITY OR coo.altName = CITY ) )
						WHERE  $this->sqlParameter Order By NAME ASC LIMIT 10
					";
			
					// open sql db
				$result = $GLOBALS['TYPO3_DB']->sql_query( $q );
				if ($row  = mysql_fetch_array($result)) {
					
					$code['ortsname'] = $row['NAME'];
					$code['lng'] = $row['laengengrad'];
					$code['lat'] = $row['breitengrad'];
					$code['lambda'] =  $this->deg2rad($row['laengengrad']);
					$code['phi'] =  $this->deg2rad($row['breitengrad']);
					$code['x'] = $this->lngToX($code['lng']);
					$code['y'] = $this->latToY($code['lat']);
					$code['plz'] = $row['plz'];
					$code['e'] = 0;
					
				} else {
				
					return '<div class="headline">Ergebnisübersicht</div><div class="ergebnis">Keine Treffer.</div></div>';
					
				}
				
				$this->buildQuery($plz);
				$this->sqlParameter .= " AND NAME LIKE '%".$plz->db_query[0][1][0]."%'";
				
				//$q = "SELECT $this->col,
				//	ACOS(SIN(".$code['phi'].") * SIN(breitengrad/180*pi()) + COS(".$code['phi'].") * COS(breitengrad/180*pi()) * COS(laengengrad/180*pi() - ".$code['lambda'].")) * ".ERDRADIUS." as entfernung
				//	FROM $kunde->db_table 
				//	INNER JOIN $plz->db_table AS coo
				//	ON (coo.plz = ZIPCODE AND ( coo.ortsname = CITY OR coo.altName = CITY ) )
				//	WHERE  $this->sqlParameter HAVING entfernung <= ".$plz->umkreis." ORDER BY NAME DESC 
				//";
				
				$q = "SELECT $this->col,
					ACOS(SIN(".$code['phi'].") * SIN(breitengrad/180*pi()) + COS(".$code['phi'].") * COS(breitengrad/180*pi()) * COS(laengengrad/180*pi() - ".$code['lambda'].")) * ".ERDRADIUS." as entfernung
					FROM $kunde->db_table 
					INNER JOIN $plz->db_table AS coo
					ON (coo.plz = ZIPCODE )
					WHERE  $this->sqlParameter HAVING entfernung <= ".$plz->umkreis." ORDER BY NAME DESC 
				";
			break;
		}
		
		/* 
		*   alle  Händler anhand der Gruppe (Roller / ATV) , der Stati und des Umkreis auslesen  
		*
		*  $dealer
		*  [0] => Array
		*   (
		*       [haendler] => AGRO-Holzhandel
		*       [plz] => 99718
		*       [ort] => Greussen/Thür\.
		*       [homepage] => www\.thueringer-holzhandel\.de
		*       [strasse] => Vor dem Warthügel 1
		*       [tel] => 03636 701280
		*       [fax] => 03636 701220
		*       [gruppe] => 0
		*       [ansprechpartner] => 
		*       [firmenlogo] => Agro-Greussen\.gif
		*       [email] => info@thueringer-holzhandel\.de
		*   )
		*/
		 
		switch ($plz->debug) {
			case 1:
				$debugOutput->intro($plz->db_host,$plz->db_name,$plz->db_user,$plz->db_table,$plz->db_password, $Connect_01);
				break;
		}
		
		//if (!$handle = fopen("/home/chi/flashbug", "a")) {
		//	print "Kann die Datei $filename nicht öffnen";
		//	exit;
		//}
		//
		//	// Schreibe $somecontent in die geöffnete Datei.
		//if (!fwrite($handle, "q:".$q.$plz->query)) {
		//	print "Kann in die Datei $filename nicht schreiben";
		//	exit;
		//}
		//fclose($handle);
		
			// open sql db
		$result = $GLOBALS['TYPO3_DB']->sql_query( $q ); $i=0;  
		while ($row = mysql_fetch_assoc($result)) {
			foreach ($row as $k => $v) {
				$tmp[$k] = htmlentities($v);
			}
			$tmp['COUNTRY']  = $this->country[$plz->db_query[0][1][1]];
			$tmp['lat'] = $row['breitengrad'];
			$tmp['lng'] = $row['laengengrad'];
			$tmp['e'] = ceil($row['entfernung']);
			$dealer[$i]['r'] = $tmp;
			$dealer[$i]['x'] = $this->lngToX($row['laengengrad']);
			$dealer[$i]['y'] = $this->latToY($row['breitengrad']);
			$dealer[$i]['e'] = $row['entfernung'];
			//~ $dealer[$i]['i'] = $obj->point_to_hilbert($dealer[$i]['x'] , 0-$dealer[$i]['y'] ,(22 - $plz->zoomlevel));
			//~ $dealer[$i]['i'] = $obj->point_to_hilbert(round($row['laengengrad']) , round($row['breitengrad']),(22 - $plz->zoomlevel));
			++$i;
		}
		
		if (count($dealer)>1) {
			$sort = array();
			foreach ($dealer as $k => $v) {
				$sort[] = $v['e'];
			}
			array_multisort($sort, SORT_ASC, SORT_NUMERIC, $dealer);
		}
		
		switch ($plz->html) {
		
			case "google":

					// cluster
				if (!$this->array_empty($dealer)) {
					$cluster['map']  = $this->cluster($dealer, $plz->haendlerradius);
				} else {
					return '<div class="headline">Ergebnisübersicht</div><div class="ergebnis">Keine Treffer.</div></div>';
				}
				list($cluster['standort'], $cluster['map']) = $this->standort($code, $cluster['map']);
				
				$this->result = json_encode($cluster);
				break;
		}
		
		switch ($plz->debug){
			case 1:
				$debugOutput->flashResult($this->result);                    
				return ($debugOutput->content);
				break;
			default:
				return ($this->result);
				break;
		}  
	}
}
?>