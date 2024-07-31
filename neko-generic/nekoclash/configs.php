<?php
include './cfg.php';

$dirPath = "$neko_dir/config";
$tmpPath = "$neko_www/lib/selected_config.txt";
$arrFiles = array();
$arrFiles = glob("$dirPath/*.yaml");

if(isset($_POST['clashconfig'])){
    $dt = $_POST['clashconfig'];
    shell_exec("echo $dt > $tmpPath");
    $selected_config = $dt;
}
if(isset($_POST['neko'])){
    $dt = $_POST['neko'];
    if ($dt == 'apply') shell_exec("$neko_dir/core/neko -r");
}
include './cfg.php';
?>
<!doctype html>
<html lang="en" data-bs-theme="<?php echo substr($neko_theme,0,-4) ?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Configs - Neko</title>
    <link rel="icon" href="./assets/img/favicon.png">
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="./assets/css/custom.css" rel="stylesheet">
    <link href="./assets/theme/<?php echo $neko_theme ?>" rel="stylesheet">
    <script type="text/javascript" src="./assets/js/feather.min.js"></script>
    <script type="text/javascript" src="./assets/js/jquery-2.1.3.min.js"></script>
    <script type="text/javascript" src="./assets/js/bootstrap.min.js"></script>
  </head>
  <body>
         <title>双击显示图标</title>
    <style>
        .container-sm {
            margin: 20px auto;
        }
    </style>
</head>
<body>
    <div class="container-sm text-center col-8">
        <img src="./assets/img/neko.png" class="img-fluid mb-5" style="display: none;">
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
    </script>
    <div class="container-sm container-bg text-center callout border border-3 rounded-4 col-11">
        <div class="row">
            <a href="./" class="col btn btn-lg">首页</a>
            <a href="./dashboard.php" class="col btn btn-lg">仪表板</a>
            <a href="#" class="col btn btn-lg">配置</a>
            <a href="./settings.php" class="col btn btn-lg">设定</a>
        </div>
    </div>
    <div class="container text-left p-3">
        
        <div class="container container-bg border border-3 rounded-4 col-12 mb-4">
            <h2 class="text-center p-2">配置</h2>
            <form action="configs.php" method="post">
                <div class="container text-center justify-content-md-center">
                    <div class="row justify-content-md-center">
                        <div class="col input-group mb-3 justify-content-md-center">
                          <select class="form-select" name="clashconfig" aria-label="themex">
                            <option selected><?php echo $selected_config ?></option>
                            <?php foreach ($arrFiles as $file) echo "<option value=\"".$file.'">'.$file."</option>" ?>
                          </select>
                        </div>
                        <div class="row justify-content-md-center">
                            <div class="btn-group d-grid d-md-flex justify-content-md-center mb-5" role="group">
                              <input class="btn btn-info" type="submit" value="更改配置">
                              <button name="neko" type="submit" value="应用" class="btn btn-warning d-grid">应用</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
       <div class="container container-bg border border-3 rounded-4 col-12 mb-4"></br>
            <ul class="nav text-center justify-content-md-center">
                <li class="nav-item">
                    <a class="col btn btn-lg active" data-bs-toggle="tab" href="#info">配置</a>
                </li>
                <li class="nav-item">
                    <a class="col btn btn-lg" data-bs-toggle="tab" href="#proxy">代理</a>
                </li>
                <li class="nav-item">
                    <a class="col btn btn-lg" data-bs-toggle="tab" href="#rules">规则</a>
                </li>
                <li class="nav-item">
                    <a class="col btn btn-lg" data-bs-toggle="tab" href="#converter">转换</a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="info" class="tab-pane fade show active">
                    <h2 class="text-center p-2">配置资讯</h2>
                    <table class="table table-borderless callout mb-5">
                        <tbody>
                            <tr class="text-center">
                                <td class="col-2">HTTP 端口</td>
                                <td class="col-2">Redir 端口</td>
                                <td class="col-2">Socks 端口</td>
                            </tr>
                            <tr class="text-center">
                                <td class="col-2">
                                    <input class="form-control text-center" name="port" type="text" placeholder="<?php echo $neko_cfg['port'] ?>" disabled>
                                </td>
                                <td class="col-2">
                                    <input class="form-control text-center" name="redir" type="text" placeholder="<?php echo $neko_cfg['redir'] ?>" disabled>
                                </td>
                                <td class="col-2">
                                    <input class="form-control text-center" name="socks" type="text" placeholder="<?php echo $neko_cfg['socks'] ?>" disabled>
                                </td>
                            </tr>
                            <tr class="text-center">
                                <td class="col-2">混合 端口</td>
                                <td class="col-2">TProxy 端口</td>
                                <td class="col-2">模式</td>
                            </tr>
                            <tr class="text-center">
                                <td class="col-2">
                                    <input class="form-control text-center" name="mixed" type="text" placeholder="<?php echo $neko_cfg['mixed'] ?>" disabled>
                                </td>
                                <td class="col-2">
                                    <input class="form-control text-center" name="tproxy" type="text" placeholder="<?php echo $neko_cfg['tproxy'] ?>" disabled>
                                </td>
                                <td class="col-2">
                                    <input class="form-control text-center" name="mode" type="text" placeholder="<?php echo $neko_cfg['mode'] ?>" disabled>
                                </td>
                            </tr>
                            <tr class="text-center">
                                <td class="col-2">增強型</td>
                                <td class="col-2">密钥</td>
                                <td class="col-2">控制器</td>
                            </tr>
                            <tr class="text-center">
                                <td class="col-2">
                                    <input class="form-control text-center" name="ech" type="text" placeholder="<?php echo $neko_cfg['echanced'] ?>" disabled>
                                </td>
                                <td class="col-2">
                                    <input class="form-control text-center" name="sec" type="text" placeholder="<?php echo $neko_cfg['secret'] ?>" disabled>
                                </td>
                                <td class="col-2">
                                    <input class="form-control text-center" name="ext" type="text" placeholder="<?php echo $neko_cfg['ext_controller'] ?>" disabled>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <h2 class="text-center p-2">配置</h2>
                    <div class="container h-100 mb-5">
                        <iframe class="rounded-4 w-100" scrolling="no" height="700" src="./configconf.php" title="yacd" allowfullscreen></iframe>
                    </div>
                </div>
                <div id="proxy" class="tab-pane fade">
                    <h2 class="text-center p-2">代理编辑器</h2>
                    <div class="container h-100 mb-5">
                        <iframe class="rounded-4 w-100" scrolling="no" height="700" src="./proxyconf.php" title="yacd" allowfullscreen></iframe>
                    </div>
                </div>
                <div id="rules" class="tab-pane fade">
                    <h2 class="text-center p-2">规则编辑器</h2>
                    <div class="container h-100 mb-5">
                        <iframe class="rounded-4 w-100" scrolling="no" height="700" src="./rulesconf.php" title="yacd" allowfullscreen></iframe>
                    </div>
                </div>
                <div id="converter" class="tab-pane fade">
                    <h2 class="text-center p-2 mb-5">转换器</h2>
                    <div class="container h-100">
                        <iframe class="rounded-4 w-100" scrolling="no" height="700" src="./yamlconv.php" title="yacd" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <footer class="text-center">
        <p><?php echo $footer ?></p>
    </footer>
  </body>
</html>
