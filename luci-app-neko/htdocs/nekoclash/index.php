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
            <a href="./dashboard.php" class="col btn btn-lg">面板</a>
            <a href="./configs.php" class="col btn btn-lg">配置</a>
            <a href="./settings.php" class="col btn btn-lg">设定</a>
        </div>
    </div>
    <div class="container text-left p-3">
       
        <div class="container container-bg border border-3 rounded-4 col-12 mb-4">
    <h2 class="text-center p-2">运行状况</h2>
    <table class="table table-borderless mb-2">
        <div class="container container-bg border border-3 rounded-4 col-12 mb-4">
   <br>
<?php
$translate = [
    'United States' => '美国',
    'China' => '中国',
    'ISP' => '互联网服务提供商',
    'Japan' => '日本',
    'South Korea' => '韩国',
    'Germany' => '德国',
    'France' => '法国',
    'United Kingdom' => '英国',
    'Canada' => '加拿大',
    'Australia' => '澳大利亚',
    'Russia' => '俄罗斯',
    'India' => '印度',
    'Brazil' => '巴西',
    'Netherlands' => '荷兰',
    'Singapore' => '新加坡',
    'Hong Kong' => '香港',
    'Saudi Arabia' => '沙特阿拉伯',
    'Turkey' => '土耳其',
    'Italy' => '意大利',
    'Spain' => '西班牙',
    'Thailand' => '泰国',
    'Malaysia' => '马来西亚',
    'Indonesia' => '印度尼西亚',
    'South Africa' => '南非',
    'Mexico' => '墨西哥',
    'Israel' => '以色列',
    'Sweden' => '瑞典',
    'Switzerland' => '瑞士',
    'Norway' => '挪威',
    'Denmark' => '丹麦',
    'Belgium' => '比利时',
    'Finland' => '芬兰',
    'Poland' => '波兰',
    'Austria' => '奥地利',
    'Greece' => '希腊',
    'Portugal' => '葡萄牙',
    'Ireland' => '爱尔兰',
    'New Zealand' => '新西兰',
    'United Arab Emirates' => '阿拉伯联合酋长国',
    'Argentina' => '阿根廷',
    'Chile' => '智利',
    'Colombia' => '哥伦比亚',
    'Philippines' => '菲律宾',
    'Vietnam' => '越南',
    'Pakistan' => '巴基斯坦',
    'Egypt' => '埃及',
    'Nigeria' => '尼日利亚',
    'Kenya' => '肯尼亚',
    'Morocco' => '摩洛哥',
    'Google' => '谷歌',
    'Amazon' => '亚马逊',
    'Microsoft' => '微软',
    'Facebook' => '脸书',
    'Apple' => '苹果',
    'IBM' => 'IBM',
    'Alibaba' => '阿里巴巴',
    'Tencent' => '腾讯',
    'Baidu' => '百度',
    'Verizon' => '威瑞森',
    'AT&T' => '美国电话电报公司',
    'T-Mobile' => 'T-移动',
    'Vodafone' => '沃达丰',
    'China Telecom' => '中国电信',
    'China Unicom' => '中国联通',
    'China Mobile' => '中国移动', 
    'Chunghwa Telecom' => '中华电信',   
    'Amazon Web Services (AWS)' => '亚马逊网络服务 (AWS)',
    'Google Cloud Platform (GCP)' => '谷歌云平台 (GCP)',
    'Microsoft Azure' => '微软Azure',
    'Oracle Cloud' => '甲骨文云',
    'Alibaba Cloud' => '阿里云',
    'Tencent Cloud' => '腾讯云',
    'DigitalOcean' => '数字海洋',
    'Linode' => '林诺德',
    'OVHcloud' => 'OVH 云',
    'Hetzner' => '赫兹纳',
    'Vultr' => '沃尔特',
    'OVH' => 'OVH',
    'DreamHost' => '梦想主机',
    'InMotion Hosting' => '动态主机',
    'HostGator' => '主机鳄鱼',
    'Bluehost' => '蓝主机',
    'A2 Hosting' => 'A2主机',
    'SiteGround' => '站点地',
    'Liquid Web' => '液态网络',
    'Kamatera' => '卡玛特拉',
    'IONOS' => 'IONOS',
    'InterServer' => '互联服务器',
    'Hostwinds' => '主机之风',
    'ScalaHosting' => '斯卡拉主机',
    'Networks' => '网络',
    'Psychz Networks' => 'Psychz网络',
    'GreenGeeks' => '绿色极客'
];
$lang = $_GET['lang'] ?? 'en';
?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($lang); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="//whois.pconline.com.cn">
    <link rel="dns-prefetch" href="//forge.speedtest.cn">
    <link rel="dns-prefetch" href="//api-ipv4.ip.sb">
    <link rel="dns-prefetch" href="//api.ipify.org">
    <link rel="dns-prefetch" href="//api.ttt.sh">
    <link rel="dns-prefetch" href="//qqwry.api.skk.moe">
    <link rel="dns-prefetch" href="//d.skk.moe">
    <link rel="preconnect" href="https://forge.speedtest.cn">
    <link rel="preconnect" href="https://whois.pconline.com.cn">
    <link rel="preconnect" href="https://api-ipv4.ip.sb">
    <link rel="preconnect" href="https://api.ipify.org">
    <link rel="preconnect" href="https://api.ttt.sh">
    <link rel="preconnect" href="https://qqwry.api.skk.moe">
    <link rel="preconnect" href="https://d.skk.moe">
    <style>
        .status {
            display: flex;
            align-items: center; 
            justify-content: center; 
            text-align: center; 
            flex-direction: column; 
        }

        .img-con {
            margin-bottom: 1rem; 
        }

        .img-con img {
            width: 65px; 
            height: auto; 
        }

        .green {
            font-size: .9rem; 
            color: #2dce89; 
        }

        .red {
            font-size: .9rem; 
            color: #fb6340; 
        }

        .yellow {
            font-size: .9rem; 
            color: #fb9a05; 
        }

        .block {
            font-size: .8125rem; 
            font-weight: 600; 
            color: #8898aa; 
            line-height: 1.8em; 
            margin: 0; 
        }

        .ip-address {
            color: #2dce89; 
            margin-bottom: 0.5rem; 
        }

        .info {
            color: #fb6340; 
        }
    </style>
</head>
<body>
<?php if (in_array($lang, ['zh-cn', 'en', 'auto'])): ?>
    <fieldset class="cbi-section">
        <div class="status">
            <div class="img-con">
                <img src="/nekoclash/assets/neko/img/loading.svg" id="flag" class="pure-img" title="国旗">
            </div>
            <div class="block">
                <p id="d-ip" class="green ip-address">Checking...</p>
                <p id="ipip" class="info"></p>
            </div>
        </div>
    </fieldset>
<?php endif; ?>

<script src="/nekoclash/assets/neko/js/jquery.min.js"></script>
<script type="text/javascript">
    const _IMG = '/nekoclash/assets/neko/';
    const translate = <?php echo json_encode($translate, JSON_UNESCAPED_UNICODE); ?>;
    let cachedIP = null;
    let cachedInfo = null;
    let random = parseInt(Math.random() * 100000000);

    let IP = {
        get: (url, type) =>
            fetch(url, { method: 'GET' }).then((resp) => {
                if (type === 'text')
                    return Promise.all([resp.ok, resp.status, resp.text(), resp.headers]);
                else
                    return Promise.all([resp.ok, resp.status, resp.json(), resp.headers]);
            }).then(([ok, status, data, headers]) => {
                if (ok) {
                    return { ok, status, data, headers };
                } else {
                    throw new Error(JSON.stringify(data.error));
                }
            }).catch(error => {
                console.error("Error fetching data:", error);
                throw error;
            }),
        Ipip: (ip, elID) => {
            if (ip === cachedIP && cachedInfo) {
                console.log("Using cached IP info");
                IP.updateUI(cachedInfo, elID);
            } else {
                IP.get(`https://api.ip.sb/geoip/${ip}`, 'json')
                    .then(resp => {
                        cachedIP = ip;  
                        cachedInfo = resp.data;  
                        IP.updateUI(resp.data, elID);
                    })
                    .catch(error => {
                        console.error("Error in Ipip function:", error);
                    });
            }
        },
        updateUI: (data, elID) => {
            let country = translate[data.country] || data.country;
            let isp = translate[data.isp] || data.isp;
            let asnOrganization = translate[data.asn_organization] || data.asn_organization;

            // Check system language
            if (data.country === 'Taiwan') {
                country = (navigator.language === 'en') ? 'China Taiwan' : '中国台湾省';
            }

            document.getElementById(elID).innerHTML = `${country} ${isp} ${asnOrganization}`;
            $("#flag").attr("src", _IMG + "flags/" + data.country + ".png");
            document.getElementById(elID).style.color = '#FF00FF';
        },
        getIpipnetIP: () => {
            if (cachedIP) {
                document.getElementById('d-ip').innerHTML = cachedIP;
                IP.updateUI(cachedInfo, 'ipip');
            } else {
                IP.get(`https://api.ipify.org?format=json&z=${random}`, 'json')
                    .then((resp) => {
                        let ip = resp.data.ip;
                        cachedIP = ip; 
                        document.getElementById('d-ip').innerHTML = ip;
                        return ip;
                    })
                    .then(ip => {
                        IP.Ipip(ip, 'ipip');
                    })
                    .catch(error => {
                        console.error("Error in getIpipnetIP function:", error);
                    });
            }
        }
    }

    IP.getIpipnetIP();
    setInterval(IP.getIpipnetIP, 5000);
</script>
</body>
</html>
       
<tbody>
    <tr>
<?php
$singbox_status = 0;
$neko_status = 0;

$logDir = '/etc/neko/tmp/';
$logFile = $logDir . 'log.txt';
$kernelLogFile = $logDir . 'neko_log.txt';
$singBoxLogFile = $logDir . 'singbox_log.txt';
$singboxStartLogFile = $logDir . 'singbox_start_log.txt'; 

$singBoxPath = '/etc/neko/core/sing-box';
$configFilePath = '/etc/neko/config/config.json';

function isSingboxRunning() {
    global $singBoxPath;
    $command = "ps w | grep '$singBoxPath' | grep -v grep";
    exec($command, $output);
    return !empty($output);
}

function isMihomoRunning() {
    $command = "ps w | grep 'mihomo' | grep -v grep";
    exec($command, $output);
    return !empty($output);
}

if (isSingboxRunning()) {
    $singbox_status = 1; 
} else {
    $singbox_status = 0; 
}

if (isMihomoRunning()) {
    $neko_status = 1; 
} else {
    $neko_status = 0; 
}

if ($neko_status == 1) {
    $str_cfg = 'Mihomo 配置文件';
} elseif ($singbox_status == 1) {
    $str_cfg = 'Sing-box 配置文件';
} else {
    $str_cfg = '无运行中的服务';
}

function getSingboxPID() {
    global $singBoxPath;
    $command = "ps w | grep '$singBoxPath' | grep -v grep | awk '{print $1}'";
    exec($command, $output);
    return isset($output[0]) ? $output[0] : null;
}

function stopSingbox() {
    $pid = getSingboxPID();
    if ($pid) {
        exec("kill -9 $pid", $output, $returnVar);
        return $returnVar === 0;
    }
    return false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['singbox'])) {
        if ($_POST['singbox'] === 'start') {
            exec("$singBoxPath run -c $configFilePath > $singBoxLogFile 2>&1 &", $output, $returnVar);
            if ($returnVar === 0) {
                $singbox_status = 1;
                file_put_contents($singBoxLogFile, "Sing-box 已启动\n", FILE_APPEND);
            } else {
                file_put_contents($singBoxLogFile, "启动 Sing-box 失败\n", FILE_APPEND);
            }
        } elseif ($_POST['singbox'] === 'disable') {
            $success = stopSingbox();
            if ($success) {
                $singbox_status = 0;
                file_put_contents($singBoxLogFile, "Sing-box 已停止\n", FILE_APPEND);
            } else {
                file_put_contents($singBoxLogFile, "停止 Sing-box 失败\n", FILE_APPEND);
            }
        } elseif ($_POST['singbox'] === 'restart') {
            $success = stopSingbox();
            if ($success) {
                exec("$singBoxPath run -c $configFilePath > $singBoxLogFile 2>&1 &", $output, $returnVar);
                if ($returnVar === 0) {
                    $singbox_status = 1;
                    file_put_contents($singBoxLogFile, "Sing-box 已重启\n", FILE_APPEND);
                } else {
                    file_put_contents($singBoxLogFile, "重启 Sing-box 失败\n", FILE_APPEND);
                }
            } else {
                file_put_contents($singBoxLogFile, "停止 Sing-box 失败\n", FILE_APPEND);
            }
        }
    }

    if (isset($_POST['clear_singbox_log'])) {
        file_put_contents($singBoxLogFile, ''); 
        $message = 'Sing-box 日志已清空';
    }

    if (isset($_POST['clear_plugin_log'])) {
        file_put_contents($logFile, ''); 
        $message = '插件日志已清空';
    }

    if (isset($_POST['clear_kernel_log'])) {
        file_put_contents($kernelLogFile, ''); 
        $message = '内核日志已清空';
    }
}

function readLogFile($filePath) {
    if (file_exists($filePath)) {
        return nl2br(htmlspecialchars(file_get_contents($filePath)));
    } else {
        return '日志文件不存在';
    }
}

$logContent = readLogFile($logFile);
$kernelLogContent = readLogFile($kernelLogFile);
$singboxLogContent = readLogFile($singBoxLogFile);
$singboxStartLogContent = readLogFile($singboxStartLogFile); 
?>

<div class="container container-bg border border-3 col-12 mb-4">
    <h2 class="text-center p-2">NekoClash 控制面板</h2>
    <table class="table table-borderless mb-2">
        <tbody>
            <tr>
        <td>状态</td>
        <td class="d-grid">
            <div class="btn-group col" role="group" aria-label="ctrl">
                <?php
                    if ($neko_status == 1) {
                        echo "<button type=\"button\" class=\"btn btn-success\">Mihomo 运行中</button>\n";
                    } else {
                        echo "<button type=\"button\" class=\"btn btn-outline-danger\">Mihomo 未运行</button>\n";
                    }

                    if ($singbox_status == 1) {
                        echo "<button type=\"button\" class=\"btn btn-success\">Sing-box 运行中</button>\n";
                    } else {
                        echo "<button type=\"button\" class=\"btn btn-outline-danger\">Sing-box 未运行</button>\n";
                    }

                    echo "<button type=\"button\" class=\"btn btn-warning d-grid\">$str_cfg</button>\n";
                ?>
            </div>
        </td>
    </tr>
    <tr>
        <td>控制</td>
        <form action="index.php" method="post">
            <td class="d-grid">
                <div class="btn-group col" role="group" aria-label="ctrl">
                    <button type="submit" name="neko" value="start" class="btn btn<?php if ($neko_status == 1) echo "-outline" ?>-success <?php if ($neko_status == 1) echo "disabled" ?> d-grid">启用 Mihomo</button>
                    <button type="submit" name="neko" value="disable" class="btn btn<?php if ($neko_status == 0) echo "-outline" ?>-danger <?php if ($neko_status == 0) echo "disabled" ?> d-grid">停用 Mihomo</button>
                    <button type="submit" name="neko" value="restart" class="btn btn<?php if ($neko_status == 0) echo "-outline" ?>-warning <?php if ($neko_status == 0) echo "disabled" ?> d-grid">重启 Mihomo</button>
                </div>
            </td>
        </form>
        <form action="index.php" method="post">
            <td class="d-grid">
                <div class="btn-group col" role="group" aria-label="ctrl">
                    <button type="submit" name="singbox" value="start" class="btn btn<?php if ($singbox_status == 1) echo "-outline" ?>-success <?php if ($singbox_status == 1) echo "disabled" ?> d-grid">启用 Sing-box</button>
                    <button type="submit" name="singbox" value="disable" class="btn btn<?php if ($singbox_status == 0) echo "-outline" ?>-danger <?php if ($singbox_status == 0) echo "disabled" ?> d-grid">停用 Sing-box</button>
                    <button type="submit" name="singbox" value="restart" class="btn btn<?php if ($singbox_status == 0) echo "-outline" ?>-warning <?php if ($singbox_status == 0) echo "disabled" ?> d-grid">重启 Sing-box</button>
                </div>
            </td>
        </form>
    </tr>
    <tr>
        <td>运行模式</td>
        <td class="d-grid">
             <?php
             $mode_placeholder = '';
             if ($neko_status == 1) {
             $mode_placeholder = $neko_cfg['echanced'] . " | " . $neko_cfg['mode'];
             } elseif ($singbox_status == 1) {
             $mode_placeholder = "Rule 模式";
             } else {
             $mode_placeholder = "未运行";
             }
             ?>
            <input class="form-control text-center" name="mode" type="text" placeholder="<?php echo $mode_placeholder; ?>" disabled>
        </td>
    </tr>
</tbody>
    </table>
</div>

<div class="container container-bg border border-3 rounded-4 col-12 mb-4">
    <h2 class="text-center p-2">系统信息</h2>
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
                <td class="col-7"><?php echo "$cpuLoadAvg1Min $cpuLoadAvg5Min $cpuLoadAvg15Min" ?></td>
            </tr>
            <tr>
                <td>运行时间</td>
                <td class="col-7"><?php echo "{$days}天 {$hours}小时 {$minutes}分钟 {$seconds}秒" ?></td>
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
        </tbody>
    </table>
</div>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container container-bg border border-3 rounded-4 col-12 mb-4">
        <h2 class="text-center p-2">日志</h2>
        <div class="row mt-3">
            <div class="col">
                <h4>插件日志</h4>
                <textarea class="form-control" rows="10" readonly><?php echo $logContent; ?></textarea>
                <form action="index.php" method="post" class="mt-3 text-center">
                    <button type="submit" name="clear_plugin_log" class="btn btn-danger">清空插件日志</button>
                </form>
            </div>
            <div class="col">
                <h4>内核日志</h4>
                <textarea class="form-control" rows="10" readonly><?php echo $kernelLogContent; ?></textarea>
                <form action="index.php" method="post" class="mt-3 text-center">
                    <button type="submit" name="clear_kernel_log" class="btn btn-danger">清空内核日志</button>
                </form>
            </div>
            <div class="col">
                <h4>Sing-box 日志</h4>
                <textarea class="form-control" rows="10" readonly><?php echo $singboxLogContent; ?></textarea>
                <form action="index.php" method="post" class="mt-3 text-center">
                    <button type="submit" name="clear_singbox_log" class="btn btn-danger">清空 Sing-box 日志</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <footer class="text-center">
        <p><?php echo isset($message) ? $message : ''; ?></p>
        <p><?php echo $footer; ?></p>
    </footer>
</body>
</html>
