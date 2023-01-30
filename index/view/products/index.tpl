{include file="../header.tpl"}
    <link rel="stylesheet" href="./public/index/vendor/datatables/dataTables.bs4.css" />
    <link rel="stylesheet" href="./public/index/vendor/datatables/dataTables.bs4-custom.css" />
    <link rel="stylesheet" href="./public/index/vendor/datatables/buttons.bs.css" rel="stylesheet" />
    <style>
        #index_length{
            margin-left: 20px;
        }
    </style>
    <div class="content-wrapper">
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="dt-buttons">
                                <a href="javascript:void(0);" onclick="batch_add()">
                                    <button class="dt-button buttons-copy buttons-html5" tabindex="0" aria-controls="copy-print-scroll" type="button">
                                        <span>批量添加</span>
                                    </button>
                                </a>
                                <a href="{$products_add_index}">
                                    <button class="dt-button buttons-copy buttons-html5" tabindex="0" aria-controls="copy-print-scroll" type="button">
                                        <span>添加</span>
                                    </button>
                                </a>
                                <a href="javascript:void(0);" onclick="download_all_export('{$token}')">
                                    <button class="dt-button buttons-copy buttons-html5" tabindex="0" aria-controls="copy-print-scroll" type="button">
                                        <span>导出漏洞报告</span>
                                    </button>
                                </a>
                                <a href="javascript:void(0);" onclick="download_repair_all_export('{$token}')">
                                    <button class="dt-button buttons-copy buttons-html5" tabindex="0" aria-controls="copy-print-scroll" type="button">
                                        <span>导出复测报告</span>
                                    </button>
                                </a>
                                <style>
                                    .select-type{
                                        line-height: 30px;
                                        color: black;
                                        margin-left: 0.5rem;
                                    }
                                </style>
                                <span>
                                    <span class="select-type">项目：</span>
                                    <select id="company_option" class="table-select" style="    padding: 0.2em;">
                                        <option value="" >全部</option>
                                        {foreach from=$project_classification item=v}
                                            {if $v['pid'] == 0}
                                                <option value="{$v.id}">{$v.title}</option>
                                            {/if}
                                        {/foreach}
                                    </select>
                                    <a href="javascript:void(0);" onclick="lookup()">
                                        <button class="dt-button buttons-copy buttons-html5" tabindex="0" aria-controls="copy-print-scroll" type="button" style="float: unset;">
                                            <span>查找</span>
                                        </button>
                                    </a>
                                </span>
                                <!-- <a href="javascript:void(0);" onclick="download_all_export('{$token}')">
                                    <button class="dt-button buttons-copy buttons-html5" tabindex="0" aria-controls="copy-print-scroll" type="button">
                                        <span>导出复测报告</span>
                                    </button>
                                </a> -->
                            </div>
                            <table id="index" class="table v-middle" style="text-align: center;">
                                <thead style="text-indent: 1rem;">
                                    <tr>
                                        <th>
                                            <input class="form-check-input" id="quanxuan" type="checkbox" value="">
                                        </th>
                                        <th>漏洞编号</th>
                                        <th>漏洞名称</th>
                                        <th>所属项目</th>
                                        <th>漏洞类型</th>                                                   
                                        <th>漏洞等级</th>                                                     
                                        <th>是否修复</th>
                                        <th>提交时间</th>
                                        <th>导出时间</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="./public/index/vendor/datatables/dataTables.min.js"></script>
    <script src="./public/index/vendor/datatables/dataTables.bootstrap.min.js"></script>
    <script src="./public/index/vendor/datatables/custom/custom-datatables.js"></script>
    <script>
        // 单报告下载
        function download_export(id,token)
        {
            layer.open({
                title: '请选择导出模板',
                btn: ['确定'],
                content: '' +
                '<div class="field-wrapper"><div class="field-wrapper"><select class="select-single js-states" title="" data-live-search="true" id="range">'+'{$template}'+'</select></div></div>',
                yes: function (index,layero) {
                    if($('#range').val()){
                        $.post("{$products_download_index}",{
                            id:id,
                            token:token,
                            path:$('#range').val(),
                        },function(data){
                            if(data.status == '1'){
                                layer.msg('正在为您下载！', {
                                    icon: 1
                                }, function(){
                                   window.location.href = "."+data.data.url
                                   // window.setTimeout("window.location.reload()",1000);
                                });
                            } else {
                                layer.msg(data.msg, {
                                    icon: 2
                                }, function(){
                                   window.location.reload();
                                });
                            }
                        },"json");
                    } else {
                        layer.msg("请选择导出模板！");
                    }
                }
            });
        }

        // 批量下载漏洞报告
        function download_all_export(token)
        {
            var data = ""
            $("#index td :checkbox").each(function(){
                if($(this).prop("checked")){
                    data+=$(this).val()+","
                }
            })
            data = data.substring(0,data.length-1);
            layer.open({
                title: '请选择导出模板',
                btn: ['确定'],
                content: '' +
                '<div class="field-wrapper"><div class="field-wrapper"><select class="select-single js-states" title="" data-live-search="true" id="range">'+'{$template}'+'</select></div></div>',
                yes: function (index,layero) {
                    if($('#range').val()){
                        $.post("{$products_download_index}",{
                            id:data,
                            token:token,
                            path:$('#range').val(),
                        },function(data){
                            if(data.status == '1'){
                                layer.msg('正在为您下载！', {
                                    icon: 1
                                }, function(){
                                   window.location.href = "."+data.data.url
                                   // window.setTimeout("window.location.reload()",1000);
                                });
                            } else {
                                layer.msg(data.msg, {
                                    icon: 2
                                }, function(){
                                   window.location.reload();
                                });
                            }
                        },"json");
                    } else {
                        layer.msg("请选择导出模板！");
                    }
                }
            });
        }

        // 批量下载复测报告
        function download_repair_all_export(token)
        {
            var data = ""
            $("#index td :checkbox").each(function(){
                if($(this).prop("checked")){
                    data+=$(this).val()+","
                }
            })
            data = data.substring(0,data.length-1);
            layer.open({
                title: '请选择导出模板',
                btn: ['确定'],
                content: '' +
                '<div class="field-wrapper"><div class="field-wrapper"><select class="select-single js-states" title="" data-live-search="true" id="range">'+'{$template}'+'</select></div></div>',
                yes: function (index,layero) {
                    if($('#range').val()){
                        $.post("{$products_repair_download_index}",{
                            id:data,
                            token:token,
                            path:$('#range').val(),
                        },function(data){
                            if(data.status == '1'){
                                layer.msg('正在为您下载！', {
                                    icon: 1
                                }, function(){
                                   window.location.href = "."+data.data.url
                                   // window.setTimeout("window.location.reload()",1000);
                                });
                            } else {
                                layer.msg(data.msg, {
                                    icon: 2
                                }, function(){
                                   window.location.reload();
                                });
                            }
                        },"json");
                    } else {
                        layer.msg("请选择导出模板！");
                    }
                }
            });
        }

        // 批量添加报告
        function batch_add()
        {
            layer.prompt({
                title:"请输入批量添加次数，不能大于10份报告",
                formType: 0,
            },function(value, index, elem){
                value = parseInt(value);
                if(value){
                    if(value > 10){
                        layer.msg('输入数量超过10份报告', {
                            icon: 2
                        }, function(){
                        });
                    } else {
                        console.dir(value)
                        $.post("{$menu['products_index']}",{
                            num:value,
                        },function(data){
                            if(data.status == '1'){
                                window.location.href = "."+data.data.url
                            } else {
                                layer.msg(data.msg, {
                                    icon: 2
                                }, function(){
                                   window.location.reload();
                                });
                            }
                        },"json");
                        layer.close(index);
                    }
                } else {
                    layer.msg('请输入批量添加次数', {
                        icon: 2
                    }, function(){
                    });
                }
            });
        }

        // 全选反选
        $("#quanxuan").on("click",function(){
            var checked = $(this).prop("checked")?"checked":"";
            $("#index td :checkbox").each(function(){
                $(this).prop("checked", checked);
            })
        });

        // 提交修复情况
        function repair(id)
        {
            window.location.href=id
        }

        // 查找项目
        function lookup()
        {
            var company_option = $("#company_option").find("option:selected").val();
            var oldTable = $('#index').dataTable();
            _init(company_option);
        }

        // 一键筛选项目
        function check_company(company_id)
        {
            _init(company_id);
        }

        // 初始化加载
        function _init(company)
        {
            $("#index").DataTable({
                "bLengthChange": true,
                "bJQueryUI": true,
                'aLengthMenu': [[10, 20, 30, 40, 50], ['10', '20', '30', '40', '50']],
                'bFilter': false,
                'bSortClasses': true,
                'bSort': true,
                'order': [[0, 'desc']],
                'bInfo' : true,
                "paging": true,
                "ordering": false,
                "info": true,
                "lengthChange": false,
                "searching": true,
                "serverSide": true,
                "deferRender": true,
                "destroy": true,
                "ajax": {
                    "url": "{$menu['products_index']}",
                    "type":"POST",
                    "data":{
                        "company":company,
                    },
                },
                "pagingType": "full_numbers",
                "columns": [
                    {
                        "data": function (row, type, val, meta) {
                            return '<input class="form-check-input" type="checkbox" value="'+row.session+'">'
                        }
                    },
                    {
                        "data": "id"
                    },
                    {
                        "data": "title"
                    },
                    {
                        "data": function (row, type, val, meta) {
                            text = '<a href="javascript:void(0);" onclick="check_company(\''+row.company_id+'\')" style="color: #6f7479;">'+row.company+'</a>'
                            return text
                        }
                    },
                    {
                        "data": "cate_id",
                    },
                    {
                        "data": "bugLevel"
                    },
                    {
                        "data": function (row, type, val, meta) {
                            text = ''
                            if(row.repair_time == '否'){
                                text += '<a href="javascript:void(0);" onclick="repair(\''+row.repair_id+'\')" style="color: #ff0000;">修复</a>'
                            } else {
                                text += '<a href="javascript:void(0);" onclick="repair(\''+row.repair_view_index+'\')" style="color: #6f7479;">查看修复报告</a>'
                            }
                            text += ''
                            return text
                        }
                    },
                    {
                        "data": "creation_time"
                    },
                    {
                        "data": "export_time"
                    },
                    {
                        "data": function (row, type, val, meta) {
                            text = '<div class="actions">'
                            text += '<a href="javascript:void(0);" onclick="download_export(\''+row.session+'\',\'{$token}\')" data-toggle="tooltip" data-placement="top" title="" data-original-title="导出"><i class="icon-download1 text-info"></i>&nbsp;</a>'
                            text += '<a href="'+row.edit_index_id+'" data-toggle="tooltip" data-placement="top" title="" data-original-title="编辑"><i class="icon-edit1 text-info"></i>&nbsp;</a>'
                            text += '<a href="'+row.del_index_id+'" data-toggle="tooltip" data-placement="top" title="" data-original-title="删除"><i class="icon-x-circle text-danger"></i>&nbsp;</a>'
                            text += '</div>'
                            return text
                        }
                    },
                ],
                "language": {
                    "sProcessing": "处理中...",
                    "sLengthMenu": "显示 _MENU_ 项结果",
                    "sZeroRecords": "没有匹配结果",
                    "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
                    "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
                    "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
                    "sInfoPostFix": "",
                    "sSearch": "搜索:",
                    "sUrl": "",
                    "sEmptyTable": "表中数据为空",
                    "sLoadingRecords": "载入中...",
                    "sInfoThousands": ",",
                    "oPaginate": {
                        "sFirst": "首页",
                        "sPrevious": "上页",
                        "sNext": "下页",
                        "sLast": "末页"
                    },
                    "oAria": {
                        "sSortAscending": ": 以升序排列此列",
                        "sSortDescending": ": 以降序排列此列"
                    }
                }
            });
        }

        // 初始化加载
        _init("");
    </script>
{include file="../footer.tpl"}