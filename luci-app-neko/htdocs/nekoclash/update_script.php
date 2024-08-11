<?php
$repo_owner = "Thaolga";
$repo_name = "neko";
$package_name = "luci-app-neko"; 

$releases_url = "https://github.com/$repo_owner/$repo_name/releases";

$response = shell_exec("wget -qO- " . escapeshellarg($releases_url));

if ($response === null) {
    die("无法访问GitHub releases页面。请检查URL或网络连接。");
}

preg_match('/\/' . $repo_owner . '\/' . $repo_name . '\/releases\/tag\/([^"]+)/', $response, $version_matches);
$new_version = trim($version_matches[1] ?? null); 

$download_url = "https://github.com/$repo_owner/$repo_name/releases/download/$new_version/{$package_name}_{$new_version}_all.ipk";

if ($new_version === null) {
    die("未找到最新版本。");
}

echo "<pre>最新版本: $new_version</pre>";

echo "<pre id='logOutput'></pre>";
echo "<script>
        function appendLog(message) {
            document.getElementById('logOutput').innerHTML += message + '\\n';
        }
      </script>";

echo "<script>appendLog('开始下载更新...');</script>"; 
$local_file = "/tmp/$package_name.ipk";
$download_command = "curl -L -o " . escapeshellarg($local_file) . " " . escapeshellarg($download_url);
shell_exec($download_command);
echo "<script>appendLog('下载完成。');</script>"; 

$output = shell_exec("opkg install --force-reinstall " . escapeshellarg($local_file));
echo "<pre>$output</pre>";
echo "<script>appendLog('安装完成。');</script>"; 

unlink($local_file);

?>
