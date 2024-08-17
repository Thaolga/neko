#!/bin/sh

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
NC='\033[0m'

log_message() {
    local message=$1
    local log_file='/var/log/neko_update.log'
    local timestamp=$(date '+%Y-%m-%d %H:%M:%S')
    echo "[$timestamp] $message" >> "$log_file"
}

get_router_ip() {
    ip addr show br-lan | grep "inet\b" | awk '{print $2}' | cut -d/ -f1
}

get_version_info() {
    local component=$1
    local version_file
    local latest_version

    case $component in
        "neko")
            version_file='/etc/neko/version_neko.txt'
            repo_owner="Thaolga"
            repo_name="neko"
            releases_url="https://api.github.com/repos/$repo_owner/$repo_name/releases/latest"
            ;;
        "core")
            version_file='/etc/neko/version_mihomo.txt'
            releases_url="https://api.github.com/repos/MetaCubeX/mihomo/releases/latest"
            ;;
        "ui")
            version_file='/etc/neko/ui/metacubexd/version.txt'
            releases_url="https://api.github.com/repos/MetaCubeX/metacubexd/releases/latest"
            ;;
        *)
            echo -e "${RED}未知组件: $component${NC}"
            return 1
            ;;
    esac

    if [ -e "$version_file" ]; then
        current_version=$(cat "$version_file")
    else
        current_version="未安装"
    fi

    echo -e "${CYAN}当前版本: $current_version${NC}"

    latest_version=$(curl -s "$releases_url" | grep '"tag_name":' | sed -E 's/.*"([^"]+)".*/\1/')
    
    if [ -z "$latest_version" ]; then
        echo -e "${RED}获取最新版本失败。请检查网络连接或 GitHub API 状态。${NC}"
        latest_version="获取失败"
    fi

    echo -e "${CYAN}最新版本: $latest_version${NC}"
}

install_ipk() {
    repo_owner="Thaolga"
    repo_name="neko"
    package_name="luci-app-neko"
    releases_url="https://api.github.com/repos/$repo_owner/$repo_name/releases/latest"

    echo -e "${CYAN}更新 opkg 软件包列表...${NC}"
    opkg update

    response=$(wget -qO- "$releases_url")

    if [ -z "$response" ]; then
        log_message "无法访问 GitHub releases 页面。"
        echo -e "${RED}无法访问 GitHub releases 页面。${NC}"
        return 1
    fi

    echo "$response" > /tmp/releases_response.json

    new_version=$(echo "$response" | awk -F'/tag/' '/\/releases\/tag\// {print $2}' | awk -F'"' '{print $1}' | head -n 1)

    if [ -z "$new_version" ]; then
        log_message "未找到最新版本。"
        echo -e "${RED}未找到最新版本。${NC}"
        return 1
    fi

    download_url="https://github.com/$repo_owner/$repo_name/releases/download/$new_version/${package_name}_${new_version}_all.ipk"

    echo -e "${CYAN}下载 URL: $download_url${NC}"
    log_message "开始下载 IPK 包..."

    local_file="/tmp/$package_name.ipk"

    curl -L -f -o "$local_file" "$download_url"

    if [ $? -eq 0 ]; then
        log_message "下载完成。"
        echo -e "${GREEN}下载完成。${NC}"
    else
        log_message "下载失败。"
        echo -e "${RED}下载失败。${NC}"
        return 1
    fi

    if [ ! -s "$local_file" ]; then
        log_message "下载的文件为空或不存在。"
        echo -e "${RED}下载的文件为空或不存在。${NC}"
        return 1
    fi

    opkg install --force-reinstall "$local_file"
    if [ $? -eq 0 ]; then
        log_message "NeKoClash安装完成。"
        echo -e "${GREEN}NeKoClash安装完成。${NC}"

        echo "$new_version" > /etc/neko/version_neko.txt

        router_ip=$(get_router_ip)
        echo -e "${GREEN}NeKo 面板已安装，可以通过以下地址访问:${NC}"
        echo -e "${GREEN}http://$router_ip/nekoclash${NC}"

        get_version_info "neko"
    else
        log_message "NeKoClash安装失败。"
        echo -e "${RED}NeKoClash安装失败。${NC}"
        return 1
    fi

    rm -f "$local_file"
    log_message "已删除临时文件: $local_file"
}

install_core() {
    log_message "获取最新核心版本号..."
    latest_version=$(curl -s https://api.github.com/repos/MetaCubeX/mihomo/releases/latest | grep '"tag_name":' | sed -E 's/.*"([^"]+)".*/\1/')

    if [ -z "$latest_version" ]; then
        log_message "无法获取最新核心版本号，更新终止。"
        echo -e "${RED}无法获取最新核心版本号，请检查网络连接。${NC}"
        return 1
    fi

    current_version=''
    install_path='/etc/neko/core/mihomo'
    temp_file='/tmp/mihomo.gz'
    temp_extract_path='/tmp/mihomo_temp'

    if [ -e "$install_path/version.txt" ]; then
        current_version=$(cat "$install_path/version.txt" 2>/dev/null)
        log_message "当前版本: $current_version"
    else
        log_message "当前版本文件不存在，将视为未安装。"
    fi

    case "$(uname -m)" in
        aarch64)
            download_url="https://github.com/MetaCubeX/mihomo/releases/download/$latest_version/mihomo-linux-arm64-$latest_version.gz"
            ;;
        armv7l)
            download_url="https://github.com/MetaCubeX/mihomo/releases/download/$latest_version/mihomo-linux-armv7l-$latest_version.gz"
            ;;
        x86_64)
            download_url="https://github.com/MetaCubeX/mihomo/releases/download/$latest_version/mihomo-linux-amd64-$latest_version.gz"
            ;;
        *)
            log_message "未找到适合架构的下载链接: $(uname -m)"
            echo -e "${RED}未找到适合架构的下载链接: $(uname -m)${NC}"
            return 1
            ;;
    esac

    echo -e "${CYAN}最新版本: $latest_version${NC}"
    echo -e "${CYAN}下载链接: $download_url${NC}"

    if [ "$current_version" = "$latest_version" ]; then
        log_message "当前版本已是最新版本，无需更新。"
        echo -e "${GREEN}当前版本已是最新版本。${NC}"
        return 0
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
                echo "$latest_version" > "/etc/neko/version_mihomo.txt"
                log_message "核心更新完成！当前版本: $latest_version"
                echo -e "${GREEN}核心更新完成！当前版本: $latest_version${NC}"
            else
                log_message "设置权限失败！"
                echo -e "${RED}设置权限失败！${NC}"
                return 1
            fi
        else
            log_message "解压失败，返回值: $return_var"
            echo -e "${RED}解压失败！${NC}"
            return 1
        fi
    else
        log_message "下载失败，返回值: $return_var"
        echo -e "${RED}下载失败！${NC}"
        return 1
    fi

    if [ -e "$temp_file" ]; then
        rm "$temp_file"
        log_message "清理临时文件: $temp_file"
    fi

    if [ -e "$temp_extract_path" ]; then
        rm -rf "$temp_extract_path"
        log_message "清理临时文件夹: $temp_extract_path"
    fi

    log_message "操作完成，返回主菜单..."
}

install_ui() {
    log_message "获取最新 UI 版本号..."
    latest_version=$(curl -s https://api.github.com/repos/MetaCubeX/metacubexd/releases/latest | grep '"tag_name":' | sed -E 's/.*"([^"]+)".*/\1/')

    if [ -z "$latest_version" ]; then
        log_message "无法获取最新 UI 版本号，更新终止。"
        echo -e "${RED}无法获取最新 UI 版本号，请检查网络连接。${NC}"
        return 1
    fi

    current_version=''
    install_path='/etc/neko/ui/metacubexd'
    temp_file='/tmp/metacubexd.tgz'
    temp_extract_path='/tmp/metacubexd_temp'

    if [ -e "$install_path/version.txt" ]; then
        current_version=$(cat "$install_path/version.txt" 2>/dev/null)
        log_message "当前版本: $current_version"
    else
        log_message "当前版本文件不存在，将视为未安装。"
    fi

    download_url="https://github.com/MetaCubeX/metacubexd/releases/download/$latest_version/compressed-dist.tgz"

    echo -e "${CYAN}最新版本: $latest_version${NC}"
    echo -e "${CYAN}下载链接: $download_url${NC}"

    if [ "$current_version" = "$latest_version" ]; then
        log_message "当前版本已是最新版本，无需更新。"
        echo -e "${GREEN}当前版本已是最新版本。${NC}"
        return 0
    fi

    log_message "开始下载 UI 更新..."
    wget -O "$temp_file" "$download_url"
    return_var=$?

    log_message "wget 返回值: $return_var"

    if [ $return_var -eq 0 ]; then
        mkdir -p "$temp_extract_path"
        log_message "解压命令: tar -xzf '$temp_file' -C '$temp_extract_path'"
        tar -xzf "$temp_file" -C "$temp_extract_path"
        return_var=$?

        log_message "解压返回值: $return_var"

        if [ $return_var -eq 0 ]; then
            mkdir -p "$install_path"
            cp -r "$temp_extract_path/"* "$install_path/"
            return_var=$?
            log_message "拷贝文件返回值: $return_var"

            if [ $return_var -eq 0 ]; then
                echo "$latest_version" > "$install_path/version.txt"
                log_message "UI 更新完成！当前版本: $latest_version"
                echo -e "${GREEN}UI 更新完成！当前版本: $latest_version${NC}"
            else
                log_message "拷贝文件失败！"
                echo -e "${RED}拷贝文件失败！${NC}"
                return 1
            fi
        else
            log_message "解压失败，返回值: $return_var"
            echo -e "${RED}解压失败！${NC}"
            return 1
        fi
    else
        log_message "下载失败，返回值: $return_var"
        echo -e "${RED}下载失败！${NC}"
        return 1
    fi

    if [ -e "$temp_file" ]; then
        rm "$temp_file"
        log_message "清理临时文件: $temp_file"
    fi

    if [ -e "$temp_extract_path" ]; then
        rm -rf "$temp_extract_path"
        log_message "清理临时文件夹: $temp_extract_path"
    fi

    log_message "操作完成，返回主菜单..."
}

install_php() {
    GREEN="\033[32m"
    RED="\033[31m"
    RESET="\033[0m"

    ARCH=$(uname -m)

    if [ "$ARCH" == "aarch64" ]; then
        PHP_CGI_URL="https://github.com/Thaolga/neko/releases/download/core_neko/php8-cgi_8.2.2-1_aarch64_generic.ipk"
        PHP_URL="https://github.com/Thaolga/neko/releases/download/core_neko/php8_8.2.2-1_aarch64_generic.ipk"
    elif [ "$ARCH" == "x86_64" ]; then
        PHP_CGI_URL="https://github.com/Thaolga/neko/releases/download/core_neko/php8-cgi_8.2.2-1_x86_64.ipk"
        PHP_URL="https://github.com/Thaolga/neko/releases/download/core_neko/php8_8.2.2-1_x86_64.ipk"
    else
        echo -e "${RED}不支持的架构: $ARCH${RESET}"
        exit 1
    fi

    echo -e "${GREEN}正在下载并安装 PHP CGI...${RESET}"
    wget "$PHP_CGI_URL" -O /tmp/php8-cgi.ipk
    if opkg install --force-reinstall --force-overwrite /tmp/php8-cgi.ipk; then
        echo -e "${GREEN}PHP CGI 安装成功。${RESET}"
    else
        echo -e "${RED}PHP CGI 安装失败。${RESET}"
    fi

    echo -e "${GREEN}正在下载并安装 PHP...${RESET}"
    wget "$PHP_URL" -O /tmp/php8.ipk
    if opkg install --force-reinstall --force-overwrite /tmp/php8.ipk; then
        echo -e "${GREEN}PHP 安装成功。${RESET}"
    else
        echo -e "${RED}PHP 安装失败。${RESET}"
    fi

    rm -f /tmp/php8-cgi.ipk /tmp/php8.ipk

    echo -e "${GREEN}安装完成。${RESET}"
    echo -e "${YELLOW}请重启服务器以应用更改。${RESET}"
}

reboot_router() {
    echo -e "${YELLOW}路由器正在重启...${NC}"
    reboot
}

while true; do
    echo -e "${YELLOW}=================================${NC}"
    echo -e "${YELLOW}|   1. 安装 NeKoClash           |${NC}"
    echo -e "${YELLOW}|   2. 安装 Mihomo 核心         |${NC}"
    echo -e "${YELLOW}|   3. 安装 UI 控制面板         |${NC}"
    echo -e "${YELLOW}|   4. 安装 PHP8 和 PHP8-CGI    |${NC}"
    echo -e "${YELLOW}|   5. 重启路由器               |${NC}"  
    echo -e "${YELLOW}|   0. 退出                     |${NC}"
    echo -e "${YELLOW}=================================${NC}"
    read -p "请输入选项: " option

    case $option in
        1)
            install_ipk
            ;;
        2)
            install_core
            ;;
        3)
            install_ui
            ;;
        4)
            install_php
            ;;
        5)  
            reboot_router
            ;;
        0)
            echo -e "${GREEN}退出程序。${NC}"
            exit 0
            ;;
        *)
            echo -e "${RED}无效选项，请重新输入。${NC}"
            ;;
    esac
done
