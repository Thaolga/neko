
<?php
$uploadDir = '/etc/neko/proxy_provider/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteFile'])) {
    $fileToDelete = $uploadDir . basename($_POST['deleteFile']);
    if (file_exists($fileToDelete) && unlink($fileToDelete)) {
        echo '文件删除成功：' . htmlspecialchars(basename($_POST['deleteFile']));
    } else {
        echo '文件删除失败！';
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

$files = array_diff(scandir($uploadDir), array('.', '..'));
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>文件上传和下载</title>
     <style>

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
    </style>
</head>
<body>

    <h2 style="color: pink;">可下载的配置文件</h2>
    <form action="" method="post">
        <label for="customPath">自定义路径：</label>
        <input type="text" name="customPath" id="customPath">
        <input type="submit" value="设置自定义路径">
    </form>
    <ul>
        <?php foreach ($files as $file): ?>
            <li>
                <a href="<?php echo $uploadDir . '/' . urlencode($file); ?>" download><?php echo htmlspecialchars($file); ?></a>
                <form action="" method="post" style="display:inline;">
                    <input type="hidden" name="deleteFile" value="<?php echo htmlspecialchars($file); ?>">
                    <input type="submit" class="delete-button" value="删除" onclick="return confirm('确定要删除这个文件吗？');">
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <br>
    <a href="javascript:history.back()" style="text-decoration: none; padding: 10px; background-color: lightblue; color: black; border: 1px solid #007bff; border-radius: 5px;">返回上一级菜单</a>
</body>
</html>
