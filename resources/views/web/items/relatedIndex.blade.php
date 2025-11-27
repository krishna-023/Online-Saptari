@extends('web.layouts.master')

@section('css')
<style>
.items-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
    padding: 2rem 0;
}

.item-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    cursor: pointer;
}

.item-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 50px rgba(0,0,0,0.15);
}

.item-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.item-content {
    padding: 1.5rem;
}

.category-filter {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 2rem;
    padding: 1rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.category-btn {
    padding: 0.5rem 1.5rem;
    border: 2px solid #e2e8f0;
    border-radius: 25px;
    background: white;
    color: #64748b;
    cursor: pointer;
    transition: all 0.3s ease;
}

.category-btn.active,
.category-btn:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.loading-spinner {
    text-align: center;
    padding: 2rem;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid var(--primary);
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="text-center mb-4">Browse Items</h1>

            <!-- Category Filter -->
            <div class="category-filter">
                <button class="category-btn active" data-category="">All Items</button>
                @foreach($categories as $category)
                <button class="category-btn" data-category="{{ $category->slug }}">
                    {{ $category->Category_Name }}
                </button>
                @endforeach
            </div>

            <!-- Items Container -->
            <div id="items-container" class="items-container"></div>

            <!-- Loading Spinner -->
            <div id="loading-spinner" class="loading-spinner">
                <div class="spinner"></div>
                <p class="mt-3 text-muted">Loading items...</p>
            </div>

            <!-- End Message -->
            <div id="end-message" class="text-center py-4" style="display: none;">
                <p class="text-muted">No more items to load</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
let currentPage = 1;
let isLoading = false;
let hasMore = true;
let currentCategory = new URLSearchParams(window.location.search).get('category') || '';

// Load items function
async function loadItems(category = '', reset = false) {
    if (isLoading) return;

    if (reset) {
        currentPage = 1;
        hasMore = true;
        document.getElementById('items-container').innerHTML = '';
        document.getElementById('end-message').style.display = 'none';
    }

    if (!hasMore) return;

    isLoading = true;
    document.getElementById('loading-spinner').style.display = 'block';

    try {
        const response = await fetch(`/api/items?category=${category}&page=${currentPage}`);
        const data = await response.json();

        if (data.items && data.items.length > 0) {
            const container = document.getElementById('items-container');

            data.items.forEach(item => {
                const itemElement = createItemCard(item);
                container.appendChild(itemElement);
            });

            currentPage++;
            hasMore = data.hasMore;
        } else {
            hasMore = false;
            if (currentPage === 1) {
                container.innerHTML = '<p class="text-center text-muted">No items found.</p>';
            } else {
                document.getElementById('end-message').style.display = 'block';
            }
        }
    } catch (error) {
        console.error('Error loading items:', error);
    } finally {
        isLoading = false;
        document.getElementById('loading-spinner').style.display = 'none';
    }
}

// Create item card
function createItemCard(item) {
    const card = document.createElement('div');
    card.className = 'item-card';
    card.onclick = () => window.location.href = `/items/${item.slug}`;

    card.innerHTML = `
        <img src="${item.image ? '/storage/' + item.image : '/admin/images/logo-sm.png'}"
             alt="${item.title}"
             class="item-image">
        <div class="item-content">
            <h5>${decodeHtml(item.title)}</h5>
            ${item.subtitle ? `<p class="text-muted">${decodeHtml(item.subtitle)}</p>` : ''}
            <div class="d-flex justify-content-between align-items-center mt-3">
                <span class="badge bg-primary">${item.category?.Category_Name || 'Uncategorized'}</span>
                <small class="text-muted">${new Date(item.collection_date).toLocaleDateString()}</small>
            </div>
        </div>
    `;

    return card;
}

// Category filter handling
document.querySelectorAll('.category-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const category = this.getAttribute('data-category');

        // Update active button
        document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        // Update URL
        const url = new URL(window.location);
        if (category) {
            url.searchParams.set('category', category);
        } else {
            url.searchParams.delete('category');
        }
        window.history.pushState({}, '', url);

        // Load items with new category
        currentCategory = category;
        loadItems(category, true);
    });
});

// Infinite scroll
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting && hasMore && !isLoading) {
            loadItems(currentCategory);
        }
    });
}, { threshold: 0.1 });

observer.observe(document.getElementById('loading-spinner'));

// Initial load
loadItems(currentCategory);
</script>
@endsection
