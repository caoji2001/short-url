<?php
$config_file = dirname(__FILE__).'/../system/config.inc.php';
require_once($config_file);
$MysqliDb_file = dirname(__FILE__).'/../system/MysqliDb.php';
require_once($MysqliDb_file);

if (!$db_config) {
    header('Location: ../install/');
    exit(0);
}

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
    </head>
    <body>
        <div class="container-fluid">
            <nav class="navbar bg-light">
                <div class="container-fluid">
                    <span class="navbar-brand mb-0 h1">短网址服务 - 管理面板</span>
                    <a class="btn btn-outline-danger" href="./logout.php" role="button">登出</a>
                </div>
            </nav>
            <div class="row">
                <div class="col-1 d-none d-sm-flex"></div>
                <div class="col-12 col-sm-10">
                    <table class="table table-sm table-hover text-break">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">URL</th>
                                <th scope="col">修改</th>
                                <th scope="col">删除</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <?php include('./table_content.php'); ?>
                        </tbody>
                    </table>

                    <div class="modal fade" id="modifyModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="mod.php" method="post">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5">
                                            修改短链接指向
                                        </h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <label class="form-label">
                                            待修改短链接
                                        </label>
                                        <div class="row">
                                            <div class="col-auto d-flex flex-column justify-content-center pe-0">
                                                <span id="mod_get_siteurl">
                                                </span>
                                            </div>
                                            <div class="col-auto ps-0">
                                                <input type="text" name="id62" id="mod_get_id62" class="form-control px-0"
                                                readonly />
                                            </div>
                                        </div>
                                        <label class="form-label">
                                            新的指向链接
                                        </label>
                                        <input type="text" id="mod_get_url" name="url" class="form-control" />
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            取消
                                        </button>
                                        <button type="submit" id="form_submit" class="btn btn-primary">
                                            确认
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="deleteModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="del.php" method="post">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5">
                                            删除确认
                                        </h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <label class="form-label">
                                            待删除短链接
                                        </label>
                                        <div class="row">
                                            <div class="col-auto d-flex flex-column justify-content-center pe-0">
                                                <span id="del_get_siteurl">
                                                </span>
                                            </div>
                                            <div class="col-auto ps-0">
                                                <input type="text" name="id62" id="del_get_id62" class="form-control px-0"
                                                readonly />
                                            </div>
                                        </div>
                                        <label class="form-label">
                                            指向链接
                                        </label>
                                        <input type="text" id="del_get_url" class="form-control" readonly />
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            取消
                                        </button>
                                        <button type="submit" id="form_submit" class="btn btn-primary">
                                            确认
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-1 d-none d-sm-flex"></div>
            </div>
        </div>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/modal.js"></script>
</html>