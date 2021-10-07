{include file="../header.tpl"}
<link rel="stylesheet" href="./public/index/vendor/dropzone/dropzone.min.css" />
<div class="content-wrapper">
	<div class="row gutters">
		<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
			<div class="card">
				<div class="card-header-lg">
					<h4>账户设置</h4>
				</div>
				<div class="card-body">
	                <div class="row gutters">
	                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
	                        <div class="row gutters">
	                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
	                                <img src="{{$user_info['img']}}" onerror="javascript:this.src='./public/index/img/user.svg';" class="img-fluid change-img-avatar" alt="Image">
	                            </div>
	                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-12">
	                                <div id="dropzone-sm" class="mb-4">
	                                    <form action="{$menu['user_img']}" class="dropzone needsclick" id="upload">
	                                        <div class="dz-message needsclick">
	                                            <button type="button" class="dz-button">修改头像</button>
	                                        </div>
	                                    </form>
	                                </div>
	                            </div>
	                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
	                                <div class="field-wrapper">
	                                    <input type="text" class="form-control" placeholder="请输入昵称" value="{{$user_info['username']}}" disabled="disabled">
	                                    <div class="field-placeholder">用户昵称</div>
	                                </div>
	                            </div>
	                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
	                                <div class="field-wrapper">
	                                    <input type="password" id="password" class="form-control" placeholder="请输入密码">
	                                    <div class="field-placeholder">用户密码</div>
	                                </div>
	                            </div>
	                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
	                                <div class="field-wrapper">
	                                    <input type="text" id="email" class="form-control" placeholder="请输入邮箱" value="{{$user_info['email']}}">
	                                    <div class="field-placeholder">邮箱</div>
	                                </div>
	                            </div>
	                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
	                                <div class="field-wrapper">
	                                    <input type="text" id="phone" class="form-control" placeholder="请输入手机号" value="{{$user_info['phone']}}">
	                                    <div class="field-placeholder">手机号</div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
							<button class="btn btn-primary mb-3" onclick="save()">保存</button>
						</div>
	                </div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="./public/index/vendor/dropzone/dropzone.min.js"></script>
<script>
	function save()
	{
		var password = $("#password").val();
		var email = $("#email").val();
		var phone = $("#phone").val();
		var token = "{$token}";
		$.post("{$menu['user_index']}",{
			password:password,
			email:email,
			phone:phone,
			token:token,
		},function(data){
			if(data.status == 0){
				layer.msg(data.msg, {
					icon: 2
				}, function(){

				});
			} else {
				layer.msg(data.msg, {
					icon: 1
				}, function(){
					if(password){
						window.location.href = "{$menu['login_logout']}"
					} else {
						window.location.reload();
					}
				});
			}
		},"json")
	}
</script>
{include file="../footer.tpl"}