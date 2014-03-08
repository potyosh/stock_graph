<?php
require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_line.php');

setlocale(LC_ALL,'ja_JP.UTF-8');
$csv_pass = $argv[1];
$fp = fopen($csv_pass,"r");

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
while($data = fgetcsv($fp)){
    for($i=0;$i<count($data);$i++){
        if($i == 0){
            $xdata[$j] = str_replace( "2013/", "", $data[$i] );
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

// Output line
$graph->Stroke();

?>