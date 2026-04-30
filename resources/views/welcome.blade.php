<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SafeGrid</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap');

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'Nunito', 'Segoe UI', sans-serif;
        overflow: hidden;
        background: #1d4ed8;
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
        width: 100vw;
        height: 300vh;   /* 3× screen — wavy edge is at ~100vh, slides to -200vh so it's 100vh above screen */
        will-change: top;
    }

    @keyframes waveDown {
        from { top: 0; }
        to   { top: -200vh; }  /* push entire SVG 200vh up — wavy edge clears screen with 100vh to spare */
    }

    @keyframes waveUp {
        from { top: -200vh; }
        to   { top: 0; }
    }

    .page {
        position: relative;
        min-height: 100vh;
        width: 100%;
        background: #f0f4f8;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .top-nav {
        position: relative;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 2.5rem;
        background: #fff;
        box-shadow: 0 1px 6px rgba(0,0,0,0.08);
        opacity: 0;
        transform: translateY(-100%);
        transition: opacity 0.42s ease 0.05s,
                    transform 0.42s cubic-bezier(0.34, 1.3, 0.64, 1) 0.05s;
    }
    .top-nav.visible { opacity: 1; transform: translateY(0); }

    .nav-brand {
        font-size: 1.35rem; font-weight: 700;
        color: #3b82f6; letter-spacing: -0.02em; text-decoration: none;
    }
    .nav-links { display: flex; gap: 1.5rem; align-items: center; }
    .nav-links a {
        font-size: .875rem; font-weight: 700; color: #475569;
        text-decoration: none; transition: color .2s;
    }
    .nav-links a:hover { color: #1e3a5f; }

    .hero {
        flex: 1; display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        text-align: center; padding: 3rem 2rem;
        position: relative; z-index: 1;
        opacity: 0; transform: translateY(24px);
        transition: opacity .52s ease .22s, transform .52s ease .22s;
    }
    .hero.visible { opacity: 1; transform: translateY(0); }

    .hero-logo { height: 72px; width: auto; margin-bottom: 1.5rem; object-fit: contain; }

    .hero h1 {
        font-size: clamp(2.25rem, 5vw, 3.75rem); font-weight: 700;
        color: #1e3a5f; line-height: 1.1; letter-spacing: -0.02em; margin-bottom: 1rem;
    }
    .hero h1 span {
        background: linear-gradient(90deg, #3b82f6, #7BF0FF);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }
    .hero p {
        font-size: 1rem; color: #64748b; max-width: 520px;
        line-height: 1.75; font-weight: 500; margin-bottom: 2.5rem;
    }
    .hero-btns { display: flex; gap: 1rem; flex-wrap: wrap; justify-content: center; }

    .btn-primary {
        padding: .75rem 2.25rem;
        background: linear-gradient(90deg, #3b82f6, #7BF0FF);
        color: #fff; border: none; border-radius: 999px;
        font-size: .9rem; font-weight: 800; letter-spacing: .04em; cursor: pointer;
        box-shadow: 0 4px 16px rgba(59,130,246,.35);
        transition: opacity .2s, transform .15s, box-shadow .2s;
        text-decoration: none; font-family: inherit;
    }
    .btn-primary:hover { opacity: .9; transform: translateY(-2px); box-shadow: 0 6px 22px rgba(59,130,246,.45); }

    .btn-secondary {
        padding: .75rem 2.25rem; background: #fff; color: #3b82f6;
        border: 2px solid #bfdbfe; border-radius: 999px;
        font-size: .9rem; font-weight: 800; letter-spacing: .04em; cursor: pointer;
        transition: background .2s, border-color .2s, transform .15s;
        text-decoration: none; font-family: inherit;
    }
    .btn-secondary:hover { background: #eff6ff; border-color: #93c5fd; transform: translateY(-2px); }

    .blob { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.16; pointer-events: none; z-index: 0; }
    .blob-1 { width: 420px; height: 420px; background: #3b82f6; top: -80px; left: -100px; }
    .blob-2 { width: 320px; height: 320px; background: #7BF0FF; bottom: -60px; right: -80px; }
</style>
</head>
<body>

<div id="waveOverlay">
    <!--
        SVG is 100vw × 300vh. Transparent everywhere except the blue wave shape.
        The shape fills from the top (y=0) down to a wavy bottom edge around y=1000.
        Everything below the wave edge is transparent — the page shows through.

        At load:       top=0      → wave shape covers the viewport (rows 0–900 of viewBox = 1 screen)
        After reveal:  top=-200vh → entire SVG is 200vh above screen, completely invisible
        On cover:      top=-200vh → 0, wave sweeps back down over the page
    -->
    <svg id="waveSvg" viewBox="0 0 1600 2700" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <linearGradient id="wg" x1="0%" y1="0%" x2="0%" y2="100%">
                <stop offset="0%"   stop-color="#1d4ed8"/>
                <stop offset="55%"  stop-color="#3b82f6"/>
                <stop offset="100%" stop-color="#60a5fa"/>
            </linearGradient>
        </defs>

        {{-- Main blue shape: fills top-left corner down to a wavy bottom edge.
             Starts at top-left (0,0), goes right to (1600,0), then the right
             edge drops straight to the wavy zone, the wave undulates left-to-right,
             then closes back up the left side. Everything outside = transparent. --}}
        <path d="M0,0 L1600,0 L1600,900
                 C1490,990 1370,950 1250,910
                 C1200,1020 1080,980 960,940
                 C910,1050  790,1010 670,970
                 C620,1080  500,1040 380,1000
                 C330,1110  210,1070  90,1030
                 C40,1100   0,1080   0,1080
                 Z"
              fill="url(#wg)"/>

        {{-- Depth layer --}}
        <path d="M0,0 L1600,0 L1600,900
                 C1480,980 1360,940 1240,900
                 C1190,1010 1070,970  950,930
                 C900,1040  780,1000  660,960
                 C610,1070  490,1030  370,990
                 C320,1090  200,1055   80,1020
                 C30,1090    0,1070    0,1070
                 Z"
              fill="#1e40af" opacity="0.28"/>

        {{-- Shimmer --}}
        <path d="M0,0 L1600,0 L1600,900
                 C1500,995 1380,955 1260,915
                 C1210,1025 1090,985  970,945
                 C920,1055  800,1015  680,975
                 C630,1085  510,1045  390,1005
                 C340,1105  220,1065  100,1025
                 C50,1095    0,1075   0,1075
                 Z"
              fill="#93c5fd" opacity="0.15"/>
    </svg>
</div>

<div class="page">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <nav class="top-nav" id="topNav">
        <a href="#" class="nav-brand">SafeGrid</a>
        <div class="nav-links">
            <a href="{{ route('login') }}"    data-nav-link>Login</a>
            <a href="{{ route('register') }}" data-nav-link>Register</a>
        </div>
    </nav>

    <main class="hero" id="hero">
        <img src="{{ asset('logo.png') }}" alt="SafeGrid Logo" class="hero-logo" onerror="this.style.display='none'"/>
        <h1>Welcome to <span>SafeGrid</span></h1>
        <p>A disaster preparedness planner for Filipino families. Build your go-bags, assign roles, and stay connected — so you are always ready when it matters most.</p>
        <div class="hero-btns">
            <a href="{{ route('login') }}"    class="btn-primary"   data-nav-link>Get Started</a>
            <a href="{{ route('register') }}" class="btn-secondary" data-nav-link>Create Account</a>
        </div>
    </main>
</div>

<script>
(function () {
    const svg  = document.getElementById('waveSvg');
    const nav  = document.getElementById('topNav');
    const hero = document.getElementById('hero');
    const SPEED  = 750;
    const EASING = 'cubic-bezier(0.76, 0, 0.24, 1)';
    function reflow() { svg.offsetHeight; }
    function revealPage(onDone) {
        svg.style.animation = 'none';
        svg.style.top = '0';
        reflow();
        svg.style.animation = `waveDown ${SPEED}ms ${EASING} forwards`;
        setTimeout(() => {
            svg.style.animation = 'none';
            svg.style.top = '-200vh'; /* resting position: fully above screen */
            if (onDone) onDone();
        }, SPEED);
    }
    function coverScreen(onDone) {
        svg.style.animation = 'none';
        svg.style.top = '-200vh'; /* start from fully above screen */
        reflow();
        svg.style.animation = `waveUp ${SPEED}ms ${EASING} forwards`;
        if (onDone) setTimeout(onDone, SPEED);
    }
    revealPage(() => {
        nav.classList.add('visible');
        hero.classList.add('visible');
    });
    document.querySelectorAll('[data-nav-link]').forEach(link => {
        link.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (!href || href === '#') return;
            e.preventDefault();
            coverScreen(() => { window.location.href = href; });
        });
    });
    window.addEventListener('pageshow', function (e) {
        if (e.persisted) {
            nav.classList.remove('visible');
            hero.classList.remove('visible');
            svg.style.top = '0';
            setTimeout(() => revealPage(() => {
                nav.classList.add('visible');
                hero.classList.add('visible');
            }), 80);
        }
    });
})();
</script>

</body>
</html>