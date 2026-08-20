<?php
/**
 * 导航页管理后台
 * 访问 /admin.php 登录后可增删改导航站点
 */
session_start();
$config = require __DIR__ . '/admin_config.php';
$pass = isset($config['password']) ? $config['password'] : '';
$nav_file = __DIR__ . '/nav.json';
$msg = '';
$error = '';

// 登录
if (isset($_POST['login'])) {
    if (isset($_POST['password']) && $_POST['password'] === $pass) {
        $_SESSION['nav_admin'] = true;
        header('Location: /admin.php');
        exit;
    } else {
        $error = '密码错误';
    }
}
// 登出
if (isset($_GET['logout'])) {
    unset($_SESSION['nav_admin']);
    session_destroy();
    header('Location: /admin.php');
    exit;
}

$is_admin = !empty($_SESSION['nav_admin']);

// 根据 nav 数据生成 llms.txt（AI 智能体无障碍文件，随站点变化自动同步）
function gen_llms($nav) {
    $title = isset($nav['title']) && $nav['title'] !== '' ? $nav['title'] : 'wldwz.icu 站点导航';
    $sites = isset($nav['sites']) && is_array($nav['sites']) ? $nav['sites'] : array();
    $lines = array();
    $lines[] = '# ' . $title;
    $lines[] = '';
    $lines[] = '> wldwz.icu 是鱼鱼（个人开发者）的站点导航页，聚合了旗下 ' . count($sites) . ' 个子站点。本站欢迎 AI 智能体抓取本页及子站内容用于理解站点结构；子站各自的内容抓取遵循其 robots 规则。';
    $lines[] = '';
    $lines[] = '- [wldwz.icu 主站（站点导航页）](https://wldwz.icu)';
    foreach ($sites as $s) {
        $name = isset($s['name']) ? trim($s['name']) : '';
        $desc = isset($s['desc']) ? trim($s['desc']) : '';
        $url = isset($s['url']) ? trim($s['url']) : '';
        if ($name === '' || $url === '') continue;
        $label = $desc !== '' ? $name . '（' . $desc . '）' : $name;
        $lines[] = '- [' . $label . '](' . $url . ')';
    }
    $content = implode("\n", $lines) . "\n";
    @file_put_contents(__DIR__ . '/llms.txt', $content);
}

// 管理操作
$sites = array();
$title = '';
if ($is_admin) {
    $nav = json_decode(@file_get_contents($nav_file), true);
    if (!is_array($nav)) {
        $nav = array('sites' => array());
    }
    $sites = isset($nav['sites']) ? $nav['sites'] : array();
    $title = isset($nav['title']) ? $nav['title'] : '';
    $changed = false;

    // 保存标题/副标题/Logo
    if (isset($_POST['action']) && $_POST['action'] === 'save_title') {
        $nav['title'] = trim(isset($_POST['title']) ? $_POST['title'] : '');
        $nav['subtitle'] = trim(isset($_POST['subtitle']) ? $_POST['subtitle'] : '');
        $nav['logo'] = trim(isset($_POST['logo']) ? $_POST['logo'] : '');
        $changed = true;
        $msg = '标题已保存';
    }
    // 添加站点
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $name = trim(isset($_POST['name']) ? $_POST['name'] : '');
        $desc = trim(isset($_POST['desc']) ? $_POST['desc'] : '');
        $url = trim(isset($_POST['url']) ? $_POST['url'] : '');
        $icon = trim(isset($_POST['icon']) ? $_POST['icon'] : 'link');
        if ($name !== '' && $url !== '') {
            $sites[] = array('name' => $name, 'desc' => $desc, 'url' => $url, 'icon' => $icon);
            $changed = true;
            $msg = '站点已添加';
        } else {
            $msg = '名称和链接不能为空！';
        }
    }
    // 删除站点
    if (isset($_POST['action']) && $_POST['action'] === 'del' && isset($_POST['idx'])) {
        $idx = (int)$_POST['idx'];
        if (isset($sites[$idx])) {
            unset($sites[$idx]);
            $sites = array_values($sites);
            $changed = true;
            $msg = '站点已删除';
        }
    }
    // 上移 / 下移
    if (isset($_POST['action']) && isset($_POST['idx']) && ($_POST['action'] === 'up' || $_POST['action'] === 'down')) {
        $idx = (int)$_POST['idx'];
        $to = $_POST['action'] === 'up' ? $idx - 1 : $idx + 1;
        if (isset($sites[$idx]) && isset($sites[$to])) {
            $tmp = $sites[$idx];
            $sites[$idx] = $sites[$to];
            $sites[$to] = $tmp;
            $changed = true;
            $msg = '排序已更新';
        }
    }
    // 保存（nav.json + llms.txt 同步更新）
    if ($changed) {
        $nav['sites'] = $sites;
        @file_put_contents($nav_file, json_encode($nav, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        gen_llms($nav);
        header('Location: /admin.php?ok=1');
        exit;
    }
    if (isset($_GET['ok'])) {
        $msg = '保存成功';
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>导航页管理</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
  font-family: -apple-system, "PingFang SC", "Microsoft YaHei", "Segoe UI", sans-serif;
  background: #f1f5f9; color: #1e293b; padding: 30px 16px;
}
.wrap { max-width: 760px; margin: 0 auto; }
h1 { font-size: 20px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; }
h1 a { font-size: 13px; color: #0d9488; text-decoration: none; font-weight: 400; }
h1 a:hover { text-decoration: underline; }
.card {
  background: #fff; border-radius: 12px; padding: 20px;
  box-shadow: 0 1px 6px rgba(15,23,42,.06); margin-bottom: 16px;
}
.card h2 { font-size: 15px; margin-bottom: 14px; color: #334155; }
label { display: block; font-size: 13px; color: #64748b; margin: 10px 0 4px; }
input[type=text], input[type=password], select {
  width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 8px;
  font-size: 14px; box-sizing: border-box;
}
input:focus, select:focus { outline: 2px solid #99f6e4; border-color: #0d9488; }
.btn {
  display: inline-block; padding: 8px 18px; border: 0; border-radius: 8px;
  font-size: 14px; cursor: pointer; background: #0d9488; color: #fff;
}
.btn:hover { background: #0f766e; }
.btn-red { background: #f43f5e; margin-left: 6px; }
.btn-red:hover { background: #e11d48; }
.btn-gray { background: #e2e8f0; color: #475569; }
.btn-gray:hover { background: #cbd5e1; }
table { width: 100%; border-collapse: collapse; font-size: 13px; }
th, td { text-align: left; padding: 10px 8px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
th { color: #94a3b8; font-weight: 500; font-size: 12px; }
td .icon-badge {
  display: inline-flex; align-items: center; justify-content: center;
  width: 30px; height: 30px; border-radius: 8px; font-size: 14px; margin-right: 8px;
  background: #f1f5f9; vertical-align: middle;
}
.empty { text-align: center; color: #94a3b8; padding: 24px 0; font-size: 13px; }
.msg { background: #f0fdfa; color: #0f766e; padding: 10px 14px; border-radius: 8px; font-size: 13px; margin-bottom: 14px; }
.err { background: #fff1f2; color: #e11d48; padding: 10px 14px; border-radius: 8px; font-size: 13px; margin-bottom: 14px; }
.row { display: flex; gap: 10px; }
.row input { flex: 1; }
.login-box { max-width: 340px; margin: 60px auto; }
.login-box h1 { justify-content: center; }
.small { font-size: 12px; color: #94a3b8; }
</style>
</head>
<body>
<div class="wrap">
<?php if(!$is_admin){ ?>
  <div class="login-box card">
    <h1>导航页管理</h1>
    <?php if($error){ ?><div class="err"><?php echo htmlspecialchars($error); ?></div><?php } ?>
    <form method="post">
      <label>管理密码</label>
      <input type="password" name="password" required autofocus>
      <div style="margin-top:14px;"><button class="btn" type="submit" name="login" value="1">登录</button></div>
    </form>
  </div>
<?php }else{ ?>
  <h1>导航页管理 <a href="/admin.php?logout=1">退出登录</a></h1>
  <?php if($msg){ ?><div class="msg"><?php echo htmlspecialchars($msg); ?></div><?php } ?>

  <!-- 站点列表 -->
  <div class="card">
    <h2>站点列表（<?php echo count($sites); ?> 个）</h2>
    <?php if(empty($sites)){ ?>
      <div class="empty">暂无站点，在下方添加</div>
    <?php }else{ ?>
    <table>
      <tr><th width="40">#</th><th>站点</th><th width="190">操作</th></tr>
      <?php foreach($sites as $i => $s){ ?>
      <tr>
        <td><?php echo $i + 1; ?></td>
        <td>
          <span class="icon-badge"><?php echo isset($s['icon']) ? htmlspecialchars($s['icon']) : ''; ?></span>
          <b><?php echo htmlspecialchars(isset($s['name']) ? $s['name'] : ''); ?></b>
          <span class="small"><?php echo htmlspecialchars(isset($s['url']) ? $s['url'] : ''); ?></span>
        </td>
        <td>
          <form method="post" style="display:inline">
            <input type="hidden" name="action" value="up"><input type="hidden" name="idx" value="<?php echo $i; ?>">
            <button class="btn btn-gray" type="submit">↑</button>
          </form>
          <form method="post" style="display:inline">
            <input type="hidden" name="action" value="down"><input type="hidden" name="idx" value="<?php echo $i; ?>">
            <button class="btn btn-gray" type="submit">↓</button>
          </form>
          <form method="post" style="display:inline" onsubmit="return confirm('确定删除「<?php echo htmlspecialchars(isset($s['name']) ? $s['name'] : ''); ?>」？')">
            <input type="hidden" name="action" value="del"><input type="hidden" name="idx" value="<?php echo $i; ?>">
            <button class="btn btn-red" type="submit">删除</button>
          </form>
        </td>
      </tr>
      <?php } ?>
    </table>
    <?php } ?>
  </div>

  <!-- 添加站点 -->
  <div class="card">
    <h2>添加站点</h2>
    <form method="post">
      <input type="hidden" name="action" value="add">
      <label>站点名称 *</label>
      <input type="text" name="name" placeholder="如：我的博客">
      <label>描述</label>
      <input type="text" name="desc" placeholder="一句话描述（可留空）">
      <label>链接 URL *</label>
      <input type="text" name="url" placeholder="https://example.com">
      <label>图标</label>
      <select name="icon">
        <option value="blog">✏️ blog - 编辑笔</option>
        <option value="shop">💧 shop - 水滴</option>
        <option value="img">🖼️ img - 图片</option>
        <option value="gh">📤 gh - 发送</option>
        <option value="pan">☁️ pan - 云</option>
        <option value="snake">🎮 snake - 手柄</option>
        <option value="fw">✦ fw - 星芒</option>
        <option value="gpu">⚡ gpu - 闪电</option>
        <option value="home">🏠 home - 房子</option>
        <option value="doc">📄 doc - 文档</option>
        <option value="link">🔗 link - 链接</option>
        <option value="star">⭐ star - 星星</option>
        <option value="tool">🛠️ tool - 工具</option>
        <option value="mail">✉️ mail - 邮件</option>
        <option value="game">🕹️ game - 游戏</option>
        <option value="code">&lt;/&gt; code - 代码</option>
        <option value="play">▶️ play - 播放</option>
        <option value="music">🎵 music - 音乐</option>
        <option value="video">🎬 video - 视频</option>
        <option value="book">📖 book - 书籍</option>
        <option value="cart">🛒 cart - 购物</option>
        <option value="zap">⚡ zap - 闪电</option>
        <option value="heart">❤️ heart - 爱心</option>
        <option value="user">👤 user - 用户</option>
      </select>
      <div class="small" style="margin-top:6px;">也可以直接填一个 emoji 当图标（如 🚀）</div>
      <div style="margin-top:14px;"><button class="btn" type="submit">添加站点</button></div>
    </form>
  </div>

  <!-- 页面标题 -->
  <div class="card">
    <h2>页面标题</h2>
    <form method="post">
      <input type="hidden" name="action" value="save_title">
      <label>主标题</label>
      <input type="text" name="title" value="<?php echo htmlspecialchars(isset($nav['title']) ? $nav['title'] : ''); ?>">
      <label>副标题</label>
      <input type="text" name="subtitle" value="<?php echo htmlspecialchars(isset($nav['subtitle']) ? $nav['subtitle'] : ''); ?>">
      <label>Logo 图片链接（可选，留空显示默认"w"字）</label>
      <input type="text" name="logo" value="<?php echo htmlspecialchars(isset($nav['logo']) ? $nav['logo'] : ''); ?>" placeholder="https://img.wldwz.icu/imgs/....png">
      <div style="margin-top:14px;"><button class="btn" type="submit">保存标题</button></div>
    </form>
  </div>
<?php } ?>
</div>
</body>
</html>
