{include file="../header.tpl"}
    <link rel="stylesheet" href="./public/index/vendor/datatables/dataTables.bs4.css" />
    <link rel="stylesheet" href="./public/index/vendor/datatables/dataTables.bs4-custom.css" />
    <link rel="stylesheet" href="./public/index/vendor/datatables/buttons.bs.css" rel="stylesheet" />
    <div class="content-wrapper">
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="dt-buttons">
                                <a href="{$menu['add_classification']}">
                                    <button class="dt-button buttons-copy buttons-html5" tabindex="0" aria-controls="copy-print-scroll" type="button">
                                        <span>添加</span>
                                    </button>
                                </a>
                            </div>
                            <table id="classification" class="table v-middle" style="text-align: center;">
                                <thead style="text-indent: 1rem;">
                                    <tr>
                                      <th>项目编号</th>
                                      <th>项目名称</th>
                                      <th>漏洞数量</th>
                                      <th>漏洞修复数量</th>
                                      <th>项目资产</th>
                                      <th>漏洞分布图</th>
                                      <th>提交时间</th>
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
        $("#classification").DataTable({
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
            "ajax": {
                "url": "{$menu['products_classification']}",
                "type":"POST"
            },
            "pagingType": "full_numbers",
            "columns": [
                {
                    "data": "id"
                },
                {
                    "data": "title"
                },
                {
                    "data": "num"
                },
                {
                    "data": "repair_num"
                },
                {
                    "data": function (row, type, val, meta) {
                        text = "<div>"
                        text += "<a href=\"javascript:void(0);\" onclick=\"see('"+row.see_classification_id+"')\"data-original-title='查看项目资产' title='' data-toggle='tooltip' data-placement='top'><i class='icon-eye text-info'></i>&nbsp;</a>"
                        text += '</div>'
                        return text
                    }
                },
                {
                    "data": function (row, type, val, meta) {
                        text = "<div>"
                        text += "<a href=\"javascript:void(0);\" onclick=\"chart_see('"+row.chart_classification_id+"')\"data-original-title='查看漏洞分布图' title='' data-toggle='tooltip' data-placement='top'><i class='icon-eye text-info'></i>&nbsp;</a>"
                        text += '</div>'
                        return text
                    }
                },
                {
                    "data": "creation_time"
                },
                {
                    "data": function (row, type, val, meta) {
                        text = ""
                        text += '<div class="actions">'
                        text += '<a href="'+row.edit_classification_id+'" data-toggle="tooltip" data-placement="top" title="" data-original-title="编辑"><i class="icon-edit1 text-info"></i>&nbsp;</a>'
                        text += '<a href="'+row.del_classification_id+'" data-toggle="tooltip" data-placement="top" title="" data-original-title="删除"><i class="icon-x-circle text-danger"></i>&nbsp;</a>'
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

        // 查看项目
        function see(url)
        {
            layer.open({
                type: 2,
                title: '查看项目资产',
                shadeClose: true,
                maxmin: true,
                area: ['60%', '80%'],
                content: url
            });
        }

        // 查看漏洞分布
        function chart_see(url)
        {
            layer.open({
                type: 2,
                title: '漏洞分布图',
                shadeClose: true,
                maxmin: true,
                area: ['60%', '80%'],
                content: url
            });
        }
    </script>
{include file="../footer.tpl"}