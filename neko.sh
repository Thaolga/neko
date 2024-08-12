#!/bin/sh

log_message() {
    local message=$1
    local log_file='/var/log/mihomo_update.log'
    local timestamp=$(date '+%Y-%m-%d %H:%M:%S')
    echo "[$timestamp] $message" >> "$log_file"
}

repo_owner="Thaolga"
repo_name="neko"
package_name="luci-app-neko"
releases_url="https://github.com/$repo_owner/$repo_name/releases"

echo "更新 opkg 软件包列表..."
opkg update

response=$(wget -qO- "$releases_url")

if [ -z "$response" ]; then
    log_message "无法访问 GitHub releases 页面。"
    echo "无法访问 GitHub releases 页面。"
    exit 1
fi

new_version=$(echo "$response" | awk -F'/tag/' '/\/releases\/tag\// {print $2}' | awk -F'"' '{print $1}' | head -n 1)

if [ -z "$new_version" ]; then
    log_message "未找到最新版本。"
    echo "未找到最新版本。"
    exit 1
fi

download_url="https://github.com/$repo_owner/$repo_name/releases/download/$new_version/${package_name}_${new_version}_all.ipk"

echo "下载 URL: $download_url"
log_message "开始下载 IPK 包..."

local_file="/tmp/$package_name.ipk"

curl -L -f -o "$local_file" "$download_url"

if [ $? -eq 0 ]; then
    log_message "下载完成。"
else
    log_message "下载失败。"
    echo "下载失败。"
    exit 1
fi

if [ ! -s "$local_file" ]; then
    log_message "下载的文件为空或不存在。"
    echo "下载的文件为空或不存在。"
    exit 1
fi

opkg install --force-reinstall "$local_file"
if [ $? -eq 0 ]; then
    log_message "IPK 包安装完成。"
else
    log_message "IPK 包安装失败。"
    echo "IPK 包安装失败。"
    exit 1
fi

rm -f "$local_file"
log_message "已删除临时文件: $local_file"

latest_version='v1.18.7'
current_version=''
install_path='/etc/neko/core/mihomo'
temp_file='/tmp/mihomo.gz'
temp_extract_path='/tmp/mihomo_temp'

if [ -e "$install_path" ]; then
    current_version=$($install_path --version 2>/dev/null)
    log_message "当前版本: $current_version"
else
    log_message "当前版本文件不存在，将视为未安装。"
fi

current_arch=$(uname -m)

case "$current_arch" in
    aarch64)
        download_url='https://github.com/MetaCubeX/mihomo/releases/download/v1.18.7/mihomo-linux-arm64-v1.18.7.gz'
        ;;
    armv7l)
        download_url='https://github.com/MetaCubeX/mihomo/releases/download/v1.18.7/mihomo-linux-armv7l-v1.18.7.gz'
        ;;
    x86_64)
        download_url='https://github.com/MetaCubeX/mihomo/releases/download/v1.18.7/mihomo-linux-amd64-v1.18.7.gz'
        ;;
    *)
        log_message "未找到适合架构的下载链接: $current_arch"
        echo "未找到适合架构的下载链接: $current_arch"
        exit 1
        ;;
esac

log_message "最新版本: $latest_version"
log_message "当前架构: $current_arch"
log_message "下载链接: $download_url"

if [ "$current_version" = "$latest_version" ]; then
    log_message "当前版本已是最新版本，无需更新。"
    echo "当前版本已是最新版本。"
    exit 0
fi

log_message "开始下载核心更新..."
wget -O "$temp_file" "$download_url"
return_var=$?

log_message "wget 返回值: $return_var"

if [ $return_var -eq 0 ]; then
    mkdir -p "$temp_extract_path"
    log_message "解压命令: gunzip -f -c '$temp_file' > '$temp_extract_path/mihomo'"
    gunzip -f -c "$temp_file" > "$temp_extract_path/mihomo"
    return_var=$?

    log_message "解压返回值: $return_var"

    if [ $return_var -eq 0 ]; then
        mv "$temp_extract_path/mihomo" "$install_path"
        chmod 0755 "$install_path"
        return_var=$?
        log_message "设置权限命令: chmod 0755 '$install_path'"
        log_message "设置权限返回值: $return_var"

        if [ $return_var -eq 0 ]; then
            log_message "核心更新完成！当前版本: $latest_version"
            echo "更新完成！当前版本: $latest_version"
        else
            log_message "设置权限失败！"
            echo "设置权限失败！"
        fi
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
if [ -d "$temp_extract_path" ]; then
    rm -r "$temp_extract_path"
    log_message "清理临时解压目录: $temp_extract_path"
fi
