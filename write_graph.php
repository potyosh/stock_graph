<?php
require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_line.php');

function write_graph($arg_input_csv, $arg_output_png)
{
	setlocale(LC_ALL,'ja_JP.UTF-8');
	$csv_pass = $arg_input_csv;
	$fp = fopen($csv_pass,"r");
	$fp_png_graph = fopen($arg_output_png, "r");
	
	$j = 0;
	while($data = fgetcsv($fp)){
	    for($i=0;$i<count($data);$i++){
	        if( $i == 6 ){
	            $ydata[$j] = $data[$i];
	            $j++;
	        }
	    }
	}
	
	$data = array();
	
	//Initialize file pointer
	fseek( $fp, 0, SEEK_SET);
	
	$j = 0;
	$temp_month = 0;
	while($data = fgetcsv($fp)){
	    for($i=0;$i<count($data);$i++){
	        if($i == 0){
				$date_splited = explode("/", $data[$i]);
 				if( $date_splited[1] != $temp_month ){
 					$xdata[$j] = $date_splited[1]."/".$date_splited[2];
					$temp_month = $date_splited[1];
 				}else{
 		            $xdata[$j] = "";
 				}
				$j++;
	        }
	    }
	}
	
	// Setup the graph
	$graph = new Graph(900,200);
	$graph->SetScale("textlin");
	
	$graph->xaxis->SetTickLabels($xdata);
	
	$graph->SetBox(false);
	
	$graph->yaxis->HideLine(false);
	$graph->yaxis->HideTicks(false,false);
	
	$graph->xgrid->Show();
	$graph->xgrid->SetLineStyle("solid");
	$graph->xgrid->SetColor('#e3e3e3');
	
	// Create the line
	$lineplot1 = new LinePlot($ydata);
	
	// Create the line
	$graph->Add($lineplot1);
	
	// Output the line
	$graph->Stroke($arg_output_png);
}
?>