<?php
trait tSetup {
	
	public function setup()
	{
		$arr=($_SERVER['argv']);
		if($arr[0]=='_setup.php')
		{
			//print_r($arr);
		}
		//echo PHP_EOL.PHP_EOL;
		
		$db=new Db('clonos');
		if(!$db->isConnected())	return array('error'=>true,'error_message'=>'db connection lost!');
		
		$tableName='menu';
		$query="SELECT name FROM sqlite_master WHERE type='table' AND name='$tableName'";
		$res=$db->selectOne($query,[]);
		if(empty($res))
		{
			$filename=self::$realpath_php.'clonos_menu.dump.sql';
			if(file_exists($filename))
			{
				$sql=file_get_contents($filename);
				print_r($db->exec($sql));
			}else{
				echo 'File: "'.$filename.'" no found.';
			}
		}
		echo PHP_EOL;	//.PHP_EOL
	}
	
	public function config_generate()
	{	// php <твой скрипт> /zmirror/jails/cmd.subr
		$arr=($_SERVER['argv']);
		if($arr[0]=='_syscfg.php')
		{
			//print_r($arr);
			//echo PHP_EOL.PHP_EOL;
		}
		
		$arr_cmd=[
			'cmd'=>'CBSD_CMD',
			'sudo'=>'SUDO_CMD'
		];
		
		//$cfile='/zmirror/jails/cmd.subr';
		if(!isset($arr[1])) {
			echo 'Не указан путь к cmd.subr!'.PHP_EOL;
			exit;
		}
		$cfile=$arr[1];
		if(file_exists($cfile))
		{
			$buf='<?php'.PHP_EOL.'Clonos::$sys=(object)[];'.PHP_EOL;
			$cfg=file_get_contents($cfile);
			foreach($arr_cmd as $key=>$val)
			{
				$pat='#'.$val.'="(.+)"#';
				$bool=preg_match($pat,$cfg,$res);
				if($bool)
				{
					//var_dump($res);
					//echo PHP_EOL.PHP_EOL;
					$buf.="Clonos::\$sys->{$key}='{$res[1]}';".PHP_EOL;
				}
			}
			
			file_put_contents('_cmd_cfg.php',$buf);
			
			//include('_test_cfg.php');
			//var_dump(Clonos::$sys);
		}
		
	}
	
	public function cacheVMProfiles()
	{
		if(file_exists(self::filenameCapabilities)){
			$jsonTxt=file_get_contents(self::filenameCapabilities);
			try {
				$json=json_decode($jsonTxt,true);
				foreach($json as $key=>&$val)
				{
					if(!$val['available']){ unset($json[$key]); }
					else{
						unset($val['info']);
						$res=CBSD::run(
							'get-profiles src=iso emulator=%s json=1',[$val['name']]
						);
						$val['profiles']=$res['message'];
					}
				}
				
				foreach($json as $key=>$val)
				{
					$json[$val['name']]=$val;
					unset($json[$key]);
				}
				
			}catch(Exception $e){
				$error_message=$e->getMessage();
			}
			
			//file_put_contents(self::$realpath_php.'_profiles_cfg.php',json_encode($json));
		}
	}
}