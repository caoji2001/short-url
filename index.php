<?php
$core_file = dirname(__FILE__).'/system/core.php';
require_once($core_file);

if (!$db_config) {
    header('Location: ../install/');
    exit(0);
}
?>
<!doctype html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>短网址服务 - 缩短长链接！</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
        <style>
            body {
                background-color: #e3f5f5;
            }
        </style>
    </head>
    <body>
        <main>
            <div class="container">
                <div class="row py-5">
                    <div class="col-12 py-sm-5 text-center">
                        <h1>短网址服务 - 缩短长链接！</h1>
                        <p class="text-primary">本站仅用于演示目的，不保证SLA，请不要将生成的短网址用于生产环境！</p>
                    </div>
                </div>

                <div class="row py-5">
                    <div class="col-2 d-none d-lg-flex"></div>
                    <div class="col-12 col-lg-8">
                        <div class="row" id="ajax_div">
                            <div class="col-12 col-sm-10 py-2">
                                <input type="text" id="input_url" class="form-control" placeholder="缩短长链接！">
                                <div id="feedback" class="d-block"></div>
                            </div>
                            <div class="col-12 col-sm-2 py-2">
                                <button type="submit" id="show_url" class="btn btn-primary w-100"">缩短</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-2 d-none d-lg-flex"></div>
                </div>
            </div>
        </main>
        <footer>
            <div class="position-absolute bottom-0 w-100 text-center">
                <span><a href="./admin/" target="_blank">管理面板</a></span>
                <span> | </span>
                <span>Powered by <a href="https://github.com/caoji2001/short-url/" target="_blank">short-url</a></span>
            </div>
        </footer>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.min.js"></script>
        <script src="./assets/js/ajax.js"></script>
    </body>
</html>