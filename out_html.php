<?php
function out_html($arg_output_path, $arg_input_path)
{
	$output_path = $arg_output_path;
	$input_path = $arg_input_path;
	$fp_input = fopen($input_path,"r");
	$fp_output_html = fopen($output_path."list.html", "w");
	
	fwrite($fp_output_html, "<html>");
	fwrite($fp_output_html, "<head>");
	fwrite($fp_output_html, "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />");
	fwrite($fp_output_html, "<title>list</title>");
	fwrite($fp_output_html, "</head>");
	fwrite($fp_output_html, "<body>");
	fwrite($fp_output_html, "<table border=\"0\">");
		
	$data = array();
	$input_data = array();
		
	while($data = fgetcsv($fp_input)){
	    for($i=0;$i<count($data);$i++){
	        if( $i == 0 ){
	            $input_data[$data[1]]['company_name'] = $data[$i];
	        }else if( $i == 1 ){
	            $input_data[$data[1]]['sec_code'] = $data[$i];
	        }else if( $i == 2 ){
	            $input_data[$data[1]]['purchase_value'] = $data[$i];
	        }else if( $i == 3 ){
	            $input_data[$data[1]]['desired_value'] = $data[$i];
	        }else if( $i == 4 ){
	            $input_data[$data[1]]['bought_quantity'] = $data[$i];
	        }
	    }
	}
		
	//Calculate balance.
	foreach( $input_data as $key => $val){
	    $fp_csv_data = fopen( $output_path.$key.".csv","r");
	    $data = array();
	    while($data = fgetcsv($fp_csv_data)){
	        for($j = 0; $j < count($data); $j++){
	            if( $j == 6 ){
	                $input_data[$key]['yesterday_value'] = $data[$j];
	            }
	        }
	    }
	    fclose($fp_csv_data);
//if you want separate output of SELL and BUY	    
// 	    if( $argv[4] == "SELL"){
// 	        $balance = $input_data[$key]['yesterday_value'] - $val['purchase_value'];
// 	    }else{
// 	        $balance = $input_data[$key]['yesterday_value'] - $val['desired_value'];
// 	    }
	    $balance = $input_data[$key]['yesterday_value'] - $val['desired_value'];
	    $input_data[$key]['balance'] = $balance*$val['bought_quantity'];
	}
		
	//Sort by balance
	foreach($input_data as $key=>$value){
	    $id[$key] = $value['balance'];
	}

//if you want separate output of SELL and BUY
// 	if( $argv[4] == "SELL" ){
// 	    array_multisort($id ,SORT_DESC,$input_data);
// 	}else{
// 	    array_multisort($id ,SORT_ASC,$input_data);
// 	}
	array_multisort($id ,SORT_ASC,$input_data);
	
	foreach( $input_data as $key => $val){
	    fwrite($fp_output_html, "\n");
	    fwrite($fp_output_html, "<tr><td>".$val['company_name']."</td></tr>");
	    fwrite($fp_output_html, "\n");
	    fwrite($fp_output_html, "<tr>");
	    fwrite($fp_output_html, "<td width=\"150\"><img src=\"".$val['sec_code'].".png\"></td>");
	    fwrite($fp_output_html, "<td>");
	    fwrite($fp_output_html, "<table>");
	    fwrite($fp_output_html, "<tr><td>Purchase:".$val['purchase_value']."</td></tr>");
	    fwrite($fp_output_html, "<tr><td>Desired:".$val['desired_value']."</td></tr>");
	    fwrite($fp_output_html, "<tr><td>Yesterday:".$val['yesterday_value']."</td></tr>");
	    fwrite($fp_output_html, "<tr><td>Quantity:".$val['bought_quantity']."</td></tr>");
	    fwrite($fp_output_html, "<tr><td>Balance:".$val['balance']."</td></tr>");
	    fwrite($fp_output_html, "</table>");
	    fwrite($fp_output_html, "</td>");
	    fwrite($fp_output_html, "</tr>");
	    fwrite($fp_output_html, "\n");
	}
	
	fwrite($fp_output_html, "</table>");
	fwrite($fp_output_html, "</body>");
	fwrite($fp_output_html, "</html>");

}
?>
