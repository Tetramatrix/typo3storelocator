<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2004-2009 Chi Hoang (info@chihoang.de)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is 
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
* 
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
* 
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/** 
 * Plugin 'Haendlersuche' for the 'ch_haendlersuche' extension.
 *
 * @author	Chi Hoang <info@chihoang.de>
 */
 
$DEBUG = 0;   
$MODE = 1; 
$LIB  = Array ( 'dummy', 'lesen_01');
$DB_HOST = "localhost";        

$DB_USER = "root";
$DB_PASSWORD = "typo3";
$DB_NAME = "amici";
//$DB_USER = "leebsites";
//$DB_PASSWORD = "kosEmyel6";
//$DB_NAME = "tgb";  

$DB_PLZ_TABLE = "tx_chhaendlersuche_plz";
$DB_KUNDE_TABLE = "CUSTTABLE";

$UMKREIS = 80;
$SELECT = 0;
$HAENDLERRADIUS = 40; // in pixel
$HTML = "google";

	// SQL - Parameter
	// 1. Array = Felder in der DB, 2. Array = Werte der Felder, 3. Array = Felder der Rückgabe 
$DB_QUERY = Array( Array(
					Array('plz', 'land'),
					Array('dummy','dummy'),
					Array('ortsname','plz','breitengrad','laengengrad','KoordX','KoordY','KoordZ')
					)
			    );
$DB_STATIC = Array(Array (
					Array('LINEDISC', 'CUSTCLASSIFICATIONID'),
					Array('dummy', 'dummy'),
					Array('NAME', 'ZIPCODE', 'CITY', 'URL', 'STREET', 'PHONE', 'TELEFAX', 'NAMEALIAS', 'EMAIL','LINEDISC','CUSTCLASSIFICATIONID')
					)
			    );
?>
