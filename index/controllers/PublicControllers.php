<?php
include_once dirname(__FILE__)."/AuthControllers.php";
class PublicControllers extends AuthControllers
{
	/**
	 * 文件上传
	 * @access  public
	 * @return html
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
	        	} else {
	        		$this->log_db("用户异常上传文件操作：".$upload->errorInfo.",文件名：".filterWords(basename($_FILES['file']['name'])),"8");
	        		$this->json(["status"=>0,"msg"=>$upload->errorInfo]);
	        	}
	      	} else {
	        	$this->json(["status"=>0,"msg"=>"文件上传异常！"]);
	      	}
	      	$this->json(["status"=>1,"msg"=>"文件上传成功","data"=>$info]);
		} else {
	      	$this->json(["status"=>0,"msg"=>"文件上传异常！"]);
		}
	}

}