<?php
header("Conten-type:text/html;charset=utf-8");

class AuthControllers
{
	public function __construct()
	{
		// 配置模板引擎
		include_once(ROOT_PATH."/lib/smarty/Smarty.class.php");  // 导入Smarty类文件
		$this->smarty = new Smarty();// new Smarty类 对象
		$this->smarty->setTemplateDir(dirname(dirname(__FILE__))."/view/");       // 模板存储路目录路径（绝对路径）
		$this->smarty->setCompileDir(ROOT_PATH."/runtime/tmp/"); // 编译后模板存储目录路径（绝对路径）
		$this->smarty->setConfigDir(ROOT_PATH."/config/");       // 配置文件存储目录路径
		$this->smarty->setCacheDir(ROOT_PATH."/cache/");         // 模板缓存文件目录路径

		// //rbac权限验证
		$m = isset($_REQUEST['m']) ? ucfirst($_REQUEST['m'])."Controllers" : '';
		
		$a = isset($_REQUEST['a']) ? $_REQUEST['a'] : '';
		
		if ('/'.$m.'/'.$a != "/InstallControllers/index" && !@file_exists(ROOT_PATH.'/runtime/install.lock')) $this->json(['status'=>0,'msg'=>'请安装应用！',"data"=>["url"=>"/".root_filename.".php?m=Install&a=index"]]);

		$not_check = [
			// 登陆页面
			'/LoginControllers/index',
			// 登陆验证码
			'/LoginControllers/code',
			// 退出登录
			'/LoginControllers/logout',
			// 错误异常页面
			'/ErrorControllers/index',
			// 初始化安装
			'/InstallControllers/index',
		];

        $this->smarty->assign('url_path',$m);
        $this->smarty->assign('url_path_action',$a);
		
		$userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : '';
		$user_info = isset($_SESSION['user_info']) ? $_SESSION['user_info'] : '';
		$session_username = isset($user_info['username']) ? $user_info['username'] : '';
		$this->smarty->assign('session_username', $session_username);

		if($userid){
			$this->smarty->assign('nickname', $user_info['email']);
			$this->smarty->assign('user_info', $user_info);
		}

		if(!isset($_SESSION['system_config'])){
			$_SESSION['system_config'] = get_system_config();
		}
		$this->smarty->assign('system_config', $_SESSION['system_config']);


  		//当前操作的请求 模块名/方法名
        if(in_array('/'.$m.'/'.$a, $not_check)){
            return true;
		}

		if(empty($userid)) $this->json(["status"=>0,"msg"=>"还没有登录，正在跳转到登录页~","data"=>["url"=>"/".root_filename.".php?m=Login&a=index"]]);
	}

	/**
	 * json数据输出
	 * @access  public
	 * @return json
	 */
	public function json($array = [])
	{
		if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){ 
			echo json_encode($array);die;
		}else{
			$url = isset($array['data']['url']) ? $array['data']['url'] : '/'.root_filename.'.php?m=Login&a=index';
			$msg = isset($array['msg']) ? $array['msg'] : '程序异常';
			echo "<script>alert('".$msg."')</script>";
			header("refresh:1;url=".$url);
			die;
		}
	}

	/**
	 * 数据库模块
	 * @access  public
	 * @return Object
	 */
	public function Db()
	{
		include_once ROOT_PATH."/classes/db.php";
		return new DB();
	}

	/**
	 * 用户操作日志
	 * @access  public
	 */
	public function log_db($msg="",$type=1,$userid=0)
	{
		$db = $this->Db();
		//记录IP地址
        $db->bind("ip", getip());
        $db->bind("crate_time", time());
        $db->bind("userid", isset($_SESSION['userid']) ? $_SESSION['userid'] : $userid);
        $db->bind("type", $type);
        $db->bind("msg", $msg);
        $db->query("INSERT INTO domain_logs(ip,crate_time,userid,type,msg) VALUES (:ip,:crate_time,:userid,:type,:msg)");
	}

	/**
	 * 判断用户是否有权限
	 * @access  public
	 * @return Object
	 */	
	public function jurisdiction($msg="非法访问")
	{
		if($_SESSION['userid'] != '1'){
			$this->log_db($msg,"8");
			$this->json(["status"=>0,"msg"=>"没有权限访问！","data"=>["url"=>"/".root_filename.".php?m=Index&a=index"]]);
			return false;
		}
		return true;
	}
}
?>