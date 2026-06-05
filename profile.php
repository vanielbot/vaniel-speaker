<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Tài khoản — Vaniel Speaker</title>
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
  min-height:100vh;-webkit-font-smoothing:antialiased;}

.nav{padding:24px 40px;border-bottom:1px solid var(--border);
  display:flex;align-items:center;justify-content:space-between;}
.nav-brand{font-family:'Cormorant Garamond',serif;font-size:1.2rem;color:var(--text-subtle);}
.nav-back{text-decoration:none;font-size:11px;letter-spacing:.15em;text-transform:uppercase;
  color:var(--text-muted);display:flex;align-items:center;gap:8px;transition:color .2s;}
.nav-back:hover{color:var(--gold);}
.nav-back svg{width:14px;height:14px;}

.page{max-width:720px;margin:0 auto;padding:60px 40px 80px;}

.page-label{font-size:10px;letter-spacing:.25em;text-transform:uppercase;color:var(--gold-dim);margin-bottom:10px;}
.page-title{font-family:'Cormorant Garamond',serif;font-size:2.2rem;font-weight:600;
  color:var(--text);margin-bottom:40px;}

/* SECTION */
.section{background:var(--bg-card);border:1px solid var(--border);
  padding:28px;margin-bottom:20px;position:relative;overflow:hidden;}
.section::before{content:'';position:absolute;top:0;left:0;right:0;height:1px;
  background:linear-gradient(90deg,transparent,var(--gold-dim),transparent);}
.section-title{font-size:11px;letter-spacing:.2em;text-transform:uppercase;
  color:var(--text-subtle);margin-bottom:20px;}

.fields{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
.field{display:flex;flex-direction:column;gap:6px;}
.field.full{grid-column:1/-1;}
.field label{font-size:10px;letter-spacing:.15em;text-transform:uppercase;color:var(--text-subtle);}
.field input{background:#1a1a1a;border:1px solid var(--border);padding:11px 14px;
  font-size:14px;color:var(--text);font-family:'DM Sans',sans-serif;outline:none;
  transition:border-color .2s;}
.field input:focus{border-color:var(--gold-dim);}
.field input:disabled{opacity:.4;cursor:not-allowed;}

.btn-save{background:var(--gold);color:#0d0d0d;border:none;padding:12px 28px;
  font-size:12px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;
  cursor:pointer;transition:background .2s;font-family:'DM Sans',sans-serif;margin-top:8px;}
.btn-save:hover{background:var(--gold-light);}
.btn-save:disabled{opacity:.5;cursor:not-allowed;}

#save-msg{font-size:12px;padding:8px 12px;display:none;margin-top:8px;}
#save-msg.success{background:rgba(39,174,96,.1);color:#2ecc71;border:1px solid rgba(39,174,96,.2);}
#save-msg.error{background:rgba(192,57,43,.1);color:#e74c3c;border:1px solid rgba(192,57,43,.2);}

/* AVATAR */
.avatar-big{width:64px;height:64px;border-radius:50%;background:var(--gold);
  color:#0d0d0d;font-size:24px;font-weight:700;display:flex;align-items:center;
  justify-content:center;margin-bottom:16px;}

/* LOGOUT */
.btn-logout{background:none;border:1px solid rgba(192,57,43,.3);color:#c0392b;
  padding:10px 20px;font-size:11px;letter-spacing:.12em;text-transform:uppercase;
  cursor:pointer;transition:all .2s;font-family:'DM Sans',sans-serif;}
.btn-logout:hover{background:rgba(192,57,43,.08);border-color:#c0392b;}

@media(max-width:600px){
  .page{padding:40px 20px;}
  .fields{grid-template-columns:1fr;}
  .field.full{grid-column:1;}
  .nav{padding:20px 20px;}
}
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

<div class="page" id="page-content" style="display:none">

  <p class="page-label">Cá nhân</p>
  <h1 class="page-title">Tài khoản của bạn</h1>

  <!-- AVATAR + EMAIL -->
  <div class="section">
    <div id="avatar-display" class="avatar-big"></div>
    <div style="font-size:13px;color:var(--text-muted)" id="email-display"></div>
  </div>

  <!-- THÔNG TIN -->
  <div class="section">
    <p class="section-title">Thông tin cá nhân</p>
    <div class="fields">
      <div class="field full">
        <label>Họ và tên</label>
        <input type="text" id="p-name" placeholder="Nguyễn Văn A">
      </div>
      <div class="field">
        <label>Số điện thoại</label>
        <input type="tel" id="p-phone" placeholder="0912 345 678">
      </div>
      <div class="field full">
        <label>Địa chỉ giao hàng</label>
        <input type="text" id="p-address" placeholder="123 Đường ABC, Quận 1, TP.HCM">
      </div>
    </div>
    <div id="save-msg"></div>
    <button class="btn-save" id="save-btn">Lưu thay đổi</button>
  </div>

  <!-- ĐĂNG XUẤT -->
  <div class="section">
    <p class="section-title">Phiên đăng nhập</p>
    <button class="btn-logout" id="logout-btn">Đăng xuất</button>
  </div>

</div>

<!-- Chưa đăng nhập -->
<div id="not-logged" style="display:none;text-align:center;padding:80px 24px;">
  <p style="color:var(--text-muted);margin-bottom:20px;">Bạn chưa đăng nhập.</p>
  <a href="login.php" style="color:var(--gold);text-decoration:none;font-size:13px;
    letter-spacing:.1em;text-transform:uppercase;border:1px solid var(--gold-dim);
    padding:10px 24px;">Đăng nhập ngay</a>
</div>

<script src="auth.js"></script>
<script>
const SUPABASE_URL = 'https://lvgmfunnudvmzicxtmkw.supabase.co';
const SUPABASE_KEY = 'sb_publishable_QCxhn8xMtRn3DfFrgRHQhQ_7XLMVAmc';

window.addEventListener('DOMContentLoaded', () => {
  if (!Auth.isLoggedIn()) {
    document.getElementById('not-logged').style.display = 'block';
    return;
  }

  document.getElementById('page-content').style.display = 'block';

  const user = Auth.getUser();
  const meta = user?.user_metadata || {};

  // Hiển thị
  const name = meta.full_name || user.email || '';
  document.getElementById('avatar-display').textContent = name.charAt(0).toUpperCase();
  document.getElementById('email-display').textContent = user.email;
  document.getElementById('p-name').value    = meta.full_name || '';
  document.getElementById('p-phone').value   = meta.phone    || '';
  document.getElementById('p-address').value = meta.address  || '';

  // Lưu
  document.getElementById('save-btn').addEventListener('click', async () => {
    const btn = document.getElementById('save-btn');
    btn.disabled = true; btn.textContent = 'Đang lưu...';

    const res = await fetch(`${SUPABASE_URL}/auth/v1/user`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'apikey': SUPABASE_KEY,
        'Authorization': 'Bearer ' + Auth.getToken()
      },
      body: JSON.stringify({ data: {
        full_name: document.getElementById('p-name').value.trim(),
        phone:     document.getElementById('p-phone').value.trim(),
        address:   document.getElementById('p-address').value.trim()
      }})
    });

    const data = await res.json();
    btn.disabled = false; btn.textContent = 'Lưu thay đổi';

    if (data.id) {
      // Cập nhật localStorage
      localStorage.setItem('vs_user', JSON.stringify(data));
      showMsg('Đã lưu thông tin!', 'success');
    } else {
      showMsg('Lưu thất bại, thử lại nhé.', 'error');
    }
  });

  // Đăng xuất
  document.getElementById('logout-btn').addEventListener('click', () => Auth.logout());
});

function showMsg(text, type) {
  const el = document.getElementById('save-msg');
  el.textContent = text;
  el.className = type;
  el.style.display = 'block';
  setTimeout(() => el.style.display = 'none', 3000);
}
</script>
</body>
</html>
