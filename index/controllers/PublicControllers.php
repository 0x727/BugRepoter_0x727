<?php
include_once dirname(__FILE__)."/AuthControllers.php";
class PublicControllers extends AuthControllers
{
	/**
	 * 普通文件上传
	 * @access  public
	 * @return json
	 */
	public function up_img()
	{
		if($_FILES){
	      	include_once ROOT_PATH."/classes/upload.php";
	      	$path = "./public/upload/";
	      	$upload = new Upload(['path'=>$path]);
	      	if(isset($_FILES['file'])){
	        	$info = $upload->uploadFile("file");
	        	if($info){
                	$this->log_db("上传文件：".filterWords($info),"10");
	      			$this->json(["status"=>1,"msg"=>"文件上传成功","data"=>$info]);
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
	 * 文件上传并且加密
	 * @access  public
	 * @return json
	 */
	public function deup_img()
	{
		if($_FILES){
	      	include_once ROOT_PATH."/classes/upload.php";
	      	$path = "./public/auto/";
	      	$upload = new Upload(['path'=>$path]);
	      	if(isset($_FILES['file'])){
	        	$info = $upload->uploadFile("file");
	        	if($info){
                	$this->log_db("上传文件：".filterWords($info),"10");
                	$name = md5(time().$path.time().code());
	        		@file_put_contents($path.$name,binary_encode(@file_get_contents($info),$name));
                	$this->log_db("加密文件：".filterWords($path.$name),"10");
	        		@unlink($info);
                	$this->log_db("删除原始文件：".filterWords($info),"10");
	      			$this->json(["status"=>1,"msg"=>"文件上传成功","data"=>"/".root_filename.".php?".AuthCode("m=Public&a=enup_img&id=".$name,"ENCODE",$_SESSION['domain_content_key'])]);
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
	 * 解密文件
	 * @access  public
	 * @return json
	 */
	public function enup_img()
	{
		$id = isset($_GET['id']) ? basename($_GET['id']) : "";
		if(empty($id)) $this->json(['status'=>0,'msg'=>'请输入ID！']);
		if(strlen($id) != '32') $this->json(['status'=>0,'msg'=>'ID错误！']);
		$path = ROOT_PATH."/public/auto/".$id;
		if(!file_exists($path)) $this->json(['status'=>0,'msg'=>'文件不存在！']);
		echo @binary_decode(@file_get_contents($path),$id);die;
	}
}