<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-[#1e3a5f] leading-tight">
                {{ __('Go Bag Management') }}
            </h2>
        </div>
    </x-slot>

    <style>
        body { background-color: #f1f5f9 !important; }
        .gb-container { max-width: 1100px; margin: 2rem auto; padding: 0 1rem; }
        .header-bar {
            background: #f1f5f9; padding: 1rem 2rem; border-radius: 1rem; 
            display: flex; justify-content: space-between; align-items: center; 
            margin-bottom: 2rem;
        }
        .filter-btn {
            color: #64748b; font-weight: 500; font-size: 0.9rem; 
            background: none; border: none; cursor: pointer; padding-bottom: 2px;
        }
        .filter-btn.active {
            color: #1e293b; border-bottom: 2px solid #3b82f6; font-weight: bold;
        }

        .btn-modal {
            background: #4f46e5; color: white; padding: 0.6rem 1.5rem; 
            border-radius: 0.75rem; font-weight: 600; border: none; cursor: pointer;
        }
        .btn-modal:disabled { opacity: 0.6; cursor: not-allowed; }

        /* Personal and Household Bag */
        .section-title { font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem; }
        .bag-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; margin-bottom: 3rem; }
        .category-card { background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .category-title {
            color: #475569; font-weight: 600; margin-bottom: 1rem; 
            border-bottom: 1px solid #f1f5f9; padding-bottom: 0.5rem;
        }
        .item-row {
            display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; 
            background: #f1f5f9; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #f1f5f9;
        }
        .item-display { display: flex; gap: 0.75rem; }
        .item-name { font-size: 0.95rem; }
        .item-extra { font-size: 0.75rem; color: #94a3b8; }
        .ntn-info { margin-left: 8px; }
        .btn-delete { color: #ef4444; background: none; border: none; cursor: pointer; }
        .empty-state { font-size: 0.85rem; color: #cbd5e1; font-style: italic; }
        .no-household {
            background: white; padding: 2rem; border-radius: 1rem; text-align: center; 
            border: 2px dashed #e2e8f0; color: #94a3b8;
        }

        /* For Modal */
        .modal-overlay {
            display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.4); 
            align-items: center; justify-content: center; z-index: 100;
        }
        .modal-content {
            background: white; padding: 2rem; border-radius: 1rem; width: 450px; 
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .modal-margin { margin-bottom: 1rem; }
        .modal-bag { display: block; font-size: 0.85rem; margin-bottom: 4px; }
        .modal-extra { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 1rem; }
        .modal-btn { display: flex; justify-content: flex-end; gap: 10px; }
        .form-input { width: 100%; border: 1px solid #e2e8f0; padding: 0.5rem; border-radius: 0.5rem; }
        .form-extra { width: 100%; border: 1px solid #e2e8f0; padding: 0.5rem; border-radius: 0.5rem; font-size: 0.8rem; }
        .btn-cancel { color: #64748b; font-weight: 600; background: none; border: none; cursor: pointer; }
    </style>

    <div class="gb-container">
        <div class="header-bar">
            <div id="category-filters" style="display: flex; gap: 1.5rem">
                <button class="filter-btn active" onclick="filtering('all', this)">All</button>
                <button class="filter-btn" onclick="filtering('Food and Water', this)">Food and Water</button>
                <button class="filter-btn" onclick="filtering('First Aid', this)">First Aid</button>
                <button class="filter-btn" onclick="filtering('Documents', this)">Documents</button>
                <button class="filter-btn" onclick="filtering('Clothing/tools', this)">Clothing/tools</button>
            </div>
            <button class="btn-modal" onclick="openModal()">+ Add Item</button>
        </div>

        <h2 class="section-title">My Go Bag</h2>
        <div id="personalBagGrid" class="bag-grid">
            <div class="no-household" style="grid-column: 1 / -1;">Loading items...</div>
        </div>

        <h2 class="section-title">
            {{ auth()->user()->activeMember()?->familyProfile->household_name ?? 'Household' }} Go Bag
        </h2>

        @if(auth()->user() && auth()->user()->active_family_profile_id)
            <div id="familyBagGrid" class="bag-grid">
                <div class="no-household" style="grid-column: 1 / -1;">Loading items...</div>
            </div>
        @else
            <div class="no-household">
                Household Bag is disabled. Please create or join a household first.
            </div>
        @endif
    </div>

    <div id="itemModal" class="modal-overlay">
        <div class="modal-content">
            <h3 class="section-title" style="margin-bottom: 1.25rem;">Add New Go Bag Item</h3>
            <form id="itemForm" onsubmit="submitItemForm(event)">
                <div class="modal-margin">
                    <label class="modal-bag">Assign to Bag</label>
                    <select class="form-input" id="targetBag" name="targetBag">
                        <option value="personal">My Personal Bag</option>
                        
                        <option value="family" {{ !auth()->user()->active_family_profile_id ? 'disabled' : '' }}>
                            {{ auth()->user()->activeMember()?->familyProfile->household_name ?? 'Household' }} Bag {{ !auth()->user()->active_family_profile_id ? '(Locked)' : '' }}
                        </option>

                    </select>
                </div>
                <div class="modal-margin">
                    <input class="form-input" type="text" name="name" placeholder="Item Name" required>
                </div>
                <div class="modal-margin">
                    <select class="form-input" name="category" id="categorySelect" required onchange="toggleExpiration()">
                        <option value="Food and Water">Food and Water</option>
                        <option value="First Aid">First Aid</option>
                        <option value="Documents">Documents</option>
                        <option value="Clothing/tools">Clothing/tools</option>
                    </select>
                </div>
                <div class="modal-extra" id="expiryGroup">
                    <input class="form-extra" id="expiryDate" type="date" name="expiry_date" title="Expiration Date">
                    <input class="form-extra" id="nutritionalInfo" type="text" name="nutritional_info" placeholder="Additional Info">
                </div>
                <div class="modal-btn">
                    <button class="btn-cancel" type="button" onclick="closeModal()">Cancel</button>
                    <button id="saveBtn" class="btn-modal" type="submit">Save Item</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const CATEGORIES = ['Food and Water', 'First Aid', 'Documents', 'Clothing/tools'];
        let currentFilter = 'all';

        // ─────────────────────────────────────────────────────────────────
        // Initialization & Fetching
        // ─────────────────────────────────────────────────────────────────
        async function fetchBagItems() {
            try {
                const res = await fetch('/api/gobag', {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });

                if (!res.ok) throw new Error(`Server returned ${res.status}: ${res.statusText}`);

                const data = await res.json();

                const personalItems = Array.isArray(data.personal) ? data.personal : [];
                renderGrid('personalBagGrid', personalItems, 'personal');
                
                const familyGrid = document.getElementById('familyBagGrid');
                if (familyGrid) {
                    const familyItems = Array.isArray(data.family) ? data.family : [];
                    renderGrid('familyBagGrid', familyItems, 'family');
                }
            } catch (err) {
                console.error('API Connection Error:', err);
                const errorHtml = `
                    <div class="no-household" style="border-color: #ef4444; color: #ef4444; grid-column: 1 / -1;">
                        <b>Connection Failed:</b> ${err.message}<br/>
                        <span style="font-size: 0.8rem; color: #64748b;">Press F12 and check the Console/Network tab for details.</span>
                    </div>`;
                
                const personalGrid = document.getElementById('personalBagGrid');
                if(personalGrid) { personalGrid.innerHTML = errorHtml; personalGrid.style.gridTemplateColumns = '1fr'; }
                
                const familyGrid = document.getElementById('familyBagGrid');
                if(familyGrid) { familyGrid.innerHTML = errorHtml; familyGrid.style.gridTemplateColumns = '1fr'; }
            }
        }

        // ─────────────────────────────────────────────────────────────────
        // Rendering the Grid
        // ─────────────────────────────────────────────────────────────────
        function renderGrid(containerId, items, type) {
            const container = document.getElementById(containerId);
            container.style.gridTemplateColumns = ''; // Reset CSS
            
            container.innerHTML = CATEGORIES.map(cat => {
                const catItems = items.filter(i => i.category === cat);
                let itemsHtml = '<p class="empty-state">Empty</p>';

                if (catItems.length > 0) {
                    itemsHtml = catItems.map(item => {
                        let extraHtml = '';
                        if (item.expiry_date || item.nutritional_info) {
                            let exp = item.expiry_date 
                                ? `<span>Exp: ${new Date(item.expiry_date).toLocaleDateString('en-US', {month:'short', day:'numeric', year:'numeric'})}</span>` : '';
                            let ntn = item.nutritional_info 
                                ? `<span class="ntn-info">${item.nutritional_info}</span>` : '';
                            extraHtml = `<div class="item-extra">${exp} ${ntn}</div>`;
                        }

                        return `
                        <div class="item-row">
                            <div class="item-display">
                                <input type="checkbox" onchange="togglePacked(${item.id}, '${type}', this.checked)" ${item.is_packed ? 'checked' : ''}>
                                <div>
                                    <div class="item-name">${item.name}</div>
                                    ${extraHtml}
                                </div>
                            </div>
                            <button class="btn-delete" type="button" onclick="deleteItem(${item.id}, '${type}')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>`;
                    }).join('');
                }

                return `
                <div class="category-card" data-category="${cat}">
                    <h3 class="category-title">${cat}</h3>
                    ${itemsHtml}
                </div>`;
            }).join('');

            applyCurrentFilter();
        }

        // ─────────────────────────────────────────────────────────────────
        // UI Interaction & AJAX Mutations
        // ─────────────────────────────────────────────────────────────────
        function filtering(category, element) {
            currentFilter = category;
            const buttons = document.querySelectorAll('#category-filters button');
            buttons.forEach(btn => btn.className= 'filter-btn');
            element.className = 'filter-btn active';
            applyCurrentFilter();
        }

        function applyCurrentFilter() {
            const cards = document.querySelectorAll('.category-card');
            cards.forEach(card => {
                card.style.display = (currentFilter === 'all' || card.getAttribute('data-category') === currentFilter) ? 'block' : 'none';
            });
        }

        function toggleExpiration() {
            const category = document.getElementById('categorySelect').value;
            const expiryGroup = document.getElementById('expiryGroup');
            
            if (category === 'Food and Water') {
                expiryGroup.style.display = 'grid';
            } else {
                expiryGroup.style.display = 'none';
                document.getElementById('expiryDate').value = '';
                document.getElementById('nutritionalInfo').value = '';
            }
        }

        function openModal() { 
            document.getElementById('itemModal').style.display = 'flex'; 
            toggleExpiration();
        }
        
        function closeModal() { 
            document.getElementById('itemModal').style.display = 'none'; 
            document.getElementById('itemForm').reset();
        }

        async function submitItemForm(e) {
            e.preventDefault();
            const form = e.target;
            const btn = document.getElementById('saveBtn');
            const targetBag = document.getElementById('targetBag').value;
            
            btn.disabled = true;
            btn.innerText = 'Saving...';

            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            try {
                const res = await fetch(`/api/gobag/${targetBag}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                    body: JSON.stringify(data)
                });
                
                if (!res.ok) throw await res.json();
                
                closeModal();
                fetchBagItems();
            } catch (err) {
                console.error('Error saving item:', err);
                alert('Failed to save item. Please check your inputs.');
            } finally {
                btn.disabled = false;
                btn.innerText = 'Save Item';
            }
        }

        async function togglePacked(id, type, isPacked) {
            try {
                await fetch(`/api/gobag/${type}/${id}`, {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                    body: JSON.stringify({ is_packed: isPacked ? 1 : 0 })
                });
            } catch (err) {
                console.error('Failed to update packed status:', err);
            }
        }

        async function deleteItem(id, type) {
            if (!confirm('Are you sure you want to remove this item?')) return;
            try {
                await fetch(`/api/gobag/${type}/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
                });
                fetchBagItems();
            } catch (err) {
                console.error('Failed to delete item:', err);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            fetchBagItems();
            toggleExpiration();
            
            document.getElementById('itemModal').addEventListener('click', function(e) {
                if (e.target === this) closeModal();
            });
        });
    </script>
</x-app-layout>