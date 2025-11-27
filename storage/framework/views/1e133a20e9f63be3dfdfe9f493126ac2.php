<?php echo $__env->make('common.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->startSection('title'); ?>
    Items Management
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<style>
:root {
    --primary-color: #4361ee;
    --secondary-color: #3f37c9;
    --success-color: #4cc9f0;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --light-bg: #f8f9fa;
    --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    --hover-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

body {
    background-color: #f5f7fb;
    font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
}

.card {
    border: none;
    border-radius: 16px;
    box-shadow: var(--card-shadow);
    transition: var(--transition);
    overflow: hidden;
}

.card:hover {
    box-shadow: var(--hover-shadow);
    transform: translateY(-2px);
}

.card-header {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 16px 16px 0 0 !important;
    color: white;
    padding: 1.5rem 2rem;
    border-bottom: none;
}

.card-header h5 {
    font-weight: 600;
    margin: 0;
}

.btn {
    border-radius: 10px;
    font-weight: 500;
    transition: var(--transition);
    border: none;
    padding: 0.625rem 1.25rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
}

.btn-outline-primary {
    border: 2px solid var(--primary-color);
    color: var(--primary-color);
    background: transparent;
}

.btn-outline-primary:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-1px);
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    border-radius: 8px;
}

.table-responsive {
    border-radius: 0 0 16px 16px;
    overflow: hidden;
}

table {
    margin-bottom: 0 !important;
    border-collapse: separate;
    border-spacing: 0;
}

thead th {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-bottom: 2px solid #e2e8f0;
    font-weight: 600;
    padding: 1.25rem 1rem;
    color: #475569;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

tbody tr {
    transition: var(--transition);
    border-bottom: 1px solid #f1f5f9;
}

tbody tr:hover {
    background: linear-gradient(90deg, rgba(67, 97, 238, 0.03), rgba(67, 97, 238, 0.08));
    transform: translateX(4px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

tbody td {
    padding: 1.25rem 1rem;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
    font-size: 0.95rem;
}

.action-buttons .btn {
    border-radius: 8px;
    padding: 0.5rem;
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.search-box {
    position: relative;
    min-width: 300px;
}

.search-box .form-control {
    border-radius: 12px;
    padding: 0.75rem 1rem 0.75rem 3rem;
    border: 2px solid #e2e8f0;
    background: #ffffff;
    font-size: 0.95rem;
    transition: var(--transition);
}

.search-box .form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    transform: translateY(-1px);
}

.search-box .search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #64748b;
    z-index: 5;
}

.stats-card {
    border-left: 4px solid var(--primary-color);
    transition: var(--transition);
}

.stats-card:hover {
    transform: translateY(-3px);
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #64748b;
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 1.5rem;
    color: #cbd5e1;
    opacity: 0.7;
}

.filter-badge {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    border-radius: 50%;
    width: 22px;
    height: 22px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 600;
    margin-left: 8px;
    box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
}

.bulk-actions {
    display: none;
    animation: fadeInUp 0.3s ease-out;
}

.bulk-actions.show {
    display: flex;
}

.scroll-indicator {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 4px 20px rgba(67, 97, 238, 0.3);
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: var(--transition);
}

.scroll-indicator.show {
    opacity: 1;
    visibility: visible;
}

.scroll-indicator:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 25px rgba(67, 97, 238, 0.4);
}

.item-row {
    animation: slideInLeft 0.4s ease-out;
}

.item-row.removing {
    animation: slideOutRight 0.3s ease-in forwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideOutRight {
    from {
        opacity: 1;
        transform: translateX(0);
    }
    to {
        opacity: 0;
        transform: translateX(20px);
    }
}

.alert {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border-left: 4px solid;
}

/* Checkbox Styles */
.form-check-input {
    width: 1.2em;
    height: 1.2em;
    border: 2px solid #cbd5e1;
    border-radius: 6px;
    cursor: pointer;
    transition: var(--transition);
}

.form-check-input:checked {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-color: var(--primary-color);
    box-shadow: 0 2px 8px rgba(67, 97, 238, 0.3);
}

.form-check-input:focus {
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    border-color: var(--primary-color);
}

/* Pagination Styles */
.pagination {
    margin-bottom: 0;
    gap: 0.5rem;
}

.page-link {
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    color: #64748b;
    font-weight: 500;
    padding: 0.75rem 1rem;
    min-width: 46px;
    text-align: center;
    transition: var(--transition);
    background: white;
}

.page-link:hover {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    border-color: var(--primary-color);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2);
}

.page-item.active .page-link {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-color: var(--primary-color);
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
    transform: translateY(-1px);
}

.page-item.disabled .page-link {
    color: #cbd5e1;
    background: #f8fafc;
    border-color: #e2e8f0;
    transform: none;
    box-shadow: none;
}

.pagination-info {
    color: #64748b;
    font-size: 0.9rem;
    font-weight: 500;
}

/* Modal Styles */
.modal-content {
    border: none;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    overflow: hidden;
}

.modal-header {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    border-bottom: none;
    padding: 1.5rem 2rem;
}

.modal-body {
    padding: 2rem;
}

.modal-footer {
    border-top: 1px solid #f1f5f9;
    padding: 1.5rem 2rem;
}

/* Form Styles */
.form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.75rem;
}

.form-control, .form-select {
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: var(--transition);
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    transform: translateY(-1px);
}

/* Loading State */
.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 16px;
    z-index: 10;
    opacity: 0;
    visibility: hidden;
    transition: var(--transition);
}

.loading-overlay.show {
    opacity: 1;
    visibility: visible;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f1f5f9;
    border-left: 4px solid var(--primary-color);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive Design */
@media (max-width: 768px) {
    .card-header {
        padding: 1.25rem;
    }

    .search-box {
        min-width: 100%;
        margin-bottom: 1rem;
    }

    .table-responsive {
        font-size: 0.875rem;
    }

    thead th, tbody td {
        padding: 0.875rem 0.5rem;
    }

    .action-buttons .btn {
        width: 32px;
        height: 32px;
        padding: 0.375rem;
    }

    .modal-body {
        padding: 1.5rem;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .card {
        background: #1e293b;
        color: #e2e8f0;
    }

    .table {
        --bs-table-bg: #1e293b;
        --bs-table-color: #e2e8f0;
    }

    .form-control, .form-select {
        background: #334155;
        border-color: #475569;
        color: #e2e8f0;
    }
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="flash-message"><?php echo $__env->make('common.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>

<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Items Management <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> All Items <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<!-- Statistics Cards -->
<div class="row mb-4 g-3">
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card animate__animated animate__fadeInUp">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-muted mb-2">Total Items</h6>
                        <h2 class="text-primary mb-0" id="totalItems"><?php echo e(\App\Models\Item::count()); ?></h2>
                        <small class="text-muted">All time</small>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-3">
                        <i class="ri-box-3-line text-primary fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stats-card animate__animated animate__fadeInUp animate__delay-1s" style="border-left-color: #10b981;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-muted mb-2">Featured Items</h6>
                        <h2 class="text-success mb-0" id="featuredItems"><?php echo e(\App\Models\Item::where('item_featured', 1)->count()); ?></h2>
                        <small class="text-muted">Promoted content</small>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-3">
                        <i class="ri-star-line text-success fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stats-card animate__animated animate__fadeInUp animate__delay-2s" style="border-left-color: #f59e0b;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-muted mb-2">Categories</h6>
                        <h2 class="text-warning mb-0" id="totalCategories"><?php echo e(\App\Models\Category::count()); ?></h2>
                        <small class="text-muted">Active categories</small>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded-3">
                        <i class="ri-price-tag-3-line text-warning fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stats-card animate__animated animate__fadeInUp animate__delay-3s" style="border-left-color: #ef4444;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-muted mb-2">This Month</h6>
                        <h2 class="text-danger mb-0" id="monthItems"><?php echo e(\App\Models\Item::whereMonth('created_at', now()->month)->count()); ?></h2>
                        <small class="text-muted">Recent additions</small>
                    </div>
                    <div class="bg-danger bg-opacity-10 p-3 rounded-3">
                        <i class="ri-calendar-event-line text-danger fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="row g-4">
    <div class="col-12">
        <div class="card shadow-lg rounded-3" id="itemList">
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <h5 class="mb-1">Items Management</h5>
                    <p class="text-white-50 mb-0 small">Manage and organize your items collection</p>
                </div>

                <div class="d-flex flex-column flex-md-row gap-3 w-100 w-md-auto">
                    <!-- Search Box -->
                    <div class="search-box">
                        <i class="ri-search-line search-icon"></i>
                        <input type="text" id="itemsearchbox" class="form-control" placeholder="Search items by title, subtitle, or category...">
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                        <!-- Create Button -->
                        <a href="<?php echo e(route('item.add')); ?>" class="btn btn-primary">
                            <i class="ri-add-circle-line me-1"></i> Add New
                        </a>

                        <!-- Import Form -->
                        <div class="dropdown">
                            <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="ri-upload-cloud-line me-1"></i> Import
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <form action="<?php echo e(route('item.import')); ?>" method="POST" enctype="multipart/form-data" class="p-3" style="min-width: 300px;">
                                        <?php echo csrf_field(); ?>
                                        <div class="mb-3">
                                            <label for="file" class="form-label small">Select file to import</label>
                                            <input type="file" name="file" id="file" class="form-control form-control-sm" accept=".xlsx,.xls,.csv" required>
                                            <div class="form-text">Supported formats: .xlsx, .xls, .csv (Max: 10MB)</div>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                                <i class="ri-upload-cloud-line me-1"></i> Upload
                                            </button>
                                            <a href="<?php echo e(route('item.import.sample')); ?>" class="btn btn-outline-secondary btn-sm" title="Download Sample File">
                                                <i class="ri-download-line"></i>
                                            </a>
                                        </div>
                                    </form>
                                </li>
                            </ul>
                        </div>

                        <!-- Export Button -->
                        <a href="<?php echo e(route('item.export')); ?>" class="btn btn-outline-primary">
                            <i class="ri-download-line me-1"></i> Export All
                        </a>

                        <!-- Filter Button -->
                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#filterModal">
                            <i class="ri-filter-3-line me-1"></i> Filter
                            <span id="filterCount" class="filter-badge">0</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Bulk Actions -->
            <div class="bulk-actions p-3 border-bottom bg-light" id="bulkActions">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-muted" id="selectedCount">0 items selected</span>
                        <div class="vr"></div>
                        <button id="deleteSelectedBtn" class="btn btn-danger btn-sm">
                            <i class="ri-delete-bin-line me-1"></i> Delete Selected
                        </button>
                        <button id="exportSelectedBtn" class="btn btn-success btn-sm">
                            <i class="ri-download-line me-1"></i> Export Selected
                        </button>
                        <button id="clearSelectionBtn" class="btn btn-outline-secondary btn-sm">
                            <i class="ri-close-line me-1"></i> Clear
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body p-0 position-relative">
                <!-- Loading Overlay -->
                <div class="loading-overlay" id="loadingOverlay">
                    <div class="spinner"></div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="60" class="ps-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="checkAll">
                                    </div>
                                </th>
                                <th>ID</th>
                                <th>Category</th>
                                <th>Title</th>
                                <th>Subtitle</th>
                                <th>Telephone</th>
                                <th>Email</th>
                                <th>Featured</th>
                                <th width="160" class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="itemsTableBody">
                            <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="align-middle item-row" data-item-id="<?php echo e($item->id); ?>">
                                    <td class="ps-4">
                                        <div class="form-check">
                                            <input class="form-check-input row-checkbox" type="checkbox" value="<?php echo e($item->id); ?>" data-encrypted-id="<?php echo e(encrypt($item->id)); ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary bg-opacity-10 text-primary">#<?php echo e($item->id); ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                            <?php echo e($item->category->Category_Name ?? 'N/A'); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="fw-medium text-truncate" style="max-width: 200px;" title="<?php echo e($item->title); ?>">
                                                <?php echo e($item->title); ?>

                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted text-truncate d-inline-block" style="max-width: 200px;" title="<?php echo e($item->subtitle); ?>">
                                            <?php echo e($item->subtitle ?: '—'); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-truncate d-inline-block" style="max-width: 150px;" title="<?php echo e($item->contacts->telephone ?? 'N/A'); ?>">
                                            <?php echo e($item->contacts->telephone ?? '—'); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-truncate d-inline-block" style="max-width: 200px;" title="<?php echo e($item->contacts->email ?? 'N/A'); ?>">
                                            <?php echo e($item->contacts->email ?? '—'); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <?php if($item->item_featured): ?>
                                            <span class="badge bg-success bg-opacity-20 text-success">
                                                <i class="ri-star-fill me-1"></i> Featured
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                Standard
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-end gap-1 action-buttons">
                                            <a href="<?php echo e(route('item.view', encrypt($item->id))); ?>" class="btn btn-outline-primary btn-sm" title="View">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="<?php echo e(route('item.edit', encrypt($item->id))); ?>" class="btn btn-outline-secondary btn-sm" title="Edit">
                                                <i class="ri-pencil-line"></i>
                                            </a>
                                            <form action="<?php echo e(route('item.destroy', encrypt($item->id))); ?>" method="POST" class="d-inline delete-form">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete" data-item-title="<?php echo e($item->title); ?>">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr id="emptyState">
                                    <td colspan="9" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="ri-inbox-line fs-1"></i>
                                            <h4 class="text-muted mt-3">No items found</h4>
                                            <p class="text-muted mb-4">Get started by creating your first item</p>
                                            <a href="<?php echo e(route('item.add')); ?>" class="btn btn-primary">
                                                <i class="ri-add-circle-line me-2"></i> Create First Item
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if($items->hasPages()): ?>
                <div class="card-footer d-flex flex-column flex-md-row justify-content-between align-items-center py-3">
                    <div class="pagination-info mb-3 mb-md-0">
                        Showing <strong><?php echo e($items->firstItem() ?? 0); ?></strong> to <strong><?php echo e($items->lastItem() ?? 0); ?></strong> of <strong><?php echo e($items->total()); ?></strong> results
                    </div>
                    <nav aria-label="Items pagination">
                        <ul class="pagination mb-0">
                            
                            <?php if($items->onFirstPage()): ?>
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="ri-arrow-left-s-line"></i>
                                    </span>
                                </li>
                            <?php else: ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo e($items->previousPageUrl()); ?>" rel="prev">
                                        <i class="ri-arrow-left-s-line"></i>
                                    </a>
                                </li>
                            <?php endif; ?>

                            
                            <?php
                                $current = $items->currentPage();
                                $last = $items->lastPage();
                                $start = max($current - 2, 1);
                                $end = min($current + 2, $last);
                            ?>

                            <?php if($start > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo e($items->url(1)); ?>">1</a>
                                </li>
                                <?php if($start > 2): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php for($page = $start; $page <= $end; $page++): ?>
                                <?php if($page == $items->currentPage()): ?>
                                    <li class="page-item active">
                                        <span class="page-link"><?php echo e($page); ?></span>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?php echo e($items->url($page)); ?>"><?php echo e($page); ?></a>
                                    </li>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if($end < $last): ?>
                                <?php if($end < $last - 1): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo e($items->url($last)); ?>"><?php echo e($last); ?></a>
                                </li>
                            <?php endif; ?>

                            
                            <?php if($items->hasMorePages()): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo e($items->nextPageUrl()); ?>" rel="next">
                                        <i class="ri-arrow-right-s-line"></i>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="ri-arrow-right-s-line"></i>
                                    </span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="filterModalLabel">
            <i class="ri-filter-3-line me-2"></i>Filter Items
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="filterForm" method="GET" action="<?php echo e(route('item.index')); ?>">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="filter-category" class="form-label">Category</label>
              <select id="filter-category" name="category_id" class="form-select">
                <option value="">All Categories</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($category->id); ?>" <?php echo e(request('category_id') == $category->id ? 'selected' : ''); ?>>
                      <?php echo e($category->Category_Name); ?>

                  </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
            </div>
            <div class="col-md-6">
              <label for="filter-featured" class="form-label">Featured</label>
              <select id="filter-featured" name="item_featured" class="form-select">
                <option value="">All Items</option>
                <option value="1" <?php echo e(request('item_featured') == '1' ? 'selected' : ''); ?>>Featured Only</option>
                <option value="0" <?php echo e(request('item_featured') == '0' ? 'selected' : ''); ?>>Standard Only</option>
              </select>
            </div>
            <div class="col-12">
              <label for="filter-title" class="form-label">Title</label>
              <input type="text" id="filter-title" name="title" class="form-control" value="<?php echo e(request('title')); ?>" placeholder="Search by title...">
            </div>
            <div class="col-12">
              <label for="filter-subtitle" class="form-label">Subtitle</label>
              <input type="text" id="filter-subtitle" name="subtitle" class="form-control" value="<?php echo e(request('subtitle')); ?>" placeholder="Search by subtitle...">
            </div>
            <div class="col-12">
              <label for="filter-date" class="form-label">Created Date</label>
              <input type="date" id="filter-date" name="collection_date" class="form-control" value="<?php echo e(request('collection_date')); ?>">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" id="resetFilters">
            <i class="ri-refresh-line me-1"></i>Reset
        </button>
        <button type="submit" form="filterForm" class="btn btn-primary">
            <i class="ri-filter-3-line me-1"></i>Apply Filters
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Scroll to top button -->
<div class="scroll-indicator" id="scrollToTop">
    <i class="ri-arrow-up-line"></i>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    let searchTimeout = null;
    let currentSearchTerm = '';

    // Initialize components
    function init() {
        updateFilterBadge();
        updateButtonVisibility();
        setupEventListeners();
        autoFocusSearch();
    }

    // Setup event listeners
    function setupEventListeners() {
        // Scroll to top
        $(window).on('scroll', handleScroll);
        $('#scrollToTop').on('click', scrollToTop);

        // Search functionality
        $('#itemsearchbox').on('input', handleSearch);

        // Filter functionality
        $('#resetFilters').on('click', resetFilters);

        // Checkbox functionality
        $('#checkAll').on('change', toggleAllCheckboxes);
        $(document).on('change', '.row-checkbox', handleRowCheckboxChange);

        // Bulk actions
        $('#deleteSelectedBtn').on('click', deleteSelectedItems);
        $('#exportSelectedBtn').on('click', exportSelectedItems);
        $('#clearSelectionBtn').on('click', clearSelection);

        // Delete confirmation
        $(document).on('submit', '.delete-form', confirmDelete);
    }

    // Scroll handling
    function handleScroll() {
        if ($(window).scrollTop() > 300) {
            $('#scrollToTop').addClass('show');
        } else {
            $('#scrollToTop').removeClass('show');
        }
    }

    function scrollToTop() {
        $('html, body').animate({ scrollTop: 0 }, 500);
    }

    // Search functionality with debouncing
    function handleSearch() {
        const searchTerm = $(this).val().trim();
        currentSearchTerm = searchTerm;

        // Clear previous timeout
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }

        // Client-side filtering for immediate feedback
        if (searchTerm.length > 0) {
            $('.item-row').each(function() {
                const rowText = $(this).text().toLowerCase();
                $(this).toggle(rowText.includes(searchTerm.toLowerCase()));
            });
            updateEmptyState();
        } else {
            $('.item-row').show();
            updateEmptyState();
        }

        // Server-side search with debouncing
        searchTimeout = setTimeout(() => {
            if (searchTerm.length >= 2 || searchTerm.length === 0) {
                performServerSearch(searchTerm);
            }
        }, 500);
    }

    function performServerSearch(searchTerm) {
        showLoading();

        const urlParams = new URLSearchParams(window.location.search);

        if (searchTerm) {
            urlParams.set('search', searchTerm);
        } else {
            urlParams.delete('search');
        }

        urlParams.delete('page');

        window.location.href = '<?php echo e(route('item.index')); ?>?' + urlParams.toString();
    }

    // Filter functionality
    function resetFilters() {
        $('#filterForm')[0].reset();
        $('#filterForm').submit();
    }

    function updateFilterBadge() {
        const urlParams = new URLSearchParams(window.location.search);
        let activeFilters = 0;

        const filterFields = ['category_id', 'title', 'subtitle', 'item_featured', 'collection_date', 'search'];
        filterFields.forEach(field => {
            if (urlParams.get(field)) activeFilters++;
        });

        $('#filterCount').text(activeFilters);
    }

    // Checkbox functionality
    function toggleAllCheckboxes() {
        const isChecked = $(this).prop('checked');
        $('.row-checkbox').prop('checked', isChecked).trigger('change');
    }

    function handleRowCheckboxChange() {
        const allChecked = $('.row-checkbox').length === $('.row-checkbox:checked').length;
        const anyChecked = $('.row-checkbox:checked').length > 0;

        $('#checkAll').prop('checked', allChecked);
        updateButtonVisibility();
        updateSelectedCount();

        // Show/hide bulk actions
        if (anyChecked) {
            $('#bulkActions').addClass('show');
        } else {
            $('#bulkActions').removeClass('show');
        }
    }

    function updateButtonVisibility() {
        const anyChecked = $('.row-checkbox:checked').length > 0;
        $('#deleteSelectedBtn, #exportSelectedBtn').toggle(anyChecked);
    }

    function updateSelectedCount() {
        const selectedCount = $('.row-checkbox:checked').length;
        const text = selectedCount === 1 ? '1 item selected' : `${selectedCount} items selected`;
        $('#selectedCount').text(text);
    }

    function clearSelection() {
        $('.row-checkbox, #checkAll').prop('checked', false).trigger('change');
        $('#bulkActions').removeClass('show');
    }

    // Bulk actions
    function deleteSelectedItems() {
        const selectedIds = $('.row-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) {
            showToast('No items selected', 'Please select items to delete', 'info');
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: `You're about to delete ${selectedIds.length} item(s). This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete them!',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#dc3545',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.post('<?php echo e(route('item.deleteSelected')); ?>', {
                    _token: '<?php echo e(csrf_token()); ?>',
                    ids: selectedIds
                }).fail(() => {
                    Swal.showValidationMessage('Failed to delete items');
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Deleted!',
                    text: result.value?.message || 'Items deleted successfully',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload();
                });
            }
        });
    }

    function exportSelectedItems() {
        const selectedIds = $('.row-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) {
            showToast('No items selected', 'Please select items to export', 'info');
            return;
        }

        const form = $('<form>', {
            method: 'POST',
            action: '<?php echo e(route("item.export")); ?>',
            target: '_blank'
        });

        selectedIds.forEach(id => {
            form.append(`<input type="hidden" name="ids[]" value="${id}">`);
        });

        form.append(`<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">`);
        $('body').append(form);
        form.submit();
        form.remove();

        showToast('Export Started', 'Your export file is being prepared...', 'success');
    }

    // Delete confirmation
    function confirmDelete(e) {
        e.preventDefault();
        const form = $(this);
        const itemTitle = form.find('button').data('item-title') || 'this item';

        Swal.fire({
            title: 'Delete Item?',
            text: `Are you sure you want to delete "${itemTitle}"? This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#dc3545',
        }).then((result) => {
            if (result.isConfirmed) {
                // Add removing animation
                const row = form.closest('tr');
                row.addClass('removing');

                setTimeout(() => {
                    form.off('submit').submit();
                }, 300);
            }
        });
    }

    // Utility functions
    function showLoading() {
        $('#loadingOverlay').addClass('show');
    }

    function hideLoading() {
        $('#loadingOverlay').removeClass('show');
    }

    function showToast(title, message, icon = 'info') {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });

        Toast.fire({
            icon: icon,
            title: title,
            text: message
        });
    }

    function updateEmptyState() {
        const visibleRows = $('.item-row:visible').length;
        if (visibleRows === 0 && currentSearchTerm) {
            if ($('#emptyState').length === 0) {
                $('#itemsTableBody').html(`
                    <tr id="emptyState">
                        <td colspan="9" class="text-center py-5">
                            <div class="empty-state">
                                <i class="ri-search-line fs-1"></i>
                                <h4 class="text-muted mt-3">No matching items found</h4>
                                <p class="text-muted mb-4">Try adjusting your search criteria</p>
                                <button class="btn btn-outline-secondary" onclick="$('#itemsearchbox').val('').trigger('input')">
                                    <i class="ri-refresh-line me-2"></i>Clear Search
                                </button>
                            </div>
                        </td>
                    </tr>
                `);
            }
        }
    }

    function autoFocusSearch() {
        <?php if(request('search')): ?>
            $('#itemsearchbox').focus().val('<?php echo e(request('search')); ?>');
        <?php endif; ?>
    }

    // Initialize the application
    init();

    // Hide loading when page fully loads
    $(window).on('load', function() {
        setTimeout(hideLoading, 500);
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Online_Saptari\resources\views/admin/items/index.blade.php ENDPATH**/ ?>