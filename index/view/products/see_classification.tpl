<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<title></title>
</head>
<body>
<link rel="stylesheet" href="./public/index/vendor/datatables/dataTables.bs4.css" />
<link rel="stylesheet" href="./public/index/vendor/datatables/dataTables.bs4-custom.css" />
<link rel="stylesheet" href="./public/index/vendor/datatables/buttons.bs.css" rel="stylesheet" />
<style>
    #classification_wrapper{
        overflow-x:hidden;
        overflow-y:hidden;
    }
    .content-wrapper{
        height: auto !important;
    }
</style>
<div class="content-wrapper">
    <div class="row gutters">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="classification" class="table v-middle" style="text-align: center;">
                            <thead style="text-indent: 1rem;">
                                <tr>
                                  <th>项目编号</th>
                                  <th>项目域名</th>
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
<link rel="stylesheet" href="./public/index/css/bootstrap.min.css">
<link rel="stylesheet" href="./public/index/fonts/style.css">
<link rel="stylesheet" href="./public/index/css/main.css">
<link rel="stylesheet" href="./public/index/vendor/megamenu/css/megamenu.css">
<link rel="stylesheet" href="./public/index/vendor/search-filter/search-filter.css">
<link rel="stylesheet" href="./public/index/vendor/search-filter/custom-search-filter.css">
<script src="./public/index/js/jquery.min.js"></script>
<script src="./public/index/js/bootstrap.bundle.min.js"></script>
<script src="./public/index/vendor/datatables/dataTables.min.js"></script>
<script src="./public/index/vendor/datatables/dataTables.bootstrap.min.js"></script>
<script src="./public/index/vendor/datatables/custom/custom-datatables.js"></script>
<script src="./public/layer/layer.js"></script>
{include file="../lib.tpl"}
<script>
    $("#classification").DataTable({
        "bJQueryUI": true,
        'aLengthMenu': [[500, 1000], ['500', '1000']],
        'bFilter': false,
        'bSortClasses': true,
        'bSort': true,
        'order': [[0, 'desc']],
        'bInfo' : false,
        "paging": false,
        "ordering": false,
        "info": false,
        "lengthChange": false,
        "searching": false,
        "serverSide": true,
        "deferRender": true,
        "bLengthChange": false,
        "ajax": {
            "url": "{$url}",
            "type":"POST"
        },
        "pagingType": "full_numbers",
        "columns": [
            {
                "data": "id"
            },
            {
                "data": "url"
            },
            {
                "data": "creation_time"
            },
            {
                "data": function (row, type, val, meta) {
                    text = ""
                    text += '<div class="actions" style="display: contents;">'
                    text += '<a href="javascript:void(0);" onclick=delurl("'+row.del_see_classification_id+'") data-toggle="tooltip" data-placement="top" title="" data-original-title="删除" style="display: contents;"><i class="icon-x-circle text-danger"></i>&nbsp;</a>'
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
    function delurl(url)
    {
        $.get(url,{
        },function(data){
            if(data.status == '1'){
                layer.msg(data.msg, {
                    icon: 1
                }, function(){
                    var index = parent.layer.getFrameIndex(window.name); 
                    parent.layer.close(index);
                });
            } else {
                layer.msg(data.msg, {
                    icon: 2
                }, function(){
                   window.location.reload()
                });
            }
        },"json");
    } 
</script>
</body>