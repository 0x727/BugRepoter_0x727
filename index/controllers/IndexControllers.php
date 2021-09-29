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
		$project_id = isset($_POST['project_id']) ? intval($_POST['project_id']) : '';

		$db = $this->Db();
		// 统计漏洞top10
		if($project_id){
			$db->bind("company", $project_id);
			$classification_post = $db->query("SELECT cate_id,count(*) as num FROM domain_post WHERE company = :company GROUP BY cate_id");
			$classification = $db->query("SELECT id,title FROM domain_classification WHERE pid != 0");
		} else {
			$classification = $db->query("SELECT id,title FROM domain_classification WHERE pid != 0");
			$classification_post = $db->query("SELECT cate_id,count(*) as num FROM domain_post GROUP BY cate_id");
		}
		$new_top_labels = [];
		$new_top_series = [];
		if($classification){
			foreach ($classification as $k => $v) {
				foreach ($classification_post as $key => $val) {
					if($v['id'] == $val['cate_id']){
						$new_top_labels[$v['id']] = $v['title'];
						$new_top_series[$v['id']] = $val['num'];
					}
				}
			}
		}

		// 漏洞提交者
		$member = $db->query("SELECT id,username FROM domain_member");
		if($project_id){
			$db->bind("company", $project_id);
			$member_post = $db->query("SELECT user_id,count(*) as num FROM domain_post WHERE company = :company GROUP BY user_id");
		} else {
			$member_post = $db->query("SELECT user_id,count(*) as num FROM domain_post GROUP BY user_id");
		}
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

	    // 漏洞量
	    $loophole_num = 0;
	    foreach ($member_post as $k => $v) {
	    	$loophole_num += $v['num'];
	    }

	    // 修复数量
	    if($project_id){
			$db->bind("company", $project_id);
			$repair_post = $db->query("SELECT user_id,count(*) as num FROM domain_post WHERE company = :company AND repair_time IS NOT NULL GROUP BY user_id");
		} else {
			$repair_post = $db->query("SELECT user_id,count(*) as num FROM domain_post WHERE repair_time IS NOT NULL GROUP BY user_id");
		}
	    $repair_num = 0;
	    foreach ($repair_post as $k => $v) {
	    	$repair_num += $v['num'];
	    }

	    // 查看项目
		$project_classification = $db->query("SELECT id,title FROM domain_project_classification WHERE pid = 0");
		if($project_id){
			$this->smarty->assign('new_top_labels',json_encode(array_values($new_top_labels)));
			$this->smarty->assign('new_top_series',json_encode(array_values($new_top_series)));
			$this->smarty->assign('new_user_labels',json_encode(array_values($new_user_labels)));
			$this->smarty->assign('new_user_series',json_encode(array_values($new_user_series)));
			$this->smarty->assign('loophole_num',$loophole_num);
			$this->smarty->assign('repair_num',$repair_num);
			$this->json([
				"new_top_labels"=>array_values($new_top_labels),
				"new_top_series"=>array_values($new_top_series),
				"new_user_labels"=>array_values($new_user_labels),
				"new_user_series"=>array_values($new_user_series),
				"loophole_num"=>$loophole_num,
				"repair_num"=>$repair_num,
			]);
		} else {
			$this->smarty->assign('new_top_labels',json_encode(array_values($new_top_labels)));
			$this->smarty->assign('new_top_series',json_encode(array_values($new_top_series)));
			$this->smarty->assign('new_user_labels',json_encode(array_values($new_user_labels)));
			$this->smarty->assign('new_user_series',json_encode(array_values($new_user_series)));
			$this->smarty->assign('loophole_num',$loophole_num);
			$this->smarty->assign('repair_num',$repair_num);
			$this->smarty->assign('project_classification',$project_classification);
    		$this->smarty->display('index/index.tpl');
		}
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