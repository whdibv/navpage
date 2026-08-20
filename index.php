<?php
/**
 * 站点导航页（从 nav.json 读取配置渲染）
 * 修改站点：编辑 nav.json 或访问 /admin.php 管理
 */
$nav = json_decode(@file_get_contents(__DIR__ . '/nav.json'), true);
if (!is_array($nav)) {
    $nav = array();
}
$sites = isset($nav['sites']) ? $nav['sites'] : array();
$title = isset($nav['title']) && $nav['title'] !== '' ? $nav['title'] : 'wldwz.icu 站点导航';
$subtitle = isset($nav['subtitle']) ? $nav['subtitle'] : '';
$logo = isset($nav['logo']) ? $nav['logo'] : '';

// 内置图标库（线性 SVG）
$icons = array(
    'blog'  => '<path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>',
    'shop'  => '<path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/>',
    'img'   => '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>',
    'gh'    => '<line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/>',
    'pan'   => '<path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"/>',
    'snake' => '<line x1="6" y1="12" x2="10" y2="12"/><line x1="8" y1="10" x2="8" y2="14"/><line x1="15" y1="13" x2="15.01" y2="13"/><line x1="18" y1="11" x2="18.01" y2="11"/><path d="M17.32 5H6.68a4 4 0 0 0-3.978 3.59c-.006.052-.01.101-.017.152C2.604 9.416 2 14.456 2 16a3 3 0 0 0 3 3c1 0 1.5-.5 2-1l1.414-1.414A2 2 0 0 1 9.828 16h4.344a2 2 0 0 1 1.414.586L17 18c.5.5 1 1 2 1a3 3 0 0 0 3-3c0-1.545-.604-6.584-.685-7.258-.007-.05-.011-.1-.017-.151A4 4 0 0 0 17.32 5z"/>',
    'fw'    => '<polygon points="12 2 15 9 22 12 15 15 12 22 9 15 2 12 9 9 12 2"/>',
    'gpu'   => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>',
    'home'  => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
    'doc'   => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>',
    'link'  => '<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>',
    'star'  => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
    'tool'  => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>',
    'mail'  => '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>',
    'game'  => '<line x1="6" y1="12" x2="10" y2="12"/><line x1="8" y1="10" x2="8" y2="14"/><line x1="15" y1="13" x2="15.01" y2="13"/><line x1="18" y1="11" x2="18.01" y2="11"/><path d="M17.32 5H6.68a4 4 0 0 0-3.978 3.59c-.006.052-.01.101-.017.152C2.604 9.416 2 14.456 2 16a3 3 0 0 0 3 3c1 0 1.5-.5 2-1l1.414-1.414A2 2 0 0 1 9.828 16h4.344a2 2 0 0 1 1.414.586L17 18c.5.5 1 1 2 1a3 3 0 0 0 3-3c0-1.545-.604-6.584-.685-7.258-.007-.05-.011-.1-.017-.151A4 4 0 0 0 17.32 5z"/>',
    'code'  => '<polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/>',
    'play'  => '<polygon points="5 3 19 12 5 21 5 3"/>',
    'music' => '<path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/>',
    'video' => '<polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>',
    'book'  => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>',
    'cart'  => '<circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>',
    'zap'   => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>',
    'heart' => '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>',
    'user'  => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
);
// 图标 → 渐变色
$colors = array(
    'blog' => 'icon-blog', 'shop' => 'icon-shop', 'img' => 'icon-img', 'gh' => 'icon-gh',
    'pan' => 'icon-pan', 'snake' => 'icon-snake', 'fw' => 'icon-fw', 'gpu' => 'icon-gpu',
    'home' => 'icon-gh', 'doc' => 'icon-blog', 'link' => 'icon-pan', 'star' => 'icon-fw',
    'tool' => 'icon-snake', 'mail' => 'icon-shop', 'game' => 'icon-snake', 'code' => 'icon-gh',
    'play' => 'icon-fw', 'music' => 'icon-gpu', 'video' => 'icon-gpu', 'book' => 'icon-blog',
    'cart' => 'icon-shop', 'zap' => 'icon-gpu', 'heart' => 'icon-fw', 'user' => 'icon-blog',
);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo htmlspecialchars($title); ?></title>
<meta name="description" content="<?php echo htmlspecialchars($subtitle !== '' ? $subtitle . '，旗下站点：' . implode('、', array_map(function($s){return isset($s['name']) ? $s['name'] : '';}, $sites)) : 'wldwz.icu 旗下站点导航'); ?>">
<meta name="robots" content="index,follow">
<link rel="canonical" href="https://wldwz.icu/">
<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:site_name" content="wldwz.icu">
<meta property="og:title" content="<?php echo htmlspecialchars($title); ?>">
<meta property="og:description" content="<?php echo htmlspecialchars($subtitle); ?>">
<meta property="og:url" content="https://wldwz.icu/">
<!-- Twitter Card -->
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="<?php echo htmlspecialchars($title); ?>">
<meta name="twitter:description" content="<?php echo htmlspecialchars($subtitle); ?>">
<!-- 结构化数据：WebSite（GEO） -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "<?php echo htmlspecialchars($title); ?>",
  "url": "https://wldwz.icu/",
  "description": "<?php echo htmlspecialchars($subtitle); ?>",
  "inLanguage": "zh-CN"
}
</script>
<!-- 结构化数据：站点列表（GEO 核心，AI 引擎可直接解析） -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "name": "wldwz.icu 旗下站点",
  "numberOfItems": <?php echo count($sites); ?>,
  "itemListElement": [
<?php foreach($sites as $i => $s){
    $nm = htmlspecialchars(isset($s['name']) ? $s['name'] : '');
    $ds = htmlspecialchars(isset($s['desc']) ? $s['desc'] : '');
    $ul = htmlspecialchars(isset($s['url']) ? $s['url'] : '');
?>
    {"@type": "ListItem", "position": <?php echo $i + 1; ?>, "name": "<?php echo $nm; ?>", "description": "<?php echo $ds; ?>", "url": "<?php echo $ul; ?>"}<?php echo $i < count($sites) - 1 ? ',' : ''; ?>

<?php } ?>
  ]
}
</script>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
  font-family: -apple-system, "PingFang SC", "Microsoft YaHei", "Segoe UI", sans-serif;
  background: linear-gradient(160deg, #eef4ff 0%, #f8fafc 45%, #eefaf4 100%);
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 40px 20px;
}
.logo {
  width: 72px; height: 72px;
  border-radius: 20px;
  background: linear-gradient(135deg, #38bdf8, #1d9e75);
  display: flex; align-items: center; justify-content: center;
  font-size: 34px; font-weight: 700; color: #fff;
  margin-bottom: 18px;
  box-shadow: 0 8px 24px rgba(29, 158, 117, .25);
}
.logo-img {
  width: 72px; height: 72px; border-radius: 20px; object-fit: cover;
  margin-bottom: 18px;
  box-shadow: 0 8px 24px rgba(29, 158, 117, .25);
}
h1 { font-size: 22px; font-weight: 700; color: #1e293b; letter-spacing: .02em; }
.sub { font-size: 13px; color: #64748b; margin: 8px 0 36px; }
header { text-align: center; }
.grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 16px;
  width: 100%; max-width: 880px;
}
.card {
  display: flex; align-items: center; gap: 16px;
  background: #fff; border: 1px solid #e8edf3; border-radius: 16px;
  padding: 20px 22px;
  text-decoration: none;
  transition: transform .15s ease, box-shadow .15s ease;
  box-shadow: 0 2px 8px rgba(15, 23, 42, .04);
}
.card:hover { transform: translateY(-3px); box-shadow: 0 10px 28px rgba(15, 23, 42, .10); }
.icon {
  width: 48px; height: 48px; border-radius: 13px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
  box-shadow: inset 0 1px 0 rgba(255,255,255,.18), 0 4px 12px rgba(15,23,42,.12);
}
.icon svg { width: 24px; height: 24px; }
.icon-emoji { font-size: 24px; background: linear-gradient(135deg, #64748b, #334155); }
.icon-blog { background: linear-gradient(135deg, #38bdf8, #2563eb); }
.icon-shop { background: linear-gradient(135deg, #fb923c, #dc2626); }
.icon-img  { background: linear-gradient(135deg, #34d399, #059669); }
.icon-gh   { background: linear-gradient(135deg, #475569, #0f172a); }
.icon-pan  { background: linear-gradient(135deg, #a78bfa, #7c3aed); }
.icon-snake{ background: linear-gradient(135deg, #4ade80, #15803d); }
.icon-fw   { background: linear-gradient(135deg, #fb7185, #e11d48); }
.icon-gpu  { background: linear-gradient(135deg, #fbbf24, #d97706); }
.card h2 { font-size: 16px; font-weight: 600; color: #1e293b; }
.card p { font-size: 12.5px; color: #64748b; margin-top: 4px; }
.card .tag {
  display: inline-block; font-size: 11px; color: #475569;
  background: #f1f5f9; border-radius: 99px; padding: 2px 10px; margin-top: 6px;
}
footer { margin-top: 40px; font-size: 12px; color: #475569; }
</style>
</head>
<body>
  <header>
    <?php if($logo !== ''){ ?>
    <img class="logo-img" src="<?php echo htmlspecialchars($logo); ?>" alt="<?php echo htmlspecialchars($title); ?>">
    <?php }else{ ?>
    <div class="logo">w</div>
    <?php } ?>
    <h1><?php echo htmlspecialchars($title); ?></h1>
    <?php if($subtitle !== ''){ ?><p class="sub"><?php echo htmlspecialchars($subtitle); ?></p><?php } ?>
  </header>
  <main class="grid">
    <?php foreach($sites as $s){
        $name = isset($s['name']) ? $s['name'] : '';
        $desc = isset($s['desc']) ? $s['desc'] : '';
        $url = isset($s['url']) ? $s['url'] : '#';
        $icon = isset($s['icon']) ? $s['icon'] : 'link';
        $domain = parse_url($url, PHP_URL_HOST);
        $domain = $domain ? $domain : '';
        $icon_html = '';
        $icon_class = 'icon-gh';
        if(isset($icons[$icon])){
            $icon_html = '<svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">'.$icons[$icon].'</svg>';
            $icon_class = isset($colors[$icon]) ? $colors[$icon] : 'icon-gh';
        }else{
            // 非内置图标名：按 emoji 显示
            $icon_html = '<span class="icon-emoji">'.htmlspecialchars($icon).'</span>';
            $icon_class = 'icon-emoji';
        }
    ?>
    <a class="card" href="<?php echo htmlspecialchars($url); ?>">
      <div class="icon <?php echo $icon_class; ?>"><?php echo $icon_html; ?></div>
      <div>
        <h2><?php echo htmlspecialchars($name); ?></h2>
        <?php if($desc !== ''){ ?><p><?php echo htmlspecialchars($desc); ?></p><?php } ?>
        <?php if($domain !== ''){ ?><span class="tag"><?php echo htmlspecialchars($domain); ?></span><?php } ?>
      </div>
    </a>
    <?php } ?>
  </main>
  <footer>© 2026 wldwz.icu</footer>
</body>
</html>
