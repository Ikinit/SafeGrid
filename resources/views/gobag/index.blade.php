<style>
    body {
        background-color: #f1f5f9 !important;
    }

    .gb-container {
        max-width: 1100px; margin: 2rem auto; padding: 0 1rem;
    }
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
    .btn-modal:disabled {
        opacity: 0.6; cursor: not-allowed;
    }

    /* Personal and Household Bag */
    .section-title {
        font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem;
    }
    .bag-grid {
        display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; margin-bottom: 3rem;
    }
    .category-card {
        background: white; border-radius: 1rem; padding: 1.5rem; 
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .category-title {
        color: #475569; font-weight: 600; margin-bottom: 1rem; 
        border-bottom: 1px solid #f1f5f9; padding-bottom: 0.5rem;
    }
    .item-row {
        display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; 
        background: #f1f5f9; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #f1f5f9;
    }
    .item-display{
        display: flex; gap: 0.75rem;
    }
    .item-name { 
        font-size: 0.95rem; 
    }
    .item-extra { 
        font-size: 0.75rem; color: #94a3b8; 
    }
    .ntn-info { 
        margin-left: 8px;
    }
    .btn-delete {
        color: #ef4444; background: none; border: none; cursor: pointer; 
    }
    .empty-state { 
        font-size: 0.85rem; color: #cbd5e1; font-style: italic;
    }
    .no-household{
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
    .modal-margin{
        margin-bottom: 1rem;
    }
    .modal-bag{
        display: block; font-size: 0.85rem; margin-bottom: 4px;
    }
    .modal-extra{
        display: grid; grid-template-columns: 1fr 1fr; gap: 10px; 
        margin-bottom: 1rem;
    }
    .modal-btn{
        display: flex; justify-content: flex-end; gap: 10px;
    }
    .form-input {
        width: 100%; border: 1px solid #e2e8f0; padding: 0.5rem; border-radius: 0.5rem;
    }
    .form-extra {
        width: 100%; border: 1px solid #e2e8f0; padding: 0.5rem; border-radius: 0.5rem;
        font-size: 0.8rem;
    }
    .btn-cancel {
        color: #64748b; font-weight: 600; background: none; border: none; cursor: pointer;
    }
</style>

<x-app-layout>
    <!-- Filter and Add Items -->
    <div class="gb-container">
        <div class="header-bar">
            <!-- Filter Options -->
            <div id="category-filters" style="display: flex; gap: 1.5rem">
                <button class="filter-btn active" onclick="filtering('all', this)">All</button>
                <button class="filter-btn" onclick="filtering('Food and Water', this)">Food and Water</button>
                <button class="filter-btn" onclick="filtering('First Aid', this)">First Aid</button>
                <button class="filter-btn" onclick="filtering('Documents', this)">Documents</button>
                <button class="filter-btn" onclick="filtering('Clothing/tools', this)">Clothing/tools</button>
            </div>
            <!-- Modal -->
            <button class="btn-modal" onclick="openModal()">+ Add Item</button>
        </div>

        <!-- Categories -->
        @php $categories = ['Food and Water', 'First Aid', 'Documents', 'Clothing/tools']; @endphp

        <!-- Go bag for Personal -->
        <h2 class="section-title">My Go Bag</h2>
        <div class="bag-grid">
            @foreach($categories as $cat)
                <div class="category-card" data-category="{{ $cat }}">
                    <h3 class="category-title">{{ $cat }}</h3>
                    <!-- Filter personal items-->
                    @forelse($personalItems->where('category', $cat) as $item)
                        <div class="item-row">
                            <div class="item-display">
                                <form action="{{ route('gobag.personal.update', $item->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="is_packed" value="0">                                        
                                    <input type="checkbox" name="is_packed" value="1" onchange="this.form.submit()" {{ $item->is_packed ? 'checked' : '' }}>
                                </form>
                                <div>
                                    <div class="item-name">{{ $item->name }}</div>
                                    @if($item->expiry_date || $item->nutritional_info)
                                        <div class="item-extra">
                                            @if($item->expiry_date) <span>Exp: {{ date('M d, Y', strtotime($item->expiry_date)) }}</span> @endif
                                            @if($item->nutritional_info) <span class="ntn-info">{{ $item->nutritional_info }}</span> @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Delete personal item -->
                            <form action="{{ route('gobag.personal.destroy', $item->id) }}" method="POST" onsubmit="return preventSpamClick(this)">
                                @csrf @method('DELETE')
                                <button class="btn-delete" type="submit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </div>
                    @empty
                        <p class="empty-state">Empty</p>
                    @endforelse
                </div>
            @endforeach
        </div>

        <!-- Go bag for Household -->
        <h2 class="section-title">Household Go Bag</h2>
        @if($profile)
            <div class="bag-grid">
                @foreach($categories as $cat)
                    <div class="category-card" data-category="{{ $cat }}">
                        <h3 class="category-title">{{ $cat }}</h3>
                        <!-- Filter household items -->
                        @forelse($familyItems->where('category', $cat) as $item)
                            <div class="item-row">
                                <div class="item-display">
                                    <form action="{{ route('gobag.family.update', $item->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="is_packed" value="0">                                        
                                        <input type="checkbox" name="is_packed" value="1" onchange="this.form.submit()" {{ $item->is_packed ? 'checked' : '' }}>
                                    </form>
                                    <div>
                                        <div class="item-name">{{ $item->name }}</div>
                                        @if($item->expiry_date || $item->nutritional_info)
                                            <div class="item-extra">
                                                @if($item->expiry_date) <span>Exp: {{ date('M d, Y', strtotime($item->expiry_date)) }}</span> @endif
                                                @if($item->nutritional_info) <span class="ntn-info">{{ $item->nutritional_info }}</span> @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <!-- Delete household item -->
                                <form action="{{ route('gobag.family.destroy', $item->id) }}" method="POST" onsubmit="return preventSpamClick(this)">
                                    @csrf @method('DELETE')
                                    <button class="btn-delete" type="submit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <p class="empty-state">Empty</p>
                        @endforelse
                    </div>
                @endforeach
            </div>
        @else
        <!-- If no Household -->
            <div class="no-household">
                Household Bag is disabled. Please create or join a household first.
            </div>
        @endif
    </div>


    <!-- Input modal -->
    <div id="itemModal" class="modal-overlay">
        <div class="modal-content">
            <h3 class="section-title">Add New Go Bag Item</h3>
            <form id="itemForm" action="{{ route('gobag.personal.store') }}" method="POST" onsubmit="return preventSpamClick(this)">
                @csrf
                <div class="modal-margin">
                    <label class="modal-bag">Assign to Bag</label>
                    <select class="form-input" id="targetBag" onchange="storeModal()">
                        <option value="personal">My Personal Bag</option>
                        <option value="family" {{ !$profile ? 'disabled' : '' }}>Household Bag {{ !$profile ? '(Locked)' : '' }}</option>
                    </select>
                </div>
                <div class="modal-margin">
                    <input class="form-input" type="text" name="name" placeholder="Item Name" required>
                </div>
                <div class="modal-margin">
                    <select class="form-input" name="category" id="categorySelect" required onchange="toggleExpiration()">
                        @foreach($categories as $cat) <option value="{{ $cat }}">{{ $cat }}</option> @endforeach
                    </select>
                </div>
                <div class="modal-extra">
                    <input class="form-extra" id="expiryDate" type="date" name="expiry_date">
                    <input class="form-extra" type="text" name="nutritional_info" placeholder="Additional Info">
                </div>
                <div class="modal-btn">
                    <button class="btn-cancel" type="button" onclick="closeModal()">Cancel</button>
                    <button id="saveBtn" class="btn-modal" type="submit">Save Item</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        //For modal to open and close
        function openModal() { 
            document.getElementById('itemModal').style.display = 'flex'; 
            toggleExpiration();
        }
        function closeModal() { document.getElementById('itemModal').style.display = 'none'; }
        
        //For modal to store either personal or household
        function storeModal() {
            const bag = document.getElementById('targetBag').value;
            const form = document.getElementById('itemForm');
            form.action = bag === 'personal' ? "{{ route('gobag.personal.store') }}" : "{{ route('gobag.family.store') }}";
        }

        //For the filtering of categories
        function filtering(category, element) {
            const buttons = document.querySelectorAll('#category-filters button');
            buttons.forEach(btn => {
                btn.className= 'filter-btn';
            });
            element.className = 'filter-btn active';

            const cards = document.querySelectorAll('.category-card');
            cards.forEach(card => {
                card.style.display = (category==='all'|| card.getAttribute('data-category') === category)? 'block' : 'none';
            });
        }

        // For expiration to be disabled as it is Food and Water only
        function toggleExpiration() {
            const category = document.getElementById('categorySelect').value;
            const expiryDate = document.getElementById('expiryDate');
            
            if (category === 'Food and Water') {
                expiryDate.style.display = 'grid';
            } else {
                expiryDate.style.display = 'none';
                expiryDate.querySelectorAll('input').forEach(input => input.value = '');
            }
        }

        //Prevent spam click
        function preventSpamClick(form){
            const btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                if (btn.id === 'saveBtn') {
                    btn.innerText = 'Saving...';
                }
            }
            return true;
        }

        document.addEventListener('DOMContentLoaded', toggleExpiration);
    </script>
</x-app-layout>
