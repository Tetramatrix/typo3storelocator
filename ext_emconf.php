<?php

########################################################################
# Extension Manager/Repository config file for ext "ch_haendlersuche".
#
# Auto generated 30-04-2012 19:55
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Händlersuche',
	'description' => 'Händlersuche für Händler und Verarbeiter',
	'category' => 'plugin',
	'shy' => 0,
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'alpha',
	'internal' => 0,
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'author' => 'Chi Hoang',
	'author_email' => 'info@chihoang.de',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'version' => '0.0.5',
	'_md5_values_when_last_written' => 'a:148:{s:21:"ext_conf_template.txt";s:4:"8733";s:12:"ext_icon.gif";s:4:"bbd6";s:17:"ext_localconf.php";s:4:"6cd1";s:14:"ext_tables.php";s:4:"92bb";s:14:"ext_tables.sql";s:4:"6950";s:24:"ext_typoscript_setup.txt";s:4:"a8c6";s:16:"indexedcache.sql";s:4:"5986";s:16:"locallang_db.php";s:4:"b6f2";s:7:"tca.php";s:4:"b016";s:14:"doc/manual.sxw";s:4:"055a";s:22:"pi1/100702_hilbert.tgz";s:4:"dc15";s:12:"pi1/JSON.php";s:4:"df26";s:36:"pi1/class.tx_chhaendlersuche_pi1.php";s:4:"c773";s:14:"pi1/config.php";s:4:"0191";s:13:"pi1/debug.php";s:4:"5975";s:21:"pi1/haendlersuche.php";s:4:"c4fd";s:14:"pi1/helper.php";s:4:"e20d";s:15:"pi1/hilbert.csv";s:4:"59c7";s:15:"pi1/hilbert.php";s:4:"0349";s:17:"pi1/locallang.php";s:4:"2576";s:16:"pi1/response.php";s:4:"45fb";s:20:"pi1/test_hilbert.php";s:4:"b0fb";s:36:"pi1/geocoder/CUSTTABLE-beeline-2.csv";s:4:"be0f";s:26:"pi1/geocoder/CUSTTABLE.csv";s:4:"f21a";s:25:"pi1/geocoder/geocode-1.sh";s:4:"883d";s:25:"pi1/geocoder/geocode-2.sh";s:4:"0406";s:30:"pi1/geocoder/latlng-beelim.txt";s:4:"c412";s:27:"pi1/geocoder/latlng-tgb.txt";s:4:"f6a6";s:23:"pi1/geocoder/latlng.txt";s:4:"c412";s:19:"pi1/geocoder/target";s:4:"be0f";s:24:"pi1/sourceforge/JSON.php";s:4:"df26";s:23:"pi1/sourceforge/aoc.php";s:4:"b239";s:32:"pi1/sourceforge/branchnbound.php";s:4:"e89f";s:32:"pi1/sourceforge/contest-bab1.php";s:4:"14b4";s:32:"pi1/sourceforge/contest-bab2.php";s:4:"2840";s:27:"pi1/sourceforge/hilbert.php";s:4:"b43a";s:33:"pi1/sourceforge/hilbert.php.bak.1";s:4:"f35f";s:34:"pi1/sourceforge/hilbert.php.bak.11";s:4:"4f3f";s:33:"pi1/sourceforge/hilbert.php.bak.2";s:4:"b012";s:34:"pi1/sourceforge/hilbert.php.bak.21";s:4:"e22a";s:25:"pi1/sourceforge/iging.php";s:4:"8911";s:31:"pi1/sourceforge/latlng-10-2.txt";s:4:"18b2";s:29:"pi1/sourceforge/latlng-10.txt";s:4:"9301";s:29:"pi1/sourceforge/latlng-24.txt";s:4:"e7eb";s:33:"pi1/sourceforge/latlng-new-10.txt";s:4:"df7b";s:22:"pi1/sourceforge/m6.csv";s:4:"bb80";s:23:"pi1/sourceforge/sfc.php";s:4:"42ac";s:32:"pi1/sourceforge/test_hilbert.php";s:4:"f6ee";s:24:"pi1/sourceforge/tsp.html";s:4:"88c2";s:27:"static/addclasskillclass.js";s:4:"8cd6";s:16:"static/addcss.js";s:4:"f2fa";s:14:"static/ajax.js";s:4:"0c06";s:21:"static/attachevent.js";s:4:"532c";s:19:"static/default.html";s:4:"e7e2";s:18:"static/formate.css";s:4:"dada";s:18:"static/icon_tx.gif";s:4:"cdb8";s:20:"static/tabtastic.css";s:4:"1358";s:19:"static/tabtastic.js";s:4:"f9b6";s:19:"static/at/setup.txt";s:4:"5fab";s:19:"static/be/setup.txt";s:4:"2f29";s:19:"static/ch/setup.txt";s:4:"d53e";s:19:"static/de/setup.txt";s:4:"b8ce";s:24:"static/i/ballon-blue.png";s:4:"65df";s:25:"static/i/ballon-green.png";s:4:"36eb";s:23:"static/i/ballon-red.png";s:4:"ddec";s:26:"static/i/eckeobenlinks.gif";s:4:"923c";s:18:"static/i/iconb.png";s:4:"6d61";s:19:"static/i/iconb1.png";s:4:"9542";s:20:"static/i/iconb10.png";s:4:"80bc";s:20:"static/i/iconb11.png";s:4:"68e1";s:20:"static/i/iconb12.png";s:4:"246f";s:20:"static/i/iconb13.png";s:4:"9bfa";s:20:"static/i/iconb14.png";s:4:"8710";s:20:"static/i/iconb15.png";s:4:"8c65";s:20:"static/i/iconb16.png";s:4:"f137";s:20:"static/i/iconb17.png";s:4:"fb69";s:20:"static/i/iconb18.png";s:4:"cb99";s:20:"static/i/iconb19.png";s:4:"6669";s:19:"static/i/iconb2.png";s:4:"7188";s:20:"static/i/iconb20.png";s:4:"16fd";s:20:"static/i/iconb21.png";s:4:"a178";s:20:"static/i/iconb22.png";s:4:"2bd8";s:20:"static/i/iconb23.png";s:4:"bd0d";s:20:"static/i/iconb24.png";s:4:"52fd";s:20:"static/i/iconb25.png";s:4:"ab31";s:19:"static/i/iconb3.png";s:4:"b1a1";s:19:"static/i/iconb4.png";s:4:"b62d";s:19:"static/i/iconb5.png";s:4:"9779";s:19:"static/i/iconb6.png";s:4:"19d3";s:19:"static/i/iconb7.png";s:4:"8676";s:19:"static/i/iconb8.png";s:4:"5bd0";s:19:"static/i/iconb9.png";s:4:"62bf";s:18:"static/i/icong.png";s:4:"0690";s:19:"static/i/icong1.png";s:4:"8ea7";s:20:"static/i/icong10.png";s:4:"ebbe";s:20:"static/i/icong11.png";s:4:"a032";s:20:"static/i/icong12.png";s:4:"6ed8";s:20:"static/i/icong13.png";s:4:"d5a7";s:20:"static/i/icong14.png";s:4:"5803";s:20:"static/i/icong15.png";s:4:"5f14";s:20:"static/i/icong16.png";s:4:"a855";s:20:"static/i/icong17.png";s:4:"0f37";s:20:"static/i/icong18.png";s:4:"c623";s:20:"static/i/icong19.png";s:4:"6bdf";s:19:"static/i/icong2.png";s:4:"f9c1";s:20:"static/i/icong20.png";s:4:"46e2";s:20:"static/i/icong21.png";s:4:"97fa";s:20:"static/i/icong22.png";s:4:"1960";s:20:"static/i/icong23.png";s:4:"ea52";s:20:"static/i/icong24.png";s:4:"316b";s:20:"static/i/icong25.png";s:4:"b106";s:19:"static/i/icong3.png";s:4:"015a";s:19:"static/i/icong4.png";s:4:"c634";s:19:"static/i/icong5.png";s:4:"daa0";s:19:"static/i/icong6.png";s:4:"482f";s:19:"static/i/icong7.png";s:4:"5264";s:19:"static/i/icong8.png";s:4:"52c1";s:19:"static/i/icong9.png";s:4:"3fe6";s:18:"static/i/iconr.png";s:4:"b7fa";s:19:"static/i/iconr1.png";s:4:"48d1";s:20:"static/i/iconr10.png";s:4:"7893";s:20:"static/i/iconr11.png";s:4:"e74c";s:20:"static/i/iconr12.png";s:4:"b21e";s:20:"static/i/iconr13.png";s:4:"f495";s:20:"static/i/iconr14.png";s:4:"403c";s:20:"static/i/iconr15.png";s:4:"6870";s:20:"static/i/iconr16.png";s:4:"7b1f";s:20:"static/i/iconr17.png";s:4:"02fc";s:20:"static/i/iconr18.png";s:4:"8a47";s:20:"static/i/iconr19.png";s:4:"b34c";s:19:"static/i/iconr2.png";s:4:"5f05";s:20:"static/i/iconr20.png";s:4:"c0ce";s:20:"static/i/iconr21.png";s:4:"e06d";s:20:"static/i/iconr22.png";s:4:"ca2e";s:20:"static/i/iconr23.png";s:4:"5e7a";s:20:"static/i/iconr24.png";s:4:"11ef";s:20:"static/i/iconr25.png";s:4:"c3a8";s:19:"static/i/iconr3.png";s:4:"29c6";s:19:"static/i/iconr4.png";s:4:"3e56";s:19:"static/i/iconr5.png";s:4:"0df4";s:19:"static/i/iconr6.png";s:4:"7274";s:19:"static/i/iconr7.png";s:4:"3a3e";s:19:"static/i/iconr8.png";s:4:"8c07";s:19:"static/i/iconr9.png";s:4:"2752";s:14:"static/i/m.gif";s:4:"7c6d";s:24:"static/i/redSquare_8.png";s:4:"4cf4";s:21:"static/i/shadow50.png";s:4:"eff9";s:19:"static/nl/setup.txt";s:4:"a37e";}',
	'constraints' => 'Array',
	'suggests' => array(
	),
);

?>