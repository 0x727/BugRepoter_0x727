{include file="../header.tpl"}
    <link rel="stylesheet" href="./public/index/vendor/bs-select/bs-select.css" />
    <link rel="stylesheet" href="./public/index/vendor/summernote/summernote-bs4.css" />
    <div class="content-wrapper">
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">验证漏洞是否修复</div>
                    </div>
                    <div class="card-body">
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="field-wrapper">
                                    <input class="form-control" type="text" value="{$post['title']}" disabled="disabled">
                                    <div class="field-placeholder">漏洞名称 <span class="text-danger">*</span></div>
                                    <div class="form-text">
                                        请输入漏洞名称
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="field-wrapper">
                                    <input class="form-control" type="text" value="{$post['bugDetail']}" disabled="disabled">
                                    <div class="field-placeholder">漏洞URL <span class="text-danger">*</span></div>
                                    <div class="form-text">
                                        请输入漏洞URL
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="field-wrapper">
                                    <div id="summernote_content"></div>
                                    <div class="field-placeholder">漏洞详情</div>
                                    <div class="form-text">
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="field-wrapper">
                                    <div id="summernote_repair"></div>
                                    <div class="field-placeholder">修复结果</div>
                                    <div class="form-text">
                                        请输入修复结果
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-body">
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
    <script src="./public/index/vendor/bs-select/bs-select.min.js"></script>
    <script src="./public/index/vendor/bs-select/bs-select-custom.js"></script>
    <script src="./public/index/vendor/summernote/summernote-bs4.js"></script>
    <script src="./public/index/vendor/summernote/lang/summernote-zh-CN.min.js"></script>
    <script>
        $(document).ready(function() {
            var $summernote_ = $('#summernote_repair').summernote({
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
                                    $summernote_.summernote('insertImage', data.data, function ($image) {
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
            var $summernoteb = $('#summernote_content').summernote({
                height: 400,
                tabsize: 2,
                focus: true,
                lang:'zh-CN',
                toolbar: [
                    ['view', ['fullscreen']],
                ],
            });
            _init()
        });
    </script>
    <script>
        // 提交
        $("#go_submit").click(function() {
            var token = $("input[name='token']").val();
            var content = $('#summernote_repair').summernote('code');
            var id = $("input[name='id']").val();
            if(content==""){
                layer.msg("修复结果不能为空", {
                    icon: 2
                }, function(){
                });
                return false
            }
            $.post("{$menu['repair_index']}",{
                id:id,
                content:content,
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
            $("#summernote_repair").summernote('code', '<p style="font-family: Arial, Helvetica, sans-serif; font-size: 12px;"><span class="Apple-style-span" style="color: rgb(255, 102, 102);">截图、本地图片可直接复制粘贴进编辑器中</span><br></p><p style="font-family: Arial, Helvetica, sans-serif; font-size: 12px;"><br></p>');
            $('#summernote_content').summernote('code', `{$post.content}`);

        }
    </script>
{include file="../footer.tpl"}