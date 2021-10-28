<?php
header("Conten-type:text/html;charset=utf-8");
include_once dirname(__FILE__)."/AuthControllers.php";
class ProductsControllers extends AuthControllers
{
	/**
	 * 漏洞列表
	 * @access  public
	 * @return html
	 */
	public function index()
	{
		$this->log_db("用户访问漏洞列表","7");
		$db = $this->Db();
		if ($_POST) {
			$num = isset($_POST['num']) ? intval($_POST['num']) : '';
			$company = isset($_POST['company']) ? intval($_POST['company']) : '';
			if($num){
		      	if($num <= 0) $num = 1;
	    		if($num > 10) $this->json(['status'=>0,'msg'=>'批量添加报告数量已超出，最大限制10份！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
				 $this->json(['status'=>1,'msg'=>'操作成功',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=add_index&num=".$num,"ENCODE",$_SESSION['domain_key'])]]);
			} else {
				$draw = isset($_POST['draw']) ? intval($_POST['draw']) : "1";
		      	$start = isset($_POST['start']) ? intval($_POST['start']) : "1";
		      	$length = isset($_POST['length']) ? intval($_POST['length']) : "10";
		      	if($length < 0 || $length > 50) $length = 10;

		      	$value = isset($_POST['value']) ? $_POST['value'] : "";
				$sql = "SELECT a.id,a.session,a.title,a.creation_time,c.title as user_id,b.title as cate_id,a.bugLevel,a.repair_time,c.title as company,a.company as company_id,a.export_time FROM domain_post as a LEFT JOIN domain_classification as b on a.cate_id = b.id LEFT JOIN domain_project_classification as c on a.company = c.id";
				$count = "SELECT count(*) as num FROM domain_post";
				if($_SESSION['userid'] != '1'){
				    $db->bind("user_id", $_SESSION['userid']);
					$sql .= " WHERE a.user_id = :user_id";
					if($company){
				    	$db->bind("company", $company);
						$sql .= " AND a.company = :company";
					}
					if($value){
						$db->bind("title", "%".$value."%");
						$sql .= " AND a.title LIKE :title";
					}
				} else {
					if($company){
				    	$db->bind("company", $company);
						$sql .= " WHERE a.company = :company";
						if($value){
					    	$db->bind("title", "%".$value."%");
							$sql .= " AND a.title LIKE :title";
						}
					} else {
						if($value){
					    	$db->bind("title", "%".$value."%");
							$sql .= " WHERE a.title LIKE :title";
						}
					}
				}
				$sql .= " ORDER BY a.id desc limit ".$start.",".$length;
				$list = $db->query($sql);
				if($list){
					$type = ['1'=>'无影响','2'=>'低危','3'=>'中危','4'=>'高危','5'=>'严重'];
		        	foreach ($list as $k => $v) {
		          		$list[$k]['creation_time'] = $v['creation_time'] == 0 ? '-' : date("Y-m-d H:i:s",$v['creation_time']);
		          		$list[$k]['repair_time'] = $v['repair_time'] == 0 ? '-' : date("Y-m-d H:i:s",$v['repair_time']);
		          		$list[$k]['export_time'] = $v['export_time'] == 0 ? '-' : date("Y-m-d H:i:s",$v['export_time']);
		          		$list[$k]['bugLevel'] = $type[ $v['bugLevel'] ];
		          		if(empty($v['repair_time'])){
			        		$list[$k]['repair_time'] = "否";
			        	} else {
			        		$list[$k]['repair_time'] = "是";
			        	}
			        	$list[$k]['edit_index_id'] = "./".root_filename.".php?".AuthCode("m=Products&a=edit_index&id=".$v['id'],"ENCODE",$_SESSION['domain_key']);
			        	$list[$k]['del_index_id'] = "./".root_filename.".php?".AuthCode("m=Products&a=del_index&id=".$v['id']."&token=".$_SESSION['token'],"ENCODE",$_SESSION['domain_key']);
			        	$list[$k]['repair_id'] = "./".root_filename.".php?".AuthCode("m=Products&a=repair_index&id=".$v['id'],"ENCODE",$_SESSION['domain_key']);
			        	$list[$k]['repair_view_index'] = "./".root_filename.".php?".AuthCode("m=Products&a=repair_view_index&id=".$v['id'],"ENCODE",$_SESSION['domain_key']);
		        	}
		      	}
				if($_SESSION['userid'] != '1'){
				    $db->bind("user_id", $_SESSION['userid']);
					$count .= " WHERE user_id = :user_id";
					if($company){
				    	$db->bind("company", $company);
						$count .= " AND company = :company";
					}
					if($value){
						$db->bind("title", "%".$value."%");
						$count .= " AND title LIKE :title";
					}
				} else {
					if($company){
				    	$db->bind("company", $company);
						$count .= " WHERE company = :company";
						if($value){
					    	$db->bind("title", "%".$value."%");
							$count .= " AND title LIKE :title";
						}
					} else {
						if($value){
					    	$db->bind("title", "%".$value."%");
							$count .= " WHERE title LIKE :title";
						}
					}
				}

	      		$classification_count = $db->find_one($count);
	      		$classification_num = isset($classification_count['num']) ? $classification_count['num'] : 0;
		      	$this->json(["draw"=>$draw,"recordsTotal"=>$classification_num,"recordsFiltered"=>$classification_num,"data"=>$list]);
			}
		} else {
			$template_list = $db->query("select uuid,name from domain_template");
			$template = "";
			if($template_list){
				foreach ($template_list as $k => $v) {
					$template .= '<option value="'.$v['uuid'].'">'.$v['name'].'</option>';
				}
			}
			$token = md5(code().time().code());
	    	$_SESSION['token'] = $token;
	    	$this->smarty->assign('token',$token);
	    	$project_classification = $db->query("select id,title from domain_project_classification");
		    $this->smarty->assign('project_classification',$project_classification);

	      	$this->smarty->assign('template',$template);
	      	$this->smarty->assign('products_add_index',"/".root_filename.".php?".AuthCode("m=Products&a=add_index&num=1","ENCODE",$_SESSION['domain_key']));
	      	$this->smarty->assign('products_download_index',"/".root_filename.".php?".AuthCode("m=Products&a=download_index","ENCODE",$_SESSION['domain_key']));
	      	$this->smarty->assign('products_repair_download_index',"/".root_filename.".php?".AuthCode("m=Products&a=repair_download_index","ENCODE",$_SESSION['domain_key']));
	      	$this->smarty->assign('add_index',"/".root_filename.".php?".AuthCode("m=Products&a=add_index","ENCODE",$_SESSION['domain_key']));
	    	$this->smarty->display('products/index.tpl');
		}
	}

	/**
	 * 添加漏洞
	 * @access  public
	 * @return html
	 */
	public function add_index()
	{
		$this->log_db("用户访问添加漏洞","4");
		$db = $this->Db();
		if($_POST){
			$data = isset($_POST['data']) ? json_decode(htmlspecialchars_decode($_POST['data']),true) : "";
	        $num = isset($_POST['num']) ? intval($_POST['num']) : "0";
	        $token = isset($_POST['token']) ? strtolower($_POST['token']) : "";
	        $data_length = count($data);
	        #IF判断区域
			if($num > 10) $this->json(['status'=>0,'msg'=>'批量添加报告数量已超出，最大限制10份！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
			if($data_length != $num) $this->json(['status'=>0,'msg'=>'报告数量异常！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
			if(empty($token)) $this->json(['status'=>0,'msg'=>'输入token！']);
	        $session_code = isset($_SESSION['token']) ? $_SESSION['token'] : '';
	        $success = 0;
			#循环判断
	        foreach ($data as $k => $v) {
		        $name = isset($v['name']) ? $v['name'] : "";
		        $bugDetail = isset($v['bugDetail']) ? $v['bugDetail'] : "";
		        $cate_id = isset($v['cate_id']) ? intval($v['cate_id']) : "";
		        $bugLevel = isset($v['bugLevel']) ? intval($v['bugLevel']) : "0";
		        $company = isset($v['company']) ? $v['company'] : "";
		        $content = isset($v['content']) ? $v['content'] : "";
		        $description = isset($v['description']) ? $v['description'] : "";
		        $suggestions = isset($v['suggestions']) ? $v['suggestions'] : "";
		        $db->bind("title", $name);
	        	$post = $db->find_one("select * from domain_post where title = :title");
		        if($post) $this->json(['status'=>0,'msg'=>'第'.$k.'份报告，漏洞名称已存在！']);

		        if(empty($name)) $this->json(['status'=>0,'msg'=>'第'.$k.'份报告，请输入漏洞名称！']);
		        if(empty($bugDetail)) $this->json(['status'=>0,'msg'=>'第'.$k.'份报告，请输入漏洞Url！']);
		        if(empty($cate_id)) $this->json(['status'=>0,'msg'=>'第'.$k.'份报告，请选择漏洞分类！']);
		        if(empty($company)) $this->json(['status'=>0,'msg'=>'第'.$k.'份报告，请选择所属公司！']);
		        if(empty($description)) $this->json(['status'=>0,'msg'=>'第'.$k.'份报告，输入漏洞描述！']);
		        if(empty($suggestions)) $this->json(['status'=>0,'msg'=>'第'.$k.'份报告，输入修复建议！']);
		        if(empty($content)) $this->json(['status'=>0,'msg'=>'第'.$k.'份报告，请输入漏洞内容！']);
	        }
	        if(empty($session_code)) $this->json(['status'=>2,'msg'=>'token异常！']);
	        if($session_code != $token) $this->json(['status'=>2,'msg'=>'token验证失败！']);
	        unset($_SESSION['token']);

	        // 循环插入数据库
	        foreach ($data as $k => $v) {
	        	$name = isset($v['name']) ? $v['name'] : "";
		        $bugDetail = isset($v['bugDetail']) ? $v['bugDetail'] : "";
		        $cate_id = isset($v['cate_id']) ? intval($v['cate_id']) : "";
		        $bugLevel = isset($v['bugLevel']) ? intval($v['bugLevel']) : "0";
		        $company = isset($v['company']) ? intval($v['company']) : "";
		        $content = isset($v['content']) ? $v['content'] : "";
		        $description = isset($v['description']) ? $v['description'] : "";
		        $suggestions = isset($v['suggestions']) ? $v['suggestions'] : "";
		        $db->bind("title", $name);
		        $db->bind("content", AuthCode(str_replace("截图、本地图片可直接复制粘贴进编辑器中", "", $content),"ENCODE",$_SESSION['domain_content_key']));
		        $db->bind("bugDetail", $bugDetail);
		        $db->bind("company", $company);
		        $db->bind("creation_time", time());
		        $db->bind("update_time", time());
		        $db->bind("cate_id", $cate_id);
		        $db->bind("description", $description);
		        $db->bind("suggestions", $suggestions);
		        $db->bind("user_id", $_SESSION['userid']);
		        $db->bind("bugLevel", $bugLevel);
		        $db->bind("session", md5(code()));
		        $info = $db->query("INSERT INTO domain_post(title,session,content,bugDetail,creation_time,update_time,cate_id,user_id,bugLevel,company,description,suggestions) VALUES (:title,:session,:content,:bugDetail,:creation_time,:update_time,:cate_id,:user_id,:bugLevel,:company,:description,:suggestions)");
		        if($info){
		        	$host = isset(parse_url($bugDetail)['host']) ? parse_url($bugDetail)['host'] : '';
		        	if($host){
		        		$db->bind("pid",$company);
		        		$db->bind("url",$host);
		        		if(!$db->find_one("select * from domain_project_domain where pid = :pid and url = :url")){
			        		$db->bind("url",$host);
			        		$db->bind("pid",$company);
			        		$db->bind("creation_time",time());
		        			$db->query("INSERT INTO domain_project_domain(url,pid,creation_time) VALUES (:url,:pid,:creation_time)");
		        		}
		        	}
		          	$success += 1;
		        } else {
		          	$success -= 1;
		        }
	        }
	        $this->json(["status"=>1,"msg"=>"提交成功，已提交(".$success.")份报告！"]);
		} else {
	      	$num = isset($_GET['num']) ? intval($_GET['num']) : '1';
	      	if($num <= 0) $num = 1;
    		if($num > 10) $this->json(['status'=>0,'msg'=>'批量添加报告数量已超出，最大限制10份！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	      	$this->smarty->assign('num',$num);
			$classification = $db->query("select * from domain_classification");
	      	$this->smarty->assign('classification',$classification);
		    $this->smarty->assign('classification_json',json_encode($classification));
	      	$project_classification = $db->query("select id,title from domain_project_classification");
	      	$this->smarty->assign('project_classification',$project_classification);
	      	$token = md5(code().time().code());
	      	$_SESSION['token'] = $token;
	      	$this->smarty->assign('token',$token);
	    	$this->smarty->display('products/add_index.tpl');
		}
	}

	/**
	 * 编辑漏洞
	 * @access  public
	 * @return html
	 */
	public function edit_index()
	{
		$this->log_db("用户访问编辑漏洞","6");
		$db = $this->Db();
		if($_POST){
	      	$id = isset($_POST['id']) ? intval($_POST['id']) : "";
			$name = isset($_POST['name']) ? $_POST['name'] : "";
	        $bugDetail = isset($_POST['bugDetail']) ? $_POST['bugDetail'] : "";
	        $cate_id = isset($_POST['cate_id']) ? intval($_POST['cate_id']) : "";
	        $bugLevel = isset($_POST['bugLevel']) ? intval($_POST['bugLevel']) : "0";
	        $company = isset($_POST['company']) ? $_POST['company'] : "";
	        $content = isset($_POST['content']) ? $_POST['content'] : "";
	        $description = isset($_POST['description']) ? $_POST['description'] : "";
	        $suggestions = isset($_POST['suggestions']) ? $_POST['suggestions'] : "";
	        $token = isset($_POST['token']) ? strtolower($_POST['token']) : "";
	        #IF判断区域
	      	if(empty($id)) $this->json(['status'=>0,'msg'=>'输入ID！']);
	        if(empty($name)) $this->json(['status'=>0,'msg'=>'请输入漏洞名称！']);
	        if(empty($bugDetail)) $this->json(['status'=>0,'msg'=>'请输入漏洞Url！']);
	        if(empty($cate_id)) $this->json(['status'=>0,'msg'=>'请选择漏洞分类！']);
	        if(empty($company)) $this->json(['status'=>0,'msg'=>'请选择所属公司！']);
	        if(empty($description)) $this->json(['status'=>0,'msg'=>'输入漏洞描述！']);
	        if(empty($suggestions)) $this->json(['status'=>0,'msg'=>'输入修复建议！']);
	        if(empty($content)) $this->json(['status'=>0,'msg'=>'请输入漏洞内容！']);
	        if(empty($token)) $this->json(['status'=>0,'msg'=>'输入token！']);
	        $session_code = isset($_SESSION['token']) ? $_SESSION['token'] : '';
	        if(empty($session_code)) $this->json(['status'=>2,'msg'=>'token异常！']);
	        if($session_code != $token) $this->json(['status'=>2,'msg'=>'token验证失败！']);
	        unset($_SESSION['token']);
	      	$db->bind("id", $id);
	        $db->bind("title", $name);
	        $db->bind("content", AuthCode($content,"ENCODE",$_SESSION['domain_content_key']));
	        $db->bind("bugDetail", $bugDetail);
	        $db->bind("company", $company);
	        $db->bind("update_time", time());
	        $db->bind("cate_id", $cate_id);
	        $db->bind("description", $description);
	        $db->bind("suggestions", $suggestions);
	        $db->bind("bugLevel", $bugLevel);
	        
        	$sql = "UPDATE domain_post SET `title` = :title,`content` = :content,`bugDetail` = :bugDetail,`update_time` = :update_time,`cate_id` = :cate_id,`bugLevel` = :bugLevel,`company` = :company,`description` = :description,`suggestions` = :suggestions WHERE `id` = :id";
	        if($_SESSION['userid'] != '1'){
			    $db->bind("user_id", $_SESSION['userid']);
				$sql .= " AND user_id = :user_id";
			}
      		$info = $db->query($sql);

	        if($info){
	          	$this->json(["status"=>1,"msg"=>"修改成功！"]);
	        } else {
	          	$this->json(["status"=>0,"msg"=>"修改失败！"]);
	        }
		} else {
			$id = isset($_GET['id']) ? intval($_GET['id']) : '';
	      	if($id){
	      		$sql = "select * from domain_post where id = :id";
	      		if($_SESSION['userid'] != '1'){
				    $db->bind("user_id", $_SESSION['userid']);
					$sql .= " AND user_id = :user_id";
				}
	        	$db->bind("id", $id);
	        	$post = $db->find_one($sql);
	        	if($post){
			     	$post['content'] = htmlspecialchars_decode(str_replace(["《","》"], ['<','>'], AuthCode($post['content'],"DECODE",$_SESSION['domain_content_key'])));
			     	preg_match_all("/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i",$post['content'],$match);
			     	if($match[0]){
			     		foreach (end($match) as $key) {
			     			if(!AuthCode(str_replace("/".root_filename.".php?","",$key),"DECODE",$_SESSION['domain_content_key'])){
			     				$post['content'] = str_replace(str_replace("/".root_filename.".php?","",$key),AuthCode(str_replace("/".root_filename.".php?","",$key),"ENCODE",$_SESSION['domain_content_key']),$post['content']);
			     			}
			     		}
			     	}
			      	$post['cate_pid'] = '';
			      	if(empty($post['repair_time'])){
		        		$post['repair_time'] = "否";
		        	} else {
		        		$post['repair_time'] = "是";
		        	}
			    } else {
	        		$this->json(['data'=>['url'=>"./".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])],'msg'=>'未找到此漏洞！']);
			    }
		      	$this->smarty->assign('post',$post);

	        	$classification = $db->query("select * from domain_classification");
	        	if($classification){
	        		foreach ($classification as $k => $v) {
				      	if($v['id'] == $post['cate_id']){
				        	$post['cate_pid'] = $v['pid'];
				      	}
				    }
	        	}
		      	$this->smarty->assign('classification',$classification);
		      	$this->smarty->assign('classification_json',json_encode($classification));

		      	$project_classification = $db->query("select id,title from domain_project_classification");
		      	$this->smarty->assign('project_classification',$project_classification);

		      	$token = md5(code().time().code());
		      	$_SESSION['token'] = $token;
		      	$this->smarty->assign('token',$token);
	        	$this->smarty->display('products/edit_index.tpl');
	      	} else {
	        	$this->json(['data'=>['url'=>"./".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])],'msg'=>'程序异常']);
	      	}
		}
	}

	/**
	 * 删除漏洞
	 * @access  public
	 * @return html
	 */
	public function del_index()
	{
		$this->log_db("用户访问删除漏洞","5");
 		$db = $this->Db();
	    if($_GET){
	      	$id = isset($_GET['id']) ? intval($_GET['id']) : '';
	      	$token = isset($_GET['token']) ? $_GET['token'] : '';
	      	$session_token = isset($_SESSION['token']) ? $_SESSION['token'] : '';
	      	#IF判断区域
	      	if(empty($id)) $this->json(['status'=>0,'msg'=>'输入ID！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	      	if(empty($token)) $this->json(['status'=>0,'msg'=>'输入token！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	      	if(empty($session_token)) $this->json(['status'=>0,'msg'=>'token异常！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	      	if($token != $session_token) $this->json(['status'=>0,'msg'=>'token验证失败！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	      	unset($_SESSION['token']);

	      	$sql = "DELETE from domain_post where `id` = :id";
	      	$db->bind("id", $id);
	      	if($_SESSION['userid'] != '1'){
			    $db->bind("user_id", $_SESSION['userid']);
				$sql .= " AND user_id = :user_id";
			}
	      	$info = $db->query($sql);
	      	if($info){
	        	$this->json(["status"=>1,"msg"=>"删除成功！","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	      	} else {
	        	$this->json(["status"=>0,"msg"=>"删除失败！","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	      	}
	    } else {
	      	$this->json(["status"=>0,"msg"=>"错误异常！","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	    }
	}

	/**
	 * 修复漏洞
	 * @access  public
	 * @return html
	 */
	public function repair_index()
	{
		$this->log_db("用户访问修复漏洞","6");
		$db = $this->Db();
		if($_POST){
			$id = isset($_POST['id']) ? intval($_POST['id']) : '';
	      	$token = isset($_POST['token']) ? $_POST['token'] : '';
	      	$content = isset($_POST['content']) ? $_POST['content'] : "";
	      	$session_token = isset($_SESSION['token']) ? $_SESSION['token'] : '';
	      	#IF判断区域
	      	if(empty($id)) $this->json(['status'=>0,'msg'=>'输入ID！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	      	if(empty($token)) $this->json(['status'=>0,'msg'=>'输入token！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	      	if(empty($session_token)) $this->json(['status'=>0,'msg'=>'token异常！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	      	if($token != $session_token) $this->json(['status'=>0,'msg'=>'token验证失败！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	      	unset($_SESSION['token']);
	      	if(empty($content)) $this->json(['status'=>0,'msg'=>'请输入修复结果！']);
	      	
	      	$db->bind("id", $id);
	      	$db->bind("repair_content", AuthCode(str_replace("截图、本地图片可直接复制粘贴进编辑器中", "", $content),"ENCODE",$_SESSION['domain_content_key']));
	        $db->bind("repair_time", time());
	      	$sql = "UPDATE domain_post SET `repair_content` = :repair_content,`repair_time` = :repair_time WHERE `id` = :id";
	        if($_SESSION['userid'] != '1'){
			    $db->bind("user_id", $_SESSION['userid']);
				$sql .= " AND user_id = :user_id";
			}
      		$info = $db->query($sql);
	        if($info){
	          	$this->json(["status"=>1,"msg"=>"修改成功！"]);
	        } else {
	          	$this->json(["status"=>0,"msg"=>"修改失败！"]);
	        }
		} else {
			$id = isset($_GET['id']) ? intval($_GET['id']) : '';
	      	if($id){
	      		$sql = "select id,title,content,bugDetail,repair_time from domain_post where id = :id";
	      		if($_SESSION['userid'] != '1'){
				    $db->bind("user_id", $_SESSION['userid']);
					$sql .= " AND user_id = :user_id";
				}
	        	$db->bind("id", $id);
	        	$post = $db->find_one($sql);
	        	if($post){
			      	if(empty($post['repair_time'])){
			      		$post['content'] = htmlspecialchars_decode(str_replace(["《","》"], ['<','>'], AuthCode($post['content'],"DECODE",$_SESSION['domain_content_key'])));
				     	preg_match_all("/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i",$post['content'],$match);
				     	if($match[0]){
				     		foreach (end($match) as $key) {
				     			if(!AuthCode(str_replace("/".root_filename.".php?","",$key),"DECODE",$_SESSION['domain_content_key'])){
				     				$post['content'] = str_replace(str_replace("/".root_filename.".php?","",$key),AuthCode(str_replace("/".root_filename.".php?","",$key),"ENCODE",$_SESSION['domain_content_key']),$post['content']);
				     			}
				     		}
				     	}
				      	$this->smarty->assign('post',$post);
				      	$token = md5(code().time().code());
				      	$_SESSION['token'] = $token;
				      	$this->smarty->assign('token',$token);
			        	$this->smarty->display('products/repair_index.tpl');
		        	} else {
		        		$this->json(['data'=>['url'=>"./".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])],'msg'=>'此漏洞已修复']);
		        	}
			    } else {
	        		$this->json(['data'=>['url'=>"./".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])],'msg'=>'未找到此漏洞！']);
			    }
	      	} else {
	        	$this->json(['data'=>['url'=>"./".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])],'msg'=>'程序异常']);
	      	}
		}
	}

	/**
	 * 查看修复结果
	 * @access  public
	 * @return html
	 */
	public function repair_view_index()
	{
		$this->log_db("用户访问查看修复结果","6");
		$db = $this->Db();
		$id = isset($_GET['id']) ? intval($_GET['id']) : '';
      	if($id){
      		$sql = "select id,title,content,bugDetail,repair_content from domain_post where id = :id";
      		if($_SESSION['userid'] != '1'){
			    $db->bind("user_id", $_SESSION['userid']);
				$sql .= " AND user_id = :user_id";
			}
        	$db->bind("id", $id);
        	$post = $db->find_one($sql);
        	if($post){
		     	$post['repair_content'] = htmlspecialchars_decode(str_replace(["《","》"], ['<','>'], AuthCode($post['repair_content'],"DECODE",$_SESSION['domain_content_key'])));
		     	preg_match_all("/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i",$post['repair_content'],$match);
		     	if($match[0]){
		     		foreach (end($match) as $key) {
		     			if(!AuthCode(str_replace("/".root_filename.".php?","",$key),"DECODE",$_SESSION['domain_content_key'])){
		     				$post['repair_content'] = str_replace(str_replace("/".root_filename.".php?","",$key),AuthCode(str_replace("/".root_filename.".php?","",$key),"ENCODE",$_SESSION['domain_content_key']),$post['repair_content']);
		     			}
		     		}
		     	}
		     	$post['content'] = htmlspecialchars_decode(str_replace(["《","》"], ['<','>'], AuthCode($post['content'],"DECODE",$_SESSION['domain_content_key'])));
		     	preg_match_all("/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i",$post['content'],$match);
		     	if($match[0]){
		     		foreach (end($match) as $key) {
		     			if(!AuthCode(str_replace("/".root_filename.".php?","",$key),"DECODE",$_SESSION['domain_content_key'])){
		     				$post['content'] = str_replace(str_replace("/".root_filename.".php?","",$key),AuthCode(str_replace("/".root_filename.".php?","",$key),"ENCODE",$_SESSION['domain_content_key']),$post['content']);
		     			}
		     		}
		     	}
		      	$this->smarty->assign('post',$post);
	        	$this->smarty->display('products/repair_view_index.tpl');
		    } else {
        		$this->json(['data'=>['url'=>"./".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])],'msg'=>'未找到此漏洞！']);
		    }
      	} else {
        	$this->json(['data'=>['url'=>"./".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])],'msg'=>'程序异常']);
      	}
	}

	/**
	 * 下载漏洞报告
	 * @access  public
	 * @return html
	 */
  	public function download_index()
  	{
  		$this->jurisdiction("非法访问下载漏洞报告");
		$this->log_db("用户访问下载漏洞报告","7");
	    $db = $this->Db();
	    if($_POST){
    		$id = isset($_POST['id']) ? explode(",", $_POST['id']) : '';
		    $path = isset($_POST['path']) ? $_POST['path'] : '';
			$token = isset($_POST['token']) ? $_POST['token'] : '';
	      	$session_token = isset($_SESSION['token']) ? $_SESSION['token'] : '';
	      	#IF判断区域
     	 	if(empty($id)) $this->json(['status'=>0,'msg'=>'输入ID！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	      	if(empty($path)) $this->json(['status'=>0,'msg'=>'输入模板ID！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	      	if(empty($token)) $this->json(['status'=>0,'msg'=>'输入token！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	      	if(empty($session_token)) $this->json(['status'=>0,'msg'=>'token异常！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	      	if($token != $session_token) $this->json(['status'=>0,'msg'=>'token验证失败！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	      	unset($_SESSION['token']);

	      	$token = md5(code().time().code());
	      	$_SESSION['token'] = $token;

      		$this->json(['status'=>1,'msg'=>'正在导出！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=download_index&id=".implode(",", $id)."&token=".$token."&path=".$path,"ENCODE",$_SESSION['domain_key'])]]);
    	}
	    if($_GET){
	      	$path = isset($_GET['path']) ? $_GET['path'] : '';
	      	$id = isset($_GET['id']) ? explode(",", $_GET['id']) : '';
	      	$token = isset($_GET['token']) ? $_GET['token'] : '';
	      	$session_token = isset($_SESSION['token']) ? $_SESSION['token'] : '';
	      	#IF判断区域
	      	if(empty($id)) $this->json(['status'=>0,'msg'=>'输入ID！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	      	if(empty($path)) $this->json(['status'=>0,'msg'=>'输入模板ID！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	      	if(empty($token)) $this->json(['status'=>0,'msg'=>'输入token！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	      	if(empty($session_token)) $this->json(['status'=>0,'msg'=>'token异常！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	      	if($token != $session_token) $this->json(['status'=>0,'msg'=>'token验证失败！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	      	unset($_SESSION['token']);

	      	$db = $this->Db();
	      	$db->bind("uuid", $path);
			$list = $db->find_one("select * from domain_template WHERE uuid = :uuid");
			if($list){
				$file_path_name = basename($list['file_path']);
				if(file_exists(ROOT_PATH."/python_web/template/".$file_path_name)){
					if(!is_array($id)) $this->json(['status'=>0,'msg'=>'程序异常！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
					$sql_where = "";
		    		foreach ($id as $k => $v) {
		    			$sql_where .= ":session".$k.",";
		    			$db->bind("session".$k, $v);
		    		}
		    		$sql = "select a.title,a.content,a.bugLevel,a.bugDetail,a.description,a.suggestions,b.title as realname,b.id,c.title as cate_id from domain_post as a left join domain_project_classification as b on a.company = b.id left join domain_classification as c on a.cate_id = c.id where a.session in ( ".trim($sql_where,",")." )";
		    		if($_SESSION['userid'] != '1'){
					    $db->bind("user_id", $_SESSION['userid']);
						$sql .= " AND a.user_id = :user_id";
					}
					$post = $db->query($sql);
					if($post){
						// 公司数组
						$company = [];
						// URL数组
						$hostlist = [];

						$url = [];
						
						$vulnerability_types = [];

						$zong_shu = [];

						$risk_level = '';
						$high = 0;
						$medium = 0;
						$low = 0;
						$serious = 0;

						// 临时图片存储
						$tmp_img = [];

						// 初始默认设置 漏洞等级
						foreach ($post as $k => $v) {
							$zong_shu[$v['realname']] = ['serious'=>0,'high'=>0,'medium'=>0,'low'=>0];
						} 

						foreach ($post as $k => $v) {
						    $v['content'] = htmlspecialchars_decode(str_replace(["《","》","截图、本地图片可直接复制粘贴进编辑器中"], ['<','>',''], AuthCode($v['content'],"DECODE",$_SESSION['domain_content_key'])));

						    preg_match_all("/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i",$v['content'],$match);
					     	if($match[0]){
					     		foreach (end($match) as $key) {
					     			if(!AuthCode(str_replace("/".root_filename.".php?","",$key),"DECODE",$_SESSION['domain_content_key'])){
					     				$post['content'] = str_replace(str_replace("/".root_filename.".php?","",$key),AuthCode(str_replace("/".root_filename.".php?","",$key),"ENCODE",$_SESSION['domain_content_key']),$post['content']);
					     			}
					     		}
					     	}
						    preg_match_all("/<p[^>]*>\s*.*\s*<\/p>/isU",$v['content'],$match);
						    $array = [];
						    foreach ($match[0] as $ks => $vs) {
						    	preg_match_all("/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i",$vs,$match);
						    	if($match[0]){
						    		foreach (end($match) as $key) {
						    			$img = @AuthCode(explode("/".root_filename.".php?", $key)[1],"DECODE",$_SESSION['domain_content_key']);
						    			if($img){
							    			$path = ROOT_PATH."/public/auto/".str_replace("m=Public&a=enup_img&id=", "", $img);
							    			if(file_exists($path)){
							    				@file_put_contents(ROOT_PATH."/python_web/tmp/".str_replace("m=Public&a=enup_img&id=", "", $img).".png",@binary_decode(@file_get_contents($path),str_replace("m=Public&a=enup_img&id=", "", $img)));
							    				$tmp_img[] = ROOT_PATH."/python_web/tmp/".str_replace("m=Public&a=enup_img&id=", "", $img).".png";
							    				$array[] = ['text'=>ROOT_PATH."/python_web/tmp/".str_replace("m=Public&a=enup_img&id=", "", $img).".png",'type'=>1];
							    			}
						    			}
						    		}
						    	} else {
						    	  $array[] = ['text'=>strip_tags($vs),'type'=>2];
						    	}
						    }
						    if($v['bugLevel'] == 2){
						    	$zong_shu[$v['realname']]['low'] += 1;
						    	$low += 1;
						    } elseif($v['bugLevel'] == 3){
						    	$zong_shu[$v['realname']]['medium'] += 1;
						    	$medium += 1;
						    } elseif($v['bugLevel'] == 4){
						    	$zong_shu[$v['realname']]['high'] += 1;
						    	$high += 1;
						    } elseif($v['bugLevel'] == 5){
						    	$zong_shu[$v['realname']]['serious'] += 1;
						    	$serious += 1;
						    }
						    $post[$k]['content'] = $v['content'] = $array;
						    @$company[$v['realname']][] = [
						    	'id'=>count($company[$v['realname']])+1,
						    	'pathname'=>$v['title'],
						    	'name'=>$v['title'],
						    	'repair_time'=>3,
						    	'level'=>intval($v['bugLevel']),
						    	'url'=>$v['bugDetail'],
						    	'analysis'=>$v['description'],
						    	'verification'=>$array,
						    	'suggestions'=>$v['suggestions'],
						    ];
						    @$hostlist[$v['realname']][] = ['id'=>count($hostlist[$v['realname']])+1,'url'=>$v['bugDetail'],'name'=>$v['title'],'type'=>$v['cate_id'],'bugLevel'=>$v['bugLevel']];
						    @$vulnerability_types[$v['realname']][] = $v['cate_id'];
						    $url[] = @getTopHost($v['bugDetail']);
				   		}
				   		if($serious>=1 || $high >= 3){
				   			$risk_level = "极度风险";
				   		} elseif ($high > 0 || $medium >= 5) {
				   			$risk_level = "严重风险";
				   		} elseif ($medium >= 2) {
				   			$risk_level = "严重隐患";
				   		}  else {
				   			$risk_level = "一般隐患";
				   		}

					   	$alerts = [];
					   	foreach ($company as $k => $v) {
					   	 	$alerts[$k] = [
					   	 		'name'=>$k,
					   	 		'path'=>$v,
					   	 	];
					   	}
					   	foreach ($url as $k => $v) {
					   		$url[$k] = "*.".$v;
					   	}
					   	$url = array_unique($url);
						reset($company);
					   	include_once ROOT_PATH."/lib/socket/stockConnector.php";
					   	$file_path = [];
						foreach ($company as $k => $v) {
							$zong_data = [
						    	'name'=>$k,
						    	'doctype'=>1,
						    	'time'=>date("Y年m月d日"),
						    	'producer'=>isset($_SESSION['name']) ? $_SESSION['name'] : 'admin',
						    	'producer_time'=>date("Y.m.d"),
						    	'reviewer'=>"",
						    	'reviewer_time'=>"",
						    	'url'=>implode(",", $url),
						    	'hostlist'=>$hostlist[$k],
						    	'alerts'=>[$alerts[$k]],
						    	'low'=>$zong_shu[$k]['low'],
						    	'medium'=>$zong_shu[$k]['medium'],
						    	'high'=>$zong_shu[$k]['high'],
						    	'serious'=>$zong_shu[$k]['serious'],
						    	'risk_level'=>$risk_level,
						    	'common'=>$zong_shu[$k]['low']+$zong_shu[$k]['medium']+$zong_shu[$k]['high']+$zong_shu[$k]['serious'],
						    	'vulnerability_types'=>implode(",", array_unique($vulnerability_types[$k])),
						    ];
						    $code = code();
						    @file_put_contents(ROOT_PATH."/python_web/tmp/".$code.".json", json_encode($zong_data));
						    try {
								$sw = new stockConnector(isset($_SESSION['system_config']['socket_ip'])?$_SESSION['system_config']['socket_ip']:'127.0.0.1',"5678");
								$aa = ["path"=>ROOT_PATH."/python_web/tmp/".$code.".json","name"=>$k."安全测试报告".date("Y-m-d"),'template_path'=>ROOT_PATH."/python_web/template/".$file_path_name];
								$con = @$sw->sendMsg(json_encode($aa));
								$ret = @$sw->getMsg();
								if($ret){
									$file_dir = ROOT_PATH."/python_web/tmp/".json_decode($ret,true)['path'];
									$file_path[] = $file_dir;
								}
							} catch (Exception $e) {
								img_unlik($tmp_img);
			      				$this->json(["status"=>0,"msg"=>"系统异常：".$e->getMessage(),"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
							}
							@unlink(ROOT_PATH."/python_web/tmp/".$code.".json");
						}

						if(count($file_path) <= 0){
							img_unlik($tmp_img);$this->json(["status"=>0,"msg"=>"系统异常：导出报告服务错误！","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
						}

						// 记录导出时间
						$sql_where = "";
			    		foreach ($id as $k => $v) {
			    			$sql_where .= ":session".$k.",";
			    			$db->bind("session".$k, $v);
			    		}
						$db->bind("export_time", time());
			    		$sql = "UPDATE domain_post SET `export_time` = :export_time WHERE session in ( ".trim($sql_where,",")." )";
			    		if($_SESSION['userid'] != '1'){
						    $db->bind("user_id", $_SESSION['userid']);
							$sql .= " AND user_id = :user_id";
						}
						$db->query($sql);


					    // 文件下载
						if(count($file_path) <= 1){
							img_unlik($tmp_img);
							$file_path = array_shift($file_path);
							downloadFile($file_path,basename($file_path));
							$this->log_db("用户成功下载漏洞报告：".filterWords(basename($file_path)),"9");
							@unlink($file_path);
						} else {
							$this->log_db("用户成功下载漏洞报告：".filterWords('共'.count($file_path).'报告'.date("Y-m-d").'.zip'),"9");
							$name = '共'.count($file_path).'报告'.date("Y-m-d").'.zip';
							$filename = ROOT_PATH."/python_web/tmp/".$name; //最终生成的文件名
							if(!is_dir(dirname($filename))){
							　　mkdir(dirname($filename), 0777, true);
							}
							$zip = new \ZipArchive();
							if($zip->open($filename,\ZIPARCHIVE::CREATE)!==TRUE){
								img_unlik($tmp_img);
			      				$this->json(["status"=>0,"msg"=>"无法打开文件，或者文件创建失败","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
							}
							foreach ($file_path as $value) {
							    $fileData = @file_get_contents($value);
							    if ($fileData) {
							        $zip->addFromString(basename($value), $fileData);
							    }
							}
							$zip->close();//关闭
							downloadFile($filename,$name);
							foreach ($file_path as $value) {
								@unlink($value);
							}
							@unlink($filename);
							img_unlik($tmp_img);
						}
					} else {
	      				$this->json(["status"=>0,"msg"=>"漏洞不存在！","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
					}
				} else {
	      			$this->json(["status"=>0,"msg"=>"模板文件不存在！","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
				}
			} else {
	      		$this->json(["status"=>0,"msg"=>"模板id不存在！","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
			}
	    } else {
	      	$this->json(["status"=>0,"msg"=>"错误异常！","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	    }
	}

	/**
	 * 下载复测漏洞报告
	 * @access  public
	 * @return html
	 */	
	public function repair_download_index()
	{
		$this->jurisdiction("非法访问下载漏洞报告");
		$this->log_db("用户访问下载漏洞报告","7");
	    $db = $this->Db();
	    if($_POST){
    		$id = isset($_POST['id']) ? explode(",", $_POST['id']) : '';
		    $path = isset($_POST['path']) ? $_POST['path'] : '';
			$token = isset($_POST['token']) ? $_POST['token'] : '';
	      	$session_token = isset($_SESSION['token']) ? $_SESSION['token'] : '';
	      	#IF判断区域
     	 	if(empty($id)) $this->json(['status'=>0,'msg'=>'输入ID！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	      	if(empty($path)) $this->json(['status'=>0,'msg'=>'输入模板ID！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	      	if(empty($token)) $this->json(['status'=>0,'msg'=>'输入token！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	      	if(empty($session_token)) $this->json(['status'=>0,'msg'=>'token异常！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	      	if($token != $session_token) $this->json(['status'=>0,'msg'=>'token验证失败！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	      	unset($_SESSION['token']);

	      	$token = md5(code().time().code());
	      	$_SESSION['token'] = $token;

      		$this->json(['status'=>1,'msg'=>'正在导出！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=repair_download_index&id=".implode(",", $id)."&token=".$token."&path=".$path,"ENCODE",$_SESSION['domain_key'])]]);
    	}
	    if($_GET){
	      	$path = isset($_GET['path']) ? $_GET['path'] : '';
	      	$id = isset($_GET['id']) ? explode(",", $_GET['id']) : '';
	      	$token = isset($_GET['token']) ? $_GET['token'] : '';
	      	$session_token = isset($_SESSION['token']) ? $_SESSION['token'] : '';
	      	#IF判断区域
	      	if(empty($id)) $this->json(['status'=>0,'msg'=>'输入ID！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	      	if(empty($path)) $this->json(['status'=>0,'msg'=>'输入模板ID！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	      	if(empty($token)) $this->json(['status'=>0,'msg'=>'输入token！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	      	if(empty($session_token)) $this->json(['status'=>0,'msg'=>'token异常！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	      	if($token != $session_token) $this->json(['status'=>0,'msg'=>'token验证失败！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	      	unset($_SESSION['token']);

	      	$db = $this->Db();
	      	$db->bind("uuid", $path);
			$list = $db->find_one("select * from domain_template WHERE uuid = :uuid");
			if($list){
				$file_path_name = basename($list['file_path']);
				if(file_exists(ROOT_PATH."/python_web/template/".$file_path_name)){
					if(!is_array($id)) $this->json(['status'=>0,'msg'=>'程序异常！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
			      	$sql_where = "";
		    		foreach ($id as $k => $v) {
		    			$sql_where .= ":session".$k.",";
		    			$db->bind("session".$k, $v);
		    		}
		    		$sql = "select a.title,a.content,a.bugLevel,a.bugDetail,a.description,a.suggestions,b.title as realname,b.id,c.title as cate_id,a.repair_content,a.repair_time from domain_post as a left join domain_project_classification as b on a.company = b.id left join domain_classification as c on a.cate_id = c.id where a.session in ( ".trim($sql_where,",")." )";
		    		if($_SESSION['userid'] != '1'){
					    $db->bind("user_id", $_SESSION['userid']);
						$sql .= " AND a.user_id = :user_id";
					}
					$post = $db->query($sql);
					if($post){
						// 公司数组
						$company = [];
						// URL数组
						$hostlist = [];

						$url = [];
						
						$vulnerability_types = [];

						$zong_shu = [];

						$risk_level = '';
						$high = 0;
						$medium = 0;
						$low = 0;
						$serious = 0;

						// 临时图片存储
						$tmp_img = [];

						// 初始默认设置 漏洞等级
						foreach ($post as $k => $v) {
							$zong_shu[$v['realname']] = ['serious'=>0,'high'=>0,'medium'=>0,'low'=>0];
						}

						foreach ($post as $k => $v) {
							if(empty($v['repair_time'])){
								$v['content'] = htmlspecialchars_decode(str_replace(["《","》","截图、本地图片可直接复制粘贴进编辑器中"], ['<','>',''], AuthCode($v['content'],"DECODE",$_SESSION['domain_content_key'])));

							    preg_match_all("/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i",$v['content'],$match);
						     	if($match[0]){
						     		foreach (end($match) as $key) {
						     			if(!AuthCode(str_replace("/".root_filename.".php?","",$key),"DECODE",$_SESSION['domain_content_key'])){
						     				$post['content'] = str_replace(str_replace("/".root_filename.".php?","",$key),AuthCode(str_replace("/".root_filename.".php?","",$key),"ENCODE",$_SESSION['domain_content_key']),$post['content']);
						     			}
						     		}
						     	}
							    preg_match_all("/<p[^>]*>\s*.*\s*<\/p>/isU",$v['content'],$match);
							    $array = [];
							    foreach ($match[0] as $ks => $vs) {
							    	preg_match_all("/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i",$vs,$match);
							    	if($match[0]){
							    		foreach (end($match) as $key) {
							    			$img = @AuthCode(explode("/".root_filename.".php?", $key)[1],"DECODE",$_SESSION['domain_content_key']);
							    			if($img){
								    			$path = ROOT_PATH."/public/auto/".str_replace("m=Public&a=enup_img&id=", "", $img);
								    			if(file_exists($path)){
								    				@file_put_contents(ROOT_PATH."/python_web/tmp/".str_replace("m=Public&a=enup_img&id=", "", $img).".png",@binary_decode(@file_get_contents($path),str_replace("m=Public&a=enup_img&id=", "", $img)));
								    				$tmp_img[] = ROOT_PATH."/python_web/tmp/".str_replace("m=Public&a=enup_img&id=", "", $img).".png";
								    				$array[] = ['text'=>ROOT_PATH."/python_web/tmp/".str_replace("m=Public&a=enup_img&id=", "", $img).".png",'type'=>1];
								    			}
							    			}
							    		}
							    	} else {
							    	  $array[] = ['text'=>strip_tags($vs),'type'=>2];
							    	}
							    }
							} else {
							    $v['repair_content'] = htmlspecialchars_decode(str_replace(["《","》","截图、本地图片可直接复制粘贴进编辑器中"], ['<','>',''], AuthCode($v['repair_content'],"DECODE",$_SESSION['domain_content_key'])));

							    preg_match_all("/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i",$v['repair_content'],$match);
						     	if($match[0]){
						     		foreach (end($match) as $key) {
						     			if(!AuthCode(str_replace("/".root_filename.".php?","",$key),"DECODE",$_SESSION['domain_content_key'])){
						     				$post['repair_content'] = str_replace(str_replace("/".root_filename.".php?","",$key),AuthCode(str_replace("/".root_filename.".php?","",$key),"ENCODE",$_SESSION['domain_content_key']),$post['repair_content']);
						     			}
						     		}
						     	}
							    preg_match_all("/<p[^>]*>\s*.*\s*<\/p>/isU",$v['repair_content'],$match);
							    $array = [];
							    foreach ($match[0] as $ks => $vs) {
							    	preg_match_all("/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i",$vs,$match);
							    	if($match[0]){
							    		foreach (end($match) as $key) {
							    			$img = @AuthCode(explode("/".root_filename.".php?", $key)[1],"DECODE",$_SESSION['domain_content_key']);
							    			if($img){
								    			$path = ROOT_PATH."/public/auto/".str_replace("m=Public&a=enup_img&id=", "", $img);
								    			if(file_exists($path)){
								    				@file_put_contents(ROOT_PATH."/python_web/tmp/".str_replace("m=Public&a=enup_img&id=", "", $img).".png",@binary_decode(@file_get_contents($path),str_replace("m=Public&a=enup_img&id=", "", $img)));
								    				$tmp_img[] = ROOT_PATH."/python_web/tmp/".str_replace("m=Public&a=enup_img&id=", "", $img).".png";
								    				$array[] = ['text'=>ROOT_PATH."/python_web/tmp/".str_replace("m=Public&a=enup_img&id=", "", $img).".png",'type'=>1];
								    			}
							    			}
							    		}
							    	} else {
							    	  $array[] = ['text'=>strip_tags($vs),'type'=>2];
							    	}
							    }
							}
							if(empty($v['repair_time'])){
							    if($v['bugLevel'] == 2){
							    	$zong_shu[$v['realname']]['low'] += 1;
							    	$low += 1;
							    } elseif($v['bugLevel'] == 3){
							    	$zong_shu[$v['realname']]['medium'] += 1;
							    	$medium += 1;
							    } elseif($v['bugLevel'] == 4){
							    	$zong_shu[$v['realname']]['high'] += 1;
							    	$high += 1;
							    } elseif($v['bugLevel'] == 5){
							    	$zong_shu[$v['realname']]['serious'] += 1;
							    	$serious += 1;
							    }
							}
						    $post[$k]['repair_content'] = $v['repair_content'] = $array;
						    @$company[$v['realname']][] = [
						    	'id'=>count($company[$v['realname']])+1,
						    	'pathname'=>$v['title'],
						    	'name'=>$v['title'],
						    	'repair_time'=>empty($v['repair_time']) ? 1 : 2,
						    	'level'=>intval($v['bugLevel']),
						    	'url'=>$v['bugDetail'],
						    	'analysis'=>$v['description'],
						    	'verification'=>$array,
						    	'suggestions'=>$v['suggestions'],
						    ];
						    if(empty($v['repair_time'])){
							    @$hostlist[$v['realname']][] = ['id'=>count($hostlist[$v['realname']])+1,'url'=>$v['bugDetail'],'name'=>$v['title'],'type'=>$v['cate_id'],'bugLevel'=>$v['bugLevel']];
						    	@$vulnerability_types[$v['realname']][] = $v['cate_id'];
							}
						    $url[] = @getTopHost($v['bugDetail']);
				   		}
				   		if($serious>=1 || $high >= 3){
				   			$risk_level = "极度风险";
				   		} elseif ($high > 0 || $medium >= 5) {
				   			$risk_level = "严重风险";
				   		} elseif ($medium >= 2) {
				   			$risk_level = "严重隐患";
				   		}  else {
				   			$risk_level = "一般隐患";
				   		}

					   	$alerts = [];
					   	foreach ($company as $k => $v) {
					   	 	$alerts[$k] = [
					   	 		'name'=>$k,
					   	 		'path'=>$v,
					   	 	];
					   	}
					   	foreach ($url as $k => $v) {
					   		$url[$k] = "*.".$v;
					   	}
					   	$url = array_unique($url);
						reset($company);
					   	include_once ROOT_PATH."/lib/socket/stockConnector.php";
					   	$file_path = [];
						foreach ($company as $k => $v) {
							$zong_data = [
						    	'name'=>$k,
						    	'doctype'=>2,
						    	'time'=>date("Y年m月d日"),
						    	'producer'=>isset($_SESSION['name']) ? $_SESSION['name'] : 'admin',
						    	'producer_time'=>date("Y.m.d"),
						    	'reviewer'=>"",
						    	'reviewer_time'=>"",
						    	'url'=>implode(",", $url),
						    	'hostlist'=>$hostlist[$k],
						    	'alerts'=>[$alerts[$k]],
						    	'low'=>$zong_shu[$k]['low'],
						    	'medium'=>$zong_shu[$k]['medium'],
						    	'high'=>$zong_shu[$k]['high'],
						    	'serious'=>$zong_shu[$k]['serious'],
						    	'risk_level'=>$risk_level,
						    	'common'=>$zong_shu[$k]['low']+$zong_shu[$k]['medium']+$zong_shu[$k]['high']+$zong_shu[$k]['serious'],
						    	'vulnerability_types'=>implode(",", array_unique($vulnerability_types[$k])),
						    ];
						    $code = code();
						    @file_put_contents(ROOT_PATH."/python_web/tmp/".$code.".json", json_encode($zong_data));
						    try {
								$sw = new stockConnector(isset($_SESSION['system_config']['socket_ip'])?$_SESSION['system_config']['socket_ip']:'127.0.0.1',"5678");
								$aa = ["path"=>ROOT_PATH."/python_web/tmp/".$code.".json","name"=>$k."复测报告".date("Y-m-d"),'template_path'=>ROOT_PATH."/python_web/template/".$file_path_name];
								$con = @$sw->sendMsg(json_encode($aa));
								$ret = @$sw->getMsg();
								if($ret){
									$file_dir = ROOT_PATH."/python_web/tmp/".json_decode($ret,true)['path'];
									$file_path[] = $file_dir;
								}
							} catch (Exception $e) {
								img_unlik($tmp_img);
			      				$this->json(["status"=>0,"msg"=>"系统异常：".$e->getMessage(),"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
							}
							@unlink(ROOT_PATH."/python_web/tmp/".$code.".json");
						}

						if(count($file_path) <= 0){
							img_unlik($tmp_img);$this->json(["status"=>0,"msg"=>"系统异常：导出报告服务错误！","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
						}

						// 记录导出时间
						$sql_where = "";
			    		foreach ($id as $k => $v) {
			    			$sql_where .= ":session".$k.",";
			    			$db->bind("session".$k, $v);
			    		}
						$db->bind("export_time", time());
			    		$sql = "UPDATE domain_post SET `export_time` = :export_time WHERE session in ( ".trim($sql_where,",")." )";
			    		if($_SESSION['userid'] != '1'){
						    $db->bind("user_id", $_SESSION['userid']);
							$sql .= " AND user_id = :user_id";
						}
						$db->query($sql);

					    // 文件下载
						if(count($file_path) <= 1){
							img_unlik($tmp_img);
							$file_path = array_shift($file_path);
							downloadFile($file_path,basename($file_path));
							$this->log_db("用户成功下载漏洞报告：".filterWords(basename($file_path)),"9");
							@unlink($file_path);
						} else {
							$this->log_db("用户成功下载漏洞报告：".filterWords('共'.count($file_path).'报告'.date("Y-m-d").'.zip'),"9");
							$name = '共'.count($file_path).'报告'.date("Y-m-d").'.zip';
							$filename = ROOT_PATH."/python_web/tmp/".$name; //最终生成的文件名
							if(!is_dir(dirname($filename))){
							　　mkdir(dirname($filename), 0777, true);
							}
							$zip = new \ZipArchive();
							if($zip->open($filename,\ZIPARCHIVE::CREATE)!==TRUE){
								img_unlik($tmp_img);
			      				$this->json(["status"=>0,"msg"=>"无法打开文件，或者文件创建失败","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
							}
							foreach ($file_path as $value) {
							    $fileData = @file_get_contents($value);
							    if ($fileData) {
							        $zip->addFromString(basename($value), $fileData);
							    }
							}
							$zip->close();//关闭
							downloadFile($filename,$name);
							foreach ($file_path as $value) {
								@unlink($value);
							}
							@unlink($filename);
							img_unlik($tmp_img);
						}
					} else {
	      				$this->json(["status"=>0,"msg"=>"漏洞不存在！","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
					}
				}
			} else {
	      		$this->json(["status"=>0,"msg"=>"模板id不存在！","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
			}
	    } else {
	      	$this->json(["status"=>0,"msg"=>"错误异常！","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=index","ENCODE",$_SESSION['domain_key'])]]);
	    }
	}
	
	/**
	 * 项目分类
	 * @access  public
	 * @return html
	 */
	public function classification()
	{
		$this->jurisdiction("非法访问项目分类");
		$this->log_db("用户访问项目分类","7");
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
			$sql = "SELECT * FROM domain_project_classification limit ".$start.",".$length;
			$count = "SELECT count(*) as num FROM domain_project_classification";
			if($value){
				$sql = "SELECT * FROM domain_project_classification WHERE title LIKE :title limit ".$start.",".$length;
			  	$db->bind("title", "%".$value."%");
			}
			$list = $db->query($sql);
			if($list){
	        	foreach ($list as $k => $v) {
	          		$list[$k]['creation_time'] = $v['creation_time'] == 0 ? '-' : date("Y-m-d H:i:s",$v['creation_time']);
	          		$list[$k]['update_time'] = $v['update_time'] == 0 ? '-' : date("Y-m-d H:i:s",$v['update_time']);
	          		$db->bind("company", $v['id']);
					$post_count = "SELECT count(*) as num FROM domain_post WHERE company = :company";
					$post_count = $db->find_one($post_count);
	          		$list[$k]['num'] = isset($post_count['num']) ? $post_count['num'] : 0;;
	          		$db->bind("company", $v['id']);
	          		$repair_post_count = "SELECT count(*) as num FROM domain_post WHERE company = :company AND repair_content != ''";
					$repair_post_count = $db->find_one($repair_post_count);
	          		$list[$k]['repair_num'] = isset($repair_post_count['num']) ? $repair_post_count['num'] : 0;;

	          		$list[$k]['edit_classification_id'] = "./".root_filename.".php?".AuthCode("m=Products&a=edit_classification&id=".$v['id'],"ENCODE",$_SESSION['domain_key']);
		        	$list[$k]['del_classification_id'] = "./".root_filename.".php?".AuthCode("m=Products&a=del_classification&id=".$v['id']."&token=".$token,"ENCODE",$_SESSION['domain_key']);
		        	$list[$k]['see_classification_id'] = "./".root_filename.".php?".AuthCode("m=Products&a=see_classification&id=".$v['id'],"ENCODE",$_SESSION['domain_key']);
		        	$list[$k]['chart_classification_id'] = "./".root_filename.".php?".AuthCode("m=Products&a=chart_classification&id=".$v['id']."&token=".$token,"ENCODE",$_SESSION['domain_key']);
	        	}
	      	}

	      	if($value){
				$count = "SELECT count(*) as num FROM domain_project_classification WHERE title LIKE :title";
			  	$db->bind("title", "%".$value."%");
			}
      		$classification_count = $db->find_one($count);
      		$classification_num = isset($classification_count['num']) ? $classification_count['num'] : 0;
	      	$this->json(["draw"=>$draw,"recordsTotal"=>$classification_num,"recordsFiltered"=>$classification_num,"data"=>$list]);
	    } else {
	    	$this->smarty->display('products/classification.tpl');
	    }
	}

	/**
	 * 添加项目分类
	 * @access  public
	 * @return html
	 */
	public function add_classification()
	{
		$this->jurisdiction("非法访问添加项目分类");
		$this->log_db("用户访问添加项目分类","4");

		$db = $this->Db();
		if($_POST){
		    $pid = isset($_POST['pid']) ? intval($_POST['pid']) : '0';
		    $name = isset($_POST['name']) ? $_POST['name'] : '';
			$token = isset($_POST['token']) ? $_POST['token'] : '';
	      	$session_token = isset($_SESSION['token']) ? $_SESSION['token'] : '';
	      	#IF判断区域
     	 	if(empty($name)) $this->json(['status'=>0,'msg'=>'输入分类名称！']);
	      	if(empty($token)) $this->json(['status'=>0,'msg'=>'输入token！']);
	      	if(empty($session_token)) $this->json(['status'=>0,'msg'=>'token异常！']);
	      	if($token != $session_token) $this->json(['status'=>0,'msg'=>'token验证失败！']);
	      	unset($_SESSION['token']);

	      	$set = [];
			$set_val = [];
			if(!empty($name)){
			  	$set[] = "title";
			  	$set_val[] = ":title";
			  	$db->bind("title", $name);
			}
			if(!empty($pid)){
			  	$set[] = "pid";
			  	$set_val[] = ":pid";
			  	$db->bind("pid", $pid);
			}
			$set[] = "creation_time";
			$set[] = "update_time";
			$set_val[] = ":creation_time";
			$set_val[] = ":update_time";
			$db->bind("creation_time", time());
			$db->bind("update_time", time());

			$set = implode(",",$set);
      		$set_val = implode(",",$set_val);
      		$info = $db->query("INSERT INTO domain_project_classification($set) VALUES ($set_val)");
      		if($info){
        		$this->json(["status"=>1,"msg"=>"添加成功！"]);
      		} else {
        		$this->json(["status"=>0,"msg"=>"添加失败！"]);
      		}
		} else {
			$list = $db->query("select id,title from domain_project_classification where pid = '0'");
	      	$this->smarty->assign('list',$list);
	      	$token = md5(code().time().code());
	      	$_SESSION['token'] = $token;
	      	$this->smarty->assign('token',$token);
	    	$this->smarty->display('products/add_classification.tpl');
		}
	}

	/**
	 * 编辑项目分类
	 * @access  public
	 * @return html
	 */
	public function edit_classification()
	{
		$this->jurisdiction("非法访问编辑项目分类");
		$this->log_db("用户访问编辑项目分类","6");

		$db = $this->Db();
    	if($_POST){
			$title = isset($_POST['title']) ? $_POST['title'] : '';
			$name = isset($_POST['name']) ? $_POST['name'] : '';
			$id = isset($_POST['id']) ? intval($_POST['id']) : '';
			$pid = isset($_POST['pid']) ? intval($_POST['pid']) : '0';
			$token = isset($_POST['token']) ? $_POST['token'] : '';
	      	$session_token = isset($_SESSION['token']) ? $_SESSION['token'] : '';
			#IF判断区域
			if(empty($name)) $this->json(['status'=>0,'msg'=>'输入分类别名！']);
			if(empty($id)) $this->json(['status'=>0,'msg'=>'输入ID！']);
			if(empty($token)) $this->json(['status'=>0,'msg'=>'输入token！']);
	      	if(empty($session_token)) $this->json(['status'=>0,'msg'=>'token异常！']);
	      	if($token != $session_token) $this->json(['status'=>0,'msg'=>'token验证失败！']);
	      	unset($_SESSION['token']);

	      	$set = [];
			$db = $this->Db();
			$db->bind("id", $id);
			if(!empty($name)){
				$set[] = "`title` = :title";
				$db->bind("title", $name);
			}
			if(!empty($pid)){
				$set[] = "`pid` = :pid";
			  	$db->bind("pid", $pid);
			}
			$set[] = "`update_time` = :update_time";
			$db->bind("update_time", time());

			$set = implode(", ",$set);
			if(empty($set)) $this->json(["status"=>0,"msg"=>"修改失败！"]);

			$info = $db->query("UPDATE domain_project_classification SET $set WHERE `id` = :id");
			if($info){
				$this->json(["status"=>1,"msg"=>"修改成功！"]);
			} else {
				$this->json(["status"=>0,"msg"=>"修改失败！"]);
			}
    	} else {
    		$id = isset($_GET['id']) ? intval($_GET['id']) : '';
	      	if($id){
	        	$db->bind("id", $id);
	        	$classification = $db->find_one("select * from domain_project_classification where id = :id");
				$list = $db->query("select id,title from domain_project_classification where pid = '0'");
		      	$this->smarty->assign('list',$list);
	        	$token = md5(code().time().code());
		      	$_SESSION['token'] = $token;
		      	$this->smarty->assign('token',$token);
	        	$this->smarty->assign('classification',$classification);
	        	$this->smarty->display('products/edit_classification.tpl');
	      	} else {
	        	$this->json(['data'=>['url'=>"./".root_filename.".php?".AuthCode("m=Products&a=classification","ENCODE",$_SESSION['domain_key'])],'msg'=>'程序异常']);
	      	}
    	}
	}

	/**
	 * 删除项目分类
	 * @access  public
	 * @return html
	 */
	public function del_classification()
	{
		$this->jurisdiction("非法访问删除项目分类");
		$this->log_db("用户访问删除项目分类","5");

 		$db = $this->Db();
	    if($_GET){
	      	$id = isset($_GET['id']) ? intval($_GET['id']) : '';
	      	$token = isset($_GET['token']) ? $_GET['token'] : '';
	      	$session_token = isset($_SESSION['token']) ? $_SESSION['token'] : '';
	      	#IF判断区域
	      	if(empty($id)) $this->json(['status'=>0,'msg'=>'输入ID！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=classification","ENCODE",$_SESSION['domain_key'])]]);
	      	if(empty($token)) $this->json(['status'=>0,'msg'=>'输入token！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=classification","ENCODE",$_SESSION['domain_key'])]]);
	      	if(empty($session_token)) $this->json(['status'=>0,'msg'=>'token异常！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=classification","ENCODE",$_SESSION['domain_key'])]]);
	      	if($token != $session_token) $this->json(['status'=>0,'msg'=>'token验证失败！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=classification","ENCODE",$_SESSION['domain_key'])]]);
	      	unset($_SESSION['token']);

	      	$db->bind("id", $id);
	      	$info = $db->query("DELETE from domain_project_classification where `id` = :id");
	      	if($info){
	        	$this->json(["status"=>1,"msg"=>"删除成功！","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=classification","ENCODE",$_SESSION['domain_key'])]]);
	      	} else {
	        	$this->json(["status"=>0,"msg"=>"删除失败！","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=classification","ENCODE",$_SESSION['domain_key'])]]);
	      	}
	    } else {
	      	$this->json(["status"=>0,"msg"=>"错误异常！","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=classification","ENCODE",$_SESSION['domain_key'])]]);
	    }
	}

	/**
	 * 查看项目资产
	 * @access  public
	 * @return html
	 */
	public function see_classification()
	{
		$this->jurisdiction("非法访问查看项目资产");
		$this->log_db("用户访问查看项目资产","5");
		$db = $this->Db();
		$token = md5(code().time().code());
      	$_SESSION['token'] = $token;
      	$this->smarty->assign('token',$token);
		if($_POST){
	      	$id = isset($_GET['id']) ? intval($_GET['id']) : "";
	      	$draw = isset($_POST['draw']) ? intval($_POST['draw']) : "1";
	      	$start = isset($_POST['start']) ? intval($_POST['start']) : "1";
	      	$length = isset($_POST['length']) ? intval($_POST['length']) : "10";

	      	#IF判断区域
	      	if(empty($id)) $this->json(['status'=>0,'msg'=>'输入ID！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=classification","ENCODE",$_SESSION['domain_key'])]]);

			$db = $this->Db();
			$db->bind("pid",$id);
			$sql = "SELECT * FROM domain_project_domain WHERE pid = :pid";
			$count = "SELECT count(*) as num FROM domain_project_domain WHERE pid = :pid";
			$list = $db->query($sql);
			if($list){
	        	foreach ($list as $k => $v) {
	          		$list[$k]['creation_time'] = $v['creation_time'] == 0 ? '-' : date("Y-m-d H:i:s",$v['creation_time']);
		        	$list[$k]['del_see_classification_id'] = "./".root_filename.".php?".AuthCode("m=Products&a=del_see_classification_id&id=".$v['id']."&token=".$token,"ENCODE",$_SESSION['domain_key']);
		        }
	      	}
			$db->bind("pid",$id);
      		$classification_count = $db->find_one($count);
      		$classification_num = isset($classification_count['num']) ? $classification_count['num'] : 0;
	      	$this->json(["draw"=>$draw,"recordsTotal"=>$classification_num,"recordsFiltered"=>$classification_num,"data"=>$list]);
	    } else {
	      	$id = isset($_GET['id']) ? intval($_GET['id']) : '';
	      	#IF判断区域
	      	if(empty($id)) $this->json(['status'=>0,'msg'=>'输入ID！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=classification","ENCODE",$_SESSION['domain_key'])]]);
	      	$this->smarty->assign('url',"./".root_filename.".php?".AuthCode("m=Products&a=see_classification&id=".$id,"ENCODE",$_SESSION['domain_key']));
	      	$this->smarty->display('products/see_classification.tpl');
	    }
	}

	/**
	 * 查看项目分布
	 * @access  public
	 * @return html
	 */
	public function chart_classification()
	{
		$id = isset($_GET['id']) ? intval($_GET['id']) : "";
		$db = $this->Db();
		$db->bind("pid",$id);
		$sql = "SELECT id,pid,url FROM domain_project_domain WHERE pid = :pid";
		$list = $db->query($sql);
		$db->bind("id",$id);
		$sql = "SELECT id,title FROM domain_project_classification WHERE id = :id";
		$classification_list = $db->find_one($sql);
		if(!$classification_list) $this->json(['status'=>0,'msg'=>'项目不存在！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=classification","ENCODE",$_SESSION['domain_key'])]]);
		$categories = [];
		$data = [];
		$lines = [];
		$data[] = ["name"=>$classification_list['title'],"id"=>0,"draggable"=>true,];
		$categories[] = ["name"=>$classification_list['title'],];
		
		if($list){
			foreach ($list as $k => $v) {
				// 关系筛选
				$categories[] = ["name"=>$v['url'],];

				// 数据组装
				$data[] = [
					"name"=>$v['url'],
					"id"=>$v['id'],
					"category"=>1,
					"draggable"=>true,
				];
				$lines[] = ["source"=>'0',"target"=>(string)$v['id'],"value"=>"",];
				$sql = "SELECT a.id,a.title,b.title as cate_id,a.bugLevel FROM domain_post as a LEFT JOIN domain_classification as b on a.cate_id = b.id WHERE a.company = :company and a.bugDetail like :bugDetail";
				$db->bind("company",$v['pid']);
			  	$db->bind("bugDetail", "%".$v['url']."%");
				$post_list = $db->query($sql);
				if($post_list){
					$i = 1;
					foreach ($post_list as $key => $value) {
						$data[] = [
							"name"=>$value['title'],
							"id"=>$v['id']."-".$i,
							"category"=>$v['id'],
							"draggable"=>true,
						];
						$lines[] = ["source"=>$v['id']."-".$i,"target"=>(string)$v['id'],"value"=>"",];
						$i++;
					}
				}

			}
		}
	    $this->smarty->assign('lines',json_encode($lines));
	    $this->smarty->assign('data',json_encode($data));
	    $this->smarty->assign('categories',json_encode($categories));
	    $this->smarty->assign('formatter',"{c}");
	    $this->smarty->display('products/chart_classification.tpl');
	}


	/**
	 * 删除项目资产
	 * @access  public
	 * @return html
	 */
	public function del_see_classification_id()
	{
		$this->jurisdiction("非法访问删除项目资产");
		$this->log_db("用户访问删除项目资产","5");

 		$db = $this->Db();
	    if($_GET){
	      	$id = isset($_GET['id']) ? intval($_GET['id']) : '';
	      	$token = isset($_GET['token']) ? $_GET['token'] : '';
	      	$session_token = isset($_SESSION['token']) ? $_SESSION['token'] : '';
	      	#IF判断区域
	      	if(empty($id)) $this->json(['status'=>0,'msg'=>'输入ID！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=classification","ENCODE",$_SESSION['domain_key'])]]);
	      	if(empty($token)) $this->json(['status'=>0,'msg'=>'输入token！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=classification","ENCODE",$_SESSION['domain_key'])]]);
	      	if(empty($session_token)) $this->json(['status'=>0,'msg'=>'token异常！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=classification","ENCODE",$_SESSION['domain_key'])]]);
	      	if($token != $session_token) $this->json(['status'=>0,'msg'=>'token验证失败！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=classification","ENCODE",$_SESSION['domain_key'])]]);
	      	unset($_SESSION['token']);

	      	$db->bind("id", $id);
	      	$info = $db->query("DELETE from domain_project_domain where `id` = :id");
	      	if($info){
	        	$this->json(["status"=>1,"msg"=>"删除成功！","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=classification","ENCODE",$_SESSION['domain_key'])]]);
	      	} else {
	        	$this->json(["status"=>0,"msg"=>"删除失败！","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=classification","ENCODE",$_SESSION['domain_key'])]]);
	      	}
	    } else {
	      	$this->json(["status"=>0,"msg"=>"错误异常！","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=classification","ENCODE",$_SESSION['domain_key'])]]);
	    }
	}

	/**
	 * 漏洞分类
	 * @access  public
	 * @return html
	 */
	public function loophole_classification()
	{
		$this->log_db("用户访问漏洞分类","7");
		$token = md5(code().time().code());
      	$_SESSION['token'] = $token;
      	$this->smarty->assign('token',$token);
		if ($_POST) {
			$draw = isset($_POST['draw']) ? intval($_POST['draw']) : "1";
	      	$start = isset($_POST['start']) ? intval($_POST['start']) : "1";
	      	$length = isset($_POST['length']) ? intval($_POST['length']) : "10";
	      	if($length < 0 || $length > 10) $length = 10;

	      	$value = isset($_POST['value']) ? $_POST['value'] : "";
			$db = $this->Db();
			$sql = "SELECT * FROM domain_classification limit ".$start.",".$length;
			$count = "SELECT count(*) as num FROM domain_classification";
			if($value){
				$sql = "SELECT * FROM domain_classification WHERE title LIKE :title limit ".$start.",".$length;
			  	$db->bind("title", "%".$value."%");
			}
			$list = $db->query($sql);
			if($list){
	        	foreach ($list as $k => $v) {
	          		$list[$k]['creation_time'] = $v['creation_time'] == 0 ? '-' : date("Y-m-d H:i:s",$v['creation_time']);
	          		$list[$k]['update_time'] = $v['update_time'] == 0 ? '-' : date("Y-m-d H:i:s",$v['update_time']);
	          		$list[$k]['edit_loophole_classification_id'] = "./".root_filename.".php?".AuthCode("m=Products&a=edit_loophole_classification&id=".$v['id'],"ENCODE",$_SESSION['domain_key']);
		        	$list[$k]['del_loophole_classification_id'] = "./".root_filename.".php?".AuthCode("m=Products&a=del_loophole_classification&id=".$v['id']."&token=".$token,"ENCODE",$_SESSION['domain_key']);
	        	}
	      	}

	      	if($value){
				$count = "SELECT count(*) as num FROM domain_classification WHERE title LIKE :title";
			  	$db->bind("title", "%".$value."%");
			}
      		$classification_count = $db->find_one($count);
      		$classification_num = isset($classification_count['num']) ? $classification_count['num'] : 0;
	      	$this->json(["draw"=>$draw,"recordsTotal"=>$classification_num,"recordsFiltered"=>$classification_num,"data"=>$list]);
		} else {
	      	
	    	$this->smarty->display('products/loophole_classification.tpl');
		}
	}

	/**
	 * 添加漏洞分类
	 * @access  public
	 * @return html
	 */
	public function add_loophole_classification()
	{
		$this->log_db("用户访问添加漏洞分类","4");
		$db = $this->Db();
		if($_POST){
		    $pid = isset($_POST['pid']) ? intval($_POST['pid']) : '0';
		    $name = isset($_POST['name']) ? $_POST['name'] : '';
		    $suggestions = isset($_POST['suggestions']) ? $_POST['suggestions'] : '';
		    $description = isset($_POST['description']) ? $_POST['description'] : '';
			$token = isset($_POST['token']) ? $_POST['token'] : '';
	      	$session_token = isset($_SESSION['token']) ? $_SESSION['token'] : '';
	      	#IF判断区域
     	 	if(empty($name)) $this->json(['status'=>0,'msg'=>'输入分类名称！']);
	      	if(empty($token)) $this->json(['status'=>0,'msg'=>'输入token！']);
	      	if(empty($session_token)) $this->json(['status'=>0,'msg'=>'token异常！']);
	      	if($token != $session_token) $this->json(['status'=>0,'msg'=>'token验证失败！']);
	      	unset($_SESSION['token']);

	      	$set = [];
			$set_val = [];
			if(!empty($name)){
			  	$set[] = "title";
			  	$set_val[] = ":title";
			  	$db->bind("title", $name);
			}
			if(!empty($pid)){
			  	$set[] = "pid";
			  	$set_val[] = ":pid";
			  	$db->bind("pid", $pid);
			}
			if(!empty($suggestions)){
			  	$set[] = "suggestions";
			  	$set_val[] = ":suggestions";
			  	$db->bind("suggestions", $suggestions);
			}
			if(!empty($description)){
			  	$set[] = "description";
			  	$set_val[] = ":description";
			  	$db->bind("description", $description);
			}
			$set[] = "creation_time";
			$set[] = "update_time";
			$set_val[] = ":creation_time";
			$set_val[] = ":update_time";
			$db->bind("creation_time", time());
			$db->bind("update_time", time());

			$set = implode(",",$set);
      		$set_val = implode(",",$set_val);
      		$info = $db->query("INSERT INTO domain_classification($set) VALUES ($set_val)");
      		if($info){
        		$this->json(["status"=>1,"msg"=>"添加成功！"]);
      		} else {
        		$this->json(["status"=>0,"msg"=>"添加失败！"]);
      		}
		} else {
			$list = $db->query("select id,title from domain_classification where pid = '0'");
	      	$this->smarty->assign('list',$list);
	      	$token = md5(code().time().code());
	      	$_SESSION['token'] = $token;
	      	$this->smarty->assign('token',$token);
	    	$this->smarty->display('products/add_loophole_classification.tpl');
		}
	}

	/**
	 * 编辑漏洞分类
	 * @access  public
	 * @return html
	 */
	public function edit_loophole_classification()
	{
		$this->log_db("用户访问编辑漏洞分类","6");
		$db = $this->Db();
    	if($_POST){
			$this->jurisdiction("非法访问编辑漏洞分类");

			$title = isset($_POST['title']) ? $_POST['title'] : '';
			$pid = isset($_POST['pid']) ? intval($_POST['pid']) : '0';
			$name = isset($_POST['name']) ? $_POST['name'] : '';
			$suggestions = isset($_POST['suggestions']) ? $_POST['suggestions'] : '';
			$description = isset($_POST['description']) ? $_POST['description'] : '';
			$id = isset($_POST['id']) ? intval($_POST['id']) : '';
			$token = isset($_POST['token']) ? $_POST['token'] : '';
	      	$session_token = isset($_SESSION['token']) ? $_SESSION['token'] : '';
			#IF判断区域
			if(empty($name)) $this->json(['status'=>0,'msg'=>'输入分类别名！']);
			if(empty($id)) $this->json(['status'=>0,'msg'=>'输入ID！']);
			if(empty($token)) $this->json(['status'=>0,'msg'=>'输入token！']);
	      	if(empty($session_token)) $this->json(['status'=>0,'msg'=>'token异常！']);
	      	if($token != $session_token) $this->json(['status'=>0,'msg'=>'token验证失败！']);
	      	unset($_SESSION['token']);
	      	
	      	$set = [];
			$db = $this->Db();
			$db->bind("id", $id);
			if(!empty($name)){
				$set[] = "`title` = :title";
				$db->bind("title", $name);
			}
			if(!empty($pid)){
				$set[] = "`pid` = :pid";
				$db->bind("pid", $pid);
			}
			$set[] = " `suggestions` = :suggestions";
			$db->bind("suggestions", $suggestions);
			$set[] = " `description` = :description";
			$db->bind("description", $description);
			$set[] = " `update_time` = :update_time";
			$db->bind("update_time", time());

			$set = implode(", ",$set);
			if(empty($set)) $this->json(["status"=>0,"msg"=>"修改失败！"]);

			$info = $db->query("UPDATE domain_classification SET $set WHERE `id` = :id");
			if($info){
				$this->json(["status"=>1,"msg"=>"修改成功！"]);
			} else {
				$this->json(["status"=>0,"msg"=>"修改失败！"]);
			}
    	} else {
    		$id = isset($_GET['id']) ? intval($_GET['id']) : '';
	      	if($id){
	        	$db->bind("id", $id);
	        	$classification = $db->find_one("select * from domain_classification where id = :id");
	        	$token = md5(code().time().code());
		      	$_SESSION['token'] = $token;
	        	$list = $db->query("select id,title from domain_classification where pid = '0'");
	      		$this->smarty->assign('list',$list);
		      	$this->smarty->assign('token',$token);
	        	$this->smarty->assign('classification',$classification);
	        	$this->smarty->display('products/edit_loophole_classification.tpl');
	      	} else {
	        	$this->json(['data'=>['url'=>"./".root_filename.".php?".AuthCode("m=Products&a=loophole_classification","ENCODE",$_SESSION['domain_key'])],'msg'=>'程序异常']);
	      	}
    	}
	}

	/**
	 * 删除漏洞分类
	 * @access  public
	 * @return html
	 */
  	public function del_loophole_classification()
  	{
		$this->jurisdiction("非法访问删除漏洞分类");
		$this->log_db("用户访问删除漏洞分类","5");

	    $db = $this->Db();
	    if($_GET){
	      	$id = isset($_GET['id']) ? intval($_GET['id']) : '';
	      	$token = isset($_GET['token']) ? $_GET['token'] : '';
	      	$session_token = isset($_SESSION['token']) ? $_SESSION['token'] : '';
	      	#IF判断区域
	      	if(empty($id)) $this->json(['status'=>0,'msg'=>'输入ID！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=loophole_classification","ENCODE",$_SESSION['domain_key'])]]);
	      	if(empty($token)) $this->json(['status'=>0,'msg'=>'输入token！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=loophole_classification","ENCODE",$_SESSION['domain_key'])]]);
	      	if(empty($session_token)) $this->json(['status'=>0,'msg'=>'token异常！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=loophole_classification","ENCODE",$_SESSION['domain_key'])]]);
	      	if($token != $session_token) $this->json(['status'=>0,'msg'=>'token验证失败！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=loophole_classification","ENCODE",$_SESSION['domain_key'])]]);
	      	unset($_SESSION['token']);

	      	$db->bind("id", $id);
	      	$info = $db->query("DELETE from domain_classification where `id` = :id");
	      	if($info){
	        	$this->json(["status"=>1,"msg"=>"删除成功！","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=loophole_classification","ENCODE",$_SESSION['domain_key'])]]);
	      	} else {
	        	$this->json(["status"=>0,"msg"=>"删除失败！","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=loophole_classification","ENCODE",$_SESSION['domain_key'])]]);
	      	}
	    } else {
	      	$this->json(["status"=>0,"msg"=>"错误异常！","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=loophole_classification","ENCODE",$_SESSION['domain_key'])]]);
	    }
	}

	/**
	 * 模板列表
	 * @access  public
	 * @return html
	 */
	public function template()
	{
		$this->jurisdiction("非法访问模板列表");
		$this->log_db("用户访问模板列表","7");
		$token = md5(code().time().code());
      	$_SESSION['token'] = $token;
      	$this->smarty->assign('token',$token);
    	if ($_POST) {
			$draw = isset($_POST['draw']) ? intval($_POST['draw']) : "1";
	      	$start = isset($_POST['start']) ? intval($_POST['start']) : "1";
	      	$length = isset($_POST['length']) ? intval($_POST['length']) : "10";
	      	if($length < 0 || $length > 10) $length = 10;

	      	$value = isset($_POST['value']) ? $_POST['value'] : "";
			$db = $this->Db();
			$sql = "SELECT * FROM domain_template limit ".$start.",".$length;
			$count = "SELECT count(*) as num FROM domain_template";
			if($value){
				$sql = "SELECT * FROM domain_template WHERE name LIKE :name limit ".$start.",".$length;
			  	$db->bind("name", "%".$value."%");
			}
			$list = $db->query($sql);
			if($list){
	        	foreach ($list as $k => $v) {
	          		$list[$k]['add_time'] = $v['add_time'] == 0 ? '-' : date("Y-m-d H:i:s",$v['add_time']);
	          		$list[$k]['download_template_id'] = "./".root_filename.".php?".AuthCode("m=Products&a=download_template&id=".$v['uuid']."&token=".$token,"ENCODE",$_SESSION['domain_key']);
		        	$list[$k]['del_template_id'] = "./".root_filename.".php?".AuthCode("m=Products&a=del_template&id=".$v['uuid']."&token=".$token,"ENCODE",$_SESSION['domain_key']);
	        	}
	      	}

	      	if($value){
				$count = "SELECT count(*) as num FROM domain_template WHERE name LIKE :name";
			  	$db->bind("name", "%".$value."%");
			}
      		$classification_count = $db->find_one($count);
      		$classification_num = isset($classification_count['num']) ? $classification_count['num'] : 0;
	      	$this->json(["draw"=>$draw,"recordsTotal"=>$classification_num,"recordsFiltered"=>$classification_num,"data"=>$list]);
		} else {
	    	$this->smarty->display('products/template.tpl');
		}
	}

	/**
	 * 下载模板模板
	 * @access  public
	 * @return html
	 */
  	public function download_template()
  	{
		$this->jurisdiction("非法访问下载模板模板");
		$this->log_db("用户访问下载模板模板","9");

	    $db = $this->Db();
	    if($_GET){
	      	$id = isset($_GET['id']) ? intval($_GET['id']) : '';
	      	$token = isset($_GET['token']) ? $_GET['token'] : '';
	      	$session_token = isset($_SESSION['token']) ? $_SESSION['token'] : '';
	      	#IF判断区域
	      	if(empty($id)) $this->json(['status'=>0,'msg'=>'输入ID！',"data"=>["url"=>"/".root_filename.".php?m=Products&a=template"]]);
	      	if(empty($token)) $this->json(['status'=>0,'msg'=>'输入token！',"data"=>["url"=>"/".root_filename.".php?m=Products&a=template"]]);
	      	if(empty($session_token)) $this->json(['status'=>0,'msg'=>'token异常！',"data"=>["url"=>"/".root_filename.".php?m=Products&a=template"]]);
	      	if($token != $session_token) $this->json(['status'=>0,'msg'=>'token验证失败！',"data"=>["url"=>"/".root_filename.".php?m=Products&a=template"]]);
	      	unset($_SESSION['token']);

	      	$db = $this->Db();
	      	$db->bind("uuid", $id);
			$list = $db->find_one("select * from domain_template WHERE uuid = :uuid");
			if($list){
				$file_path = basename($list['file_path']);
				if(file_exists(ROOT_PATH."/python_web/template/".$file_path)){
				    $file = fopen ( ROOT_PATH."/python_web/template/".$file_path, "rb"); 
				    Header ("Content-type: application/octet-stream"); 
				    Header ("Accept-Ranges: bytes" );  
				    Header ("Accept-Length: " . filesize(ROOT_PATH."/python_web/template/".$file_path));  
				    Header ("Content-Disposition: attachment; filename=" . $file_path );    
				    echo fread ( $file, filesize(ROOT_PATH."/python_web/template/".$file_path));    
				    fclose($file);    
				    exit();
				} else {
	      			$this->json(["status"=>0,"msg"=>"模板文件不存在！","data"=>["url"=>"/".root_filename.".php?m=Products&a=template"]]);
				}
			} else {
	      		$this->json(["status"=>0,"msg"=>"模板id不存在！","data"=>["url"=>"/".root_filename.".php?m=Products&a=template"]]);
			}
	    } else {
	      	$this->json(["status"=>0,"msg"=>"错误异常！","data"=>["url"=>"/".root_filename.".php?m=Products&a=template"]]);
	    }
	}

	/**
	 * 删除模板
	 * @access  public
	 * @return html
	 */
  	public function del_template()
  	{
		$this->jurisdiction("非法访问删除模板");
		$this->log_db("用户访问删除模板","5");
  		
	    $db = $this->Db();
	    if($_GET){
	      	$id = isset($_GET['id']) ? intval($_GET['id']) : '';
	      	$token = isset($_GET['token']) ? $_GET['token'] : '';
	      	$session_token = isset($_SESSION['token']) ? $_SESSION['token'] : '';
	      	#IF判断区域
	      	if(empty($id)) $this->json(['status'=>0,'msg'=>'输入ID！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=template","ENCODE",$_SESSION['domain_key'])]]);
	      	if(empty($token)) $this->json(['status'=>0,'msg'=>'输入token！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=template","ENCODE",$_SESSION['domain_key'])]]);
	      	if(empty($session_token)) $this->json(['status'=>0,'msg'=>'token异常！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=template","ENCODE",$_SESSION['domain_key'])]]);
	      	if($token != $session_token) $this->json(['status'=>0,'msg'=>'token验证失败！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=template","ENCODE",$_SESSION['domain_key'])]]);
	      	unset($_SESSION['token']);

	      	if($id == "656b721dee262140946d1120f35f84e3") $this->json(['status'=>0,'msg'=>'默认模板禁止删除！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=template","ENCODE",$_SESSION['domain_key'])]]);

	      	$db->bind("uuid", $id);
	      	$info = $db->query("DELETE from domain_template where `uuid` = :uuid");
	      	if($info){
	        	$this->json(["status"=>1,"msg"=>"删除成功！","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=template","ENCODE",$_SESSION['domain_key'])]]);
	      	} else {
	        	$this->json(["status"=>0,"msg"=>"删除失败！","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=template","ENCODE",$_SESSION['domain_key'])]]);
	      	}
	    } else {
	      	$this->json(["status"=>0,"msg"=>"错误异常！","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Products&a=template","ENCODE",$_SESSION['domain_key'])]]);
	    }
	}
}
?>