<?php
include_once dirname(__FILE__)."/AuthControllers.php";
class LoginControllers extends AuthControllers
{
	/**
	 * 登陆页面渲染
	 * @access  public
	 * @return html
	 */
	public function index()
	{
        if($_POST){
            $name = isset($_POST['name']) ? $_POST['name'] : "";
            $password = isset($_POST['password']) ? $_POST['password'] : "";
            $verify = isset($_POST['verify']) ? strtolower($_POST['verify']) : "";
            #IF判断区域
            if(empty($name)) $this->json(['status'=>0,'msg'=>'输入账户！']);
            if(empty($password)) $this->json(['status'=>0,'msg'=>'输入密码！']);
            if(empty($verify)) $this->json(['status'=>0,'msg'=>'输入验证码！']);
            $session_code = isset($_SESSION['index_code']) ? $_SESSION['index_code'] : '';
            if(empty($session_code)) $this->json(['status'=>0,'msg'=>'验证码错误！']);
            if($session_code != $verify) $this->json(['status'=>0,'msg'=>'验证码错误！']);
            unset($_SESSION['index_code']);
            
            #IF判断区域
            $db = $this->Db();
            $db->bind("name", $name);
            $Query_login = $db->find_one("select * from domain_member where `username` = :name");
            if ($Query_login) {

                // 判断是否存在锁定用户并且检查账户密码是否正确
                if(md5($password.$Query_login['salt']) != $Query_login['password']){
	            	$error_num = $Query_login['error_num']+1;
	            	$error_time = strtotime("+10 minutes");

	            	// 超过10次就不进行写入
	            	if($error_num <= 10){
	            		$error_time = strtotime("+30 minutes");
		            	$db->bind("error_num",$error_num);
		            	$db->bind("error_time",$error_time);
		            	$db->bind("id",$Query_login['id']);
		            	$db->query("UPDATE domain_member SET error_num = :error_num,error_time = :error_time WHERE id = :id");
	            	}

	            	// 判断当前时间是否小于锁定时间
	            	if(time() < $Query_login['error_time']){
                		// 判断错误超过10次锁30分钟
	            		if($Query_login['error_num'] >= 10){
	                		$this->log_db("该账户已锁定，30分钟后重试！","0",$Query_login['id']);
	                		$this->json(['status'=>0,'msg'=>'该账户已锁定，30分钟后重试！']);
	                	}
                		// 判断错误超过5次锁30分钟
	                	if($Query_login['error_num'] >= 5){
	                		$this->log_db("该账户已锁定，10分钟后重试！","0",$Query_login['id']);
	                		$this->json(['status'=>0,'msg'=>'该账户已锁定，10分钟后重试！']);
	                	}
	            	}
                    $this->log_db("密码输入错误！","0",$Query_login['id']);
                    $this->json(['status'=>0,'msg'=>'登陆失败！']);
                } else {
                	$db->bind("error_num",0);
                	$db->bind("error_time",0);
                	$db->bind("id",$Query_login['id']);
                	$db->query("UPDATE domain_member SET error_num = :error_num,error_time = :error_time WHERE id = :id");
                }
                
                //验证成功赋予账户密码到SESSION中
                $_SESSION['userid'] = $Query_login['id'];
                $_SESSION['user_info'] = $Query_login;
                
                #更新最后一次update_at
                $db->bind("login_ip",getip());
                $db->bind("update_at",time());
                $db->bind("id",$Query_login['id']);
                $db->query("UPDATE domain_member SET update_at = :update_at,login_ip = :login_ip WHERE id = :id");

                $this->log_db("登陆成功","1");
                $this->json(["status"=>1,"msg"=>"登陆成功！"]);
            } else {
                $this->log_db("查询不到此账户：".filterWords($name),"8");
                $this->json(["status"=>0,"msg"=>"登陆失败！"]);
            }
        } else {
            $this->smarty->assign('verify_img',"./".root_filename.".php?".AuthCode("m=Login&a=code","ENCODE",$_SESSION['domain_key']));
            $this->smarty->assign('ajax_from',"./".root_filename.".php?".AuthCode("m=Login&a=index","ENCODE",$_SESSION['domain_key']));
            $this->smarty->assign('home_index',"./".root_filename.".php?".AuthCode("m=Index&a=index","ENCODE",$_SESSION['domain_key']));
            $this->smarty->display('login/index.tpl');
        }
	}

	/**
	 * 验证码类
	 * @access  public
	 * @return html
	 */
	public function code()
	{
		include_once ROOT_PATH."/lib/ValidateCode/ValidateCode.php";
        $_vc = new ValidateCode();
        $_vc->doimg();
        $_SESSION['index_code'] = $_vc->getCode();
	}

	/**
	 * 退出登录
	 * @access  public
	 * @return json
	 */
	public function logout()
	{
        $this->log_db("退出登录","2");
		$_SESSION=[];
		setCookie("BQ","",time()-1,"/");
		session_destroy();
		echo "<script>alert('退出登录成功！')</script>";
		header("refresh:1;url=/".root_filename.".php?m=Login&a=index");
		die;
	}
}
