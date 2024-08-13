#!/bin/sh

log_message() {
    local message=$1
    local log_file='/var/log/metacubexd_update.log'
    local timestamp=$(date '+%Y-%m-%d %H:%M:%S')
    echo "[$timestamp] $message" >> "$log_file"
}

install_path='/etc/neko/ui/metacubexd'
temp_file='/tmp/compressed-dist.tgz'

if [ -e "$install_path/version.txt" ]; then
    current_version=$(cat "$install_path/version.txt" 2>/dev/null)
    log_message "当前版本: $current_version"
else
    log_message "未安装，或者版本文件缺失。"
    current_version=''
fi

log_message "获取最新版本号..."
latest_version=$(curl -s https://api.github.com/repos/MetaCubeX/metacubexd/releases/latest | grep '"tag_name":' | sed -E 's/.*"([^"]+)".*/\1/')

if [ -z "$latest_version" ]; then
    log_message "无法获取最新版本号，更新终止。"
    echo "无法获取最新版本号，请检查网络连接。"
    exit 1
fi

log_message "最新版本: $latest_version"
echo "当前版本: $current_version"
echo "最新版本: $latest_version"

if [ "$current_version" = "$latest_version" ]; then
    log_message "当前版本已是最新版本，无需更新。"
    echo "当前版本已是最新版本。"
    exit 0
fi

download_url="https://github.com/MetaCubeX/metacubexd/releases/download/$latest_version/compressed-dist.tgz"

log_message "下载链接: $download_url"
log_message "开始下载更新..."
wget -O "$temp_file" "$download_url"
return_var=$?

log_message "wget 返回值: $return_var"

if [ $return_var -eq 0 ]; then
    mkdir -p "$install_path"
    log_message "解压命令: tar -xzf '$temp_file' -C '$install_path'"
    tar -xzf "$temp_file" -C "$install_path"
    return_var=$?

    log_message "解压返回值: $return_var"

    if [ $return_var -eq 0 ]; then
        echo "$latest_version" > "$install_path/version.txt"
        log_message "更新完成！当前版本: $latest_version"
        echo "更新完成！当前版本: $latest_version"
    else
        log_message "解压失败，返回值: $return_var"
        echo "解压失败！"
    fi
else
    log_message "下载失败，返回值: $return_var"
    echo "下载失败！"
fi

if [ -e "$temp_file" ]; then
    rm "$temp_file"
    log_message "清理临时文件: $temp_file"
fi
