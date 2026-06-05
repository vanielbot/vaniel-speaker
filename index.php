<?php

require 'config.php';

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $SUPABASE_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "apikey: $SUPABASE_KEY",
    "Authorization: Bearer $SUPABASE_KEY"
]);

$response = curl_exec($ch);
curl_close($ch);

$products = json_decode($response, true);

?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Vaniel Speaker</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

<style>

*, *::before, *::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

:root {
    --gold: #c9a84c;
    --gold-light: #e8c97a;
    --gold-dim: #7a6230;
    --bg: #0d0d0d;
    --bg-card: #141414;
    --bg-card-hover: #1a1a1a;
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

/* ── HEADER ── */
.header {
    position: relative;
    padding: 80px 60px 70px;
    border-bottom: 1px solid var(--border);
    overflow: hidden;
}

.header::before {
    content: '';
    position: absolute;
    top: -60px;
    right: -60px;
    width: 420px;
    height: 420px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(201,168,76,0.07) 0%, transparent 70%);
    pointer-events: none;
}

.header-inner {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 40px;
    flex-wrap: wrap;
}

.brand {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.brand-icon {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}

.brand-icon svg {
    width: 36px;
    height: 36px;
    color: var(--gold);
}

.brand-label {
    font-size: 11px;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    color: var(--gold);
    font-weight: 500;
}

.brand h1 {
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(2.4rem, 5vw, 3.6rem);
    font-weight: 600;
    color: var(--text);
    letter-spacing: -0.01em;
    line-height: 1;
}

.brand p {
    font-size: 14px;
    color: var(--text-muted);
    font-weight: 300;
    margin-top: 8px;
    letter-spacing: 0.01em;
}

.header-stats {
    display: flex;
    gap: 40px;
    padding-bottom: 4px;
}

.stat {
    text-align: right;
}

.stat-num {
    font-family: 'Cormorant Garamond', serif;
    font-size: 2rem;
    font-weight: 600;
    color: var(--gold);
    line-height: 1;
}

.stat-label {
    font-size: 11px;
    color: var(--text-subtle);
    letter-spacing: 0.12em;
    text-transform: uppercase;
    margin-top: 4px;
}

/* ── DIVIDER ── */
.divider {
    max-width: 1200px;
    margin: 0 auto;
    padding: 28px 60px 0;
    display: flex;
    align-items: center;
    gap: 16px;
}

.divider-line {
    flex: 1;
    height: 1px;
    background: var(--border);
}

.divider-text {
    font-size: 11px;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--text-subtle);
    white-space: nowrap;
}

/* ── GRID ── */
.products {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2px;
    padding: 28px 60px 80px;
}

/* ── CARD ── */
.card {
    background: var(--bg-card);
    padding: 32px 28px 28px;
    border: 1px solid var(--border);
    position: relative;
    transition: background 0.2s ease, border-color 0.2s ease;
    display: flex;
    flex-direction: column;
    gap: 0;
    overflow: hidden;
}

.card::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--gold-dim), transparent);
    opacity: 0;
    transition: opacity 0.25s ease;
}

.card:hover {
    background: var(--bg-card-hover);
    border-color: var(--border-hover);
}

.card:hover::after {
    opacity: 1;
}

.card-num {
    font-family: 'Cormorant Garamond', serif;
    font-size: 11px;
    color: var(--text-subtle);
    letter-spacing: 0.2em;
    margin-bottom: 20px;
}

.card-brand {
    font-size: 10px;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--gold-dim);
    font-weight: 500;
    margin-bottom: 8px;
}

.card h3 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.4rem;
    font-weight: 600;
    color: var(--text);
    line-height: 1.2;
    margin-bottom: 24px;
    flex: 1;
}

.card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-top: auto;
    padding-top: 20px;
    border-top: 1px solid var(--border);
}

.price {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--gold-light);
    letter-spacing: -0.01em;
}

.price-unit {
    font-family: 'DM Sans', sans-serif;
    font-size: 12px;
    color: var(--text-subtle);
    font-weight: 400;
}

.button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 500;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--gold);
    padding: 8px 16px;
    border: 1px solid var(--gold-dim);
    transition: all 0.2s ease;
    white-space: nowrap;
}

.button svg {
    width: 12px;
    height: 12px;
    transition: transform 0.2s ease;
}

.button:hover {
    background: var(--gold);
    color: #0d0d0d;
    border-color: var(--gold);
}

.button:hover svg {
    transform: translateX(3px);
}

/* ── FOOTER ── */
.footer {
    border-top: 1px solid var(--border);
    padding: 28px 60px;
    text-align: center;
    color: var(--text-subtle);
    font-size: 12px;
    letter-spacing: 0.08em;
    max-width: 1200px;
    margin: 0 auto;
}

/* ── ANIMATIONS ── */
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}

.card {
    animation: fadeUp 0.4s ease both;
}

<?php for ($i = 1; $i <= 20; $i++): ?>
.card:nth-child(<?= $i ?>) { animation-delay: <?= $i * 0.04 ?>s; }
<?php endfor; ?>

/* ── RESPONSIVE ── */
@media (max-width: 768px) {
    .header { padding: 48px 24px 40px; }
    .header-stats { display: none; }
    .divider, .products { padding-left: 24px; padding-right: 24px; }
    .footer { padding: 24px; }
}


/* ── CART ICON ── */
.cart-icon-wrap {
    position: relative;
    cursor: pointer;
    padding: 10px;
    border: 1px solid var(--border);
    transition: border-color .2s;
    display: flex; align-items: center; gap: 8px;
}
.cart-icon-wrap:hover { border-color: var(--border-hover); }
.cart-icon-wrap svg { width: 22px; height: 22px; color: var(--gold); }
.cart-icon-label { font-size: 11px; text-transform: uppercase; letter-spacing: .15em; color: var(--text-muted); }
#cart-badge {
    position: absolute; top: -6px; right: -6px;
    background: var(--gold); color: #0d0d0d;
    font-size: 10px; font-weight: 700;
    width: 18px; height: 18px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
}

/* ── ADD TO CART BTN ── */
.btn-add {
    display: inline-flex; align-items: center; gap: 6px;
    background: none; border: 1px solid var(--gold-dim);
    color: var(--gold); padding: 8px 14px;
    font-size: 12px; font-weight: 500; letter-spacing: .08em; text-transform: uppercase;
    cursor: pointer; transition: all .2s; white-space: nowrap;
    font-family: 'DM Sans', sans-serif;
}
.btn-add svg { width: 12px; height: 12px; flex-shrink: 0; }
.btn-add:hover { background: var(--gold); color: #0d0d0d; border-color: var(--gold); }

.card-footer { 
    flex-wrap: nowrap !important; 
    gap: 8px !important; 
    align-items: center !important;
}
.card-footer .price { white-space: nowrap; }
.btn-add { padding: 8px 10px; font-size: 11px; }

</style>
</head>

<body>

<header class="header">
    <div class="header-inner">
        <div class="brand">
            <div class="brand-icon">
                <svg viewBox="0 0 36 36" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="18" cy="18" r="10"/>
                    <circle cx="18" cy="18" r="4"/>
                    <circle cx="18" cy="18" r="1.5" fill="currentColor" stroke="none"/>
                    <path d="M18 4v4M18 28v4M4 18h4M28 18h4" stroke-linecap="round"/>
                </svg>
                <span class="brand-label">Premium Audio</span>
            </div>
            <h1>Vaniel Speaker</h1>
            <p>Tìm chiếc loa phù hợp với bạn bằng AI</p>
        </div>


        <div id="auth-ui"></div>
        <div class="header-stats">
            <div class="cart-icon-wrap" onclick="openCart()" title="Giỏ hàng">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M3 3h2l.8 4m0 0L7 13h9l2-6H5.8z" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="7.5" cy="16.5" r="1" fill="currentColor" stroke="none"/>
                    <circle cx="15.5" cy="16.5" r="1" fill="currentColor" stroke="none"/>
                </svg>
                <span class="cart-icon-label">Giỏ hàng</span>
                <span id="cart-badge" style="display:none">0</span>
            </div>
            <div class="stat">
                <div class="stat-num"><?= count($products) ?></div>
                <div class="stat-label">Sản phẩm</div>
            </div>
            <div class="stat">
                <div class="stat-num">AI</div>
                <div class="stat-label">Tư vấn</div>
            </div>
        </div>
    </div>
</header>

<div class="divider">
    <div class="divider-line"></div>
    <span class="divider-text">Bộ sưu tập</span>
    <div class="divider-line"></div>
</div>

<div class="products">

<?php foreach($products as $index => $sp): ?>

<div class="card">

    <div class="card-num"><?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?></div>

    <div class="card-brand"><?= htmlspecialchars($sp['hang']) ?></div>

    <h3><?= htmlspecialchars($sp['ten_sp']) ?></h3>

    <div class="card-footer">
        <div>
            <div class="price"><?= number_format((int)$sp['gia']) ?><span class="price-unit"> đ</span></div>
        </div>
            <button class="btn-add" data-add-cart
                    data-id="<?= htmlspecialchars($sp['ma_sp']) ?>"
                    data-name="<?= htmlspecialchars($sp['ten_sp']) ?>"
                    data-brand="<?= htmlspecialchars($sp['hang']) ?>"
                    data-price="<?= (int)$sp['gia'] ?>">
                <svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M6 2v8M2 6h8" stroke-linecap="round"/>
                </svg>
                Thêm
            </button>
        <a class="button" href="product.php?id=<?= urlencode($sp['ma_sp']) ?>">
            Chi tiết
            <svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M2 6h8M6 2l4 4-4 4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
    </div>

</div>

<?php endforeach; ?>

</div>

<div class="footer">
    &copy; <?= date('Y') ?> Vaniel Speaker — Premium Audio Experience
</div>


<!-- ── CHAT BUBBLE ── -->
<style>
#chat-bubble {
    position: fixed;
    bottom: 28px;
    right: 28px;
    z-index: 1000;
}

#chat-toggle {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: var(--gold);
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 24px rgba(201,168,76,0.35);
    transition: transform 0.2s ease, background 0.2s ease;
}
#chat-toggle:hover { background: var(--gold-light); transform: scale(1.08); }
#chat-toggle svg { width: 24px; height: 24px; color: #0d0d0d; }

#chat-window {
    position: fixed;
    bottom: 96px;
    right: 28px;
    width: 360px;
    height: 520px;
    background: #141414;
    border: 1px solid var(--border);
    border-radius: 16px;
    display: none;
    flex-direction: column;
    overflow: hidden;
    box-shadow: 0 8px 40px rgba(0,0,0,0.5);
    z-index: 999;
}
#chat-window.open { display: flex; }

.chat-header {
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 10px;
}
.chat-header-icon {
    width: 32px; height: 32px; border-radius: 50%;
    background: rgba(201,168,76,0.15);
    display: flex; align-items: center; justify-content: center;
}
.chat-header-icon svg { width: 16px; height: 16px; color: var(--gold); }
.chat-header-text { flex: 1; }
.chat-header-title { font-size: 13px; font-weight: 500; color: var(--text); }
.chat-header-sub { font-size: 11px; color: var(--text-subtle); margin-top: 1px; }
.chat-close {
    background: none; border: none; cursor: pointer;
    color: var(--text-subtle); padding: 4px; transition: color 0.2s;
}
.chat-close:hover { color: var(--text); }
.chat-close svg { width: 16px; height: 16px; }

#chat-messages {
    flex: 1; overflow-y: auto; padding: 16px;
    display: flex; flex-direction: column; gap: 12px;
    scrollbar-width: thin; scrollbar-color: var(--border) transparent;
}
.msg {
    max-width: 85%; font-size: 13px; line-height: 1.6;
    padding: 10px 14px; border-radius: 12px; word-break: break-word;
}
.msg.bot {
    background: #1e1e1e; color: var(--text);
    border: 1px solid var(--border);
    align-self: flex-start; border-bottom-left-radius: 4px;
}
.msg.user {
    background: var(--gold); color: #0d0d0d;
    align-self: flex-end; border-bottom-right-radius: 4px; font-weight: 500;
}
.msg.typing { color: var(--text-muted); font-style: italic; }

.chat-footer {
    padding: 12px 16px; border-top: 1px solid var(--border);
    display: flex; gap: 8px; align-items: center;
}
#chat-input {
    flex: 1; background: #1a1a1a; border: 1px solid var(--border);
    border-radius: 8px; padding: 9px 12px; font-size: 13px;
    color: var(--text); font-family: 'DM Sans', sans-serif;
    outline: none; transition: border-color 0.2s;
}
#chat-input::placeholder { color: var(--text-subtle); }
#chat-input:focus { border-color: var(--gold-dim); }
#chat-send {
    width: 36px; height: 36px; border-radius: 8px;
    background: var(--gold); border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background 0.2s; flex-shrink: 0;
}
#chat-send:hover { background: var(--gold-light); }
#chat-send svg { width: 16px; height: 16px; color: #0d0d0d; }
#chat-send:disabled { opacity: 0.4; cursor: not-allowed; }
</style>

<div id="chat-bubble">
    <button id="chat-toggle" aria-label="Mở chat tư vấn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>
</div>

<div id="chat-window">
    <div class="chat-header">
        <div class="chat-header-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <circle cx="12" cy="12" r="3"/><circle cx="12" cy="12" r="8"/>
                <path d="M12 4v2M12 18v2M4 12h2M18 12h2" stroke-linecap="round"/>
            </svg>
        </div>
        <div class="chat-header-text">
            <div class="chat-header-title">Tư vấn AI</div>
            <div class="chat-header-sub">Vaniel Speaker Assistant</div>
        </div>
        <button class="chat-close" id="chat-close-btn" aria-label="Đóng">
            <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M3 3l10 10M13 3L3 13" stroke-linecap="round"/>
            </svg>
        </button>
    </div>

    <div id="chat-messages">
        <div class="msg bot">Xin chào! Tôi là trợ lý tư vấn loa của Vaniel Speaker. Bạn đang tìm loa cho mục đích gì — nghe nhạc, xem phim, hay dùng ngoài trời?</div>
    </div>

    <div class="chat-footer">
        <input id="chat-input" type="text" placeholder="Nhập câu hỏi..." maxlength="500">
        <button id="chat-send">
            <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M3 8h10M9 4l4 4-4 4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </div>
</div>

<script>
// Gọi thẳng Dify API từ JavaScript — không qua PHP proxy



let conversationId = '';

const toggle   = document.getElementById('chat-toggle');
const closeBtn = document.getElementById('chat-close-btn');
const chatWindow = document.getElementById('chat-window');
const input    = document.getElementById('chat-input');
const sendBtn  = document.getElementById('chat-send');
const messages = document.getElementById('chat-messages');

toggle.addEventListener('click', () => {
    chatWindow.classList.toggle('open');
    if (chatWindow.classList.contains('open')) input.focus();
});
closeBtn.addEventListener('click', () => chatWindow.classList.remove('open'));
input.addEventListener('keydown', e => {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
});
sendBtn.addEventListener('click', sendMessage);

function addMsg(text, role) {
    const div = document.createElement('div');
    div.className = 'msg ' + role;
    div.textContent = text;
    messages.appendChild(div);
    messages.scrollTop = messages.scrollHeight;
    return div;
}

async function sendMessage() {
    const text = input.value.trim();
    if (!text) return;
    input.value = '';
    sendBtn.disabled = true;
    addMsg(text, 'user');
    const typing = addMsg('Đang soạn...', 'bot typing');

    try {
        const body = {
            inputs: {},
            query: text,
            response_mode: 'blocking',
            user: 'visitor-' + Math.random().toString(36).slice(2, 8)
        };
        if (conversationId) body.conversation_id = conversationId;

        const res = await fetch('chat_proxy.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(body)
        });

        const data = await res.json();
        typing.remove();

        if (data.answer) {
            addMsg(data.answer, 'bot');
            if (data.conversation_id) conversationId = data.conversation_id;
        } else {
            addMsg('Có lỗi xảy ra, vui lòng thử lại.', 'bot');
        }
    } catch (err) {
        typing.remove();
        addMsg('Không thể kết nối, vui lòng thử lại.', 'bot');
    }

    sendBtn.disabled = false;
    input.focus();
}
</script>
<script src="auth.js"></script>
<script src="cart.js"></script>
<script>window.addEventListener("DOMContentLoaded", initAddToCartButtons);</script>
</body>
</html>