<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2010 Chi Hoang (info@chihoang.de)
*  All rights reserved
*
***************************************************************/

class hilbert {
	
	var $hilbert_map = array ( 'a' => array (
									"0, 0" => array (0, 'd'),
									"0, 1" => array (1, 'a'), 
									"1, 0" => array (3, 'b'),
									"1, 1" => array (2, 'a')
							), 
							 'b' => array ( 
									 "0, 0" => array (2, 'b'), 
									 "0, 1" => array (1, 'b'), 
									 "1, 0" => array (3, 'a'),
									 "1, 1" => array (0, 'c')
								), 
							'c' => array ( 
									"0, 0" => array (2, 'c'),
									"0, 1" => array (3, 'd'),
									"1, 0" => array (1, 'c'),
									"1, 1" => array (0, 'b')
								), 
							'd' => array (
									"0, 0" => array (0, 'a'), 
									"0, 1" => array (3, 'c'), 
									"1, 0" => array (1, 'd'), 
									"1, 1" => array (2, 'd')
							),
				);

	var $rev_map = array (       'a' => array (
									"0, 0" => array (0, 'b'),
									"0, 1" => array (1, 'b'), 
									"1, 0" => array (3, 'd'),
									"1, 1" => array (2, 'd')
							), 
							 'b' => array ( 
									 "0, 0" => array (0, 'a'), 
									 "0, 1" => array (2, 'b'), 
									 "1, 0" => array (3, 'b'),
									 "1, 1" => array (1, 'c')
								), 
							'c' => array ( 
									"0, 0" => array (3, 'd'),
									"0, 1" => array (2, 'c'),
									"1, 0" => array (0, 'c'),
									"1, 1" => array (1, 'b')
								), 
							'd' => array (
									"0, 0" => array (3, 'c'), 
									"0, 1" => array (1, 'd'), 
									"1, 0" => array (0, 'd'), 
									"1, 1" => array (2, 'a')
							),
				);
	
	var $z_map = array       (  'a' => array (
									"0, 0" => array (0, 'a'),
									"0, 1" => array (2, 'a'), 
									"1, 0" => array (1, 'a'),
									"1, 1" => array (3, 'a')
							), 
				);
	
	var $hilbert3d_map = array (     'a' => array (
											"0, 0, 0" => array(0,'a'),
											"0, 0, 1" => array(1,'a'),
											"0, 1, 1" => array(2,'a'),
											"0, 1, 0" => array(3,'a'),
											"1, 1, 0" => array(4,'a'),
											"1, 1, 1" => array(5,'a'),
											"1, 0, 1" => array(6,'a'),
											"1, 0, 0" => array(7,'a'),
										),
								);		
	
	
	function point_to_quadtree($x, $y, $order=16) {
		$current_square = 'a' ;
		$position = 0; 
		foreach (range($order-1, 0, -1) as $i) { 
			$quad_x = $x & (1 << $i) ? 1 : 0;
			$quad_y = $y & (1 << $i) ? 1 : 0;
			list($quad_position, $current_square) = $this->hilbert_map[$current_square]["$quad_x, $quad_y"];
			$position .= $quad_position;
		}
		return $position;
	}
	
	function point_to_hilbert3D($x, $y, $z, $order=16) {
		$current_square = 'a' ;
		$position = 0; 
		foreach (range($order-1, 0, -1) as $i) { 
			$position <<= 2; 
			$quad_x = $x & (1 << $i) ? 1 : 0;
			$quad_y = $y & (1 << $i) ? 1 : 0;
			$quad_z = $z & (1 << $i) ? 1 : 0;
			list($quad_position, $current_square) = $this->hilbert3d_map[$current_square]["$quad_x, $quad_y, $quad_z"];
			$position |= $quad_position;
		}
		return $position;
	}
	
	function point_to_hilbert($x, $y, $order=16) {
		$current_square = 'a' ;
		$position = 0; 
		foreach (range($order-1, 0, -1) as $i) { 
			$position <<= 2; 
			$quad_x = $x & (1 << $i) ? 1 : 0;
			$quad_y = $y & (1 << $i) ? 1 : 0;
			list($quad_position, $current_square) = $this->hilbert_map[$current_square]["$quad_x, $quad_y"];
			$position |= $quad_position;
		}
		return $position;
	}
	
	function hilbert_to_point($hilbert, $order) {
		$current_square = 'a' ;
		$quad_x=$quad_y=$x=$y=0;
		for ($i = 0; $end = 2*$order, $i < $end; $i += 2) {
			$x <<= 1;  $y <<= 1;
			$quad_x = ($hilbert >> ($i+1)) & 1;      // Get bit i+1 of s. 
			$quad_y = ($hilbert >> $i) & 1;          // Get bit i of s.
			list($quad_position, $current_square) = $this->rev_map[$current_square]["$quad_x, $quad_y"];
			$x |= $quad_position & 2 ? 1 : 0;
			$y |= $quad_position & 1 ? 1 : 0;
		} 
		return "$x, $y";
	} 
	
	function point_to_z($x, $y, $order=16) {
		$current_square = 'a' ;
		$position = 0; 
		foreach (range($order-1, 0, -1) as $i) { 
			$position <<= 2; 
			$quad_x = $x & (1 << $i) ? 1 : 0;
			$quad_y = $y & (1 << $i) ? 1 : 0;
			list($quad_position, $current_square) = $this->z_map[$current_square]["$quad_x, $quad_y"];
			$position |= $quad_position;
		}
		return $position;
	}
	
	function test_pth() {
		foreach (range(3,0,-1) as $x) {
			foreach (range(3,0,-1) as $y) {
				$sort[] = $points["$x,$y"] = $this->point_to_hilbert($x, $y, 2);
			}
		}
		array_multisort($points, $sort);
		foreach ($points as $k => $v) {
			echo $k."\n";
		}
//		foreach ($sort as $k) {
//			echo $k."\n";
//		}
	}
	
	function test_pth3d() {
		foreach (range(7,0,-1) as $x) {
			foreach (range(7,0,-1) as $y) {
				foreach (range(7,0,-1) as $z) {
					$sort[] = $points["$x,$y,$z"] = $this->point_to_hilbert3D($x, $y, $z, 3);
				}
			}
		}
		array_multisort($points, $sort);
		foreach ($points as $k => $v) {
			echo $k."\n";
		}
	}
}
?>
