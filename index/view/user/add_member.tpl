{include file="../header.tpl"}
    <div class="content-wrapper">
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">添加用户</div>
                    </div>
                    <div class="card-body">
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="field-wrapper">
                                    <input class="form-control" type="text" name="username" value="">
                                    <div class="field-placeholder">用户名 <span class="text-danger">*</span></div>
                                    <div class="form-text">
                                        请输入用户名
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="field-wrapper">
                                    <input class="form-control" type="password" name="password" value="">
                                    <div class="field-placeholder">密码 <span class="text-danger">*</span></div>
                                    <div class="form-text">
                                        请输入密码
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="field-wrapper">
                                    <input class="form-control" type="text" name="email" value="">
                                    <div class="field-placeholder">邮箱 <span class="text-danger">*</span></div>
                                    <div class="form-text">
                                        请输入邮箱
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="field-wrapper">
                                    <input class="form-control" type="text" name="phone" value="">
                                    <div class="field-placeholder">手机号 <span class="text-danger">*</span></div>
                                    <div class="form-text">
                                        请输入手机号
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <input type="hidden" name="token" value="{$token}">
                                <button class="btn btn-primary" type="button" id="go_submit">提交</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function() {
            $("#go_submit").click(function() {
                var username = $("input[name='username']").val();
                var password = $("input[name='password']").val();
                var email = $("input[name='email']").val();
                var phone = $("input[name='phone']").val();
                var token = $("input[name='token']").val();
                
                if(username==""){
                    layer.msg('用户名不能为空', {
                        icon: 2
                    }, function(){

                    });
                    return false
                }
                if(password==""){
                    layer.msg('密码不能为空', {
                        icon: 2
                    }, function(){
                        
                    });
                    return false
                }
                
                $.post("{$menu['user_add_member']}",{
                    username:username,
                    password:password,
                    email:email,
                    phone:phone,
                    token:token,
                },function(data){
                    if(data.status == '1'){
                        layer.msg(data.msg, {
                            icon: 1
                        }, function(){
                            window.location.href = "{$menu['user_member']}"
                        });
                    } else {
                        layer.msg(data.msg, {
                            icon: 2
                        }, function(){
                            window.location.reload()
                        });
                    }
                },"json");
            })
        })
    </script>
{include file="../footer.tpl"}