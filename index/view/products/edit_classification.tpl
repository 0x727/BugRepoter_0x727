{include file="../header.tpl"}
    <div class="content-wrapper">
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">编辑项目分类</div>
                    </div>
                    <div class="card-body">
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="field-wrapper">
                                    <input class="form-control" type="text" name="name" value="{$classification.title}">
                                    <div class="field-placeholder">项目名称 <span class="text-danger">*</span></div>
                                    <div class="form-text">
                                        请输入项目名称
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="field-wrapper">
                                    <div class="field-wrapper">
                                        <select class="select-single js-states" title="上级项目分类" data-live-search="true" name="pid">
                                            <option value="0">父级</option>
                                            {foreach from=$list item=vo}
                                                <option value="{$vo.id}" {if $classification['pid'] == $vo['id']} selected {/if}>{$vo.title}</option>
                                            {/foreach}
                                        </select>
                                        <div class="field-placeholder">上级项目分类</div>
                                    </div>
                                    <div class="form-text">
                                        请选择项目分类
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <input type="hidden" name="token" value="{$token}">
                                <input type="hidden" name="id" value="{$classification.id}">
                                <button class="btn btn-primary" type="button" id="go_submit">提交</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="./public/index/vendor/bs-select/bs-select.css" />
    <script src="./public/index/vendor/bs-select/bs-select.min.js"></script>
    <script src="./public/index/vendor/bs-select/bs-select-custom.js"></script>
    <script>
        $(function() {
            $("#go_submit").click(function() {
                var id = $("input[name='id']").val();
                var pid = $("select[name='pid']").find("option:selected").val();
                var name = $("input[name='name']").val();
                var token = $("input[name='token']").val();
                
                if(name==""){
                    layer.msg('分类名称不能为空', {
                        icon: 2
                    }, function(){

                    });
                    return false
                }
                if(pid==""){
                    layer.msg('上级分类不能为空', {
                        icon: 2
                    }, function(){

                    });
                    return false
                }
                
                $.post("{$menu['edit_classification']}",{
                    id:id,
                    pid:pid,
                    name:name,
                    token:token,
                },function(data){
                    if(data.status == '1'){
                        layer.msg(data.msg, {
                            icon: 1
                        }, function(){
                            window.location.href = "{$menu['products_classification']}"
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