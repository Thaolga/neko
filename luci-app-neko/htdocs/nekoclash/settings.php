<?php

include './cfg.php';


$themeDir = "$neko_www/assets/theme";
$tmpPath = "$neko_www/lib/selected_config.txt";
$arrFiles = array();
$arrFiles = glob("$themeDir/*.css");

for($x=0;$x<count($arrFiles);$x++) $arrFiles[$x] = substr($arrFiles[$x], strlen($themeDir)+1);

if(isset($_POST['themechange'])){
    $dt = $_POST['themechange'];
    shell_exec("echo $dt > $neko_www/lib/theme.txt");
    $neko_theme = $dt;
}
if(isset($_POST['fw'])){
    $dt = $_POST['fw'];
    if ($dt == 'enable') shell_exec("uci set neko.cfg.new_interface='1' && uci commit neko");
    if ($dt == 'disable') shell_exec("uci set neko.cfg.new_interface='0' && uci commit neko");
}
$fwstatus=shell_exec("uci get neko.cfg.new_interface");
?>
<!doctype html>
<html lang="en" data-bs-theme="<?php echo substr($neko_theme,0,-4) ?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Settings - Neko</title>
    <link rel="icon" href="./assets/img/favicon.png">
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="./assets/theme/<?php echo $neko_theme ?>" rel="stylesheet">
    <link href="./assets/css/custom.css" rel="stylesheet">
    <script type="text/javascript" src="./assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="./assets/js/feather.min.js"></script>
    <script type="text/javascript" src="./assets/js/jquery-2.1.3.min.js"></script>
    <script type="text/javascript" src="./assets/js/neko.js"></script>
  </head>
  <body>
<head>
    <meta charset="UTF-8">
   <head>
    <meta charset="UTF-8">
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
            <a href="./configs.php" class="col btn btn-lg">配置</a>
            <a href="#" class="col btn btn-lg">设定</a>
        </div>
    </div>
    <div class="container text-left p-3">
    <div class="container container-bg border border-3 rounded-4 col-12 mb-4">
        <h2 class="text-center p-2 mb-3">主题设定</h2>
            <form action="settings.php" method="post">
                <div class="container text-center justify-content-md-center">
                    <div class="row justify-content-md-center">
                        <div class="col mb-3 justify-content-md-center">
                          <select class="form-select" name="themechange" aria-label="themex">
                                <option selected>Change Theme (<?php echo $neko_theme ?>)</option>
                                <?php foreach ($arrFiles as $file) echo "<option value=\"".$file.'">'.$file."</option>" ?>
                          </select>
                        </div>
                        <div class="row justify-content-md-center">
                            <div class="col justify-content-md-center mb-3">
                              <input class="btn btn-info" type="submit" value="更改主题">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
<h2 class="text-center p-2 mb-3">软体资讯</h2>
<table class="table table-borderless mb-3">
    <tbody>
        <tr>
            <td class="col-2">自动重新载入防火墙</td>
            <form action="settings.php" method="post">
                <td class="d-grid">
                    <div class="btn-group col" role="group" aria-label="ctrl">
                        <button type="submit" name="fw" value="enable" class="btn btn<?php if($fwstatus==1) echo "-outline" ?>-success <?php if($fwstatus==1) echo "disabled" ?> d-grid">启用</button>
                        <button type="submit" name="fw" value="disable" class="btn btn<?php if($fwstatus==0) echo "-outline" ?>-danger <?php if($fwstatus==0) echo "disabled" ?> d-grid">停用</button>
                    </div>
                </td>
            </form>
        </tr>
        <tr>
            <td class="col-2">客户版本</td>
            <td class="col-4">
                <div class="form-control text-center" style="display: flex; align-items: center; justify-content: center;">
                    <div style="font-family: monospace; padding-right: 10px; margin-left: -130px; ">
                        <?php
                        $package_name = "luci-app-neko"; 
                        $installed_version = trim(shell_exec("opkg list-installed | grep $package_name | awk '{print $3}'"));
                        echo htmlspecialchars($installed_version ?: '-'); 
                        ?>
                    </div>
                    <button id="updateButton" class="button" style="background-color: blue; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-left: 10px;">更新到最新版本</button>
                </div>
                <div id="logOutput"></div>
            </td>
        </tr>
        <tr>
            <td class="col-2">核心版本</td>
            <td class="col-4">
                <div class="form-control text-center" style="display: flex; align-items: center; justify-content: center;">
                    <div style="font-family: monospace; padding-right: 10px; ">
                        <div id="corever">-</div>
                    </div>
                    <button id="updateCoreButton" class="button" style="background-color: blue; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-left: 10px;">切换Mihomo内核</button>
                    <button id="updateNekoButton" class="button" style="background-color: blue; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-left: 10px;">切换NeKo内核</button>
                </div>
            </td>
        </tr>
    </tbody>
</table>

<script>
    document.getElementById('updateButton').addEventListener('click', function() {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_script.php', true); 
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        document.getElementById('logOutput').innerHTML = '开始下载更新...';

        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('logOutput').innerHTML += '\n更新完成！';
                document.getElementById('logOutput').innerHTML += '\n' + xhr.responseText; 
            } else {
                document.getElementById('logOutput').innerHTML += '\n发生错误：' + xhr.statusText;
            }
        };

        xhr.send(); 
    });

    document.getElementById('updateCoreButton').addEventListener('click', function() {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'core.php', true); 
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        document.getElementById('logOutput').innerHTML = '开始下载核心更新...';

        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('logOutput').innerHTML += '\n核心更新完成！';
                document.getElementById('logOutput').innerHTML += '\n' + xhr.responseText; 
            } else {
                document.getElementById('logOutput').innerHTML += '\n发生错误：' + xhr.statusText;
            }
        };

        xhr.send(); 
    });

    document.getElementById('updateNekoButton').addEventListener('click', function() {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'neko.php', true); 
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        document.getElementById('logOutput').innerHTML = '开始下载核心更新...';

        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('logOutput').innerHTML += '\n核心更新完成！';
                document.getElementById('logOutput').innerHTML += '\n' + xhr.responseText; 
            } else {
                document.getElementById('logOutput').innerHTML += '\n发生错误：' + xhr.statusText;
            }
        };

        xhr.send(); 
    });
</script>

<div class="container container-bg border border-3 rounded-4 col-12 mb-4">
    <h2 class="text-center p-2 mb-3">关于</h2>
    <div class="container text-center border border-3 rounded-4 col-10 mb-4">
        <br>
        <h5 class="mb-3">NekoClash</h5>
    </div>
</div>

<p class="text-center">NekoClash是一款适合家庭的Clash代理工具，该工具使用户可以轻松使用Clash代理，NekoClash由PHP和BASH编写。</p>
<p class="text-center">该工具旨在让Clash代理的使用更加容易。</p>
<p class="text-center">如果您对NekoClash有疑问或反馈，可以透过下方链接与我联系。</p>

<table class="table table-borderless callout mb-5">
    <tbody>
        <tr class="text-center">
            <td>Script</td>
            <td>GUI</td>
        </tr>
        <tr class="text-center">
            <td>NOSIGNAL</td>
            <td>NOSIGNAL</td>
        </tr>
        <tr class="text-center">
            <td>Theme</td>
            <td>Clash</td>
        </tr>
        <tr class="text-center">
            <td><a class="btn btn-outline-secondary col-10" target="_blank" href="https://getbootstrap.com">BOOTSTRAP</a></td>
            <td><a class="btn btn-outline-secondary col-10" target="_blank" href="https://github.com/MetaCubeX">METACUBEX</a></td>
        </tr>
    </tbody>
</table>

<h5 class="text-center mb-3">外部链接</h5>
<table class="table table-borderless callout mb-5">
    <tbody>
        <tr class="text-center">
            <td>Discord</td>
            <td>Github</td>
        </tr>
        <tr class="text-center callout">
            <td><a class="btn btn-outline-secondary col-10" target="_blank" href="https://discord.gg/vtV5QSq6D6">DBAI</a></td>
            <td><a class="btn btn-outline-secondary col-10" target="_blank" href="https://github.com/Thaolga/neko">Thaolga</a></td>
        </tr>
        <tr class="text-center">
            <td>Telegram</td>
            <td>Clash</td>
        </tr>
        <tr class="text-center">
            <td><a class="btn btn-outline-secondary col-10" target="_blank" href="https://t.me/joinchat/InJrhXPcuJJiMDdl">Telegram</a></td>
            <td><a class="btn btn-outline-secondary col-10" target="_blank" href="https://github.com/MetaCubeX/mihomo">Mihomo</a></td>
        </tr>
    </tbody>
</table>
    <footer class="text-center">
        <p><?php echo $footer ?></p>
    </footer>
  </body>
</html>
