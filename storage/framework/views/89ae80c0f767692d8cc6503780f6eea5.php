<?php use Illuminate\Support\Str; ?>

<?php $__env->startSection('title', $item->title . ' - View Item'); ?>

<?php $__env->startSection('css'); ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.css" />

<style>
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --info-gradient: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
    --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
}

body {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
}

.glass-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.glass-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}

.stat-card {
    background: var(--primary-gradient);
    color: white;
    border-radius: 15px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

.stat-card i {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    opacity: 0.9;
}

.badge-featured {
    background: var(--warning-gradient);
    animation: pulse 2s infinite;
    font-size: 0.8rem;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.gallery-img {
    border-radius: 12px;
    transition: all 0.3s ease;
    cursor: pointer;
    height: 200px;
    object-fit: cover;
}

.gallery-img:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}

.map-container {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.social-link {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    margin: 0.25rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 25px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
}

.social-link:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}

.accordion-glass {
    background: rgba(255, 255, 255, 0.8) !important;
    border: 1px solid rgba(255, 255, 255, 0.3) !important;
    border-radius: 10px !important;
    margin-bottom: 0.5rem;
}

.accordion-glass .accordion-button {
    background: transparent !important;
    border: none !important;
    border-radius: 10px !important;
    font-weight: 600;
}

.accordion-glass .accordion-button:not(.collapsed) {
    background: rgba(255, 255, 255, 0.3) !important;
    color: #667eea;
}

.info-chip {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    background: rgba(102, 126, 234, 0.1);
    border-radius: 20px;
    margin: 0.25rem;
    font-size: 0.9rem;
}

.info-chip i {
    margin-right: 0.5rem;
    color: #667eea;
}

.floating-action {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    z-index: 1000;
}

.action-btn {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: none;
    background: var(--primary-gradient);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
}

.action-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
}

.contact-badge {
    background: var(--success-gradient);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    margin: 0.25rem;
}

.contact-badge i {
    margin-right: 0.5rem;
}

.content-preview {
    background: rgba(248, 249, 250, 0.8);
    border-radius: 10px;
    padding: 1rem;
    max-height: 200px;
    overflow-y: auto;
    border-left: 4px solid #667eea;
}

.leaflet-popup-content {
    font-family: 'Segoe UI', sans-serif;
}

.leaflet-marker-icon {
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">

    <?php echo $__env->make('common.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-1"><?php echo e($item->title); ?></h1>
                    <p class="text-muted mb-0"><?php echo e($item->subtitle); ?></p>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?php echo e(route('item.edit', encrypt($item->id))); ?>" class="btn btn-primary">
                        <i class="ri-edit-line me-1"></i> Edit Item
                    </a>
                    <a href="<?php echo e(route('item.index')); ?>" class="btn btn-outline-secondary">
                        <i class="ri-arrow-left-line me-1"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 col-6">
            <div class="stat-card">
                <i class="ri-image-line"></i>
                <h4 class="mb-0"><?php echo e($stats['total_gallery_images']); ?></h4>
                <small>Gallery Images</small>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="stat-card" style="background: var(--success-gradient);">
                <i class="ri-share-line"></i>
                <h4 class="mb-0"><?php echo e($stats['social_links_count']); ?></h4>
                <small>Social Links</small>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="stat-card" style="background: var(--warning-gradient);">
                <i class="ri-time-line"></i>
                <h4 class="mb-0"><?php echo e($stats['has_opening_hours'] ? 'Yes' : 'No'); ?></h4>
                <small>Opening Hours</small>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="stat-card" style="background: var(--info-gradient);">
                <i class="ri-contacts-line"></i>
                <h4 class="mb-0"><?php echo e($stats['has_contact_info'] ? 'Yes' : 'No'); ?></h4>
                <small>Contact Info</small>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="stat-card" style="background: var(--dark-gradient);">
                <i class="ri-star-line"></i>
                <h4 class="mb-0"><?php echo e($stats['is_featured'] ? 'Yes' : 'No'); ?></h4>
                <small>Featured</small>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <i class="ri-calendar-line"></i>
                <h4 class="mb-0"><?php echo e($item->collection_date ? \Carbon\Carbon::parse($item->collection_date)->format('M j') : 'N/A'); ?></h4>
                <small>Collection Date</small>
            </div>
        </div>
    </div>

    <div class="row g-4">

        <!-- Basic Information -->
        <div class="col-xl-4 col-lg-6">
            <div class="glass-card p-4 h-100">
                <div class="d-flex align-items-center mb-3">
                    <i class="ri-information-line text-primary me-2 fs-4"></i>
                    <h5 class="mb-0 text-primary">Basic Information</h5>
                </div>

                <div class="mb-3">
                    <span class="info-chip">
                        <i class="ri-hashtag"></i> ID: <?php echo e($item->id); ?>

                    </span>
                    <span class="info-chip">
                        <i class="ri-price-tag-3-line"></i> <?php echo e($item->category->Category_Name ?? 'N/A'); ?>

                    </span>
                    <span class="badge badge-featured">
                        <i class="ri-star-line me-1"></i> <?php echo e($item->item_featured ? 'Featured' : 'Not Featured'); ?>

                    </span>
                </div>

                <div class="row g-2">
                    <div class="col-12">
                        <small class="text-muted">Collection Date</small>
                        <p class="mb-2 fw-semibold">
                            <i class="ri-calendar-line me-1"></i>
                            <?php echo e($item->collection_date ? \Carbon\Carbon::parse($item->collection_date)->format('F j, Y') : 'Not set'); ?>

                        </p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Created</small>
                        <p class="mb-0 fw-semibold">
                            <i class="ri-add-circle-line me-1"></i>
                            <?php echo e($item->created_at->format('M j, Y')); ?>

                        </p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Updated</small>
                        <p class="mb-0 fw-semibold">
                            <i class="ri-refresh-line me-1"></i>
                            <?php echo e($item->updated_at->format('M j, Y')); ?>

                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Item Details -->
        <div class="col-xl-8 col-lg-6">
            <div class="glass-card p-4 h-100">
                <div class="d-flex align-items-center mb-3">
                    <i class="ri-file-text-line text-success me-2 fs-4"></i>
                    <h5 class="mb-0 text-success">Item Details</h5>
                </div>

                <?php if($item->image): ?>
                <div class="text-center mb-3">
                    <img src="<?php echo e(asset('storage/' . $item->image)); ?>"
                         alt="<?php echo e($item->title); ?>"
                         class="img-fluid rounded-3 shadow"
                         style="max-height: 200px;">
                </div>
                <?php endif; ?>

                <div class="mb-3">
                    <h6 class="text-muted mb-2">Content Preview</h6>
                    <div class="content-preview">
                        <?php echo nl2br(e($item->content ?? 'No content available')); ?>

                    </div>
                </div>

                <div class="row g-2">
                    <div class="col-md-6">
                        <small class="text-muted">Author</small>
                        <p class="mb-2 fw-semibold">
                            <i class="ri-user-line me-1"></i>
                            <?php echo e($item->author_first_name); ?> <?php echo e($item->author_last_name); ?>

                        </p>
                        <a href="mailto:<?php echo e($item->author_email); ?>" class="text-decoration-none">
                            <i class="ri-mail-line me-1"></i><?php echo e($item->author_email); ?>

                        </a>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Permalink</small>
                        <p class="mb-2">
                            <a href="<?php echo e($item->permalink); ?>" target="_blank" class="text-decoration-none">
                                <i class="ri-external-link-line me-1"></i>Visit Page
                            </a>
                        </p>
                        <small class="text-muted">Slug</small>
                        <p class="mb-0 fw-semibold">
                            <i class="ri-link me-1"></i><?php echo e($item->slug); ?>

                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information & Map -->
        <div class="col-xl-6">
            <div class="glass-card p-4 h-100">
                <div class="d-flex align-items-center mb-3">
                    <i class="ri-contacts-line text-warning me-2 fs-4"></i>
                    <h5 class="mb-0 text-warning">Contact Information</h5>
                </div>

                <?php if($item->contacts): ?>
                <div class="row g-2 mb-3">
                    <?php if($item->contacts->telephone): ?>
                    <div class="col-md-6">
                        <div class="contact-badge">
                            <i class="ri-phone-line"></i>
                            <a href="tel:<?php echo e($item->contacts->telephone); ?>" class="text-white text-decoration-none">
                                <?php echo e($item->contacts->telephone); ?>

                            </a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($item->contacts->email): ?>
                    <div class="col-md-6">
                        <div class="contact-badge" style="background: var(--info-gradient);">
                            <i class="ri-mail-line"></i>
                            <a href="mailto:<?php echo e($item->contacts->email); ?>" class="text-white text-decoration-none">
                                <?php echo e($item->contacts->email); ?>

                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <?php if($item->contacts->phone1): ?>
                    <p class="mb-1">
                        <i class="ri-smartphone-line me-2"></i>
                        <strong>Phone 1:</strong>
                        <a href="tel:<?php echo e($item->contacts->phone1); ?>"><?php echo e($item->contacts->phone1); ?></a>
                    </p>
                    <?php endif; ?>

                    <?php if($item->contacts->phone2): ?>
                    <p class="mb-1">
                        <i class="ri-smartphone-line me-2"></i>
                        <strong>Phone 2:</strong>
                        <a href="tel:<?php echo e($item->contacts->phone2); ?>"><?php echo e($item->contacts->phone2); ?></a>
                    </p>
                    <?php endif; ?>

                    <?php if($item->contacts->address): ?>
                    <p class="mb-0">
                        <i class="ri-map-pin-line me-2"></i>
                        <strong>Address:</strong> <?php echo e($item->contacts->address); ?>

                    </p>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-4">
                    <i class="ri-contacts-line text-muted fs-1"></i>
                    <p class="text-muted mb-0">No contact information available</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Map -->
        <div class="col-xl-6">
            <div class="glass-card p-4 h-100">
                <div class="d-flex align-items-center mb-3">
                    <i class="ri-map-pin-line text-danger me-2 fs-4"></i>
                    <h5 class="mb-0 text-danger">Location Map</h5>
                </div>
                <div id="map" class="map-container" style="height: 300px;"></div>
            </div>
        </div>

        <!-- Opening Times -->
        <div class="col-xl-6">
            <div class="glass-card p-4 h-100">
                <div class="d-flex align-items-center mb-3">
                    <i class="ri-time-line text-info me-2 fs-4"></i>
                    <h5 class="mb-0 text-info">Opening Times</h5>
                </div>

                <?php if($item->openingTimes): ?>
                <div class="accordion" id="openingTimesAccordion">
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

                    <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day => $hours): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($hours): ?>
                    <div class="accordion-glass">
                        <div class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo e($loop->index); ?>">
                                <i class="ri-calendar-line me-2"></i><?php echo e($day); ?>

                            </button>
                        </div>
                        <div id="collapse<?php echo e($loop->index); ?>" class="accordion-collapse collapse" data-bs-parent="#openingTimesAccordion">
                            <div class="accordion-body">
                                <?php echo e($hours); ?>

                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php if($item->openingTimes->openingHoursNote): ?>
                    <div class="mt-3 p-3 bg-light rounded">
                        <i class="ri-information-line me-2"></i>
                        <strong>Note:</strong> <?php echo e($item->openingTimes->openingHoursNote); ?>

                    </div>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-4">
                    <i class="ri-time-line text-muted fs-1"></i>
                    <p class="text-muted mb-0">No opening times available</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Social Links -->
        <div class="col-xl-6">
            <div class="glass-card p-4 h-100">
                <div class="d-flex align-items-center mb-3">
                    <i class="ri-share-line text-secondary me-2 fs-4"></i>
                    <h5 class="mb-0 text-secondary">Social Links</h5>
                </div>

                <?php if($item->socialIcons && $item->socialIcons->count()): ?>
                <div class="d-flex flex-wrap">
                    <?php $__currentLoopData = $item->socialIcons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $socialIcon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($socialIcon->socialIcons_url): ?>
                    <a href="<?php echo e($socialIcon->socialIcons_url); ?>"
                       class="social-link"
                       target="<?php echo e($socialIcon->socialIconsOpenInNewWindow ? '_blank' : '_self'); ?>"
                       title="<?php echo e(ucfirst($socialIcon->socialIcons)); ?>">
                        <i class="ri-<?php echo e(strtolower($socialIcon->socialIcons)); ?>-line me-2"></i>
                        <?php echo e(ucfirst($socialIcon->socialIcons)); ?>

                    </a>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php else: ?>
                <div class="text-center py-4">
                    <i class="ri-share-line text-muted fs-1"></i>
                    <p class="text-muted mb-0">No social links available</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Gallery -->
        <div class="col-12">
            <div class="glass-card p-4">
                <div class="d-flex align-items-center mb-3">
                    <i class="ri-gallery-line text-primary me-2 fs-4"></i>
                    <h5 class="mb-0 text-primary">Gallery</h5>
                    <span class="badge bg-primary ms-2"><?php echo e($stats['total_gallery_images']); ?> images</span>
                </div>

                <?php if($item->galleries->count() > 0): ?>
                <div class="row g-3" id="gallery">
                    <?php $__currentLoopData = $item->galleries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gallery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        $imgs = is_array($gallery->gallery) ? $gallery->gallery : (is_string($gallery->gallery) ? [$gallery->gallery] : []);
                        if (empty($imgs)) {
                            $raw = $gallery->getAttributes()['gallery'] ?? null;
                            if ($raw) {
                                $decoded = @json_decode($raw, true);
                                $imgs = is_array($decoded) ? $decoded : [$raw];
                            }
                        }
                    ?>
                        <?php $__currentLoopData = $imgs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $img = trim($img); ?>
                            <?php if(empty($img)): ?> <?php continue; ?> <?php endif; ?>
                            <?php
                                if (Str::startsWith($img, ['http://','https://'])) {
                                    $imgUrl = $img;
                                } elseif (Str::startsWith($img, 'storage/')) {
                                    $imgUrl = asset($img);
                                } else {
                                    $imgUrl = asset('storage/' . ltrim($img, '/'));
                                }
                            ?>
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
                                <img src="<?php echo e($imgUrl); ?>"
                                     class="img-fluid gallery-img w-100"
                                     alt="Gallery Image"
                                     data-original="<?php echo e($imgUrl); ?>">
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <i class="ri-gallery-line text-muted fs-1"></i>
                    <p class="text-muted mb-0">No gallery images available</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<!-- Floating Action Buttons -->
<div class="floating-action">
    <button class="action-btn" onclick="window.print()" title="Print">
        <i class="ri-printer-line"></i>
    </button>
    <button class="action-btn" onclick="shareItem()" title="Share">
        <i class="ri-share-line"></i>
    </button>
    <button class="action-btn" onclick="scrollToTop()" title="Scroll to Top">
        <i class="ri-arrow-up-line"></i>
    </button>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Initialize Map
    let map = L.map('map').setView([27.7172, 85.3240], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    <?php if($item->contacts && is_numeric($item->contacts->latitude) && is_numeric($item->contacts->longitude)): ?>
        let marker = L.marker([<?php echo e($item->contacts->latitude); ?>, <?php echo e($item->contacts->longitude); ?>]).addTo(map);
        let popupContent = `
            <div class="p-2">
                <h6 class="mb-1"><?php echo e($item->title); ?></h6>
                <p class="mb-1 small"><?php echo e($item->contacts->address ?? 'No address'); ?></p>
                <?php if($item->contacts->telephone): ?>
                <p class="mb-0 small">
                    <i class="ri-phone-line"></i>
                    <a href="tel:<?php echo e($item->contacts->telephone); ?>"><?php echo e($item->contacts->telephone); ?></a>
                </p>
                <?php endif; ?>
            </div>
        `;
        marker.bindPopup(popupContent).openPopup();
        map.setView([<?php echo e($item->contacts->latitude); ?>, <?php echo e($item->contacts->longitude); ?>], 14);
    <?php endif; ?>

    // Initialize Image Gallery Viewer
    const gallery = document.getElementById('gallery');
    if (gallery) {
        const viewer = new Viewer(gallery, {
            navbar: false,
            title: false,
            toolbar: {
                zoomIn: 1,
                zoomOut: 1,
                oneToOne: 1,
                reset: 1,
                prev: 1,
                play: 0,
                next: 1,
                rotateLeft: 1,
                rotateRight: 1,
                flipHorizontal: 1,
                flipVertical: 1,
            },
        });
    }

    // Add smooth scrolling to accordion items
    document.querySelectorAll('.accordion-button').forEach(button => {
        button.addEventListener('click', function() {
            this.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        });
    });
});

// Utility Functions
function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function shareItem() {
    if (navigator.share) {
        navigator.share({
            title: '<?php echo e($item->title); ?>',
            text: '<?php echo e($item->subtitle); ?>',
            url: window.location.href,
        })
        .catch(console.error);
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Link copied to clipboard!');
        });
    }
}

// Add loading animation
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.glass-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        card.classList.add('animate__animated', 'animate__fadeInUp');
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Online_Saptari\resources\views/admin/items/view.blade.php ENDPATH**/ ?>