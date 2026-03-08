<?php

//require_once("cbsd.php");

class Config extends ClonOS
{
	private $_workdir='';

	/* Список языков, используемых в проекте */
	public static $languages=array(
		'en'=>'English',
		'ru'=>'Russian',
		'es'=>'Spanish',
	);

	public $os_types_names=array(
		'netbsd'=>'NetBSD',
		'dflybsd'=>'DragonflyBSD',
		'linux'=>'Linux',
		'other'=>'Other',
		'freebsd'=>'FreeBSD',
		'openbsd'=>'OpenBSD',
		'windows'=>'Windows',
	);

	public static $other_titles=array(
		'settings'=>'CBSD Settings',
		'users'=>'CBSD Users',
	);

	/* Меню проекта */
	/* Так же можно использовать подменю (в menu.php есть пример) */
/*
	public static $menu=array(
		'overview'=>array(
			'name'=>'Overview',
			'title'=>'Summary Overview',	// заголовки лучше делать более полными, чем просто повторение пункта меню
			'icon'=>'icon-chart-bar',
		),
		'jailscontainers'=>array(
			'name'=>'Jails containers',
			'title'=>'Jails containers control panel',
			'icon'=>'icon-server',
		),

		'instance_jail'=>array(
			'name'=>'Template for jail',
			'title'=>'Helpers and wizard for containers',
			'icon'=>'icon-cubes',
		),

		'bhyvevms'=>array(
			'name'=>'Bhyve VMs',
			'title'=>'Virtual machine control panel',
			'icon'=>'icon-th-list',
		),
		/-*
		'nodes'=>array(
			'name'=>'Nodes',
			'title'=>'Nodes control panel',
			'icon'=>'icon-buffer',
		),
		*-/
		'vm_packages'=>array(
			'name'=>'VM Packages',
			'title'=>'Manage VM Packages group',
			'icon'=>'icon-cubes',
		),

		'k8s'=>array(
			'name'=>'K8S clusters',
			'title'=>'Manage K8S clusters',
			'icon'=>'icon-cubes',
		),

		'vpnet'=>array(
			'name'=>'Virtual Private Network',
			'title'=>'Manage for virtual private networks',
			'icon'=>'icon-plug',
		),

		'authkey'=>array(
			'name'=>'Authkeys',
			'title'=>'Manage for SSH auth key',
			'icon'=>'icon-key',
		),

		'media'=>array(
			'name'=>'Storage Media',
			'title'=>'Virtual Media Manager',
			'icon'=>'icon-inbox',
		),

		'imported'=>array(
			'name'=>'Imported images',
			'title'=>'Imported images',
			'icon'=>'icon-upload',
		),
		/-*
		'repo'=>array(
			'name'=>'Repository',
			'title'=>'Remote repository',
			'icon'=>'icon-globe',
		),
		*-/
		'bases'=>array(
			'name'=>'FreeBSD Bases',
			'title'=>'FreeBSD bases manager',
			'icon'=>'icon-database',
		),

		'sources'=>array(
			'name'=>'FreeBSD Sources',
			'title'=>'FreeBSD sources manager',
			'icon'=>'icon-edit',
		),
		/-*
		'jail_marketplace'=>array(
			'name'=>'Jail Marketplace',
			'title'=>'Public remote containers marketplace',
			'icon'=>'icon-flag',
		),
		*-//-*
		'bhyve_marketplace'=>array(
			'name'=>'Bhyve Marketplace',
			'title'=>'Public remote virtual machine marketplace',
			'icon'=>'icon-flag-checkered',
		),
		*-/
		'tasklog'=>array(
			'name'=>'TaskLog',
			'title'=>'System task log',
			'icon'=>'icon-list-alt',
		),

		'sqlite'=>array(
			'name'=>'SQLite admin',
			'title'=>'SQLite admin interface',
			'icon'=>'icon-wpforms',
		)
	);
*/
	public $os_types=array(
		array(
			'os'=>'DragonflyBSD',
			'items'=>array(
				array('name'=>'DragonflyBSD 4','type'=>'dflybsd',
						'profile'=>'x86-4','obtain'=>false),
			),
		),
		array(
			'os'=>'FreeBSD',
			'items'=>array(
				array('name'=>'FreeBSD 11.0-RELEASE','type'=>'freebsd',
						'profile'=>'FreeBSD-x64-11.0','obtain'=>true),
				array('name'=>'FreeBSD pfSense 2.4.0-DEVELOP','type'=>'freebsd',
						'profile'=>'pfSense-2-LATEST-amd64','obtain'=>false),
				array('name'=>'FreeBSD OPNsense-16.7','type'=>'freebsd',
						'profile'=>'OPNsense-16-RELEASE-amd64','obtain'=>false),
			),
		),
		array(
			'os'=>'Linux',
			'items'=>array(
				array('name'=>'Linux Arch 2016','type'=>'linux',
						'profile'=>'ArchLinux-x86-2016','obtain'=>false),
				array('name'=>'Linux CentOS 7','type'=>'linux',
						'profile'=>'CentOS-7-x86_64','obtain'=>false),
				array('name'=>'Linux Debian 8','type'=>'linux',
						'profile'=>'Debian-x86-8','obtain'=>false),
				array('name'=>'Linux Open Suse 42','type'=>'linux',
						'profile'=>'opensuse-x86-42','obtain'=>false),
				array('name'=>'Linux Ubuntu 16.04','type'=>'linux',
						'profile'=>'ubuntuserver-x86-16.04','obtain'=>true),
				array('name'=>'Linux Ubuntu 17.04','type'=>'linux',
						'profile'=>'ubuntuserver-x86-17.04','obtain'=>true),
			),
		),
		array(
			'os'=>'Windows',
			'items'=>array(
				array('name'=>'Windows 10','type'=>'windows',
						'profile'=>'10_86x_64x','obtain'=>false),
			),
		)
	);

	public $os_types_obtain=array();
	public $os_interfaces=array();

	function __construct(){
		
//		$this->_workdir=getenv('WORKDIR');
		$vm_profile_list_file=self::$workdir.'/tmp/bhyve-vm.json';
		$cloud_profile_list_file=self::$workdir.'/tmp/bhyve-cloud.json';
		$interface_list_file=self::$workdir.'/tmp/interfaces.json';

		/// check for file/cache first
		// vm profile (ISO)
		$vm_profile_list_file_size=0;
		if(file_exists($vm_profile_list_file)) {
			$vm_profile_list_file_size=filesize($vm_profile_list_file);
			if( $vm_profile_list_file_size < 16 ) {
				// probably empty, renew needed
				$vm_profile_list_file_size=0;
			}
		}

		// cloud profile
		$cloud_profile_list_file_size=0;
		// check for file/cache first
		if(file_exists($cloud_profile_list_file)) {
			$cloud_profile_list_file_size=filesize($cloud_profile_list_file);
			if( $cloud_profile_list_file_size < 16 ) {
				// probably empty, renew needed
				$cloud_profile_list_file_size=0;
			}
		}

		// interface profile
		$interface_list_file_size=0;
		// check for file/cache first
		if(file_exists($interface_list_file)) {
			$interface_list_file_size=filesize($interface_list_file);
			if( $interface_list_file_size < 4 ) {
				// probably empty, renew needed
				$interface_list_file_size=0;
			}
		}

		/// load vm profile list
		// vm profile (ISO)
		if ($vm_profile_list_file_size > 0 ) {
			ClonOS::syslog("config.php: (cached) found vm_profile cache file/size: $vm_profile_list_file exist/$vm_profile_list_file_size");
			$res['message']=file_get_contents($vm_profile_list_file);
//echo $res['message'];exit;
			$this->os_types=$this->create_bhyve_profiles($res);
		} else {
			ClonOS::syslog("config.php: vm_profile cache file not found: $vm_profile_list_file");
			$res=CBSD::run('get_bhyve_profiles src=vm clonos=1', array());
			if($res['retval']==0){
				$this->os_types=$this->create_bhyve_profiles($res);
			}
		}

		// cloud profile
		if ($cloud_profile_list_file_size > 0 ) {
			ClonOS::syslog("config.php: (cached) found cloud_profile cache file/size: $cloud_profile_list_file exist/$cloud_profile_list_file_size");
			$res1['message']=file_get_contents($cloud_profile_list_file);
			$this->os_types_obtain=$this->create_bhyve_profiles($res1);
		} else {
			$res1=CBSD::run('get_bhyve_profiles src=cloud', array());
			if($res1['retval']==0){
				$this->os_types_obtain=$this->create_bhyve_profiles($res1);
			}
		}

		// interfaces
		if ($interface_list_file_size > 0 ) {
			ClonOS::syslog("config.php: (cached) found interfaces cache file/size: $interface_list_file exist/$interface_list_file_size");
			$res2['message']=file_get_contents($interface_list_file);
			$this->os_interfaces=$this->create_interfaces($res2);
		} else {
			$res2=CBSD::run('get_interfaces', array());
			if($res2['retval']==0){
				$this->os_interfaces=$this->create_interfaces($res2);
			}
		}
	}

	function create_bhyve_profiles($info){
		$os_names = array();
		$res=json_decode($info['message'],true);
		if(!is_null($res) && $res != false){
			foreach($res as $item){
				$os_name=$this->os_types_names[$item['type']];
				if(isset($os_names[$os_name])){
					$os_names[$os_name]['items'][]=$item;
				}else{
					$os_names[$os_name]=array('os'=>$os_name,'items'=>array($item));
				}
			}
		}
		return $os_names;
	}

	function create_interfaces($info){
		$res=json_decode($info['message'],true);
		if(!is_null($res) && $res != false){
			return $res['interfaces'];
		} else {
			return array();
		}
	}

	function os_types_create($obtain='new'){
//print_r($this->os_types);exit;
		$obtain=($obtain=='obtain');
		if($obtain)
			$info=$this->os_types_obtain;
		else
			$info=$this->os_types;

		$html='';
		foreach($info as $num1=>$os)
		{
			$obtain_count=0;
			$html_tmp='					<optgroup label="'.$os['os'].'">'.PHP_EOL;
			$items=$os['items'];
			foreach($items as $num2=>$item)
			{
				//if(!isset($item['obtain'])) $item['obtain']=false;
				//if(!$obtain || $item['obtain'])
					$html_tmp.='						<option value="'.$num1.'.'.$num2.'">'.$item['name'].'</option>'.PHP_EOL;
				//if($item['obtain']) $obtain_count++;
			}
			$html_tmp.='					</optgroup>'.PHP_EOL;
			
			//if(!$obtain || $obtain_count>0) $html.=$html_tmp;
			$html.=$html_tmp;
		}
		return $html;
	}
	function os_types_getOne($name='first',$obtain='')
	{
		$res=array();
		if($obtain=='obtain')
		{
			$info=$this->os_types_obtain;
		}else{
			$info=$this->os_types;
		}

		if($name=='first')
		{
			$res=current($info)['items'][0];
		}else{
			foreach($info as $type)
			{
				foreach($type['items'] as $arr)
				{
					if($arr['name']==$name)
					{
						$res=$arr;
						break 2;
					}
				}
			}
		}
		return $res;
	}

	function authkeys_list(){
		$db=new Db('base','authkey');
		$res=$db->select('SELECT idx,name FROM authkey;', array());

		$html='';
		if(!empty($res))foreach($res as $item){
			$html.='					<option value="'.$item['idx'].'">'.$item['name'].'</option>'.PHP_EOL;
		}
		return $html;
	}

	function vm_packages_list(){
		$db=new Db('base','local');
		$res=$db->select('select id,name,description,pkg_vm_ram,pkg_vm_disk,pkg_vm_cpus,owner from vmpackages order by name asc;', array());

		$html='<option value="0"></option>';
		$min=0;
		$min_id=0;
		if(!empty($res))foreach($res as $item){
			$cpu=$item['pkg_vm_cpus'];
			$ram=trim($item['pkg_vm_ram']);
			$ed=substr($ram,-1);
			if($ed=='b'){
				$ed=substr($ram,-2,1).'b';
				$ram=substr($ram,0,-2);
			}
			if($ed=='m' || $ed=='g') $ed.='b';
			if($ed=='mb'){
				$ram1=substr($ram,0,-1);
				$ram1=$ram1/1000000;
			}
			if($ed=='gb'){
				$ram1=substr($ram,0,-1);
				$ram1=$ram1/1000;
			}
			$res1=$cpu+$ram1;
			if($min>$res1 || $min==0) {$min=$res1;$min_id=$item['id'];}

			$name=$item['name'].' (cpu: '.$cpu.'; ram: '.$ram.'; hdd: '.$item['pkg_vm_disk'].')';	# '<strong>'.	</strong>
			$html.='					<option value="'.$item['id'].'" title="'.$item['description'].'">'.$name.'</option>'.PHP_EOL;
		}
		return array('html'=>$html,'min_id'=>$min_id);
	}
	
	
	function getOsProfile($form=[],$cloud='')
	{
		if(!isset($form['profile']))
		{
			//$form=[];
			$eng=array_first(ClonOS::$engines);
			$form['engine']=$eng['name'];
			if(!isset($form['cloud'])) $form['cloud']='';
			if($cloud=='cloud')
			{
				foreach($eng['data'] as $key=>$val)
				{
					if(@$val['is_cloud']=='1')
					{
						$form['profile']=$key;
						break;
					}
				}
			}else{
				$form['profile']=array_key_first($eng['data']);
			}
		}
		
		if(!isset($form['profile'])) return false;
		
		$profile=$form['profile'];
		if(isset($form['engine']) && isset(ClonOS::$engines[$form['engine']]))
		{
			$engine=$form['engine'];
			$eng=ClonOS::$engines[$engine];
			$vars=$eng['data'][$profile];
		}else{
			$eng=array_first(ClonOS::$engines);
			$vars=$eng['data'][$profile];
		}
		
		$keys=['vm_cpus_min','vm_cpus_max','imgsize_min','imgsize_max','vm_ram_min','vm_ram_max'];
		foreach($keys as $key)
		{
			if(!isset($vars[$key]))
				$vars[$key]=$eng[$key];
		}
		
		return $vars;
	}
	
	function getEnginesList($type='')
	{
		if(empty(ClonOS::$engines))
		{
			return "<div>Engines is not configured!</div>";
		}
		
		$tabs="\t\t\t\t";
		$ehtml=$tabs.'<select name="engine" onchange="clonos.onChangeEngine(this,event);">';
		$phtml='';
		$first=true;
		$not_engines=(count(ClonOS::$engines)==1);
		foreach(ClonOS::$engines as $key=>$eng)
		{
			if($first)$prefix=$eng['prefix'];
			$ehtml.='<option value="'.$eng['name'].'">'.$eng['description'].'</option>';
			$class=($first)?'':' class="hidden"';
			$phtml.=$tabs.'<select name="profile"'.$class.' id="p-'.$key.'" data-prefix="'.$eng['prefix'].'" onchange="clonos.onChangeProfile(this,event);">';
			
			$arr=$eng['data'];
			$new_arr=[];
			$res=array_multisort(array_column($arr,'long_description'),SORT_ASC,SORT_NATURAL,$arr);

			foreach($arr as $key=>$val)
			{
				$os=$this->os_types_names[$arr[$key]['vm_os_type']];	// ?? 'Unknown';
				$new_arr[$os][$key]=$val;
			}

			foreach($new_arr as $os=>$arr)
			{
				//echo '<pre>';print_r($arr);exit;
				//echo 'count: '.count($arr).'<br>';
				//if(count($arr)==1){echo '<pre>';print_r($arr);exit;}
				$phtml1='<optgroup label="'.$os.'">'.PHP_EOL;
				$count=0;
				foreach($new_arr[$os] as $pkey=>&$prof)
				{
					if(!isset($prof['is_cloud'])) $prof['is_cloud']="0";
					if(($type=='iso' && $prof['is_cloud']!=1) ||
						($type=='cloud' && $prof['is_cloud']==1))
					{
						$phtml1.='<option value="'.$pkey.'" data-jname="'.$prof['default_jailname'].'">'.
							$prof['long_description'].'</option>';
						$count++;
					}
				}
				if($count>0) $phtml.=$phtml1.'</optgroup>'.PHP_EOL;
			}

			$phtml.='</select>'.PHP_EOL;
			$first=false;
		}
		$ehtml.='</select>'.PHP_EOL;
		
		if($not_engines) $ehtml='';
		
		return ['engines'=>$ehtml,'profiles'=>$phtml,'prefix'=>$prefix];
	}
	
	function getEnginesCombo($type='iso')
	{
		ob_start();
		echo '<label for="oslist">Выберите профиль:</label><input list="ositems" name="oslist" id="oslist" placeholder="Начните вводить..." style="width:260px"><datalist id="ositems">';
		
		$key='bhyve';
		$eng=ClonOS::$engines[$key];
//		foreach(ClonOS::$engines as $key=>$eng)
//		{
			$arr=$eng['data'];
			$new_arr=[];
			$res=array_multisort(array_column($arr,'long_description'),SORT_ASC,SORT_NATURAL,$arr);
			foreach($arr as $key=>$val)
			{
				$os=$this->os_types_names[$arr[$key]['vm_os_type']];	// ?? 'Unknown';
				$new_arr[$os][$key]=$val;
			}
			//echo '<pre>';print_r($new_arr);exit;
			
			foreach($new_arr as $os=>$arr)
			{
				//$phtml1='<optgroup label="'.$os.'">'.PHP_EOL;
				echo '<optgroup label="'.$os.'">'.PHP_EOL;
				$count=0;
				foreach($arr as $pkey=>&$prof)
				{
					echo '<option value="'.$prof['long_description'].'" data-jname="'.$prof['default_jailname'].'">'.$prof['long_description'].'</option>';
					echo PHP_EOL;
					$count++;
/*
					if(!isset($prof['is_cloud'])) $prof['is_cloud']="0";
					if(($type=='iso' && $prof['is_cloud']!=1) ||
						($type=='cloud' && $prof['is_cloud']==1))
					{
						$phtml1.='<option value="'.$prof['long_description'].'" data-jname="'.$prof['default_jailname'].'">'.$prof['long_description'].'</option>';
						$count++;
					}
*/
				}
				//$phtml.='</optgroup>'.PHP_EOL;
				echo '</optgroup>'.PHP_EOL;
				//if($count>0) $phtml.=$phtml1.'</optgroup>'.PHP_EOL;
			}
/*
			foreach($new_arr as $os=>$arr)
			{
				$phtml1='<optgroup label="'.$os.'">'.PHP_EOL;
				$count=0;
				foreach($new_arr[$os] as $pkey=>&$prof)
				{
					if(!isset($prof['is_cloud'])) $prof['is_cloud']="0";
					if(($type=='iso' && $prof['is_cloud']!=1) ||
						($type=='cloud' && $prof['is_cloud']==1))
					{
						$phtml1.='<option value="'.$prof['long_description'].'" data-jname="'.$prof['default_jailname'].'">'.$prof['long_description'].'</option>';
						$count++;
					}
				}
				if($count>0) $phtml.=$phtml1.'</optgroup>'.PHP_EOL;
			}
*/
//		}
		echo '</datalist>'.PHP_EOL;
		
		$html = ob_get_contents();
		ob_end_clean();
		
		return $html;
	}
}