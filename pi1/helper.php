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
 * Plugin 'H�ndlersuche' for the 'ch_haendlersuche' extension.
 *
 * @author	Chi Hoang <info@chihoang.de>
 */
 
class config {
    function config($config=Array()) {        
        foreach($config as $k=>$v) {
            $this->{$k} = $v;
        }        
    }
}

class arrayhelper {
	/**
    *
    *  http://de2.php.net/manual/de/function.array-multisort.php
    *  artox at online dot no
    *  16-Jul-2004 05:16 
    *  I made this little function for sorting small multi-dim arrays by a chosen column in multiple rows. 
    *  Sorts numeric, ascending. 
    *
    *  $arr = array to sort.
    *  $col = column to sort by. 
    *
    */
    
    function incision_sort($arr, $col){
           for($k = 0; $k < sizeof($arr)-1; $k++){
               # $arr[$k+1] is possibly in the wrong place. Take it out. 
               $t = $arr[$k+1]; 
               $i = $k;    
               
               # Push $arr[i] to the right until we find the right place for $t. 
               while($i >= 0 && $arr[$i][$col] > $t[$col]){
                   $arr[$i+1] = $arr[$i];
                   $i--;
               } 
               
               # Insert $t into the right place.
               $arr[$i+1] = $t;                            
           }# End sort
           return $arr;        
       }
       
    function remove_empty ($inarray) {
        if (is_array($inarray)) {
            foreach ($inarray as $k => $v) {
                if (!(empty($v))) {
			$out[]=$v;
                }
            }
            return $out;
        } else {
            return $inarray;
        }
    }
    
    function resetIndex($inarray) {
     if (is_array($inarray)) {
            foreach($inarray as $k => $v) {
                $out[]=$v;
            }
            return $out;
        } else {
            return $inarray;
        }    
    }
}

class db {
    var $connid;
    var $erg;

    function db($host,$user,$passwort) {
        if(!$this->connid = mysql_connect($host, $user, $passwort)) {
            echo "Fehler beim Verbinden...";
        }
        return $this->connid;
    }

    function select_db($db) {
        if (!mysql_select_db($db, $this->connid)) {
            echo "Fehler beim Ausw�hlen der DB...";
        }
    }

    function sql($sql) {
        if (!$this->erg = mysql_query($sql, $this->connid)) {
            echo "Fehler beim Senden der Abfrage...";
        }
        return $this->erg;
    }
}
?>