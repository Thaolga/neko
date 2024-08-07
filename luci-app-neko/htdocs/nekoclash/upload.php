<?php
date_default_timezone_set('Asia/Shanghai');
$lunar_months = ["正月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "冬月", "腊月"];
$heavenly_stems = ["甲", "乙", "丙", "丁", "戊", "己", "庚", "辛", "壬", "癸"];
$earthly_branches = ["子", "丑", "寅", "卯", "辰", "巳", "午", "未", "申", "酉", "戌", "亥"];
$zodiacs = ["鼠", "牛", "虎", "兔", "龙", "蛇", "马", "羊", "猴", "鸡", "狗", "猪"];

$chinese_weekdays = ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"];

$capital_numbers = ["初", "一", "二", "三", "四", "五", "六", "七", "八", "九", "十", "十一", "十二", "十三", "十四", "十五", "十六", "十七", "十八", "十九", "二十", "廿一", "廿二", "廿三", "廿四", "廿五", "廿六", "廿七", "廿八", "廿九", "三十"];

function convertSolarToLunar($year, $month, $day) {
    $lunar_year = 2024; 
    $lunar_month = 7;   
    $lunar_day = 3;    
    $is_leap = false;   

    return [
        'year' => $lunar_year,
        'month' => $lunar_month,
        'day' => $lunar_day,
        'is_leap' => $is_leap
    ];
}

$year = date('Y');
$month = date('m');
$day = date('d');

$lunar_date = convertSolarToLunar($year, $month, $day);

if ($lunar_date === null || !isset($lunar_date['year'], $lunar_date['month'], $lunar_date['day'])) {
    echo "农历转换出错，请检查转换函数。\n";
    exit;
}

$lunar_year = $lunar_date['year'];
$lunar_month = $lunar_date['month'];
$lunar_day = $lunar_date['day'];
$is_leap = $lunar_date['is_leap'];

$base_year = 1900;
$cycle_length = 60;

$year_index = ($lunar_year - $base_year) % $cycle_length;
$heavenly_stem_year = $heavenly_stems[$year_index % 10];
$earthly_branch_year = $earthly_branches[$year_index % 12];
$zodiac = $zodiacs[$year_index % 12];

$month_index = (($lunar_year - $base_year) * 12 + $lunar_month) % $cycle_length;
$heavenly_stem_month = $heavenly_stems[$month_index % 10];
$earthly_branch_month = $earthly_branches[$month_index % 12];

$day_index = ((($lunar_year - $base_year) * 12 + $lunar_month) * 30 + $lunar_day) % $cycle_length;
$heavenly_stem_day = $heavenly_stems[$day_index % 10];
$earthly_branch_day = $earthly_branches[$day_index % 12];

$lunar_date_str = $heavenly_stem_year . $earthly_branch_year . "年 (" . $zodiac . "年) ";
$lunar_date_str .= ($is_leap ? "闰" : "") . $lunar_months[$lunar_month - 1];

if ($lunar_day == 1) {
    $lunar_date_str .= "初一";
} elseif ($lunar_day <= 10) {
    $lunar_date_str .= "初" . $capital_numbers[$lunar_day];
} elseif ($lunar_day == 11) {
    $lunar_date_str .= "十一";
} elseif ($lunar_day <= 19) {
    $lunar_date_str .= "十" . $capital_numbers[$lunar_day - 10];
} elseif ($lunar_day == 20) {
    $lunar_date_str .= "二十";
} elseif ($lunar_day <= 29) {
    $lunar_date_str .= "廿" . $capital_numbers[$lunar_day - 20];
} elseif ($lunar_day == 30) {
    $lunar_date_str .= "三十";
}
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
    <div>当前日期: <?php echo date('Y年m月d日'); ?></div>
    <div>农历日期: <?php echo $lunar_date_str; ?> <?php echo $chinese_weekdays[date('w')]; ?></div>

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

$proxyFiles = array_diff(scandir($uploadDir), array('.', '..'));
$configFiles = array_diff(scandir($configDir), array('.', '..'));

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

    <h2 style="color: #00FF7F;">自定义目录文件查看管理</h2>
    <form action="" method="get">
        <input type="text" name="customDir" placeholder="自定义目录" required>
        <input type="submit" value="查看文件">
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
        <h2 style="color: #00FF7F;">编辑文件: <?php echo $editingFileName; ?></h2> 
        <form action="" method="post">
            <textarea name="saveContent" rows="15" cols="150"><?php echo $fileContent; ?></textarea><br>
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
        <a href="/nekoclash" class="button">返回主菜单</a>
    </div>
</body>
</html>



<?php
$subscriptionPath = '/etc/neko/proxy_provider/';
$subscriptionFile = $subscriptionPath . 'subscriptions.json';
$clashFile = $subscriptionPath . 'clash_config.yaml';

$message = "";
$decodedContent = ""; 
$subscriptions = [];

if (!file_exists($subscriptionPath)) {
    mkdir($subscriptionPath, 0755, true);
}

if (!file_exists($subscriptionFile)) {
    file_put_contents($subscriptionFile, json_encode([]));
}

$subscriptions = json_decode(file_get_contents($subscriptionFile), true);
if (!$subscriptions) {
    for ($i = 0; $i < 5; $i++) {
        $subscriptions[$i] = [
            'url' => '',
            'file_name' => "subscription_{$i}.yaml",
        ];
    }
}

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
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clash订阅程序</title>
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
    </style>
</head>
<body>
    <h1 style="color: #00FF7F;">Clash订阅程序</h1>
    <p class="help-text">
        请在下方输入框中填写您的订阅链接，删除上方subscriptions.json文件可以清空订阅信息。<br>只支持clash订阅，要支持Meta订阅可以修改配置文件找到机场订阅替换为你的机场通用链接。<button id="convertButton" style="background-color: #00BFFF; color: white;">访问订阅转换网站</button>
    <br>
        在“Base64 转换节点信息”部分，您可以将 Base64 内容粘贴到文本框中，并点击“生成节点信息”按钮来转换内容。      
    </p>
<script>
    document.getElementById('convertButton').onclick = function() {
        window.open('https://suburl.v1.mk', '_blank');
    }
    </script>
    <?php if ($message): ?>
        <p><?php echo nl2br(htmlspecialchars($message)); ?></p>
    <?php endif; ?>

    <?php if (!empty($decodedContent)): ?>
        <h2>解码后的内容</h2>
        <textarea name="decoded_content" id="decoded_content" class="copyable" readonly><?php echo htmlspecialchars($decodedContent); ?></textarea>
        <script>
            document.querySelector('.copyable').style.display = 'block';
        </script>
    <?php endif; ?>

    <?php for ($i = 0; $i < 5; $i++): ?>
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
</body>
</html>

