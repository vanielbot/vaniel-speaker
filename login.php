<?php
// login.php — Vaniel Speaker
// Dùng Supabase Auth REST API (không cần SDK)
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Đăng nhập — Vaniel Speaker</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --gold:#c9a84c;--gold-light:#e8c97a;--gold-dim:#7a6230;
  --bg:#0d0d0d;--bg-card:#141414;--border:rgba(201,168,76,.15);
  --border-hover:rgba(201,168,76,.45);--text:#e8e4dc;
  --text-muted:#7a7570;--text-subtle:#4a4642;
}
body{font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--text);
  min-height:100vh;display:flex;flex-direction:column;-webkit-font-smoothing:antialiased;}

/* NAV */
.nav{padding:24px 40px;border-bottom:1px solid var(--border);
  display:flex;align-items:center;justify-content:space-between;}
.nav-brand{font-family:'Cormorant Garamond',serif;font-size:1.2rem;color:var(--text-subtle);}
.nav-back{text-decoration:none;font-size:11px;letter-spacing:.15em;text-transform:uppercase;
  color:var(--text-muted);display:flex;align-items:center;gap:8px;transition:color .2s;}
.nav-back:hover{color:var(--gold);}
.nav-back svg{width:14px;height:14px;}

/* LAYOUT */
.page{flex:1;display:flex;align-items:center;justify-content:center;padding:40px 24px;}

/* CARD */
.card{width:100%;max-width:420px;background:var(--bg-card);
  border:1px solid var(--border);padding:40px;position:relative;overflow:hidden;}
.card::before{content:'';position:absolute;top:0;left:0;right:0;height:1px;
  background:linear-gradient(90deg,transparent,var(--gold-dim),transparent);}

.card-label{font-size:10px;letter-spacing:.25em;text-transform:uppercase;
  color:var(--gold-dim);margin-bottom:10px;}
.card-title{font-family:'Cormorant Garamond',serif;font-size:2rem;font-weight:600;
  color:var(--text);margin-bottom:32px;line-height:1.1;}

/* TABS */
.tabs{display:flex;gap:0;margin-bottom:28px;border-bottom:1px solid var(--border);}
.tab{flex:1;padding:10px;text-align:center;font-size:12px;letter-spacing:.12em;
  text-transform:uppercase;color:var(--text-subtle);cursor:pointer;
  border-bottom:2px solid transparent;transition:all .2s;margin-bottom:-1px;}
.tab.active{color:var(--gold);border-bottom-color:var(--gold);}

/* FORM */
.form{display:flex;flex-direction:column;gap:16px;}
.field{display:flex;flex-direction:column;gap:6px;}
.field label{font-size:10px;letter-spacing:.15em;text-transform:uppercase;color:var(--text-subtle);}
.field input{background:#1a1a1a;border:1px solid var(--border);padding:12px 14px;
  font-size:14px;color:var(--text);font-family:'DM Sans',sans-serif;outline:none;
  transition:border-color .2s;}
.field input:focus{border-color:var(--gold-dim);}
.field input::placeholder{color:var(--text-subtle);}

.btn-submit{background:var(--gold);color:#0d0d0d;border:none;padding:14px;
  font-size:13px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;
  cursor:pointer;transition:background .2s;margin-top:4px;font-family:'DM Sans',sans-serif;}
.btn-submit:hover{background:var(--gold-light);}
.btn-submit:disabled{opacity:.5;cursor:not-allowed;}

/* MESSAGE */
#msg{font-size:13px;padding:10px 14px;display:none;margin-top:4px;}
#msg.error{background:rgba(192,57,43,.12);color:#e74c3c;border:1px solid rgba(192,57,43,.2);}
#msg.success{background:rgba(39,174,96,.12);color:#2ecc71;border:1px solid rgba(39,174,96,.2);}

/* DECO */
.deco{margin:32px auto 0;text-align:center;opacity:.06;}
.deco svg{width:120px;height:120px;}
</style>
</head>
<body>

<nav class="nav">
  <a class="nav-back" href="index.php">
    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
      <path d="M10 3L5 8l5 5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    Quay lại
  </a>
  <span class="nav-brand">Vaniel Speaker</span>
</nav>

<div class="page">
  <div class="card">
    <p class="card-label">Tài khoản</p>
    <h1 class="card-title">Chào mừng<br>trở lại</h1>

    <div class="tabs">
      <div class="tab active" data-tab="login">Đăng nhập</div>
      <div class="tab" data-tab="register">Đăng ký</div>
    </div>

    <!-- LOGIN FORM -->
    <div id="form-login" class="form">
      <div class="field">
        <label>Email</label>
        <input type="email" id="login-email" placeholder="your@email.com" autocomplete="email">
      </div>
      <div class="field">
        <label>Mật khẩu</label>
        <input type="password" id="login-pass" placeholder="••••••••" autocomplete="current-password">
      </div>
      <div id="msg"></div>
      <button class="btn-submit" id="login-btn">Đăng nhập</button>
    </div>

    <!-- REGISTER FORM -->
    <div id="form-register" class="form" style="display:none">
      <div class="field">
        <label>Họ tên</label>
        <input type="text" id="reg-name" placeholder="Nguyễn Văn A">
      </div>
      <div class="field">
        <label>Email</label>
        <input type="email" id="reg-email" placeholder="your@email.com" autocomplete="email">
      </div>
      <div class="field">
        <label>Mật khẩu</label>
        <input type="password" id="reg-pass" placeholder="Tối thiểu 6 ký tự" autocomplete="new-password">
      </div>
      <div class="field">
        <label>Số điện thoại</label>
        <input type="tel" id="reg-phone" placeholder="0912 345 678">
      </div>
      <div id="msg-reg"></div>
      <button class="btn-submit" id="reg-btn">Tạo tài khoản</button>
    </div>

    <div class="deco">
      <svg viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="60" cy="60" r="55" stroke="#c9a84c" stroke-width="0.5"/>
        <circle cx="60" cy="60" r="40" stroke="#c9a84c" stroke-width="0.5"/>
        <circle cx="60" cy="60" r="25" stroke="#c9a84c" stroke-width="0.5"/>
        <circle cx="60" cy="60" r="8" fill="#c9a84c" opacity="0.5"/>
      </svg>
    </div>
  </div>
</div>

<script>
const SUPABASE_URL = 'https://lvgmfunnudvmzicxtmkw.supabase.co';
const SUPABASE_KEY = 'sb_publishable_QCxhn8xMtRn3DfFrgRHQhQ_7XLMVAmc';

// ── TABS ──
document.querySelectorAll('.tab').forEach(tab => {
  tab.addEventListener('click', () => {
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
    const target = tab.dataset.tab;
    document.getElementById('form-login').style.display = target === 'login' ? 'flex' : 'none';
    document.getElementById('form-register').style.display = target === 'register' ? 'flex' : 'none';
    document.getElementById('msg').style.display = 'none';
    document.getElementById('msg-reg').style.display = 'none';
  });
});

function showMsg(id, text, type) {
  const el = document.getElementById(id);
  el.textContent = text;
  el.className = type;
  el.style.display = 'block';
}

// ── ĐĂNG NHẬP ──
document.getElementById('login-btn').addEventListener('click', async () => {
  const email = document.getElementById('login-email').value.trim();
  const pass  = document.getElementById('login-pass').value;
  if (!email || !pass) return showMsg('msg', 'Vui lòng nhập đầy đủ thông tin.', 'error');

  const btn = document.getElementById('login-btn');
  btn.disabled = true; btn.textContent = 'Đang đăng nhập...';

  const res = await fetch(`${SUPABASE_URL}/auth/v1/token?grant_type=password`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'apikey': SUPABASE_KEY },
    body: JSON.stringify({ email, password: pass })
  });
  const data = await res.json();

  if (data.access_token) {
    // Lưu session
    localStorage.setItem('vs_token', data.access_token);
    localStorage.setItem('vs_user', JSON.stringify(data.user));
    // Redirect
    const redirect = new URLSearchParams(location.search).get('redirect') || 'index.php';
    location.href = redirect;
  } else {
    const err = data.error_description || data.msg || 'Email hoặc mật khẩu không đúng.';
    showMsg('msg', err, 'error');
    btn.disabled = false; btn.textContent = 'Đăng nhập';
  }
});

// ── ĐĂNG KÝ ──
document.getElementById('reg-btn').addEventListener('click', async () => {
  const name  = document.getElementById('reg-name').value.trim();
  const email = document.getElementById('reg-email').value.trim();
  const pass  = document.getElementById('reg-pass').value;
  const phone = document.getElementById('reg-phone').value.trim();

  if (!name || !email || !pass) return showMsg('msg-reg', 'Vui lòng nhập đầy đủ thông tin.', 'error');
  if (pass.length < 6) return showMsg('msg-reg', 'Mật khẩu tối thiểu 6 ký tự.', 'error');

  const btn = document.getElementById('reg-btn');
  btn.disabled = true; btn.textContent = 'Đang tạo tài khoản...';

  const res = await fetch(`${SUPABASE_URL}/auth/v1/signup`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'apikey': SUPABASE_KEY },
    body: JSON.stringify({
      email, password: pass,
      data: { full_name: name, phone }
    })
  });
  const data = await res.json();

  if (data.access_token) {
    localStorage.setItem('vs_token', data.access_token);
    localStorage.setItem('vs_user', JSON.stringify(data.user));
    location.href = 'index.php';
  } else if (data.id) {
    // Confirm email bật → báo check email
    showMsg('msg-reg', 'Đăng ký thành công! Kiểm tra email để xác nhận.', 'success');
    btn.disabled = false; btn.textContent = 'Tạo tài khoản';
  } else {
    const err = data.error_description || data.msg || 'Đăng ký thất bại.';
    showMsg('msg-reg', err, 'error');
    btn.disabled = false; btn.textContent = 'Tạo tài khoản';
  }
});

// Enter key
document.addEventListener('keydown', e => {
  if (e.key !== 'Enter') return;
  const activeTab = document.querySelector('.tab.active').dataset.tab;
  if (activeTab === 'login') document.getElementById('login-btn').click();
  else document.getElementById('reg-btn').click();
});
</script>
</body>
</html>
