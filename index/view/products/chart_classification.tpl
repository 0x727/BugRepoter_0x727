 <!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<title></title>
</head>
 
<body>
<div id="main" style="height: 700px;"></div>
<script src="./public/index/js/echarts.min.js"></script>
<script src="./public/index/js/jquery.min.js"></script>
{include file="../lib.tpl"}
<script>
    var myChart = echarts.init(document.getElementById('main'));
    var option = {
        title: {
            text: ''
        },
        // color: ["#BB8FCE", "#0099FF", "#5DADE2", ],
        tooltip: {},
        animationDurationUpdate: 2000,
        label: {
            normal: {
                show: true,
                textStyle: {
                    fontSize: 12
                },
            }
        },
        // legend: {$categories},
        series: [{
            type: 'graph',
            layout: 'force',
            symbolSize: 45,
            legendHoverLink: true,
            focusNodeAdjacency: false,
            roam: true,
            label: {
                normal: {
                    show: true,
                    position: 'inside',
                    textStyle: {
                        fontSize: 12
                    },
                }
            },
            force: {
                repulsion: 1000
            },
            edgeSymbolSize: [4, 50],
            edgeLabel: {
                normal: {
                    show: false,
                    textStyle: {
                        fontSize: 10
                    },
                    formatter: "{$formatter}"
                }
            },

            categories: {$categories},
            data: {$data},
            links: {$lines},
            lineStyle: {
                normal: {
                    opacity: 0.9,
                    width: 1,
                    curveness: 0
                }
            }
        }]
    };
    
    myChart.setOption(option);
    let model  = myChart.getModel().getSeriesByIndex(0).getData()._itemLayouts;
    console.log('model',model);
</script>
</body>
