<?php

$_real_path=realpath('../');
$_real_path=GETENV('HOME').'/clonos';
$_real_path_php=$_real_path.'/php/new/';
$run_mode=php_sapi_name();

require_once($_real_path_php.'clonos.php');

$clonos=new ClonOS($_real_path,true);
if($run_mode=='cli')
{
	$clonos->config_generate();
}else{
	$clonos->start();
}
