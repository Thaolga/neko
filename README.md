<h1 align="center">
  <img src="https://raw.githubusercontent.com/Thaolga/neko/main/img/neko.png" alt="neko" width="500">
</h1>

<div align="center">
 <a target="_blank" href="https://github.com/Thaolga/neko/releases"><img src="https://img.shields.io/github/downloads/nosignals/neko/total?label=Total%20Download&labelColor=blue&style=for-the-badge"></a>
 <a target="_blank" href="https://dbai.team/discord"><img src="https://img.shields.io/discord/1127928183824597032?style=for-the-badge&logo=discord&label=%20"></a>
</div>


<p align="center">
  XRAY/V2ray, Shadowsocks, ShadowsocksR, etc.</br>
  Mihomo based Proxy
</p>

Supported Devices
---
- OpenWrt - [luci-app-neko](https://github.com/Thaolga/neko/tree/luci-app-neko)
- Linux Generic - [linux-generic](https://github.com/Thaolga/neko/tree/linux-generic)
- Magisk Module - [neko-for-magisk](https://github.com/Thaolga/neko/tree/neko-for-magisk) (soon)

Features
---
- your Own Custom Theme based Bootstrap ` nekoclash/assets/theme `
- Configs, Proxy, and Rules can edit on webui
- xray/v2ray config converter

About
---
nosignal is gone

Credit
---
- nosignals - [原作者地址](https://github.com/nosignals/neko)
  
# openwrt一键安装脚本
---

```bash
wget -O /root/nekoclash.sh https://raw.githubusercontent.com/Thaolga/neko/main/nekoclash.sh && chmod 0755 /root/nekoclash.sh && /root/nekoclash.sh

```

# openwrt编译
---
## 克隆neko源码 :
---

```bash
git clone https://github.com/Thaolga/neko  package/neko
cd package/neko
```
  
## 切换到指定的分支 :
---

```bash
git checkout luci-app-neko
```
  


Screenshoot
---
<details><summary>Home</summary>
 <p>
  <img src="https://raw.githubusercontent.com/Thaolga/neko/main/img/home.png" alt="home">
 </p>
</details>

<details><summary>Dasboard</summary>
 <p>
  <img src="https://raw.githubusercontent.com/Thaolga/neko/main/img/dashboard.png" alt="dash">
 </p>
</details>

<details><summary>Config - Home</summary>
  <img src="https://raw.githubusercontent.com/Thaolga/neko/main/img/config.png" alt="cfg">
</details>
<details><summary>Config - Proxy</summary>
  <img src="https://raw.githubusercontent.com/Thaolga/neko/main/img/config-proxy.png" alt="proxy">
</details>
<details><summary>Config - Rules</summary>
  <img src="https://raw.githubusercontent.com/Thaolga/neko/main/img/config-rules.png" alt="rules">
</details>
<details><summary>Config - Converter</summary>
  <img src="https://raw.githubusercontent.com/Thaolga/neko/main/img/config-converter.png" alt="conv">
</details>

<details><summary>Settings</summary>
  <img src="https://raw.githubusercontent.com/Thaolga/neko/main/img/setting.png" alt="setting">
</details>

