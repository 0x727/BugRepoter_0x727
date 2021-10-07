{include file="../header.tpl"}
    <link rel="stylesheet" href="./public/index/vendor/bs-select/bs-select.css" />
    <link rel="stylesheet" href="./public/index/vendor/summernote/summernote-bs4.css" />
    <div class="content-wrapper">
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">提交漏洞</div>
                    </div>
                    <div class="card-body">
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="field-wrapper">
                                    <input class="form-control" type="text" name="name" value="{$post.title}">
                                    <div class="field-placeholder">漏洞名称 <span class="text-danger">*</span></div>
                                    <div class="form-text">
                                        请输入漏洞名称
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="field-wrapper">
                                    <input class="form-control" type="text" name="bugDetail" value="{$post.bugDetail}">
                                    <div class="field-placeholder">漏洞URL <span class="text-danger">*</span></div>
                                    <div class="form-text">
                                        请输入漏洞URL
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="field-wrapper">
                                    <div class="field-wrapper">
                                        <select class="select-single js-states" title="项目" data-live-search="true" name="company">
                                            <option value="" >--请选择项目--</option>
                                            {foreach from=$project_classification item=v}
                                                {if $v['pid'] == 0}
                                                    <option value="{$v.id}" {if $post['company'] == $v['id']} selected {/if}>{$v.title}</option>
                                                {/if}
                                            {/foreach}
                                        </select>
                                        <div class="field-placeholder">项目</div>
                                    </div>
                                    <div class="form-text">
                                        请选择项目
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="field-wrapper">
                                    <div class="field-wrapper">
                                        <select class="select-single js-states" title="漏洞类型" data-live-search="true" name="cate_pid" id="cate_pid">
                                            {foreach from=$classification item=v}
                                                {if $v['pid'] == 0}
                                                    <option value="{$v.id}" {if $post['cate_pid'] == $v['id']} selected {/if}>{$v.title}</option>
                                                {/if}
                                            {/foreach}
                                        </select>
                                        <div class="field-placeholder">漏洞类型</div>
                                    </div>
                                    <div class="form-text">
                                        请选择漏洞类型
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="field-wrapper">
                                    <div class="field-wrapper">
                                        <select class="select-single js-states" title="漏洞子级类型" data-live-search="true" name="cate_id" id="cate_id">
                                            {foreach from=$classification item=v}
                                                {if $v['pid'] != 0}
                                                    <option value="{$v.id}" {if $post['cate_id'] == $v['id']} selected {/if}>{$v.title}</option>
                                                {/if}
                                            {/foreach}
                                        </select>
                                        <div class="field-placeholder">漏洞子级类型</div>
                                    </div>
                                    <div class="form-text">
                                        请选择漏洞子级类型
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="field-wrapper">
                                    <div class="checkbox-container form-control">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="bugLevel" value="2" {if $post['bugLevel'] == '2'} checked="checked"  {/if}>
                                            <label class="form-check-label" for="inlineRadio1">低危</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="bugLevel" value="3" {if $post['bugLevel'] == '3'} checked="checked"  {/if}>
                                            <label class="form-check-label" for="inlineRadio2">中危</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="bugLevel" value="4" {if $post['bugLevel'] == '4'} checked="checked"  {/if}>
                                            <label class="form-check-label" for="inlineRadio3">高危</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="bugLevel" value="5" {if $post['bugLevel'] == '5'} checked="checked"  {/if}>
                                            <label class="form-check-label" for="inlineRadio3">严重</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="bugLevel" value="1" {if $post['bugLevel'] == '1'} checked="checked"  {/if}>
                                            <label class="form-check-label" for="inlineRadio4">无影响</label>
                                        </div>
                                    </div>
                                    <div class="field-placeholder">危害等级 <span class="text-danger">*</span></div>
                                    <div class="form-text">
                                        请选择危害等级
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="field-wrapper">
                                    <textarea class="form-control" rows="5" name="description">{$post.description}</textarea>
                                    <div class="field-placeholder">漏洞描述</div>
                                    <div class="form-text">
                                        请输入漏洞描述
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="field-wrapper">
                                    <div class="summernote"></div>
                                    <div class="field-placeholder">漏洞内容</div>
                                    <div class="form-text">
                                        请输入漏洞内容
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="field-wrapper">
                                    <textarea class="form-control" rows="5" name="suggestions">{$post.suggestions}</textarea>
                                    <div class="field-placeholder">修复方案</div>
                                    <div class="form-text">
                                        请输入修复方案
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="field-wrapper">
                                    <div class="checkbox-container form-control">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="repair_time" value="2" {if $post['repair_time'] == '否'} checked="checked"  {/if}>
                                            <label class="form-check-label" for="inlineRadio1">否</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="repair_time" value="1" {if $post['repair_time'] == '是'} checked="checked"  {/if}>
                                            <label class="form-check-label" for="inlineRadio2">是</label>
                                        </div>
                                    </div>
                                    <div class="field-placeholder">漏洞是否修复 <span class="text-danger">*</span></div>
                                    <div class="form-text">
                                        请选择漏洞是否修复
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <input type="hidden" name="id" value="{$post.id}">
                                <input type="hidden" name="token" value="{$token}">
                                <button class="btn btn-primary" type="button" id="go_submit">提交</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="./public/index/vendor/bs-select/bs-select.min.js"></script>
    <script src="./public/index/vendor/bs-select/bs-select-custom.js"></script>
    <script src="./public/index/vendor/summernote/summernote-bs4.js"></script>
    <script src="./public/index/vendor/summernote/lang/summernote-zh-CN.min.js"></script>
    <script>
        var classification_json = {{$classification_json}}
        $(document).ready(function() {
            var $summernote = $('.summernote').summernote({
                height: 210,
                tabsize: 2,
                focus: true,
                lang:'zh-CN',
                toolbar: [
                    ['font', ['bold', 'underline', 'clear']],
                    ['para', ['ul', 'ol']],
                    ['insert', ['picture']],
                    ['view', ['fullscreen', 'codeview', 'help']],
                ],
                //调用图片上传
                callbacks: {
                    onImageUpload: function (files) {
                        sendFile($summernote, files[0]);
                    },
                    onPaste: function (ne) {
                        var _clipboardData = (ne.originalEvent || ne).clipboardData || window.clipboardData;
                        var bufferHtml = _clipboardData.getData("text/html");
                        var bufferText = _clipboardData.getData("text/plain")
                        ne.preventDefault ? ne.preventDefault() : (ne.returnValue = false);
                        if (bufferHtml == "") {
                            document.execCommand("insertText",false,bufferText);
                        } else {
                            document.execCommand("insertHTML",false,bufferHtml);
                        }
                    }
                }
            });

            $('.summernote').summernote('code', `{$post.content}`);
            
            // ajax上传图片
            function sendFile($summernote, file) {
                var formData = new FormData();
                formData.append("file", file);
                $.ajax({
                    url: "{$menu['public_deup_img']}",
                    data: formData,
                    cache: false,
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data) {
                        if(data.status == '1'){
                            $summernote.summernote('insertImage', data.data, function ($image) {
                                $image.attr('src', data.data);
                            });
                        } else {
                            layer.msg(data.msg, {
                                icon: 2
                            }, function(){
                            });
                        }
                    }
                });
            }
            
            // 提交
            $("#go_submit").click(function() {
                var id = $("input[name='id']").val();
                var name = $("input[name='name']").val();
                var bugDetail = $("input[name='bugDetail']").val();
                var company = $("select[name='company']").find("option:selected").val();
                var bugLevel = $("input[name='bugLevel']:checked").val();
                var repair_time = $("input[name='repair_time']:checked").val();
                var cate_id = $("select[name='cate_id']").find("option:selected").val();
                var description = $("textarea[name='description']").val();
                var suggestions = $("textarea[name='suggestions']").val();
                var content = $('.summernote').summernote('code');
                var token = $("input[name='token']").val();
                
                if(name==""){
                    layer.msg("漏洞名称不能为空", {
                        icon: 2
                    }, function(){
                    });
                    return false
                }
                if(bugDetail==""){
                    layer.msg("漏洞URL不能为空", {
                        icon: 2
                    }, function(){
                    });
                    return false
                }
                if(!fIsUrL(bugDetail)){
                    layer.msg("漏洞URL格式错误", {
                        icon: 2
                    }, function(){
                    });
                    return false
                }
                if(company==""){
                    layer.msg("项目不能为空", {
                        icon: 2
                    }, function(){
                    });
                    return false
                }
                if(cate_id==""){
                    layer.msg("漏洞类型不能为空", {
                        icon: 2
                    }, function(){
                    });
                    return false
                }
                if(bugLevel==""){
                    layer.msg("漏洞等级不能为空", {
                        icon: 2
                    }, function(){
                    });
                    return false
                }
                if(description==""){
                    layer.msg("漏洞描述不能为空", {
                        icon: 2
                    }, function(){
                    });
                    return false
                }
                if(content==""){
                    layer.msg("漏洞内容不能为空", {
                        icon: 2
                    }, function(){
                    });
                    return false
                }
                if(suggestions==""){
                    layer.msg("修复建议不能为空", {
                        icon: 2
                    }, function(){
                    });
                    return false
                }
                
                $.post("{$menu['products_edit_index']}",{
                    id:id,
                    name:name,
                    bugDetail:bugDetail,
                    company:company,
                    cate_id:cate_id,
                    bugLevel:bugLevel,
                    description:description,
                    content:content,
                    suggestions:suggestions,
                    repair_time:repair_time,
                    token:token,
                },function(data){
                    if(data.status == '1'){
                        layer.msg(data.msg, {
                            icon: 1
                        }, function(){
                            window.location.href = "{$menu['products_index']}"
                        });
                    } else if(data.status == '2') {
                        layer.msg(data.msg, {
                            icon: 2
                        }, function(){
                        });
                    } else {
                        layer.msg(data.msg, {
                            icon: 2
                        }, function(){
                            window.location.reload()
                        });
                    }
                },"json");
            });

            //修改选择框
            $("#cate_pid").change(function(){
                var selected = $(this).children('option:selected').val();
                var html = "";
                var description = ""
                var suggestions = ""
                $("#cate_id").html("");
                for (var bb in classification_json) {
                    if(classification_json[bb].pid == selected){
                        if(description == "" && suggestions == ""){
                            description = classification_json[bb].description
                            suggestions = classification_json[bb].suggestions
                        }
                        html += '<option value="'+classification_json[bb].id+'">'+classification_json[bb].title+'</option>'
                    }
                }
                $("textarea[name='description']").val(description)
                $("textarea[name='suggestions']").val(suggestions)
                $("#cate_id").html(html);
            });
            //修改选择框
            $("#cate_id").change(function(){
                var selected = $(this).children('option:selected').val();
                var description = ""
                var suggestions = ""
                for (var bb in classification_json) {
                    if(classification_json[bb].id == selected){
                        if(description == "" && suggestions == ""){
                            description = classification_json[bb].description
                            suggestions = classification_json[bb].suggestions
                        }
                    }
                }
                $("textarea[name='description']").val(description)
                $("textarea[name='suggestions']").val(suggestions)
            });
        });
    </script>
{include file="../footer.tpl"}