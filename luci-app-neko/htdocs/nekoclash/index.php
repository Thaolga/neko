<?php

include './cfg.php';
include './devinfo.php';
$str_cfg=substr($selected_config, strlen("$neko_dir/config")+1);
$_IMG = '/luci-static/ssr/';
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
</div>
  <title>双击显示图标</title>
    <style>
        .container-sm {
            margin: 20px auto;
            position: relative;
        }
        .draggable {
            position: absolute;
            cursor: move;
        }
    </style>
</head>
<body>
    <div class="container-sm text-center col-8">
        <img src="./assets/img/photo.png" class="img-fluid mb-5 draggable" style="display: none;">
    </div>

    <script>
        function toggleImage() {
            var img = document.querySelector('.container-sm img');
            var btn = document.getElementById('showHideButton');
            if (img.style.display === 'none') {
                img.style.display = 'block';
                btn.innerText = '隐藏图标';
            } else {
                img.style.display = 'none';
                btn.innerText = '显示图标';
            }
        }

        function hideIcon() {
            var img = document.querySelector('.container-sm img');
            var btn = document.getElementById('showHideButton');
            if (img.style.display === 'block') {
                img.style.display = 'none';
                btn.innerText = '显示图标';
            }
        }

        document.body.ondblclick = function() {
            toggleImage();
        };

        // Drag and Drop functionality
        document.addEventListener('DOMContentLoaded', (event) => {
            var img = document.querySelector('.container-sm img');
            img.addEventListener('mousedown', function(e) {
                var offsetX = e.clientX - parseInt(window.getComputedStyle(img).left);
                var offsetY = e.clientY - parseInt(window.getComputedStyle(img).top);

                function mouseMoveHandler(e) {
                    img.style.left = (e.clientX - offsetX) + 'px';
                    img.style.top = (e.clientY - offsetY) + 'px';
                }

                function reset() {
                    document.removeEventListener('mousemove', mouseMoveHandler);
                    document.removeEventListener('mouseup', reset);
                }

                document.addEventListener('mousemove', mouseMoveHandler);
                document.addEventListener('mouseup', reset);
            });
        });
    </script>
    <div class="container-sm container-bg text-center callout border border-3 rounded-4 col-11">
        <div class="row">
            <a href="#" class="col btn btn-lg">首页</a>
            <a href="./dashboard.php" class="col btn btn-lg">仪表板</a>
            <a href="./configs.php" class="col btn btn-lg">配置</a>
            <a href="./settings.php" class="col btn btn-lg">设定</a>
        </div>
    </div>
    <div class="container text-left p-3">
       
        <div class="container container-bg border border-3 rounded-4 col-12 mb-4">
        <h2 class="text-center p-2">运行状况</h2>
            <table class="table table-borderless mb-2">
                <tbody>
                    <tr>
                        <td>状态</td>
                        <td class="d-grid">
                            <div class="btn-group col" role="group" aria-label="ctrl">            
                                <?php
                                    if($neko_status==1) echo "<button type=\"button\" class=\"btn btn-success\">运行中</button>\n";
                                    else echo "<button type=\"button\" class=\"btn btn-outline-danger\">未运行</button>\n";
                                    echo "<button type=\"button\" class=\"btn btn-warning d-grid\">$str_cfg</button>\n";
                                ?>
                            </div>
                        </td>
                    </tr>
                        <td>控制</td>
                        <form action="index.php" method="post">
                            <td class="d-grid">
                                <div class="btn-group col" role="group" aria-label="ctrl">
                                    <button type="submit" name="neko" value="start" class="btn btn<?php if($neko_status==1) echo "-outline" ?>-success <?php if($neko_status==1) echo "disabled" ?> d-grid">启用</button>
                                    <button type="submit" name="neko" value="disable" class="btn btn<?php if($neko_status==0) echo "-outline" ?>-danger <?php if($neko_status==0) echo "disabled" ?> d-grid">停用</button>
                                    <button type="submit" name="neko" value="restart" class="btn btn<?php if($neko_status==0) echo "-outline" ?>-warning <?php if($neko_status==0) echo "disabled" ?> d-grid">重启</button>
                                </div>
                            </td>
                        </form>
                    </tr>
                    <tr>
                        <td>运行模式</td>
                        <td class="d-grid">
                            <input class="form-control text-center" name="mode" type="text" placeholder="<?php echo $neko_cfg['echanced']." | ".$neko_cfg['mode'] ?>" disabled>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="container container-bg border border-3 rounded-4 col-12 mb-4">
           
            <h2 class="text-center p-2">NekoClash</h2>
            <table class="table table-borderless mb-2">
                <tbody>
                    <tr>
                              
                        <td>型号</td>
                        <td class="col-7"><?php echo $devices ?></td>
                    </tr>
                    <tr>
                        <td>内存</td>
                        <td class="col-7"><?php echo "$ramUsage/$ramTotal MB" ?></td>
                    </tr>
                    <tr>
                        <td>固件版本</td>
                        <td class="col-7"><?php echo $OSVer ?></td>
                    </tr>
                    <tr>
                        <td>内核版本</td>
                        <td class="col-7"><?php echo $kernelv ?></td>
                    </tr>
                    <tr>
                         <td>平均负载</td>
                        <td class="col-7"><?php echo "$cpuLoadAvg1Min $cpuLoadAvg5Min $cpuLoadAvg15Min"  ?></td>
                    </tr>
                    <tr>
                        <td>运行时间</td>
                        <td class="col-7"><?php echo "{$days}天 {$hours}小时 {$minutes}分钟 {$seconds}秒"?></td>
                    </tr>
                
                </tbody>
            </table>
        </div>
         <div class="container container-bg border border-3 rounded-4 col-12 mb-4">
        <table class="table table-borderless mb-0">
            <tbody>
                <tr class="text-center">
                    <td class="col-2">下载-总计</td>
                    <td class="col-2">上传-总计</td>
                </tr>
                <tr class="text-center">
                    <td class="col-2"><class id="downtotal">-</class></td>
                    <td class="col-2"><class id="uptotal">-</class></td>
                </tr>
                <tr>
            </tbody>
        </table>
    </div>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>日志清理</title>
    
</head>
<body>
<div class="container container-bg border border-3 rounded-4 col-12 mb-4">
        <h2 class="text-center p-2">插件日志</h2>
        <div class="mb-3">
            <textarea class="form-control" id="logs" rows="10" readonly></textarea>
        </div>
     
        <h2 class="text-center p-2">内核日志</h2>
        <div class="mb-3">
            <textarea class="form-control" id="bin_logs" rows="10" readonly></textarea>
           </div>
        </div>
    </div>
    <footer class="text-center">
        <p><?php echo $footer ?></p>
    </footer>
</body>
</html>
