<?php
include_once dirname(__FILE__)."/AuthControllers.php";
class DocxControllers extends AuthControllers
{
	
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
	          		$list[$k]['download_template_id'] = "./".root_filename.".php?".AuthCode("m=Docx&a=download_template&id=".$v['id']."&token=".$token,"ENCODE",$_SESSION['domain_key']);
	          		$list[$k]['edit_template_id'] = "./".root_filename.".php?".AuthCode("m=Docx&a=edit_template&id=".$v['id']."&token=".$token,"ENCODE",$_SESSION['domain_key']);
		        	$list[$k]['del_template_id'] = "./".root_filename.".php?".AuthCode("m=Docx&a=del_template&id=".$v['id']."&token=".$token,"ENCODE",$_SESSION['domain_key']);
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
	    	$this->smarty->display('docx/template.tpl');
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
	      	if(empty($id)) $this->json(['status'=>0,'msg'=>'输入ID！',"data"=>["url"=>"/".root_filename.".php?m=Docx&a=template"]]);
	      	if(empty($token)) $this->json(['status'=>0,'msg'=>'输入token！',"data"=>["url"=>"/".root_filename.".php?m=Docx&a=template"]]);
	      	if(empty($session_token)) $this->json(['status'=>0,'msg'=>'token异常！',"data"=>["url"=>"/".root_filename.".php?m=Docx&a=template"]]);
	      	if($token != $session_token) $this->json(['status'=>0,'msg'=>'token验证失败！',"data"=>["url"=>"/".root_filename.".php?m=Docx&a=template"]]);
	      	unset($_SESSION['token']);

	      	$db = $this->Db();
	      	$db->bind("id", $id);
			$list = $db->find_one("SELECT * FROM domain_template WHERE id = :id");
			if($list){
				$file_path = basename($list['file_path']);
				if(file_exists(ROOT_PATH."/public/docx/".$_SESSION['user_info']['uuid']."/".$file_path)){
				    $file = fopen ( ROOT_PATH."/public/docx/".$_SESSION['user_info']['uuid']."/".$file_path, "rb"); 
				    Header ("Content-type: application/octet-stream"); 
				    Header ("Accept-Ranges: bytes" );  
				    Header ("Accept-Length: " . filesize(ROOT_PATH."/public/docx/".$_SESSION['user_info']['uuid']."/".$file_path));  
				    Header ("Content-Disposition: attachment; filename=" . $file_path );    
				    echo fread ( $file, filesize(ROOT_PATH."/public/docx/".$_SESSION['user_info']['uuid']."/".$file_path));    
				    fclose($file);    
				    exit();
				} else {
	      			$this->json(["status"=>0,"msg"=>"模板文件不存在！","data"=>["url"=>"/".root_filename.".php?m=Docx&a=template"]]);
				}
			} else {
	      		$this->json(["status"=>0,"msg"=>"模板id不存在！","data"=>["url"=>"/".root_filename.".php?m=Docx&a=template"]]);
			}
	    } else {
	      	$this->json(["status"=>0,"msg"=>"错误异常！","data"=>["url"=>"/".root_filename.".php?m=Docx&a=template"]]);
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
	      	if(empty($id)) $this->json(['status'=>0,'msg'=>'输入ID！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Docx&a=template","ENCODE",$_SESSION['domain_key'])]]);
	      	if(empty($token)) $this->json(['status'=>0,'msg'=>'输入token！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Docx&a=template","ENCODE",$_SESSION['domain_key'])]]);
	      	if(empty($session_token)) $this->json(['status'=>0,'msg'=>'token异常！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Docx&a=template","ENCODE",$_SESSION['domain_key'])]]);
	      	if($token != $session_token) $this->json(['status'=>0,'msg'=>'token验证失败！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Docx&a=template","ENCODE",$_SESSION['domain_key'])]]);
	      	unset($_SESSION['token']);

	      	if($id == "1") $this->json(['status'=>0,'msg'=>'默认模板禁止删除！',"data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Docx&a=template","ENCODE",$_SESSION['domain_key'])]]);
	      	$db = $this->Db();
	      	$db->bind("id", $id);
			$list = $db->find_one("SELECT * FROM domain_template WHERE id = :id");
			if($list){
				$path = ROOT_PATH."/public/docx/".$_SESSION['user_info']['uuid']."/";
		      	$db->bind("id", $id);
		      	$info = $db->query("DELETE from domain_template where `id` = :id");
		      	if($info){
					deldir($path.$list['file_path']."-hist");
					unlink($path.$list['file_path']);
		        	$this->json(["status"=>1,"msg"=>"删除成功！","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Docx&a=template","ENCODE",$_SESSION['domain_key'])]]);
		      	} else {
		        	$this->json(["status"=>0,"msg"=>"删除失败！","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Docx&a=template","ENCODE",$_SESSION['domain_key'])]]);
		      	}
			} else {
		        $this->json(["status"=>0,"msg"=>"删除失败！","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Docx&a=template","ENCODE",$_SESSION['domain_key'])]]);
			}
	    } else {
	      	$this->json(["status"=>0,"msg"=>"错误异常！","data"=>["url"=>"/".root_filename.".php?".AuthCode("m=Docx&a=template","ENCODE",$_SESSION['domain_key'])]]);
	    }
	}

	/**
	 * 添加模板
	 * @access  public
	 * @return html
	 */
	public function add_template()
	{
		if(if_onlyoffice()){
			$this->jurisdiction("非法访问添加模板");
			$this->log_db("用户访问添加模板","4");
			$this->docx_template();
		    $this->smarty->display('docx/add_template.tpl');
		} else {
			$this->smarty->display('docx/error_template.tpl');
		}
	}

	/**
	 * 编辑模板
	 * @access  public
	 * @return html
	 */
	public function edit_template()
	{
		if(if_onlyoffice()){
			$this->jurisdiction("非法访问编辑模板");
			$this->log_db("用户访问编辑模板","6");
			$db = $this->Db();
	    	if($_GET){
	    		$id = isset($_GET['id']) ? intval($_GET['id']) : '';
		      	if($id){
		        	$db->bind("id", $id);
		        	$list = $db->find_one("select * from domain_template where id = :id");
		        	if($list){
		        		$this->docx_template($list['file_path']);
			        	$this->smarty->display('docx/add_template.tpl');
		        	} else {
		        		$this->json(['data'=>['url'=>"./".root_filename.".php?".AuthCode("m=Docx&a=template","ENCODE",$_SESSION['domain_key'])],'msg'=>'程序异常']);
		        	}
		      	} else {
		        	$this->json(['data'=>['url'=>"./".root_filename.".php?".AuthCode("m=Docx&a=template","ENCODE",$_SESSION['domain_key'])],'msg'=>'程序异常']);
		      	}
	    	}
		} else {
			$this->smarty->display('docx/error_template.tpl');
		}
	}

	/**
	 * 添加编辑公共调用模板
	 * @access  protected
	 * @return json
	 */
	protected function docx_template($docx_file = "")
	{
		$uuid = $_SESSION['user_info']['uuid'];
		$path = ROOT_PATH."/public/docx/".$uuid;
		// 判断文件是否创建，没有创建就进行创建
		if(!is_dir($path)){
			// 递归创建文件
			superBuilt(ROOT_PATH."/public/docx/".$_SESSION['user_info']['uuid']);
		}
	    
		if(empty($docx_file)){
			$docx = md5(md5(code().time()).code()).".docx";
		} else {
			$docx = $docx_file;
		}
		// 判断文件是否存在
		if(!is_file($path."/".$docx)){
			// 创建文件
			touch($path."/".$docx);
		}
		$isJwtEnabled = false;
		$docKey = GenerateRevisionId(filemtime($path."/".$docx).getCurUserHostAddress());
		$filetype = strtolower(pathinfo($path."/".$docx, PATHINFO_EXTENSION));
    	$editorsMode = empty($_GET["action"]) ? "edit" : $_GET["action"];
    	$canEdit = in_array(strtolower('.' . pathinfo($path."/".$docx, PATHINFO_EXTENSION)), array(".docx", ".xlsx", ".csv", ".pptx", ".txt"));
    	$submitForm = $canEdit && ($editorsMode == "edit" || $editorsMode == "fillForms");
    	$mode = $canEdit && $editorsMode != "view" ? "edit" : "view";
    	$type = empty($_GET["type"]) ? "desktop" : $_GET["type"];
   		$templatesImageUrl = get_curl()."/public/docx/css/images/file_docx.svg";
   		$createUrl = get_curl()."/example/doceditor.php?fileExt=docx&user=user-".$_SESSION['user_info']['id']."&type=desktop&1f2018903=".session_id();
   		$templates = [
	        [
	            "image" => "",
	            "title" => "Blank",
	            "url" => $createUrl
	        ],
	        [
	            "image" => $templatesImageUrl,
	            "title" => "With sample content",
	            "url" => $createUrl . "&sample=true"
	        ]
	    ];

	    $config = [
        	"type" => $type,
        	"documentType" => getDocumentType($path."/".$docx),
        	"document" => [
	            "title" => $docx,
	            "url" => get_curl()."/example/webeditor-ajax.php?type=download&fileName=".urlencode($docx)."&userAddress=".getCurUserHostAddress()."&1f2018903=".session_id(),
	            "fileType" => $filetype,
	            "key" => $docKey,
	            "info" => [
	                "owner" => $_SESSION['user_info']['username'],
	                "uploaded" => date('d.m.y'),
	                "favorite" => NULL
	            ],
	            "permissions" => [
	                "comment" => $editorsMode != "view" && $editorsMode != "fillForms" && $editorsMode != "embedded" && $editorsMode != "blockcontent",
	                "copy" => true,
	                "download" => true,
	                "edit" => $canEdit && ($editorsMode == "edit" || $editorsMode == "view" || $editorsMode == "filter" || $editorsMode == "blockcontent"),
	                "print" => true,
	                "fillForms" => $editorsMode != "view" && $editorsMode != "comment" && $editorsMode != "embedded" && $editorsMode != "blockcontent",
	                "modifyFilter" => $editorsMode != "filter",
	                "modifyContentControl" => $editorsMode != "blockcontent",
	                "review" => $canEdit && ($editorsMode == "edit" || $editorsMode == "review"),
	                "reviewGroups" => NULL,
	                "commentGroups" => []
	            ]
	        ],
	        "editorConfig" => [
	            "actionLink" => empty($_GET["actionLink"]) ? null : json_decode($_GET["actionLink"]),
	            "mode" => $mode,
	            "lang" => "zh",
	            "callbackUrl" => get_curl()."/example/webeditor-ajax.php?type=track&fileName=".urlencode($docx)."&userAddress=".getCurUserHostAddress()."&1f2018903=".session_id(),
	            "createUrl" => $createUrl,
	            "templates" => $templates,
	            "user" => [
	                "id" => $_SESSION['user_info']['id'],
	                "name" => $_SESSION['user_info']['username'],
	                "group" => NULL
	            ],
	            "embedded" => [
	                "saveUrl" => get_curl()."/public/docx/".$uuid."/".$docx,
	                "embedUrl" => get_curl()."/public/docx/".$uuid."/".$docx,
	                "shareUrl" => get_curl()."/public/docx/".$uuid."/".$docx,
	                "toolbarDocked" => "top",
	            ],
	            "customization" => [
	                "about" => true,
	                "feedback" => true,
	                "forcesave" => false,
	                "submitForm" => true,
	                "goback" => [
	                    "url" => get_curl(),
	                ]
	            ]
	        ],
	    ];
	    $dataInsertImage = [
	        "fileType" => "png",
	        "url" => get_curl()."/public/docx/css/images/logo.png"
	    ];
	    $dataCompareFile = [
	        "fileType" => "docx",
	        "url" => get_curl() . "/example/webeditor-ajax.php?type=assets&name=sample.docx"."&1f2018903=".session_id()
	    ];

	    $dataMailMergeRecipients = [
	        "fileType" =>"csv",
	        "url" => get_curl() . "/example/webeditor-ajax.php?type=csv"."&1f2018903=".session_id()

	    ];

	    $GLOBALS['docx_template'] = "1";
	    $GLOBALS['1f2018903'] = session_id();
	    
	    include_once ROOT_PATH."/example/jwtmanager.php";
	    if (isJwtEnabled()) {
	        $config["token"] = jwtEncode($config);
	        $dataInsertImage["token"] = jwtEncode($dataInsertImage);
	        $dataCompareFile["token"] = jwtEncode($dataCompareFile);
	        $dataMailMergeRecipients["token"] = jwtEncode($dataMailMergeRecipients);
	    }

	    $usersForMentions = [
	    	[
	    		"name"=>$_SESSION['user_info']['username'],
	    		"email"=>$_SESSION['user_info']['email'],
	    	],
	    ];

	    $out = getHistory(ROOT_PATH."/public/docx/".$uuid."/".$docx, $filetype, $docKey, get_curl()."/public/docx/".$uuid."/".$docx);
	    $history = json_encode($out[0]);
	    $historyData = json_encode($out[1]);
	    if(!$out){
	    	$history = NULL;
	    	$historyData = NULL;
	    }
	    $this->smarty->assign("dataInsertImage",mb_strimwidth(json_encode($dataInsertImage), 1, strlen(json_encode($dataInsertImage)) - 2));
	    $this->smarty->assign("dataCompareFile",json_encode($dataCompareFile));
	    $this->smarty->assign("dataMailMergeRecipients",json_encode($dataMailMergeRecipients));
	    $this->smarty->assign("config",json_encode($config));
	    $this->smarty->assign("history",$history);
	    $this->smarty->assign("historyData",$historyData);
	    $this->smarty->assign("get_curl",get_curl());
	    $this->smarty->assign("usersForMentions",json_encode($usersForMentions));
	    if(empty($docx_file)){
			$db = $this->Db();
		    $info = $db->find_one("SELECT * FROM domain_template WHERE name LIKE '%新建文档%' ORDER BY id desc");
		    if($info){
		    	$name = "新建文档(".intval(str_replace(["新建文档(",")"], "", $info['name'])+1).")";
		    } else {
		    	$name = "新建文档";
		    }
		    $db->bind("uuid", md5(uuid()));
		    $db->bind("name", $name);
		    $db->bind("file_path", $docx);
		    $db->bind("add_time", time());
	      	$db->query("INSERT INTO domain_template(uuid,name,file_path,add_time) VALUES (:uuid,:name,:file_path,:add_time)");
		} else {
			$docx = $docx_file;
		}
	}
}