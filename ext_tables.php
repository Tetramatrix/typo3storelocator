<?php

t3lib_extMgm::addStaticFile($_EXTKEY,'static/de/','Deutschland');
t3lib_extMgm::addStaticFile($_EXTKEY,'static/be/','Belgien');
t3lib_extMgm::addStaticFile($_EXTKEY,'static/ch/','Schweiz');
t3lib_extMgm::addStaticFile($_EXTKEY,'static/at/','Österreich');
t3lib_extMgm::addStaticFile($_EXTKEY,'static/nl/','Niederlanden');


t3lib_div::loadTCA("tt_content");
$TCA["tt_content"]["types"]["list"]["subtypes_excludelist"][$_EXTKEY."_pi1"]="layout,select_key";
t3lib_extMgm::addPlugin(Array("LLL:EXT:ch_haendlersuche/locallang_db.php:tt_content.list_type_pi1", $_EXTKEY."_pi1"),"list_type");
?>