<?php
include_once("const.php");

$sell_cmd = "php"." ".ROOT_PASS."command.php"." ".ROOT_PASS." ".INPUT_PASS_SELL." ".OUTPUT_PASS_SELL." "."SELL";
exec($sell_cmd);

$buy_cmd = "php"." ".ROOT_PASS."command.php"." ".ROOT_PASS." ".INPUT_PASS_BUY." ".OUTPUT_PASS_BUY." "."BUY";
exec($buy_cmd);

?>