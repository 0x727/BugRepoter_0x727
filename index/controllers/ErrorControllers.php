<?php
include_once dirname(__FILE__)."/AuthControllers.php";
class ErrorControllers extends AuthControllers
{
	public function index()
	{
		if(empty($_SERVER['HTTP_REFERER'])){
			$this->log_db("程序错误","8");
		} else {
			$this->log_db("异常访问：".filterWords($_SERVER['HTTP_REFERER']),"8");
		}
    	$this->smarty->display('error/index.tpl');
	}
}
?>