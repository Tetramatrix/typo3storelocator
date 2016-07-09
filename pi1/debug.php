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
 
class debug {
    var $content;

    function __construct() {
        $this->content = "";    
    }
    
    function intro($db_host,$db_name,$db_user,$db_table,$db_password, $db_connect) {    
        $this->content .= "<style type=\"text/css\">td{font-size:10pt;}</style><table border=0 cellspacing=0 cellpadding=3 height=1><tr><td><h2><a name=\"top\">H�ndlersuche</a></h2></td><td valign=top><sup><a href=\"http://www.chihoang.de\" target=\"_blank\">&copy; chi hoang</a><sup></td></tr><tr><td colspan=2><b>Version 0.1</b></td></tr><tr><td colspan=2><b>Debug Modus</b></td></tr></table>";
        $this->content .= "<table border=0 cellspacing=0 cellpadding=3><tr><td valign=top align=left>";
        $this->content .= "<table border=1 cellspacing=0 cellpadding=3>";
        $this->content .= "<tr><td bgcolor=ffee00 colspan=\"2\"><font size=+2><i>Ausgangspunkt:</i></font></td></tr>";
        $this->content .="<tr><td colspan=\"2\"><b>DB:</b> ".$db_host.":".$db_user.":".$db_password.":".$db_name.":".$db_table."</td></tr>";
        $this->content .= "<tr><td colspan=2><b>Connect:</b> ".$db_connect."</td></tr>";
    }
        
    function gefundeneDatensaetze($sqlParameter,$nrows) {
        $this->content .=  "<tr><td><b>Query:</b></td><td>".$sqlParameter."</td></tr>";
        $this->content .=  "<br><tr><td colspan=\"2\">Gefunden:</td></tr><tr><td><b>Datensatz:</b></td><td>".$nrows."</td></tr>";
    }
    
    function haendlersuche($Ortsname0,$Plz0,$Laengengrad0,$Breitengrad0,$umkreis) {
        $this->content .= "<tr><td><b>Ortsname:</b></td><td>".$Ortsname0."</td></tr>";
        $this->content .= "<tr><td><b>PLZ:</b></td><td>".$Plz0."</td></tr>";
        $this->content .= "<tr><td><b>L�ngengrad:</b></td><td>". $Laengengrad0."</td></tr>";
        $this->content .= "<tr><td><b>Breitengrad:</b></td><td>".$Breitengrad0."</td></tr>";
        $this->content .= "<tr><td><b><a href=\"#umkreis\">Suche alle H�ndler im Umkreis von:</a></b></td><td>".$umkreis." km </td></tr></table>";    
    }
    
     function hilf1($db_host,$db_name,$db_user,$db_table,$db_password, $db_connect) {    
        $this->content .= "</td><td>";
        $this->content .= "<table border=1 cellspacing=0 cellpadding=3>";
        $this->content .= "<tr><td bgcolor=66ff00 colspan=\"2\"><font size=+2><i>H�ndler Standorte:</i></font></td></tr>";
        $this->content .= "<tr><td colspan=2><b>DB:</b> ".$db_host.":".$db_user.":".$db_password.":".$db_name.":".$db_table."</td></tr>";
        $this->content .= "<tr><td colspan=2><b>Connect:</b> ".$db_connect."</td></tr>";
     }
     
    function kunde($kunde, $nrows) {
        $this->content .= "<tr><td><b>Query:</b></td><td>Select * from ". $kunde . "</td></tr>";
        $this->content .= "<br><tr><td colspan=\"2\">Gefunden:</td></tr><tr><td><b>Datensatz:</b></td><td>".$nrows."</td></tr>";
    } 

    function kundenDatensaetze($nrows, $Kunde00, $Plz00, $Ort00, $Homepage00) {    
        $this->content .= "<tr><td colspan=2>1. Datensatz (von $nrows Datens�tzen)</td></tr>";
        $this->content .= "<tr><td><b>H�ndler:</b></td><td>".$Kunde00."</td></tr>";
        $this->content .= "<tr><td><b>PLZ:</b></td><td>".$Plz00."</td></tr>";
        $this->content .= "<tr><td><b>Ort:</b></td><td>". $Ort00."</td></tr>";
        $this->content .= "<tr><td><b>Homepage:</b></td><td>".$Homepage00."</td></tr></table><br>";
    }
    
    function alleHaendler($db_host,$db_name,$db_user,$db_table,$db_password, $db_connect) {
        $this->content .=  "</td></tr></table>";
        $this->content .=  "<table border=1 cellspacing=0 cellpadding=3>";
        $this->content .=  "<tr><td bgcolor=4499ff colspan=7><font size=+2><i>Alle H�ndler (Entfernung von $Ortsname0):</i></font><br></td></tr>";
        $this->content .=  "<tr><td colspan=7><b>DB:</b> ".$db_host.":".$db_user.":".$db_password.":".$db_name.":".$db_table."</td></tr>";
        $this->content .=  "<tr><td colspan=7><b>Connect:</b> ".$db_connect."</td></tr>";
        $this->content .=  "<tr><td><b>Query:</b></td><td><b>Datensatz:</b></td><td><b>Ortsname:</b></td><td><b>PLZ:</b></td><td><b>L�ngengrad:</b></td><td><b>Breitengrad:</b></td><td><b>Entfernung (km):</b></td></tr>";
    }
    
    function haendlerSpalte($color, $sqlParameter, $nrows) {
        $color=$color ^ 1;
        switch ($color) {
            case 0:
                $this->content .= "<tr bgcolor=eeeeee><td>".$sqlParameter."</td>";
                $this->content .= "<td bgcolor=dddddd>".$nrows."</td>";
                break;
            default:
                $this->content .= "<tr><td>".$sqlParameter."</td>";
                $this->content .= "<td bgcolor=eeeeee>".$nrows."</td>";
                break;	
            }       
    }
    
    
    function hilf2($color, $ortsname, $plz, $laengengrad, $breitengrad, $entfernung) {
        switch ($color) {
            case 0:
                $this->content .=  "<td>".$ortsname."</td>";
                $this->content .=  "<td bgcolor=dddddd>".$plz."</td>";
                $this->content .=  "<td>".$laengengrad."</td>";
                $this->content .=  "<td bgcolor=dddddd>".$breitengrad."</td>";
                $this->content .=  "<td>".$entfernung."</td></tr>";
                break;
            default:
                $this->content .=  "<td>".$ortsname."</td>";
                $this->content .=  "<td bgcolor=eeeeee>".$plz."</td>";
                $this->content .=  "<td>".$laengengrad."</td>";
                $this->content .=  "<td bgcolor=eeeeee>".$breitengrad."</td>";
                $this->content .=  "<td>".$entfernung."</td></tr>";
                break;
        }       
    }
    
    function hilf3($umkreis, $ortsname0) {
        $this->content .= "</table><br><a href=\"#top\">Top</a><br><br><table border=1 cellspacing=0 cellpadding=3><tr><td bgcolor=4499ff colspan=11><font size=+2><i><a name=\"umkreis\">Alle</a> H�ndler im Umkreis von $umkreis km von $ortsname0:</i></font><br></td></tr><tr><td><b>H�ndler</b></td><td><b>Homepage</b></td><td><b>Ort</b></td><td><b>PLZ</b></td><td><b>Entfernung (km)</b></td><td>x</td><td>y</td><td>Strasse</td><td>Tel</td><td>Fax</td><td>Gruppe</td></tr>";
    }
    
    function hilf4($color, $kunde, $homepage, $ortsname, $plz, $entfernung, $strasse, $tel, $fax, $gruppe, $tx, $ty) {
        $color=$color ^ 1;
        switch ($color) {
            case 0:
                $this->content .=  "<td bgcolor=eeeeee>".$kunde."</td>";
                $this->content .=  "<td bgcolor=dddddd>".$homepage."</td>";
                $this->content .=  "<td bgcolor=eeeeee>".$ortsname."</td>";
                $this->content .=  "<td bgcolor=dddddd>".$plz."</td>";
                $this->content .=  "<td bgcolor=eeeeee>".$entfernung."</td>";
                $this->content .=  "<td bgcolor=dddddd>".$tx."</td>";
                $this->content .=  "<td bgcolor=eeeeee>".$ty."</td>";
                $this->content .=  "<td bgcolor=dddddd>".$strasse."</td>";
                $this->content .=  "<td bgcolor=eeeeee>".$tel."</td>";
                $this->content .=  "<td bgcolor=dddddd>".$fax."</td>";
                $this->content .=  "<td bgcolor=eeeeee>".$gruppe."</td></tr>";
                break;
            default:
                $this->content .=  "<td>".$kunde."</td>";
                $this->content .=  "<td bgcolor=eeeeee>".$homepage."</td>";
                $this->content .=  "<td>".$ortsname."</td>";
                $this->content .=  "<td bgcolor=eeeeee>".$plz."</td>";
                $this->content .=  "<td>".$entfernung."</td>";
                $this->content .=  "<td bgcolor=eeeeee>".$tx."</td>";
                $this->content .=  "<td>".$ty."</td>";
                $this->content .=  "<td bgcolor=eeeeee>".$strasse."</td>";
                $this->content .=  "<td>".$tel."</td>";
                $this->content .=  "<td bgcolor=eeeeee>".$fax."</td>";
                $this->content .=  "<td>".$gruppe."</td></tr>";
                break;
        }       
    }
    
    function flashResult($result) {
        $this->content .=  "</table><br><a href=\"#top\">Top</a><br><br><table border=1 cellspacing=0 cellpadding=3 width=300><tr><td bgcolor=4499ff><font size=+2><i>R�ckgabewert an Flash</i></font></td></tr><tr><td>";
        $this->content .= $result;
        $this->content .= "</tr></td></table><br><a href=\"#top\">Top</a><br><br>";
    }
}
?>