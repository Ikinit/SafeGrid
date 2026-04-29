<x-app-layout>
    <!-- Filter and Add Items -->
    <div class="gb-container" style="max-width: 1100px; margin: 2rem auto; padding: 0 1rem;">
        <div style="background: #f1f5f9; padding: 1rem 2rem; border-radius: 1rem; display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <!-- Filter Options -->
            <div id="category-filters" style="display: flex; gap: 1.5rem; color: #64748b; font-weight: 500; font-size: 0.9rem;">
                <button onclick="filtering('all', this)" style="background:none; border:none; cursor:pointer; color: #1e293b; border-bottom: 2px solid #3b82f6; padding-bottom: 2px; font-weight: bold;">All</button>
                <button onclick="filtering('Food and Water', this)" style="background:none; border:none; cursor:pointer; color: #64748b;">Food and Water</button>
                <button onclick="filtering('First Aid', this)" style="background:none; border:none; cursor:pointer; color: #64748b;">First Aid</button>
                <button onclick="filtering('Documents', this)" style="background:none; border:none; cursor:pointer; color: #64748b;">Documents</button>
                <button onclick="filtering('Clothing/tools', this)" style="background:none; border:none; cursor:pointer; color: #64748b;">Clothing/tools</button>
            </div>
            <!-- Modal -->
            <button onclick="openModal()" style="background: #4f46e5; color: white; padding: 0.6rem 1.5rem; border-radius: 0.75rem; font-weight: 600; border: none; cursor: pointer;">
                + Add Item
            </button>
        </div>

        <!-- Categories -->
        @php $categories = ['Food and Water', 'First Aid', 'Documents', 'Clothing/tools']; @endphp

        <!-- Go bag for Personal -->
        <h2 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem;">My Go Bag</h2>
        <div class="bag-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; margin-bottom: 3rem;">
            @foreach($categories as $cat)
                <div class="category-card" data-category="{{ $cat }}" style="background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <h3 style="color: #475569; font-weight: 600; margin-bottom: 1rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.5rem;">{{ $cat }}</h3>
                    <!-- Filter personal items-->
                    @forelse($personalItems->where('category', $cat) as $item)
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                            <div style="display: flex; gap: 0.75rem;">
                                <input type="checkbox" {{ $item->is_packed ? 'checked' : '' }}>
                                <div>
                                    <div style="font-size: 0.95rem; {{ $item->is_packed ? 'text-decoration: line-through; color: #94a3b8;' : '' }}">{{ $item->name }}</div>
                                    @if($item->expiry_date || $item->nutritional_info)
                                        <div style="font-size: 0.75rem; color: #94a3b8;">
                                            @if($item->expiry_date) <span>Exp: {{ $item->expiry_date }}</span> @endif
                                            @if($item->nutritional_info) <span style="margin-left: 8px;">{{ $item->nutritional_info }}</span> @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Delete personal item -->
                            <form action="{{ route('gobag.personal.destroy', $item->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" style="color: #ef4444; background: none; border: none; cursor: pointer;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </div>
                    @empty
                        <p style="font-size: 0.85rem; color: #cbd5e1; font-style: italic;">Empty</p>
                    @endforelse
                </div>
            @endforeach
        </div>

        <!-- Go bag for Household -->
        <h2 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem;">Household Go Bag</h2>
        @if($profile)
            <div class="bag-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
                @foreach($categories as $cat)
                    <div class="category-card" data-category="{{ $cat }}" style="background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                        <h3 style="color: #475569; font-weight: 600; margin-bottom: 1rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.5rem;">{{ $cat }}</h3>
                        <!-- Filter household items -->
                        @forelse($familyItems->where('category', $cat) as $item)
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                                <div style="display: flex; gap: 0.75rem;">
                                    <input type="checkbox" {{ $item->is_packed ? 'checked' : '' }}>
                                    <div>
                                        <div style="font-size: 0.95rem;">{{ $item->name }}</div>
                                        @if($item->expiry_date || $item->nutritional_info)
                                            <div style="font-size: 0.75rem; color: #94a3b8;">
                                                @if($item->expiry_date) <span>Exp: {{ $item->expiry_date }}</span> @endif
                                                @if($item->nutritional_info) <span style="margin-left: 8px;">{{ $item->nutritional_info }}</span> @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <!-- Delete household item -->
                                <form action="{{ route('gobag.family.destroy', $item->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="color: #ef4444; background: none; border: none; cursor: pointer;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <p style="font-size: 0.85rem; color: #cbd5e1; font-style: italic;">Empty</p>
                        @endforelse
                    </div>
                @endforeach
            </div>
        @else
            <div style="background: #fff; padding: 2rem; border-radius: 1rem; text-align: center; border: 2px dashed #e2e8f0; color: #94a3b8;">
                Household Bag is disabled. Please create or join a household first.
            </div>
        @endif
    </div>


    <!-- Input modal -->
    <div id="itemModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.4); align-items: center; justify-content: center; z-index: 100;">
        <div style="background: white; padding: 2rem; border-radius: 1rem; width: 450px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
            <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 1.5rem;">Add New Go Bag Item</h3>
            <form id="itemForm" action="{{ route('gobag.personal.store') }}" method="POST">
                @csrf
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.85rem; margin-bottom: 4px;">Assign to Bag</label>
                    <select id="targetBag" onchange="updateFormUrl()" style="width: 100%; border: 1px solid #e2e8f0; padding: 0.5rem; border-radius: 0.5rem;">
                        <option value="personal">My Personal Bag</option>
                        <option value="family" {{ !$profile ? 'disabled' : '' }}>Household Bag {{ !$profile ? '(Locked)' : '' }}</option>
                    </select>
                </div>
                <div style="margin-bottom: 1rem;">
                    <input type="text" name="name" placeholder="Item Name" required style="width: 100%; border: 1px solid #e2e8f0; padding: 0.5rem; border-radius: 0.5rem;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <select name="category" required style="width: 100%; border: 1px solid #e2e8f0; padding: 0.5rem; border-radius: 0.5rem;">
                        @foreach($categories as $cat) <option value="{{ $cat }}">{{ $cat }}</option> @endforeach
                    </select>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 1rem;">
                    <input type="date" name="expiry_date" style="border: 1px solid #e2e8f0; padding: 0.5rem; border-radius: 0.5rem; font-size: 0.8rem;">
                    <input type="text" name="nutritional_info" placeholder="Nutritional Info" style="border: 1px solid #e2e8f0; padding: 0.5rem; border-radius: 0.5rem; font-size: 0.8rem;">
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" onclick="closeModal()" style="color: #64748b; font-weight: 600; background:none; border:none; cursor:pointer;">Cancel</button>
                    <button type="submit" style="background: #4f46e5; color: white; padding: 0.5rem 1.5rem; border-radius: 0.5rem; font-weight: 600; border:none; cursor:pointer;">Save Item</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        //modal
        function openModal() { document.getElementById('itemModal').style.display = 'flex'; }
        function closeModal() { document.getElementById('itemModal').style.display = 'none'; }
        
        function updateFormUrl() {
            const bag = document.getElementById('targetBag').value;
            const form = document.getElementById('itemForm');
            form.action = bag === 'personal' ? "{{ route('gobag.personal.store') }}" : "{{ route('gobag.family.store') }}";
        }

        //filter
        function filtering(category, element) {
            const buttons = document.querySelectorAll('#category-filters button');
            buttons.forEach(btn => {
                btn.style.color = '#64748b';
                btn.style.borderBottom = 'none';
                btn.style.fontWeight = '500';
            });
            element.style.color = '#1e293b';
            element.style.borderBottom = '2px solid #3b82f6';
            element.style.fontWeight = 'bold';

            const cards = document.querySelectorAll('.category-card');
            cards.forEach(card => {
                if (category === 'all' || card.getAttribute('data-category') === category) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>

</x-app-layout>