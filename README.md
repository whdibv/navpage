<p align="center">
  <img src="https://img.wldwz.icu/imgs/2026/08/cd6a9f52e4309408.png" width="72" height="72" alt="logo">
  <h1 align="center">wldwz.icu 站点导航</h1>
  <p align="center">一个 PHP + JSON 的站点导航页，自带管理后台、SEO/GEO 优化与 llms.txt 自动同步。</p>
  <p align="center"><a href="https://wldwz.icu">🌐 在线演示</a></p>
</p>

## 🧭 站点列表

- [鱼鱼 Blog](https://blog.wldwz.icu) — 个人博客 · 生活记录 · 小玩意
- [BEISAX 佰赛思](https://bsx.wldwz.icu) — 即热饮水 · 健康生活
- [鱼鱼图床](https://img.wldwz.icu) — 图片托管 · 外链 · API
- [GitHub 加速](https://ghproxy.wldwz.icu) — GitHub 文件下载加速代理
- [OpenList 网盘](https://pan.wldwz.icu) — 文件列表 · 分享
- [贪吃蛇](https://snake.wldwz.icu) — 经典小游戏
- [烟花](https://fireworks.wldwz.icu) — 烟花特效
- [GPU 测试](https://gputest.wldwz.icu) — 体积着色器性能测试
- [短链接](https://url.wldwz.icu) — 长链接变短 · 一键跳转

## ✨ 特性

| 功能 | 说明 |
|---|---|
| ⚡ **零依赖** | PHP 单文件渲染 + JSON 存储，虚拟主机即传即用 |
| 🎛 **管理后台** | `/admin.php` 登录后增删改站点、排序、改标题/Logo，不用碰代码 |
| 🎨 **图标库** | 24 个内置线性 SVG 图标，也可直接填 emoji |
| 🔍 **SEO** | 动态 meta / Open Graph / Twitter Card / canonical |
| 🤖 **GEO** | JSON-LD（WebSite + ItemList）结构化数据，AI 引擎可直接解析 |
| 🗺 **sitemap** | `sitemap.php` 动态生成，加站自动更新 |
| 🤝 **llms.txt** | AI 智能体无障碍文件，后台保存站点时**自动同步重写** |
| 📱 **自适应** | 桌面/移动端网格布局 |

## 🚀 快速开始

### 1. 上传文件

把以下文件上传到站点根目录（如 `public_html/`）：

```
index.php              前台渲染（唯一必传）
admin.php              管理后台
nav.json               站点数据
sitemap.php            动态 sitemap（可选）
robots.txt             robots 规则（可选）
```

### 2. 配置管理密码

```bash
cp admin_config.example.php admin_config.php
# 编辑 admin_config.php，修改 password
```

```php
<?php
return array(
    'password' => '改成你的强密码',
);
```

### 3. 完成 🎉

- 导航页：`https://你的域名/`
- 管理后台：`https://你的域名/admin.php`

> 💡 想用独立二级域名（如 `s.example.com`），在域名管理里指向该目录即可。

## 📁 目录结构

```
├── index.php              前台渲染（读 nav.json，含 SEO/GEO/无障碍优化）
├── admin.php              管理后台（登录 / 增删改 / 排序 / 标题 / Logo）
├── admin_config.php       管理密码（不入库，自行创建）
├── admin_config.example.php  配置模板
├── nav.json               站点数据（title / subtitle / logo / sites[]）
├── sitemap.php            动态 sitemap（读 nav.json 生成）
├── robots.txt             robots 规则
└── llms.txt               AI 智能体文件（admin.php 保存时自动重写）
```

## 🔧 数据格式（nav.json）

```json
{
  "title": "wldwz.icu 站点导航",
  "subtitle": "鱼鱼的所有站点，从这里出发",
  "logo": "https://example.com/logo.png",
  "sites": [
    { "name": "站点名称", "desc": "一句话描述", "url": "https://example.com", "icon": "blog" }
  ]
}
```

- `logo` 可留空（显示默认"w"字）
- `icon` 用内置图标名（blog/shop/img/gh/pan/snake/fw/gpu/home/doc/link/star/tool/mail/game/code/play/music/video/book/cart/zap/heart/user），或直接填 emoji

## ❓ FAQ

**Q：改站点一定要用后台吗？**
A：不强制，直接改 `nav.json` 也行。但用后台改会自动同步更新 `llms.txt`，推荐走后台。

**Q：llms.txt 会自动更新吗？**
A：会。每次通过后台保存站点（增/删/排序/改标题），`llms.txt` 自动重写，无需手动维护。

**Q：支持 HTTPS 吗？**
A：支持，代码不写死协议，自动跟随访问协议。

## 📄 License

[MIT](LICENSE) © 鱼鱼

---

*由 [🐟 鱼鱼](https://blog.wldwz.icu) 维护 · 用 ❤️ 和 PHP 写成*
