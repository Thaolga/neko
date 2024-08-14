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
        <li class="nav-item">
            <a class="col btn btn-lg" data-bs-toggle="tab" href="#upload">订阅</a>
        </li>
    </ul>
</div>

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
                    <div id="upload" class="tab-pane fade">
    <?php
    $subscriptionPath = '/etc/neko/proxy_provider/';
    $subscriptionFile = $subscriptionPath . 'subscriptions.json';
    $autoUpdateConfigFile = $subscriptionPath . 'auto_update_config.json';

    $message = "";
    $subscriptions = [];
    $autoUpdateConfig = ['auto_update_enabled' => false, 'update_time' => '00:00'];

    if (!file_exists($subscriptionPath)) {
        mkdir($subscriptionPath, 0755, true);
    }

    if (!file_exists($subscriptionFile)) {
        file_put_contents($subscriptionFile, json_encode([]));
    }

    if (!file_exists($autoUpdateConfigFile)) {
        file_put_contents($autoUpdateConfigFile, json_encode($autoUpdateConfig));
    }

    $subscriptions = json_decode(file_get_contents($subscriptionFile), true);
    if (!$subscriptions) {
        for ($i = 0; $i < 7; $i++) {
            $subscriptions[$i] = [
                'url' => '',
                'file_name' => "subscription_{$i}.yaml",
            ];
        }
    }

    $autoUpdateConfig = json_decode(file_get_contents($autoUpdateConfigFile), true);

    if (isset($_POST['update'])) {
        $index = intval($_POST['index']);
        $url = $_POST['subscription_url'] ?? '';
        $customFileName = $_POST['custom_file_name'] ?? "subscription_{$index}.yaml";

        $subscriptions[$index]['url'] = $url;
        $subscriptions[$index]['file_name'] = $customFileName;

        if (!empty($url)) {
            $finalPath = $subscriptionPath . $customFileName;
            $command = "curl -fsSL -o {$finalPath} {$url}";
            exec($command . ' 2>&1', $output, $return_var);

            if ($return_var === 0) {
                $message = "订阅链接 {$url} 更新成功！文件已保存到: {$finalPath}";
            } else {
                $message = "配置更新失败！错误信息: " . implode("\n", $output);
            }
        } else {
            $message = "第" . ($index + 1) . "个订阅链接为空！";
        }

        file_put_contents($subscriptionFile, json_encode($subscriptions));
    }

    if (isset($_POST['set_auto_update'])) {
        $updateTime = $_POST['update_time'] ?? '00:00';
        $autoUpdateEnabled = isset($_POST['auto_update_enabled']);

        $autoUpdateConfig = [
            'auto_update_enabled' => $autoUpdateEnabled,
            'update_time' => $updateTime
        ];

        file_put_contents($autoUpdateConfigFile, json_encode($autoUpdateConfig));
        $message = "自动更新设置已保存！";
    }
    ?>
    <!DOCTYPE html>
    <html lang="zh-CN">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mihomo订阅程序</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: transparent; 
                margin: 0;
                padding: 0;
            }
            .container {
                padding: 20px;
                max-width: 1200px;
                margin: 0 auto;
                background-color: transparent; 
            }
            .text-center {
                text-align: center;
            }
            .input-group {
                margin-bottom: 15px;
            }
            .input-group label {
                display: block;
                margin-bottom: 5px;
            }
            .input-group input,
            .input-group textarea,
            .input-group select {
                width: 100%;
                padding: 8px;
                box-sizing: border-box;
            }
            .btn {
                padding: 10px 20px;
                border: none;
                cursor: pointer;
                color: white;
                border-radius: 4px;
                text-align: center;
                display: inline-block;
                text-decoration: none;
            }
            .btn-primary {
                background-color: #007bff; 
            }
            .btn-primary:hover {
                background-color: #0056b3;
            }
            footer {
                background-color: transparent; 
                color: white;
                padding: 10px;
                text-align: center;
            }
            .container-bg {
                background-color: transparent; 
            }
            .border {
                border: 1px solid #ccc;
            }
            .rounded-4 {
                border-radius: 4px;
            }
            .form-spacing {
                margin: 20px 0;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1 class="text-center" style="color: #00FF7F;">Mihomo订阅程序</h1>
            <p class="help-text text-center">
                Mihomo订阅支持所有格式《Base64/clash格式/节点链接》，如需解码请用订阅转换。
                <br><br>
                <a href="/nekoclash/upload.php" class="btn btn-primary">打开订阅管理器</a>
                <button id="convertButton" class="btn btn-primary">访问订阅转换网站</button>
                <br><br>     
            </p>

            <h2 class="text-center" style="color: #00FF7F;">自动更新设置</h2>
            <form method="post" class="text-center">
                <div class="input-group">
                    <label for="update_time">设置更新时间:</label>
                    <select name="update_time" id="update_time" required>
                        <?php
                        for ($h = 0; $h < 24; $h++) {
                            $time = sprintf('%02d:00', $h);
                            $selected = ($time == $autoUpdateConfig['update_time']) ? 'selected' : '';
                            echo "<option value='$time' $selected>$time</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="input-group">
                    <label for="auto_update_enabled">启用自动更新:</label>
                    <input type="checkbox" name="auto_update_enabled" id="auto_update_enabled" <?php echo $autoUpdateConfig['auto_update_enabled'] ? 'checked' : ''; ?>>
                </div>
                <button type="submit" name="set_auto_update" class="btn btn-primary">保存设置</button>
            </form>

            <div class="form-spacing"></div> 

            <?php if ($message): ?>
                <p class="text-center"><?php echo nl2br(htmlspecialchars($message)); ?></p>
            <?php endif; ?>

            <h2 class="text-center" style="color: #00FF7F;">订阅管理</h2>
            <?php for ($i = 0; $i < 7; $i++): ?>
                <form method="post" class="text-center">
                    <div class="input-group">
                        <label for="subscription_url_<?php echo $i; ?>">订阅链接 <?php echo ($i + 1); ?>:</label>
                        <input type="text" name="subscription_url" id="subscription_url_<?php echo $i; ?>" value="<?php echo htmlspecialchars($subscriptions[$i]['url']); ?>" required>
                    </div>
                    <div class="input-group">
                        <label for="custom_file_name_<?php echo $i; ?>">自定义文件名:</label>
                        <input type="text" name="custom_file_name" id="custom_file_name_<?php echo $i; ?>" value="<?php echo htmlspecialchars($subscriptions[$i]['file_name']); ?>">
                        <input type="hidden" name="index" value="<?php echo $i; ?>">
                        <button type="submit" name="update" class="btn btn-primary">更新配置</button>
                    </div>
                </form>
            <?php endfor; ?>

            <div class="container container-bg border border-3 rounded-4 col-12 mb-4">
              <h2 class="text-center p-2 mb-3">小提示</h2>
              <div class="container text-center border border-3 rounded-4 col-10 mb-4">
                <p style="color: #87CEEB; text-align: left;">
                  播放器采用github歌单推送歌曲，键盘方向键可以控制切换歌曲。终端输入./nekoclash.sh可以更新客户端和核心<br>
                  <?php
                  error_reporting(E_ALL);
                  ini_set('display_errors', 1);

                  $output = [];
                  $return_var = 0;
                  exec('uci get network.lan.ipaddr 2>&1', $output, $return_var);
                  $routerIp = trim(implode("\n", $output));

                  function isValidIp($ip) {
                  $parts = explode('.', $ip);
                  if (count($parts) !== 4) return false;
                  foreach ($parts as $part) {
                      if (!is_numeric($part) || (int)$part < 0 || (int)$part > 255) return false;
                  }
                  return true;
                  }

                  if (isValidIp($routerIp) && !in_array($routerIp, ['0.0.0.0', '255.255.255.255'])) {
                  $controlPanelUrl = "http://$routerIp/nekoclash";
                  echo "独立控制面板地址: $controlPanelUrl<br>";
                  } else {
                  echo "无法获取路由器的 IP 地址。错误信息: $routerIp";
                  }
                 ?>
              </p>
          </div>
        </div>
            <footer class="text-center">
                <p><?php echo $footer ?></p>
            </footer>
        </div>

        <script>
             document.getElementById('convertButton').onclick = function() {
                 window.open('https://suburl.v1.mk', '_blank');
             }

            document.addEventListener('DOMContentLoaded', function() {
                const autoUpdateEnabled = <?php echo json_encode($autoUpdateConfig['auto_update_enabled']); ?>;
                const updateTime = <?php echo json_encode($autoUpdateConfig['update_time']); ?>;

                if (autoUpdateEnabled) {
                    const now = new Date();
                    const updateParts = updateTime.split(':');
                    const updateHour = parseInt(updateParts[0], 10);
                    const updateMoment = new Date(now.getFullYear(), now.getMonth(), now.getDate(), updateHour, 0, 0, 0);

                    if (now > updateMoment) {
                        updateMoment.setDate(updateMoment.getDate() + 1);
                    }

                    const timeUntilUpdate = updateMoment - now;

                    function triggerUpdate() {
                        fetch(window.location.href, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: new URLSearchParams({
                                'update': true
                            })
                        }).then(response => response.text())
                        .then(data => console.log('自动更新完成', data))
                        .catch(error => console.error('自动更新错误:', error));
                    }

                    setTimeout(function() {
                        triggerUpdate();
                        setInterval(triggerUpdate, 24 * 60 * 60 * 1000); 
                    }, timeUntilUpdate);
                }
            });
        </script>
    </body>
    </html>
</div>
