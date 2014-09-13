<?php
include_once("getcsvfromyahoo.php");
include_once("write_graph.php");
include_once("out_html.php");

function command($root_pass, $input_pass, $output_pass)
{
	$yesterday = date('Y-m-d', strtotime("now"));
	
	$fp_input = fopen($input_pass,"r");
	
	$j = 0;
	while($data = fgetcsv($fp_input)){
	    for($i=0;$i<count($data);$i++){
	        if( $i == 1 ){
	            $sec_codes[$j] = $data[$i];
				$j++;
	        }
	    }
	}
	
	for($i=0;$i<count($sec_codes);$i++){
		getcsvfromyahoo($yesterday, $sec_codes[$i], $output_pass);		
	 	write_graph($output_pass.$sec_codes[$i].".csv", $output_pass.$sec_codes[$i].".png");
	}
	
	out_html($output_pass, $input_pass);
}
?>