<?php
include_once('simple_html_dom.php');
function getcsvfromyahoo($yesterday, $sec_code, $output_pass)
{
    $tablenumber = 1; // Get the value of 1th table.
    // Get arguents.
    $sdate = localtime(strtotime($yesterday),TRUE);
    $c=$sdate['tm_year']+1900;
    $a=$sdate['tm_mon']+1;
    $b=$sdate['tm_mday'];
    $edate = localtime(strtotime($yesterday),TRUE);
    $f=$edate['tm_year']+1900;
    $d=$edate['tm_mon']+1;
    $e=$edate['tm_mday'];
    $stock = $sec_code;
    // Gget HTML
    //$url = "http://info.finance.yahoo.co.jp/history/?code=4665.T&sy=2010&sm=11&sd=1&ey=2010&em=11&ed=7&tm=d";
    $url = "http://info.finance.yahoo.co.jp/history/?code=$stock&sy=$c&sm=$a&sd=$b&ey=$f&em=$d&ed=$e&tm=d";
    $html = file_get_html($url);
    //echo $html;
    $filename = $output_pass.$sec_code.".csv";
    $fp = fopen("$filename","a");
    
    // Generate CSV
    $table = $html->find('table',$tablenumber);
	$i = 0;
    foreach( $table->find('tr') as $tr ){
        $arr = array();
        foreach( $tr->find('td,th') as $td ){
            $tmp = $td->plaintext;
            //$tmp = mb_convert_encoding($tmp,'UTF-8','EUC-JP');
            $tmp = str_replace(',','',$tmp);
            $arr[] = $tmp;
        }
        $str = implode(',',$arr);
		if($i != 0 ){
			//echo "$str\n";
            $str = str_replace("年", "/", $str);
            $str = str_replace("月", "/", $str);
            $str = str_replace("日", "", $str);
            fwrite($fp, "$str\n");
		}
		$i++;
    }
    fclose($fp);
}
?>

