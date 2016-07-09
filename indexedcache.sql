update tx_chhaendlersuche_plz  set KoordX = 6378.388 * COS(tx_chhaendlersuche_plz.breitengrad * PI() / 180) * COS(tx_chhaendlersuche_plz.laengengrad * PI() / 180);
update tx_chhaendlersuche_plz  set KoordY = 6378.388 * COS(tx_chhaendlersuche_plz.breitengrad * PI() / 180) * SIN(tx_chhaendlersuche_plz.laengengrad * PI() / 180);
update tx_chhaendlersuche_plz  set KoordZ = 6378.388 * SIN(tx_chhaendlersuche_plz.breitengrad * PI() / 180);

http://maps.google.de/maps?f=q&source=s_q&hl=de&geocode=&q=Deutschland&sll=50.092393,15.161133&sspn=15.469182,46.538086&ie=UTF8&hq=&hnear=Deutschland&z=6
http://maps.google.de/maps?f=q&source=s_q&hl=de&geocode=&q=belgien&sll=51.165691,10.451526&sspn=7.553528,23.269043&ie=UTF8&hq=&hnear=Belgien&z=8
http://maps.google.de/maps?f=q&source=s_q&hl=de&geocode=&q=niederlande&sll=50.503887,4.469936&sspn=1.91468,5.817261&ie=UTF8&hq=&hnear=Niederlande&ll=52.133488,5.289917&spn=1.847818,5.817261&z=8
http://maps.google.de/maps?f=q&source=s_q&hl=de&geocode=&q=%C3%B6sterreich&sll=52.133488,5.289917&sspn=1.847818,5.817261&ie=UTF8&hq=&hnear=%C3%96sterreich&z=7
http://maps.google.de/maps?f=q&source=s_q&hl=de&geocode=&q=schweiz&sll=47.516231,14.550072&sspn=4.066227,11.634521&ie=UTF8&hq=&hnear=Schweiz&z=8


SELECT coo.ortsname, coo.plz, coo.breitengrad, coo.laengengrad, coo.KoordX, coo.KoordY, coo.KoordZ, NAME, ZIPCODE, CITY, URL, STREET, PHONE, TELEFAX, NAMEALIAS, EMAIL, LINEDISC, CUSTCLASSIFICATIONID
FROM CUSTTABLE
INNER JOIN tx_chhaendlersuche_plz AS coo ON coo.plz = ZIPCODE
WHERE coo.KoordX BETWEEN '3890.375062918' AND '4090.375062918' AND
coo.KoordY BETWEEN '386.7047925408' AND '586.7047925408' AND
coo.KoordZ BETWEEN '4852.1569826394' AND '5052.1569826394'
AND LINEDISC
IN (
'CPI10', 'HA-AT-DE', 'HA-AT-DE15', 'HA-IT', 'HA-NL-BE', 'HA-NL-BE15', 'Hielscher'
)
AND CUSTCLASSIFICATIONID
IN (
'A', 'AT', 'B', 'C', 'HATVR', 'HATVRFI', 'HR', 'HRFI', 'P', 'SATVR', 'SATVRF'
)
AND COUNTRYREGIONID IN ('DE')
LIMIT 0 , 30




SELECT DISTINCT `LINEDISC` , `COUNTRYREGIONID` , `CUSTCLASSIFICATIONID`
FROM `CUSTTABLE`
WHERE COUNTRYREGIONID = "AT"
LIMIT 0 , 30

Österreich: siehe Deutschland
Belgien: 
LINEDISC: HA-AT-DE, HA-NL-BE15, HA-NL-BE
CUSTCLASSIFICATIONID: HR, B, HATVR, C, E
Niederlande:
LINEDISC: HA-NL-BE, HA-AT-DE
CUSTCLASSIFICATIONID: HR, B, E, HATVR
Schweiz:
LINEDISC: HA-AT-DE
CUSTCLASSIFICATIONID: CH

function haendlerAmStandort(&$item, $key) {
$result =''; $m = count($this->haendlerStandort);
$standort  = 's' . $m . '=' . count($item)-1 . '&';        
for ($i=0;$endi=count($item),$i<$endi;$i++) {        
	$tmp=array(); $standort .=  'b' . $m . "2$i=";
	for ($j=0;$endj=count($item[$i]),$j<$endj;$j++) {
		$tmp[]=utf8_encode(preg_replace("/&/","und",$item[$i][$j]));
	}
	$standort .= implode('|',$tmp);
	$result .= $i == 0 ? $standort : '&' .$standort; 
	$standort='';
}
$this->haendlerStandort[]= $result;
}

function flashResult(&$item, $key) {
$result=''; $m =count($this->kollisionResult);
$haendler = 'n' . $m . '=' . count($item)-1 . '&';        
for ($i=0;$endi=count($item),$i<$endi;$i++) {        
	$tmp=array(); $haendler .=  'a' . $m . "2$i=";
	for ($j=0;$endj=count($item[$i]),$j<$endj;$j++) {
		$tmp[] = utf8_encode(preg_replace("/&/","und",$item[$i][$j]));
	}
	$haendler .= implode('|',$tmp);
	$result .= $i == 0 ? $haendler : '&' .$haendler; 
	$haendler='';
}
$this->kollisionResult[]= $result;
}

function KreisKollision($iKreis1X,  $iKreis1Y,  $iKreis1Radius='15',  $iKreis2X,  $iKreis2Y,  $iKreis2Radius='15') {
$a = ($iKreis1X - $iKreis2X) * ($iKreis1X - $iKreis2X) + ($iKreis1Y - $iKreis2Y) * ($iKreis1Y - $iKreis2Y);
$b = ($iKreis1Radius + $iKreis2Radius) * ($iKreis1Radius + $iKreis2Radius);
$tmp = $a <= $b ? true : false;
return $tmp;
}



$n=0;    $string='';
	// Frankfurt am Main Geokoordinaten in RAD
$Breite1=0.87462; $Laenge1=0.15153;
	// Frankfurt am Main auf meiner Karte 
$x = 105; $y=292;
	// Referenzkarte in Pixel 
$karte_referenz = 545;
	// Breite von Deutschland in km 
$deutschland_breite = 640;
$pixel_referenz = $deutschland_breite/$karte_referenz;
	// Entfernung von Frankfurt am Main-Berlin nach der Zylindrische Abbildungen
$frankfurt_berlin_01 = 0.081782123748098;
	// Entfernung von Frankfurt am Main - Berlin auf der Referenzkarte(!) gemessen in Pixel
$frankfurt_berlin_02 = 247;
$keine_ahnung = $frankfurt_berlin_02*$pixel_referenz/$frankfurt_berlin_01;
	// Die Maske  in Pixel 
$meine_maske = 320;
$meine_pixel = $deutschland_breite/$meine_maske;
$scale = 100/100/$meine_maske*$karte_referenz;

$diff_x=$customer['laengengrad']-$Laenge1;
$diff_y=log(tan(pi()/4+$customer['breitengrad']/180*pi()/2))-log(tan(pi()/4+$Breite1/2));
$tx=$x+$diff_x*$keine_ahnung/$scale;
$ty=$y-$diff_y*$keine_ahnung/$scale;

$config = array ( 	'plz' => $customer['plz'],
				'name' => $customer['ortsname'],
				'x' => $tx,
				'y' => $ty
			      );                        
$this->standort = new config($config);

$this->buffer = array(); 
for ($i=0; $end=count($dealer),$i<$end; $i++) {
	$diff_x=$dealer[$i]['l']-$Laenge1;
	$diff_y=log(tan(pi()/4+$dealer[$i]['b']/2))-log(tan(pi()/4+$Breite1/2));
	$this->buffer[$i] = array(  
							$dealer[$i]['NAME'],
							$dealer[$i]['URL'],
							$dealer[$i]['STREET'],
							$dealer[$i]['ZIPCODE'],
							$dealer[$i]['entfernung'],     
							$dealer[$i]['STREET'],
							$dealer[$i]['PHONE'],
							$dealer[$i]['TELEFAX'],
							$dealer[$i]['dummy'],
							$dealer[$i]['NAMEALIAS'], 
							$dealer[$i]['dummy'],
							$dealer[$i]['EMAIL'],
							$tx=$x+$diff_x*$keine_ahnung/$scale,
							$ty=$y-$diff_y*$keine_ahnung/$scale,
							$kollision=array()
	);
}         

7157AD,REEKEN,{"name":"7157AD,REEKEN","Status":{"code":602,"request":"geocode"}}
KAPELLEN,15.6305432,47.6467209,0
HUISSIGNIES,3.7540580,50.5648264,0
HERNE,7.2192370,51.5385230,0
BRAKEL,7.8411624,51.4741645,0
ALPHENAANDENRIJN,{"name":"ALPHENAANDENRIJN","Status":{"code":602,"request":"geocode"}}
1394,NEDERHORSTDENBERG,{"name":"1394,NEDERHORSTDENBERG","Status":{"code":602,"request":"geocode"}}
5741PA,BEEKENDONK,{"name":"5741PA,BEEKENDONK","Status":{"code":602,"request":"geocode"}}
2231HP,RIJNSBURG-ZH,{"name":"2231HP,RIJNSBURG-ZH","Status":{"code":602,"request":"geocode"}}
4353RT,SEROOSKERKE(WALCHEREN),{"name":"4353RT,SEROOSKERKE(WALCHEREN)","Status":{"code":602,"request":"geocode"}}
4793RB,FYNAART,{"name":"4793RB,FYNAART","Status":{"code":602,"request":"geocode"}}
4243JT,NIEUWLAND,{"name":"4243JT,NIEUWLAND","Status":{"code":602,"request":"geocode"}}
4625AE,BERGENOPZOOM,{"name":"4625AE,BERGENOPZOOM","Status":{"code":602,"request":"geocode"}}
ALPHENAANDERIJN,{"name":"ALPHENAANDERIJN","Status":{"code":602,"request":"geocode"}}
9412,St.Margarethen,i.Lav.,{"name":"9412,St.Margarethen,i.Lav.","Status":{"code":602,"request":"geocode"}}
Middelfart,9.7283975,55.5069211,0
91166,Georgsgmuend,{"name":"91166,Georgsgmuend","Status":{"code":602,"request":"geocode"}}
99974,Reiser,GM,Unstruttal,{"name":"99974,Reiser,GM,Unstruttal","Status":{"code":602,"request":"geocode"}}
8209,Rebesgrün,{"name":"8209,Rebesgrün","Status":{"code":602,"request":"geocode"}}
4860,Welsau,{"name":"4860,Welsau","Status":{"code":602,"request":"geocode"}}
1737,Kurort,Hartha,{"name":"1737,Kurort,Hartha","Status":{"code":602,"request":"geocode"}}
22073,Socco,di,Fino,Mornasco,{"name":"22073,Socco,di,Fino,Mornasco","Status":{"code":602,"request":"geocode"}}


}|uhHcuodBafEzzrBg`^d`dCcgAhtcA|ln@f|i@jtUra{@mrLddwBvqi@tia@qvUpen@hfr@hdP~ro@pm{BnplBsa{@sy^peuCpfp@vzyEuhSzv_ChuGzv_CtopA|nr@`_h@edwB}qP}re@xeb@{rlCkuv@ouhExmbAgtcA|`w@}krKmke@{zrBotPoy{Duys@qa{@sky@umT}wd@i`]_pg@tia@{wDsuaBebm@sia@ymiAhtcAqpl@_wXkoi@|j_A

BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB


SELECT coo.ortsname,coo.plz,coo.breitengrad,coo.laengengrad,coo.KoordX,coo.KoordY,coo.KoordZ,NAME,ZIPCODE,CITY,URL,STREET,PHONE,TELEFAX,NAMEALIAS,EMAIL,LINEDISC,CUSTCLASSIFICATIONID, ACOS(SIN(0.88908231867879) * SIN(breitengrad/180*pi()) + COS(0.88908231867879) * COS(breitengrad/180*pi())) * COS(laengengrad/180*pi() - 0.12147337132242) * 6378.388 as entfernung
								FROM CUSTTABLE 
								INNER JOIN tx_chhaendlersuche_plz AS coo
								ON coo.plz = ZIPCODE
								WHERE 	coo.KoordX  BETWEEN 3889.564252333 AND 4089.564252333
											AND coo.KoordY BETWEEN 387.02365089736 AND  587.02365089736
											AND coo.KoordZ BETWEEN 4852.7788683241 AND 5052.7788683241
											AND COUNTRYREGIONID IN ('DE')
											
											
											SELECT coo.ortsname,coo.plz,coo.breitengrad,coo.laengengrad,coo.KoordX,coo.KoordY,coo.KoordZ,NAME,ZIPCODE,CITY,URL,STREET,PHONE,TELEFAX,NAMEALIAS,EMAIL,LINEDISC,CUSTCLASSIFICATIONID, ACOS(SIN(0.88908231867879) * SIN(breitengrad/180*pi()) + COS(0.88908231867879) * COS(breitengrad/180*pi())) * COS(laengengrad/180*pi() - 0.12147337132242) * 6378.388 entfernung
								FROM CUSTTABLE 
								INNER JOIN tx_chhaendlersuche_plz AS coo
								ON coo.plz = ZIPCODE
								WHERE 	COUNTRYREGIONID IN ('DE')
                                                                HAVING entfernung <= 100 
								
								
								
10.04.09 23:00