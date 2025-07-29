<div class="col-lg-auto">
    <div class="hstack flex-wrap gap-2">

        <a href="{{ route('item.add') }}" class="btn btn-secondary"><i class="bi bi-plus-circle align-baseline me-1"></i> Add Item</a>
        <div>
            <button type="button" class="btn btn-info" data-bs-toggle="offcanvas" data-bs-target="#courseFilters" aria-controls="courseFilters"><i class="bi bi-funnel align-baseline me-1"></i> Filter</button>
            <a href="" class="btn btn-subtle-primary btn-icon"><i class="bi bi-grid"></i></a>
            <a href="{{ route('item.index') }}" class="btn btn-subtle-primary active btn-icon"><i class="bi bi-list-task"></i></a>
        </div>
        <button type="button" id="deleteSelectedBtn" class="btn btn-danger" style="display: none;">Delete Selected</button>
        <button type="button" id="exportSelectedBtn" class="btn btn-secondary">
        <i class="bi bi-download align-baseline me-1"></i> Export Selected</button>
    </div>
</div>
