<?php
/**
 * 动态生成站点地图（从 nav.json 读取）
 * 访问 /sitemap.php
 */
header('Content-Type: application/xml; charset=utf-8');
$nav = json_decode(@file_get_contents(__DIR__ . '/nav.json'), true);
$sites = isset($nav['sites']) ? $nav['sites'] : array();

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
// 主站
echo "  <url>\n    <loc>https://wldwz.icu/</loc>\n    <changefreq>weekly</changefreq>\n    <priority>1.0</priority>\n  </url>\n";
// 各子站
foreach ($sites as $s) {
    $url = isset($s['url']) ? $s['url'] : '';
    if ($url !== '') {
        echo "  <url>\n    <loc>" . htmlspecialchars($url) . "</loc>\n    <changefreq>weekly</changefreq>\n    <priority>0.8</priority>\n  </url>\n";
    }
}
echo '</urlset>' . "\n";
