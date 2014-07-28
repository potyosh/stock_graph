<?php
    include_once('simple_html_dom.php');
    $tablenumber = 9; // Get the value of 10th table.
    // Get arguents.
    $sdate = localtime(strtotime($argv[1]),TRUE);
    $c=$sdate['tm_year']+1900;
    $a=$sdate['tm_mon']+1;
    $b=$sdate['tm_mday'];
    $edate = localtime(strtotime($argv[2]),TRUE);
    $f=$edate['tm_year']+1900;
    $d=$edate['tm_mon']+1;
    $e=$edate['tm_mday'];
    $stock = $argv[3];
    // Gget HTML
    // http://table.yahoo.co.jp/t?c=2010&a=11&b=1&f=2010&d=11&e=7&g=d&s=9631&y=0&z=&x=sb
    // http://info.finance.yahoo.co.jp/history/?code=4665.T&sy=2010&sm=11&sd=1&ey=2010&em=11&ed=7&tm=d
    //$url = "http://info.finance.yahoo.co.jp/history/?code=4665.T&sy=2010&sm=11&sd=1&ey=2010&em=11&ed=7&tm=d";
    $url = "http://table.yahoo.co.jp/t?c=$c&a=$a&b=$b&f=$f&d=$d&e=$e&g=d&s=$stock&y=0&z=&x=sb";
    $html = file_get_html($url);
    $filename = $argv[4];
    $fp = fopen("$filename","a");
    
    // Generate CSV
    $table = $html->find('table',$tablenumber);
	$i = 0;
    foreach( $table->find('tr') as $tr ){
        $arr = array();
        foreach( $tr->find('td,th') as $td ){
            $tmp = $td->plaintext;
            $tmp = mb_convert_encoding($tmp,'UTF-8','EUC-JP');
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
?>

