<?php
if (isset($_GET['file'])) {
    $file = $_GET['file'];
    $filePath = '/etc/neko/proxy_provider/' . basename($file);

    if (file_exists($filePath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Content-Length: ' . filesize($filePath));
        flush();
        readfile($filePath);
        exit;
    } else {
        echo '文件不存在。';
    }
} else {
    echo '无效的文件请求。';
}
?>
