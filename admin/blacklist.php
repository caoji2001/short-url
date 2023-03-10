<?php
$core_file = dirname(__FILE__).'/../system/core.php';
require_once($core_file);
$MysqliDb_file = dirname(__FILE__).'/../system/MysqliDb.php';
require_once($MysqliDb_file);

session_start();
if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    exit('仅限管理员访问');
}
?>
<!doctype html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>短网址服务 - 缩短长链接！</title>
        <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="../assets/css/bootstrap-icons.min.css">
        <link rel="stylesheet" href="../assets/css/bootstrap-table.min.css">
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
                            <a class="nav-link active" href="./blacklist.php">域名黑名单管理</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./report.php">统计报表</a>
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
                    <div id="toolbar">
                        <button type="button" class="btn btn-outline-success m-1" data-bs-toggle="modal" data-bs-target="#addModal">新增</button>
                    </div>
                    <table class="table table-sm table-hover text-break"
                    data-toggle="table"
                    data-url="./blacklist/table_content.php"
                    data-pagination="true"
                    data-search="true">
                        <thead>
                            <tr>
                                <th data-field="domain" data-width="92" data-width-unit="%">域名</th>
                                <th data-field="operation" data-formatter="operationFormatter" data-width="8" data-width-unit="%">操作</th>
                            </tr>
                        </thead>
                    </table>

                    <div class="modal fade" id="addModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5">新增黑名单域名</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <label class="form-label">待新增黑名单域名</label>
                                    <input type="text" id="add_get_domain" class="form-control" />
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                    <button type="button" id="form_submit" class="btn btn-primary" data-bs-dismiss="modal" onclick="add()">确认</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="deleteModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5">删除黑名单域名确认</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <label class="form-label">待删除黑名单域名</label>
                                    <input type="text" id="del_get_domain" id="del_get_domain" class="form-control" readonly />
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                    <button type="button" id="form_submit" class="btn btn-primary" data-bs-dismiss="modal" onclick="del()">确认</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="feedbackModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5">返回信息</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p id="get_feedback"></p>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary" onclick="location.reload()">确认</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-1 d-none d-sm-flex"></div>
            </div>
        </div>
        <script src="../assets/js/jquery-3.6.3.min.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/bootstrap-table.min.js"></script>
        <script src="../assets/js/bootstrap-table-zh-CN.min.js"></script>
        <script>
            const deleteModal = document.getElementById('deleteModal')
            const feedbackModal = document.getElementById("feedbackModal")

            deleteModal.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget
                const domain = button.parentNode.parentNode.children[0].innerHTML

                document.getElementById('del_get_domain').value = domain
            })

            function add() {
                $.post(
                    "./blacklist/add.php",
                    {
                        "domain": $("#add_get_domain").val(),
                    },
                    function(result) {
                        $("#get_feedback").html(result);
                    }
                );
                $(feedbackModal).modal('show')
            }

            function del() {
                $.post(
                    "./blacklist/del.php",
                    {
                        "domain": $("#del_get_domain").val(),
                    },
                    function(result) {
                        $("#get_feedback").html(result);
                    }
                );
                $(feedbackModal).modal('show')
            }

            function operationFormatter(value) {
                return '<button type="button" class="btn btn-outline-danger m-1" data-bs-toggle="modal" data-bs-target="#deleteModal">删除</button>'
            }
        </script>
    </body>
</html>