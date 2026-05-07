<x-guest-layout>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap');

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'Nunito', 'Segoe UI', sans-serif;
        overflow: hidden;
    }

    #fadeOverlay {
        position: fixed;
        inset: 0;
        background: #fff;
        z-index: 200;
        pointer-events: none;
        opacity: 1;
        transition: opacity 0.5s ease;
    }

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

    .auth-card {
        background: #fff;
        border-radius: 1.25rem;
        padding: 2rem 2rem;
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
        color: #1e3a5f; margin-bottom: 1.25rem;
        letter-spacing: -0.01em;
    }

    .field-group { margin-bottom: .9rem; }
    .field-group label {
        display: block;
        font-size: .75rem; font-weight: 700;
        color: #64748b; margin-bottom: .3rem;
        text-transform: uppercase; letter-spacing: .05em;
    }
    .field-group input {
        width: 100%;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: .625rem;
        padding: .5rem .85rem;
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
        cursor: pointer; margin-top: 1.25rem;
        box-shadow: 0 4px 14px rgba(123,144,255,.35);
        transition: opacity .2s, transform .15s, box-shadow .2s;
        font-family: inherit;
    }
    .btn-submit:hover { opacity: .9; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(123,144,255,.45); }
    .btn-submit:active { transform: none; }
    .btn-submit:disabled { opacity: .65; cursor: default; transform: none; }
</style>

<div id="fadeOverlay"></div>

<div class="page">
    <img src="{{ asset('Vector.png') }}" alt="" class="bg-wave" aria-hidden="true"/>

    <nav class="top-nav">
        <a href="{{ route('home') }}"  data-nav-link>Guest</a>
        <a href="{{ route('login') }}" data-nav-link>Login</a>
    </nav>

    <div class="content-wrap">

        <div class="branding" id="branding">
            <img src="{{ asset('logo.png') }}" alt="SafeGrid Logo" class="branding-logo"/>
            <h1>Welcome to<br/><span>SafeGrid!</span></h1>
            <p>A comprehensive disaster preparedness platform designed to help Filipino families proactively organize, manage, and secure their emergency plans. Build your go-bags, assign roles, and stay connected — so you are always ready when it matters most.</p>
        </div>

        <div class="auth-card" id="authCard">
            <h2>Register</h2>

            @if ($errors->any())
                <div class="error-banner" id="errorBanner">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @else
                <div class="error-banner" id="errorBanner" style="display:none;"></div>
            @endif

            <form id="registerForm" method="POST" action="{{ route('register') }}">
                @csrf

                <div class="field-group">
                    <label for="username">Username</label>
                    <input id="username" type="text" name="username"
                           value="{{ old('username') }}"
                           placeholder="Choose a username"
                           required autofocus autocomplete="username"/>
                </div>

                <div class="field-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="email@example.com"
                           required autocomplete="email"/>
                </div>

                <div class="field-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password"
                           placeholder="Create a password"
                           required autocomplete="new-password"/>
                </div>

                <div class="field-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                           placeholder="Confirm your password"
                           required autocomplete="new-password"/>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">SIGN UP</button>
            </form>
        </div>

    </div>
</div>

<script>
(function () {
    const overlay  = document.getElementById('fadeOverlay');
    const branding = document.getElementById('branding');
    const card     = document.getElementById('authCard');
    const form     = document.getElementById('registerForm');
    const btn      = document.getElementById('submitBtn');
    const SPEED    = 500;

    function revealPage(onDone) {
        overlay.style.opacity = '0';
        setTimeout(() => { if (onDone) onDone(); }, SPEED);
    }

    function coverScreen(onDone) {
        overlay.style.opacity = '1';
        if (onDone) setTimeout(onDone, SPEED);
    }

    revealPage(() => {
        branding.classList.add('visible');
        card.classList.add('visible');
    });

    document.querySelectorAll('[data-nav-link]').forEach(link => {
        link.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (!href || href === '#') return;
            e.preventDefault();
            coverScreen(() => { window.location.href = href; });
        });
    });

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
        btn.textContent = 'Creating account…';

        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                redirect: 'follow',
            });

            const finalUrl = response.url;
            const isRegisterPage = finalUrl.includes('/register') || finalUrl === window.location.href;

            if (!response.ok || isRegisterPage) {
                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const errorEls = doc.querySelectorAll('.error-banner div, [class*="error"] li, [class*="error"] p, ul[class*="error"] li');
                let messages = Array.from(errorEls).map(el => el.textContent.trim()).filter(Boolean);

                if (messages.length === 0) {
                    messages = ['Registration failed. Please check the fields and try again.'];
                }

                showErrors(messages);
                btn.disabled = false;
                btn.textContent = 'SIGN UP';
            } else {
                coverScreen(() => { window.location.href = finalUrl; });
            }
        } catch (err) {
            showErrors(['Something went wrong. Please try again.']);
            btn.disabled = false;
            btn.textContent = 'SIGN UP';
        }
    });

    window.addEventListener('pageshow', function (e) {
        if (e.persisted) {
            btn.disabled = false;
            btn.textContent = 'SIGN UP';
            overlay.style.opacity = '1';
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