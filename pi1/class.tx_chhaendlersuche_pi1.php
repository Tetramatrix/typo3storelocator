<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2010 Chi Hoang (info@chihoang.de)
*  All rights reserved
*
***************************************************************/

require_once(PATH_tslib."class.tslib_pibase.php");

class tx_chhaendlersuche_pi1 extends tslib_pibase {
    
	var $prefixId = "tx_chhaendlersuche_pi1";		// Same as class name
	var $scriptRelPath = "pi1/class.tx_chhaendlersuche_pi1.php";	// Path to this script relative to the extension dir.    
	var $scriptRelPathHtdocs = "typo3conf/ext/ch_haendlersuche/pi1/";    
	var $extKey = "ch_haendlersuche";	// The extension key.        
	
	var $county = array ( "DE" => array ( "LAND" => "Deutschland", "LATLNG" => "51.11215, 10.26285", "ZOOMLEVEL" => "3","POLYLINE" => "cbtfIqgtl@l|H|nr@fkShdPhf`@hrV??`gI}`l@t{x@~lE??hye@|vXfx`@~lE??uh@~nr@ffb@|zKn|Bgjp@joZ_wXbyc@|nr@doMtia@??rdP_wXrnY|flA??irIfjp@fgLria@h|V}zK`um@swg@|dc@hdPlzU~lEhyMrmT??`zM~hRfdK_wXjm[~lEr|PsmTboW_iRscV|d_@????puk@}|x@lb]ihCxmO|d_@vvLt_Nvcn@u_NpzTgxv@??v|h@~vXh_UkhCjcXsen@??r_b@smT{k@}xeAueC}`l@|tY}|x@b`Fsa{@lzHi`]jsDirVjle@rwg@??vv`@~d_@vty@|re@tn_At_NzqhA~lEjm@}byA_uG}|x@}~e@}|x@vsTixv@ntGezcBjgSsst@u{A_se@vm]qst@pgVumToff@g|i@?}byAkuGqihCe}[oeuCd}[qqnB{ng@smTs`dA|j_Auwl@qm{Bavi@}vXqjJ_or@syc@}zKmsbAdhjBs}dApihCerv@|vXkxqA|j_Aysb@el}AqiZgdwBwxTsa{@kib@qqnB{qg@}j_Ahdb@gxv@aqg@}nr@kud@?ycW~zKaagAfxv@op{A_{Kch{@tmTuzz@p}gAwvdAsen@ycnBria@k}Lr}gAc`t@h`]oqKpa{@ors@htcAz~CzzrB~oZnihCbjv@~nr@yoA|j_AlqFpen@wk{@}vXins@_wXh}a@rytA{}\dhjB?ren@kds@tqGcgUpa{@dlF|flAc}Czv_Cd`s@}j_A`ki@|j_Ardv@gxv@vyd@sia@zdNzbyA~xc@t_NhbRirVehNzxeA~la@??hnc@}fW?ig\~vXraD|trA","POLYLEVEL" => "BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB"), 
					      "AT" => array ( "LAND" =>  "Oesterreich", "LATLNG" => "47.74458835, 13.815395950000001", "ZOOMLEVEL" => "3"),
					      "BE" => array ( "LAND" => "Belgien", "LATLNG" => "50.4711025, 4.3994614", "ZOOMLEVEL" => "3"),
					      "NL" => array ( "LAND" => "Niederlanden", "LATLNG" => "52.04603155, 5.79477735", "ZOOMLEVEL" => "3", "POLYLINE" => "}|uhHcuodBafEzzrBg`^d`dCcgAhtcA|ln@f|i@jtUra{@mrLddwBvqi@tia@qvUpen@hfr@hdP~ro@pm{BnplBsa{@sy^peuCpfp@vzyEuhSzv_ChuGzv_CtopA|nr@`_h@edwB}qP}re@xeb@{rlCkuv@ouhExmbAgtcA|`w@}krKmke@{zrBotPoy{Duys@qa{@sky@umT}wd@i`]_pg@tia@{wDsuaBebm@sia@ymiAhtcAqpl@_wXkoi@|j_A", "POLYLEVEL" => "BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB"),
					      "CH" => array ( "LAND" => "Schweiz", "LATLNG" => "46.768420000000006, 7.9354548", "ZOOMLEVEL" => "3"));
	    
    	/**
	 * Returns a string containing a Javascript include of the xajax.js file
	 * along with a check to see if the file loaded after six seconds
	 * (typically called internally by xajax from get/printJavascript).
	 * 
	 * @param string the relative address of the folder where xajax has been
	 *               installed. For instance, if your PHP file is
	 *               "http://www.myserver.com/myfolder/mypage.php"
	 *               and xajax was installed in
	 *               "http://www.myserver.com/anotherfolder", then $sJsURI
	 *               should be set to "../anotherfolder". Defaults to assuming
	 *               xajax is in the same folder as your PHP file.
	 * @param string the relative folder/file pair of the xajax Javascript
	 *               engine located within the xajax installation folder.
	 *               Defaults to xajax_js/xajax.js.
	 * @return string
	 */
	function getJavascriptInclude($sJsURI="", $sJsFile=NULL)
	{
		if ($sJsURI != "" && substr($sJsURI, -1) != "/") $sJsURI .= "/";
		$html = "\t<script type=\"text/javascript\" charset=\"utf-8\" src=\"" . $sJsURI . $sJsFile . "\"></script>\n";
		return $html;
	}

	/**
	 * Returns a string containing a Javascript include of the xajax.js file
	 * along with a check to see if the file loaded after six seconds
	 * (typically called internally by xajax from get/printJavascript).
	 * 
	 * @param string the relative address of the folder where xajax has been
	 *               installed. For instance, if your PHP file is
	 *               "http://www.myserver.com/myfolder/mypage.php"
	 *               and xajax was installed in
	 *               "http://www.myserver.com/anotherfolder", then $sJsURI
	 *               should be set to "../anotherfolder". Defaults to assuming
	 *               xajax is in the same folder as your PHP file.
	 * @param string the relative folder/file pair of the xajax Javascript
	 *               engine located within the xajax installation folder.
	 *               Defaults to xajax_js/xajax.js.
	 * @return string
	 */
	function getCSSInclude($sCSSURI="", $sCSSFile=NULL)
	{
		if ($sCSSURI != "" && substr($sCSSURI, -1) != "/") $sCSSURI .= "/";
		$html = "\t<link rel=\"stylesheet\" href=\"" . $sCSSURI . $sCSSFile . "\"media=\"screen\">\n";
		return $html;
	}
	
	function main($content,$conf)	{
        
		$this->conf=$conf;		// Setting the TypoScript passed to this function in $this->conf                
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();		// Loading the LOCAL_LANG values
		
		$js = $this->getJavascriptInclude ( t3lib_extMgm::siteRelPath ( 'ch_haendlersuche' ) . 'static/', 'addclasskillclass.js' );
		$js .= $this->getJavascriptInclude ( t3lib_extMgm::siteRelPath ( 'ch_haendlersuche' ) . 'static/', 'attachevent.js' );
		$js .= $this->getJavascriptInclude ( t3lib_extMgm::siteRelPath ( 'ch_haendlersuche' ) . 'static/', 'addcss.js');
		$js .= $this->getJavascriptInclude ( t3lib_extMgm::siteRelPath ( 'ch_haendlersuche' ) . 'static/', 'tabtastic.js' );
		$js .= $this->getJavascriptInclude ( t3lib_extMgm::siteRelPath ( 'ch_haendlersuche' ) . 'static/', 'ajax.js' );
		$js .= $this->getCSSInclude ( t3lib_extMgm::siteRelPath ( 'ch_haendlersuche' ) . 'static/', 'formate.css' );
		$js .= $this->getCSSInclude ( t3lib_extMgm::siteRelPath ( 'ch_haendlersuche' ) . 'static/', 'tabtastic.css' );
		$GLOBALS ['TSFE']->additionalHeaderData [$this->prefixId] = $js;

			// get Extension config
		$_extConfig = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['ch_haendlersuche']);
		
			// get the template
		$this->templateCode = $this->cObj->fileResource($this->conf["templateFile"]);
		$template = $this->cObj->getSubpart($this->templateCode, '###DEALERLOOKUP###');
                
			// create the content by replacing the marker in the template
		$markerArray['###APIKEY###'] = $_extConfig['apikey'];
		$markerArray['###AJAXAPIKEY###'] =  $_extConfig['apikey'];
		$markerArray['###TYPO3SITEURL###'] = t3lib_div::getIndpEnv('TYPO3_SITE_URL');
		$markerArray['###SNDREQ###'] = t3lib_div::getIndpEnv('TYPO3_SITE_URL').$this->pi_linkTP_keepPIvars_url(array (),0);
		$markerArray['###FORM###'] = $this->pi_linkTP_keepPIvars_url(array (),0);
		$markerArray["###UMKREIS###"] = $this->conf['Umkreis'];
		$markerArray["###CUSTCLASSIFICATIONID###"] = str_replace(",",'/',str_replace(' ','',$this->conf['CUSTCLASSIFICATIONID']));
		$markerArray["###LINEDISC###"] = str_replace(",",'/',str_replace(' ','',$this->conf['LINEDISC']));
		$markerArray["###LAND###"] = $this->conf['Land'];
		$markerArray["###LATLNG###"] = $this->county[$this->conf['Land']]["LATLNG"];
		$markerArray["###ZOOMLEVEL###"] = $this->county[$this->conf['Land']]["ZOOMLEVEL"];
		$markerArray["###KONTAKTDATEN###"] = $this->conf['Kontaktdaten'];
		$markerArray["###DYNAMIC###"] = $this->conf['Dynamic'];
		$template = str_replace("###ZIPCODE###",utf8_encode($this->pi_getLL("ZIPCODE")), $template);
		$template = str_replace("###CITY###", utf8_encode($this->pi_getLL("CITY")), $template);
		$template = str_replace("###DEALER###", utf8_encode($this->pi_getLL("DEALER")), $template);
		$template = str_replace("###BOUNDS###", utf8_encode($this->pi_getLL("BOUNDS")), $template);
		$template = str_replace("###START###", utf8_encode($this->pi_getLL("START")), $template);
		$template = str_replace("###LIST###", utf8_encode($this->pi_getLL("LIST")), $template);
		$template = str_replace("###MARKER###", utf8_encode($this->pi_getLL("MARKER")), $template);
		$template = str_replace("###ERRORMSG###", utf8_encode($this->pi_getLL("ERRORMSG")), $template);
		$template = str_replace("###SEND###", utf8_encode($this->pi_getLL("SEND")), $template);
		return $this->pi_wrapInBaseClass($this->cObj->substituteMarkerArrayCached($template, array(), $markerArray , array()));
	}
}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/ch_haendlersuche/pi1/class.tx_chhaendlersuche_pi1.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/ch_haendlersuche/pi1/class.tx_chhaendlersuche_pi1.php"]);
}

?>
