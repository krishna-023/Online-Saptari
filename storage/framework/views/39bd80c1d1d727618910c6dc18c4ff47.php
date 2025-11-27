<?php echo $__env->make('common.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->startSection('title'); ?>
    OnlineSaptari - Discover Local Businesses & Services
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(asset('web/css/homepage.css')); ?>" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --warning-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
    --glass-bg: rgba(255, 255, 255, 0.1);
    --glass-border: rgba(255, 255, 255, 0.2);
    --shadow-lg: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    --shadow-xl: 0 35px 60px -15px rgba(0, 0, 0, 0.3);
}

body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
    overflow-x: hidden;
}

/* Modern Hero Section */
.hero-section {
    min-height: 100vh;
    display: flex;
    align-items: center;
    position: relative;
    background: var(--dark-gradient);
    overflow: hidden;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background:
        radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.15) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.2) 0%, transparent 50%);
    animation: float 6s ease-in-out infinite;
}

.hero-content {
    position: relative;
    z-index: 2;
    text-align: center;
    color: white;
    padding: 4rem 2rem;
}

.hero-badge {
    display: inline-block;
    background: var(--glass-bg);
    backdrop-filter: blur(10px);
    border: 1px solid var(--glass-border);
    color: white;
    padding: 0.5rem 1.5rem;
    border-radius: 50px;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    animation: fadeInUp 0.8s ease-out;
}

.hero-title {
    font-size: 4rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
    background: linear-gradient(135deg, #fff 0%, #e3f2fd 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: fadeInUp 0.8s ease-out 0.2s both;
    line-height: 1.1;
}

.hero-subtitle {
    font-size: 1.4rem;
    margin-bottom: 3rem;
    color: rgba(255, 255, 255, 0.9);
    font-weight: 300;
    animation: fadeInUp 0.8s ease-out 0.4s both;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

/* Modern Search Container */
.search-container-modern {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 25px;
    padding: 2.5rem;
    box-shadow: var(--shadow-lg);
    margin: 0 auto 3rem;
    max-width: 900px;
    animation: slideUpFade 1s ease-out 0.6s both;
}

.search-form-modern {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr auto;
    gap: 1.5rem;
    align-items: end;
}

.search-group-modern {
    position: relative;
    text-align: left;
}

.search-label {
    display: block;
    margin-bottom: 0.75rem;
    font-weight: 600;
    color: white;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.search-input-modern {
    width: 100%;
    padding: 1.25rem 1.5rem;
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 15px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.1);
    color: white;
    backdrop-filter: blur(10px);
}

.search-input-modern:focus {
    outline: none;
    border-color: rgba(255, 255, 255, 0.5);
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
    transform: translateY(-2px);
    background: rgba(255, 255, 255, 0.15);
}

.search-input-modern::placeholder {
    color: rgba(255, 255, 255, 0.7);
}

.search-btn-modern {
    background: var(--primary-gradient);
    color: white;
    border: none;
    padding: 1.25rem 2.5rem;
    border-radius: 15px;
    font-weight: 600;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    height: fit-content;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
}

.search-btn-modern:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(102, 126, 234, 0.6);
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
}

/* Quick Search Tags */
.quick-search-modern {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
    margin: 2rem 0;
}

.quick-tag-modern {
    background: var(--glass-bg);
    backdrop-filter: blur(10px);
    border: 1px solid var(--glass-border);
    color: white;
    padding: 0.8rem 1.5rem;
    border-radius: 25px;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
}

.quick-tag-modern:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

/* Stats Section */
.hero-stats-modern {
    display: flex;
    justify-content: center;
    gap: 4rem;
    margin-top: 4rem;
    padding-top: 3rem;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
    animation: fadeInUp 0.8s ease-out 0.8s both;
}

.stat-item-modern {
    text-align: center;
}

.stat-number-modern {
    display: block;
    font-size: 2.5rem;
    font-weight: 800;
    color: white;
    margin-bottom: 0.5rem;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.stat-label-modern {
    font-size: 1rem;
    color: rgba(255, 255, 255, 0.8);
    font-weight: 500;
}

/* Floating Elements */
.floating-elements {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    pointer-events: none;
    z-index: 1;
}

.floating-element {
    position: absolute;
    background: var(--glass-bg);
    backdrop-filter: blur(5px);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    animation: float 6s ease-in-out infinite;
}

.floating-element:nth-child(1) {
    top: 20%;
    left: 10%;
    width: 60px;
    height: 60px;
    animation-delay: 0s;
}

.floating-element:nth-child(2) {
    top: 60%;
    right: 15%;
    width: 40px;
    height: 40px;
    animation-delay: 2s;
}

.floating-element:nth-child(3) {
    bottom: 30%;
    left: 20%;
    width: 50px;
    height: 50px;
    animation-delay: 4s;
}

/* Main Content Area */
.main-content {
    background: white;
    position: relative;
    z-index: 3;
    border-radius: 50px 50px 0 0;
    margin-top: -50px;
    padding-top: 4rem;
}

/* Section Headers */
.section-header {
    text-align: center;
    margin-bottom: 4rem;
}

.section-badge {
    display: inline-block;
    background: var(--primary-gradient);
    color: white;
    padding: 0.5rem 1.5rem;
    border-radius: 25px;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.section-title {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 1rem;
    background: var(--dark-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.section-subtitle {
    font-size: 1.2rem;
    color: #6c757d;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

/* Modern Categories Grid */
.categories-grid-modern {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin: 3rem 0;
}

.category-card-modern {
    position: relative;
    border-radius: 25px;
    overflow: hidden;
    box-shadow: var(--shadow-lg);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    background: white;
    height: 350px;
    cursor: pointer;
}

.category-card-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, transparent 0%, rgba(255,255,255,0.1) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 2;
}

.category-card-modern:hover {
    transform: translateY(-15px) scale(1.03);
    box-shadow: var(--shadow-xl);
}

.category-card-modern:hover::before {
    opacity: 1;
}

.category-image-modern {
    position: relative;
    height: 100%;
    overflow: hidden;
}

.category-image-modern img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
}

.category-card-modern:hover .category-image-modern img {
    transform: scale(1.1);
}

.category-overlay-modern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(102, 126, 234, 0.9), rgba(118, 75, 162, 0.8));
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.4s ease;
    z-index: 3;
}

.category-card-modern:hover .category-overlay-modern {
    opacity: 1;
}

.category-content-modern {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 2.5rem;
    background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
    color: white;
    z-index: 2;
}

.category-icon-modern {
    width: 60px;
    height: 60px;
    margin-bottom: 1rem;
    background: var(--primary-gradient);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    transition: all 0.3s ease;
}

.category-card-modern:hover .category-icon-modern {
    transform: scale(1.1) rotate(5deg);
}

.category-title-modern {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
    color: white;
}

.category-stats-modern {
    display: flex;
    gap: 1.5rem;
    margin-top: 0.75rem;
}

.stat-modern {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    opacity: 0.9;
}

.stat-modern i {
    font-size: 0.9rem;
}

.category-badge-modern {
    position: absolute;
    top: 1.5rem;
    right: 1.5rem;
    background: var(--secondary-gradient);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 700;
    z-index: 3;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

/* Featured Listings */
.featured-section {
    background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
    padding: 6rem 0;
    margin: 4rem 0;
    border-radius: 40px;
}

.featured-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    margin-top: 3rem;
}

.featured-card-modern {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: var(--shadow-lg);
    transition: all 0.3s ease;
    cursor: pointer;
}

.featured-card-modern:hover {
    transform: translateY(-10px);
    box-shadow: var(--shadow-xl);
}

.featured-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.featured-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.featured-card-modern:hover .featured-image img {
    transform: scale(1.1);
}

.featured-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    background: var(--success-gradient);
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: 15px;
    font-size: 0.7rem;
    font-weight: 600;
}

.featured-content {
    padding: 1.5rem;
}

.featured-title {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #2c3e50;
}

.featured-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #f1f3f4;
}

.featured-rating {
    display: flex;
    align-items: center;
    gap: 0.3rem;
    color: #ffc107;
}

.featured-views {
    color: #6c757d;
    font-size: 0.9rem;
}

/* Modern Banner Carousel */
.banner-section-modern {
    padding: 4rem 0;
}

.banner-carousel-modern {
    border-radius: 30px;
    overflow: hidden;
    box-shadow: var(--shadow-xl);
}

.banner-item-modern {
    position: relative;
    height: 500px;
    overflow: hidden;
}

.banner-image-modern {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.banner-carousel-modern:hover .banner-image-modern {
    transform: scale(1.05);
}

.banner-caption-modern {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
    padding: 3rem;
    color: white;
}

.banner-title-modern {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 1rem;
}

.banner-description-modern {
    font-size: 1.2rem;
    opacity: 0.9;
    margin-bottom: 0;
}

/* Trending Section */
.trending-section {
    padding: 6rem 0;
}

.trending-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-top: 3rem;
}

.trending-card-modern {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: var(--shadow-lg);
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
}

.trending-card-modern:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-xl);
}

.trending-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    background: var(--warning-gradient);
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: 15px;
    font-size: 0.7rem;
    font-weight: 600;
    z-index: 2;
}

.trending-image {
    height: 180px;
    overflow: hidden;
}

.trending-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.trending-card-modern:hover .trending-image img {
    transform: scale(1.1);
}

.trending-content {
    padding: 1.5rem;
}

.trending-title {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #2c3e50;
}

/* CTA Section */
.cta-section {
    background: var(--dark-gradient);
    padding: 6rem 0;
    text-align: center;
    color: white;
    border-radius: 40px;
    margin: 4rem 0;
}

.cta-title {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
}

.cta-description {
    font-size: 1.3rem;
    margin-bottom: 3rem;
    opacity: 0.9;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.cta-buttons {
    display: flex;
    gap: 1.5rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-cta-primary {
    background: var(--primary-gradient);
    color: white;
    padding: 1rem 2.5rem;
    border-radius: 15px;
    text-decoration: none;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
}

.btn-cta-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(102, 126, 234, 0.6);
    color: white;
}

.btn-cta-secondary {
    background: var(--glass-bg);
    backdrop-filter: blur(10px);
    border: 1px solid var(--glass-border);
    color: white;
    padding: 1rem 2.5rem;
    border-radius: 15px;
    text-decoration: none;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

.btn-cta-secondary:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-3px);
    color: white;
}

/* Animations */
@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(5deg); }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideUpFade {
    from {
        opacity: 0;
        transform: translateY(50px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Responsive Design */
@media (max-width: 1024px) {
    .hero-title {
        font-size: 3rem;
    }

    .search-form-modern {
        grid-template-columns: 1fr 1fr;
    }

    .section-title {
        font-size: 2.5rem;
    }
}

@media (max-width: 768px) {
    .hero-section {
        min-height: auto;
        padding: 4rem 0;
    }

    .hero-title {
        font-size: 2.5rem;
    }

    .hero-subtitle {
        font-size: 1.2rem;
    }

    .search-form-modern {
        grid-template-columns: 1fr;
    }

    .search-container-modern {
        padding: 2rem;
        margin: 0 1rem 2rem;
    }

    .hero-stats-modern {
        flex-direction: column;
        gap: 2rem;
    }

    .section-title {
        font-size: 2rem;
    }

    .categories-grid-modern {
        grid-template-columns: 1fr;
    }

    .featured-grid,
    .trending-grid {
        grid-template-columns: 1fr;
    }

    .banner-item-modern {
        height: 300px;
    }

    .banner-title-modern {
        font-size: 1.8rem;
    }

    .cta-title {
        font-size: 2.2rem;
    }

    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }

    .btn-cta-primary,
    .btn-cta-secondary {
        width: 100%;
        max-width: 300px;
    }
}

/* Utility Classes */
.text-gradient {
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.bg-gradient {
    background: var(--primary-gradient);
}

.glass-effect {
    background: var(--glass-bg);
    backdrop-filter: blur(10px);
    border: 1px solid var(--glass-border);
}

/* Loading States */
.skeleton-loader {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* Enhanced existing styles for compatibility */
.card.clickable-card {
    transition: transform 0.3s, box-shadow 0.3s, opacity 0.6s;
    cursor: pointer;
    border-radius: 15px;
    overflow: hidden;
    opacity: 1;
}

.card.clickable-card.visible { opacity: 1; }
.card.clickable-card:hover {
    transform: translateY(-5px) scale(1.03);
    box-shadow: 0 20px 50px rgba(0,0,0,0.25);
}

.fade-slide-up {
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.6s ease-out;
}
.fade-slide-up.visible {
    opacity: 1;
    transform: translateY(0);
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="homepage-modern">

    
    <section class="hero-section">
        <div class="floating-elements">
            <div class="floating-element"></div>
            <div class="floating-element"></div>
            <div class="floating-element"></div>
        </div>

        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-star me-2"></i>#1 Business Directory in Saptari
                </div>

                <h1 class="hero-title">
                    Discover & Connect with<br>
                    <span class="text-gradient">Local Businesses</span>
                </h1>

                <p class="hero-subtitle">
                    Find the best restaurants, shops, services, and professionals in your area.
                    Everything you need, all in one place.
                </p>

                
                <div class="search-container-modern">
                    <form action="<?php echo e(route('search.results')); ?>" method="GET" class="search-form-modern" onsubmit="trackModernSearch(this)">
                        <div class="search-group-modern">
                            <label for="keyword-modern" class="search-label">
                                <i class="fas fa-search"></i>What are you looking for?
                            </label>
                            <input type="text"
                                   id="keyword-modern"
                                   name="keyword"
                                   class="search-input-modern"
                                   placeholder="Restaurants, hotels, doctors, shops..."
                                   value="<?php echo e(request('keyword')); ?>"
                                   autocomplete="off">
                        </div>

                        <div class="search-group-modern">
                            <label for="category-modern" class="search-label">
                                <i class="fas fa-layer-group"></i>Category
                            </label>
                            <select id="category-modern" name="category" class="search-input-modern">
                                <option value="">All Categories</option>
                                <?php $__currentLoopData = $latestCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->id); ?>" <?php echo e(request('category') == $category->id ? 'selected' : ''); ?>>
                                        <?php echo e($category->Category_Name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="search-group-modern">
                            <label for="location-modern" class="search-label">
                                <i class="fas fa-map-marker-alt"></i>Location
                            </label>
                            <input type="text"
                                   id="location-modern"
                                   name="location"
                                   class="search-input-modern"
                                   placeholder="Where are you?"
                                   value="<?php echo e(request('location')); ?>"
                                   autocomplete="off">
                        </div>

                        <button type="submit" class="search-btn-modern">
                            <i class="fas fa-search"></i>
                            Search Now
                        </button>
                    </form>
                </div>

                
                <div class="quick-search-modern">
                    <span class="quick-tag-modern" onclick="quickModernSearch('Restaurant')">
                        <i class="fas fa-utensils"></i>Restaurants
                    </span>
                    <span class="quick-tag-modern" onclick="quickModernSearch('Hotel')">
                        <i class="fas fa-hotel"></i>Hotels
                    </span>
                    <span class="quick-tag-modern" onclick="quickModernSearch('Hospital')">
                        <i class="fas fa-hospital"></i>Hospitals
                    </span>
                    <span class="quick-tag-modern" onclick="quickModernSearch('School')">
                        <i class="fas fa-graduation-cap"></i>Schools
                    </span>
                    <span class="quick-tag-modern" onclick="quickModernSearch('Shop')">
                        <i class="fas fa-shopping-bag"></i>Shops
                    </span>
                </div>

                
                <div class="hero-stats-modern">
                    <div class="stat-item-modern">
                        <span class="stat-number-modern"><?php echo e($totalItems ?? '1,000+'); ?></span>
                        <span class="stat-label-modern">Businesses Listed</span>
                    </div>
                    <div class="stat-item-modern">
                        <span class="stat-number-modern"><?php echo e($totalCategories ?? '50+'); ?></span>
                        <span class="stat-label-modern">Categories</span>
                    </div>
                    <div class="stat-item-modern">
                        <span class="stat-number-modern">24/7</span>
                        <span class="stat-label-modern">Live Support</span>
                    </div>
                    <div class="stat-item-modern">
                        <span class="stat-number-modern">98%</span>
                        <span class="stat-label-modern">Satisfaction Rate</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <div class="main-content">

        
        <?php if(isset($featuredItems) && $featuredItems->count()): ?>
        <section class="featured-section">
            <div class="container">
                <div class="section-header">
                    <span class="section-badge">Premium Listings</span>
                    <h2 class="section-title">Featured Businesses</h2>
                    <p class="section-subtitle">
                        Hand-picked businesses offering exceptional services and quality
                    </p>
                </div>

                <div class="featured-grid">
                    <?php $__currentLoopData = $featuredItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fitem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="featured-card-modern fade-slide-up"
                         data-url="<?php echo e(route('item.userview', $fitem->slug)); ?>"
                         data-item-id="<?php echo e($fitem->id); ?>">
                        <div class="featured-image">
                            <img src="<?php echo e($fitem->image ? asset('storage/'.$fitem->image) : asset('web/images/single-listing-01.jpg')); ?>"
                                 alt="<?php echo e($fitem->title); ?>"
                                 loading="lazy">
                            <span class="featured-badge">Featured</span>
                        </div>
                        <div class="featured-content">
                            <h3 class="featured-title"><?php echo e(Str::limit($fitem->title, 40)); ?></h3>
                            <p class="text-muted mb-2"><?php echo e(Str::limit($fitem->subtitle, 80)); ?></p>
                            <div class="featured-meta">
                                <div class="featured-rating">
                                    <i class="fas fa-star"></i>
                                    <span>4.8</span>
                                </div>
                                <div class="featured-views">
                                    <i class="fas fa-eye me-1"></i>
                                    <?php echo e($fitem->views ?? 0); ?> views
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        
        <section id="categories" class="modern-categories">
            <div class="container">
                <div class="section-header">
                    <span class="section-badge">Browse Everything</span>
                    <h2 class="section-title">Explore Categories</h2>
                    <p class="section-subtitle">
                        Find exactly what you need across our carefully organized categories
                    </p>
                </div>

                <div class="categories-grid-modern">
                    <?php $__currentLoopData = $latestCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $categoryIcons = ['fas fa-mobile-alt', 'fas fa-tshirt', 'fas fa-home', 'fas fa-basketball-ball', 'fas fa-book', 'fas fa-car'];
                        $isFeatured = $index < 2;
                        $isNew = $index == count($latestCategories) - 1;
                        $categoryImages = [
                            'category-box-01.jpg', 'category-box-02.jpg', 'category-box-03.jpg',
                            'category-box-04.jpg', 'category-box-05.jpg', 'category-box-06.jpg',
                            'category-box-07.jpg', 'category-box-08.jpg'
                        ];
                        $imgKey = $index % count($categoryImages);
                    ?>

                    <div class="category-card-modern fade-slide-up"
                         onclick="window.location.href='<?php echo e(route('category.items', $category->slug)); ?>'"
                         data-category-type="<?php echo e($isFeatured ? 'featured' : ''); ?> <?php echo e($isNew ? 'new' : ''); ?>">

                        <?php if($isNew): ?>
                        <span class="category-badge-modern">New</span>
                        <?php endif; ?>

                        <div class="category-image-modern">
                            <img src="<?php echo e(asset('web/images/' . $categoryImages[$imgKey])); ?>"
                                 data-src="<?php echo e(asset('web/images/' . $categoryImages[$imgKey])); ?>"
                                 class="lazy-load-img"
                                 alt="<?php echo e($category->Category_Name); ?>"
                                 loading="lazy">
                            <div class="category-overlay-modern">
                                <div class="text-center">
                                    <h4 class="text-white mb-3">Explore <?php echo e($category->Category_Name); ?></h4>
                                    <span class="btn btn-light rounded-pill px-4">
                                        <i class="fas fa-arrow-right me-2"></i>
                                        View All
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="category-content-modern">
                            <div class="category-icon-modern">
                                <i class="<?php echo e($categoryIcons[$index % count($categoryIcons)]); ?>"></i>
                            </div>
                            <h3 class="category-title-modern"><?php echo e($category->Category_Name); ?></h3>
                            <div class="category-stats-modern">
                                <div class="stat-modern">
                                    <i class="fas fa-eye"></i>
                                    <span><?php echo e(rand(100, 1000)); ?>+ views</span>
                                </div>
                                <div class="stat-modern">
                                    <i class="fas fa-store"></i>
                                    <span><?php echo e(rand(10, 100)); ?>+ listings</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                
                <div class="text-center mt-5">
                    <a href="<?php echo e(route('all.categories')); ?>" class="btn btn-primary btn-lg px-5 py-3">
                        <i class="fas fa-th-large me-2"></i>
                        Explore All Categories
                    </a>
                </div>
            </div>
        </section>

        
        <?php if(isset($banners) && $banners->count()): ?>
        <section class="banner-section-modern">
            <div class="container">
                <div class="section-header">
                    <span class="section-badge">Special Offers</span>
                    <h2 class="section-title">Promotions & Updates</h2>
                    <p class="section-subtitle">
                        Latest deals and announcements from local businesses
                    </p>
                </div>

                <div class="banner-carousel-modern">
                    <div id="bannerCarouselModern" class="carousel slide" data-bs-ride="carousel">
                        <?php if($banners->count() > 1): ?>
                        <div class="carousel-indicators">
                            <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button type="button"
                                    data-bs-target="#bannerCarouselModern"
                                    data-bs-slide-to="<?php echo e($index); ?>"
                                    class="<?php echo e($index == 0 ? 'active' : ''); ?>"
                                    aria-current="<?php echo e($index == 0 ? 'true' : 'false'); ?>"
                                    aria-label="Slide <?php echo e($index + 1); ?>"></button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php endif; ?>

                        <div class="carousel-inner">
                            <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="carousel-item <?php echo e($index == 0 ? 'active' : ''); ?>">
                                <div class="banner-item-modern">
                                    <?php if($banner->link): ?>
                                    <a href="<?php echo e($banner->link); ?>" target="_blank" class="d-block h-100">
                                        <img src="<?php echo e(Storage::exists('public/banners/' . $banner->image) ? asset('storage/banners/'.$banner->image) : asset('web/images/default-banner.png')); ?>"
                                             class="banner-image-modern"
                                             alt="<?php echo e($banner->title ?? 'Promotional Banner'); ?>"
                                             loading="lazy">
                                    </a>
                                    <?php else: ?>
                                    <img src="<?php echo e(Storage::exists('public/banners/' . $banner->image) ? asset('storage/banners/'.$banner->image) : asset('web/images/default-banner.png')); ?>"
                                         class="banner-image-modern"
                                         alt="<?php echo e($banner->title ?? 'Promotional Banner'); ?>"
                                         loading="lazy">
                                    <?php endif; ?>

                                    <?php if($banner->title || $banner->description): ?>
                                    <div class="banner-caption-modern">
                                        <?php if($banner->title): ?>
                                        <h3 class="banner-title-modern"><?php echo e($banner->title); ?></h3>
                                        <?php endif; ?>
                                        <?php if($banner->description): ?>
                                        <p class="banner-description-modern"><?php echo e(Str::limit($banner->description, 150)); ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <?php if($banners->count() > 1): ?>
                        <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarouselModern" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#bannerCarouselModern" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?>

        
        <section id="listings" class="container my-5">
            <div class="section-header">
                <span class="section-badge">Fresh Additions</span>
                <h2 class="section-title">Latest Listings</h2>
                <p class="section-subtitle">
                    Discover the newest businesses and services added to our directory
                </p>
            </div>

            <div class="row g-4" id="items-grid">
                <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card shadow-sm clickable-card fade-slide-up"
                         data-url="<?php echo e(route('item.userview', $item->slug)); ?>"
                         data-item-id="<?php echo e($item->id); ?>">
                        <div class="position-relative">
                            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 200'%3E%3Crect fill='%23f8f9fa' width='300' height='200'/%3E%3C/svg%3E"
                                 data-src="<?php echo e($item->image ? asset('storage/'.$item->image) : asset('web/images/no-image.png')); ?>"
                                 class="card-img-top lazy-load-img"
                                 alt="<?php echo e($item->title); ?>"
                                 loading="lazy"
                                 onerror="this.src='<?php echo e(asset('web/images/no-image.png')); ?>'">
                            <?php if($item->item_featured): ?>
                            <span class="position-absolute top-0 start-0 m-2 badge bg-warning">
                                <i class="fas fa-star me-1"></i>Featured
                            </span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fw-bold"><?php echo e(Str::limit($item->title, 50)); ?></h5>
                            <p class="card-text text-muted"><?php echo e(Str::limit($item->subtitle, 80)); ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    <?php echo e($item->created_at->diffForHumans()); ?>

                                </small>
                                <span class="badge bg-primary">
                                    <i class="fas fa-eye me-1"></i>
                                    <?php echo e($item->views ?? 0); ?>

                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12">
                    <div class="alert alert-warning text-center py-5">
                        <i class="fas fa-inbox display-4 text-warning mb-3 d-block"></i>
                        <h4>No Listings Available</h4>
                        <p class="mb-0">Be the first to add your business to our directory!</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            
            <?php if($items->hasPages()): ?>
            <div class="mt-5 d-flex justify-content-center">
                <?php echo e($items->links('pagination::bootstrap-5')); ?>

            </div>
            <?php endif; ?>
        </section>

        
        <?php if(isset($mostSearchedItems) && $mostSearchedItems->count()): ?>
        <section class="trending-section">
            <div class="container">
                <div class="section-header">
                    <span class="section-badge">Hot Right Now</span>
                    <h2 class="section-title">Trending Listings</h2>
                    <p class="section-subtitle">
                        Discover what's popular and trending in your area
                    </p>
                </div>

                <div class="trending-grid">
                    <?php $__currentLoopData = $mostSearchedItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="trending-card-modern fade-slide-up"
                         data-url="<?php echo e(route('item.userview', $item->slug)); ?>"
                         data-item-id="<?php echo e($item->id); ?>">
                        <span class="trending-badge">
                            <i class="fas fa-fire me-1"></i>Trending
                        </span>
                        <div class="trending-image">
                            <img src="<?php echo e($item->image ? asset('storage/' . $item->image) : asset('web/images/no-image.png')); ?>"
                                 alt="<?php echo e($item->title); ?>"
                                 loading="lazy"
                                 onerror="this.src='<?php echo e(asset('web/images/no-image.png')); ?>'">
                        </div>
                        <div class="trending-content">
                            <h5 class="trending-title"><?php echo e(Str::limit($item->title, 40)); ?></h5>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="text-primary fw-bold">
                                    <i class="fas fa-eye me-1"></i>
                                    <?php echo e($item->views ?? 0); ?> views
                                </span>
                                <span class="text-success">
                                    <i class="fas fa-chart-line me-1"></i>
                                    Popular
                                </span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        
        <section class="cta-section">
            <div class="container">
                <h2 class="cta-title">Ready to Grow Your Business?</h2>
                <p class="cta-description">
                    Join thousands of businesses already listed on OnlineSaptari and reach more customers today
                </p>
                <div class="cta-buttons">
                    <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('user.add')); ?>" class="btn-cta-primary">
                        <i class="fas fa-plus-circle me-2"></i>
                        Add Your Business
                    </a>
                    <a href="<?php echo e(route('banners.create')); ?>" class="btn-cta-secondary">
                        <i class="fas fa-megaphone me-2"></i>
                        Promote Your Business
                    </a>
                    <?php else: ?>
                    <a href="<?php echo e(route('register')); ?>" class="btn-cta-primary">
                        <i class="fas fa-user-plus me-2"></i>
                        Join Now - It's Free
                    </a>
                    <a href="<?php echo e(route('login')); ?>" class="btn-cta-secondary">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Sign In
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </section>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Modern JavaScript with enhanced functionality
document.addEventListener('DOMContentLoaded', function() {
    initializeModernHomepage();
});

function initializeModernHomepage() {
    // Initialize animations
    initScrollAnimations();

    // Initialize lazy loading
    initLazyLoading();

    // Initialize click handlers
    initClickHandlers();

    // Initialize search functionality
    initSearchFeatures();

    // Initialize carousels
    initCarousels();

    // Initialize infinite scroll
    initInfiniteScroll();
}

function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '50px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);

    // Observe all fade-slide-up elements
    document.querySelectorAll('.fade-slide-up').forEach(el => {
        observer.observe(el);
    });
}

function initLazyLoading() {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.classList.add('loaded');
                    observer.unobserve(img);
                }
            }
        });
    }, {
        rootMargin: '100px'
    });

    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}

function initClickHandlers() {
    // Card click handlers with event delegation
    document.addEventListener('click', function(e) {
        const card = e.target.closest('.clickable-card, .featured-card-modern, .trending-card-modern, .category-card-modern');
        if (card && card.dataset.url) {
            e.preventDefault();
            trackAction('card_click', {
                itemId: card.dataset.itemId,
                type: card.classList.contains('featured-card-modern') ? 'featured' :
                      card.classList.contains('trending-card-modern') ? 'trending' :
                      card.classList.contains('category-card-modern') ? 'category' : 'regular'
            });
            window.location.href = card.dataset.url;
        }
    });

    // Quick search tag handlers
    document.querySelectorAll('.quick-tag-modern').forEach(tag => {
        tag.addEventListener('click', function() {
            const keyword = this.textContent.trim().replace(/[^a-zA-Z]/g, '');
            quickModernSearch(keyword);
        });
    });
}

function initSearchFeatures() {
    const keywordInput = document.getElementById('keyword-modern');
    const locationInput = document.getElementById('location-modern');

    if (keywordInput) {
        // Search suggestions
        keywordInput.addEventListener('input', debounce(function(e) {
            if (e.target.value.length > 2) {
                showSearchSuggestions(e.target.value);
            }
        }, 300));

        // Enter key search
        keywordInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.querySelector('.search-form-modern').submit();
            }
        });
    }

    if (locationInput) {
        // Location autocomplete
        locationInput.addEventListener('input', debounce(function(e) {
            if (e.target.value.length > 2) {
                suggestLocations(e.target.value);
            }
        }, 300));
    }
}

function initCarousels() {
    const bannerCarousel = document.getElementById('bannerCarouselModern');
    if (bannerCarousel) {
        new bootstrap.Carousel(bannerCarousel, {
            interval: 5000,
            ride: 'carousel',
            wrap: true
        });
    }
}

function initInfiniteScroll() {
    const sentinel = document.getElementById('infinite-scroll-sentinel');
    if (!sentinel) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                loadMoreItems();
            }
        });
    });

    observer.observe(sentinel);
}

// Modern Search Functions
function quickModernSearch(keyword) {
    const keywordInput = document.getElementById('keyword-modern');
    if (keywordInput) {
        keywordInput.value = keyword;
        trackAction('quick_search', { keyword: keyword });

        // Add visual feedback
        const searchBtn = document.querySelector('.search-btn-modern');
        const originalText = searchBtn.innerHTML;
        searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Searching...';

        setTimeout(() => {
            document.querySelector('.search-form-modern').submit();
        }, 500);
    }
}

function trackModernSearch(form) {
    const formData = {
        keyword: form.keyword.value,
        category: form.category.value,
        location: form.location.value
    };

    trackAction('modern_search', formData);
}

// Utility Functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function trackAction(action, data = {}) {
    if (navigator.sendBeacon) {
        const formData = new FormData();
        formData.append('action', action);
        formData.append('data', JSON.stringify(data));
        formData.append('_token', '<?php echo e(csrf_token()); ?>');
        formData.append('url', window.location.href);

        navigator.sendBeacon('<?php echo e(route("track.action")); ?>', formData);
    }
}

function showSearchSuggestions(query) {
    // Implementation for search suggestions
    console.log('Fetching suggestions for:', query);
}

function suggestLocations(query) {
    // Implementation for location suggestions
    console.log('Fetching locations for:', query);
}

function loadMoreItems() {
    // Implementation for infinite scroll
    console.log('Loading more items...');
}

// Performance optimizations
window.addEventListener('load', function() {
    // Preload critical images
    const criticalImages = [
        '<?php echo e(asset("web/images/category-box-01.jpg")); ?>',
        '<?php echo e(asset("web/images/category-box-02.jpg")); ?>',
        '<?php echo e(asset("web/images/category-box-03.jpg")); ?>'
    ];

    criticalImages.forEach(src => {
        const img = new Image();
        img.src = src;
    });
});

// Error handling for images
document.addEventListener('error', function(e) {
    if (e.target.tagName === 'IMG') {
        e.target.src = '<?php echo e(asset("web/images/Online_Saptari_Logo.jpeg")); ?>';
        e.target.classList.add('error-image');
    }
}, true);
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('web.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Online_Saptari\resources\views/home.blade.php ENDPATH**/ ?>