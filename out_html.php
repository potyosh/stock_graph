<?php
$root_pass = $argv[1];
$output_pass = $argv[2];
$input_pass = $argv[3];
$fp_input = fopen($input_pass,"r");

print<<<EOF
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>list</title>
</head>
<body>
 <table border="0">
EOF;

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
    $fp_csv_data = fopen( $output_pass.$key.".csv","r");
    $data = array();
    while($data = fgetcsv($fp_csv_data)){
        for($j = 0; $j < count($data); $j++){
            if( $j == 6 ){
                $input_data[$key]['yesterday_value'] = $data[$j];
            }
        }
    }
    fclose($fp_csv_data);
    if( $argv[4] == "SELL"){
        $balance = $input_data[$key]['yesterday_value'] - $val['purchase_value'];
    }else{
        $balance = $input_data[$key]['yesterday_value'] - $val['desired_value'];
    }
    $input_data[$key]['balance'] = $balance*$val['bought_quantity'];
}

//Sort by balance
foreach($input_data as $key=>$value){
    $id[$key] = $value['balance'];
}

if( $argv[4] == "SELL" ){
    array_multisort($id ,SORT_DESC,$input_data);
}else{
    array_multisort($id ,SORT_ASC,$input_data);
}

foreach( $input_data as $key => $val){
    print "\n";
    print "<tr><td>".$val['company_name']."</td></tr>";
    print "\n";
    print "<tr>";
    print "<td width=\"150\"><img src=\"".$val['sec_code'].".png\"></td>";
    print "<td>";
    print "<table>";
    print "<tr><td>Purchase:".$val['purchase_value']."</td></tr>";
    print "<tr><td>Desired:".$val['desired_value']."</td></tr>";
    print "<tr><td>Yesterday:".$val['yesterday_value']."</td></tr>";
    print "<tr><td>Quantity:".$val['bought_quantity']."</td></tr>";
    print "<tr><td>Balance:".$val['balance']."</td></tr>";
    print "</table>";
    print "</td>";
    print "</tr>";
    print "\n";
}
 print<<<EOF
 </table>
</body>
</html>
EOF;
?>
