/***************************************************************
*  Copyright notice
*  
*  (c) 2010 Chi Hoang (info@chihoang.de)
*  All rights reserved
*
***************************************************************/
var extPath = "typo3conf/ext/ch_haendlersuche/static/i/";
var Erdradius = 6378.388;
var cc = new Array();
cc["DE"]="Deutschland";
cc["AT"]="Austria";
cc["BE"]="Belgium";
cc["NL"]="Nederland";
cc["CH"]="Swiss";

function createRequestObject() {
	var xmlHttp = false;
		// Mozilla, Opera, Safari sowie Internet Explorer 7
	if (typeof(XMLHttpRequest) != 'undefined') {
		xmlHttp = new XMLHttpRequest();
	}
	if (!xmlHttp) {
		// Internet Explorer 6 und älter
		try {
			xmlHttp  = new ActiveXObject("Msxml2.XMLHTTP");
			} catch(e) {
				try {
					xmlHttp  = new ActiveXObject("Microsoft.XMLHTTP");
				} catch(e) {
				xmlHttp  = false;
			}
		}
	}
	return xmlHttp;
}
var backupField;
function sndReq(field, page, zoom, init) {
	map.closeInfoWindow();
	if (init == null) init = new Boolean(0);
	if (page == null) page=0;
	if (zoom == null) zoom = new Boolean(0);
	switch (field) {
		case "zipcode":
			backupField=field;
			value = document.haendlersuche.plz.value;
			test = new Boolean(validateZipCode(value));	
			break;
		case "name":
			backupField=field;
			value = document.haendlersuche.ort.value;
			test = new Boolean(validateNameCode(value));	
			break;
		case "dealer":
			backupField=field;
			value = document.haendlersuche.haendler.value;
			test = new Boolean(validateDealerCode(value));
			break;
		case "map":
			var latlng = map.getCenter();
			value = latlng.lat()+","+latlng.lng();
			break;
		case "dynamic":
			//~ alert(backupField);
			//~ alert(gQuery);
			switch (backupField) {
				case "zipcode":
					value = document.haendlersuche.plz.value;
					test = new Boolean(validateZipCode(value));	
					break;
				case "name":
					value = document.haendlersuche.ort.value;
					test = new Boolean(validateNameCode(value));	
					break;
				case "dealer":
					value = document.haendlersuche.haendler.value;
					test = new Boolean(validateDealerCode(value));
					break;
			}
			break;
	}
	if (test==false) {
		alert(errorMsg);
	} else if (zoom==false && gQuery != "dynamic" && field != null) {
		var google = createRequestObject();
		ajaxObj(value, google, field, page, init);
	} else {
		//~ alert(gQuery);
		gQuery = null;	
	}
}
var gmarkers=[], cmarkers=[], maxResults=5, maxPages=5, centerMarker, cflag;
function removeMarkers() {
	for (i = 0; i < gmarkers.length; i++) {
		map.removeOverlay(gmarkers[i][0]);
	}
	gmarkers=[];
	for (i = 0; i < cmarkers.length; i++) {
		map.removeOverlay(cmarkers[i]);
	}
	cmarkers=[];
	for (i = 0; i < drivePolyLine.length; i++) {
		map.removeOverlay(drivePolyLine[i]);
	}
	drivePolyLine=[];
	for (i = 0; i < circleMarkers.length; i++) {
		map.removeOverlay(circleMarkers[i]);
	}
	circleMarkers=[];
	for (i = 0; i < loadStrArray.length; i++) {
		map.removeOverlay(loadStrArray[i]);
	}
	loadStrArray=[];
	if (drivePolygon) {
		map.removeOverlay(drivePolygon);
	}
	if (loadStrCircle) {
		map.removeOverlay(loadStrCircle);
	}
}

function movingCircle() {
	//~ alert("test:"+centerMarker);
	if (centerMarker) {
		for (i = 0; i < cmarkers.length; i++) {
			map.removeOverlay(cmarkers[i]);
		}
		cmarkers=[];
		map.setCenter(centerMarker.getLatLng());
		map.removeOverlay(centerMarker);
		sndReq("map");
	} else {
		centerMarker = new GMarker(map.getCenter(),{draggable:true});
		GEvent.addListener(centerMarker,'dragend',drawCircle);
		GEvent.addListener(centerMarker, 'mouseover', function() {
			centerMarker.openInfoWindowHtml("Bitte verschieben Sie den Marker!");
		});
		map.addOverlay(centerMarker);
		init=new Boolean(1);
	}
	var bounds = new GLatLngBounds(); var circlePoints=[];
	with (Math) {
		var center = map.getCenter();
		var d = umkreis/Erdradius	// radians
		var lat1 = (PI/180)* center.lat(); // radians
		var lng1 = (PI/180)* center.lng(); // radians
		for (var a = 0 ; a < 361 ; a++ ) {
			var tc = (PI/180)*a;
			var y = asin(sin(lat1)*cos(d)+cos(lat1)*sin(d)*cos(tc));
			var dlng = atan2(sin(tc)*sin(d)*cos(lat1),cos(d)-sin(lat1)*sin(y));
			var x = ((lng1-dlng+PI) % (2*PI)) - PI ; // MOD function
			var point = new GLatLng(parseFloat(y*(180/PI)),parseFloat(x*(180/PI)));
			circlePoints.push(point);
			bounds.extend(point);
		}
		if (d < 1.5678565720686044) {
			circle = new GPolygon(circlePoints, '#000000', 2, 1, '#000000', 0.15);	
		}
		else {
			circle = new GPolygon(circlePoints, '#000000', 2, 1);	
		}
		if (centerMarker && init==false) {
			cmarkers.push(circle);
			map.setCenter(bounds.getCenter());
			map.setZoom(maxMapScale);
			map.addOverlay(circle);
		} else {
			cmarkers.push(circle);
			newZoom = map.getBoundsZoomLevel(bounds);
			newCenter = bounds.getCenter();
			map.setCenter(newCenter,parseInt(newZoom)-3); 
			//~ map.setZoom(maxMapScale);
			map.addOverlay(circle);
		}
	}
}
function umkreis(ursprungLat,  ursprungLng,  lat,  lng,  umkreis) {
	if (umkreis == null) umkreis = 15;
	with (Math) {
		entf=acos(SIN(ursprungLat /180*PI)*SIN(lat / 180*PI)+COS(ursprungLat /180*PI)*COS(lat / 180*PI)*COS(lng / 180*PI - ursprungLng / 180*PI))*Erdradius;
	}
	op = entf <= umkreis ? new Boolean(1) : new Boolean(0);
	return op;
}
function shortenAndShow(polyline) {
	var dist = 0, copyPoints = Array();
	for (var n = 0 ; n < polyline.getVertexCount()-1 ; n++ ) {
		dist += polyline.getVertex(n).distanceFrom(polyline.getVertex(n+1));
		copyPoints.push(polyline.getVertex(n));
	}
	var lastPoint = copyPoints[copyPoints.length-1];
	var newLine = new GPolyline(copyPoints, '#0000CD', 2, 1);
	drivePolyLine.push(newLine);
	map.addOverlay(newLine);
	addBorderMarker(lastPoint,dist);
}
function onDirectionsLoad() {
	var status = dirObj.getStatus();
	var bounds = dirObj.getBounds();
	var distance = parseInt(dirObj.getDistance().meters / 1000);
	var duration = parseFloat(dirObj.getDuration().seconds / 3600).toFixed(2);
	var polyline = dirObj.getPolyline();
	shortenAndShow(polyline);
}
function addBorderMarker(pt,d) {
	var str = 'Fahrtstrecke: ' + (d/1000).toFixed(2) + ' km';
	var marker = new GMarker(pt, {icon:redIcon8,title:str});
	circleMarkers.push(marker);
	map.addOverlay(marker);
}
function dirIntervall() {
	if (loadStrArray.length != 0 && standortLatlng != null) {
		loadStr = loadStrArray.shift();
		dirObj.load( "from: "+standortLatlng.lat()+","+standortLatlng.lng()+loadStr, {getPolyline:true, getSteps: true});
		setTimeout ( "dirIntervall()", 3000 );
	} else { 
		if (drivePolyPoints.length > 3) {
			if (drivePolygon) {
				map.removeOverlay(drivePolygon);
			}
			//~ drivePolygon = new GPolygon(drivePolyPoints, '#00ff00', 1, 1, '#00ff00', 0.4);	
			//~ drivePolygon = new GPolyline(drivePolyPoints, '#00ff00', 1, 1);	
			//~ map.addOverlay(drivePolygon);
		}
	}
}
var infoWindowArr=[], saddr;
function infoWindow(d, e, f, z) {
	if (z == null) z = new Boolean(1);
	infoWindowArr.push('<li class="innen">'+e+'</li><li class="spacer"></li><li class="title"><a href="http://'+d['URL']+'" target="_blank">'+d['NAME']+'</a><li class="aussen">'+d['STREET']+'<br>'+d['ZIPCODE']+'&nbsp;'+d['CITY']+'<br>Entfernung: '+d['e']+' km<br>');
	if (f==true) {
		infoWindowArr.push('Tel.: '+d['PHONE']+'<br>');
		infoWindowArr.push('Fax: ' +d['TELEFAX']+'<br>');
		infoWindowArr.push('E-Mail: <a href="mailto:'  + d['EMAIL'] + '">' +d['EMAIL'] + '</a><br>');
	}
	if (z==true) {
		infoWindowArr.push('<a href="http://maps.google.de/maps?f=d&hl='+d['COUNTRYREGIONID'].toLowerCase()+'&saddr='+saddr+'  '+cc[d['COUNTRYREGIONID']]+'&daddr='+d['STREET'].replace(/\d{1,4}/g,'' )+' ' +d['ZIPCODE']+' ' +d['CITY']+'" target="_blank">Routenplaner</a></li><li class="clr"><br class="clr" /></li>');
	}
}
var trefferWindowArr=[];
function trefferWindow(d, e, f, c, z) {
	if (z == null) z = new Boolean(1);
	trefferWindowArr.push('<li class="innen">'+e+'</li><li class="spacer"></li><li class="title"><a href="#" onClick="selectAddress(\''+c +'\',\''+e+'\');return false;">' +d['NAME']+'</a><li class="aussen">'+d['STREET']+'<br>'+d['ZIPCODE']+'&nbsp;'+d['CITY']+'<br>Entfernung: '+d['e']+' km<br>');
	if (f==true) {
		trefferWindowArr.push('Tel.: '+d['PHONE']+'<br>');
		trefferWindowArr.push('Fax: '+d['TELEFAX']+'<br>');
		trefferWindowArr.push('E-Mail: <a href="mailto:'  + d['EMAIL'] + '">' +d['EMAIL'] + '</a><br>');
	}
	if (d['URL']!='') {
		trefferWindowArr.push( 'Web: <a href="http://' +d['URL'] + '" target="_blank">' + d['URL'] + '</a><br>');
	}
	if (z==true) {
		trefferWindowArr.push('<a href="http://maps.google.de/maps?f=d&hl='+d['COUNTRYREGIONID'].toLowerCase()+'&saddr='+saddr+' '+cc[d['COUNTRYREGIONID']]+'&daddr='+d['STREET']+' ' +d['ZIPCODE']+' ' +d['CITY']+'" target="_blank">Routenplaner</a><br>');
		trefferWindowArr.push('<li class="clr"><br class="clr" /></li>');
	}
}

var jsonData, loadStrArray=[], drivePolyPoints=[], drivePolyLine=[], drivePolygon, loadStrCircle, circleMarkers=[], standortMarker, standortLatlng, newCenter, gTop=3;
function handleResponse(ajaxObj, kontaktdaten, umkreis, field, value, page, init) {
	document.getElementById('treffer').innerHTML = ""; jsonData = "";
	if (centerMarker) {
		map.removeOverlay(centerMarker); 
		centerMarker = null;
	}
	try { 
		jsonData = eval("(" + ajaxObj.responseText + ")");
		//~ alert(ajaxObj.responseText);
		//~ alert( typeof( jsonData ));
	} catch (e) {
		jsonData = ""; removeMarkers();  
		if (gDynamic == 0) {
			sndReq("dynamic"); 
			//~ alert("sndReq(dynamic)");
		} else if (gDynamic == 1) {
			++gDynamic;
			sndReq(backupField); 
			//~ alert(backupField);
		}
		document.getElementById('treffer').innerHTML = ajaxObj.responseText;
	}
	if ( typeof( jsonData ) == "object") {
		//~ alert(land);
		saddr=value; setTimeout ( "dirIntervall()", 3000 );
		loadStrArray=[];drivePolyPoints=[]; removeMarkers();  id=0; bounds = new GLatLngBounds(); c=0;
		if (jsonData['map'] != null) { 
			d=jsonData['map'][0][0]['r'];
			if (jsonData['standort'][0]['r']['NAME']) {
				id += jsonData['standort'].length+1;
			}
			for ( i=0; i < jsonData['map'].length; i++) {
				++c; p=0; z=1; infoWindowArr=[]; infoTabs=[]; d=jsonData['map'][i][0]['r']; toStr=d['lat']+','+d['lng'];
				loadStrArray.push('  to: '+toStr);
				for ( k=0; k < jsonData['map'][i].length; k++) { 
					++p; d=jsonData['map'][i][k]['r']; infoWindow(d, p, kontaktdaten);
				}
				infoTabs.push(new GInfoWindowTab((String.fromCharCode(parseInt(z)+64)), "<div id='infoWindow'><ul>" + infoWindowArr.join("") + "</ul></div>"));
				++z;
				infoTabs.push(new GInfoWindowTab((String.fromCharCode(parseInt(z)+64)), "Sie sehen "+p+" Treffer."));
				latlng = new GLatLng(jsonData['map'][i][0]['r']['lat'], jsonData['map'][i][0]['r']['lng']);
				bounds.extend(latlng);
				drivePolyPoints.push(latlng);
				newIcon = new GIcon();
				newIcon.image = k > 25 ? extPath+"ballon-red.png" : extPath+"iconr"+k+".png";
				newIcon.shadow = extPath+"shadow50.png";
				newIcon.iconSize = new GSize(20, 34);
				newIcon.shadowSize = new GSize(37, 34);
				newIcon.iconAnchor = new GPoint(12, 34);
				newIcon.infoWindowAnchor = new GPoint(12, 2);
				tooltip = p>1 ? null : html_entity_decode(jsonData['map'][i][0]['r']['NAME']);
				marker = createMarker(id, latlng, infoTabs,  newIcon, 1, 1, tooltip);
				++id; temp=[];
				temp.push(marker);
				temp.push(infoTabs);
				temp.push(jsonData['map'][i][0]['r']['lat']);
				temp.push(jsonData['map'][i][0]['r']['lng']);
				if (k > 25) {
					temp.push(extPath+"ballon-red.png");
					temp.push(extPath+"ballon-blue.png");
				} else {
					temp.push(extPath+"iconr"+k+".png");
					temp.push(extPath+"iconb"+k+".png");
				}
				temp.push(field);
				temp.push(jsonData['map'][i][0]['r']['NAME']);
				temp.push(jsonData['map'][i][0]['r']['ZIPCODE']);
				temp.push(jsonData['map'][i][0]['r']['CITY']);
				gmarkers.push(temp);
				map.addOverlay(marker);
			}
		}
		//~ alert(dump(jsonData['standort'],4));
		if (standortMarker) map.removeOverlay(standortMarker); 
		if (jsonData['standort'][0]['r']['NAME']) {
			id=0; p=0; infoWindowArr=[]; infoTabs=[]; 
			for ( k=0; k < jsonData['standort'].length; k++) { 
				p++; d=jsonData['standort'][k]['r']; infoWindow(d, p, kontaktdaten, 1);
			}
			z=1;
			infoTabs.push(new GInfoWindowTab((String.fromCharCode(parseInt(z)+64)), "<div id='infoWindow'><ul>" + infoWindowArr.join("") + "</ul></div>"));
			z++;
			infoTabs.push(new GInfoWindowTab((String.fromCharCode(parseInt(z)+64)), "Ihr Standort ist "+jsonData['standort'][0]['r']['plz']+" "+jsonData['standort'][0]['r']['ortsname']+".<br>An ihrem Standort  sehen sie "+p+" Treffer."));
			tooltip = p>1 ? null : html_entity_decode(jsonData['standort'][0]['r']['NAME']);
		} else {
			id=999; infoTabs=[];
			//~ infoTabs.push(new GInfoWindowTab((String.fromCharCode(parseInt(1)+64)), "<div id='infoWindow'>Ihr Standort ist "+jsonData['standort'][0]['r']['ortsname']+".</div>"));
			infoTabs.push(new GInfoWindowTab((String.fromCharCode(parseInt(1)+64)), "Ihr Standort ist "+jsonData['standort'][0]['r']['ortsname']+"."));
			tooltip = html_entity_decode("Ihr Standort");
		}
		standortLatlng = new GLatLng(jsonData['standort'][0]['r']['lat'], jsonData['standort'][0]['r']['lng']);
		bounds.extend(standortLatlng);
		newIcon = new GIcon();
		newIcon.image = extPath+"ballon-blue.png";
		newIcon.shadow = extPath+"shadow50.png";
		newIcon.iconSize = new GSize(20, 34);
		newIcon.shadowSize = new GSize(37, 34);
		newIcon.iconAnchor = new GPoint(12, 34);
		newIcon.infoWindowAnchor = new GPoint(12, 2);
		standortMarker = createMarker(id, standortLatlng, infoTabs,  newIcon, 1, 999, tooltip); id++; temp=[];
		if (jsonData['standort'][0]['r']['NAME']) {
			temp.push(standortMarker);
			temp.push(infoTabs);
			temp.push(standortLatlng.lat());
			temp.push(standortLatlng.lng());
			temp.push(extPath+"ballon-blue.png");
			temp.push(extPath+"ballon-red.png");
			temp.push(field);
			temp.push(jsonData['standort'][0]['r']['NAME']);
			temp.push(jsonData['standort'][0]['r']['ZIPCODE']);
			temp.push(jsonData['standort'][0]['r']['CITY']);
			gmarkers.unshift(temp);
		}
		map.addOverlay(standortMarker);
		if (init==false) {
			newZoom = map.getBoundsZoomLevel(bounds);
			newCenter = bounds.getCenter();
			map.setCenter(newCenter,parseInt(newZoom)-gTop);
			gTop=0;
		} else {
			map.setCenter(bounds.getCenter());
		}
		trefferWindowArr=[]; zaehler=0; cluster=0; 
		if (jsonData['standort'][0]['r']['NAME']) {
			for (var i=0; i < jsonData['standort'].length; i++) {
				++zaehler;
				trefferWindow(jsonData['standort'][i]['r'], zaehler, kontaktdaten, cluster, 1);
			}
			++cluster;
		}
		if (jsonData['map'] != null) { 
			for (var i=0; i < jsonData['map'].length; i++) {
				for (var k=0; k < jsonData['map'][i].length; k++) {
					++zaehler;
					trefferWindow(jsonData['map'][i][k]['r'], zaehler, kontaktdaten, cluster);
				}
				++cluster;
			}
		}
		result=[]; html=[];
		for (var i =0; i < trefferWindowArr.length; i++) {
			result.push(trefferWindowArr[i]);
		}
		document.getElementById('treffer').innerHTML = "<ul>"+result.join("")+"</ul>";
	} 
}
function importanceOrder (marker,b) {
	return GOverlay.getZIndex(marker.getPoint().lat()) + marker.importance*1000000;
}
function createMarker(id, point, infoTabs, icon, mouseover, importance, tooltip) {
	if (tooltip) {
		var marker = new GMarker(point, {title: tooltip, icon:icon, zIndexProcess:importanceOrder});
	} else {
		var marker = new GMarker(point, {icon:icon, zIndexProcess:importanceOrder});
	}
	marker.importance = importance;
	marker.id = id;
	GEvent.addListener(marker, 'click', function() {
		marker.openInfoWindowTabsHtml(infoTabs);
		map.panTo(point);
	});
	if (mouseover) {
		GEvent.addListener(marker, "mouseover", function() {
			for (i = 0; i < gmarkers.length; i++) {
				gmarkers[i][0].setImage(gmarkers[i][4]);
			}
			marker.setImage(gmarkers[marker.id][5]);
		});
		GEvent.addListener(marker, "mouseout", function() {
			marker.setImage(gmarkers[marker.id][4]);
	       });
       }
       GEvent.addListener(marker, 'infowindowclose', function(){
	       //~ alert(gmarkers[marker.id][2]);
		map.panTo(point);
	});
	return marker;
}
	// Selects a specific address item in the list
function selectAddress(id, p) {
	for (i = 0; i < gmarkers.length; i++) {
		gmarkers[i][0].setImage(gmarkers[i][4]);
	}
	if ( typeof( jsonData ) == "object") {
		z=0; tmp=[];
		if (jsonData['standort'][0]['r']['NAME']) {
			for ( i = 0; i < jsonData['standort'].length; i++) {
				if (++z == p) tmp=jsonData['standort'][i]['r'];
			}
		}
		if (jsonData['map'] != null) { 
			for ( i = 0; i < jsonData['map'].length; i++) {
				for ( k  = 0; k < jsonData['map'][i].length; k++) { 
					if (++z == p) tmp=jsonData['map'][i][k]['r'];
				}
			}
		}
			//~ alert(tmp['ZIPCODE']);
			//~ alert(gmarkers[id][6]);
		switch (gmarkers[id][6]) {
			case "zipcode":
				document.haendlersuche.plz.value = html_entity_decode(tmp['ZIPCODE']);
				break;
			case "name":
				document.haendlersuche.ort.value = html_entity_decode(tmp['CITY']);
				break;
			case "dealer":
				document.haendlersuche.haendler.value = html_entity_decode(tmp['NAME']);
				break;
			case "map":
				document.haendlersuche.haendler.value = html_entity_decode(tmp['NAME']);
				document.haendlersuche.plz.value = html_entity_decode(tmp['ZIPCODE']);
				document.haendlersuche.ort.value = html_entity_decode(tmp['CITY']);
				break;
		}
	}
	map.closeInfoWindow();
	map.panTo(new GLatLng(gmarkers[id][2], gmarkers[id][3]));
	gmarkers[id][0].setImage(gmarkers[id][5]);
	map.openInfoWindow(new GLatLng(gmarkers[id][2], gmarkers[id][3]), gmarkers[id][1], {pixelOffset: new GSize(0,-30)});
} 
function validateZipCode(value){
	var zipCodePattern = /^\d{4}$|^\d{5}$|^\d{4,5}.{1,2}$/;
	return zipCodePattern.test(value);
}
function validateNameCode(value){
	var zipCodePattern = /^[äüöÄÜÖßa-zA-Z-\.\/ ]+$/;
	return zipCodePattern.test(value);
}
function validateDealerCode(value){
	var zipCodePattern = /^[äüöÄÜÖßa-zA-Z- \.\/&]+$/;
	return zipCodePattern.test(value);
}
function html_entity_decode(str){
	/*Firefox (and IE if the string contains no elements surrounded by angle brackets )*/
try{
	var ta=document.createElement("textarea");
	ta.innerHTML=str;
	return ta.value;
}catch(e){};
	/*Internet Explorer*/
try{
	var d=document.createElement("div");
	d.innerHTML=str.replace(/</g,"&lt;").replace(/>/g,"&gt;");
	if(typeof d.innerText!="undefined")return d.innerText;/*Sadly this strips tags as well*/
	}catch(e){}
}
/**
*
*  UTF-8 data encode / decode
*  http://www.webtoolkit.info/
*
**/
var Utf8 = {
	// public method for url encoding
	encode : function (string) {
		string = string.replace(/\r\n/g,"\n");
		var utftext = "";
 
		for (var n = 0; n < string.length; n++) {
 
			var c = string.charCodeAt(n);
 
			if (c < 128) {
				utftext += String.fromCharCode(c);
			}
			else if((c > 127) && (c < 2048)) {
				utftext += String.fromCharCode((c >> 6) | 192);
				utftext += String.fromCharCode((c & 63) | 128);
			}
			else {
				utftext += String.fromCharCode((c >> 12) | 224);
				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
				utftext += String.fromCharCode((c & 63) | 128);
			}
 
		}
 
		return utftext;
	},
 
	// public method for url decoding
	decode : function (utftext) {
		var string = "";
		var i = 0;
		var c = c1 = c2 = 0;
 
		while ( i < utftext.length ) {
 
			c = utftext.charCodeAt(i);
 
			if (c < 128) {
				string += String.fromCharCode(c);
				i++;
			}
			else if((c > 191) && (c < 224)) {
				c2 = utftext.charCodeAt(i+1);
				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
				i += 2;
			}
			else {
				c2 = utftext.charCodeAt(i+1);
				c3 = utftext.charCodeAt(i+2);
				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
				i += 3;
			} 
		}
		return string;
	} 
}
function onDirectionsError() {
	GLog.write('Error: ' + directions.getStatus().code);
}
/**
 * Function : dump()
 * Arguments: The data - array,hash(associative array),object
 *    The level - OPTIONAL
 * Returns  : The textual representation of the array.
 * This function was inspired by the print_r function of PHP.
 * This will accept some data as the argument and return a
 * text that will be a more readable version of the
 * array/hash/object that is given.
 * Docs: http://www.openjs.com/scripts/others/dump_function_php_print_r.php
 */
function dump(arr,level) {
	var dumped_text = "";
	if(!level) level = 0;
	
	//The padding given at the beginning of the line.
	var level_padding = "";
	for(var j=0;j<level+1;j++) level_padding += "    ";
	
	if(typeof(arr) == 'object') { //Array/Hashes/Objects 
		for(var item in arr) {
			var value = arr[item];
			
			if(typeof(value) == 'object') { //If it is an array,
				dumped_text += level_padding + "'" + item + "' ...\n";
				dumped_text += dump(value,level+1);
			} else {
				dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
			}
		}
	} else { //Stings/Chars/Numbers etc.
		dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
	}
	return dumped_text;
}