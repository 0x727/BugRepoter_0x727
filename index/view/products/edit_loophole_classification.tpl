{include file="../header.tpl"}
    <link rel="stylesheet" href="./public/index/vendor/bs-select/bs-select.css" />
    <div class="content-wrapper">
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">编辑分类</div>
                    </div>
                    <div class="card-body">
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="field-wrapper">
                                    <input class="form-control" type="text" name="name" value="{$classification.title}">
                                    <div class="field-placeholder">分类名称 <span class="text-danger">*</span></div>
                                    <div class="form-text">
                                        请输入分类名称
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="field-wrapper">
                                    <div class="field-wrapper">
                                        <select class="select-single js-states" title="上级分类" data-live-search="true" name="pid">
                                            <option value="0">父级</option>
                                            {foreach from=$list item=vo}
                                                <option value="{$vo.id}" {if $classification['pid'] == $vo['id']} selected {/if}>{$vo.title}</option>
                                            {/foreach}
                                        </select>
                                        <div class="field-placeholder">上级分类</div>
                                    </div>
                                    <div class="form-text">
                                        请选择分类
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="field-wrapper">
                                    <textarea class="form-control" rows="5" name="description">{$classification.description}</textarea>
                                    <div class="field-placeholder">漏洞描述</div>
                                    <div class="form-text">
                                        请输入漏洞描述
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="field-wrapper">
                                    <textarea class="form-control" rows="5" name="suggestions">{$classification.suggestions}</textarea>
                                    <div class="field-placeholder">修复建议</div>
                                    <div class="form-text">
                                        请输入修复建议
                                    </div>
                                </div>
                            </div>
                            {if $user_info['id'] == "1"}
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <input type="hidden" name="token" value="{$token}">
                                    <input type="hidden" name="id" value="{$classification.id}">
                                    <button class="btn btn-primary" type="button" id="go_submit">提交</button>
                                </div>
                            {/if}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="./public/index/vendor/bs-select/bs-select.min.js"></script>
    <script src="./public/index/vendor/bs-select/bs-select-custom.js"></script>
    {if $user_info['id'] == "1"}
        <script>
            $(function() {
                $("#go_submit").click(function() {
                    var id = $("input[name='id']").val();
                    var name = $("input[name='name']").val();
                    var pid = $("select[name='pid']").find("option:selected").val();
                    var description = $("textarea[name='description']").val();
                    var suggestions = $("textarea[name='suggestions']").val();
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
                    
                    $.post("{$menu['edit_loophole_classification_index']}",{
                        id:id,
                        name:name,
                        pid:pid,
                        description:description,
                        suggestions:suggestions,
                        token:token,
                    },function(data){
                        if(data.status == '1'){
                            layer.msg(data.msg, {
                                icon: 1
                            }, function(){
                                window.location.href = "{$menu['products_loophole_classification']}"
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
    {/if}
{include file="../footer.tpl"}