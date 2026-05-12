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
        background: #1d4ed8; /* Retained the deep blue background from the original design */
    }

    #fadeOverlay {
        position: fixed;
        inset: 0;
        background: #f0f4f8;
        z-index: 200;
        pointer-events: none;
        opacity: 1;
        transition: opacity 0.5s ease;
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

    /* Animated background blobs from original design */
    .blob { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.16; pointer-events: none; z-index: 0; }
    .blob-1 { width: 420px; height: 420px; background: #3b82f6; top: -80px; left: -100px; }
    .blob-2 { width: 320px; height: 320px; background: #7BF0FF; bottom: -60px; right: -80px; }

    .dash-container { max-width: 1280px; margin: 2rem auto; padding: 0 1.5rem 4rem; }
    .dash-card { background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; }
    .section-title { font-size: 1.25rem; font-weight: 800; color: #1e3a5f; margin-bottom: 1.25rem; }
</style>
</head>
<body>

<div id="fadeOverlay"></div>

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

    <div class="dash-container space-y-8 relative z-10">

        <div>
            <div class="flex justify-between items-center mb-4">
                <h2 class="section-title mb-0">Weather Updates</h2>
            </div>

            <div class="dash-card p-0 overflow-hidden h-[450px]">
                    <iframe src="https://www.panahon.gov.ph/" width="100%" height="100%" frameborder="0" style="border:0;"></iframe>
            </div>
        </div>

        <div>
            <div class="flex justify-between items-center mb-4">
                <h2 class="section-title mb-0">Latest Preparedness Guides</h2>
                <a href="#" class="text-blue-600 text-sm font-bold hover:underline">See all →</a>
            </div>
            
            <div id="guestPostsContainer" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="col-span-3 text-center py-6 text-slate-500">Loading guides...</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 dash-card">
                <h3 class="section-title">Go Bag Essentials</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                        <h4 class="text-xs font-bold text-slate-500 uppercase mb-2">1. Food and Water</h4>
                        <div class="text-sm font-semibold text-blue-900 mb-1">Canned Meatloaf / Tuna</div>
                        <div class="text-sm font-semibold text-blue-900 mb-1">6L Water (3-Day Supply)</div>
                        <div class="text-sm font-semibold text-blue-900">Oreo</div>
                    </div>
                    <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                        <h4 class="text-xs font-bold text-slate-500 uppercase mb-2">2. First Aid</h4>
                        <div class="text-sm font-semibold text-blue-900 mb-1">Bandages & Antiseptic</div>
                        <div class="text-sm font-semibold text-blue-900 mb-1">Maintenance Meds</div>
                        <div class="text-sm font-semibold text-blue-900">Alcohol / Medicine Kit</div>
                    </div>
                    <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                        <h4 class="text-xs font-bold text-slate-500 uppercase mb-2">3. Documents</h4>
                        <div class="text-sm font-semibold text-blue-900 mb-1">IDs in Waterproof Bag</div>
                        <div class="text-sm font-semibold text-blue-900 mb-1">Project Design</div>
                        <div class="text-sm font-semibold text-blue-900">Clothing Sketches</div>
                    </div>
                    <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                        <h4 class="text-xs font-bold text-slate-500 uppercase mb-2">4. Clothing/Tools</h4>
                        <div class="text-sm font-semibold text-blue-900 mb-1">LED Flashlight & Batteries</div>
                        <div class="text-sm font-semibold text-blue-900 mb-1">Powerbank & Cables</div>
                        <div class="text-sm font-semibold text-blue-900">Laptop</div>
                    </div>
                </div>
            </div>

            <div class="dash-card flex flex-col">
                <h3 class="section-title">Emergency Hotlines</h3>
                <select id="dashLocationToggle" onchange="hotlineFilter()" class="w-full mb-4 rounded-lg border-slate-200 text-sm font-semibold bg-slate-50">
                    <option value="National">Philippines - National</option>
                    <option value="Tacloban">Local - Tacloban</option>
                    <option value="Cebu City">Local - Cebu City</option>
                    <option value="Manila">Local - Manila</option>
                </select>
                
                <div id="guestHotlinesContainer" class="flex-1 overflow-y-auto pr-2" style="max-height: 250px;">
                    <div class="text-center py-4 text-slate-500 text-sm">Loading hotlines...</div>
                </div>
            </div>
        </div>

        <div>
            <h2 class="section-title">Evacuation Centers</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="dash-card p-0 overflow-hidden h-[450px] relative">
                    <div class="absolute top-0 left-0 w-full bg-blue-600 text-white text-xs font-bold px-4 py-2 flex justify-between z-10 shadow">
                        <span>Nearby Evacuation Centers</span>
                        <span id="locStatus">Locating device...</span>
                    </div>
                    <iframe id="evacMap" width="100%" height="100%" frameborder="0" style="border:0; padding-top: 32px;"
                        src="https://maps.google.com/maps?q=evacuation+center+near+me&t=&z=13&ie=UTF8&iwloc=&output=embed" allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function hotlineFilter() {
        const selected = document.getElementById('dashLocationToggle').value;
        document.querySelectorAll('.dash-hotline-row').forEach(row => {
            row.style.display = (row.getAttribute('data-location') === selected) ? 'flex' : 'none';
        });
    }

    function updateMapLocation() {
        const status = document.getElementById('locStatus');
        const map = document.getElementById('evacMap');
        
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    status.innerText = "Location Found ✓";
                    map.src = `https://maps.google.com/maps?q=evacuation+center+near+${lat},${lon}&t=&z=13&ie=UTF8&iwloc=&output=embed`;
                },
                (error) => {
                    status.innerText = "Using default location";
                    console.warn("Location failed or denied.");
                }
            );
        } else {
            status.innerText = "Location not supported";
        }
    }

    async function fetchGuestData() {
        try {
            // Added headers to force JSON response
            const response = await fetch('/api/dashboard/stats', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(`Server returned ${response.status}`);
            }

            const data = await response.json();

            // Inject Posts
            const postsContainer = document.getElementById('guestPostsContainer');
            if(data.posts && data.posts.length > 0) {
                postsContainer.innerHTML = data.posts.map(post => `
                    <div class="dash-card p-0 overflow-hidden flex flex-col">
                        <img src="${post.image_path}" alt="${post.title}" class="w-full h-48 object-cover bg-slate-100">
                        <div class="p-4 flex-1">
                            <h3 class="font-bold text-slate-800 mb-1">${post.title}</h3>
                            <p class="text-sm text-slate-500 line-clamp-2">${post.description}</p>
                        </div>
                    </div>
                `).join('');
            } else {
                postsContainer.innerHTML = '<div class="col-span-3 p-6 text-center text-slate-500 bg-slate-50 rounded-xl border border-dashed border-slate-300">No guides available.</div>';
            }

            // Inject Hotlines
            const hotlinesContainer = document.getElementById('guestHotlinesContainer');
            if(data.hotlines) {
                hotlinesContainer.innerHTML = data.hotlines.map(h => `
                    <div class="dash-hotline-row flex justify-between items-center py-2 border-b border-slate-100" data-location="${h.location}">
                        <span class="text-sm font-semibold text-slate-600">${h.name}</span>
                        <span class="text-sm font-extrabold text-blue-600">${h.contact_number}</span>
                    </div>
                `).join('');
                hotlineFilter();
            }
        } catch (error) {
            console.error('Error loading dashboard stats:', error);
            // Updated error message to be visible
            document.getElementById('guestPostsContainer').innerHTML = `<div class="col-span-3 text-center text-red-500 py-6 font-bold">Failed to load data: ${error.message}</div>`;
            document.getElementById('guestHotlinesContainer').innerHTML = `<div class="text-center py-4 text-red-500 text-sm font-bold">Failed to load data</div>`;
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        updateMapLocation();
    });
    
    (function () {
        const overlay = document.getElementById('fadeOverlay');
        const nav     = document.getElementById('topNav');
        const hero    = document.getElementById('hero');
        const SPEED   = 500;

        function revealPage(onDone) {
            overlay.style.opacity = '0';
            setTimeout(() => { if (onDone) onDone(); }, SPEED);
        }

        function coverScreen(onDone) {
            overlay.style.opacity = '1';
            if (onDone) setTimeout(onDone, SPEED);
        }

        revealPage(() => {
            nav.classList.add('visible');
            hero.classList.add('visible');
            fetchGuestData(); // Fetch the AJAX data when page reveals
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
                overlay.style.opacity = '1';
                setTimeout(() => revealPage(() => {
                    nav.classList.add('visible');
                    hero.classList.add('visible');
                }), 80);
            }
        });
    })();
</script>

</body>
</html>s