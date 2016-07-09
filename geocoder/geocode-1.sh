#!/bin/bash

#***************************************************************
#  Copyright notice
#  
#  (c) 2010 Chi Hoang (info@chihoang.de)
#  All rights reserved
#
#  This script is part of the TYPO3 project. The TYPO3 project is 
#  free software; you can redistribute it and/or modify
#  it under the terms of the GNU General Public License as published by
#  the Free Software Foundation; either version 2 of the License, or
#  (at your option) any later version.
# 
#  The GNU General Public License can be found at
#  http://www.gnu.org/copyleft/gpl.html.
# 
#  This script is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU General Public License for more details.
#
#  This copyright notice MUST APPEAR in all copies of the script!
#***************************************************************/
#
# Target File Format: ZIPCODE CITY COUNTY
# For example: 9420 Aaigem Belgium
#		      8511 Aalbeke Belgium	
#		      9300 Aalst Belgium	
#

exec 3<target

while read file <&3
do
 replace=`echo $file | sed -r -e 's/ /,/g'` 
 location=`wget -q -O - "http://maps.google.com/maps/geo?q=$replace"`
 geo=`echo $location | sed -r -e 's/(.+?"coordinates": \[ )(.+?)\] } } \] }/\2/g'`
 cache=`echo $replace,$geo | sed -r -e 's/ //g'`
 echo $cache >> latlng.txt
 sleep 1

done

