@extends('web.layouts.master')

@section('title', 'All Categories - OnlineSaptari')

@section('css')
<style>
/* Categories Page Specific Styles */
.categories-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 4rem 0;
    text-align: center;
    margin-bottom: 3rem;
}

.categories-hero h1 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.categories-hero p {
    font-size: 1.2rem;
    opacity: 0.9;
}

.categories-stats {
    display: flex;
    justify-content: center;
    gap: 3rem;
    margin-top: 2rem;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    display: block;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.8;
}

/* Categories Grid */
.categories-container {
    padding: 2rem 0;
}

.categories-filter {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 3rem;
}

.filter-btn {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #333;
    padding: 0.8rem 1.5rem;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    cursor: pointer;
}

.filter-btn:hover,
.filter-btn.active {
    background: linear-gradient(135deg, #0d6efd, #6610f2);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.category-card {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    background: linear-gradient(145deg, #ffffff, #f8f9fa);
    border: 1px solid rgba(255, 255, 255, 0.2);
    height: 350px;
    cursor: pointer;
}

.category-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.category-image {
    position: relative;
    height: 100%;
    overflow: hidden;
}

.category-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
}

.category-card:hover .category-image img {
    transform: scale(1.1);
}

.category-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(
        45deg,
        rgba(13, 110, 253, 0.85),
        rgba(102, 16, 242, 0.75)
    );
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.4s ease;
}

.category-card:hover .category-overlay {
    opacity: 1;
}

.category-content {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 2rem;
    background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
    color: white;
    z-index: 2;
}

.category-icon {
    width: 60px;
    height: 60px;
    margin-bottom: 1rem;
    background: linear-gradient(135deg, #0d6efd, #6610f2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    transition: all 0.3s ease;
}

.category-card:hover .category-icon {
    transform: scale(1.1) rotate(5deg);
}

.category-title {
    font-size: 1.4rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: white;
}

.category-description {
    font-size: 0.9rem;
    opacity: 0.9;
    margin-bottom: 1rem;
    line-height: 1.5;
}

.category-stats {
    display: flex;
    gap: 1.5rem;
    margin-top: 0.5rem;
}

.stat {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    opacity: 0.9;
}

.stat i {
    font-size: 1rem;
}

.category-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: linear-gradient(135deg, #ff6b6b, #ee5a24);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    z-index: 3;
}

/* Subcategories */
.subcategories-section {
    margin-top: 4rem;
}

.subcategories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1.5rem;
}

.subcategory-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    text-decoration: none;
    color: inherit;
    position: relative;
    overflow: hidden;
}

.subcategory-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    color: inherit;
}

.subcategory-image {
    width: 100%;
    height: 120px;
    border-radius: 10px;
    margin-bottom: 1rem;
    overflow: hidden;
}

.subcategory-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.subcategory-card:hover .subcategory-image img {
    transform: scale(1.1);
}

.subcategory-icon {
    width: 50px;
    height: 50px;
    margin: 0 auto 1rem;
    background: linear-gradient(135deg, #0d6efd, #6610f2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: white;
}

.subcategory-name {
    font-weight: 600;
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
}

.subcategory-count {
    font-size: 0.8rem;
    color: #6c757d;
    background: rgba(13, 110, 253, 0.1);
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
    display: inline-block;
}

.subcategory-badge {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    background: linear-gradient(135deg, #20c997, #099268);
    color: white;
    padding: 0.3rem 0.7rem;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 600;
    z-index: 2;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #6c757d;
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 1rem;
    color: #dee2e6;
}

/* Loading State */
.loading-spinner {
    text-align: center;
    padding: 3rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .categories-hero h1 {
        font-size: 2rem;
    }

    .categories-stats {
        gap: 1.5rem;
    }

    .stat-number {
        font-size: 1.5rem;
    }

    .categories-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .category-card {
        height: 300px;
    }

    .category-content {
        padding: 1.5rem;
    }

    .subcategories-grid {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    }
}

/* Animation Classes */
.fade-slide-up {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.6s ease-out;
}

.fade-slide-up.visible {
    opacity: 1;
    transform: translateY(0);
}
</style>
@endsection

@section('content')
<div class="categories-page">
    <!-- Hero Section -->
    <section class="categories-hero">
        <div class="container">
            <h1>Explore All Categories</h1>
            <p>Discover amazing items across various categories in OnlineSaptari</p>

            <div class="categories-stats">
                <div class="stat-item">
                    <span class="stat-number" id="totalCategories">{{ $categoriesWithStats->count() }}</span>
                    <span class="stat-label">Categories</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number" id="totalItems">{{ $categoriesWithStats->sum('items_count') + $categoriesWithStats->sum('subcategory_items_count') }}</span>
                    <span class="stat-label">Total Items</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number" id="totalViews">{{ $categoriesWithStats->sum('views_count') + $categoriesWithStats->sum('subcategory_views_count') }}</span>
                    <span class="stat-label">Total Views</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Filter -->
    <section class="categories-container">
        <div class="container">
            <div class="categories-filter">
                <button class="filter-btn active" data-filter="all">All Categories</button>
                <button class="filter-btn" data-filter="popular">Most Popular</button>
                <button class="filter-btn" data-filter="featured">Featured</button>
                <button class="filter-btn" data-filter="new">Newest</button>
                <button class="filter-btn" data-filter="items">Most Items</button>
            </div>

            <!-- Categories Grid -->
            <div class="categories-grid" id="categoriesGrid">
                @forelse($categoriesWithStats as $index => $category)
                @php
                    // Get random item image for this category
                    $randomItemImage = $category->random_item_image ?? null;
                    $categoryImages = [
                        'https://images.unsplash.com/photo-1441986300917-64674bd600d8?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                        'https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                        'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                        'https://images.unsplash.com/photo-1542751371-adc38448a05e?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                        'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                        'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'
                    ];
                    $categoryIcons = ['ri-smartphone-line', 'ri-t-shirt-line', 'ri-home-4-line', 'ri-basketball-line', 'ri-book-line', 'ri-car-line'];
                    $totalItemsInCategory = $category->items_count + ($category->subcategory_items_count ?? 0);
                    $isPopular = $totalItemsInCategory > 50;
                    $isNew = $index >= count($categoriesWithStats) - 3;
                @endphp

                <div class="category-card fade-slide-up"
                     data-category-id="{{ $category->id }}"
                     data-items-count="{{ $totalItemsInCategory }}"
                     data-views-count="{{ $category->views_count + ($category->subcategory_views_count ?? 0) }}"
                     data-created-at="{{ $category->created_at->timestamp }}"
                     onclick="window.location.href='{{ route('category.items', $category->slug) }}'">

                    @if($isNew)
                    <span class="category-badge">New</span>
                    @elseif($isPopular)
                    <span class="category-badge" style="background: linear-gradient(135deg, #20c997, #099268);">Popular</span>
                    @endif

                    <div class="category-image">
                        <img src="{{ $randomItemImage ? asset('storage/' . $randomItemImage) : $categoryImages[$index % count($categoryImages)] }}"
                             alt="{{ $category->Category_Name }}"
                             onerror="this.src='{{ $categoryImages[$index % count($categoryImages)] }}'">
                        <div class="category-overlay">
                            <div class="text-center">
                                <h4 class="text-white mb-3">Explore {{ $category->Category_Name }}</h4>
                                <span class="btn btn-light rounded-pill">
                                    <i class="ri-arrow-right-line me-1"></i>
                                    View {{ $totalItemsInCategory }} Items
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="category-content">
                        <div class="category-icon">
                            <i class="{{ $categoryIcons[$index % count($categoryIcons)] }}"></i>
                        </div>
                        <h3 class="category-title">{{ $category->Category_Name }}</h3>
                        <p class="category-description">
                            Discover {{ $totalItemsInCategory }} amazing items in this category
                        </p>
                        <div class="category-stats">
                            <div class="stat">
                                <i class="ri-eye-line"></i>
                                <span>{{ number_format($category->views_count + ($category->subcategory_views_count ?? 0)) }} views</span>
                            </div>
                            <div class="stat">
                                <i class="ri-archive-line"></i>
                                <span>{{ $totalItemsInCategory }} items</span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="ri-inbox-line"></i>
                    <h3>No Categories Found</h3>
                    <p>There are no categories available at the moment.</p>
                </div>
                @endforelse
            </div>

            <!-- Subcategories Section -->
            @php
                $categoriesWithSubcategories = $categoriesWithStats->filter(function($category) {
                    return $category->children && $category->children->count() > 0;
                });
            @endphp

            @if($categoriesWithSubcategories->count() > 0)
            <div class="subcategories-section">
                <h2 class="text-center mb-4">Browse Subcategories</h2>
                @foreach($categoriesWithSubcategories as $category)
                <div class="mb-5">
                    <h4 class="mb-3">{{ $category->Category_Name }} Subcategories</h4>
                    <div class="subcategories-grid">
                        @foreach($category->children as $subcategory)
                        @php
                            // Get random item image for subcategory
                            $subcategoryRandomImage = $subcategory->random_item_image ?? null;
                            $subcategoryImages = [
                                'https://images.unsplash.com/photo-1560769629-975ec94e6a86?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
                                'https://images.unsplash.com/photo-1546868871-7041f2a55e12?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
                                'https://images.unsplash.com/photo-1523275335684-37898b6baf30?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
                                'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'
                            ];
                            $subcategoryIcons = ['ri-folder-line', 'ri-folder-2-line', 'ri-folder-3-line', 'ri-folder-open-line'];
                            $subcategoryItemsCount = $subcategory->items_count ?? 0;
                            $isSubcategoryPopular = $subcategoryItemsCount > 20;
                        @endphp

                        <a href="{{ route('category.items', $subcategory->slug) }}" class="subcategory-card">
                            @if($isSubcategoryPopular)
                            <span class="subcategory-badge">Popular</span>
                            @endif

                            <div class="subcategory-image">
                                <img src="{{ $subcategoryRandomImage ? asset('storage/' . $subcategoryRandomImage) : $subcategoryImages[$loop->index % count($subcategoryImages)] }}"
                                     alt="{{ $subcategory->Category_Name }}"
                                     onerror="this.src='{{ $subcategoryImages[$loop->index % count($subcategoryImages)] }}'">
                            </div>

                            <div class="subcategory-icon">
                                <i class="{{ $subcategoryIcons[$loop->index % count($subcategoryIcons)] }}"></i>
                            </div>
                            <div class="subcategory-name">{{ $subcategory->Category_Name }}</div>
                            <div class="subcategory-count">{{ $subcategoryItemsCount }} items</div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Back to Home -->
            <div class="text-center mt-5">
                <a href="{{ route('home') }}" class="btn btn-outline-primary btn-lg">
                    <i class="ri-arrow-left-line me-2"></i>
                    Back to Home
                </a>
            </div>
        </div>
    </section>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize animations
    initializeCategoryAnimations();

    // Filter functionality
    initializeCategoryFilters();

    // Category card interactions
    initializeCategoryInteractions();
});

function initializeCategoryAnimations() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    document.querySelectorAll('.fade-slide-up').forEach(el => {
        observer.observe(el);
    });
}

function initializeCategoryFilters() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const categoryCards = document.querySelectorAll('.category-card');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));

            // Add active class to clicked button
            this.classList.add('active');

            const filterType = this.dataset.filter;
            filterCategories(filterType);
        });
    });

    function filterCategories(filterType) {
        categoryCards.forEach(card => {
            let showCard = true;

            switch(filterType) {
                case 'popular':
                    const viewsCount = parseInt(card.dataset.viewsCount);
                    showCard = viewsCount > 100;
                    break;
                case 'featured':
                    const hasFeaturedBadge = card.querySelector('.category-badge');
                    showCard = hasFeaturedBadge !== null;
                    break;
                case 'new':
                    const createdAt = parseInt(card.dataset.createdAt);
                    const oneWeekAgo = Date.now() / 1000 - (7 * 24 * 60 * 60);
                    showCard = createdAt > oneWeekAgo;
                    break;
                case 'items':
                    const itemsCount = parseInt(card.dataset.itemsCount);
                    showCard = itemsCount > 10;
                    break;
                default:
                    showCard = true;
            }

            if (showCard) {
                card.style.display = 'block';
                // Re-trigger animation
                card.classList.remove('visible');
                setTimeout(() => {
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                entry.target.classList.add('visible');
                            }
                        });
                    });
                    observer.observe(card);
                }, 100);
            } else {
                card.style.display = 'none';
            }
        });
    }
}

function initializeCategoryInteractions() {
    const categoryCards = document.querySelectorAll('.category-card');

    categoryCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.zIndex = '10';
        });

        card.addEventListener('mouseleave', function() {
            this.style.zIndex = '1';
        });

        // Add click animation
        card.addEventListener('click', function(e) {
            if (!e.target.closest('.category-overlay')) {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            }
        });
    });
}

// Search functionality for categories
function searchCategories(query) {
    const categoryCards = document.querySelectorAll('.category-card');
    const searchTerm = query.toLowerCase();

    categoryCards.forEach(card => {
        const categoryName = card.querySelector('.category-title').textContent.toLowerCase();
        const categoryDescription = card.querySelector('.category-description').textContent.toLowerCase();

        if (categoryName.includes(searchTerm) || categoryDescription.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// Add search input if needed
function addCategorySearch() {
    const searchHtml = `
        <div class="row justify-content-center mb-4">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" id="categorySearch" class="form-control" placeholder="Search categories...">
                    <button class="btn btn-primary" type="button">
                        <i class="ri-search-line"></i>
                    </button>
                </div>
            </div>
        </div>
    `;

    document.querySelector('.categories-filter').insertAdjacentHTML('beforebegin', searchHtml);

    document.getElementById('categorySearch').addEventListener('input', function(e) {
        searchCategories(e.target.value);
    });
}

// Uncomment the line below if you want to add search functionality
// addCategorySearch();
</script>
@endsection
