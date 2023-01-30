<?php
header("Conten-type:text/html;charset=utf-8");

include_once dirname(__FILE__)."/config/function.php";

//获取文件名
define("root_filename", pathinfo(__FILE__)['filename']);

// 获取项目根目录
define("ROOT_PATH", dirname(__FILE__));

// 设置session参数配置  开始
$Lifetime = 5 * 3600;//保存一天
 
$DirectoryPath = dirname(__FILE__)."/runtime/session_tmp/";//设置session保存路径

is_dir($DirectoryPath) or mkdir($DirectoryPath, 0777);

//是否开启基于url传递sessionid,这里是不开启，发现开启也要关闭掉
if (ini_get("session.use_trans_sid") == true) {
	ini_set("url_rewriter.tags", "");
	ini_set("session.use_trans_sid", false);
}
 
ini_set("session.cookie_httponly", 1);//设置httponly

ini_set("session.gc_maxlifetime", $Lifetime);//设置session生存时间
 
ini_set("session.gc_divisor", "1");
 
ini_set("session.gc_probability", "1");
 
ini_set("session.cookie_lifetime", "0");//sessionID在cookie中的生存时间
 
ini_set("session.save_path", $DirectoryPath);//session文件存储的路径

ini_set("session.name", "BQ");//设置session名字

//开启session
session_start();
// 设置session参数配置 结束

$system_config = include_once ROOT_PATH."/config/system.php";

// 设置debug模式
$config_debug = isset($system_config["config_debug"]) ? $system_config["config_debug"] : '0';
if($config_debug == '1'){
	ini_set("display_errors", "On");//打开错误提示
	ini_set("error_reporting", E_ALL);//显示所有错误
} else {
	ini_set("display_errors", "Off");//关闭错误提示
}

$GLOBALS["encryption_url"] = isset($system_config["encryption_url"]) ? $system_config["encryption_url"] : '0';

if(empty($_SESSION['domain_key'])){
	if(empty($system_config['domain_key'])){
		$system_config['domain_key'] = md5(code().time().code());
		$string = "<?php return [\n";
		foreach ($system_config as $k => $v) {
			if(in_array($k, array('legitimate_ip'))){
	    		$txt = "[";
	    		foreach ($v as $ks => $vs) {
	    			$txt .= "'".$vs."',";
	    		}
	    		$txt .= "]";
	        	$string .= "'".$k."'=>".$txt.",\n";
	    	} else {
	        	$string .= "'".$k."'=>'".$v."',\n";
	    	}
		}
		$string .= "]; ?>";
		@file_put_contents(ROOT_PATH."/config/system.php", $string);
		$_SESSION['domain_key'] = $system_config['domain_key'].getip();
		$_SESSION['domain_content_key'] = $system_config['domain_key'];
	} else {
		$_SESSION['domain_content_key'] = $system_config['domain_key'];
		$_SESSION['domain_key'] = $system_config['domain_key'].getip();
	}
} else {
	$_SESSION['domain_content_key'] = $system_config['domain_key'];
	$_SESSION['domain_key'] = $system_config['domain_key'].getip();
}
	
// 开始做URL防止篡改
if(empty($_SERVER['QUERY_STRING'])){
	if(@file_exists(ROOT_PATH."/runtime/install.lock")){
		header("Location:/".root_filename.".php?".AuthCode("m=Login&a=index","ENCODE",$_SESSION['domain_key']));die;
	} else {
		header("Location:/".root_filename.".php?".AuthCode("m=Install&a=index","ENCODE",$_SESSION['domain_key']));die;
	}
}
$_GET = AuthCode($_SERVER['QUERY_STRING'],"DECODE",$_SESSION['domain_key']);
if(!$_GET){
	$_GET = AuthCode($_SERVER['QUERY_STRING'],"DECODE",$_SESSION['domain_content_key']);
	if(!$_GET){
		if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
			echo json_encode(["status"=>0,"msg"=>"Url参数错误！","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Error&a=index","ENCODE",$_SESSION['domain_key'])]]);
		} else {
			header_flush('Url参数错误！',"./".root_filename.".php?".AuthCode("m=Error&a=index","ENCODE",$_SESSION['domain_key']));
		}
	}
}
// 把url转换成数组
parse_str($_GET,$_GET);

//过滤全局xss和sql注入
$_REQUEST = filterWords($_REQUEST);
$_COOKIE = filterWords($_COOKIE);
$_SERVER = filterWords($_SERVER);
$_GET = filterWords($_GET);
$_POST = filterWords($_POST);

//安全体系防御更改文件后台
if(!in_array(getip(), get_system_config()['legitimate_ip'])){
	if(@get_system_config()['legitimate_type'] == '1'){
		//自动换文件名
		rename(pathinfo(__FILE__)['filename'].".php",getRandomString(8).".php");
		die;
	} else if(@get_system_config()['legitimate_type'] == '2'){
		//输出伪造页面
		@header('HTTP/1.0 404 Not Found');
		echo '<html>
	<head>
		<title>404 Not Found</title>
	</head>
	<body>
		<center>
			<h1>404 Not Found</h1>
		</center>
		<hr>
		<center>nginx</center>
	</body>
</html>
		';
		die;
	}
}

$m = isset($_GET['m']) ? ucfirst($_GET['m'])."Controllers" : '';
$a = isset($_GET['a']) ? $_GET['a'] : '';

//判断是否传了控制器，没有传则进入登陆页面
if(empty($m)){
	if(@file_exists(ROOT_PATH."/runtime/install.lock")){
		header("Location:/".root_filename.".php?".AuthCode("m=Login&a=index","ENCODE",$_SESSION['domain_key']));die;
	} else {
		header("Location:/".root_filename.".php?".AuthCode("m=Install&a=index","ENCODE",$_SESSION['domain_key']));die;
	}
}

// 认证符合的控制器允许进入
$m_auth_array = [
	'InstallControllers',
	'LoginControllers',
	'ErrorControllers',
	'IndexControllers',
	'ProductsControllers',
	'UserControllers',
	'PublicControllers',
	'LogControllers',
	'SetupControllers',
	'DocxControllers',
];

if(!in_array($m, $m_auth_array)){
	if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
		echo json_encode(["status"=>0,"msg"=>"程序异常","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Error&a=index","ENCODE",$_SESSION['domain_key'])]]);
	} else {
		header_flush('程序异常',"./".root_filename.".php?".AuthCode("m=Error&a=index","ENCODE",$_SESSION['domain_key']));
	}
	die;
}
if(!is_file(dirname(__FILE__)."/index/controllers/".$m.".php")){
	if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
		echo json_encode(["status"=>0,"msg"=>"控制器不存在","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Error&a=index","ENCODE",$_SESSION['domain_key'])]]);
	} else {
		header_flush('控制器不存在',"./".root_filename.".php?".AuthCode("m=Error&a=index","ENCODE",$_SESSION['domain_key']));
	}
	die;
}

include_once dirname(__FILE__)."/index/controllers/".$m.".php";

$info = new $m();
if(!method_exists($info,$a)){
	if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
		echo json_encode(["status"=>0,"msg"=>"方法不存在","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Login&a=index","ENCODE",$_SESSION['domain_key'])]]);
	} else {
		header_flush('方法不存在',"./".root_filename.".php?".AuthCode("m=Error&a=index","ENCODE",$_SESSION['domain_key']));
	}
	die;
}

$info->$a();
