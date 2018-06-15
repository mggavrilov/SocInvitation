<?php
	function csvToArray($file) {
		$csv = array();
		$headings = array();
		
		$csvFile = fopen($file, "r");
		if($csvFile === false) {
			return $csv;
		}
		
		if(($csvRow = fgetcsv($csvFile)) !== false) {
			$headings = $csvRow;
		}
		
		while(($csvRow = fgetcsv($csvFile)) !== false) {
			$csvEntry = array();
			
			foreach($headings as $key => $value) {
				$csvEntry[$value] = $csvRow[$key];
			}
			
			$csv[] = $csvEntry;
		}
		
		return $csv;
	}
?>