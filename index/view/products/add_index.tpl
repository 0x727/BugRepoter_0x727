{include file="../header.tpl"}
    <link rel="stylesheet" href="./public/index/vendor/bs-select/bs-select.css" />
    <link rel="stylesheet" href="./public/index/vendor/summernote/summernote-bs4.css" />
    <div class="content-wrapper">
        <div class="row gutters">
            {for $foo=1 to $num}
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">提交漏洞({$foo})</div>
                        </div>
                        <div class="card-body">
                            <div class="row gutters">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="field-wrapper">
                                        <input class="form-control" type="text" name="post[{$foo}][name]" value="">
                                        <div class="field-placeholder">漏洞名称 <span class="text-danger">*</span></div>
                                        <div class="form-text">
                                            请输入漏洞名称
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="field-wrapper">
                                        <input class="form-control" type="text" name="post[{$foo}][bugDetail]" value="">
                                        <div class="field-placeholder">漏洞URL <span class="text-danger">*</span></div>
                                        <div class="form-text">
                                            请输入漏洞URL
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="field-wrapper">
                                        <div class="field-wrapper">
                                            <select class="select-single js-states" title="项目" data-live-search="true" name="post[{$foo}][company]">
                                                <option value="" >--请选择项目--</option>
                                                {foreach from=$project_classification item=v}
                                                    {if $v['pid'] == 0}
                                                        <option value="{$v.id}">{$v.title}</option>
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
                                            <select class="select-single js-states" title="漏洞类型" data-live-search="true" name="post[{$foo}][cate_pid]" id="cate_pid_{$foo}">
                                                {foreach from=$classification item=v}
                                                    {if $v['pid'] == 0}
                                                        <option value="{$v.id}">{$v.title}</option>
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
                                            <select class="select-single js-states" title="漏洞子级类型" data-live-search="true" name="post[{$foo}][cate_id]" id="cate_id_{$foo}">
                                                {foreach from=$classification item=v}
                                                    {if $v['pid'] != 0}
                                                        <option value="{$v.id}">{$v.title}</option>
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
                                                <input class="form-check-input" type="radio" name="post[{$foo}][bugLevel]" value="2">
                                                <label class="form-check-label" for="inlineRadio1">低危</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="post[{$foo}][bugLevel]" value="3">
                                                <label class="form-check-label" for="inlineRadio2">中危</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="post[{$foo}][bugLevel]" value="4">
                                                <label class="form-check-label" for="inlineRadio3">高危</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="post[{$foo}][bugLevel]" value="5">
                                                <label class="form-check-label" for="inlineRadio3">严重</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="post[{$foo}][bugLevel]" value="1" checked="checked">
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
                                        <textarea class="form-control" rows="5" name="post[{$foo}][description]"></textarea>
                                        <div class="field-placeholder">漏洞描述</div>
                                        <div class="form-text">
                                            请输入漏洞描述
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="field-wrapper">
                                        <div id="summernote_{$foo}"></div>
                                        <div class="field-placeholder">漏洞内容</div>
                                        <div class="form-text">
                                            请输入漏洞内容
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="field-wrapper">
                                        <textarea class="form-control" rows="5" name="post[{$foo}][suggestions]"></textarea>
                                        <div class="field-placeholder">修复方案</div>
                                        <div class="form-text">
                                            请输入修复方案
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {/for}

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <input type="hidden" name="token" value="{$token}">
                            <button class="btn btn-primary" type="button" id="go_submit">提交</button>
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
    {for $foo=1 to $num}
        <script>
            $(document).ready(function() {
                var $summernote_{$foo} = $('#summernote_'+{$foo}).summernote({
                    height: 400,
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
                            var formData = new FormData();
                            formData.append("file", files[0]);
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
                                        $summernote_{$foo}.summernote('insertImage', data.data, function ($image) {
                                            $image.attr('src', data.data);
                                            $image.attr('style', "width:50%");
                                        });
                                    } else {
                                        layer.msg(data.msg, {
                                            icon: 2
                                        }, function(){
                                        });
                                    }
                                }
                            });
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
                _init({$foo})
            });
        </script>
    {/for}
    <script>
        var num = {$num}
        var classification_json = {{$classification_json}}
        
        // 提交
        $("#go_submit").click(function() {
            var data = [];
            var token = $("input[name='token']").val();
            for (var i=1;i<=num;i++){
                var name = $("input[name='post["+i+"][name]']").val();
                var bugDetail = $("input[name='post["+i+"][bugDetail]']").val();
                var company = $("select[name='post["+i+"][company]']").find("option:selected").val();
                var bugLevel = $("input[name='post["+i+"][bugLevel]']:checked").val();
                var cate_id = $("select[name='post["+i+"][cate_id]']").find("option:selected").val();
                var description = $("textarea[name='post["+i+"][description]']").val();
                var suggestions = $("textarea[name='post["+i+"][suggestions]']").val();
                var content = $('#summernote_'+i).summernote('code');
                if(name==""){
                    layer.msg("第"+i+"份报告，漏洞名称不能为空", {
                        icon: 2
                    }, function(){
                    });
                    return false
                }
                if(bugDetail==""){
                    layer.msg("第"+i+"份报告，漏洞URL不能为空", {
                        icon: 2
                    }, function(){
                    });
                    return false
                }
                if(!fIsUrL(bugDetail)){
                    layer.msg("第"+i+"份报告，漏洞URL格式错误", {
                        icon: 2
                    }, function(){
                    });
                    return false
                }
                if(company==""){
                    layer.msg("第"+i+"份报告，项目不能为空", {
                        icon: 2
                    }, function(){
                    });
                    return false
                }
                if(cate_id==""){
                    layer.msg("第"+i+"份报告，漏洞类型不能为空", {
                        icon: 2
                    }, function(){
                    });
                    return false
                }
                if(bugLevel==""){
                    layer.msg("第"+i+"份报告，漏洞等级不能为空", {
                        icon: 2
                    }, function(){
                    });
                    return false
                }
                if(description==""){
                    layer.msg("第"+i+"份报告，漏洞描述不能为空", {
                        icon: 2
                    }, function(){
                    });
                    return false
                }
                if(content==""){
                    layer.msg("第"+i+"份报告，漏洞内容不能为空", {
                        icon: 2
                    }, function(){
                    });
                    return false
                }
                if(suggestions==""){
                    layer.msg("第"+i+"份报告，修复建议不能为空", {
                        icon: 2
                    }, function(){
                    });
                    return false
                }
                data.push({
                    name:name,
                    bugDetail:bugDetail,
                    company:company,
                    cate_id:cate_id,
                    bugLevel:bugLevel,
                    description:description,
                    content:content,
                    suggestions:suggestions,
                });
            }
            $.post("{$menu['add_index']}",{
                data:JSON.stringify(data),
                num:num,
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

        function _init(num){
            $("#summernote_"+num).summernote('code', '<p style="font-family: Arial, Helvetica, sans-serif; font-size: 12px;"><span class="Apple-style-span" style="color: rgb(255, 102, 102);">截图、本地图片可直接复制粘贴进编辑器中</span><br></p><p style="font-family: Arial, Helvetica, sans-serif; font-size: 12px;"><br></p>');

            //修改选择框
            $("#cate_pid_"+num).change(function(){
                var selected = $(this).children('option:selected').val();
                var html = "";
                var description = ""
                var suggestions = ""
                $("#cate_id_"+num).html("");
                for (var bb in classification_json) {
                    if(classification_json[bb].pid == selected){
                        if(description == "" && suggestions == ""){
                            description = classification_json[bb].description
                            suggestions = classification_json[bb].suggestions
                        }
                        html += '<option value="'+classification_json[bb].id+'">'+classification_json[bb].title+'</option>'
                    }
                }
                $("textarea[name='post["+num+"][description]']").val(description)
                $("textarea[name='post["+num+"][suggestions]']").val(suggestions)
                $("#cate_id_"+num).html(html);
            });

            //修改选择框
            $("#cate_id_"+num).change(function(){
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
                $("textarea[name='post["+num+"][description]']").val(description)
                $("textarea[name='post["+num+"][suggestions]']").val(suggestions)
            });
        }
    </script>
{include file="../footer.tpl"}