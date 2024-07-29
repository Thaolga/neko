<?php

include './cfg.php';
include './log.php';
$str_cfg=substr($selected_config, strlen("$neko_dir/config")+1);

if(isset($_POST['neko'])){
    $dt = $_POST['neko'];
    if ($dt == 'start') shell_exec("$neko_dir/core/neko -s");
    if ($dt == 'disable') shell_exec("$neko_dir/core/neko -k");
    if ($dt == 'restart') shell_exec("$neko_dir/core/neko -r");
}
$neko_status=exec("uci -q get neko.cfg.enabled");
?>
<!doctype html>
<html lang="en" data-bs-theme="<?php echo substr($neko_theme,0,-4) ?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home - Neko</title>
    <link rel="icon" href="./assets/img/favicon.png">
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="./assets/css/custom.css" rel="stylesheet">
    <link href="./assets/theme/<?php echo $neko_theme ?>" rel="stylesheet">
    <script type="text/javascript" src="./assets/js/feather.min.js"></script>
    <script type="text/javascript" src="./assets/js/jquery-2.1.3.min.js"></script>
    <script type="text/javascript" src="./assets/js/neko.js"></script>
  </head>
  <body>
    <div class="container-sm text-center col-8">
	    <img src="./assets/img/neko.png" class="img-fluid mb-5">
    </div>
    <div class="container-sm container-bg text-center callout border border-3 rounded-4 col-11">
        <div class="row">
            <a href="#" class="col btn btn-lg">首页</a>
            <a href="./dashboard.php" class="col btn btn-lg">仪表板</a>
            <a href="./configs.php" class="col btn btn-lg">配置</a>
            <a href="./settings.php" class="col btn btn-lg">设定</a>
            <a href="./log.php" class="col btn btn-lg">日志</a>
        </div>
    </div>
    <div class="container text-left p-3">
        <h1 class="text-center p-2 mb-3">日志</h1>
              <div class="container container-bg border border-3 rounded-4 col-12 mb-4">
            <h2 class="text-center p-2">插件日志</h2>
            <div class="mb-3">
            </br>
                <textarea class="form-control" id="logs" rows="10" readonly></textarea>
            </div>
            <h2 class="text-center p-2">内核日志</h2>
            <div class="mb-3">
            </br>
                <textarea class="form-control" id="bin_logs" rows="10" readonly></textarea>
            </div>
        </div>
    </div>

    <footer class="text-center">
        <p><?php echo $footer ?></p>
    </footer>
  </body>
</html>
