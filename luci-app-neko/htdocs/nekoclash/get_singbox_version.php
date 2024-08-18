<?php
$version_file = '/etc/neko/core/version.txt'; 

if (file_exists($version_file)) {
    $version = trim(file_get_contents($version_file));
    echo htmlspecialchars($version ?: '-');
} else {
    echo '版本文件不存在';
}
?>
