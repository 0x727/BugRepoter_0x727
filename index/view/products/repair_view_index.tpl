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
                    ['view', ['fullscreen']],
                ],
                //调用图片上传
                callbacks: {
                    onImageUpload: function (files) {
                        
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
            var $summernote_b = $('#summernote_content').summernote({
                height: 400,
                tabsize: 2,
                focus: true,
                lang:'zh-CN',
                toolbar: [
                    ['view', ['fullscreen']],
                ],
            });
            
            $('#summernote_repair').summernote('code', `{$post.repair_content}`);
            $('#summernote_content').summernote('code', `{$post.content}`);
        });
    </script>
{include file="../footer.tpl"}