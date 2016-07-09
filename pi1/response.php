<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2010 Chi Hoang (info@chihoang.de)
*  All rights reserved
*
***************************************************************/

header('content-type: text/html; charset=utf-8');
header("Expires: Sat, 1 Jan 2005 00:00:00 GMT");
header("Last-Modified: ".gmdate( "D, d M Y H:i:s")."GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

require_once ("config.php");
require_once ("helper.php");
require_once ("haendlersuche.php");

ini_set("error_reporting", E_ALL & ~E_DEPRECATED);
xdebug_break();

$DB_QUERY = Array	( 	Array(
							Array('plz', 'land'),
							Array(addslashes($_GET['value']), addslashes($_GET['land'])),
							Array('ortsname','plz','breitengrad','laengengrad')
						)
					);
	    
$DB_STATIC = Array	(	Array (
							Array('COUNTRYREGIONID', 'LINEDISC', 'CUSTCLASSIFICATIONID'),
							Array(addslashes($_GET['land']), addslashes($_GET['LINEDISC']), addslashes($_GET['CUSTCLASSIFICATIONID'])),
							Array('NAME', 'ZIPCODE', 'CITY', 'URL', 'STREET', 'PHONE', 'TELEFAX', 'NAMEALIAS', 'EMAIL','LINEDISC','CUSTCLASSIFICATIONID','COUNTRYREGIONID')
						)
					);
	    
$config = array(	"debug" => $DEBUG,
				"mode" => $MODE,
				"lib" => $LIB,
				"db_host" => $DB_HOST,
				"db_user" => $DB_USER,
				"db_name" => $DB_NAME,
				"db_password" => $DB_PASSWORD,
				"db_table" => $DB_PLZ_TABLE,
				"db_query" => $DB_QUERY,
				"db_static" => $DB_STATIC,
				"umkreis" => $UMKREIS,
				"select" => $SELECT,
				"query" => addslashes($_GET['query']),
				"dynamic" => addslashes($_GET['dynamic']),
				"dynamicFactor" => addslashes($_GET['dynamicFactor']),
				"haendlerradius" => $HAENDLERRADIUS,
				"html" => $HTML,
				"kontaktdaten" => addslashes($_GET['kontaktdaten']),
				"zoomlevel" => addslashes($_GET['zoomlevel']),
);

	// now have some fun with the results...
//~ if (!$handle = fopen("/home/chi/flashbug", "a")) {ini_set("error_reporting", E_ALL & ~E_DEPRECATED);
	//~ print "Kann die Datei $filename nicht öffnen";
	//~ exit;
//~ }

	//~ // Schreibe $somecontent in die geöffnete Datei.
//~ if (!fwrite($handle, "plz:".$_GET['value']."|umkreis:".addslashes($_GET['umkreis']))) {
	//~ print "Kann in die Datei $filename nicht schreiben";
	//~ exit;
//~ }
//~ fclose($handle);
	
$config_plz = new config($config);
$config["db_table"] = $DB_KUNDE_TABLE;
$config_kunde = new config($config);

$config_plz->umkreis = $_GET['umkreis'];
$config_kunde->umkreis = $_GET['umkreis'];       

$haendler = new haendlersuche();
echo call_user_method ($config_kunde->lib[$config_plz->mode], $haendler , $config_plz, $config_kunde);

?>