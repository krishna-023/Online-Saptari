@if(session()->has('primary'))
    <!-- Primary =-->
    <div class="alert alert-primary alert-border-left alert-dismissible fade show" role="{{ession()->get('primary')}}
        <i class="ri-user-smile-line me-3 align-middle"></i> <strong>Primary</strong> - Left border alert
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<!-- Secondary Alert -->
@if(session()->has('secondary'))
<div class="alert alert-secondary alert-border-left alert-dismissible fade show" role="alert">
    <i class="ri-check-double-line me-3 align-middle"></i> <strong>Secondary</strong> - {{session()->get('getondary')}}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Success Alert -->
@if(session()->has('success'))
<div class="alert alert-success alert-border-left alert-dismissible fade show" role="alert">
    <i class="ri-notification-off-line me-3 align-middle"></i> <strong>Success</strong> - {{session()->get('success')}}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Danger Alert -->
@if(session()->has('danger'))
<div class="alert alert-danger alert-border-left alert-dismissible fade show" role="alert">
    <i class="ri-error-warning-line me-3 align-middle"></i> <strong>Danger</strong> - {{session()->get('danger')}}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Warning Alert -->
@if(session()->has('warning'))
<div class="alert alert-warning alert-border-left alert-dismissible fade show" role="alert">
    <i class="ri-alert-line me-3 align-middle"></i> <strong>Warning</strong> - {{session()->get('warning')}}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Info Alert -->
@if(session()->has('info'))
<div class="alert alert-info alert-border-left alert-dismissible fade show" role="alert">
    <i class="ri-airplay-line me-3 align-middle"></i> <strong>Info</strong> - {{session()->get('info')}}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Light Alert -->
@if(session()->has('light'))
<div class="alert alert-light alert-border-left alert-dismissible fade show" role="alert">
    <i class="ri-mail-line me-3 align-middle"></i> <strong>Light</strong> - {{session()->get('light')}}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Dark Alert -->
@if(session()->has('dark'))
<div class="alert alert-dark alert-border-left alert-dismissible fade show" role="alert">
    <i class="ri-refresh-line me-3 align-middle"></i> <strong>Dark</strong> - {{session()->get('dark')}}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
    @endif
