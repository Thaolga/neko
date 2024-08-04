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
    $lunar_day = 1;    
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
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center; 
            justify-content: center; 
            height: 100vh; 
            margin: 0;
        }
        #current-time {
            margin-bottom: 20px; 
        }
    </style>
</head>
<body>
        <h1>简易文件管理器</h1>
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
        }
        setInterval(updateTime, 1000);
        updateTime();
    </script>
</body>
</html>

<?php
$uploadDir = '/etc/neko/proxy_provider/';
$configDir = '/etc/neko/config/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if (!is_dir($configDir)) {
    mkdir($configDir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fileInput'])) {
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['configFileInput'])) {
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteFile'])) {
    $fileToDelete = $uploadDir . basename($_POST['deleteFile']);
    if (file_exists($fileToDelete) && unlink($fileToDelete)) {
        echo '文件删除成功：' . htmlspecialchars(basename($_POST['deleteFile']));
    } else {
        echo '文件删除失败！';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteConfigFile'])) {
    $fileToDelete = $configDir . basename($_POST['deleteConfigFile']);
    if (file_exists($fileToDelete) && unlink($fileToDelete)) {
        echo '配置文件删除成功：' . htmlspecialchars(basename($_POST['deleteConfigFile']));
    } else {
        echo '配置文件删除失败！';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['customPath'])) {
    $customPath = trim($_POST['customPath']);
    if ($customPath !== '') {
        $uploadDir = $customPath;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['oldFileName'], $_POST['newFileName'])) {
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editFile']) && isset($_POST['fileType']) && $_POST['fileType'] === 'proxy') {
    $fileToEdit = $uploadDir . basename($_POST['editFile']);
    $fileContent = '';

    if (file_exists($fileToEdit)) {
        $fileContent = htmlspecialchars(file_get_contents($fileToEdit));
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editFile']) && isset($_POST['fileType']) && $_POST['fileType'] === 'config') {
    $fileToEdit = $configDir . basename($_POST['editFile']);
    $fileContent = '';

    if (file_exists($fileToEdit)) {
        $fileContent = htmlspecialchars(file_get_contents($fileToEdit));
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['saveContent'], $_POST['fileName'], $_POST['fileType'])) {
    if ($_POST['fileType'] === 'proxy') {
        $fileToSave = $uploadDir . basename($_POST['fileName']);
    } else {
        $fileToSave = $configDir . basename($_POST['fileName']);
    }
    $contentToSave = $_POST['saveContent'];
    file_put_contents($fileToSave, $contentToSave);
    echo '文件内容已更新：' . htmlspecialchars(basename($fileToSave));
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
    <title>文件上传和下载</title>
    <style>
        body {
            background-image: url('/nekoclash/assets/img/1.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            color: white;
        }
        .delete-button {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .delete-button:hover {
            background-color: darkred;
        }
        .rename-button {
            background-color: lightgreen;
            color: black;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .rename-button:hover {
            background-color: darkgreen;
        }
        .edit-button {
            background-color: lightpink;
            color: black;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .edit-button:hover {
            background-color: darkred;
        }
        .button-group {
            display: flex;
            gap: 10px;
            align-items: center;
        }
    </style>
</head>
<body>
    <h2 style="color: pink;">可下载的代理文件</h2>
    <form action="" method="post">
        <label for="customPath">自定义路径：</label>
        <input type="text" name="customPath" id="customPath">
        <input type="submit" value="设置自定义路径">
    </form>

    <h2 style="color: pink;">上传代理文件</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="fileInput" required>
        <input type="submit" value="上传代理文件">
    </form>
    <h2 style="color: pink;">上传配置文件</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="configFileInput" required>
        <input type="submit" value="上传配置文件">
    </form>

    <h2 style="color: pink;">代理文件管理</h2>
    <ul>
        <?php foreach ($proxyFiles as $file): ?>
            <li>
                <a href="<?php echo $uploadDir . urlencode($file); ?>" download><?php echo htmlspecialchars($file); ?></a> 
                (大小: <?php echo formatSize(filesize($uploadDir . $file)); ?>)
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

    <h2 style="color: pink;">配置文件管理</h2>
    <ul>
        <?php foreach ($configFiles as $file): ?>
            <li>
                <a href="<?php echo $configDir . urlencode($file); ?>" download><?php echo htmlspecialchars($file); ?></a> 
                (大小: <?php echo formatSize(filesize($configDir . $file)); ?>)
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

    <?php if (isset($fileContent)): ?>
        <h2>编辑文件内容</h2>
        <form action="" method="post">
            <textarea name="saveContent" rows="15" cols="150"><?php echo $fileContent; ?></textarea><br>
            <input type="hidden" name="fileName" value="<?php echo htmlspecialchars($_POST['editFile']); ?>">
            <input type="hidden" name="fileType" value="<?php echo htmlspecialchars($_POST['fileType']); ?>">
            <input type="submit" value="保存内容">
        </form>
    <?php endif; ?>

    <br>
    <a href="javascript:history.back()" style="text-decoration: none; padding: 10px; background-color: lightblue; color: black; border: 1px solid #007bff; border-radius: 5px;">返回上一级菜单</a>
</body>
</html>
