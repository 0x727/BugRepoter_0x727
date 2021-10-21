<!Doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="Responsive Bootstrap4 Dashboard Template">
		<meta name="author" content="ParkerThemes">
		<link rel="shortcut icon" href="./public/index/img/fav.png" />
		<title>{$system_config['name']}-安装程序</title>
		<link rel="stylesheet" href="./public/index/css/bootstrap.min.css">
		<link rel="stylesheet" href="./public/index/fonts/style.css">
		<link rel="stylesheet" href="./public/index/css/main.css">
		<link rel="stylesheet" href="./public/index/vendor/particles/particles.css">
		<style>
            ::-webkit-scrollbar{
                width: 7px;
                height: 7px;
                background-color: #F5F5F5;
            }

            ::-webkit-scrollbar-track {
                box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
                -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
                border-radius: 10px;
                background-color: #F5F5F5;
            }

            ::-webkit-scrollbar-thumb{
                border-radius: 10px;
                box-shadow: inset 0 0 6px rgba(0, 0, 0, .1);
                -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, .1);
                background-color: #c8c8c8;
            }
            p{
                margin-bottom: 0rem !important;
                font-size: 15px;
            }
            strong{
                font-size: 17px;
            }
			

		</style>
	</head>

	<body class="subscribe-page">
		<div class="subscribe-screen" style="min-width: 50rem !important;">
            <div class="subscribe-header">
                <div class="subscribe-icon">
                    <i class="icon-drafts"></i>
                </div>
            </div>
            <div class="subscribe-body" id="one">
                <h1>使用协议</h1>
                <div class="form-control input-lg" style = "background-color: #fafafa;border: 1px solid #ccc;height: 400px;overflow-x: hidden;overflow-y: scroll;padding: 0 20px;width: 100%;text-align: left;" id="one_content">
                    <br>
                    <p>安装环境：PHP5.2+，推荐PHP7+。</p>
                    <strong style="color:red;margin:5px 0;">为了使你正确并合法的使用本软件，请你在使用前务必阅读清楚下面的协议条款：</strong>
                    <br>
                    <br>
                    <strong>一、本授权协议适用于 0x727自动化编写报告平台系统所有版本，0x727自动化编写报告平台系统官方拥有对本授权协议的最终解释权。</strong>
                    <br>
                    <br>
                    <strong>二、协议许可的权利 </strong>
                    <p>1、您可以在完全遵守本最终用户授权协议的基础上，将本软件应用于非商业用途，而不必支付软件版权授权费用。 </p>
                    <p>2、您可以在协议规定的约束和限制范围内修改 0x727自动化编写报告平台系统 源代码或界面风格以适应您的网站要求。 </p>
                    <p>3、您拥有使用本软件构建的网站全部内容所有权，并独立承担与这些内容的相关法律义务。 </p>
                    <br>
                    <strong>三、有限担保和免责声明 </strong>
                    <p>1、本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的。 </p>
                    <p>2、用户出于自愿而使用本软件，您必须了解使用本软件的风险，在尚未购买产品技术服务之前，我们不承诺对免费用户提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关责任。 </p>
                    <p>3、电子文本形式的授权协议如同双方书面签署的协议一样，具有完全的和等同的法律效力。您一旦开始确认本协议并安装 0x727自动化编写报告平台系统，即被视为完全理解并接受本协议的各项条款，在享有上述条款授予的权力的同时，受到相关的约束和限制。协议许可范围以外的行为，将直接违反本授权协议并构成侵权，我们有权随时终止授权，责令停止损害，并保留追究相关责任的权力。</p>
                    <br>
                    <strong>版权所有 &copy;2021-2021，0x727安全团队 保留所有权利。 </strong>
				</div>
                <div class="field-wrapper" style="margin-top: 1rem;">
                    <button class="btn btn-primary" type="button" onclick="next_step_one()">下一步</button>
                </div>
            </div>
            <div class="subscribe-body" id="three" style="display: none;">
                <h1>网站配置</h1>
                <div class="field-wrapper">
                    <input type="text" name="ip" placeholder="如, 127.0.0.1:3306">
                    <div class="field-placeholder">数据库地址:</div>
                </div>
                <div class="field-wrapper">
                    <input type="text" name="library_name" placeholder="库名">
                    <div class="field-placeholder">数据库:</div>
                </div>
                <div class="field-wrapper">
                    <input type="text" name="username" placeholder="请输入数据库账户（建议使用非ROOT账户）">
                    <div class="field-placeholder">数据库账户:</div>
                </div>
                <div class="field-wrapper">
                    <input type="text" name="password" placeholder="请输入数据库密码（允许范围：/^[a-zA-Z0-9_!@-^&]+$/）">
                    <div class="field-placeholder">数据库密码:</div>
                </div>
                <div class="field-wrapper" style="margin-top: 1rem;">
                    <button class="btn btn-primary" type="button" onclick="next_step_three()">下一步</button>
                </div>
            </div>
            <div class="subscribe-body" id="four" style="display: none;">
                <h1>正在安装</h1>
                <div class="form-control input-lg" style = "background-color: #fafafa; border: 1px solid #ccc; height: 200px; overflow-x: hidden; overflow-y: scroll; padding: 0px 20px; width: 100%;text-align: left;" id="four_content">
                	<br>
                </div>
                <div class="field-wrapper" style="margin-top: 1rem;">
                    <button class="btn btn-primary" type="button" onclick="next_step_four()">下一步</button>
                </div>
            </div>
            <div class="subscribe-body" id="five" style="display: none;">
                <h1>安装完成</h1>
                <p><h4>初始账户：admin</h4></p>
                <p><h4>初始密码：123456.</h4></p>
                <div class="field-wrapper" style="margin-top: 1rem;">
                    <button class="btn btn-primary" type="button" onclick="next_step_five()">跳转后台</button>
                </div>
            </div>
		</div>
	</body>
    <script src="./public/index/js/jquery.min.js"></script>
    <script src="./public/layer/layer.js"></script>
	<script>
        var install_data = []
		function next_step_one()
		{
            var scrollTop = $("#one_content").scrollTop();
            var windowHeight = $("#one_content").height();
            var scrollHeight = $("#one_content")[0].scrollHeight;
            // if(scrollTop+windowHeight!=scrollHeight){
            //     layer.msg("请阅读完协议！", {
            //         icon: 2
            //     }, function(){
                    
            //     });
            // } else {
    			$("#one").attr("style","display:none;");
    			$("#three").attr("style","display:;");
    			$(".subscribe-screen").attr("style","");
            // }
		}
		function next_step_two()
		{
			$("#one").attr("style","display:none;");
			$("#three").attr("style","display:;");
			$(".subscribe-screen").attr("style","");
		}
		function next_step_three()
		{
			var ip = $("input[name='ip']").val();
			var library_name = $("input[name='library_name']").val();
			var username = $("input[name='username']").val();
			var password = $("input[name='password']").val();
			var token = "{$token}"
            var re = /^[a-zA-Z]+$/;
            if(!re.test(library_name)){
                layer.msg('只能输入英文', {
                    icon: 2
                }, function(){
                });
            }else{
    			$.post("{$menu['install_index']}",{
    				ip:ip,
    				library_name:library_name,
    				username:username,
    				password:password,
    				token:token,
    				type:'three',
    			},function(data){
    				if(data.status == '1'){
                        layer.msg(data.msg, {
                            icon: 1
                        }, function(){
                            $("#three").attr("style","display:none;");
                            $("#four").attr("style","display:;");
                            install_sql(data.data)
                        });
                    } else {
                        layer.msg(data.msg, {
                            icon: 2
                        }, function(){
                        });
                    }
    			},"json");
            }
		}

        function install_sql(data)
        {
            for (var i = 0; i < data.length; i++) {
                $.post("{$menu['install_index']}",{
                    md5:data[i],
                    type:'four',
                },function(data){
                    if(data.status == '1'){
                        i++
                        $("#four_content").html($("#four_content").html()+"<div style=\"line-height: 25px;\">"+data.msg+"</div>")
                        $("#four_content")[0].scrollTop = $("#four_content")[0].scrollHeight
                    }
                },"json");
            }
            $.post("{$menu['install_index']}",{
                type:'yes',
            });
        }

		function next_step_four()
		{
            $("#four").attr("style","display:none;");
            $("#five").attr("style","display:;");
		}
		function next_step_five()
		{
			layer.msg("正在为您跳转后台~", {
                icon: 1
            }, function(){
                window.location.href = "{$menu['login_index']}"
            });
		}
	</script>
</html>
