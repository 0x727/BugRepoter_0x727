{include file="../header.tpl"}
<div class="content-wrapper">
	<div class="row gutters">
		<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
			<div class="card">
				<div class="card-header-lg">
					<h4>网站设置</h4>
				</div>
				<div class="card-body">
	                <div class="row gutters">
	                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
	                        <div class="row gutters">
	                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
	                                <div class="field-wrapper">
	                                    <input type="text" class="form-control" placeholder="请输入标题" value="{$name}" name="name">
	                                    <div class="field-placeholder">网站标题</div>
	                                    <div class="form-text">
	                                        请输入网站标题
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
	                                <div class="field-wrapper">
	                                    <div class="checkbox-container form-control">
	                                        <div class="form-check form-check-inline">
	                                            <input class="form-check-input" type="radio" name="repair_time" value="0" {if $legitimate_type == 0 } checked="checked" {/if}>
	                                            <label class="form-check-label" for="inlineRadio1">关闭模式</label>
	                                        </div>
	                                        <div class="form-check form-check-inline">
	                                            <input class="form-check-input" type="radio" name="repair_time" value="1" {if $legitimate_type == 1 } checked="checked" {/if}>
	                                            <label class="form-check-label" for="inlineRadio2">动态防护后台地址模式</label>
	                                        </div>
	                                        <div class="form-check form-check-inline">
	                                            <input class="form-check-input" type="radio" name="repair_time" value="2" {if $legitimate_type == 2 } checked="checked" {/if}>
	                                            <label class="form-check-label" for="inlineRadio2">限制IP模式</label>
	                                        </div>
	                                    </div>
	                                    <div class="field-placeholder">安全模式 <span class="text-danger">*</span></div>
	                                    <div class="form-text">
	                                        请选择安全模式
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
	                                <div class="field-wrapper">
	                                    <textarea class="form-control" rows="5" name="legitimate_ip" placeholder="127.0.0.1
127.0.0.2" rows="5">{$legitimate_ip}</textarea>
	                                    <div class="field-placeholder">限制IP</div>
	                                    <div class="form-text">
	                                        请输入限制IP
	                                    </div>
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
<script>
	function save()
	{
		var legitimate_ip = $("textarea[name='legitimate_ip']").val();
		var repair_time = $("input[name='repair_time']:checked").val();
		var name = $("input[name='name']").val();
		var token = "{$token}";
		$.post("./index.php?m=Setup&a=index",{
			legitimate_ip:legitimate_ip,
			name:name,
			repair_time:repair_time,
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
					window.location.reload();
				});
			}
		},"json")
	}
</script>
{include file="../footer.tpl"}