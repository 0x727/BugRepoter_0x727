<?php
include_once dirname(__FILE__)."/AuthControllers.php";
class LogControllers extends AuthControllers
{
	/**
	 * 日志页面渲染
	 * @access  public
	 * @return html
	 */
	public function index()
	{
		$this->jurisdiction("非法访问日志页面");
		$this->log_db("用户访问日志页面","7");
		if($_POST){
	      	$draw = isset($_POST['draw']) ? intval($_POST['draw']) : "1";
	      	$start = isset($_POST['start']) ? intval($_POST['start']) : "1";
	      	$length = isset($_POST['length']) ? intval($_POST['length']) : "10";
	      	if($length < 0 || $length > 10) $length = 10;

	      	$value = isset($_POST['value']) ? $_POST['value'] : "";
			$db = $this->Db();
			$sql = "SELECT a.*,b.username FROM domain_logs as a LEFT JOIN domain_member as b ON a.userid = b.id ORDER by id desc limit ".$start.",".$length;
			$count = "SELECT count(*) as num FROM domain_logs as a LEFT JOIN domain_member as b ON a.userid = b.id";
			if($value){
				$sql = "SELECT a.*,b.username FROM domain_logs as a LEFT JOIN domain_member as b ON a.userid = b.id WHERE a.ip = :ip OR b.username LIKE :username ORDER by id desc limit ".$start.",".$length;
			  	$db->bind("ip", $value);
			  	$db->bind("username", "%".$value."%");
			}
			$list = $db->query($sql);

			if($list){
				$type = ['密码错误','登录','退出登录','锁定用户','增加','删除','修改','查询','非法请求','下载报告','文件上传',];
	        	foreach ($list as $k => $v) {
	          		$list[$k]['crate_time'] = $v['crate_time'] == 0 ? '-' : date("Y-m-d H:i:s",$v['crate_time']);
	          		$list[$k]['type'] = $type[ $v['type'] ];
	          		if(empty($v['username'])){
						$list[$k]['username'] = "游客";
	          		}
	        	}
	      	}
	      	if($value){
				$count = "SELECT count(*) as num FROM domain_logs as a LEFT JOIN domain_member as b ON a.userid = b.id WHERE a.ip = :ip OR b.username LIKE :username";
			  	$db->bind("ip", $value);
			  	$db->bind("username", "%".$value."%");
			}
      		$logs_count = $db->find_one($count);
      		$logs_num = isset($logs_count['num']) ? $logs_count['num'] : 0;
	      	$this->json(["draw"=>$draw,"recordsTotal"=>$logs_num,"recordsFiltered"=>$logs_num,"data"=>$list]);
	    } else {
	      	$token = md5(code().time().code());
	      	$_SESSION['token'] = $token;
	      	$this->smarty->assign('token',$token);
	    	$this->smarty->display('log/index.tpl');
	    }
	}
}