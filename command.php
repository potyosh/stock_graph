<?php
$yesterday = date('Y-m-d', strtotime("now"));
$root_pass = $argv[1];
$input_pass = $argv[2];
$output_pass = $argv[3];

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

//exec("php getcsvfromyahoo.php 2012-12-17 2012-12-17 7261.T data.csv");
for($i=0;$i<count($sec_codes);$i++){
    $csv_cmd = "php ".$root_pass."getcsvfromyahoo.php";
	$csv_cmd = $csv_cmd.' '.$yesterday;
	$csv_cmd = $csv_cmd.' '.$yesterday;
	$csv_cmd = $csv_cmd.' '.$sec_codes[$i];
	$csv_cmd = $csv_cmd.' '.$output_pass.$sec_codes[$i].".csv";
	
	exec($csv_cmd);
	
    $graph_cmd = "php ".$root_pass."write_graph.php ";
	$graph_cmd = $graph_cmd.' '.$output_pass.$sec_codes[$i].".csv";
	$graph_cmd = $graph_cmd.' '." > ";
	$graph_cmd = $graph_cmd.' '.$output_pass.$sec_codes[$i].".png";
	
	exec($graph_cmd);
 
	//exec("php ~/Dropbox/Stock_TEST/getcsvfromyahoo.php $yesterday $yesterday 7261.T ~/Dropbox/Stock_TEST/7261.T.csv");
	//exec("php ~/Dropbox/Stock_TEST/write_graph.php 7261.T.csv > ~/Dropbox/Stock_TEST/7261.T.png");
}

$html_cmd = "php ".$root_pass."out_html.php"." ".$root_pass." ".$output_pass." ".$input_pass." ".$argv[4]." ".">"." ".$output_pass."list.html";
exec($html_cmd);

?>