#!/bin/sh

repo_owner="Thaolga"
repo_name="neko"
package_name="luci-app-neko"

releases_url="https://github.com/$repo_owner/$repo_name/releases"

echo "更新 opkg 软件包列表..."
opkg update

response=$(wget -qO- "$releases_url")

if [ -z "$response" ]; then
    echo "无法访问 GitHub releases 页面。请检查 URL 或网络连接。"
    exit 1
fi

echo "$response" | head -n 20

new_version=$(echo "$response" | sed -n 's/.*\/releases\/tag\/\([^"]*\).*/\1/p' | head -n 1)

if [ -z "$new_version" ]; then
    echo "未找到最新版本。"
    exit 1
fi

echo "最新版本: $new_version"

download_url="https://github.com/$repo_owner/$repo_name/releases/download/$new_version/${package_name}_${new_version}_all.ipk"

echo "下载 URL: $download_url"

append_log() {
    echo "$1"
}

append_log "开始下载更新..."

local_file="/tmp/$package_name.ipk"

curl -L -o "$local_file" "$download_url"

if [ $? -eq 0 ]; then
    append_log "下载完成。"
else
    append_log "下载失败。"
    exit 1
fi

append_log "下载文件的信息:"
ls -l "$local_file"
file "$local_file"

if [ ! -s "$local_file" ]; then
    append_log "下载的文件为空或不存在。"
    exit 1
fi

output=$(opkg install --force-reinstall "$local_file")
echo "$output"

append_log "安装完成。"

rm -f "$local_file"
