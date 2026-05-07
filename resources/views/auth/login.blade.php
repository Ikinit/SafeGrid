<x-guest-layout>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap');

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'Nunito', 'Segoe UI', sans-serif;
        overflow: hidden;
    }

    #waveOverlay {
        position: fixed;
        inset: 0;
        z-index: 200;
        pointer-events: none;
        overflow: hidden;
    }

    #waveSvg {
        position: absolute;
        top: 0;
        left: 0;
        height: 100vh;
        width: 220vw;
        will-change: left;
    }

    @keyframes waveReveal {
        /* slide LEFT */
        from { left: 0;      }
        to   { left: -110vw; }
    }

    @keyframes waveCover {
        /* slide RIGHT */
        from { left: -110vw; }
        to   { left: 0;      }
    }

    /* ─────────────────────────────────────────────────
       PAGE LAYOUT
    ───────────────────────────────────────────────── */
    .page {
        position: relative;
        min-height: 100vh;
        width: 100%;
        min-width: 670px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        overflow: hidden;
    }

    .bg-wave {
        position: absolute;
        left: 0; top: 0;
        height: 100vh;
        width: auto;
        z-index: 0;
        pointer-events: none;
        user-select: none;
    }

    .top-nav {
        position: absolute;
        top: 2rem; right: 3rem;
        display: flex;
        gap: 1.5rem;
        z-index: 10;
    }
    .top-nav a {
        font-size: .875rem;
        font-weight: 700;
        color: #475569;
        text-decoration: none;
        transition: color .2s;
    }
    .top-nav a:hover { color: #1e3a5f; }

    .content-wrap {
        position: relative;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5rem;
        padding: 2rem 3rem;
        width: 100%;
        max-width: 1100px;
    }
    @media (max-width: 1000px) { .content-wrap { flex-direction: column; gap: 2rem; } }

    /* ── Branding ── */
    .branding {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        max-width: 420px;
        width: 100%;
        opacity: 0;
        transform: translateX(-20px);
        transition: opacity .55s ease, transform .55s ease;
    }
    .branding.visible { opacity: 1; transform: none; }

    .branding-logo {
        height: 88px; width: auto;
        object-fit: contain;
        margin-bottom: 1.75rem;
        align-self: center;
    }
    .branding h1 {
        font-size: clamp(2.75rem, 5vw, 4.25rem);
        font-weight: 900;
        color: #1e3a5f;
        line-height: 1.1;
        letter-spacing: -0.02em;
        margin-bottom: 1.25rem;
    }
    .branding h1 span {
        background: linear-gradient(90deg, #3b82f6, #7BF0FF);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .branding p {
        font-size: .9rem; color: #64748b;
        line-height: 1.7; font-weight: 500;
        text-align: justify;
    }

    /* ── Auth card ── */
    .auth-card {
        background: #fff;
        border-radius: 1.25rem;
        padding: 2.25rem 2rem;
        width: 100%; max-width: 340px;
        box-shadow: 0 4px 24px rgba(59,130,246,.12), 0 1px 4px rgba(0,0,0,.05);
        border: 1px solid #e0eeff;
        opacity: 0;
        transform: translateY(16px);
        transition: opacity .55s ease .1s, transform .55s ease .1s;
    }
    .auth-card.visible { opacity: 1; transform: none; }
    .auth-card h2 {
        text-align: center;
        font-size: 1.75rem; font-weight: 900;
        color: #1e3a5f; margin-bottom: 1.5rem;
        letter-spacing: -0.01em;
    }

    .field-group { margin-bottom: 1rem; }
    .field-group label {
        display: block;
        font-size: .75rem; font-weight: 700;
        color: #64748b; margin-bottom: .35rem;
        text-transform: uppercase; letter-spacing: .05em;
    }
    .field-group input {
        width: 100%;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: .625rem;
        padding: .55rem .85rem;
        font-size: .875rem; color: #1e293b;
        font-family: inherit;
        transition: border-color .2s, box-shadow .2s;
        outline: none;
    }
    .field-group input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,.15);
        background: #fff;
    }
    .field-group input::placeholder { color: #94a3b8; }

    .error-banner {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: .5rem;
        padding: .6rem .85rem;
        margin-bottom: 1rem;
        font-size: .78rem;
        color: #b91c1c;
    }

    .btn-submit {
        width: 100%;
        background: linear-gradient(90deg, #7BF0FF, #7E98FF);
        border: none; border-radius: 999px;
        padding: .75rem;
        font-size: .875rem; font-weight: 800;
        color: #fff; letter-spacing: .08em;
        cursor: pointer; margin-top: 1.5rem;
        box-shadow: 0 4px 14px rgba(123,144,255,.35);
        transition: opacity .2s, transform .15s, box-shadow .2s;
        font-family: inherit;
    }
    .btn-submit:hover { opacity: .9; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(123,144,255,.45); }
    .btn-submit:active { transform: none; }
    .btn-submit:disabled { opacity: .65; cursor: default; transform: none; }
</style>

{{-- ══ Wave Overlay — starts covering the screen (left:0) ══ --}}
<div id="waveOverlay">
    <svg id="waveSvg" viewBox="0 0 2200 900" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <linearGradient id="wg" x1="0%" y1="0%" x2="100%" y2="0%">
                <stop offset="0%"   stop-color="#1d4ed8"/>
                <stop offset="60%"  stop-color="#3b82f6"/>
                <stop offset="100%" stop-color="#7BF0FF"/>
            </linearGradient>
        </defs>
        <path d="M0,0 L1150,0 C1150,0 1240,110 1200,230 C1160,350 1270,410 1230,520 C1190,630 1130,660 1170,770 C1210,880 1250,900 1250,900 L0,900 Z"
              fill="url(#wg)"/>
        <path d="M0,0 L1100,0 C1100,0 1190,100 1150,220 C1110,340 1220,400 1180,510 C1140,620 1080,650 1120,760 C1160,870 1200,900 1200,900 L0,900 Z"
              fill="#1e40af" opacity="0.28"/>
        <path d="M0,0 L1120,0 C1120,0 1200,95 1165,210 C1130,325 1245,390 1205,498 C1165,606 1105,638 1140,748 C1175,858 1215,900 1215,900 L0,900 Z"
              fill="#93c5fd" opacity="0.15"/>
    </svg>
</div>

{{-- ══ Page Content ══ --}}
<div class="page">
    <img src="{{ asset('Vector.png') }}" alt="" class="bg-wave" aria-hidden="true"/>

    <nav class="top-nav">
        <a href="{{ route('home') }}"     data-nav-link>Guest</a>
        <a href="{{ route('register') }}" data-nav-link>Register</a>
    </nav>

    <div class="content-wrap">

        <div class="branding" id="branding">
            <img src="{{ asset('logo.png') }}" alt="SafeGrid Logo" class="branding-logo"/>
            <h1>Welcome to<br/><span>SafeGrid!</span></h1>
            <p>A comprehensive disaster preparedness platform designed to help Filipino families proactively organize, manage, and secure their emergency plans. Build your go-bags, assign roles, and stay connected — so you are always ready when it matters most.</p>
        </div>

        <div class="auth-card" id="authCard">
            <h2>Login</h2>

            <x-auth-session-status class="mb-4" :status="session('status')"/>

            @if ($errors->any())
                <div class="error-banner" id="errorBanner">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @else
                <div class="error-banner" id="errorBanner" style="display:none;"></div>
            @endif

            <form id="loginForm" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="Enter Email"
                           required autofocus autocomplete="email"/>
                </div>

                <div class="field-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password"
                           placeholder="Enter password"
                           required autocomplete="current-password"/>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">LOGIN</button>
            </form>
        </div>

    </div>
</div>

<script>
(function () {
    const svg      = document.getElementById('waveSvg');
    const branding = document.getElementById('branding');
    const card     = document.getElementById('authCard');
    const form     = document.getElementById('loginForm');
    const btn      = document.getElementById('submitBtn');

    const SPEED      = 680; // ms
    const EASING     = 'cubic-bezier(0.76,0,0.24,1)';

    /* Force a reflow so the browser acknowledges a style change */
    function reflow() { svg.offsetHeight; }

    /* Slide wave LEFT */
    function revealPage(onDone) {
        svg.style.animation = 'none';
        svg.style.left = '0';
        reflow();
        svg.style.animation = `waveReveal ${SPEED}ms ${EASING} forwards`;
        setTimeout(() => {
            svg.style.animation = 'none';
            svg.style.left = '-110vw';
            if (onDone) onDone();
        }, SPEED);
    }

    /* Slide wave RIGHT */
    function coverScreen(onDone) {
        svg.style.animation = 'none';
        svg.style.left = '-110vw';
        reflow();
        svg.style.animation = `waveCover ${SPEED}ms ${EASING} forwards`;
        if (onDone) setTimeout(onDone, SPEED);
    }

    /* ── ENTRANCE ── */
    revealPage(() => {
        branding.classList.add('visible');
        card.classList.add('visible');
    });

    /* ── NAV LINKS ── */
    document.querySelectorAll('[data-nav-link]').forEach(link => {
        link.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (!href || href === '#') return;
            e.preventDefault();
            coverScreen(() => { window.location.href = href; });
        });
    });

    /* ── LOGIN FORM ── */

    // Error banner
    const errorBanner = document.getElementById('errorBanner');

    function showErrors(messages) {
        errorBanner.innerHTML = messages.map(m => `<div>${m}</div>`).join('');
        errorBanner.style.display = '';
    }

    function clearErrors() {
        errorBanner.style.display = 'none';
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        clearErrors();
        btn.disabled = true;
        btn.textContent = 'Logging in…';

        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                redirect: 'follow',
            });

            const finalUrl = response.url;
            const isLoginPage = finalUrl.includes('/login') || finalUrl === window.location.href;

            if (!response.ok || isLoginPage) {

                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');


                const errorEls = doc.querySelectorAll('.error-banner div, [class*="error"] li, [class*="error"] p, ul[class*="error"] li');
                let messages = Array.from(errorEls).map(el => el.textContent.trim()).filter(Boolean);

                if (messages.length === 0) {
                    const allText = doc.body.innerText || doc.body.textContent;
                    if (allText.toLowerCase().includes('credential') || allText.toLowerCase().includes('password')) {
                        messages = ['These credentials do not match our records.'];
                    } else {
                        messages = ['Login failed. Please check your credentials.'];
                    }
                }

                showErrors(messages);
                btn.disabled = false;
                btn.textContent = 'LOGIN';

            } else {
                coverScreen(() => {
                    window.location.href = finalUrl;
                });
            }

        } catch (err) {
            showErrors(['Something went wrong. Please try again.']);
            btn.disabled = false;
            btn.textContent = 'LOGIN';
        }
    });

    /* Back button / bfcache restore */
    window.addEventListener('pageshow', function (e) {
        if (e.persisted) {
            btn.disabled = false;
            btn.textContent = 'LOGIN';
            svg.style.left = '0';
            branding.classList.remove('visible');
            card.classList.remove('visible');
            setTimeout(() => revealPage(() => {
                branding.classList.add('visible');
                card.classList.add('visible');
            }), 80);
        }
    });
})();
</script>

</x-guest-layout>
