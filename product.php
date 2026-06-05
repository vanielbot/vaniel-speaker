<?php

require 'config.php';

$id = $_GET['id'] ?? '';

$url = $SUPABASE_URL . '?ma_sp=eq.' . urlencode($id);

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "apikey: $SUPABASE_KEY",
    "Authorization: Bearer $SUPABASE_KEY"
]);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if (!$data || count($data) == 0) {
    die("Không tìm thấy sản phẩm");
}

$sp = $data[0];

// Parse tags
$tags = array_filter(array_map('trim', preg_split('/[,،\n]+/', $sp['tag_ai'] ?? '')));

?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($sp['ten_sp']) ?> — Vaniel Speaker</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

<style>

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --gold: #c9a84c;
    --gold-light: #e8c97a;
    --gold-dim: #7a6230;
    --bg: #0d0d0d;
    --bg-card: #141414;
    --bg-section: #111111;
    --border: rgba(201,168,76,0.15);
    --border-hover: rgba(201,168,76,0.45);
    --text: #e8e4dc;
    --text-muted: #7a7570;
    --text-subtle: #4a4642;
}

body {
    font-family: 'DM Sans', sans-serif;
    background: var(--bg);
    color: var(--text);
    min-height: 100vh;
    -webkit-font-smoothing: antialiased;
}

/* ── NAV ── */
.nav {
    padding: 24px 60px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.back {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    font-size: 12px;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: var(--text-muted);
    transition: color 0.2s ease;
}

.back svg { width: 16px; height: 16px; transition: transform 0.2s ease; }
.back:hover { color: var(--gold); }
.back:hover svg { transform: translateX(-3px); }

.nav-brand {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.1rem;
    color: var(--text-subtle);
    letter-spacing: 0.05em;
}

/* ── LAYOUT ── */
.page {
    max-width: 1100px;
    margin: 0 auto;
    padding: 60px 60px 80px;
    display: grid;
    grid-template-columns: 1fr 420px;
    gap: 60px;
    align-items: start;
}

/* ── LEFT COLUMN ── */
.left {}

.product-label {
    font-size: 10px;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    color: var(--gold-dim);
    font-weight: 500;
    margin-bottom: 12px;
}

.product-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 600;
    color: var(--text);
    line-height: 1.1;
    letter-spacing: -0.01em;
    margin-bottom: 32px;
}

/* ── PRICE BLOCK ── */
.price-block {
    display: flex;
    align-items: baseline;
    gap: 8px;
    padding: 24px 0;
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
    margin-bottom: 36px;
}

.price-label {
    font-size: 11px;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: var(--text-subtle);
}

.price-value {
    font-family: 'Cormorant Garamond', serif;
    font-size: 2.4rem;
    font-weight: 600;
    color: var(--gold-light);
    line-height: 1;
}

.price-unit {
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    color: var(--text-muted);
    font-weight: 300;
}

/* ── DESCRIPTION ── */
.section-label {
    font-size: 10px;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--text-subtle);
    margin-bottom: 14px;
}

.description {
    font-size: 15px;
    font-weight: 300;
    color: var(--text-muted);
    line-height: 1.8;
    margin-bottom: 40px;
}

/* ── TAGS ── */
.tags-wrap {
    margin-bottom: 40px;
}

.tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 14px;
}

.tag {
    font-size: 11px;
    font-weight: 500;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--gold-dim);
    padding: 5px 12px;
    border: 1px solid rgba(201,168,76,0.2);
    background: rgba(201,168,76,0.04);
    transition: all 0.2s ease;
}

.tag:hover {
    border-color: var(--gold-dim);
    color: var(--gold);
    background: rgba(201,168,76,0.08);
}

/* ── AI BUTTON ── */
.ai-btn {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #0d0d0d;
    background: var(--gold);
    padding: 14px 28px;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
}

.ai-btn::before {
    content: '';
    position: absolute;
    inset: 0;
    background: var(--gold-light);
    transform: translateX(-100%);
    transition: transform 0.25s ease;
}

.ai-btn span, .ai-btn svg { position: relative; z-index: 1; }
.ai-btn svg { width: 16px; height: 16px; }
.ai-btn:hover::before { transform: translateX(0); }

/* ── RIGHT COLUMN ── */
.right {
    position: sticky;
    top: 40px;
}

.info-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    padding: 28px;
    position: relative;
    overflow: hidden;
}

.info-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--gold-dim), transparent);
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 0;
    border-bottom: 1px solid var(--border);
}

.info-row:last-child { border-bottom: none; }

.info-key {
    font-size: 11px;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: var(--text-subtle);
}

.info-val {
    font-size: 13px;
    font-weight: 500;
    color: var(--text);
    text-align: right;
    max-width: 60%;
}

.info-val.gold { color: var(--gold-light); font-family: 'Cormorant Garamond', serif; font-size: 1.3rem; }

/* ── DECORATIVE CIRCLE ── */
.deco {
    margin: 32px auto 0;
    width: 180px;
    height: 180px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.deco svg { width: 100%; height: 100%; opacity: 0.18; }

/* ── ANIMATIONS ── */
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}

.left  { animation: fadeUp 0.5s ease 0.05s both; }
.right { animation: fadeUp 0.5s ease 0.15s both; }

/* ── RESPONSIVE ── */
@media (max-width: 900px) {
    .nav { padding: 20px 24px; }
    .page { grid-template-columns: 1fr; padding: 36px 24px 60px; gap: 40px; }
    .right { position: static; }
    .deco { display: none; }
}


/* ── CART ── */
.cart-icon-wrap {
    cursor:pointer; padding:8px 14px;
    border:1px solid var(--border); display:flex; align-items:center; gap:8px;
    transition:border-color .2s; position:relative;
}
.cart-icon-wrap:hover { border-color:var(--border-hover); }
.cart-icon-wrap svg { width:20px; height:20px; color:var(--gold); }
.cart-icon-wrap span { font-size:11px; text-transform:uppercase; letter-spacing:.15em; color:var(--text-muted); }
#cart-badge {
    position:absolute; top:-6px; right:-6px;
    background:var(--gold); color:#0d0d0d;
    font-size:10px; font-weight:700;
    width:18px; height:18px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
}

.add-cart-btn {
    display:inline-flex; align-items:center; gap:12px;
    text-decoration:none; font-size:13px; font-weight:500;
    letter-spacing:.1em; text-transform:uppercase;
    color:#0d0d0d; background:var(--gold); padding:14px 28px;
    border:none; cursor:pointer; font-family:'DM Sans',sans-serif;
    transition:background .2s; margin-left:12px;
}
.add-cart-btn:hover { background:var(--gold-light); }


/* ── COMPARE ── */
.compare-btn {
    display:inline-flex; align-items:center; gap:10px;
    font-size:13px; font-weight:500; letter-spacing:.1em; text-transform:uppercase;
    color:var(--text-muted); background:none;
    border:1px solid var(--border); padding:14px 28px;
    cursor:pointer; transition:all .2s; font-family:'DM Sans',sans-serif;
    margin-left:12px;
}
.compare-btn:hover { border-color:var(--border-hover); color:var(--text); }
.compare-btn svg { width:16px; height:16px; }

/* MODAL */
#compare-modal {
    position:fixed; inset:0; background:rgba(0,0,0,.8);
    z-index:3000; display:none; align-items:flex-start;
    justify-content:center; padding:40px 20px; overflow-y:auto;
}
#compare-modal.open { display:flex; }

#compare-box {
    background:#141414; border:1px solid rgba(201,168,76,.2);
    width:100%; max-width:960px; position:relative;
    animation: popIn .25s ease both;
}
@keyframes popIn { from{opacity:0;transform:translateY(-16px)} to{opacity:1;transform:translateY(0)} }

#compare-header {
    padding:22px 28px; border-bottom:1px solid var(--border);
    display:flex; align-items:center; justify-content:space-between;
}
#compare-title {
    font-family:'Cormorant Garamond',serif; font-size:1.3rem;
    font-weight:600; color:var(--text);
}
#compare-close {
    background:none; border:none; cursor:pointer; color:var(--text-subtle); padding:4px;
}
#compare-close:hover { color:var(--text); }
#compare-close svg { width:18px; height:18px; }

#compare-scroll { overflow-x:auto; padding:28px; }

/* TABLE */
.compare-table {
    border-collapse:collapse; width:100%; min-width:600px;
}
.compare-table th, .compare-table td {
    padding:14px 16px; text-align:left; vertical-align:top;
    border-bottom:1px solid var(--border);
}
.compare-table th {
    font-size:10px; letter-spacing:.18em; text-transform:uppercase;
    color:var(--text-subtle); font-weight:500; width:120px; white-space:nowrap;
}
.compare-table td { font-size:13px; color:var(--text-muted); }

/* Cột sản phẩm hiện tại highlight */
.col-current { background:rgba(201,168,76,.04); }
.col-current td { color:var(--text); }

.col-name {
    font-family:'Cormorant Garamond',serif; font-size:1rem;
    font-weight:600; color:var(--text); line-height:1.2;
}
.col-brand { font-size:10px; letter-spacing:.15em; text-transform:uppercase; color:var(--gold-dim); }
.col-price {
    font-family:'Cormorant Garamond',serif; font-size:1.1rem;
    font-weight:600; color:var(--gold-light);
}
.col-price.current { color:var(--gold); }

.tag-sm {
    display:inline-block; font-size:10px; padding:3px 8px;
    border:1px solid rgba(201,168,76,.2); color:var(--gold-dim);
    margin:2px 2px 2px 0;
}

.compare-link {
    font-size:11px; letter-spacing:.1em; text-transform:uppercase;
    color:var(--gold); text-decoration:none; border-bottom:1px solid var(--gold-dim);
    padding-bottom:1px; transition:color .2s;
}
.compare-link:hover { color:var(--gold-light); }

.compare-loading {
    text-align:center; padding:60px; color:var(--text-subtle);
    font-size:13px; letter-spacing:.1em;
}

</style>
</head>

<body>

<nav class="nav">
    <a class="back" href="index.php">
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M10 3L5 8l5 5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Quay lại
    </a>
    <div id="auth-ui"></div>
    <div class="cart-icon-wrap" onclick="openCart()" title="Giỏ hàng"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 3h2l.8 4m0 0L7 13h9l2-6H5.8z" stroke-linecap="round" stroke-linejoin="round"/><circle cx="7.5" cy="16.5" r="1" fill="currentColor" stroke="none"/><circle cx="15.5" cy="16.5" r="1" fill="currentColor" stroke="none"/></svg><span>Giỏ hàng</span><span id="cart-badge" style="display:none">0</span></div>
</nav>

<div class="page">

    <!-- LEFT -->
    <div class="left">

        <p class="product-label"><?= htmlspecialchars($sp['hang']) ?></p>
        <h1 class="product-title"><?= htmlspecialchars($sp['ten_sp']) ?></h1>

        <div class="price-block">
            <span class="price-label">Giá bán</span>
            <span class="price-value"><?= number_format((int)$sp['gia']) ?></span>
            <span class="price-unit">đồng</span>
        </div>

        <div style="margin-bottom: 36px;">
            <p class="section-label">Mô tả sản phẩm</p>
            <p class="description"><?= nl2br(htmlspecialchars($sp['mo_ta'])) ?></p>
        </div>

        <?php if (!empty($tags)): ?>
        <div class="tags-wrap">
            <p class="section-label">Đặc điểm nổi bật</p>
            <div class="tags">
                <?php foreach($tags as $tag): ?>
                <span class="tag"><?= htmlspecialchars($tag) ?></span>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <button class="add-cart-btn" data-add-cart
            data-id="<?= htmlspecialchars($sp['ma_sp']) ?>"
            data-name="<?= htmlspecialchars($sp['ten_sp']) ?>"
            data-brand="<?= htmlspecialchars($sp['hang']) ?>"
            data-price="<?= (int)$sp['gia'] ?>">
            <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" style="width:16px;height:16px;">
                <path d="M3 3h2l.8 4m0 0L7 13h9l2-6H5.8z" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="7.5" cy="16.5" r="1" fill="currentColor" stroke="none"/>
                <circle cx="15.5" cy="16.5" r="1" fill="currentColor" stroke="none"/>
            </svg>
            Thêm vào giỏ
        </button>


        <button class="compare-btn" id="open-compare-btn">
            <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M2 4h5v8H2zM9 4h5v8H9z" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            So sánh sản phẩm
        </button>


    </div>

    <!-- RIGHT -->
    <div class="right">

        <div class="info-card">

            <div class="info-row">
                <span class="info-key">Sản phẩm</span>
                <span class="info-val"><?= htmlspecialchars($sp['ma_sp']) ?></span>
            </div>

            <div class="info-row">
                <span class="info-key">Thương hiệu</span>
                <span class="info-val"><?= htmlspecialchars($sp['hang']) ?></span>
            </div>

            <div class="info-row">
                <span class="info-key">Giá</span>
                <span class="info-val gold"><?= number_format((int)$sp['gia']) ?> đ</span>
            </div>

        </div>

        <div class="deco">
            <svg viewBox="0 0 180 180" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="90" cy="90" r="85" stroke="#c9a84c" stroke-width="0.5"/>
                <circle cx="90" cy="90" r="65" stroke="#c9a84c" stroke-width="0.5"/>
                <circle cx="90" cy="90" r="45" stroke="#c9a84c" stroke-width="0.5"/>
                <circle cx="90" cy="90" r="25" stroke="#c9a84c" stroke-width="0.5"/>
                <circle cx="90" cy="90" r="8" fill="#c9a84c" opacity="0.4"/>
                <line x1="90" y1="5" x2="90" y2="175" stroke="#c9a84c" stroke-width="0.5" stroke-dasharray="4 6"/>
                <line x1="5" y1="90" x2="175" y2="90" stroke="#c9a84c" stroke-width="0.5" stroke-dasharray="4 6"/>
            </svg>
        </div>

    </div>

</div>

<script src="auth.js"></script>
<script src="cart.js"></script>
<script>window.addEventListener("DOMContentLoaded", initAddToCartButtons);</script>

<!-- ── COMPARE MODAL ── -->
<div id="compare-modal">
  <div id="compare-box">
    <div id="compare-header">
      <span id="compare-title">So sánh sản phẩm cùng tầm giá</span>
      <button id="compare-close">
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
          <path d="M3 3l10 10M13 3L3 13" stroke-linecap="round"/>
        </svg>
      </button>
    </div>
    <div id="compare-scroll">
      <div class="compare-loading">Đang tải...</div>
    </div>
  </div>
</div>




<script>
(function() {
    var btn = document.getElementById('open-compare-btn');
    var modal = document.getElementById('compare-modal');
    var closeBtn = document.getElementById('compare-close');
    var scroll = document.getElementById('compare-scroll');
    var loaded = false;

    if (!btn || !modal) return;

    btn.addEventListener('click', function() {
        modal.style.display = 'flex';
        if (!loaded) { loaded = true; loadCompare(); }
    });

    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    modal.addEventListener('click', function(e) {
        if (e.target === modal) modal.style.display = 'none';
    });

    function loadCompare() {
        var curGia    = <?= (int)$sp['gia'] ?>;
        var curMaSp   = <?= json_encode($sp['ma_sp']) ?>;
        var curTenSp  = <?= json_encode($sp['ten_sp']) ?>;
        var curHang   = <?= json_encode($sp['hang']) ?>;
        var curMoTa   = <?= json_encode(substr($sp['mo_ta'] ?? '', 0, 120)) ?>;
        var curTagAi  = <?= json_encode($sp['tag_ai'] ?? '') ?>;

        scroll.innerHTML = '<div class="compare-loading">Đang tải sản phẩm tương tự...</div>';

        var min = Math.round(curGia * 0.8);
        var max = Math.round(curGia * 1.2);
        var url = 'https://lvgmfunnudvmzicxtmkw.supabase.co/rest/v1/products'
                + '?gia=gte.' + min + '&gia=lte.' + max
                + '&ma_sp=neq.' + encodeURIComponent(curMaSp)
                + '&limit=5';

        fetch(url, {
            headers: {
                'apikey': 'sb_publishable_QCxhn8xMtRn3DfFrgRHQhQ_7XLMVAmc',
                'Authorization': 'Bearer sb_publishable_QCxhn8xMtRn3DfFrgRHQhQ_7XLMVAmc'
            }
        })
        .then(function(r) { return r.json(); })
        .then(function(products) {
            if (!Array.isArray(products) || !products.length) {
                scroll.innerHTML = '<div class="compare-loading">Không có sản phẩm tương tự trong tầm giá này.</div>';
                return;
            }

            var cur = { ma_sp: curMaSp, ten_sp: curTenSp, hang: curHang,
                        gia: curGia, mo_ta: curMoTa, tag_ai: curTagAi, _current: true };
            var all = [cur].concat(products);

            var html = '<table class="compare-table"><thead><tr><th></th>';
            all.forEach(function(sp) {
                html += '<th class="' + (sp._current ? 'col-current' : '') + '">'
                      + (sp._current ? '★ Sản phẩm này' : sp.hang) + '</th>';
            });
            html += '</tr></thead><tbody>';

            // Hàng tên
            html += '<tr><th>Sản phẩm</th>';
            all.forEach(function(sp) {
                html += '<td class="' + (sp._current ? 'col-current' : '') + '">'
                      + '<div class="col-name">' + sp.ten_sp + '</div>'
                      + '<div class="col-brand">' + sp.hang + '</div></td>';
            });
            html += '</tr>';

            // Hàng giá
            html += '<tr><th>Giá</th>';
            all.forEach(function(sp) {
                html += '<td class="' + (sp._current ? 'col-current' : '') + '">'
                      + '<span class="col-price' + (sp._current ? ' current' : '') + '">'
                      + parseInt(sp.gia).toLocaleString('vi-VN') + ' đ</span></td>';
            });
            html += '</tr>';

            // Hàng mô tả
            html += '<tr><th>Mô tả</th>';
            all.forEach(function(sp) {
                var mo = (sp.mo_ta || '').substring(0, 100) + '...';
                html += '<td class="' + (sp._current ? 'col-current' : '') + '">' + mo + '</td>';
            });
            html += '</tr>';

            // Hàng tags
            html += '<tr><th>Đặc điểm</th>';
            all.forEach(function(sp) {
                var tags = (sp.tag_ai || '').split(/[,\n\r،]+/).map(function(t){return t.trim();}).filter(Boolean).slice(0,5);
                var tagHtml = tags.map(function(t){ return '<span class="tag-sm">' + t + '</span>'; }).join('') || '—';
                html += '<td class="' + (sp._current ? 'col-current' : '') + '">' + tagHtml + '</td>';
            });
            html += '</tr>';

            // Hàng link
            html += '<tr><th>Xem</th>';
            all.forEach(function(sp) {
                html += '<td class="' + (sp._current ? 'col-current' : '') + '">'
                      + (sp._current
                          ? '<span style="font-size:11px;color:var(--gold)">Đang xem</span>'
                          : '<a class="compare-link" href="product.php?id=' + encodeURIComponent(sp.ma_sp) + '">Xem chi tiết →</a>')
                      + '</td>';
            });
            html += '</tr>';

            html += '</tbody></table>';
            scroll.innerHTML = html;
        })
        .catch(function(err) {
            scroll.innerHTML = '<div class="compare-loading">Lỗi: ' + err.message + '</div>';
        });
    }
})();
</script>

</body>
</html>