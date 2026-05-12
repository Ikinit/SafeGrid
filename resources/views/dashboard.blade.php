<x-app-layout>
    <style>
        .dash-container { max-width: 1280px; margin: 2rem auto; padding: 0 1.5rem; }
        .dash-card { background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; }
        .section-title { font-size: 1.25rem; font-weight: 800; color: #1e3a5f; margin-bottom: 1.25rem; }
    </style>

    <div class="dash-container space-y-8">
        
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
            <div id="postsContainer" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="col-span-3 py-6 text-center text-slate-500">Loading guides...</div>
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
                        <div class="text-sm font-semibold text-blue-900">Alcohol / Hygiene Kit</div>
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
                <div id="hotlinesContainer" class="flex-1 overflow-y-auto pr-2" style="max-height: 250px;">
                    <div class="text-center py-4 text-slate-500 text-sm">Loading hotlines...</div>
                </div>
            </div>
        </div>

        <div>
            <h2 class="section-title">Evacuation & Live Weather Hazards</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="dash-card p-0 overflow-hidden h-[450px] relative">
                    <div class="absolute top-0 left-0 w-full bg-blue-600 text-white text-xs font-bold px-4 py-2 flex justify-between z-10 shadow">
                        <span>Nearby Evacuation Centers</span>
                        <span id="locStatus">Locating device...</span>
                    </div>
                    <iframe id="evacMap" width="100%" height="100%" frameborder="0" style="border:0; padding-top: 32px;"
                        src="https://maps.google.com/maps?q=0,0&z=2&output=embed" allowfullscreen>
                    </iframe>
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
                        map.src = `https://maps.google.com/maps?q=${lat},${lon}&z=13&output=embed`;
                    },
                    (error) => {
                        status.innerText = "Using default location";
                        console.warn("Location failed or denied.");
                        // Default to Manila or somewhere generic if denied
                        map.src = `https://maps.google.com/maps?q=14.5995,120.9842&z=10&output=embed`; 
                    }
                );
            } else {
                status.innerText = "Location not supported";
            }
        }

        async function fetchDashboardData() {
            try {
                const response = await fetch('/api/dashboard/stats');
                const data = await response.json();

                // Inject Posts
                const postsContainer = document.getElementById('postsContainer');
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
                    // Styled empty state based on your original design
                    postsContainer.innerHTML = '<div class="col-span-3 p-6 text-center text-slate-500 bg-slate-50 rounded-xl border border-dashed border-slate-300">No guides available.</div>';
                }

                // Inject Hotlines
                const hotlinesContainer = document.getElementById('hotlinesContainer');
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
                document.getElementById('postsContainer').innerHTML = '<div class="col-span-3 p-6 text-center text-red-500 bg-red-50 rounded-xl border border-dashed border-red-300">Failed to load data.</div>';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            fetchDashboardData();
            updateMapLocation();
        });
    </script>
</x-app-layout>