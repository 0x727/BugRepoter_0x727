<?php

header("Conten-type:text/html;charset=utf-8");

include_once dirname(__FILE__)."/config/function.php";

ini_set("display_errors", "Off");//关闭错误提示
// ini_set("display_errors", "On");//打开错误提示
// ini_set("error_reporting", E_ALL);//显示所有错误

//过滤全局xss和sql注入
$_REQUEST = filterWords($_REQUEST);
$_COOKIE = filterWords($_COOKIE);
$_SERVER = filterWords($_SERVER);
$_GET = filterWords($_GET);
$_POST = filterWords($_POST);

//获取文件名
define("root_filename", pathinfo(__FILE__)['filename']);

// 获取项目根目录
define("ROOT_PATH", dirname(__FILE__));

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

$m = isset($_REQUEST['m']) ? ucfirst($_REQUEST['m'])."Controllers" : '';
$a = isset($_REQUEST['a']) ? $_REQUEST['a'] : '';

//判断是否传了控制器，没有传则进入登陆页面
if(empty($m)){
	if(@file_exists(ROOT_PATH."/runtime/install.lock")){
		header("Location:/".root_filename.".php?m=Login&a=index");die;
	} else {
		header("Location:/".root_filename.".php?m=Install&a=index");die;
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
];

if(!in_array($m, $m_auth_array)){
	if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
		echo json_encode(["status"=>0,"msg"=>"程序异常","data"=>["url"=>"/".root_filename.".php?m=Error&a=index"]]);
	} else {
		echo "<script>alert('程序异常')</script>";
		header("refresh:1;url=/".root_filename.".php?m=Error&a=index");
	}
	die;
}
if(!is_file(dirname(__FILE__)."/index/controllers/".$m.".php")){
	if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
		echo json_encode(["status"=>0,"msg"=>"控制器不存在","data"=>["url"=>"/".root_filename.".php?m=Error&a=index"]]);
	} else {
		echo "<script>alert('控制器不存在')</script>";
		header("refresh:1;url=/".root_filename.".php?m=Error&a=index");
	}
	die;
}

include_once dirname(__FILE__)."/index/controllers/".$m.".php";

$info = new $m();
if(!method_exists($info,$a)){
	if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
		echo json_encode(["status"=>0,"msg"=>"方法不存在","data"=>["url"=>"/".root_filename.".php?m=Login&a=index"]]);
	} else {
		echo "<script>alert('方法不存在')</script>";
		header("refresh:1;url=/".root_filename.".php?m=Error&a=index");
	}
	die;
}

$info->$a();
