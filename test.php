<?php


$filename = "phpflow_data_export_".date('Ymd') . ".xls";
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");

$data = array( 
    array("NAME" => "John Doe", "EMAIL" => "john.doe@gmail.com", "GENDER" => "Male", "COUNTRY" => "United States"), 
    array("NAME" => "Gary Riley", "EMAIL" => "gary@hotmail.com", "GENDER" => "Male", "COUNTRY" => "United Kingdom"), 
    array("NAME" => "Edward Siu", "EMAIL" => "siu.edward@gmail.com", "GENDER" => "Male", "COUNTRY" => "Switzerland"), 
    array("NAME" => "Betty Simons", "EMAIL" => "simons@example.com", "GENDER" => "Female", "COUNTRY" => "Australia"), 
    array("NAME" => "Frances Lieberman", "EMAIL" => "lieberman@gmail.com", "GENDER" => "Female", "COUNTRY" => "United Kingdom") 
);

ExportFile($data);

exit();
     
function ExportFile($records) {
	$heading = false;
	if(!empty($records))
	  foreach($records as $row) {
	if(!$heading) {
	  // display field/column names as a first row
	  echo implode("\t", array_keys($row)) . "\n";
	  $heading = true;
	}
	echo implode("\t", array_values($row)) . "\n";
	  }
	exit;
}