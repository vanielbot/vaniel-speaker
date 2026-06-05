// ── VANIEL SPEAKER — AUTH ENGINE ──────────────────────────────────

const Auth = (() => {

    function getToken() { return localStorage.getItem('vs_token'); }

    function getUser() {
        try { return JSON.parse(localStorage.getItem('vs_user')); }
        catch { return null; }
    }

    function isLoggedIn() { return !!getToken(); }

    function logout() {
        localStorage.removeItem('vs_token');
        localStorage.removeItem('vs_user');
        location.href = 'index.php';
    }

    function getName() {
        const u = getUser();
        return u?.user_metadata?.full_name || u?.email || '';
    }

    function getPhone() {
        const u = getUser();
        return u?.user_metadata?.phone || '';
    }

    function getEmail() {
        const u = getUser();
        return u?.email || '';
    }

    return { getToken, getUser, isLoggedIn, logout, getName, getPhone, getEmail };
})();


// ── INJECT USER UI vào header/nav ─────────────────────────────────
function initAuthUI() {
    const wrap = document.getElementById('auth-ui');
    if (!wrap) return;

    if (Auth.isLoggedIn()) {
        const name = Auth.getName();
        wrap.innerHTML = `
<div id="auth-user-wrap">
    <div id="auth-avatar">${name.charAt(0).toUpperCase()}</div>
    <span id="auth-name">${name}</span>
    <div id="auth-dropdown">
        <a href="profile.php">Tài khoản</a>
        <a href="#" id="auth-logout-btn">Đăng xuất</a>
    </div>
</div>`;
        document.getElementById('auth-logout-btn')
            .addEventListener('click', e => { e.preventDefault(); Auth.logout(); });
    } else {
        wrap.innerHTML = `<a href="login.php" id="auth-login-link">
    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
        <circle cx="10" cy="7" r="3"/>
        <path d="M3 17c0-3.3 3.1-6 7-6s7 2.7 7 6" stroke-linecap="round"/>
    </svg>
    Đăng nhập
</a>`;
    }
}

// ── CSS cho auth UI ───────────────────────────────────────────────
(function injectAuthCSS() {
    const s = document.createElement('style');
    s.textContent = `
#auth-ui { display:flex; align-items:center; }

#auth-login-link {
    display:inline-flex; align-items:center; gap:7px;
    text-decoration:none; font-size:11px; letter-spacing:.15em;
    text-transform:uppercase; color:var(--text-muted);
    padding:8px 14px; border:1px solid var(--border);
    transition:all .2s;
}
#auth-login-link:hover { border-color:var(--gold-dim); color:var(--gold); }
#auth-login-link svg { width:16px; height:16px; }

#auth-user-wrap {
    display:flex; align-items:center; gap:10px;
    cursor:pointer; position:relative; padding:6px 12px;
    border:1px solid var(--border); transition:border-color .2s;
}
#auth-user-wrap:hover { border-color:var(--border-hover); }
#auth-user-wrap:hover #auth-dropdown { display:flex; }

#auth-avatar {
    width:28px; height:28px; border-radius:50%;
    background:var(--gold); color:#0d0d0d;
    font-size:12px; font-weight:700;
    display:flex; align-items:center; justify-content:center;
    flex-shrink:0;
}

#auth-name {
    font-size:12px; color:var(--text-muted); max-width:120px;
    overflow:hidden; text-overflow:ellipsis; white-space:nowrap;
}

#auth-dropdown {
    display:none; flex-direction:column;
    position:absolute; top:calc(100% + 4px); right:0;
    background:#1a1a1a; border:1px solid var(--border);
    min-width:150px; z-index:100;
}
#auth-dropdown a {
    padding:11px 16px; font-size:12px; letter-spacing:.08em;
    text-transform:uppercase; text-decoration:none;
    color:var(--text-muted); transition:all .15s;
    border-bottom:1px solid var(--border);
}
#auth-dropdown a:last-child { border-bottom:none; }
#auth-dropdown a:hover { background:rgba(201,168,76,.06); color:var(--gold); }
`;
    document.head.appendChild(s);
})();

window.addEventListener('DOMContentLoaded', initAuthUI);
