<?php
include_once dirname(__FILE__)."/AuthControllers.php";
class UserControllers extends AuthControllers
{
	/**
	 * 个人中心页面渲染
	 * @access  public
	 * @return html
	 */
	public function index()
	{
		$this->log_db("用户访问个人中心","7");

		if($_POST){
			$phone = isset($_POST['phone']) ? $_POST['phone'] : '';
			$email = isset($_POST['email']) ? $_POST['email'] : '';
			$password = isset($_POST['password']) ? $_POST['password'] : '';
			$token = isset($_POST['token']) ? $_POST['token'] : '';
	      	$session_token = isset($_SESSION['token']) ? $_SESSION['token'] : '';
			#IF判断区域
			if(empty($token)) $this->json(['status'=>0,'msg'=>'输入token！']);
	      	if(empty($session_token)) $this->json(['status'=>0,'msg'=>'token异常！']);
	      	if($token != $session_token) $this->json(['status'=>0,'msg'=>'token验证失败！']);
	      	unset($_SESSION['token']);
	      	
	      	$set = [];
			$db = $this->Db();
			$db->bind("id", $_SESSION['userid']);
			if(!empty($email) && is_email($email)){
				$set[] = "`email` = :email";
				$db->bind("email", $email);
			}
			if(!empty($phone) && is_mobile_phone($phone)){
				$set[] = "`phone` = :phone";
				$db->bind("phone", $phone);
			}
			if(!empty($password)){
				$salt = code();
				$set[] = "`password` = :password";
			    $db->bind("password", md5($password.$salt));
				$set[] = "`salt` = :salt";
			    $db->bind("salt", $salt);
			}
			$set = implode(", ",$set);
			if(empty($set)) $this->json(["status"=>0,"msg"=>"修改失败！"]);
			$info = $db->query("UPDATE domain_member SET $set WHERE `id` = :id");
			if($info){
				$db->bind("id", $_SESSION['userid']);
				$Query_login = $db->find_one("select * from domain_member where `id` = :id");
				$_SESSION['user_info'] = $Query_login;
				$this->json(["status"=>1,"msg"=>"修改成功！"]);
			} else {
				$this->json(["status"=>0,"msg"=>"修改失败！"]);
			}
		} else {
			$token = md5(code().time().code());
			$_SESSION['token'] = $token;
			$this->smarty->assign('token',$token);
			$this->smarty->assign('user_info',$_SESSION['user_info']);
	    	$this->smarty->display('user/index.tpl');
		}
	}

	/**
	 * 头像修改
	 * @access  public
	 * @return html
	 */
	public function img()
	{
		if($_FILES){
	      	include_once ROOT_PATH."/classes/upload.php";
	      	$path = "./public/upload/";
	      	$upload = new Upload(['path'=>$path]);
	      	if(isset($_FILES['file'])){
	        	$info = $upload->uploadFile("file");
	        	if($info){
	            	$db = $this->Db();
	            	$db->bind("id", $_SESSION['userid']);
	            	$db->bind("img", $info);
	            	$_SESSION['user_info']['img'] = $info;
					$db->query("UPDATE domain_member SET `img` = :img WHERE `id` = :id");
					$this->log_db("用户修改了头像","6");
		      		$this->json(["status"=>1,"msg"=>"文件上传成功"]);
	        	} else {
					$this->log_db("用户异常上传文件操作：".$upload->errorInfo.",文件名：".filterWords(basename($_FILES['file']['name'])),"8");
	        		$this->json(["status"=>0,"msg"=>$upload->errorInfo]);
	        	}
	      	} else {
	        	$this->json(["status"=>0,"msg"=>"文件上传异常！"]);
	      	}
		} else {
	      	$this->json(["status"=>0,"msg"=>"文件上传异常！"]);
		}
	}

	/**
	 * 用户管理页面渲染
	 * @access  public
	 * @return html
	 */
	public function member()
	{
		$this->jurisdiction("非法访问用户管理");
		$this->log_db("用户访问用户管理页面","7");
		$token = md5(code().time().code());
      	$_SESSION['token'] = $token;
      	$this->smarty->assign('token',$token);
		if($_POST){
	      	$draw = isset($_POST['draw']) ? intval($_POST['draw']) : "1";
	      	$start = isset($_POST['start']) ? intval($_POST['start']) : "1";
	      	$length = isset($_POST['length']) ? intval($_POST['length']) : "10";
	      	if($length < 0 || $length > 10) $length = 10;

	      	$value = isset($_POST['value']) ? $_POST['value'] : "";
			$db = $this->Db();
			$sql = "SELECT id,username,img,email,phone,login_ip,update_at FROM domain_member limit ".$start.",".$length;
			$count = "SELECT count(*) as num FROM domain_member";
			if($value){
				$sql = "SELECT id,username,img,email,phone,login_ip,update_at FROM domain_member WHERE username LIKE :username OR email LIKE :email OR phone LIKE :phone limit ".$start.",".$length;
			  	$db->bind("username", "%".$value."%");
			  	$db->bind("email", "%".$value."%");
			  	$db->bind("phone", "%".$value."%");
			}
			$list = $db->query($sql);

			if($list){
	        	foreach ($list as $k => $v) {
	          		$list[$k]['update_at'] = $v['update_at'] == 0 ? '-' : date("Y-m-d H:i:s",$v['update_at']);
	          		$list[$k]['edit_member'] = "./".root_filename.".php?".AuthCode("m=User&a=edit_member&id=".$v['id'],"ENCODE",$_SESSION['domain_key']);
		        	$list[$k]['del_member'] = "./".root_filename.".php?".AuthCode("m=User&a=del_member&id=".$v['id']."&token=".$token,"ENCODE",$_SESSION['domain_key']);
	        	}
	      	}
	      	if($value){
				$count = "SELECT count(*) as num FROM domain_member WHERE username LIKE :username OR email LIKE :email OR phone LIKE :phone ";
			  	$db->bind("username", "%".$value."%");
			  	$db->bind("email", "%".$value."%");
			  	$db->bind("phone", "%".$value."%");
			}
      		$classification_count = $db->find_one($count);
      		$classification_num = isset($classification_count['num']) ? $classification_count['num'] : 0;
	      	$this->json(["draw"=>$draw,"recordsTotal"=>$classification_num,"recordsFiltered"=>$classification_num,"data"=>$list]);
	    } else {
	    	$this->smarty->display('user/member.tpl');
	    }
	}

	/**
	 * 添加用户
	 * @access  public
	 * @return html
	 */
	public function add_member()
	{
		$this->jurisdiction("非法访问添加用户");
		$this->log_db("用户访问添加管理页面","4");

		$db = $this->Db();
		if($_POST){
		    $username = isset($_POST['username']) ? $_POST['username'] : '';
		    $password = isset($_POST['password']) ? $_POST['password'] : '';
		    $email = isset($_POST['email']) ? $_POST['email'] : '';
		    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
			$token = isset($_POST['token']) ? $_POST['token'] : '';
	      	$session_token = isset($_SESSION['token']) ? $_SESSION['token'] : '';
	      	#IF判断区域
     	 	if(empty($username)) $this->json(['status'=>0,'msg'=>'输入用户名！']);
     	 	if(empty($password)) $this->json(['status'=>0,'msg'=>'输入密码！']);
	      	if(empty($token)) $this->json(['status'=>0,'msg'=>'输入token！']);
	      	if(empty($session_token)) $this->json(['status'=>0,'msg'=>'token异常！']);
	      	if($token != $session_token) $this->json(['status'=>0,'msg'=>'token验证失败！']);
	      	unset($_SESSION['token']);

			$db->bind("username", trim($username));
	      	$member = $db->find_one("select * from domain_member where username = :username");
	      	if($member) $this->json(['status'=>0,'msg'=>'账户已存在！']);

	      	$set = [];
			$set_val = [];
			if(!empty($username)){
			  	$set[] = "username";
			  	$set_val[] = ":username";
			  	$db->bind("username", $username);
			}

			if(!empty($email) && is_email($email)){
				$set[] = "email";
			  	$set_val[] = ":email";
				$db->bind("email", $email);
			}
			if(!empty($phone) && is_mobile_phone($phone)){
				$set[] = "phone";
			  	$set_val[] = ":phone";
				$db->bind("phone", $phone);
			}

			if(!empty($password)){
				$salt = code();
				$set[] = "password";
			  	$set_val[] = ":password";
			    $db->bind("password", md5($password.$salt));
				$set[] = "salt";
			  	$set_val[] = ":salt";
			    $db->bind("salt", $salt);
			}

			$set[] = "uuid";
			$set[] = "create_at";
			$set[] = "update_at";
			$set_val[] = ":uuid";
			$set_val[] = ":create_at";
			$set_val[] = ":update_at";
			$db->bind("uuid", uuid());
			$db->bind("create_at", time());
			$db->bind("update_at", time());

			$set = implode(",",$set);
      		$set_val = implode(",",$set_val);
      		$info = $db->query("INSERT INTO domain_member($set) VALUES ($set_val)");
      		if($info){
        		$this->json(["status"=>1,"msg"=>"添加成功！"]);
      		} else {
        		$this->json(["status"=>0,"msg"=>"添加失败！"]);
      		}
		} else {
	      	$token = md5(code().time().code());
	      	$_SESSION['token'] = $token;
	      	$this->smarty->assign('token',$token);
	    	$this->smarty->display('user/add_member.tpl');
		}
	}

	/**
	 * 编辑用户
	 * @access  public
	 * @return html
	 */
	public function edit_member()
	{
		$this->jurisdiction("非法访问编辑用户");
		$this->log_db("用户访问编辑管理页面","6");

		$db = $this->Db();
    	if($_POST){
			$username = isset($_POST['username']) ? $_POST['username'] : '';
		    $password = isset($_POST['password']) ? $_POST['password'] : '';
		    $email = isset($_POST['email']) ? $_POST['email'] : '';
		    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
			$id = isset($_POST['id']) ? intval($_POST['id']) : '';
			$token = isset($_POST['token']) ? $_POST['token'] : '';
	      	$session_token = isset($_SESSION['token']) ? $_SESSION['token'] : '';
			#IF判断区域
			if(empty($username)) $this->json(['status'=>0,'msg'=>'输入用户名！']);
			if(empty($id)) $this->json(['status'=>0,'msg'=>'输入ID！']);
			if(empty($token)) $this->json(['status'=>0,'msg'=>'输入token！']);
	      	if(empty($session_token)) $this->json(['status'=>0,'msg'=>'token异常！']);
	      	if($token != $session_token) $this->json(['status'=>0,'msg'=>'token验证失败！']);
	      	unset($_SESSION['token']);

	      	$set = [];
			$db = $this->Db();
			$db->bind("id", $id);
			
			if(!empty($username)){
				$set[] = "`username` = :username";
				$db->bind("username", $username);
			}

			if(!empty($name)){
				$set[] = "`title` = :title";
				$db->bind("title", $name);
			}

			if(!empty($email) && is_email($email)){
				$set[] = "`email` = :email";
				$db->bind("email", $email);
			}
			if(!empty($phone) && is_mobile_phone($phone)){
				$set[] = "`phone` = :phone";
				$db->bind("phone", $phone);
			}

			if(!empty($password)){
				$salt = code();
			    $set[] = "`password` = :password";
			    $db->bind("password", md5($password.$salt));
			    $set[] = "`salt` = :salt";
				$db->bind("salt", $salt);
			}


			$set[] = "`update_at` = :update_at";
			$db->bind("update_at", time());

			$set = implode(", ",$set);
			if(empty($set)) $this->json(["status"=>0,"msg"=>"修改失败！"]);
			$info = $db->query("UPDATE domain_member SET $set WHERE `id` = :id");
			if($info){
				$this->json(["status"=>1,"msg"=>"修改成功！"]);
			} else {
				$this->json(["status"=>0,"msg"=>"修改失败！"]);
			}
    	} else {
    		$id = isset($_GET['id']) ? intval($_GET['id']) : '';
	      	if($id){
	        	$db->bind("id", $id);
	        	$member = $db->find_one("select * from domain_member where id = :id");
	        	$token = md5(code().time().code());
		      	$_SESSION['token'] = $token;
		      	$this->smarty->assign('token',$token);
	        	$this->smarty->assign('member',$member);
	        	$this->smarty->display('user/edit_member.tpl');
	      	} else {
	        	$this->json(['data'=>['url'=>"./".root_filename.".php?m=User&a=index"],'msg'=>'程序异常']);
	      	}
    	}
	}

	/**
	 * 删除用户
	 * @access  public
	 * @return html
	 */
	public function del_member()
	{
		$this->jurisdiction("非法访问删除用户");
		$this->log_db("用户访问删除用户","5");

 		$db = $this->Db();
	    if($_GET){
	      	$id = isset($_GET['id']) ? intval($_GET['id']) : '';
	      	$token = isset($_GET['token']) ? $_GET['token'] : '';
	      	$session_token = isset($_SESSION['token']) ? $_SESSION['token'] : '';
	      	#IF判断区域
	      	if(empty($id)) $this->json(['status'=>0,'msg'=>'输入ID！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=User&a=member","ENCODE",$_SESSION['domain_key'])]]);
	      	if($id == "1") $this->json(['status'=>0,'msg'=>'不能删除超级管理员！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=User&a=member","ENCODE",$_SESSION['domain_key'])]]);
	      	if(empty($token)) $this->json(['status'=>0,'msg'=>'输入token！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=User&a=member","ENCODE",$_SESSION['domain_key'])]]);
	      	if(empty($session_token)) $this->json(['status'=>0,'msg'=>'token异常！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=User&a=member","ENCODE",$_SESSION['domain_key'])]]);
	      	if($token != $session_token) $this->json(['status'=>0,'msg'=>'token验证失败！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=User&a=member","ENCODE",$_SESSION['domain_key'])]]);
	      	unset($_SESSION['token']);

	      	$db->bind("id", $id);
	      	$info = $db->query("DELETE from domain_member where `id` = :id");
	      	if($info){
	        	$this->json(["status"=>1,"msg"=>"删除成功！","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=User&a=member","ENCODE",$_SESSION['domain_key'])]]);
	      	} else {
	        	$this->json(["status"=>0,"msg"=>"删除失败！","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=User&a=member","ENCODE",$_SESSION['domain_key'])]]);
	      	}
	    } else {
	      	$this->json(["status"=>0,"msg"=>"错误异常！","data"=>["url"=>"./".root_filename.".php?".AuthCode("m=User&a=member","ENCODE",$_SESSION['domain_key'])]]);
	    }
	}
}