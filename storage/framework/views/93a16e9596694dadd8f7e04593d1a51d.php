<?php use Illuminate\Support\Str; ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.1/css/lightgallery-bundle.min.css"/>
<style>
:root {
    --primary: #6366f1;
    --primary-dark: #4f46e5;
    --primary-light: #818cf8;
    --secondary: #f59e0b;
    --secondary-dark: #d97706;
    --dark: #1e293b;
    --darker: #0f172a;
    --light: #f8fafc;
    --lighter: #ffffff;
    --gray: #64748b;
    --gray-light: #cbd5e1;
    --success: #10b981;
    --danger: #ef4444;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    --shadow-xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    --gradient-primary: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    --gradient-secondary: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-dark) 100%);
    --gradient-dark: linear-gradient(135deg, var(--dark) 0%, var(--darker) 100%);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
    line-height: 1.6;
    color: var(--dark);
    background: var(--light);
    overflow-x: hidden;
}

/* Modern Hero Section with Parallax */
.modern-hero {
    background: linear-gradient(135deg,
        rgba(99, 102, 241, 0.92) 0%,
        rgba(79, 70, 229, 0.88) 50%,
        rgba(67, 56, 202, 0.85) 100%),
        <?php if($item->image): ?>
            url('<?php echo e(asset('storage/' . $item->image)); ?>')
        <?php else: ?>
            url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="600" viewBox="0 0 1200 600"><defs><linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:%236366f1;stop-opacity:1"/><stop offset="100%" style="stop-color:%234f46e5;stop-opacity:1"/></linearGradient></defs><rect width="1200" height="600" fill="url(%23grad)"/></svg>')
        <?php endif; ?>;
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    background-repeat: no-repeat;
    color: white;
    padding: 6rem 0 4rem;
    position: relative;
    overflow: hidden;
    min-height: 80vh;
    display: flex;
    align-items: center;
}

.hero-pattern {
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
    z-index: 10;
    text-align: center;
    max-width: 900px;
    margin: 0 auto;
    padding: 0 2rem;
}

.hero-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 3rem;
}

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(20px);
    color: white;
    padding: 0.875rem 1.5rem;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: var(--shadow-lg);
}

.back-btn:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-2px);
    box-shadow: var(--shadow-xl);
    color: white;
    text-decoration: none;
}

.hero-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(20px);
    color: white;
    padding: 0.75rem 1.25rem;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.action-btn:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-2px);
    color: white;
    text-decoration: none;
}

.category-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(20px);
    color: white;
    padding: 1rem 2rem;
    border-radius: 50px;
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 2rem;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    box-shadow: var(--shadow-lg);
    animation: slideInUp 0.8s ease-out 0.2s both;
}

.category-badge:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-3px) scale(1.05);
    box-shadow: var(--shadow-xl);
    color: white;
    text-decoration: none;
}

.hero-title {
    font-size: clamp(2.5rem, 5vw, 4.5rem);
    font-weight: 800;
    margin-bottom: 1.5rem;
    line-height: 1.1;
    text-shadow: 2px 4px 20px rgba(0, 0, 0, 0.3);
    background: linear-gradient(135deg, #ffffff 0%, #e3f2fd 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: slideInUp 0.8s ease-out 0.4s both;
}

.hero-subtitle {
    font-size: clamp(1.1rem, 2.5vw, 1.5rem);
    opacity: 0.95;
    margin-bottom: 3rem;
    font-weight: 300;
    line-height: 1.6;
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
    animation: slideInUp 0.8s ease-out 0.6s both;
}

.hero-meta {
    display: flex;
    justify-content: center;
    gap: 3rem;
    flex-wrap: wrap;
    margin-top: 3rem;
    padding-top: 3rem;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
    animation: slideInUp 0.8s ease-out 0.8s both;
}

.meta-item {
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
}

.meta-icon {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    border: 2px solid rgba(255, 255, 255, 0.3);
    transition: all 0.3s ease;
}

.meta-item:hover .meta-icon {
    background: rgba(255, 255, 255, 0.25);
    transform: scale(1.1) rotate(5deg);
}

.meta-label {
    font-size: 0.9rem;
    opacity: 0.9;
    font-weight: 500;
}

.meta-value {
    font-size: 1.1rem;
    font-weight: 700;
}

/* Main Content Area */
.main-content {
    background: white;
    position: relative;
    z-index: 20;
    border-radius: 40px 40px 0 0;
    margin-top: -40px;
    padding: 4rem 0 0;
    box-shadow: 0 -20px 60px rgba(0, 0, 0, 0.1);
}

/* Modern Card Styles */
.modern-card {
    background: var(--lighter);
    border-radius: 24px;
    box-shadow: var(--shadow-sm);
    border: 1px solid rgba(226, 232, 240, 0.8);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    overflow: hidden;
    position: relative;
}

.modern-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-xl);
    border-color: var(--primary-light);
}

.modern-card.glass {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

/* Feature Grid */
.feature-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin: 2rem 0;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 2rem;
    background: linear-gradient(135deg, var(--light) 0%, #f1f5f9 100%);
    border-radius: 20px;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.feature-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    transition: left 0.6s ease;
}

.feature-item:hover::before {
    left: 100%;
}

.feature-item:hover {
    background: var(--gradient-primary);
    color: white;
    transform: translateX(12px) translateY(-5px);
    box-shadow: var(--shadow-xl);
}

.feature-icon {
    width: 70px;
    height: 70px;
    background: var(--gradient-primary);
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
    font-size: 1.5rem;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    box-shadow: var(--shadow-md);
}

.feature-item:hover .feature-icon {
    background: white;
    color: var(--primary);
    transform: scale(1.1) rotate(10deg);
    box-shadow: var(--shadow-lg);
}

.feature-content {
    flex: 1;
}

.feature-title {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    transition: color 0.3s ease;
}

.feature-item:hover .feature-title {
    color: white;
}

.feature-description {
    color: var(--gray);
    transition: color 0.3s ease;
    line-height: 1.5;
}

.feature-item:hover .feature-description {
    color: rgba(255, 255, 255, 0.9);
}

/* Modern Tabs */
.modern-tabs {
    background: white;
    border-radius: 30px;
    box-shadow: var(--shadow-xl);
    overflow: hidden;
    margin: 4rem 0;
    border: 1px solid rgba(226, 232, 240, 0.8);
}

.tab-header {
    display: flex;
    background: linear-gradient(135deg, var(--light) 0%, #f1f5f9 100%);
    border-bottom: 1px solid rgba(226, 232, 240, 0.8);
    flex-wrap: wrap;
    padding: 1rem;
    gap: 0.5rem;
}

.tab-button {
    padding: 1.25rem 2rem;
    background: none;
    border: none;
    color: var(--gray);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    flex: 1;
    min-width: 160px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    font-size: 1rem;
}

.tab-button.active {
    color: var(--primary);
    background: white;
    box-shadow: var(--shadow-lg);
    transform: translateY(-2px);
}

.tab-button.active::before {
    content: '';
    position: absolute;
    bottom: -1px;
    left: 50%;
    transform: translateX(-50%);
    width: 60%;
    height: 3px;
    background: var(--gradient-primary);
    border-radius: 3px 3px 0 0;
}

.tab-button .badge {
    background: var(--primary);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 700;
    min-width: 24px;
}

.tab-content {
    padding: 3rem;
    display: none;
    animation: fadeInUp 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.tab-content.active {
    display: block;
}

/* Enhanced Gallery */
.gallery-section {
    padding: 2rem 0;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin: 2rem 0;
}

.gallery-item {
    border-radius: 20px;
    overflow: hidden;
    position: relative;
    cursor: pointer;
    aspect-ratio: 1;
    box-shadow: var(--shadow-lg);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    background: var(--light);
}

.gallery-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(99, 102, 241, 0.1), rgba(79, 70, 229, 0.1));
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 1;
}

.gallery-item:hover {
    transform: translateY(-12px) scale(1.03);
    box-shadow: var(--shadow-xl);
}

.gallery-item:hover::before {
    opacity: 1;
}

.gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.gallery-item:hover img {
    transform: scale(1.15);
}

.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent 70%);
    display: flex;
    align-items: flex-end;
    padding: 2rem;
    color: white;
    opacity: 0;
    transition: all 0.4s ease;
    z-index: 2;
}

.gallery-item:hover .gallery-overlay {
    opacity: 1;
}

.gallery-actions {
    position: absolute;
    top: 1rem;
    right: 1rem;
    display: flex;
    gap: 0.5rem;
    opacity: 0;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    z-index: 3;
}

.gallery-item:hover .gallery-actions {
    opacity: 1;
    transform: translateY(0);
}

.gallery-action-btn {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--dark);
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: var(--shadow);
}

.gallery-action-btn:hover {
    background: white;
    transform: scale(1.1);
    color: var(--primary);
}

/* LightGallery Overrides */
.lg-backdrop {
    background: rgba(0, 0, 0, 0.95) !important;
    backdrop-filter: blur(20px);
}

.lg-outer .lg-thumb-item.active,
.lg-outer .lg-thumb-item:hover {
    border-color: var(--primary) !important;
}

.lg-actions .lg-next,
.lg-actions .lg-prev {
    background-color: rgba(255, 255, 255, 0.1) !important;
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
}

.lg-toolbar .lg-icon {
    color: white !important;
    background: rgba(255, 255, 255, 0.1) !important;
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
}

/* Map Container */
.map-container {
    border-radius: 24px;
    overflow: hidden;
    box-shadow: var(--shadow-xl);
    height: 500px;
    margin: 2rem 0;
    border: 1px solid rgba(226, 232, 240, 0.8);
}

#interactive-map {
    height: 100%;
    width: 100%;
    border-radius: 24px;
}

/* Social Media Hub */
.social-hub {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin: 2rem 0;
}

.social-card {
    padding: 2.5rem 2rem;
    border-radius: 20px;
    color: white;
    text-align: center;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    text-decoration: none;
    display: block;
    box-shadow: var(--shadow-lg);
    position: relative;
    overflow: hidden;
}

.social-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.social-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: var(--shadow-xl);
    color: white;
    text-decoration: none;
}

.social-card:hover::before {
    opacity: 1;
}

.social-card.facebook { background: linear-gradient(135deg, #1877f2, #0d5cb6); }
.social-card.instagram { background: linear-gradient(135deg, #e4405f, #c13584); }
.social-card.twitter { background: linear-gradient(135deg, #1da1f2, #0d8bd9); }
.social-card.linkedin { background: linear-gradient(135deg, #0077b5, #00639c); }
.social-card.youtube { background: linear-gradient(135deg, #ff0000, #cc0000); }
.social-card.whatsapp { background: linear-gradient(135deg, #25d366, #128c7e); }
.social-card.tiktok { background: linear-gradient(135deg, #000000, #69c9d0); }

.social-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    display: block;
    transition: transform 0.3s ease;
}

.social-card:hover .social-icon {
    transform: scale(1.2) rotate(5deg);
}

/* Opening Hours Timeline */
.opening-hours {
    background: linear-gradient(135deg, var(--light) 0%, #f1f5f9 100%);
    border-radius: 24px;
    padding: 3rem;
    margin: 2rem 0;
    border: 1px solid rgba(226, 232, 240, 0.8);
}

.timeline {
    position: relative;
    padding-left: 3rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: var(--gradient-primary);
    border-radius: 4px;
}

.timeline-item {
    position: relative;
    margin-bottom: 2rem;
    padding-left: 2rem;
    transition: all 0.3s ease;
}

.timeline-item:hover {
    transform: translateX(10px);
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -3.2rem;
    top: 0.5rem;
    width: 16px;
    height: 16px;
    background: var(--gradient-primary);
    border-radius: 50%;
    border: 4px solid white;
    box-shadow: var(--shadow-md);
    transition: all 0.3s ease;
}

.timeline-item:hover::before {
    transform: scale(1.2);
    box-shadow: var(--shadow-lg);
}

.timeline-day {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 0.25rem;
}

.timeline-time {
    font-size: 1rem;
    color: var(--gray);
    font-weight: 500;
}

.timeline-item.closed .timeline-time {
    color: var(--danger);
    font-weight: 600;
}

/* Content Section Styling */
.content-section {
    line-height: 1.8;
    font-size: 1.1rem;
    color: var(--dark);
}

.content-section > * {
    margin-bottom: 1.5rem;
}

.content-section h1,
.content-section h2,
.content-section h3,
.content-section h4,
.content-section h5,
.content-section h6 {
    margin-top: 2.5rem;
    margin-bottom: 1.5rem;
    color: var(--dark);
    font-weight: 700;
    line-height: 1.3;
}

.content-section h1 {
    font-size: 2.5rem;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.content-section h2 { font-size: 2rem; }
.content-section h3 { font-size: 1.75rem; }
.content-section h4 { font-size: 1.5rem; }
.content-section h5 { font-size: 1.25rem; }
.content-section h6 { font-size: 1.1rem; }

.content-section p {
    margin-bottom: 1.5rem;
}

.content-section ul,
.content-section ol {
    margin-bottom: 1.5rem;
    padding-left: 2.5rem;
}

.content-section li {
    margin-bottom: 0.75rem;
    position: relative;
}

.content-section ul li::before {
    content: '▸';
    color: var(--primary);
    font-weight: bold;
    position: absolute;
    left: -1.5rem;
}

.content-section blockquote {
    border-left: 4px solid var(--primary);
    padding: 2rem;
    margin: 2.5rem 0;
    font-style: italic;
    color: var(--gray);
    background: linear-gradient(135deg, var(--light) 0%, #f1f5f9 100%);
    border-radius: 0 20px 20px 0;
    box-shadow: var(--shadow-lg);
    position: relative;
}

.content-section blockquote::before {
    content: '"';
    font-size: 4rem;
    color: var(--primary);
    position: absolute;
    top: -1rem;
    left: 1rem;
    opacity: 0.3;
}

.content-section table {
    width: 100%;
    border-collapse: collapse;
    margin: 2.5rem 0;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: var(--shadow-lg);
}

.content-section table th,
.content-section table td {
    padding: 1.25rem;
    border: 1px solid rgba(226, 232, 240, 0.8);
    text-align: left;
}

.content-section table th {
    background: var(--gradient-primary);
    color: white;
    font-weight: 600;
    font-size: 1rem;
}

.content-section table tr:nth-child(even) {
    background: var(--light);
}

.content-section table tr:hover {
    background: rgba(99, 102, 241, 0.05);
}

.content-section img {
    max-width: 100%;
    height: auto;
    border-radius: 16px;
    margin: 2rem 0;
    box-shadow: var(--shadow-lg);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.content-section img:hover {
    transform: scale(1.02);
    box-shadow: var(--shadow-xl);
}

.content-section a {
    color: var(--primary);
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    border-bottom: 2px solid transparent;
}

.content-section a:hover {
    color: var(--primary-dark);
    border-bottom-color: var(--primary);
}

/* Animations */
@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(5deg); }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(40px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(60px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.05); opacity: 0.8; }
}

@keyframes zoomIn {
    from { transform: scale(0.8); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

/* Loading States */
.loading-spinner {
    display: none;
    text-align: center;
    padding: 4rem;
}

.spinner {
    width: 60px;
    height: 60px;
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

/* Utility Classes */
.text-gradient {
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.bg-gradient {
    background: var(--gradient-primary);
}

.glass-effect {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Responsive Design */
@media (max-width: 1200px) {
    .hero-title {
        font-size: 3.5rem;
    }

    .feature-grid {
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    }
}

@media (max-width: 768px) {
    .modern-hero {
        padding: 4rem 0 3rem;
        min-height: 70vh;
        background-attachment: scroll;
    }

    .hero-navigation {
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .hero-actions {
        width: 100%;
        justify-content: center;
    }

    .hero-title {
        font-size: 2.5rem;
    }

    .hero-subtitle {
        font-size: 1.2rem;
    }

    .hero-meta {
        gap: 2rem;
    }

    .main-content {
        border-radius: 30px 30px 0 0;
        margin-top: -30px;
        padding: 3rem 0 0;
    }

    .tab-header {
        flex-direction: column;
    }

    .tab-button {
        min-width: 100%;
        margin: 0.25rem 0;
    }

    .tab-content {
        padding: 2rem;
    }

    .feature-grid {
        grid-template-columns: 1fr;
    }

    .gallery-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .social-hub {
        grid-template-columns: 1fr;
    }

    .map-container {
        height: 400px;
    }

    .content-section h1 { font-size: 2rem; }
    .content-section h2 { font-size: 1.75rem; }
    .content-section h3 { font-size: 1.5rem; }
}

@media (max-width: 480px) {
    .modern-hero {
        padding: 3rem 0 2rem;
        min-height: 60vh;
    }

    .hero-title {
        font-size: 2rem;
    }

    .hero-subtitle {
        font-size: 1.1rem;
    }

    .hero-meta {
        flex-direction: column;
        gap: 1.5rem;
    }

    .gallery-grid {
        grid-template-columns: 1fr;
    }

    .tab-content {
        padding: 1.5rem;
    }

    .feature-item {
        padding: 1.5rem;
    }

    .feature-icon {
        width: 60px;
        height: 60px;
        font-size: 1.3rem;
    }
}

/* Print Styles */
@media print {
    .modern-hero {
        background: none !important;
        color: black !important;
        min-height: auto;
        padding: 2rem 0;
    }

    .hero-pattern,
    .hero-actions,
    .tab-header {
        display: none;
    }

    .modern-card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }

    .main-content {
        margin-top: 0;
        border-radius: 0;
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    :root {
        --primary: #0000ff;
        --primary-dark: #0000cc;
        --secondary: #ffa500;
    }

    .modern-card {
        border: 2px solid var(--dark);
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 12px;
}

::-webkit-scrollbar-track {
    background: var(--light);
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: var(--gradient-primary);
    border-radius: 10px;
    border: 3px solid var(--light);
}

::-webkit-scrollbar-thumb:hover {
    background: var(--primary-dark);
}

/* Selection styles */
::selection {
    background: var(--primary-light);
    color: white;
}

::-moz-selection {
    background: var(--primary-light);
    color: white;
}
/* TomTom Map Container */
#tomtom-map {
    width: 100%;
    height: 400px;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
}

/* Map Controls */
.tt-control-container {
    margin: 10px;
}

/* Responsive Design */
@media (max-width: 768px) {
    #tomtom-map {
        height: 300px;
    }
}

@media (max-width: 480px) {
    #tomtom-map {
        height: 250px;
    }
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('common.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<!-- Modern Hero Section -->
<section class="modern-hero">
    <div class="hero-pattern"></div>
    <div class="container">
        <div class="hero-content">
            <!-- Navigation -->
            <div class="hero-navigation">
                <a href="<?php echo e(route('home')); ?>" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to Home
                </a>
                <div class="hero-actions">
                    <a href="#contact" class="action-btn">
                        <i class="fas fa-phone"></i>
                        Contact
                    </a>
                    <a href="#location" class="action-btn">
                        <i class="fas fa-map-marker-alt"></i>
                        Location
                    </a>
                    <button class="action-btn" onclick="window.print()">
                        <i class="fas fa-print"></i>
                        Print
                    </button>
                </div>
            </div>

            <!-- Category Badge -->
            <?php if($item->category): ?>
            <a href="<?php echo e(route('category.items', $item->category->slug)); ?>" class="category-badge">
                <i class="fas fa-tag"></i>
                <?php echo e($item->category->Category_Name); ?>

            </a>
            <?php endif; ?>

            <!-- Title & Subtitle -->
            <h1 class="hero-title"><?php echo $item->title; ?></h1>

            <?php if($item->subtitle): ?>
            <div class="hero-subtitle"><?php echo $item->subtitle; ?></div>
            <?php endif; ?>

            <!-- Meta Information -->
            <div class="hero-meta">
                <?php if($item->collection_date): ?>
                <div class="meta-item">
                    <div class="meta-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <span class="meta-label">Established</span>
                    <span class="meta-value"><?php echo e(\Carbon\Carbon::parse($item->collection_date)->format('M d, Y')); ?></span>
                </div>
                <?php endif; ?>

                <?php if($item->item_locations): ?>
                <div class="meta-item">
                    <div class="meta-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <span class="meta-label">Location</span>
                    <span class="meta-value"><?php echo e($item->item_locations); ?></span>
                </div>
                <?php endif; ?>

                <div class="meta-item">
                    <div class="meta-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <span class="meta-label">Views</span>
                    <span class="meta-value"><?php echo e($item->views ?? 0); ?></span>
                </div>

                <?php if($item->item_featured): ?>
                <div class="meta-item">
                    <div class="meta-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <span class="meta-label">Status</span>
                    <span class="meta-value">Featured</span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<div class="main-content">
    <div class="container">

        <!-- Quick Stats Cards -->
        <div class="row g-4 mb-5">
            <div class="col-xl-3 col-md-6">
                <div class="modern-card text-center p-4 fade-up">
                    <div class="feature-icon mx-auto mb-3">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h5>Established</h5>
                    <p class="mb-0 text-muted"><?php echo e($item->collection_date ? \Carbon\Carbon::parse($item->collection_date)->format('M d, Y') : 'N/A'); ?></p>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="modern-card text-center p-4 fade-up">
                    <div class="feature-icon mx-auto mb-3">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h5>Location</h5>
                    <p class="mb-0 text-muted"><?php echo e($item->item_locations ?? 'N/A'); ?></p>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="modern-card text-center p-4 fade-up">
                    <div class="feature-icon mx-auto mb-3">
                        <i class="fas fa-star"></i>
                    </div>
                    <h5>Status</h5>
                    <p class="mb-0 text-muted"><?php echo e($item->item_featured ? 'Featured' : 'Standard'); ?></p>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="modern-card text-center p-4 fade-up">
                    <div class="feature-icon mx-auto mb-3">
                        <i class="fas fa-user"></i>
                    </div>
                    <h5>Author</h5>
                    <p class="mb-0 text-muted"><?php echo e($item->author_first_name); ?> <?php echo e($item->author_last_name); ?></p>
                </div>
            </div>
        </div>

        <!-- Modern Tabs -->
        <div class="modern-tabs">
            <div class="tab-header">
                <button class="tab-button active" data-tab="overview">
                    <i class="fas fa-info-circle"></i>
                    Overview
                    <span class="badge">1</span>
                </button>
                <button class="tab-button" data-tab="contact">
                    <i class="fas fa-address-book"></i>
                    Contact
                    <span class="badge"><?php echo e($item->contacts ? '1' : '0'); ?></span>
                </button>
                <button class="tab-button" data-tab="gallery">
    <i class="fas fa-images"></i>
    Gallery
    <?php
        // Use the same processing logic as the controller
        $galleryCount = 0;
        if($item->galleries && $item->galleries->count() > 0) {
            $allImages = [];

            foreach($item->galleries as $gallery) {
                // Individual records
                if (!empty($gallery->image_url)) {
                    $allImages[] = $gallery->image_url;
                }

                // Array storage
                if (!empty($gallery->gallery)) {
                    $images = is_array($gallery->gallery) ? $gallery->gallery : json_decode($gallery->gallery, true);
                    if($images && is_array($images)) {
                        $allImages = array_merge($allImages, array_filter($images));
                    }
                }

                // Local files (avoid duplicates)
                if (!empty($gallery->local_path) && !in_array($gallery->local_path, $allImages)) {
                    $allImages[] = $gallery->local_path;
                }
            }

            $galleryCount = count(array_unique(array_filter($allImages)));
        }
    ?>
    <span class="badge"><?php echo e($galleryCount); ?></span>
</button>
                <?php if($item->contacts && ($item->contacts->latitude || $item->contacts->address)): ?>
                <button class="tab-button" data-tab="location">
                    <i class="fas fa-map"></i>
                    Location
                    <span class="badge">1</span>
                </button>
                <?php endif; ?>
                <?php if($item->socialIcons && $item->socialIcons->count() > 0): ?>
                <button class="tab-button" data-tab="social">
                    <i class="fas fa-share-alt"></i>
                    Social
                    <span class="badge"><?php echo e($item->socialIcons->count()); ?></span>
                </button>
                <?php endif; ?>
                <?php if($item->openingTimes): ?>
                <button class="tab-button" data-tab="hours">
                    <i class="fas fa-clock"></i>
                    Opening Hours
                    <span class="badge">1</span>
                </button>
                <?php endif; ?>
            </div>

            <!-- Overview Tab -->
            <div class="tab-content active" id="overview">
                <div class="content-section">
                    <?php echo $item->content ?? '<div class="text-center py-5"><i class="fas fa-info-circle fa-4x text-muted mb-4"></i><h4>No Content Available</h4><p class="text-muted">This item doesn\'t have any detailed content yet.</p></div>'; ?>

                </div>

                <?php if($item->contacts): ?>
                <div class="feature-grid mt-5">
                    <?php if($item->contacts->telephone): ?>
                    <div class="feature-item" onclick="window.location.href='tel:<?php echo e($item->contacts->telephone); ?>'">
                        <div class="feature-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="feature-content">
                            <h5 class="feature-title">Primary Phone</h5>
                            <p class="feature-description"><?php echo e($item->contacts->telephone); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($item->contacts->email): ?>
                    <div class="feature-item" onclick="window.location.href='mailto:<?php echo e($item->contacts->email); ?>'">
                        <div class="feature-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="feature-content">
                            <h5 class="feature-title">Email Address</h5>
                            <p class="feature-description"><?php echo e($item->contacts->email); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($item->contacts->web): ?>
                    <div class="feature-item" onclick="window.open('<?php echo e($item->contacts->web); ?>', '_blank')">
                        <div class="feature-icon">
                            <i class="fas fa-globe"></i>
                        </div>
                        <div class="feature-content">
                            <h5 class="feature-title">Website</h5>
                            <p class="feature-description">Visit our website</p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($item->contacts->address): ?>
                    <div class="feature-item" onclick="document.querySelector('[data-tab=\"location\"]').click()">
                        <div class="feature-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="feature-content">
                            <h5 class="feature-title">Address</h5>
                            <p class="feature-description"><?php echo e($item->contacts->address); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Contact Tab -->
            <div class="tab-content" id="contact">
                <?php if($item->contacts): ?>
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="modern-card h-100 p-4">
                            <h4 class="mb-4"><i class="fas fa-address-book me-2"></i>Contact Details</h4>
                            <div class="feature-grid">
                                <?php if($item->contacts->telephone): ?>
                                <div class="feature-item" onclick="window.location.href='tel:<?php echo e($item->contacts->telephone); ?>'">
                                    <div class="feature-icon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div class="feature-content">
                                        <h5 class="feature-title">Primary Phone</h5>
                                        <p class="feature-description"><?php echo e($item->contacts->telephone); ?></p>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if($item->contacts->phone1): ?>
                                <div class="feature-item" onclick="window.location.href='tel:<?php echo e($item->contacts->phone1); ?>'">
                                    <div class="feature-icon">
                                        <i class="fas fa-mobile-alt"></i>
                                    </div>
                                    <div class="feature-content">
                                        <h5 class="feature-title">Additional Phone 1</h5>
                                        <p class="feature-description"><?php echo e($item->contacts->phone1); ?></p>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if($item->contacts->phone2): ?>
                                <div class="feature-item" onclick="window.location.href='tel:<?php echo e($item->contacts->phone2); ?>'">
                                    <div class="feature-icon">
                                        <i class="fas fa-phone-square-alt"></i>
                                    </div>
                                    <div class="feature-content">
                                        <h5 class="feature-title">Additional Phone 2</h5>
                                        <p class="feature-description"><?php echo e($item->contacts->phone2); ?></p>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if($item->contacts->email): ?>
                                <div class="feature-item" onclick="window.location.href='mailto:<?php echo e($item->contacts->email); ?>'">
                                    <div class="feature-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="feature-content">
                                        <h5 class="feature-title">Email Address</h5>
                                        <p class="feature-description"><?php echo e($item->contacts->email); ?></p>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if($item->contacts->web): ?>
                                <div class="feature-item" onclick="window.open('<?php echo e($item->contacts->web); ?>', '_blank')">
                                    <div class="feature-icon">
                                        <i class="fas fa-globe"></i>
                                    </div>
                                    <div class="feature-content">
                                        <h5 class="feature-title">Website</h5>
                                        <p class="feature-description">Visit our website</p>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="modern-card h-100 p-4">
                            <h4 class="mb-4"><i class="fas fa-map-marker-alt me-2"></i>Location Details</h4>
                            <?php if($item->contacts->address): ?>
                            <div class="feature-item mb-4">
                                <div class="feature-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="feature-content">
                                    <h5 class="feature-title">Full Address</h5>
                                    <p class="feature-description"><?php echo e($item->contacts->address); ?></p>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if($item->contacts->latitude && $item->contacts->longitude): ?>
                            <div class="modern-card p-4 mt-4">
                                <h6 class="mb-3">Coordinates</h6>
                                <div class="row text-center">
                                    <div class="col-6">
                                        <small class="text-muted d-block">Latitude</small>
                                        <strong><?php echo e($item->contacts->latitude); ?></strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Longitude</small>
                                        <strong><?php echo e($item->contacts->longitude); ?></strong>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-info-circle fa-4x text-muted mb-4"></i>
                    <h4>No Contact Information Available</h4>
                    <p class="text-muted">Contact details for this item are not available.</p>
                </div>
                <?php endif; ?>
            </div>

           
<?php if(count($allGalleryImages) > 0): ?>
<div class="tab-content" id="gallery">
    <div class="gallery-section">
        <!-- Storage Method Indicator -->
        <div class="alert alert-info mb-4">
            <i class="fas fa-info-circle me-2"></i>
            Found <?php echo e(count($allGalleryImages)); ?> images
            (<?php echo e(count(array_filter($allGalleryImages, fn($img) => $img['type'] === 'individual'))); ?> individual records,
            <?php echo e(count(array_filter($allGalleryImages, fn($img) => $img['type'] === 'array'))); ?> from arrays)
        </div>

        <div class="gallery-grid" id="lightgallery">
            <?php $__currentLoopData = $allGalleryImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $imageData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="gallery-item"
                     data-src="<?php echo e($imageData['url']); ?>"
                     data-sub-html="<h4>Image <?php echo e($index + 1); ?></h4><p>Storage: <?php echo e(ucfirst($imageData['type'])); ?></p>">

                    <img src="<?php echo e($imageData['url']); ?>"
                         alt="Gallery Image <?php echo e($index + 1); ?>"
                         loading="lazy"
                         onerror="this.src='<?php echo e(asset('admin/images/logo-sm.png')); ?>'">

                    <div class="gallery-overlay">
                        <div class="text-white">
                            <h6 class="mb-1">Image <?php echo e($index + 1); ?></h6>
                            <small>
                                <?php if($imageData['type'] === 'individual'): ?>
                                    <i class="fas fa-database me-1" title="Individual Record"></i>
                                <?php elseif($imageData['type'] === 'array'): ?>
                                    <i class="fas fa-layer-group me-1" title="Array Storage"></i>
                                <?php else: ?>
                                    <i class="fas fa-download me-1" title="Local File"></i>
                                <?php endif; ?>
                                <?php echo e(ucfirst($imageData['type'])); ?> Storage
                            </small>
                        </div>
                    </div>

                    <div class="gallery-actions">
                        <button class="gallery-action-btn" onclick="event.stopPropagation(); window.open('<?php echo e($imageData['url']); ?>', '_blank')">
                            <i class="fas fa-external-link-alt"></i>
                        </button>
                        <button class="gallery-action-btn" onclick="event.stopPropagation(); downloadImage('<?php echo e($imageData['url']); ?>', 'image-<?php echo e($index + 1); ?>')">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="text-center mt-4">
            <p class="text-muted">
                <i class="fas fa-images me-2"></i>
                Displaying <?php echo e(count($allGalleryImages)); ?> images from multiple storage methods
            </p>
        </div>
    </div>
</div>
<?php else: ?>
<div class="tab-content" id="gallery">
    <div class="text-center py-5">
        <i class="fas fa-images fa-4x text-muted mb-4"></i>
        <h4>No Gallery Images Available</h4>
        <p class="text-muted mb-4">This item doesn't have any gallery images yet.</p>

        <!-- Debug info for admins -->
        <?php if(Auth::check() && Auth::user()->isAdmin()): ?>
            <div class="modern-card p-4 d-inline-block text-start">
                <h6 class="mb-3">Debug Information:</h6>
                <p class="small text-muted mb-1">Total Gallery Records: <?php echo e($item->galleries->count()); ?></p>
                <p class="small text-muted mb-1">Individual Records: <?php echo e($item->galleries->where('image_url', '!=', null)->count()); ?></p>
                <p class="small text-muted mb-1">Array Records: <?php echo e($item->galleries->where('gallery', '!=', null)->count()); ?></p>
                <p class="small text-muted">Local Files: <?php echo e($item->galleries->where('local_path', '!=', null)->count()); ?></p>
            </div>
        <?php endif; ?>

        <?php if($item->image): ?>
        <div class="modern-card p-4 d-inline-block mt-3">
            <p class="mb-2">But we found the main image:</p>
            <img src="<?php echo e(asset('storage/' . $item->image)); ?>"
                 alt="<?php echo e($item->title); ?>"
                 class="rounded"
                 style="max-height: 200px;">
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
           <!-- Location Tab -->
<!-- Location Tab -->
<?php if($item->contacts && ($item->contacts->latitude || $item->contacts->address)): ?>
<div class="tab-content" id="location">
    <?php if($item->contacts->latitude && $item->contacts->longitude): ?>
    <div class="map-container">
        <div id="openstreet-map"></div>
    </div>
    <?php else: ?>
    <div class="alert alert-warning mb-4">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Map coordinates not available. Showing address information only.
    </div>
    <?php endif; ?>

    <?php if($item->contacts->address): ?>
    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="modern-card p-4">
                <h4><i class="fas fa-map-marker-alt me-2"></i>Location Information</h4>
                <p class="mb-3 fs-5">
                    <i class="fas fa-location-arrow text-primary me-2"></i>
                    <?php echo e($item->contacts->address); ?>

                </p>
                <div class="row mt-4">
                    <?php if($item->contacts->latitude && $item->contacts->longitude): ?>
                    <div class="col-md-6">
                        <div class="modern-card p-3 text-center">
                            <small class="text-muted d-block">Latitude</small>
                            <strong class="fs-5"><?php echo e($item->contacts->latitude); ?></strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="modern-card p-3 text-center">
                            <small class="text-muted d-block">Longitude</small>
                            <strong class="fs-5"><?php echo e($item->contacts->longitude); ?></strong>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Additional Location Details -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="modern-card p-3">
                            <h6 class="mb-3"><i class="fas fa-info-circle me-2"></i>Location Services</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-primary">Google Maps</span>
                                <span class="badge bg-success">Directions</span>
                                <span class="badge bg-info">Street View</span>
                                <?php if($item->contacts->latitude && $item->contacts->longitude): ?>
                                <span class="badge bg-success">Coordinates Available</span>
                                <?php else: ?>
                                <span class="badge bg-warning">Address Only</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="modern-card p-4 h-100">
                <h5 class="mb-3"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                <div class="d-grid gap-2">
                    <?php if($item->contacts->latitude && $item->contacts->longitude): ?>
                    <button class="btn btn-primary btn-lg" onclick="getGoogleDirections()">
                        <i class="fas fa-route me-2"></i>
                        Get Directions
                    </button>
                    <button class="btn btn-outline-primary" onclick="openGoogleMaps()">
                        <i class="fab fa-google me-2"></i>
                        Open in Google Maps
                    </button>
                    <button class="btn btn-outline-info" onclick="openGoogleStreetView()">
                        <i class="fas fa-street-view me-2"></i>
                        Street View
                    </button>
                    <?php else: ?>
                    <button class="btn btn-primary btn-lg" onclick="getGoogleDirectionsByAddress()">
                        <i class="fas fa-route me-2"></i>
                        Get Directions by Address
                    </button>
                    <button class="btn btn-outline-primary" onclick="openGoogleMapsByAddress()">
                        <i class="fab fa-google me-2"></i>
                        Open in Google Maps
                    </button>
                    <?php endif; ?>
                    <button class="btn btn-outline-secondary" onclick="copyAddress('<?php echo e($item->contacts->address); ?>')">
                        <i class="fas fa-copy me-2"></i>
                        Copy Address
                    </button>
                    <button class="btn btn-outline-success" onclick="shareLocation()">
                        <i class="fas fa-share-alt me-2"></i>
                        Share Location
                    </button>
                </div>

                <!-- Quick Stats -->
                <div class="mt-4 pt-3 border-top">
                    <h6 class="mb-3"><i class="fas fa-chart-bar me-2"></i>Location Stats</h6>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <small class="text-muted d-block">Map Type</small>
                                <strong>Google Maps</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <small class="text-muted d-block">Provider</small>
                                <strong>Google</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php else: ?>
<div class="tab-content" id="location">
    <div class="text-center py-5">
        <i class="fas fa-map-marker-alt fa-4x text-muted mb-4"></i>
        <h4>No Location Information Available</h4>
        <p class="text-muted">This item doesn't have any location details.</p>
    </div>
</div>
<?php endif; ?>

            <!-- Social Media Tab -->
            <?php if($item->socialIcons && $item->socialIcons->count() > 0): ?>
            <div class="tab-content" id="social">
                <div class="social-hub">
                    <?php $__currentLoopData = $item->socialIcons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $social): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e($social->socialIcons_url); ?>"
                       target="<?php echo e($social->socialIconsOpenInNewWindow ? '_blank' : '_self'); ?>"
                       class="social-card <?php echo e($social->socialIcons); ?>">
                        <i class="fab fa-<?php echo e($social->socialIcons); ?> social-icon"></i>
                        <h5 class="mb-2"><?php echo e(ucfirst($social->socialIcons)); ?></h5>
                        <p class="mb-0 opacity-90">Follow us on <?php echo e(ucfirst($social->socialIcons)); ?></p>
                        <small class="opacity-75 mt-2 d-block"><?php echo e(Str::limit($social->socialIcons_url, 30)); ?></small>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Opening Hours Tab -->
            <?php if($item->openingTimes): ?>
            <div class="tab-content" id="hours">
                <div class="opening-hours">
                    <h4 class="mb-4"><i class="fas fa-clock me-2"></i>Opening Hours</h4>
                    <div class="timeline">
                        <?php
                            $days = [
                                'Monday' => $item->openingTimes->openingHoursMonday,
                                'Tuesday' => $item->openingTimes->openingHoursTuesday,
                                'Wednesday' => $item->openingTimes->openingHoursWednesday,
                                'Thursday' => $item->openingTimes->openingHoursThursday,
                                'Friday' => $item->openingTimes->openingHoursFriday,
                                'Saturday' => $item->openingTimes->openingHoursSaturday,
                                'Sunday' => $item->openingTimes->openingHoursSunday,
                            ];
                        ?>
                        <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day => $time): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($time && $time !== '-'): ?>
                            <div class="timeline-item <?php echo e(in_array(strtolower($time), ['closed', 'close', 'holiday']) ? 'closed' : ''); ?>">
                                <div class="timeline-day"><?php echo e($day); ?></div>
                                <div class="timeline-time">
                                    <?php if(in_array(strtolower($time), ['closed', 'close', 'holiday'])): ?>
                                        <span class="text-danger">Closed</span>
                                    <?php else: ?>
                                        <?php echo e($time); ?>

                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php if($item->openingTimes->openingHoursNote): ?>
                    <div class="modern-card p-4 mt-4">
                        <div class="d-flex align-items-start gap-3">
                            <i class="fas fa-info-circle text-primary mt-1"></i>
                            <div>
                                <h6 class="mb-2">Additional Information</h6>
                                <p class="text-muted mb-0"><?php echo e($item->openingTimes->openingHoursNote); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Related Items Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="text-center mb-5">
                    <h3 class="display-5 fw-bold mb-3">Related Items</h3>
                    <p class="text-muted fs-5">Discover similar businesses and services you might like</p>
                </div>
                <div id="related-items-container" class="row g-4">
                    <!-- Related items will be loaded here via JavaScript -->
                </div>
                <div id="loading-spinner" class="loading-spinner">
                    <div class="spinner"></div>
                    <p class="mt-3 text-muted">Loading related items...</p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.1/lightgallery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.1/plugins/thumbnail/lg-thumbnail.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.1/plugins/zoom/lg-zoom.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.1/plugins/fullscreen/lg-fullscreen.min.js"></script>

<script>
// Main initialization
document.addEventListener("DOMContentLoaded", function() {
    console.log('🚀 Initializing Modern Item View...');

    initializeAnimations();
    initializeTabs();
    initializeGallery();
    initializeRelatedItems();
    initializePerformanceOptimizations();
});

// Enhanced animations with Intersection Observer
function initializeAnimations() {
    const fadeObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0) scale(1)';
            }
        });
    }, { threshold: 0.1, rootMargin: '50px' });

    document.querySelectorAll('.fade-up').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px) scale(0.95)';
        el.style.transition = 'all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
        fadeObserver.observe(el);
    });
}

// Enhanced tab functionality
function initializeTabs() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tabId = button.getAttribute('data-tab');

            // Update active states
            tabButtons.forEach(btn => {
                btn.classList.remove('active');
                btn.style.transition = 'all 0.3s ease';
            });
            button.classList.add('active');

            // Show active content with animation
            tabContents.forEach(content => {
                if (content.id === tabId) {
                    content.style.display = 'block';
                    content.style.opacity = '0';
                    content.classList.add('active');

                    setTimeout(() => {
                        content.style.transition = 'opacity 0.3s ease';
                        content.style.opacity = '1';
                    }, 50);

                    // Initialize specific tab content
                    if (tabId === 'gallery') {
                        setTimeout(initializeGallery, 100);
                    } else if (tabId === 'location') {
                        setTimeout(initializeTomTomMap, 100);
                    }
                } else {
                    content.classList.remove('active');
                    content.style.display = 'none';
                }
            });

            // Update URL hash without scrolling
            window.history.replaceState(null, null, `#${tabId}`);

            // Track tab view
            trackEvent('tab_view', { tab: tabId, item_id: <?php echo e($item->id); ?> });
        });
    });

    // Handle initial tab from URL hash
    const initialTab = window.location.hash.substring(1);
    if (initialTab) {
        const initialTabBtn = document.querySelector(`.tab-button[data-tab="${initialTab}"]`);
        if (initialTabBtn) {
            setTimeout(() => initialTabBtn.click(), 300);
        }
    }
}

// Map Utilities Class
class MapUtils {
    static getTomTomKey() {
        // Get the key from Laravel config
        return '<?php echo e(config("services.tomtom.key", "eV5ZiNE8kKeHSBqvzXL4ZbvN9D0JMZOf")); ?>';
    }

    static openDirections(latitude, longitude, address = null) {
        if (latitude && longitude) {
            const url = `https://www.google.com/maps/dir/?api=1&destination=${latitude},${longitude}&travelmode=driving`;
            window.open(url, '_blank');
        } else if (address) {
            const url = `https://www.google.com/maps/dir/?api=1&destination=${encodeURIComponent(address)}&travelmode=driving`;
            window.open(url, '_blank');
        } else {
            this.showToast('No location information available', 'error');
        }
    }

    static openMapView(latitude, longitude, address = null) {
        if (latitude && longitude) {
            const url = `https://www.google.com/maps/search/?api=1&query=${latitude},${longitude}`;
            window.open(url, '_blank');
        } else if (address) {
            const url = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(address)}`;
            window.open(url, '_blank');
        } else {
            this.showToast('No location information available', 'error');
        }
    }

    static openStreetView(latitude, longitude) {
        if (latitude && longitude) {
            const url = `https://www.google.com/maps/@?api=1&map_action=pano&viewpoint=${latitude},${longitude}`;
            window.open(url, '_blank');
        } else {
            this.showToast('Coordinates required for Street View', 'error');
        }
    }

    static showToast(message, type = 'info') {
        // Remove existing toasts
        const existingToasts = document.querySelectorAll('.map-toast');
        existingToasts.forEach(toast => toast.remove());

        // Create new toast
        const toast = document.createElement('div');
        toast.className = `map-toast toast-${type}`;
        toast.textContent = message;

        // Basic toast styles
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'error' ? '#dc3545' : type === 'success' ? '#28a745' : '#007bff'};
            color: white;
            padding: 12px 20px;
            border-radius: 4px;
            z-index: 10000;
            font-family: system-ui, -apple-system, sans-serif;
            font-size: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            max-width: 300px;
            word-wrap: break-word;
        `;

        document.body.appendChild(toast);

        // Auto remove after 3 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 3000);
    }
}

// TomTom Maps Initialization (Primary Map)
function initializeTomTomMap() {
    const mapContainer = document.getElementById('openstreet-map');
    if (!mapContainer) {
        console.error('Map container not found');
        return;
    }

    const latitude = <?php echo e($item->contacts->latitude ?? '27.7172'); ?>;
    const longitude = <?php echo e($item->contacts->longitude ?? '85.3240'); ?>;
    const address = `<?php echo e($item->contacts->address ?? ''); ?>`;
    const tomtomKey = MapUtils.getTomTomKey();

    console.log('Initializing TomTom map with key:', tomtomKey ? 'Key available' : 'No key');

    try {
        // Check if TomTom library is loaded
        if (typeof tt === 'undefined') {
            console.error('TomTom maps library not loaded');
            showMapFallback('TomTom maps library failed to load');
            return;
        }

        // Check if API key is valid
        if (!tomtomKey || tomtomKey.includes('YOUR_TOMTOM_API_KEY')) {
            console.error('TomTom API key not configured properly');
            showMapFallback('TomTom API key not configured');
            return;
        }

        // Clear any existing map
        if (window.tomTomMap) {
            window.tomTomMap.remove();
        }

        // Initialize TomTom map
        const map = tt.map({
            key: tomtomKey,
            container: 'openstreet-map',
            center: [longitude, latitude],
            zoom: 15,
            style: 'tomtom://vector/1/basic-main',
            language: 'en-GB'
        });

        window.tomTomMap = map;

        // Add marker
        const marker = new tt.Marker()
            .setLngLat([longitude, latitude])
            .addTo(map);

        // Create popup content
        const popupContent = `
            <div style="padding: 12px; min-width: 250px; font-family: system-ui, sans-serif;">
                <h4 style="margin: 0 0 8px 0; color: #1e293b; font-size: 16px; font-weight: 700;">
                    <?php echo e($item->title ?? 'Location'); ?>

                </h4>
                ${address ? `
                <p style="margin: 0 0 12px 0; color: #64748b; font-size: 14px; line-height: 1.4;">
                    <i class="fas fa-map-marker-alt" style="color: #6366f1; margin-right: 8px;"></i>
                    ${address}
                </p>
                ` : ''}
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    <button onclick="MapUtils.openDirections(${latitude}, ${longitude})"
                            style="flex: 1; padding: 8px 12px; background: #6366f1; color: white; border: none; border-radius: 8px; font-size: 12px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; min-width: 100px; transition: all 0.3s ease;">
                        <i class="fas fa-route"></i>
                        Directions
                    </button>
                    <button onclick="MapUtils.openMapView(${latitude}, ${longitude})"
                            style="flex: 1; padding: 8px 12px; background: white; color: #6366f1; border: 1px solid #6366f1; border-radius: 8px; font-size: 12px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; min-width: 100px; transition: all 0.3s ease;">
                        <i class="fab fa-google"></i>
                        Google Maps
                    </button>
                </div>
            </div>
        `;

        // Add popup to marker
        const popup = new tt.Popup({
            offset: 30,
            className: 'custom-tomtom-popup'
        }).setHTML(popupContent);

        marker.setPopup(popup);

        // Add click event to marker
        marker.getElement().addEventListener('click', () => {
            popup.addTo(map);
        });

        // Add navigation control
        map.addControl(new tt.NavigationControl());

        // Add scale control
        map.addControl(new tt.ScaleControl());

        console.log('🗺️ TomTom map initialized successfully');

        // Add map loaded event
        map.on('load', () => {
            console.log('TomTom map fully loaded');
            mapContainer.style.opacity = '1';
        });

    } catch (error) {
        console.error('Failed to initialize TomTom map:', error);
        MapUtils.showToast('TomTom map failed to load. Using fallback.', 'error');
        showMapFallback(error.message);
    }
}

// Show map fallback when TomTom fails
function showMapFallback(errorMessage = '') {
    const mapContainer = document.getElementById('openstreet-map');
    if (!mapContainer) return;

    console.log('Showing map fallback:', errorMessage);

    mapContainer.innerHTML = `
        <div style="text-align: center; padding: 40px 20px; background: #f8f9fa; border-radius: 8px; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center;">
            <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
            <h4 style="color: #6c757d; margin-bottom: 1rem;">Interactive Map Unavailable</h4>
            <p class="text-muted mb-3" style="max-width: 400px;">
                ${errorMessage || 'Unable to load interactive map at this time.'}
            </p>
            <div class="d-flex gap-2 justify-content-center flex-wrap">
                <button onclick="MapUtils.openMapView(<?php echo e($item->contacts->latitude ?? 'null'); ?>, <?php echo e($item->contacts->longitude ?? 'null'); ?>)"
                        class="btn btn-primary"
                        style="padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    <i class="fab fa-google"></i> Open in Google Maps
                </button>
                <button onclick="copyAddress()" class="btn btn-outline-secondary"
                        style="padding: 10px 20px; background: transparent; color: #6c757d; border: 1px solid #6c757d; border-radius: 4px; cursor: pointer;">
                    <i class="fas fa-copy"></i> Copy Address
                </button>
            </div>
        </div>
    `;
}

// Individual functions for HTML onclick attributes
function getGoogleDirections() {
    const latitude = <?php echo e($item->contacts->latitude ?? 'null'); ?>;
    const longitude = <?php echo e($item->contacts->longitude ?? 'null'); ?>;
    MapUtils.openDirections(latitude, longitude);
}

function getGoogleDirectionsByAddress() {
    const address = `<?php echo e($item->contacts->address); ?>`;
    MapUtils.openDirections(null, null, address);
}

function openGoogleMaps() {
    const latitude = <?php echo e($item->contacts->latitude ?? 'null'); ?>;
    const longitude = <?php echo e($item->contacts->longitude ?? 'null'); ?>;
    MapUtils.openMapView(latitude, longitude);
}

function openGoogleMapsByAddress() {
    const address = `<?php echo e($item->contacts->address); ?>`;
    MapUtils.openMapView(null, null, address);
}

function openGoogleStreetView() {
    const latitude = <?php echo e($item->contacts->latitude ?? 'null'); ?>;
    const longitude = <?php echo e($item->contacts->longitude ?? 'null'); ?>;
    MapUtils.openStreetView(latitude, longitude);
}

function copyAddress() {
    const address = `<?php echo e($item->contacts->address); ?>`;
    if (address) {
        navigator.clipboard.writeText(address).then(() => {
            MapUtils.showToast('Address copied to clipboard!', 'success');
        }).catch(() => {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = address;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            MapUtils.showToast('Address copied to clipboard!', 'success');
        });
    } else {
        MapUtils.showToast('No address available to copy', 'error');
    }
}

// Enhanced Share Location Function
async function shareLocation() {
    const latitude = <?php echo e($item->contacts->latitude ?? 'null'); ?>;
    const longitude = <?php echo e($item->contacts->longitude ?? 'null'); ?>;
    const address = `<?php echo e($item->contacts->address); ?>`;
    const title = `<?php echo e(addslashes($item->title)); ?>`;

    let shareText = `${title}\n\n`;
    let mapsUrl = '';

    if (latitude && longitude) {
        shareText += `Coordinates: ${latitude}, ${longitude}\n`;
        mapsUrl = `https://www.google.com/maps/search/?api=1&query=${latitude},${longitude}`;

        if (address) {
            shareText += `Address: ${address}\n`;
        }
    } else if (address) {
        shareText += `Address: ${address}\n`;
        mapsUrl = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(address)}`;
    } else {
        MapUtils.showToast('No location information available to share', 'error');
        return;
    }

    shareText += `View on Maps: ${mapsUrl}\n`;
    shareText += `Shared from: ${window.location.href}`;

    // Try Web Share API first
    if (navigator.share) {
        try {
            await navigator.share({
                title: title,
                text: shareText,
                url: mapsUrl
            });
            MapUtils.showToast('Location shared successfully!', 'success');
            return;
        } catch (error) {
            if (error.name !== 'AbortError') {
                console.log('Web Share failed, falling back to clipboard');
            } else {
                return; // User cancelled share
            }
        }
    }

    // Fallback to clipboard
    try {
        await navigator.clipboard.writeText(shareText);
        MapUtils.showToast('Location details copied to clipboard!', 'success');
    } catch (error) {
        // Final fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = shareText;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        textArea.style.top = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            const successful = document.execCommand('copy');
            document.body.removeChild(textArea);

            if (successful) {
                MapUtils.showToast('Location details copied to clipboard!', 'success');
            } else {
                throw new Error('Copy command failed');
            }
        } catch (err) {
            document.body.removeChild(textArea);
            MapUtils.showToast('Failed to share location. Please copy manually.', 'error');
            console.error('Copy failed:', err);
        }
    }
}

// Map Initialization Manager
document.addEventListener('DOMContentLoaded', function() {
    let mapInitialized = false;
    const locationTab = document.querySelector('[data-tab="location"]');

    function initMap() {
        if (!mapInitialized) {
            initializeTomTomMap();
            mapInitialized = true;
        }
    }

    // Initialize when location tab is clicked
    if (locationTab) {
        locationTab.addEventListener('click', function() {
            setTimeout(initMap, 100);
        });
    }

    // Initialize immediately if location tab is active on page load
    if (locationTab && locationTab.classList.contains('active')) {
        initMap();
    }

    // Alternative: Initialize when map container becomes visible
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !mapInitialized) {
                initMap();
            }
        });
    });

    const mapContainer = document.getElementById('openstreet-map');
    if (mapContainer) {
        observer.observe(mapContainer);
    }
});

// Enhanced Gallery with LightGallery
function initializeGallery() {
    const galleryContainer = document.getElementById('lightgallery');
    if (!galleryContainer) {
        console.log('No gallery container found');
        return;
    }

    try {
        // Initialize LightGallery
        const gallery = lightGallery(galleryContainer, {
            plugins: [lgZoom, lgThumbnail, lgFullscreen],
            speed: 500,
            thumbnail: true,
            animateThumb: true,
            zoomFromOrigin: false,
            allowMediaOverlap: false,
            toggleThumb: true,
            showZoomInOutIcons: true,
            actualSize: false,
            escKey: true,
            keyPress: true,
            controls: true,
            download: true,
            counter: true,
            swipeThreshold: 50,
            enableSwipe: true,
            enableDrag: true,
            closable: true,
            showAfterLoad: true,
            selector: '.gallery-item',
            exThumbImage: 'data-src'
        });

        console.log('🖼️ Gallery initialized with', galleryContainer.children.length, 'items');

        // Track gallery interactions
        galleryContainer.addEventListener('click', (e) => {
            if (e.target.closest('.gallery-item')) {
                trackEvent('gallery_image_click', {
                    item_id: <?php echo e($item->id); ?>,
                    total_images: galleryContainer.children.length
                });
            }
        });

    } catch (error) {
        console.error('Gallery initialization failed:', error);

        // Fallback to basic modal
        galleryContainer.querySelectorAll('.gallery-item').forEach(item => {
            item.addEventListener('click', function() {
                const imgSrc = this.getAttribute('data-src');
                openImageModal(imgSrc);
            });
        });
    }
}

// Basic image modal fallback
function openImageModal(src) {
    const modal = document.createElement('div');
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        cursor: zoom-out;
    `;

    const img = document.createElement('img');
    img.src = src;
    img.style.maxWidth = '90%';
    img.style.maxHeight = '90%';
    img.style.objectFit = 'contain';
    img.style.borderRadius = '8px';

    modal.appendChild(img);
    modal.addEventListener('click', () => document.body.removeChild(modal));
    document.body.appendChild(modal);
}

// Related items loading with Intersection Observer
function initializeRelatedItems() {
    const container = document.getElementById('related-items-container');
    const spinner = document.getElementById('loading-spinner');

    if (!container || !spinner) return;

    const observer = new IntersectionObserver(async (entries) => {
        if (entries[0].isIntersecting) {
            observer.unobserve(spinner);
            await loadRelatedItems();
        }
    }, { threshold: 0.1 });

    observer.observe(spinner);
}

async function loadRelatedItems() {
    const container = document.getElementById('related-items-container');
    const spinner = document.getElementById('loading-spinner');

    if (!container) return;

    try {
        spinner.style.display = 'block';

        // Simulate API call - replace with actual endpoint
        await new Promise(resolve => setTimeout(resolve, 1500));

        // Mock data - replace with actual data from your backend
        const mockItems = [
            {
                title: "Similar Business 1",
                subtitle: "Related service in same category",
                image: "<?php echo e(asset('admin/images/logo-sm.png')); ?>",
                url: "#"
            },
            {
                title: "Similar Business 2",
                subtitle: "Another great option nearby",
                image: "<?php echo e(asset('admin/images/logo-sm.png')); ?>",
                url: "#"
            },
            {
                title: "Similar Business 3",
                subtitle: "Popular choice in this area",
                image: "<?php echo e(asset('admin/images/logo-sm.png')); ?>",
                url: "#"
            }
        ];

        container.innerHTML = mockItems.map(item => `
            <div class="col-lg-4 col-md-6">
                <div class="modern-card h-100">
                    <img src="${item.image}"
                         alt="${item.title}"
                         class="card-img-top"
                         style="height: 200px; object-fit: cover;">
                    <div class="card-body p-4">
                        <h5 class="card-title">${item.title}</h5>
                        <p class="card-text text-muted">${item.subtitle}</p>
                        <a href="${item.url}" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        `).join('');

        spinner.style.display = 'none';

    } catch (error) {
        console.error('Failed to load related items:', error);
        spinner.innerHTML = `
            <div class="text-center text-muted">
                <i class="fas fa-exclamation-circle me-2"></i>
                Unable to load related items
            </div>
        `;
    }
}

// Performance optimizations
function initializePerformanceOptimizations() {
    // Lazy load images
    const lazyImages = document.querySelectorAll('img[loading="lazy"]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                }
                img.classList.add('loaded');
                observer.unobserve(img);
            }
        });
    }, { rootMargin: '100px' });

    lazyImages.forEach(img => imageObserver.observe(img));

    // Preload critical resources
    const criticalResources = [
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css'
    ];

    criticalResources.forEach(resource => {
        const link = document.createElement('link');
        link.rel = 'preload';
        link.href = resource;
        link.as = 'style';
        document.head.appendChild(link);
    });

    // Optimize scroll performance
    let ticking = false;
    window.addEventListener('scroll', () => {
        if (!ticking) {
            requestAnimationFrame(() => {
                updateParallax();
                ticking = false;
            });
            ticking = true;
        }
    }, { passive: true });
}

// Parallax effect for hero
function updateParallax() {
    const hero = document.querySelector('.modern-hero');
    if (hero) {
        const scrolled = window.pageYOffset;
        const rate = scrolled * -0.1;
        hero.style.transform = `translateY(${rate}px)`;
    }
}

// Utility functions
function downloadImage(url, filename) {
    fetch(url)
        .then(response => response.blob())
        .then(blob => {
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = filename;
            link.click();
            URL.revokeObjectURL(link.href);
            trackEvent('image_download', { item_id: <?php echo e($item->id); ?> });
        })
        .catch(() => MapUtils.showToast('Failed to download image', 'error'));
}

function trackEvent(action, data = {}) {
    if (typeof gtag !== 'undefined') {
        gtag('event', action, data);
    }

    // Send to your analytics endpoint
    fetch('<?php echo e(route("track.action")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({
            action: action,
            data: data,
            item_id: <?php echo e($item->id); ?>,
            url: window.location.href
        })
    }).catch(() => {}); // Fail silently
}

// Error boundary
window.addEventListener('error', (e) => {
    console.error('Global error:', e.error);
    trackEvent('javascript_error', {
        message: e.error?.message,
        item_id: <?php echo e($item->id); ?>

    });
});

// Add custom styles for TomTom map
const tomtomStyles = `
    .custom-tomtom-popup .tt-popup-content {
        border-radius: 12px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    .tt-popup-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;

const styleSheet = document.createElement('style');
styleSheet.textContent = tomtomStyles;
document.head.appendChild(styleSheet);

console.log('🎉 Modern Item View initialized successfully!');
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('web.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Online_Saptari\resources\views/web/items/userview.blade.php ENDPATH**/ ?>