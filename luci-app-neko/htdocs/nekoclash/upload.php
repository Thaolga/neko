<?php
date_default_timezone_set('Asia/Shanghai');

$heavenly_stems = ["甲", "乙", "丙", "丁", "戊", "己", "庚", "辛", "壬", "癸"];
$earthly_branches = ["子", "丑", "寅", "卯", "辰", "巳", "午", "未", "申", "酉", "戌", "亥"];
$zodiacs = ["鼠", "牛", "虎", "兔", "龙", "蛇", "马", "羊", "猴", "鸡", "狗", "猪"];
$chinese_weekdays = ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"]; 
$capital_numbers = ["初", "一", "二", "三", "四", "五", "六", "七", "八", "九", "十", "十一", "十二", "十三", "十四", "十五", "十六", "十七", "十八", "十九", "二十", "廿一", "廿二", "廿三", "廿四", "廿五", "廿六", "廿七", "廿八", "廿九", "三十"];

function convertSolarToLunar($year, $month, $day) {
    $lunar_year = 2024; 
    return [
        'year' => $lunar_year,
    ];
}

$year = date('Y');
$month = date('m');
$day = date('d');

$lunar_date = convertSolarToLunar($year, $month, $day);

if ($lunar_date === null || !isset($lunar_date['year'])) {
    echo "农历转换出错，请检查转换函数。\n";
    exit;
}

$lunar_year = $lunar_date['year'];

$base_year = 1984; 
$cycle_length = 60; 

$year_index = ($lunar_year - $base_year) % $cycle_length;
if ($year_index < 0) {
    $year_index += $cycle_length; 
}

$heavenly_stem_year = $heavenly_stems[$year_index % 10];
$earthly_branch_year = $earthly_branches[$year_index % 12];
$zodiac = $zodiacs[$year_index % 12];

$lunar_year_str = $heavenly_stem_year . $earthly_branch_year . "年 (" . $zodiac . "年)";
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            display: flex; 
            flex-direction: column;
            margin: 0;
            min-height: 100vh; 
            overflow: auto; 
            background-color: #f0f0f0;
            align-items: center; 
            justify-content: flex-start; 
        }
        .container {
            display: flex;
            flex-direction: column;
            width: 100%; 
            padding: 20px;
            box-sizing: border-box; 
            align-items: center; 
            text-align: center; 
        }
        h1 {
            color: white; 
        }
        h2 {
            color: #333;
        }
        .button-group {
            display: inline-block;
        }
        .delete-button,
        .rename-button,
        .edit-button {
            margin-left: 5px;
            cursor: pointer;
        }
        #current-time {
            margin-bottom: 20px; 
        }
    </style>
</head>
<body>
    <h1 style="color: #00FF7F;">简易文件管理器</h1>
    <div id="current-time"></div>
    <div>当前日期: <?php echo $year; ?>年<?php echo $month; ?>月<?php echo $day; ?>日</div>
    <div>农历年: <?php echo $lunar_year_str; ?></div>
    <div>今天是: <?php echo $chinese_weekdays[date('w')]; ?></div> 
</body>
</html>
    <script>
        function updateTime() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const timeString = `${hours}:${minutes}:${seconds}`;
            document.getElementById('current-time').textContent = `北京时间: ${timeString}`;
            
            if (minutes === '00' && seconds === '00') {
                speakTime(hours);
            }
        }

        function speakTime(hours) {
            const speech = new SpeechSynthesisUtterance();
            speech.lang = 'zh-CN';
            speech.text = `整点播报现在是北京时间${hours}点整`;
            window.speechSynthesis.speak(speech);
        }

        setInterval(updateTime, 1000);
        updateTime();
    </script>
</body>
</html>
<?php
$uploadDir = '/etc/neko/proxy_provider/';
$configDir = '/etc/neko/config/';

ini_set('memory_limit', '256M');

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if (!is_dir($configDir)) {
    mkdir($configDir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['fileInput'])) {
        $file = $_FILES['fileInput'];
        $uploadFilePath = $uploadDir . basename($file['name']);

        if ($file['error'] === UPLOAD_ERR_OK) {
            if (move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
                echo '文件上传成功：' . htmlspecialchars(basename($file['name']));
            } else {
                echo '文件上传失败！';
            }
        } else {
            echo '上传错误：' . $file['error'];
        }
    }

    if (isset($_FILES['configFileInput'])) {
        $file = $_FILES['configFileInput'];
        $uploadFilePath = $configDir . basename($file['name']);

        if ($file['error'] === UPLOAD_ERR_OK) {
            if (move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
                echo '配置文件上传成功：' . htmlspecialchars(basename($file['name']));
            } else {
                echo '配置文件上传失败！';
            }
        } else {
            echo '上传错误：' . $file['error'];
        }
    }

    if (isset($_POST['deleteFile'])) {
        $fileToDelete = $uploadDir . basename($_POST['deleteFile']);
        if (file_exists($fileToDelete) && unlink($fileToDelete)) {
            echo '文件删除成功：' . htmlspecialchars(basename($_POST['deleteFile']));
        } else {
            echo '文件删除失败！';
        }
    }

    if (isset($_POST['deleteConfigFile'])) {
        $fileToDelete = $configDir . basename($_POST['deleteConfigFile']);
        if (file_exists($fileToDelete) && unlink($fileToDelete)) {
            echo '配置文件删除成功：' . htmlspecialchars(basename($_POST['deleteConfigFile']));
        } else {
            echo '配置文件删除失败！';
        }
    }

    if (isset($_POST['oldFileName'], $_POST['newFileName'])) {
        $oldFileName = basename($_POST['oldFileName']);
        $newFileName = basename($_POST['newFileName']);
        $oldFilePath = $uploadDir . $oldFileName;
        $newFilePath = $uploadDir . $newFileName;

        if (file_exists($oldFilePath) && !file_exists($newFilePath)) {
            if (rename($oldFilePath, $newFilePath)) {
                echo '文件重命名成功：' . htmlspecialchars($oldFileName) . ' -> ' . htmlspecialchars($newFileName);
            } else {
                echo '文件重命名失败！';
            }
        } else {
            echo '文件重命名失败，文件不存在或新文件名已存在。';
        }
    }

    if (isset($_POST['editFile']) && isset($_POST['fileType'])) {
        $fileToEdit = ($_POST['fileType'] === 'proxy') ? $uploadDir . basename($_POST['editFile']) : $configDir . basename($_POST['editFile']);
        $fileContent = '';
        $editingFileName = htmlspecialchars($_POST['editFile']);

        if (file_exists($fileToEdit)) {
            $handle = fopen($fileToEdit, 'r');
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    $fileContent .= htmlspecialchars($line);
                }
                fclose($handle);
            } else {
                echo '无法打开文件';
            }
        }
    }

    if (isset($_POST['saveContent'], $_POST['fileName'], $_POST['fileType'])) {
        $fileToSave = ($_POST['fileType'] === 'proxy') ? $uploadDir . basename($_POST['fileName']) : $configDir . basename($_POST['fileName']);
        $contentToSave = $_POST['saveContent'];
        file_put_contents($fileToSave, $contentToSave);
        echo '<p>文件内容已更新：' . htmlspecialchars(basename($fileToSave)) . '</p>';
    }

    if (isset($_FILES['customFileInput']) && isset($_POST['customDir'])) {
        $customDir = rtrim($_POST['customDir'], '/') . '/';
        if (!is_dir($customDir)) {
            if (!mkdir($customDir, 0755, true)) {
                echo '自定义目录创建失败！';
            }
        }

        $file = $_FILES['customFileInput'];
        $uploadFilePath = $customDir . basename($file['name']);

        if ($file['error'] === UPLOAD_ERR_OK) {
            if (move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
                echo '文件上传到自定义目录成功：' . htmlspecialchars(basename($file['name']));
            } else {
                echo '文件上传到自定义目录失败！';
            }
        } else {
            echo '上传错误：' . $file['error'];
        }
    }

    if (isset($_GET['customFile'])) {
        $customDir = rtrim($_GET['customDir'], '/') . '/';
        $customFilePath = $customDir . basename($_GET['customFile']);
        if (file_exists($customFilePath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($customFilePath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($customFilePath));
            readfile($customFilePath);
            exit;
        } else {
            echo '文件不存在！';
        }
    }
}

$proxyFiles = scandir($uploadDir);
$configFiles = scandir($configDir);

if ($proxyFiles !== false) {
    $proxyFiles = array_diff($proxyFiles, array('.', '..'));
} else {
    $proxyFiles = []; 
}

if ($configFiles !== false) {
    $configFiles = array_diff($configFiles, array('.', '..'));
} else {
    $configFiles = []; 
}

function formatSize($size) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $unit = 0;
    while ($size >= 1024 && $unit < count($units) - 1) {
        $size /= 1024;
        $unit++;
    }
    return round($size, 2) . ' ' . $units[$unit];
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            background-image: url('/nekoclash/assets/img/1.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            color: white;
        }
        .editor {
            width: 100%;
            height: 400px; 
            background-color: #222; 
            color: white;
            padding: 10px;
            border: 1px solid #555;
            border-radius: 5px;
            font-family: monospace; 
        }
        .delete-button, .rename-button, .edit-button, .download-button {
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .delete-button {
            background-color: red;
            color: white;
            border: none;
        }
        .delete-button:hover {
            background-color: darkred;
        }
        .rename-button {
            background-color: lightgreen;
            color: black;
            border: none;
        }
        .rename-button:hover {
            background-color: darkgreen;
        }
        .edit-button {
            background-color: orange;
            color: white;
            border: none;
        }
        .edit-button:hover {
            background-color: darkred;
        }
        .download-button {
            background-color: lightblue;
            color: black;
            border: none;
        }
        .download-button:hover {
            background-color: deepskyblue;
        }
        .button-group {
            display: inline-flex;
            gap: 5px;
        }
    </style>
</head>
<body>
    <h1 style="color: #00FFFF;">文件上传和下载管理</h1>

    <h2 style="color: #00FF7F;">代理文件管理</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="fileInput" required>
        <input type="submit" value="上传代理文件">
    </form>
    <ul>
        <?php foreach ($proxyFiles as $file): ?>
            <?php $filePath = $uploadDir . $file; ?>
            <li>
                <a href="download.php?file=<?php echo urlencode($file); ?>"><?php echo htmlspecialchars($file); ?></a>
                (大小: <?php echo file_exists($filePath) ? formatSize(filesize($filePath)) : '文件不存在'; ?>)
                <div class="button-group">
                    <form action="" method="post" style="display:inline;">
                        <input type="hidden" name="deleteFile" value="<?php echo htmlspecialchars($file); ?>">
                        <input type="submit" class="delete-button" value="删除" onclick="return confirm('确定要删除这个文件吗？');">
                    </form>

                    <form action="" method="post" style="display:inline;">
                        <input type="hidden" name="oldFileName" value="<?php echo htmlspecialchars($file); ?>">
                        <input type="text" name="newFileName" placeholder="新文件名" required>
                        <input type="submit" class="rename-button" value="重命名">
                    </form>

                    <form action="" method="post" style="display:inline;">
                        <input type="hidden" name="editFile" value="<?php echo htmlspecialchars($file); ?>">
                        <input type="hidden" name="fileType" value="proxy">
                        <input type="submit" class="edit-button" value="编辑">
                    </form>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2 style="color: #00FF7F;">配置文件管理</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="configFileInput" required>
        <input type="submit" value="上传配置文件">
    </form>
    <ul>
        <?php foreach ($configFiles as $file): ?>
            <?php $filePath = $configDir . $file; ?>
            <li>
                <a href="download.php?file=<?php echo urlencode($file); ?>"><?php echo htmlspecialchars($file); ?></a>
                (大小: <?php echo file_exists($filePath) ? formatSize(filesize($filePath)) : '文件不存在'; ?>)
                <div class="button-group">
                    <form action="" method="post" style="display:inline;">
                        <input type="hidden" name="deleteConfigFile" value="<?php echo htmlspecialchars($file); ?>">
                        <input type="submit" class="delete-button" value="删除" onclick="return confirm('确定要删除这个文件吗？');">
                    </form>

                    <form action="" method="post" style="display:inline;">
                        <input type="hidden" name="oldFileName" value="<?php echo htmlspecialchars($file); ?>">
                        <input type="text" name="newFileName" placeholder="新文件名" required>
                        <input type="submit" class="rename-button" value="重命名">
                    </form>

                    <form action="" method="post" style="display:inline;">
                        <input type="hidden" name="editFile" value="<?php echo htmlspecialchars($file); ?>">
                        <input type="hidden" name="fileType" value="config">
                        <input type="submit" class="edit-button" value="编辑">
                    </form>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2 style="color: #00FF7F;">自定义目录文件上传</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="text" name="customDir" placeholder="自定义目录" required>
        <input type="file" name="customFileInput" required>
        <input type="submit" value="上传到自定义目录">
    </form>
    <?php
    if (isset($_GET['customDir'])) {
        $customDir = rtrim($_GET['customDir'], '/') . '/';
        if (is_dir($customDir)) {
            $customFiles = array_diff(scandir($customDir), array('.', '..'));
            echo '<ul>';
            foreach ($customFiles as $file) {
                echo '<li>';
                echo '<a href="?customDir=' . urlencode($customDir) . '&customFile=' . urlencode($file) . '">' . htmlspecialchars($file) . '</a>';
                echo ' (大小: ' . formatSize(filesize($customDir . $file)) . ')';
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '目录不存在！';
        }
    }
    ?>

    <?php if (isset($fileContent)): ?>
        <?php $fileToEdit = ($_POST['fileType'] === 'proxy') ? $uploadDir . basename($_POST['editFile']) : $configDir . basename($_POST['editFile']); ?>
        <h2 style="color: #00FF7F;">编辑文件: <?php echo $editingFileName; ?></h2>
        <p>最后更新日期: <?php echo date('Y-m-d H:i:s', filemtime($fileToEdit)); ?></p>
        <form action="" method="post">
            <textarea name="saveContent" rows="15" cols="150" class="editor"><?php echo $fileContent; ?></textarea><br>
            <input type="hidden" name="fileName" value="<?php echo htmlspecialchars($_POST['editFile']); ?>">
            <input type="hidden" name="fileType" value="<?php echo htmlspecialchars($_POST['fileType']); ?>">
            <input type="submit" value="保存内容">
        </form>
    <?php endif; ?>
    <br>
    <style>
        .button {
            text-decoration: none;
            padding: 10px;
            background-color: lightblue;
            color: black;
            border: 1px solid #007bff;
            border-radius: 5px;
            transition: background-color 0.3s; 
        }
        .button:hover {
            background-color: deepskyblue; 
        }
    </style>

    <div style="display: flex; gap: 10px;">
        <a href="javascript:history.back()" class="button">返回上一级菜单</a>
        <a href="/nekoclash/upload.php" class="button">返回当前菜单</a>
        <a href="/nekoclash/configs.php" class="button">返回配置菜单</a>
        <a href="/nekoclash" class="button">返回主菜单</a>
    </div>
</body>
</html>
<?php
$subscriptionPath = '/etc/neko/proxy_provider/';
$subscriptionFile = $subscriptionPath . 'subscriptions.json';
$clashFile = $subscriptionPath . 'clash_config.yaml';
$autoUpdateConfigFile = $subscriptionPath . 'auto_update_config.json';

$message = "";
$decodedContent = ""; 
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

if (isset($_POST['convert_base64'])) {
    $base64Content = $_POST['base64_content'] ?? '';

    if (!empty($base64Content)) {
        $decodedContent = base64_decode($base64Content); 

        if ($decodedContent === false) {
            $message = "Base64 解码失败，请检查输入！";
        } else {
            $clashConfig = "# Clash Meta Config\n\n";
            $clashConfig .= $decodedContent;
            file_put_contents($clashFile, $clashConfig);
            $message = "Clash 配置文件已生成并保存到: {$clashFile}";
        }
    } else {
        $message = "Base64 内容为空！";
    }
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
    <title>简易文件管理器</title>
    <style>
        .input-group {
            margin-bottom: 10px;
        }
        .input-group label {
            margin-right: 10px;
            white-space: nowrap;
        }
        .help-text {
            font-size: 14px;
            color: white;
            margin-bottom: 20px;
        }
        body {
            background-color: #333;
            font-family: Arial, sans-serif; 
        }
        h1, .help-text {
            color: rgb;
        }
        textarea.copyable {
            width: 50%; 
            height: 150px; 
            resize: none; 
            padding: 10px; 
            border: 1px solid #ccc; 
            border-radius: 5px;    
            background-color: #444; 
            color: white; 
            font-size: 14px; 
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.5); 
            display: none; 
        }
        textarea.copyable:focus {
            outline: none; 
            border-color: #ff79c6; 
            box-shadow: 0 0 5px rgba(255, 121, 198, 0.5); 
        }
        #copyButton {
            background-color: #00BFFF; 
            color: white; 
            padding: 5px 10px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer;
        }
        #copyButton:hover {
            background-color: #008CBA;
        }
        button[name="update"] {
            background-color: #FF6347; 
            color: white; 
            padding: 5px 10px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
        }
        button[name="update"]:hover {
            background-color: darkgreen; 
        }
        #convertButton,
        button[name="convert_base64"] {
            background-color: #00BFFF;
            color: white; 
            padding: 5px 10px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            font-size: 14px; 
        }
        #convertButton:hover,
        button[name="convert_base64"]:hover {
            background-color: #008CBA; 
        }
        button[name="set_auto_update"] {
            background-color: #32CD32; 
            color: white; 
            padding: 5px 10px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            margin-top: 10px; 
        }
        button[name="set_auto_update"]:hover {
            background-color: #228B22; 
        }
        .form-spacing {
            margin-bottom: 30px; 
        }
    </style>
</head>
<body>
    <h1 style="color: #00FF7F;">Mihomo订阅程序</h1>
    <p class="help-text">
        请在下方输入框中填写您的订阅链接，删除上方subscriptions.json文件可以清空订阅信息。<br>Mihomo订阅支持所有格式《Base64/clash格式/节点链接》，如需解码请用订阅转换。<button id="convertButton">访问订阅转换网站</button>
    <br>
        节点转换工具输入你的节点信息转换，会自动保存为代理，简化流程。      
    </p>

    <h2 style="color: #00FF7F;">自动更新设置</h2>
    <form method="post">
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
        <button type="submit" name="set_auto_update">保存设置</button>
    </form>

    <div class="form-spacing"></div> 

    <?php if ($message): ?>
        <p><?php echo nl2br(htmlspecialchars($message)); ?></p>
    <?php endif; ?>

    <?php for ($i = 0; $i < 7; $i++): ?>
        <form method="post">
            <div class="input-group">
                <label for="subscription_url_<?php echo $i; ?>">订阅链接 <?php echo ($i + 1); ?>:</label>
                <input type="text" name="subscription_url" id="subscription_url_<?php echo $i; ?>" value="<?php echo htmlspecialchars($subscriptions[$i]['url']); ?>" required>
            </div>
            <div class="input-group">
                <label for="custom_file_name_<?php echo $i; ?>">自定义文件名:</label>
                <input type="text" name="custom_file_name" id="custom_file_name_<?php echo $i; ?>" value="<?php echo htmlspecialchars($subscriptions[$i]['file_name']); ?>">
                <input type="hidden" name="index" value="<?php echo $i; ?>">
                <button type="submit" name="update">更新配置</button>
            </div>
        </form>
    <?php endfor; ?>

    <h2 style="color: #00FF7F;">Base64 节点信息转换</h2>
    <form method="post">
        <div class="input-group">
            <label for="base64_content">Base64 内容:</label>
            <textarea name="base64_content" id="base64_content" rows="4" required></textarea>
        </div>
        <button type="submit" name="convert_base64">生成节点信息</button>
    </form>

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

                setTimeout(function() {
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
                    
                    setInterval(function() {
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
                    }, 24 * 60 * 60 * 1000); 
                }, timeUntilUpdate);
            }
        });
    </script>
</body>
</html>
    <style>
        button[name="convert"] {
            background-color: #00BFFF; 
            color: white; 
            padding: 5px 10px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            font-size: 14px; 
            margin-top: 10px; 
        }

        button[name="convert"]:hover {
            background-color: #008CBA; 
        }
    </style>

    <h1 style="color: #00FF7F;">节点转换工具</h1>
  <form method="post">
        <textarea name="input" rows="10" cols="50" placeholder="粘贴 ss//vless//vmess//trojan//hysteria2 节点信息..."></textarea>

        <button type="submit" name="convert">转换</button>
    </form>

<?php
function parseVmess($base, $tmpdata) {
    $decoded = base64_decode($base['host']);
    $urlparsed = array();
    $arrjs = json_decode($decoded, true);
    if (!empty($arrjs['v'])) {
        $urlparsed['cfgtype'] = isset($base['scheme']) ? $base['scheme'] : '';
        $urlparsed['name'] = isset($arrjs['ps']) ? $arrjs['ps'] : '';
        $urlparsed['host'] = isset($arrjs['add']) ? $arrjs['add'] : '';
        $urlparsed['port'] = isset($arrjs['port']) ? $arrjs['port'] : '';
        $urlparsed['uuid'] = isset($arrjs['id']) ? $arrjs['id'] : '';
        $urlparsed['alterId'] = isset($arrjs['aid']) ? $arrjs['aid'] : '';
        $urlparsed['type'] = isset($arrjs['net']) ? $arrjs['net'] : '';
        $urlparsed['path'] = isset($arrjs['path']) ? $arrjs['path'] : '';
        $urlparsed['security'] = isset($arrjs['type']) ? $arrjs['type'] : '';
        $urlparsed['sni'] = isset($arrjs['host']) ? $arrjs['host'] : '';
        $urlparsed['tls'] = isset($arrjs['tls']) ? $arrjs['tls'] : '';
        return printcfg($urlparsed);
    } else {
        return "DECODING FAILED! PLEASE CHECK YOUR URL!";
    }
}

function parseUrl($basebuff) {
    $urlparsed = array();
    $querybuff = array();
    $urlparsed['cfgtype'] = isset($basebuff['scheme']) ? $basebuff['scheme'] : '';
    $urlparsed['name'] = isset($basebuff['fragment']) ? $basebuff['fragment'] : '';
    $urlparsed['host'] = isset($basebuff['host']) ? $basebuff['host'] : '';
    $urlparsed['port'] = isset($basebuff['port']) ? $basebuff['port'] : '';

    if ($urlparsed['cfgtype'] == "ss") {
        $urlparsed['uuid'] = isset($basebuff['user']) ? $basebuff['user'] : '';
        $basedata = explode(":", base64_decode($urlparsed['uuid']));
        $urlparsed['cipher'] = $basedata[0];
        $urlparsed['uuid'] = $basedata[1];
    } else {
        $urlparsed['uuid'] = isset($basebuff['user']) ? $basebuff['user'] : '';
    }

    // Ensure 'query' parameter exists before processing
    $tmpquery = isset($basebuff['query']) ? $basebuff['query'] : '';
    if ($urlparsed['cfgtype'] == "ss") {
        $tmpbuff = array();
        $tmpstr = "";
        $tmpquery2 = explode(";", $tmpquery);
        for ($x = 0; $x < count($tmpquery2); $x++) {
            $tmpstr .= $tmpquery2[$x] . "&";
        }
        parse_str($tmpstr, $querybuff);
        $urlparsed['mux'] = isset($querybuff['mux']) ? $querybuff['mux'] : '';
        $urlparsed['host2'] = isset($querybuff['host2']) ? $querybuff['host2'] : '';
    } else {
        parse_str($tmpquery, $querybuff);
    }

    $urlparsed['type'] = isset($querybuff['type']) ? $querybuff['type'] : '';
    $urlparsed['path'] = isset($querybuff['path']) ? $querybuff['path'] : '';
    $urlparsed['mode'] = isset($querybuff['mode']) ? $querybuff['mode'] : '';
    $urlparsed['plugin'] = isset($querybuff['plugin']) ? $querybuff['plugin'] : '';
    $urlparsed['security'] = isset($querybuff['security']) ? $querybuff['security'] : '';
    $urlparsed['encryption'] = isset($querybuff['encryption']) ? $querybuff['encryption'] : '';
    $urlparsed['serviceName'] = isset($querybuff['serviceName']) ? $querybuff['serviceName'] : '';
    $urlparsed['sni'] = isset($querybuff['sni']) ? $querybuff['sni'] : '';

    return printcfg($urlparsed);
}

function printcfg($data) {
    $outcfg = "";
    if (empty($GLOBALS['isProxiesPrinted'])) {
        $outcfg .= "proxies:\n";
        $GLOBALS['isProxiesPrinted'] = true;
    }
    if ($data['cfgtype'] == "vless") {
        if (!empty($data['name'])) $outcfg .= "    - name: " . $data['name'] . "\n";
        else $outcfg .= "    - name: VLESS\n";
        $outcfg .= "      type: " . $data['cfgtype'] . "\n";
        $outcfg .= "      server: " . $data['host'] . "\n";
        $outcfg .= "      port: " . $data['port'] . "\n";
        $outcfg .= "      uuid: " . $data['uuid'] . "\n";
        $outcfg .= "      cipher: auto\n";
        $outcfg .= "      tls: true\n";
        if ($data['type'] == "ws") {
            $outcfg .= "      network: " . $data['type'] . "\n";
            $outcfg .= "      ws-opts: \n";
            $outcfg .= "       path: " . $data['path'] . "\n";
            $outcfg .= "       Headers: \n";
            $outcfg .= "          Host: " . $data['host'] . "\n";
            $outcfg .= "       flow:  \n";
            $outcfg .= "          client-fingerprint: chrome\n"; 
        } else if ($data['type'] == "grpc") {
            $outcfg .= "      network: " . $data['type'] . "\n";
            $outcfg .= "      grpc-opts: \n";
            $outcfg .= "       grpc-service-name: " . $data['serviceName'] . "\n";
        }
        $outcfg .= "      udp: true\n";
        $outcfg .= "      skip-cert-verify: true \n";
    } else if ($data['cfgtype'] == "trojan") {
    if (!empty($data['name'])) $outcfg .= "    - name: " . $data['name'] . "\n";
    else $outcfg .= "    - name: TROJAN\n";
    
    $outcfg .= "      type: " . $data['cfgtype'] . "\n";
    $outcfg .= "      server: " . $data['host'] . "\n";
    $outcfg .= "      port: " . $data['port'] . "\n";
    $outcfg .= "      password: " . $data['uuid'] . "\n";
    
    if (!empty($data['sni'])) {
        $outcfg .= "      sni: " . $data['sni'] . "\n";
    } else {
        $outcfg .= "      sni: " . $data['host'] . "\n";
    }

    if ($data['type'] == "ws") {
        $outcfg .= "      network: " . $data['type'] . "\n";
        $outcfg .= "      ws-opts: \n";
        $outcfg .= "       path: " . $data['path'] . "\n";
        $outcfg .= "       Headers: \n";
        $outcfg .= "          Host: " . (isset($data['sni']) && !empty($data['sni']) ? $data['sni'] : $data['host']) . "\n";
    } else if ($data['type'] == "grpc") {
        $outcfg .= "      network: " . $data['type'] . "\n";
        $outcfg .= "      grpc-opts: \n";
        $outcfg .= "       grpc-service-name: " . $data['serviceName'] . "\n";
    }
    
    $outcfg .= "      udp: true\n";
    $outcfg .= "      skip-cert-verify: true \n";
    } else if ($data['cfgtype'] == "hysteria2" || $scheme == "hy2") {
    if (!empty($data['name'])) {
        $outcfg .= "    - name: " . $data['name'] . "\n";
    } else {
        $outcfg .= "    - name: HYSTERIA2\n";
    }
        $outcfg .= "      server: " . $data['host'] . "\n";
        $outcfg .= "      port: " . $data['port'] . "\n";
        $outcfg .= "      type: " . $data['cfgtype'] . "\n";
        $outcfg .= "      password: " . $data['uuid'] . "\n";
        $outcfg .= "      udp: true\n";
        $outcfg .= "      ports: 20000-55000\n";
        $outcfg .= "      mport: 20000-55000\n";
        $outcfg .= "      skip-cert-verify: true\n";
        $outcfg .= "      sni: " . (isset($data['sni']) && !empty($data['sni']) ? $data['sni'] : $data['host']) . "\n";  
    } else if ($data['cfgtype'] == "ss") {
        if (!empty($data['name'])) $outcfg .= "    - name: " . $data['name'] . "\n";
        else $outcfg .= "    - name: SHADOWSOCKS\n";
        $outcfg .= "      type: " . $data['cfgtype'] . "\n";
        $outcfg .= "      server: " . $data['host'] . "\n";
        $outcfg .= "      port: " . $data['port'] . "\n";
        $outcfg .= "      cipher: " . $data['cipher'] . "\n";
        $outcfg .= "      password: " . $data['uuid'] . "\n";
        if ($data['plugin'] == "v2ray-plugin" || $data['plugin'] == "xray-plugin") {
            $outcfg .= "      plugin: " . $data['plugin'] . "\n";
            $outcfg .= "      plugin-opts: \n";
            $outcfg .= "       mode: websocket\n";
            $outcfg .= "       # path: " . $data['path'] . "\n";
            $outcfg .= "       mux: " . $data['mux'] . "\n";
            $outcfg .= "       # tls: true \n";
            $outcfg .= "       # skip-cert-verify: true \n";
            $outcfg .= "       # headers: \n";
            $outcfg .= "       #    custom: value\n";
        } else if ($data['plugin'] == "obfs") {
            $outcfg .= "      plugin: " . $data['plugin'] . "\n";
            $outcfg .= "      plugin-opts: \n";
            $outcfg .= "       mode: tls\n";
            $outcfg .= "       # host: " . $data['host2'] . "\n";
        }
        $outcfg .= "      udp: true\n";
        $outcfg .= "      skip-cert-verify: true \n";
    } else if ($data['cfgtype'] == "vmess") {
        if (!empty($data['name'])) $outcfg .= "    - name: " . $data['name'] . "\n";
        else $outcfg .= "    - name: VMESS\n";
        $outcfg .= "      type: " . $data['cfgtype'] . "\n";
        $outcfg .= "      server: " . $data['host'] . "\n";
        $outcfg .= "      port: " . $data['port'] . "\n";
        $outcfg .= "      uuid: " . $data['uuid'] . "\n";
        $outcfg .= "      alterId: " . $data['alterId'] . "\n";
        $outcfg .= "      cipher: auto\n";
        $outcfg .= "      tls: " . ($data['tls'] == "tls" ? "true" : "false") . "\n";
        $outcfg .= "      servername: " . (!empty($data['sni']) ? $data['sni'] : $data['host']) . "\n";
        $outcfg .= "      network: " . $data['type'] . "\n";
        if ($data['type'] == "ws") {
            $outcfg .= "      ws-opts: \n";
            $outcfg .= "       path: " . $data['path'] . "\n";
            $outcfg .= "       Headers: \n";
            $outcfg .= "          Host: " . $data['sni'] . "\n";
        } else if ($data['type'] == "grpc") {
            $outcfg .= "      grpc-opts: \n";
            $outcfg .= "       grpc-service-name: " . $data['serviceName'] . "\n";
        } else if ($data['type'] == "h2") {
            $outcfg .= "      h2-opts: \n";
            $outcfg .= "       host: \n";
            $outcfg .= "         - google.com \n";
            $outcfg .= "         - bing.com \n";
            $outcfg .= "       path: " . $data['path'] . "\n";
        } else if ($data['type'] == "http") {
            $outcfg .= "      # http-opts: \n";
            $outcfg .= "      #   method: \"GET\"\n";
            $outcfg .= "      #   path: \n";
            $outcfg .= "      #     - '/'\n";
            $outcfg .= "      #   headers: \n";
            $outcfg .= "      #     Connection: \n";
            $outcfg .= "      #       - keep-alive\n";
        }
        $outcfg .= "      udp: true\n";
        $outcfg .= "      skip-cert-verify: true \n";
    }
    return $outcfg;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = $_POST['input'] ?? ''; 
    if (empty($input)) {
        echo "Input is empty. Please provide the necessary information.";
    } else {
        $lines = explode("\n", trim($input));  
        $allcfgs = "";  
        $GLOBALS['isProxiesPrinted'] = false;  

        foreach ($lines as $line) {
            $base64url = parse_url($line);
            $base64url = array_map('urldecode', $base64url);
            $tmpdata = 'output.txt';  // Output file name

            if (isset($base64url['scheme']) && $base64url['scheme'] === 'vmess') {
                $allcfgs .= parseVmess($base64url, $tmpdata);
            } else {
                $allcfgs .= parseUrl($base64url);
            }
        }

        $file_path = '/etc/neko/proxy_provider/subscription_7.json';
        file_put_contents($file_path, $allcfgs);

        echo "<h2 style=\"color: #00FFFF;\">转换完成</h2>";
        echo "<p>配置文件已经成功保存到 <strong>$file_path</strong></p>";
        echo "<textarea id='output' readonly style='width:100%;height:400px;'>$allcfgs</textarea>";
        echo "<button onclick='copyToClipboard()'>复制</button>";
        echo "<script>
            function copyToClipboard() {
                var output = document.getElementById('output');
                output.select();
                document.execCommand('copy');
                alert('复制成功');
            }
        </script>";
    }
}
?>


