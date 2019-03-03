<?php
function cutString($array, $start, $end=null, $startingOffset = 0, $offset=0){
	$numOfChars = 0;
	
	$startFrom = strpos($array, $start) + $startingOffset;
	if (!$end){
		return substr($array, $startFrom);
		 }
	if ($startFrom){
	$numOfChars = strpos($array, $end) - $startFrom + strlen($end) + $offset;
	} else{
		$numOfChars = 0;
	}
	return substr( $array, $startFrom, $numOfChars ) ;
}
?>