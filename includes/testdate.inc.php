<?php

function test_date($control, $test){

	$control_y = substr($control, 0, 4);
	$control_m = substr($control, 5, 2);
	$control_d = substr($control, 8, 2);

	$test_y = substr($test, 0, 4);
	$test_m = substr($test, 5, 2);
	$test_d = substr($test, 8, 2);

	if($test_y >= $control_y){
		if($test_m > $control_m){
			return true;
		}else if($test_m == $control_m){
			if($test_d >= $control_d){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}else{
		return false;
	}
}