<?php
include_once dirname(__FILE__)."/AuthControllers.php";
class SetupControllers extends AuthControllers
{
	/**
	 * 网站管理页面渲染
	 * @access  public
	 * @return html
	 */
	public function index()
	{
		$this->jurisdiction("非法访问网站管理页");
		$this->log_db("用户访问网站管理页","7");
		$system_config = include ROOT_PATH."/config/system.php";
		if($_POST){
	      	$data['name'] = isset($_POST['name']) ? $_POST['name'] : '';
			$data['legitimate_ip'] = isset($_POST['legitimate_ip']) ? $_POST['legitimate_ip'] : '';
			$data['legitimate_type'] = isset($_POST['repair_time']) ? intval($_POST['repair_time']) : '0';
			$token = isset($_POST['token']) ? $_POST['token'] : '';
	      	$session_token = isset($_SESSION['token']) ? $_SESSION['token'] : '';
			#IF判断区域
			if(empty($token)) $this->json(['status'=>0,'msg'=>'输入token！']);
	      	if(empty($session_token)) $this->json(['status'=>0,'msg'=>'token异常！']);
	      	if($token != $session_token) $this->json(['status'=>0,'msg'=>'token验证失败！']);
	      	unset($_SESSION['token']);
	      	$data['domain_key'] = $system_config['domain_key'];
			$list = array_merge($system_config,$data);
            $string = "<?php return [\n";
            foreach ($list as $key => $value) {
            	if(in_array($key, array('legitimate_ip'))){
            		$txt = "[";
            		foreach (explode("\n", $value) as $k => $v) {
            			if(checkIp($v)){
            				$txt .= "'".$v."',";
            			}
            		}
            		$txt .= "]";
                	$string .= "'".$key."'=>".$txt.",\n";
            	} else {
                	$string .= "'".$key."'=>'".$value."',\n";
            	}
            }
            $string .= "]; ?>";
            if(@file_put_contents(ROOT_PATH."/config/system.php", $string)){
            	$_SESSION['system_config'] = get_system_config();
                return $this->json(['status'=>"1",'msg'=>"写入配置成功"]);
            } else {
                return $this->json(['status'=>"0",'msg'=>"写入配置失败"]);
            }
	    } else {
	      	$token = md5(code().time().code());
	      	$_SESSION['token'] = $token;
	      	$this->smarty->assign('token',$token);
			$this->smarty->assign('name',$system_config['name']);
			$this->smarty->assign('legitimate_type',$system_config['legitimate_type']);
			$this->smarty->assign('legitimate_ip',implode("\n", $system_config['legitimate_ip']));
	    	$this->smarty->display('setup/index.tpl');
	    }
	}
}