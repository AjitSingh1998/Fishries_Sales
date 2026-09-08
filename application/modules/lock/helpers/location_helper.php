<?php

function weekdays(){
	return array('monday','tuesday','wednesday','thursday','friday','saturday','sunday');
}


//$breakTimeArray is array of break times
//$avail_from_time is start time in unix timestamp
//$avail_to_time is to time in unix timestamp
function checkStartEndTime($breakTimeArray = array(), $avail_from_time = '', $avail_to_time = ''){
	for($i=0; $i<count($breakTimeArray); $i++){
		$break_from = strtotime($breakTimeArray[$i]['break_from']);
		$break_to 	= strtotime($breakTimeArray[$i]['break_to']);
		
		if((($break_from > $avail_from_time) && ($break_from <= $avail_to_time)) && (($break_to > $avail_from_time) && ($break_to <= $avail_to_time))){
			$isFormOK[] = 'VALID';
		}else{
			//$isFormOK[] = 'NOTVALID';
			return FALSE;
		}
	}
	return TRUE;
}

function checkBreakTimeInternally($breakTimeArray){
	for($j=0; $j <count($breakTimeArray); $j++){
		$break_from = strtotime($breakTimeArray[$j]['break_from']);
		$break_to 	= strtotime($breakTimeArray[$j]['break_to']);
		
		for($k=0; $k <count($breakTimeArray); $k++){
			$break_from_k 	= strtotime($breakTimeArray[$k]['break_from']);
			$break_to_k 	= strtotime($breakTimeArray[$k]['break_to']);
			if($j == $k){
				if($break_from < $break_to){
					$isBreakOk[] = 'VALID';
				}else{
					//$isBreakOk[] = 'NOTVALID';
					return FALSE;
				}
			}else{
				if((($break_from_k >= $break_from) && ($break_from_k <= $break_to)) && (($break_to_k >= $break_from) && ($break_to_k <= $break_to))){
					//$isBreakOk[] = 'NOTVALID';
					return FALSE;
				}else{
					$isBreakOk[] = 'VALID';
				}
			}
		}
	}
	return TRUE;
}