<?php
$core_file = dirname(__FILE__).'/../system/core.php';
require_once($core_file);
$MysqliDb_file = dirname(__FILE__).'/../system/MysqliDb.php';
require_once($MysqliDb_file);

session_start();
if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header('Location: ./login.php');
    exit(0);
}
?>
<!doctype html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>短网址服务 - 缩短长链接！</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/echarts@5.4.1/dist/echarts.min.js"></script>
    </head>
    <body>
        <nav class="navbar navbar-expand-sm bg-light">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h1">短网址服务 - 管理面板</span>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="./index.php">短网址管理</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./blacklist.php">域名黑名单管理</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="./report.php">统计报表</a>
                        </li>
                    </ul>
                    <a class="btn btn-outline-danger" href="./logout.php" role="button">登出</a>
                </div>
            </div>
        </nav>
        <div class="container-fluid">
            <div class="row">
                <div class="col-1 d-none d-sm-flex"></div>
                <div class="col-12 col-sm-10">
                    <div id="main" class="w-100" style="height:600px"></div>
                </div>
                <div class="col-1 d-none d-sm-flex"></div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.min.js"></script>
        <script>
            $(document).ready(function() {
                $.ajax({
                    method: "GET",
                    url: "./report/chart_content.php",
                    dataType: "json"
                })
                .done(function(data) {
                    const chart = echarts.init(document.getElementById('main'))

                    const option = {
                        title: {text: '短链接跳转次数统计'},
                        tooltip: {},
                        legend: {data: ['跳转次数']},
                        xAxis: {
                            type: 'time',
                            axisLabel: {
                                formatter: function(value) {
                                    const t_date = new Date(value);
                                    return [t_date.getFullYear(), t_date.getMonth() + 1, t_date.getDate()].join('-')
                                }
                            }
                        },
                        yAxis: {type: 'value'},
                        series: [{
                            name: '跳转次数',
                            type: 'line',
                            smooth: true,
                            data: data
                        }]
                    }

                    chart.setOption(option)
                });
            });
        </script>
    </body>
</html>