<?php
header("Conten-type:text/html;charset=utf-8");
include_once dirname(__FILE__)."/AuthControllers.php";
class IndexControllers extends AuthControllers
{
	/**
	 * 首页
	 * @access  public
	 * @return html
	 */
	public function index()
	{
		$db = $this->Db();
		// 统计漏洞top10
		$classification = $db->query("select id,title from domain_classification where pid != 0");
		$classification_post = $db->query("select cate_id,count(*) as num from domain_post group by cate_id");
		$new_top_labels = [];
		$new_top_series = [];
		if($classification){
			foreach ($classification as $k => $v) {
				$new_top_labels[$v['id']] = $v['title'];
				$new_top_series[$v['id']] = 0;
				foreach ($classification_post as $key => $val) {
					if($v['id'] == $val['cate_id']){
						$new_top_labels[$v['id']] = $v['title'];
						$new_top_series[$v['id']] = $val['num'];
					}
				}
			}
		}
		$this->smarty->assign('new_top_labels',json_encode(array_values($new_top_labels)));
	    $this->smarty->assign('new_top_series',json_encode(array_values($new_top_series)));

		// 漏洞提交者
		$member = $db->query("select id,username from domain_member");
		$member_post = $db->query("select user_id,count(*) as num from domain_post group by user_id");
		$repair_post = $db->query("select user_id,count(*) as num from domain_post where repair_time is not null group by user_id");
		$new_user_labels = [];
		$new_user_series = [];
		if($member){
			foreach ($member as $k => $v) {
				$new_user_labels[$v['id']] = $v['username'];
				$new_user_series[$v['id']] = 0;
				foreach ($member_post as $key => $val) {
					if($v['id'] == $val['user_id']){
						$new_user_labels[$v['id']] = $v['username'];
						$new_user_series[$v['id']] = $val['num'];
					}
				}
			}
		}
	    $this->smarty->assign('new_user_labels',json_encode(array_values($new_user_labels)));
	    $this->smarty->assign('new_user_series',json_encode(array_values($new_user_series)));

	    // 漏洞量
	    $loophole_num = 0;
	    foreach ($member_post as $k => $v) {
	    	$loophole_num += $v['num'];
	    }
	    $this->smarty->assign('loophole_num',$loophole_num);

	    // 修复数量
	    $repair_num = 0;
	    foreach ($repair_post as $k => $v) {
	    	$repair_num += $v['num'];
	    }
	    $this->smarty->assign('repair_num',$repair_num);

    	$this->smarty->display('index/index.tpl');
	}

	/**
	 * 关于我们
	 * @access  public
	 * @return html
	 */
	public function about_us()
	{
    	$this->smarty->display('index/about_us.tpl');
	}
}
?>