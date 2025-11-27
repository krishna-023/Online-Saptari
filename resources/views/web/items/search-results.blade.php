@extends('web.layouts.master')

@section('title')
    Search Results - OnlineSaptari
@endsection

@section('css')
<style>
.search-results-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 3rem 0;
    color: white;
    margin-bottom: 3rem;
}

.search-stats {
    font-size: 1.1rem;
    opacity: 0.9;
}

.search-filters {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 25px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.filter-group {
    margin-bottom: 1.5rem;
}

.filter-group label {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #333;
}

.filter-input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.filter-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.results-count {
    color: #6c757d;
    font-size: 1.1rem;
    margin-bottom: 2rem;
}

.item-card {
    border: none;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    margin-bottom: 2rem;
}

.item-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.item-image {
    height: 200px;
    object-fit: cover;
    width: 100%;
}

.item-body {
    padding: 1.5rem;
}

.item-title {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #333;
}

.item-subtitle {
    color: #6c757d;
    margin-bottom: 1rem;
}

.item-location {
    color: #667eea;
    font-weight: 600;
}

.featured-badge {
    background: linear-gradient(135deg, #ff6b6b, #ee5a24);
    color: white;
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.no-results {
    text-align: center;
    padding: 4rem 2rem;
}

.no-results-icon {
    font-size: 4rem;
    color: #6c757d;
    margin-bottom: 1rem;
}

.search-suggestions {
    margin-top: 2rem;
}

.suggestion-tag {
    display: inline-block;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    margin: 0.3rem;
    text-decoration: none;
    color: #495057;
    transition: all 0.3s ease;
}

.suggestion-tag:hover {
    background: #667eea;
    color: white;
    border-color: #667eea;
}
</style>
@endsection

@section('content')
<div class="search-results-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-5 fw-bold mb-3">Search Results</h1>
                @if(!empty($keyword) || !empty($category) || !empty($location))
                <div class="search-stats">
                    @if(!empty($keyword))<span class="badge bg-light text-dark me-2">Keyword: {{ $keyword }}</span>@endif
                    @if(!empty($category))<span class="badge bg-light text-dark me-2">Category: {{ \App\Models\Category::find($category)->Category_Name ?? '' }}</span>@endif
                    @if(!empty($location))<span class="badge bg-light text-dark me-2">Location: {{ $location }}</span>@endif
                </div>
                @endif
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('home') }}" class="btn btn-light btn-lg">
                    <i class="ri-arrow-left-line me-2"></i>Back to Home
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container">
    {{-- Search Filters --}}
    <div class="search-filters">
        <form action="{{ route('search.results') }}" method="GET">
            <div class="row">
                <div class="col-md-4">
                    <div class="filter-group">
                        <label for="keyword"><i class="ri-search-line me-1"></i> Search Keyword</label>
                        <input type="text"
                               id="keyword"
                               name="keyword"
                               class="filter-input"
                               placeholder="What are you looking for?"
                               value="{{ $keyword ?? '' }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="filter-group">
                        <label for="category"><i class="ri-grid-line me-1"></i> Category</label>
                        <select id="category" name="category" class="filter-input">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ ($category ?? '') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->Category_Name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="filter-group">
                        <label for="location"><i class="ri-map-pin-line me-1"></i> Location</label>
                        <input type="text"
                               id="location"
                               name="location"
                               class="filter-input"
                               placeholder="Enter location"
                               value="{{ $location ?? '' }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="filter-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100" style="padding: 0.75rem 1rem;">
                            <i class="ri-search-line me-1"></i> Search
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Results --}}
    <div class="results-count">
        Found {{ $items->total() }} result(s)
    </div>

    @if($items->count() > 0)
        <div class="row">
            @foreach($items as $item)
            <div class="col-md-6 col-lg-4">
                <div class="item-card clickable-card"
                     onclick="window.location='{{ route('item.userview', $item->slug) }}'">
                    <img src="{{ $item->image ? asset('storage/'.$item->image) : asset('web/images/no-image.png') }}"
                         class="item-image"
                         alt="{{ $item->title }}">
                    <div class="item-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h3 class="item-title">{{ Str::limit($item->title, 50) }}</h3>
                            @if($item->item_featured)
                            <span class="featured-badge">Featured</span>
                            @endif
                        </div>
                        <p class="item-subtitle">{{ Str::limit($item->subtitle, 80) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="item-location">
                                <i class="ri-map-pin-line me-1"></i>
                                {{ $item->item_locations ?? 'N/A' }}
                            </span>
                            <span class="text-muted">
                                <i class="ri-calendar-line me-1"></i>
                                {{ $item->created_at->format('M d, Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-5">
            {{ $items->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="no-results">
            <div class="no-results-icon">
                <i class="ri-search-line"></i>
            </div>
            <h3>No results found</h3>
            <p class="text-muted mb-4">We couldn't find any items matching your search criteria.</p>

            <div class="search-suggestions">
                <h5>Try these suggestions:</h5>
                <div class="mt-3">
                    <a href="{{ route('search.results', ['keyword' => 'restaurant']) }}" class="suggestion-tag">Restaurants</a>
                    <a href="{{ route('search.results', ['keyword' => 'hotel']) }}" class="suggestion-tag">Hotels</a>
                    <a href="{{ route('search.results', ['keyword' => 'school']) }}" class="suggestion-tag">Schools</a>
                    <a href="{{ route('search.results', ['keyword' => 'shop']) }}" class="suggestion-tag">Shops</a>
                    <a href="{{ route('search.results', ['keyword' => 'hospital']) }}" class="suggestion-tag">Hospitals</a>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                    <i class="ri-home-line me-2"></i>Back to Homepage
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

@section('script')
<script>
// Add some interactivity to the search page
document.addEventListener('DOMContentLoaded', function() {
    // Clear individual filters
    const clearButtons = document.querySelectorAll('.clear-filter');
    clearButtons.forEach(button => {
        button.addEventListener('click', function() {
            const target = this.dataset.target;
            document.getElementById(target).value = '';
            this.closest('form').submit();
        });
    });

    // Auto-submit category changes
    const categorySelect = document.getElementById('category');
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            this.closest('form').submit();
        });
    }
});
</script>
@endsection
