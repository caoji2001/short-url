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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.21.2/dist/bootstrap-table.min.css">
    </head>
    <body>
        <nav class="navbar navbar-expand-sm bg-light">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h1">短网址服务 - 管理面板</span>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" href="./index.php">短网址管理</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./blacklist.php">域名黑名单管理</a>
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
                    <table class="table table-sm table-hover text-break"
                    data-toggle="table"
                    data-url="./fwlink/table_content.php"
                    data-pagination="true"
                    data-search="true">
                        <thead>
                            <tr>
                                <th data-field="id" data-sortable="true" data-width="10" data-width-unit="%">#</th>
                                <th data-field="url" data-width="70" data-width-unit="%">URL</th>
                                <th data-field="count" data-width="5" data-width-unit="%">访问量</th>
                                <th data-field="operation" data-formatter="operationFormatter" data-width="15" data-width-unit="%">操作</th>
                            </tr>
                        </thead>
                    </table>

                    <div class="modal fade" id="modifyModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="./fwlink/mod.php" method="post">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5">修改短链接指向</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <label class="form-label">待修改短链接</label>
                                        <div class="row">
                                            <div class="col-auto d-flex flex-column justify-content-center pe-0">
                                                <span id="mod_get_siteurl">
                                                </span>
                                            </div>
                                            <div class="col-auto ps-0">
                                                <input type="text" id="mod_get_id62" class="form-control px-0"
                                                readonly />
                                            </div>
                                        </div>
                                        <label class="form-label">新的指向链接</label>
                                        <input type="text" id="mod_get_url" class="form-control" />
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="mod()">确认</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="deleteModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5">删除确认</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <label class="form-label">待删除短链接</label>
                                    <div class="row">
                                        <div class="col-auto d-flex flex-column justify-content-center pe-0">
                                            <span id="del_get_siteurl">
                                            </span>
                                        </div>
                                        <div class="col-auto ps-0">
                                            <input type="text" id="del_get_id62" class="form-control px-0"
                                            readonly />
                                        </div>
                                    </div>
                                    <label class="form-label">指向链接</label>
                                    <input type="text" id="del_get_url" class="form-control" readonly />
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="del()">确认</button>
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
    </body>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.21.2/dist/bootstrap-table.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.21.2/dist/locale/bootstrap-table-zh-CN.min.js"></script>
    <script>
        const modifyModal = document.getElementById("modifyModal")
        const deleteModal = document.getElementById("deleteModal")
        const feedbackModal = document.getElementById("feedbackModal")

        modifyModal.addEventListener("show.bs.modal", event => {
            const button = event.relatedTarget
            const siteurl = window.location.origin + "/"
            const id62 = button.parentNode.parentNode.children[0].innerHTML
            const url = button.parentNode.parentNode.children[1].innerHTML

            document.getElementById("mod_get_siteurl").innerText = siteurl
            document.getElementById("mod_get_id62").value = id62
            document.getElementById("mod_get_url").value = url
        })

        deleteModal.addEventListener("show.bs.modal", event => {
            const button = event.relatedTarget
            const siteurl = window.location.origin + "/"
            const id62 = button.parentNode.parentNode.children[0].innerHTML
            const url = button.parentNode.parentNode.children[1].innerHTML

            document.getElementById("del_get_siteurl").innerText = siteurl
            document.getElementById("del_get_id62").value = id62
            document.getElementById("del_get_url").value = url
        })

        function mod() {
            $.post(
                "./fwlink/mod.php",
                {
                    "id62": $("#mod_get_id62").val(),
                    "url": $("#mod_get_url").val()
                },
                function(result) {
                    $("#get_feedback").html(result);
                }
            );
            $(feedbackModal).modal('show')
        }

        function del() {
            $.post(
                "./fwlink/del.php",
                {
                    "id62": $("#del_get_id62").val(),
                },
                function(result) {
                    $("#get_feedback").html(result);
                }
            );
            $(feedbackModal).modal('show')
        }

        function operationFormatter(value) {
            return '<button type="button" class="btn btn-outline-warning m-1" data-bs-toggle="modal" data-bs-target="#modifyModal">修改</button><button type="button" class="btn btn-outline-danger m-1" data-bs-toggle="modal" data-bs-target="#deleteModal">删除</button>'
        }
    </script>
</html>