<?php
header("Conten-type:text/html;charset=utf-8");
include_once dirname(__FILE__)."/AuthControllers.php";
class InstallControllers extends AuthControllers
{
	/**
	 * 初始化安装
	 * @access  public
	 * @return html
	 */
	public function index()
	{
		if (file_exists(ROOT_PATH.'/runtime/install.lock')) $this->json(['status'=>0,'msg'=>'请删除install.lock重新开始安装。']);

		if($_POST){
			$type = isset($_POST['type']) ? $_POST['type'] : '';
			if ($type == "three") {
				$ip = isset($_POST['ip']) ? $_POST['ip'] : '';
				$library_name = isset($_POST['library_name']) ? $_POST['library_name'] : '';
				$username = isset($_POST['username']) ? $_POST['username'] : '';
				$password = isset($_POST['password']) ? $_POST['password'] : '';
				
				if(!preg_match("/^([a-zA-Z0-9]+\.*)+:+[0-9]+$/",$ip)) $this->json(['status'=>0,'msg'=>'服务器地址格式错误！']);
				if(!preg_match("/^[a-zA-Z0-9_-]+$/",$library_name)) $this->json(['status'=>0,'msg'=>'数据库名格式错误！']);
				if(!preg_match("/^[a-zA-Z0-9_-]+$/",$username)) $this->json(['status'=>0,'msg'=>'数据库用户格式错误！']);
				if(!preg_match("/^[a-zA-Z0-9_!@-^]+$/",$password)) $this->json(['status'=>0,'msg'=>'数据库密码格式错误！']);
				if(try_mysql($ip,$library_name,$username,$password)){
					$content = "host = \"".explode(':',$ip)[0]."\";
user = \"".$username."\";
password = \"".$password."\";
dbname = \"".$library_name."\";
port = ".explode(':',$ip)[1].";";
					if(@file_put_contents(ROOT_PATH."/config/config.php", $content)){
						$data = [
							md5("domain_classification"),
							md5("domain_logs"),
							md5("domain_member"),
							md5("domain_post"),
							md5("domain_project_classification"),
							md5("domain_template"),
							md5("install_sql_member"),
							md5("install_sql_classification"),
							md5("install_sql_template"),
						];
		                $this->json(['status'=>1,'msg'=>'正在为您安装~','data'=>$data]);
		            } else {
		            	$this->json(['status'=>0,'msg'=>'写入配置失败']);
		            }
				} else {
					 $this->json(['status'=>0,'msg'=>'连接失败！']);
				}
			} elseif ($type == "four"){
				$md5 = isset($_POST['md5']) ? $_POST['md5'] : '';
				$Db = $this->Db();
				sleep(2);
				switch ($md5) {
					case md5("domain_classification"):
						$domain_classification = "CREATE TABLE IF NOT EXISTS `domain_classification` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父分类ID',
						  `title` varchar(100) NOT NULL DEFAULT '暂无' COMMENT '分类标题',
						  `suggestions` varchar(255) DEFAULT '暂无' COMMENT '修复建议',
						  `description` varchar(255) DEFAULT '暂无' COMMENT '漏洞描述',
						  `creation_time` int(11) NOT NULL COMMENT '创建时间',
						  `update_time` int(11) NOT NULL COMMENT '修改时间',
						  PRIMARY KEY (`id`) USING BTREE
						) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='漏洞分类表';";
						if($Db->exec($domain_classification)){
							$this->json(['status'=>1,'msg'=>'漏洞分类表创建成功！']);
						} else {
							$this->json(['status'=>0,'msg'=>'漏洞分类表创建失败！']);
						}
						break;
					case md5("install_sql_classification"):
						$install_sql_classification = "
	INSERT INTO `domain_classification` VALUES ('1', '0', 'web安全漏洞', '', '', '0', '0');
	INSERT INTO `domain_classification` VALUES ('2', '1', 'SQL注入', '[1]在网页代码中对用户输入的数据进行严格的过滤；\n[2]以最小权限执行SQL语句；\n[3]部署Web应用防火墙；', 'SQL 注入攻击被广泛用于非法获取网站控制权，是发生在应用程序的数据库层上的安全漏洞。由于在设计程序时，忽略了对输入字符串中夹带的 SQL 指令的检查，被数据库误认为是正常的 SQL 指令而运行，进而使数据库受到攻击，可能导致数据被窃取、更改、删除，甚至执行系统命令等，以及进一步导致网站被嵌入恶意代码、被植入后门程序等危害。', '0', '0');
	INSERT INTO `domain_classification` VALUES ('3', '1', '存储型XSS', '[1]对用户输入数据进行严格的检查，过滤或拦截所有不符合当前语境的输入，针对不同的输出位置，制定不同的转义或过滤规则；\n[2]对重要的 Cookie 字段使用 HttpOnly 参数；', '跨站脚本通常指黑客通过“HTML注入”篡改了网页，插入恶意脚本，从而在用户浏览网页时，插入恶意脚本，从而在用户浏览网页时，控制用户浏览器的一种攻击。XSS 漏洞可被用于用户身份窃取、行为劫持、挂马、蠕虫、钓鱼等，该攻击对 Web 服务器本身虽无直接危害，但是它借助网站进行传播，对网站用户进行攻击，窃取网站用户账户信息等，从而对网站产生较严重的危害。', '0', '0');
	INSERT INTO `domain_classification` VALUES ('4', '1', '反射型XSS', '[1]对用户输入数据进行严格的检查，过滤或拦截所有不符合当前语境的输入，针对不同的输出位置，制定不同的转义或过滤规则；\n[2]对重要的 Cookie 字段使用 HttpOnly 参数；', '跨站脚本通常指黑客通过“HTML注入”篡改了网页，插入恶意脚本，从而在用户浏览网页时，插入恶意脚本，从而在用户浏览网页时，控制用户浏览器的一种攻击。XSS 漏洞可被用于用户身份窃取、行为劫持、挂马、蠕虫、钓鱼等，该攻击对 Web 服务器本身虽无直接危害，但是它借助网站进行传播，对网站用户进行攻击，窃取网站用户账户信息等，从而对网站产生较严重的危害。', '0', '0');
	INSERT INTO `domain_classification` VALUES ('6', '1', 'CSRF', '[1]添加中间环节，当用户填写完内容后点击submit，服务器会接收到内容，并弹出一个确认框，让用户进行二次确认。\n[2]添加验证码，用户在提交内容时需要输入验证码，利用验证码来确认是否为当前用户发起的请求。\n[3]验证referer，可通过验证referer值是否合法，既通过验证请求来源的方式确定此次请求是否正常。\n[4]在建设web系统时利用token来识别当前用户身份的真实性。', 'CSRF是跨站请求伪造，不攻击网站服务器，而是冒充用户在站内的正常操作。通常由于服务端没有对请求头做严格过滤引起的。CSRF会造成密码重置，用户伪造等问题，可能引发严重后果。绝大多数网站是通过 cookie 等方式辨识用户身份，再予以授权的。所以要伪造用户的正常操作，最好的方法是通过 XSS 或链接欺骗等途径，让用户在本机（即拥有身份 cookie 的浏览器端）发起用户所不知道的请求。CSRF攻击会令用户在不知情的情况下攻击自己已经登录的系统。', '0', '0');
	INSERT INTO `domain_classification` VALUES ('7', '1', '代码执行漏洞', '[1]建议假定所有输入都是可疑的，尝试对所有输入提交可能执行命令的构造语句进行严格的检查或者控制外部输入，系统命令执行函数的参数不允许外部传递。\n[2]不仅要验证数据的类型，还要验证其格式、长度、范围和内容。\n[3]不要仅仅在客户端做数据的验证与过滤，关键的过滤步骤在服务端进行。\n[4]对输出的数据也要检查，数据库里的值有可能会在一个大网站的多处都有输出，即使在输入做了编码等操作，在各处的输出点时也要进行安全检查。\n[5] 在发布应用程序之前测试所有已知的威胁。', '代码执行通常指将可执行代码注入到当前页面中，比如PHP的eval函数，可以将字符串代表的代码作为PHP代码执行，当前用户能够控制这段字符串时，将产生代码执行漏洞。', '0', '0');
	INSERT INTO `domain_classification` VALUES ('8', '1', '命令执行', '[1]能使用脚本解决的工作，不要调用其它程序处理。尽量少用执行命令的函数，并在disable_functions中将其禁用。\n[2]对于可控点是程序参数的情况，使用escapeshellcmd函数进行过滤。\n[3]对于可控点是参数值的情况,使用escapeshellarg函数进行过滤。\n[4]参数值尽量使用单引号包裹，并在接入前使用addslashes进行转义。', '脚本语言有点事简洁，方便，但也伴随一些问题，比如运行速度慢，无法接触系统底层，如果我们开发的应用(特别是企业级的一些应用)需要除去web的特殊功能时，就需要调用一些外部程序。在应用需要去调用外部程序去处理的情况下，就会用到一些执行系统命令的函数。如php中的system,exec,shell_exec等，当用户可以控制命令执行的函数时，可以恶意注入系统命令到正常命令中，造成命令执行', '0', '0');
	INSERT INTO `domain_classification` VALUES ('9', '1', 'URL跳转', '[1]若跳转的URL事先是可以确定的，包括url和参数的值，则可以在后台先配置好，URL参数只需传对应URL的索引即可，通过索引找到对应具体URL再进行跳转；\n[2]若跳转的URL事先不确定，则必须在跳转的时候对URL进行按规则校验。', 'URL跳转漏洞出现在应用接受参数并将用户重定向到该参数值，并且没有对该值进行任何校验的时候。网站通过以GET或者POST的方式接收用户输入的URL，跳转到一个攻击者控制的网站，可能导致跳转过去的用户被精心设置的钓鱼页面骗走自己的个人信息和登录口令，也可能引发的XSS漏洞。', '0', '0');
	INSERT INTO `domain_classification` VALUES ('10', '1', '文件包含', '[1]尝试对所有提交的输入中可能包含文件地址（包括服务器本地文件及远程文件）进行严格的检查，参数中不允许出现 ../ 之类的目录跳转符；\n[2]严格检查 include 类的文件包含函数中的参数是否外界可控；\n[3]将关键的过滤步骤放在服务端执行；', '文件包含漏洞是一种针对依赖于脚本运行时间的Web应用程序漏洞。当应用程序使用攻击者控制的变量构建可执行代码的路径时，一旦其运行攻击者控制运行时执行哪个文件，则会引发该漏洞。该漏洞可被利用在服务器上远程执行命令，攻击者可以把上传的静态文件或网站日志文件作为代码执行，获取服务器权限，并进一步篡改用户和交易数据，恶意删除网站等。', '0', '0');
	INSERT INTO `domain_classification` VALUES ('11', '1', '越权', '[1]对用户访问角色的权限进行严格的检查及限制；\n[2]在一些操作时可以使用session对用户的身份进行判断和控制', '由于没有对用户访问角色的权限进行严格的检查及限制，导致当前账号可对其他账号进行相关操作，如查看、修改等。对低权限对高权限账户的操作为纵向越权，相同权限账户之间的操作成为横向越权也称水平越权。', '0', '0');
	INSERT INTO `domain_classification` VALUES ('12', '1', '设计缺陷/逻辑错误', '[1]在输入接口设置验证；\n[2]注册界面的接口不要返回太多敏感信息，以防遭到黑客制作枚举字典；\n[3]验证码请不要以短数字来甚至，最好是以字母加数字进行组合，并且验证码需要设定时间期限。', '逻辑错误漏洞是指由于程序逻辑不严或逻辑太复杂，导致一些逻辑分支不能够正常处理或处理错误。', '0', '0');
	INSERT INTO `domain_classification` VALUES ('13', '1', '敏感信息泄露', '[1]如果是探针或测试页面等无用的程序建议删除，或者修改成难以猜解的名字；\n[2]不影响业务或功能的情况下删除或禁止访问泄露敏感信息页面；\n[3]在服务器端对相关敏感信息进行模糊化处理；\n[4]对服务器端返回的数据进行严格的检查，满足查询数据与页面显示数据一致。', '信息泄露漏洞是由于Web服务器或应用程序没有正确处理一些特殊请求，泄露Web服务器的一些敏感信息，如用户名、密码、源代码、服务器信息、配置信息等。', '0', '0');
	INSERT INTO `domain_classification` VALUES ('14', '1', '目录遍历', '[1]通过修改配置文件，禁止中间件（如IIS、apache、tomcat）的文件目录索引功能；\n[2]设置目录访问权限；\n[3]对传入的文件名参数进行过滤，并且判断是否是允许获取的文件类型，过滤回溯符../。', '目录遍历漏洞是攻击者向Web服务器发送请求，通过在URL中或在有特殊意义的目录中附加“…/”、或者附加“…/”的一些变形（如“…\\”或“…//”甚至其编码），导致攻击者能够访问未授权的目录，以及在Web服务器的根目录以外执行命令。', '0', '0');
	INSERT INTO `domain_classification` VALUES ('15', '1', 'SSRF漏洞', '[1]禁用不需要的协议，只允许HTTP和HTTPS请求，可以防止类似于file://, gopher://, ftp:// 等引起的问题；\n[2]白名单的方式限制访问的目标地址，禁止对内网发起请求；\n[3]过滤或屏蔽请求返回的详细信息，验证远程服务器对请求的响应是比较容易的方法。如果web应用是去获取某一种类型的文件。那么在把返回结果展示给用户之前先验证返回的信息是否符合标准；\n[4]验证请求的文件格式；\n[5]禁止跳转；\n[6]统一错误信息，避免用户可以根据错误信息来判断远端服务器的端口状态。', 'SSRF（Server-Side Request Forgery，服务器端请求伪造）：通俗的来说就是我们可以伪造服务器端发起的请求，从而获取客户端所不能得到的数据。SSRF漏洞形成的原因主要是服务器端所提供的接口中包含了所要请求的内容的URL参数，并且未对客户端所传输过来的URL参数进行过滤。', '0', '0');
	INSERT INTO `domain_classification` VALUES ('16', '1', '任意文件读取', '[1]过滤.(点)等可能的恶意字符：这个试用于能够修改线上代码，最为推荐的方法；\n[2]正则判断用户输入的参数的格式，看输入的格式是否合法：这个方法的匹配最为准确和细致，但是有很大难度，需要大量时间配置规则。', '通过提交专门设计的输入，攻击者就可以在被访问的文件系统中读取或写入任意内容，往往能够使攻击者从服务器上获取敏感信息文件。', '0', '0');
	INSERT INTO `domain_classification` VALUES ('17', '1', '弱口令', '[1]强制用户首次登录时修改默认口令，或是使用用户自定义初始密码的策略；\n[2]完善密码策略，信息安全最佳实践的密码策略为8位（包括）以上字符，包含数字、大小写字母、特殊字符中的至少3种；\n[3]增加人机验证机制，限制ip访问次数。', '由于网站用户帐号存在弱口令，导致攻击者通过弱口令可轻松登录到网站中，从而进行下一步的攻击，如上传webshell，获取敏感数据；另外攻击者利用弱口令登录网站管理后台，可执行任意管理员的操作。', '0', '0');
	INSERT INTO `domain_classification` VALUES ('18', '1', '未授权的访问/权限绕过', '[1]页面进行严格的访问权限的控制以及对访问角色进行权限检查；\n[2]可以使用session对用户的身份进行判断和控制。', '由于没有对网站敏感页面进行登录状态、访问权限的检查，导致攻击者可未授权访问，获取敏感信息及进行未授权操作。', '0', '0');
	INSERT INTO `domain_classification` VALUES ('19', '0', '暂无', '暂无', '暂无', '0', '0');";
							if($Db->exec($install_sql_classification)){
								$this->json(['status'=>1,'msg'=>'插入漏洞分类数据成功！']);
							} else {
								$this->json(['status'=>0,'msg'=>'插入漏洞分类数据失败！']);
							}
						break;
					case md5("domain_logs"):
							$domain_logs = "CREATE TABLE IF NOT EXISTS `domain_logs` (
							  `id` int(11) NOT NULL AUTO_INCREMENT,
							  `crate_time` int(11) NOT NULL COMMENT '登录时间',
							  `ip` varchar(50) NOT NULL COMMENT 'IP地址',
							  `userid` int(11) NOT NULL COMMENT '用户id',
							  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '类型 0:密码错误 1:登录 2:退出登录 3:锁定用户 4:增加 5:删除 6:修改 7:查询 8:非法请求 9:下载报告',
							  `msg` varchar(100) NOT NULL COMMENT '操作',
							  PRIMARY KEY (`id`)
							) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户登录日志';";
							if($Db->exec($domain_logs)){
								$this->json(['status'=>1,'msg'=>'用户登录日志表创建成功！']);
							} else {
								$this->json(['status'=>0,'msg'=>'用户登录日志表创建失败！']);
							}
						break;
					case md5("domain_member"):
							$domain_member = "CREATE TABLE IF NOT EXISTS `domain_member` (
							  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
							  `uuid` varchar(255) NOT NULL COMMENT 'uuid',
							  `username` varchar(20) DEFAULT '路人甲' COMMENT '用户昵称',
							  `salt` varchar(9) NOT NULL DEFAULT '暂无' COMMENT '加密salt',
							  `password` varchar(32) DEFAULT NULL COMMENT '用户密码',
							  `create_at` varchar(11) DEFAULT '0' COMMENT '创建时间',
							  `update_at` varchar(11) DEFAULT '0' COMMENT '更新时间',
							  `login_ip` varchar(20) DEFAULT '0' COMMENT '登录IP',
							  `email` varchar(50) DEFAULT NULL COMMENT '邮箱',
							  `phone` varchar(50) DEFAULT NULL COMMENT '手机号码',
							  `img` varchar(100) DEFAULT NULL COMMENT '头像',
							  PRIMARY KEY (`id`),
							  KEY `username` (`username`) USING BTREE,
							  KEY `password` (`password`) USING BTREE
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户表';";
							if($Db->exec($domain_member)){
								$this->json(['status'=>1,'msg'=>'用户表创建成功！']);
							} else {
								$this->json(['status'=>0,'msg'=>'用户表创建失败！']);
							}
						break;
					case md5("install_sql_member"):
							$install_sql_member = "INSERT INTO `domain_member` VALUES ('1', 'c394e47f-350f-0eea-7cc9-67d300b57502', 'admin', '6JTD4IKN', '199346966b7fa9a77cc562b712a7dc47', '0', '1631936587', '', '', null, './public/index/img/user.svg');";
							if($Db->exec($install_sql_member)){
								$this->json(['status'=>1,'msg'=>'插入用户数据成功！']);
							} else {
								$this->json(['status'=>0,'msg'=>'插入用户数据失败！']);
							}
						break;
					case md5("domain_post"):
							$domain_post = "
							CREATE TABLE IF NOT EXISTS `domain_post` (
							  `id` int(11) NOT NULL AUTO_INCREMENT,
							  `session` varchar(255) DEFAULT '0' COMMENT '临时报告查看授权id',
							  `title` varchar(255) NOT NULL COMMENT '报告标题',
							  `content` text NOT NULL COMMENT '报告内容',
							  `cate_id` int(11) NOT NULL COMMENT '分类id',
							  `user_id` int(11) NOT NULL COMMENT '提交者id',
							  `bugLevel` tinyint(1) NOT NULL DEFAULT '1' COMMENT '无影响:1, 低危:2, 中危:3, 高危:4',
							  `bugDetail` varchar(255) NOT NULL COMMENT '漏洞URL',
							  `repair_time` int(11) DEFAULT NULL COMMENT '修复时间',
							  `company` varchar(50) NOT NULL,
							  `description` varchar(255) NOT NULL DEFAULT '无' COMMENT '漏洞描述',
							  `suggestions` varchar(255) NOT NULL DEFAULT '无' COMMENT '修复建议',
							  `creation_time` int(11) NOT NULL COMMENT '创建时间',
							  `update_time` int(11) NOT NULL COMMENT '修改时间',
							  PRIMARY KEY (`id`) USING BTREE,
							  KEY `cate_id` (`cate_id`) USING BTREE,
							  KEY `user_id` (`user_id`) USING BTREE
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='漏洞表';";
							if($Db->exec($domain_post)){
								$this->json(['status'=>1,'msg'=>'漏洞表创建成功！']);
							} else {
								$this->json(['status'=>0,'msg'=>'漏洞表创建失败！']);
							}
						break;
					case md5("domain_project_classification"):
							$domain_project_classification = "
							CREATE TABLE IF NOT EXISTS `domain_project_classification` (
							  `id` int(11) NOT NULL AUTO_INCREMENT,
							  `title` varchar(100) NOT NULL DEFAULT '暂无' COMMENT '分类标题',
							  `creation_time` int(11) NOT NULL,
							  `update_time` int(11) NOT NULL,
							  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父分类ID',
							  PRIMARY KEY (`id`) USING BTREE
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='项目分类表';";
							if($Db->exec($domain_project_classification)){
								$this->json(['status'=>1,'msg'=>'项目分类表创建成功！']);
							} else {
								$this->json(['status'=>0,'msg'=>'项目分类表创建失败！']);
							}
						break;
					case md5("domain_template"):
							$domain_template = "
								CREATE TABLE IF NOT EXISTS `domain_template` (
								  `id` int(11) NOT NULL AUTO_INCREMENT,
								  `uuid` varchar(255) NOT NULL COMMENT '随机数',
								  `name` varchar(100) NOT NULL COMMENT '模板名称',
								  `file_path` varchar(255) NOT NULL COMMENT '模板路径',
								  `add_time` int(11) NOT NULL COMMENT '添加时间',
								  PRIMARY KEY (`id`)
								) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='模板表';";
							if($Db->exec($domain_template)){
								$this->json(['status'=>1,'msg'=>'模板表创建成功！']);
							} else {
								$this->json(['status'=>0,'msg'=>'模板表创建失败！']);
							}
						break;
					case md5("install_sql_template"):
							$install_sql_template = "INSERT INTO `domain_template` VALUES ('1', '656b721dee262140946d1120f35f84e3', '默认', '3b2bd38d2e911dc033217dc96cd6675d.docx', '1600133289');";
							if($Db->exec($install_sql_template)){
								$this->json(['status'=>1,'msg'=>'插入模板数据成功！']);
							} else {
								$this->json(['status'=>0,'msg'=>'插入模板数据失败！']);
							}
						break;
					default:
							$this->json(['status'=>0,'msg'=>'程序异常！']);
						break;
				}
			} elseif ($type == 'yes') {
				@file_put_contents(ROOT_PATH."/runtime/install.lock", "1");
			}
		} else {
			$token = md5(code().time().code());
	      	$_SESSION['token'] = $token;
	      	$this->smarty->assign('token',$token);
       		$this->smarty->display('install/index.tpl');
		}
	}
}
?>