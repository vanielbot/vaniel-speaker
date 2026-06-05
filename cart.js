// ── VANIEL SPEAKER — CART ENGINE ──────────────────────────────────
// Dùng localStorage để lưu giỏ hàng giữa các trang

const Cart = (() => {

    const KEY = 'vaniel_cart';

    function load() {
        try { return JSON.parse(localStorage.getItem(KEY)) || []; }
        catch { return []; }
    }

    function save(items) {
        localStorage.setItem(KEY, JSON.stringify(items));
        document.dispatchEvent(new Event('cart:updated'));
    }

    function add(product) {
        // product = { ma_sp, ten_sp, hang, gia }
        const items = load();
        const idx = items.findIndex(i => i.ma_sp === product.ma_sp);
        if (idx >= 0) {
            items[idx].qty += 1;
        } else {
            items.push({ ...product, qty: 1 });
        }
        save(items);
    }

    function remove(ma_sp) {
        save(load().filter(i => i.ma_sp !== ma_sp));
    }

    function changeQty(ma_sp, delta) {
        const items = load();
        const idx = items.findIndex(i => i.ma_sp === ma_sp);
        if (idx < 0) return;
        items[idx].qty = Math.max(1, items[idx].qty + delta);
        save(items);
    }

    function clear() { save([]); }

    function total() {
        return load().reduce((s, i) => s + i.gia * i.qty, 0);
    }

    function count() {
        return load().reduce((s, i) => s + i.qty, 0);
    }

    return { load, add, remove, changeQty, clear, total, count };
})();


// ── CART ICON BADGE (cập nhật số lượng trên icon) ─────────────────
function updateCartBadge() {
    const badge = document.getElementById('cart-badge');
    if (!badge) return;
    const n = Cart.count();
    badge.textContent = n;
    badge.style.display = n > 0 ? 'flex' : 'none';
}

document.addEventListener('cart:updated', updateCartBadge);
window.addEventListener('DOMContentLoaded', updateCartBadge);


// ── CART MODAL ────────────────────────────────────────────────────
function buildCartModal() {
    if (document.getElementById('cart-modal')) return; // đã có rồi

    const overlay = document.createElement('div');
    overlay.id = 'cart-modal';
    overlay.innerHTML = `
<div id="cart-backdrop"></div>
<div id="cart-panel">

  <div id="cart-panel-header">
    <div id="cart-panel-title">
      <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
        <path d="M3 3h2l.8 4m0 0L7 13h9l2-6H5.8z" stroke-linecap="round" stroke-linejoin="round"/>
        <circle cx="7.5" cy="16.5" r="1" fill="currentColor" stroke="none"/>
        <circle cx="15.5" cy="16.5" r="1" fill="currentColor" stroke="none"/>
      </svg>
      Giỏ hàng
    </div>
    <button id="cart-close">
      <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
        <path d="M3 3l10 10M13 3L3 13" stroke-linecap="round"/>
      </svg>
    </button>
  </div>

  <div id="cart-items"></div>

  <div id="cart-panel-footer">
    <div id="cart-total-row">
      <span id="cart-total-label">Tổng cộng</span>
      <span id="cart-total-value">0 đ</span>
    </div>
    <button id="cart-checkout-btn">
      Thanh toán QR Momo
      <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
        <path d="M3 8h10M9 4l4 4-4 4" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </button>
    <button id="cart-clear-btn">Xoá giỏ hàng</button>
  </div>

</div>

<!-- ── QR PAYMENT MODAL ── -->
<div id="qr-modal">
  <div id="qr-box">
    <button id="qr-close">
      <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
        <path d="M3 3l10 10M13 3L3 13" stroke-linecap="round"/>
      </svg>
    </button>

    <!-- STEP 1: Form thông tin giao hàng -->
    <div id="qr-step-info">
      <p id="qr-label">Thông tin giao hàng</p>
      <div id="qr-owner">Vui lòng điền đầy đủ trước khi thanh toán</div>

      <div class="qr-field">
        <label>Số điện thoại <span class="qr-required">*</span></label>
        <input type="tel" id="qr-phone" placeholder="0912 345 678" maxlength="15">
        <div class="qr-field-err" id="qr-phone-err">Vui lòng nhập số điện thoại</div>
      </div>

      <div class="qr-field">
        <label>Địa chỉ giao hàng <span class="qr-required">*</span></label>
        <input type="text" id="qr-address" placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/TP">
        <div class="qr-field-err" id="qr-address-err">Vui lòng nhập địa chỉ giao hàng</div>
      </div>

      <div id="qr-info-errmsg"></div>
      <button id="qr-next-btn">Tiếp tục thanh toán
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
          <path d="M3 8h10M9 4l4 4-4 4" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>
    </div>

    <!-- STEP 2: QR -->
    <div id="qr-step-qr" style="display:none">
      <p id="qr-label2">Quét mã để thanh toán</p>
      <div id="qr-owner2">PHẠM TUẤN ANH — Vaniel Speaker</div>
      <div id="qr-img-wrap">
        <img src="qr_momott.jpg" alt="QR Momo" id="qr-img">
      </div>
      <div id="qr-amount"></div>
      <div id="qr-delivery-summary"></div>
      <p id="qr-note">Vui lòng ghi nội dung chuyển khoản là <strong>tên + số điện thoại</strong> của bạn.</p>
      <button id="qr-done-btn">Tôi đã chuyển khoản</button>
    </div>
  </div>
</div>
`;

    document.body.appendChild(overlay);

    // ── CSS ──
    const style = document.createElement('style');
    style.textContent = `
/* CART OVERLAY */
#cart-modal { display:none; }
#cart-modal.open { display:block; }

#cart-backdrop {
  position:fixed; inset:0; background:rgba(0,0,0,.65); z-index:2000;
  animation: fadeIn .2s ease;
}

#cart-panel {
  position:fixed; top:0; right:0; bottom:0; width:420px; max-width:100vw;
  background:#141414; border-left:1px solid rgba(201,168,76,.18);
  display:flex; flex-direction:column; z-index:2001;
  animation: slideIn .25s ease;
}

@keyframes fadeIn { from{opacity:0} to{opacity:1} }
@keyframes slideIn { from{transform:translateX(100%)} to{transform:translateX(0)} }

#cart-panel-header {
  padding:22px 24px; border-bottom:1px solid rgba(201,168,76,.15);
  display:flex; align-items:center; justify-content:space-between;
}

#cart-panel-title {
  font-family:'Cormorant Garamond',serif; font-size:1.3rem; font-weight:600;
  color:#e8e4dc; display:flex; align-items:center; gap:10px;
}
#cart-panel-title svg { width:20px; height:20px; color:#c9a84c; }

#cart-close {
  background:none; border:none; cursor:pointer; color:#4a4642; padding:4px;
  transition:color .2s;
}
#cart-close:hover { color:#e8e4dc; }
#cart-close svg { width:18px; height:18px; }

/* ITEMS */
#cart-items {
  flex:1; overflow-y:auto; padding:16px 24px;
  scrollbar-width:thin; scrollbar-color:rgba(201,168,76,.2) transparent;
}

.cart-empty {
  text-align:center; padding:60px 0; color:#4a4642;
  font-size:14px; letter-spacing:.05em;
}
.cart-empty svg { width:48px; height:48px; margin:0 auto 16px; display:block; opacity:.3; }

.cart-item {
  padding:16px 0; border-bottom:1px solid rgba(201,168,76,.1);
  display:grid; grid-template-columns:1fr auto; gap:8px 16px;
  align-items:center;
  animation: fadeUp .2s ease both;
}
@keyframes fadeUp { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }

.cart-item-name {
  font-family:'Cormorant Garamond',serif; font-size:1rem; font-weight:600;
  color:#e8e4dc; line-height:1.2;
}
.cart-item-brand { font-size:10px; color:#7a6230; letter-spacing:.15em; text-transform:uppercase; margin-top:3px; }
.cart-item-price { font-family:'Cormorant Garamond',serif; font-size:1rem; color:#e8c97a; text-align:right; }

.cart-item-qty {
  display:flex; align-items:center; gap:8px; margin-top:8px;
}
.qty-btn {
  width:26px; height:26px; border:1px solid rgba(201,168,76,.3); background:none;
  color:#c9a84c; cursor:pointer; font-size:14px; display:flex; align-items:center;
  justify-content:center; transition:all .15s;
}
.qty-btn:hover { background:#c9a84c; color:#0d0d0d; }
.qty-num { font-size:13px; font-weight:500; color:#e8e4dc; min-width:20px; text-align:center; }

.cart-item-remove {
  background:none; border:none; cursor:pointer; color:#4a4642;
  font-size:11px; letter-spacing:.1em; text-transform:uppercase; padding:4px;
  transition:color .2s; margin-top:4px; text-align:left;
}
.cart-item-remove:hover { color:#c0392b; }

/* FOOTER */
#cart-panel-footer {
  padding:20px 24px; border-top:1px solid rgba(201,168,76,.15);
  display:flex; flex-direction:column; gap:10px;
}

#cart-total-row {
  display:flex; justify-content:space-between; align-items:baseline;
  padding-bottom:12px; border-bottom:1px solid rgba(201,168,76,.1);
}
#cart-total-label { font-size:11px; text-transform:uppercase; letter-spacing:.15em; color:#4a4642; }
#cart-total-value {
  font-family:'Cormorant Garamond',serif; font-size:1.6rem;
  font-weight:600; color:#e8c97a;
}

#cart-checkout-btn {
  background:#c9a84c; color:#0d0d0d; border:none; padding:14px 20px;
  font-size:13px; font-weight:600; letter-spacing:.1em; text-transform:uppercase;
  cursor:pointer; display:flex; align-items:center; justify-content:center; gap:10px;
  transition:background .2s;
}
#cart-checkout-btn:hover { background:#e8c97a; }
#cart-checkout-btn svg { width:16px; height:16px; }
#cart-checkout-btn:disabled { opacity:.4; cursor:not-allowed; }

#cart-clear-btn {
  background:none; border:1px solid rgba(201,168,76,.15); color:#7a7570;
  padding:10px; font-size:12px; letter-spacing:.08em; text-transform:uppercase;
  cursor:pointer; transition:all .2s;
}
#cart-clear-btn:hover { border-color:#c0392b; color:#c0392b; }

/* QR MODAL */
#qr-modal {
  position:fixed; inset:0; background:rgba(0,0,0,.8);
  z-index:3000; display:none; align-items:center; justify-content:center;
  animation: fadeIn .2s ease;
}
#qr-modal.open { display:flex; }

#qr-box {
  background:#141414; border:1px solid rgba(201,168,76,.25);
  padding:36px 32px; max-width:380px; width:90%;
  text-align:center; position:relative;
  animation: popIn .25s ease;
  max-height:90vh; overflow-y:auto;
}
@keyframes popIn { from{opacity:0;transform:scale(.9)} to{opacity:1;transform:scale(1)} }

#qr-close {
  position:absolute; top:14px; right:14px;
  background:none; border:none; cursor:pointer; color:#4a4642;
}
#qr-close:hover { color:#e8e4dc; }
#qr-close svg { width:16px; height:16px; }

#qr-label, #qr-label2 {
  font-size:10px; letter-spacing:.2em; text-transform:uppercase;
  color:#7a6230; margin-bottom:6px;
}
#qr-owner, #qr-owner2 {
  font-family:'Cormorant Garamond',serif; font-size:1.05rem; font-weight:600;
  color:#e8e4dc; margin-bottom:20px;
}

/* FORM FIELDS */
.qr-field {
  text-align:left; margin-bottom:14px;
}
.qr-field label {
  display:block; font-size:10px; letter-spacing:.15em; text-transform:uppercase;
  color:#4a4642; margin-bottom:5px;
}
.qr-required { color:#c9a84c; }
.qr-field input {
  width:100%; background:#1a1a1a; border:1px solid rgba(201,168,76,.15);
  padding:10px 12px; font-size:13px; color:#e8e4dc;
  font-family:'DM Sans',sans-serif; outline:none; transition:border-color .2s;
}
.qr-field input:focus { border-color:#7a6230; }
.qr-field input.error { border-color:#c0392b; }
.qr-field-err {
  font-size:11px; color:#e74c3c; margin-top:4px; display:none;
}
.qr-field-err.show { display:block; }

#qr-info-errmsg {
  font-size:12px; color:#e74c3c; margin-bottom:10px; display:none;
}

#qr-next-btn {
  background:#c9a84c; color:#0d0d0d; border:none; padding:13px 20px;
  font-size:12px; font-weight:600; letter-spacing:.1em; text-transform:uppercase;
  cursor:pointer; width:100%; transition:background .2s;
  display:flex; align-items:center; justify-content:center; gap:8px;
  font-family:'DM Sans',sans-serif;
}
#qr-next-btn:hover { background:#e8c97a; }
#qr-next-btn svg { width:14px; height:14px; }

#qr-img-wrap {
  background:#fff; padding:12px; display:inline-block; margin-bottom:16px;
}
#qr-img { width:200px; height:200px; object-fit:contain; display:block; }

#qr-amount {
  font-family:'Cormorant Garamond',serif; font-size:1.8rem;
  font-weight:600; color:#e8c97a; margin-bottom:8px;
}

#qr-delivery-summary {
  font-size:12px; color:#7a7570; margin-bottom:10px; line-height:1.6;
  background:rgba(201,168,76,.05); border:1px solid rgba(201,168,76,.1);
  padding:8px 12px; text-align:left;
}

#qr-note {
  font-size:12px; color:#7a7570; line-height:1.7; margin-bottom:20px;
}
#qr-note strong { color:#c9a84c; }

#qr-done-btn {
  background:#c9a84c; color:#0d0d0d; border:none; padding:12px 28px;
  font-size:13px; font-weight:600; letter-spacing:.1em; text-transform:uppercase;
  cursor:pointer; width:100%; transition:background .2s;
}
#qr-done-btn:hover { background:#e8c97a; }
`;
    document.head.appendChild(style);

    // ── RENDER ITEMS ──
    function renderItems() {
        const items = Cart.load();
        const el = document.getElementById('cart-items');
        if (!items.length) {
            el.innerHTML = `<div class="cart-empty">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
    <path d="M3 3h2l.8 4m0 0L7 13h9l2-6H5.8z" stroke-linecap="round" stroke-linejoin="round"/>
    <circle cx="7.5" cy="16.5" r="1" fill="currentColor" stroke="none"/>
    <circle cx="15.5" cy="16.5" r="1" fill="currentColor" stroke="none"/>
  </svg>
  Giỏ hàng trống
</div>`;
        } else {
            el.innerHTML = items.map(i => `
<div class="cart-item">
  <div>
    <div class="cart-item-name">${i.ten_sp}</div>
    <div class="cart-item-brand">${i.hang}</div>
    <div class="cart-item-qty">
      <button class="qty-btn" data-id="${i.ma_sp}" data-d="-1">−</button>
      <span class="qty-num">${i.qty}</span>
      <button class="qty-btn" data-id="${i.ma_sp}" data-d="1">+</button>
      <button class="cart-item-remove" data-id="${i.ma_sp}">Xoá</button>
    </div>
  </div>
  <div class="cart-item-price">${(i.gia * i.qty).toLocaleString('vi-VN')} đ</div>
</div>`).join('');
        }
        document.getElementById('cart-total-value').textContent =
            Cart.total().toLocaleString('vi-VN') + ' đ';
        document.getElementById('cart-checkout-btn').disabled = items.length === 0;

        // events
        el.querySelectorAll('.qty-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                Cart.changeQty(btn.dataset.id, parseInt(btn.dataset.d));
                renderItems();
            });
        });
        el.querySelectorAll('.cart-item-remove').forEach(btn => {
            btn.addEventListener('click', () => {
                Cart.remove(btn.dataset.id);
                renderItems();
            });
        });
    }

    document.addEventListener('cart:updated', renderItems);

    // open / close
    document.getElementById('cart-backdrop').addEventListener('click', closeCart);
    document.getElementById('cart-close').addEventListener('click', closeCart);

    document.getElementById('cart-clear-btn').addEventListener('click', () => {
        Cart.clear(); renderItems();
    });

    // checkout → Step 1 (form info)
    document.getElementById('cart-checkout-btn').addEventListener('click', () => {
        // Reset về step 1 mỗi lần mở
        document.getElementById('qr-step-info').style.display = 'block';
        document.getElementById('qr-step-qr').style.display = 'none';
        document.getElementById('qr-phone').classList.remove('error');
        document.getElementById('qr-address').classList.remove('error');
        document.getElementById('qr-phone-err').classList.remove('show');
        document.getElementById('qr-address-err').classList.remove('show');
        document.getElementById('qr-modal').classList.add('open');
    });

    // Step 1 → Step 2: validate rồi hiện QR
    document.getElementById('qr-next-btn').addEventListener('click', () => {
        const phone = document.getElementById('qr-phone').value.trim();
        const address = document.getElementById('qr-address').value.trim();
        let valid = true;

        if (!phone) {
            document.getElementById('qr-phone').classList.add('error');
            document.getElementById('qr-phone-err').classList.add('show');
            valid = false;
        } else {
            document.getElementById('qr-phone').classList.remove('error');
            document.getElementById('qr-phone-err').classList.remove('show');
        }

        if (!address) {
            document.getElementById('qr-address').classList.add('error');
            document.getElementById('qr-address-err').classList.add('show');
            valid = false;
        } else {
            document.getElementById('qr-address').classList.remove('error');
            document.getElementById('qr-address-err').classList.remove('show');
        }

        if (!valid) return;

        // Chuyển sang step QR
        document.getElementById('qr-amount').textContent =
            Cart.total().toLocaleString('vi-VN') + ' đ';
        document.getElementById('qr-delivery-summary').innerHTML =
            `📞 <strong style="color:#c9a84c">${phone}</strong><br>📍 ${address}`;
        document.getElementById('qr-step-info').style.display = 'none';
        document.getElementById('qr-step-qr').style.display = 'block';
    });

    document.getElementById('qr-close').addEventListener('click', () => {
        document.getElementById('qr-modal').classList.remove('open');
    });

    document.getElementById('qr-done-btn').addEventListener('click', () => {
        document.getElementById('qr-modal').classList.remove('open');
        closeCart();
        Cart.clear();
        showToast('Cảm ơn bạn! Đơn hàng sẽ được xác nhận sớm nhất.');
    });

    function closeCart() {
        document.getElementById('cart-modal').classList.remove('open');
    }

    // render lần đầu
    renderItems();
}

// ── OPEN CART ─────────────────────────────────────────────────────
function openCart() {
    buildCartModal();
    document.getElementById('cart-modal').classList.add('open');
}

// ── TOAST ─────────────────────────────────────────────────────────
function showToast(msg) {
    let t = document.getElementById('vs-toast');
    if (!t) {
        t = document.createElement('div');
        t.id = 'vs-toast';
        const s = document.createElement('style');
        s.textContent = `
#vs-toast {
  position:fixed; bottom:24px; left:50%; transform:translateX(-50%) translateY(20px);
  background:#c9a84c; color:#0d0d0d; padding:12px 24px;
  font-size:13px; font-weight:500; letter-spacing:.05em;
  opacity:0; transition:all .3s ease; z-index:9999; white-space:nowrap;
  pointer-events:none;
}
#vs-toast.show { opacity:1; transform:translateX(-50%) translateY(0); }`;
        document.head.appendChild(s);
        document.body.appendChild(t);
    }
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3500);
}

// ── ADD-TO-CART BUTTON HELPER ──────────────────────────────────────
// Gọi hàm này sau khi DOM load để gắn sự kiện cho các nút .btn-add-cart
function initAddToCartButtons() {
    document.querySelectorAll('[data-add-cart]').forEach(btn => {
        btn.addEventListener('click', () => {
            const el = btn.closest('[data-product]') || btn;
            Cart.add({
                ma_sp: btn.dataset.id,
                ten_sp: btn.dataset.name,
                hang: btn.dataset.brand,
                gia: parseInt(btn.dataset.price)
            });
            showToast('Đã thêm vào giỏ hàng!');
        });
    });
}
