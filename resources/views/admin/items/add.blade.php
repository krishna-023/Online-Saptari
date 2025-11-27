@extends('admin.layouts.master')

@section('title', isset($item) ? 'Edit Item' : 'Add Item')

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
    /* ---------- Modern Step Navigation ---------- */
    .stepper {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
        position: relative;
    }

    .stepper::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 10%;
        right: 10%;
        height: 3px;
        background-color: #e0e0e0;
        z-index: 0;
    }

    .step {
        position: relative;
        z-index: 1;
        text-align: center;
        width: 16%;
    }

    .step .circle {
        width: 40px;
        height: 40px;
        line-height: 40px;
        background-color: #dcdcdc;
        border-radius: 50%;
        display: inline-block;
        color: #555;
        font-weight: bold;
        transition: all 0.4s ease;
    }

    .step.active .circle {
        background-color: #007bff;
        color: #fff;
        transform: scale(1.1);
    }

    .step.completed .circle {
        background-color: #28a745;
        color: #fff;
    }

    .step small {
        display: block;
        margin-top: 10px;
        color: #666;
    }

    /* ---------- Form Panels ---------- */
    .form-step {
        display: none;
        animation: fadeIn 0.5s ease;
    }

    .form-step.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ---------- Buttons ---------- */
    .btn-modern {
        border-radius: 50px;
        padding: 10px 25px;
        transition: 0.3s;
        font-weight: 500;
    }

    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.2);
    }

    .card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }
</style>
@endsection

@section('content')
<div class="flash-message">@include('common.flash')</div>

@component('components.breadcrumb')
    @slot('li_1') Item @endslot
    @slot('title') {{ isset($item) ? 'Edit Item' : 'Add Item' }} @endslot
@endcomponent

<div class="container py-5">
    <div class="card p-5">
        <h3 class="mb-4 text-center text-primary fw-bold">{{ isset($item) ? 'Edit Item' : 'Add New Item' }}</h3>

        <form action="{{ route('item.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Progress Stepper -->
            <div class="stepper mb-5">
                <div class="step active" data-step="1"><span class="circle">1</span><small>Category</small></div>
                <div class="step" data-step="2"><span class="circle">2</span><small>Main</small></div>
                <div class="step" data-step="3"><span class="circle">3</span><small>Contacts</small></div>
                <div class="step" data-step="4"><span class="circle">4</span><small>Opening</small></div>
                <div class="step" data-step="5"><span class="circle">5</span><small>Social</small></div>
                <div class="step" data-step="6"><span class="circle">6</span><small>Gallery</small></div>
            </div>

            <!-- Step 1: Category -->
            <div class="form-step active" id="step-1">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Category Mode:</label><br>
                        <div class="form-check form-check-inline">
                            <input type="radio" name="category_mode" id="selectMode" value="select_category" class="form-check-input" onclick="toggleCategoryMode()" checked>
                            <label for="selectMode" class="form-check-label">Select Existing</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" name="category_mode" id="newMode" value="new_category" class="form-check-input" onclick="toggleCategoryMode()">
                            <label for="newMode" class="form-check-label">Create New</label>
                        </div>

                        <div id="category_select_div" class="mt-3">
                            <label for="category_id" class="form-label">Choose Category:</label>
                            <select class="form-select" id="category_id" name="category_id">
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->Category_Name }}</option>
                                    @foreach($cat->children as $child)
                                        <option value="{{ $child->id }}">— {{ $child->Category_Name }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>

                        <div id="category_new_div" class="mt-3" style="display:none;">
                            <label class="form-label">New Category Name:</label>
                            <input type="text" name="category_name" class="form-control" placeholder="Parent category name">
                            <label class="form-label mt-3">Child Category (Optional):</label>
                            <input type="text" name="child_category_name" class="form-control" placeholder="Child category name">
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <button type="button" class="btn btn-modern btn-primary next-step">Next <i class="bi bi-arrow-right"></i></button>
                </div>
            </div>

            <!-- Step 2: Main -->
            <div class="form-step" id="step-2">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Subtitle</label>
                        <input type="text" name="subtitle" class="form-control">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Content</label>
                        <textarea name="content" class="form-control" rows="4"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Featured</label>
                        <input type="text" name="item_featured" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Collection Date</label>
                        <input type="date" name="collection_date" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Image</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                    <div class="col-md-6">
                <label for="author_username" class="form-label">Author Username:</label>
                <input type="text"
                    class="form-control @error('author_username') is-invalid @enderror"
                    id="author_username" name="author_username">
                @error('author_username')
                    <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="col-sm-6">
                <label for="author_email" class="form-label">Author Email:</label>
                <input type="email"
                    class="form-control @error('author_email') is-invalid @enderror"
                    id="author_email" name="author_email">
                @error('author_email')
                    <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="author_first_name" class="form-label">Author First Name:</label>
                <input type="text"
                    class="form-control @error('author_first_name') is-invalid @enderror"
                    id="author_first_name" name="author_first_name">
                @error('author_first_name')
                    <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="col-sm-6">
                <label for="author_last_name" class="form-label">Author Last Name:</label>
                <input type="text"
                    class="form-control @error('author_last_name') is-invalid @enderror"
                    id="author_last_name" name="author_last_name">
                @error('author_last_name')
                    <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-modern btn-outline-secondary prev-step"><i class="bi bi-arrow-left"></i> Back</button>
                    <button type="button" class="btn btn-modern btn-primary next-step">Next <i class="bi bi-arrow-right"></i></button>
                </div>
            </div>

            <!-- Step 3: Contacts -->
            <div class="form-step" id="step-3">
                <h5 class="fw-semibold mb-3">Contact Information</h5>
                <div class="row g-3">
                    <div class="col-md-4"><label class="form-label">Telephone</label><input type="text" name="telephone" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label">Phone 1</label><input type="text" name="phone1" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label">Phone 2</label><input type="text" name="phone2" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control"></div>
                    <div class="col-md-6 form-check align-items-center d-flex">
                        <input type="checkbox" class="form-check-input me-2" id="showemail" name="showemail" value="1">
                        <label class="form-check-label" for="showemail">Show Email</label>
                    </div>
                    <div class="col-md-6 form-check align-items-center d-flex">
                        <input type="checkbox" class="form-check-input me-2" id="contactOwnerBtn" name="contactOwnerBtn" value="1">
                        <label class="form-check-label" for="contactOwnerBtn">Contact Owner Button</label>
                    </div>
                    <div class="col-md-6"><label class="form-label">Website</label><input type="url" name="web" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label">Website Label</label><input type="text" name="webLinkLabel" class="form-control"></div>
                    <div class="col-md-12"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2"></textarea></div>
                    <div class="col-md-4"><label class="form-label">Latitude</label><input type="text" name="latitude" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label">Longitude</label><input type="text" name="longitude" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label">Street View</label><input type="text" name="streetview" class="form-control"></div>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-secondary prev-step"><i class="bi bi-arrow-left"></i> Back</button>
                    <button type="button" class="btn btn-primary next-step">Next</button>
                </div>
            </div>

            <!-- Step 4: Opening Hours -->
            <div class="form-step" id="step-4">
                <h5 class="fw-semibold mb-3">Opening Hours</h5>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="displayOpeningHours" id="displayOpeningHours" value="1">
                    <label class="form-check-label" for="displayOpeningHours">Display Opening Hours</label>
                </div>
                <div class="row g-2">
                    @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                    <div class="col-md-6">
                        <label class="form-label">{{ $day }}</label>
                        <input type="text" name="openingHours{{ $day }}" class="form-control" placeholder="e.g. 9AM - 6PM">
                    </div>
                    @endforeach
                    <div class="col-md-12">
                        <label class="form-label">Note</label>
                        <textarea name="openingHoursNote" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-secondary prev-step"><i class="bi bi-arrow-left"></i> Back</button>
                    <button type="button" class="btn btn-primary next-step">Next</button>
                </div>
            </div>

            <!-- Step 5: Social -->
            <div class="form-step" id="step-5">
                <h5 class="fw-semibold mb-3">Social Icons</h5>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="displaySocialIcons" id="displaySocialIcons" value="1">
                    <label class="form-check-label" for="displaySocialIcons">Display Social Icons</label>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="socialIconsOpenInNewWindow" id="socialIconsOpenInNewWindow" value="1">
                    <label class="form-check-label" for="socialIconsOpenInNewWindow">Open in New Window</label>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Icon Name</label>
                        <input type="text" name="socialIcons" class="form-control" placeholder="e.g. Facebook, Instagram">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Icon URL</label>
                        <input type="url" name="socialIcons_url" class="form-control" placeholder="https://...">
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-secondary prev-step"><i class="bi bi-arrow-left"></i> Back</button>
                    <button type="button" class="btn btn-primary next-step">Next</button>
                </div>
            </div>

            <!-- Step 6: Gallery -->
            <div class="form-step" id="step-6">
                <h5 class="fw-semibold mb-3">Gallery Upload</h5>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="displayGallery" id="displayGallery" value="1" checked>
                    <label class="form-check-label" for="displayGallery">Display Gallery</label>
                </div>
                <input type="file" name="gallery[]" class="form-control" multiple>
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-secondary prev-step"><i class="bi bi-arrow-left"></i> Back</button>
                    <button type="submit" class="btn btn-success btn-modern">Submit Item</button>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
    // ---------- Step Navigation ----------
    let currentStep = 1;
    const totalSteps = 6;

    function updateStepper() {
        document.querySelectorAll('.step').forEach(step => {
            const stepNum = parseInt(step.getAttribute('data-step'));
            step.classList.toggle('active', stepNum === currentStep);
            step.classList.toggle('completed', stepNum < currentStep);
        });

        document.querySelectorAll('.form-step').forEach((form, index) => {
            form.classList.toggle('active', index + 1 === currentStep);
        });
    }

    document.querySelectorAll('.next-step').forEach(btn => {
        btn.addEventListener('click', () => {
            if (currentStep < totalSteps) currentStep++;
            updateStepper();
        });
    });

    document.querySelectorAll('.prev-step').forEach(btn => {
        btn.addEventListener('click', () => {
            if (currentStep > 1) currentStep--;
            updateStepper();
        });
    });

    // ---------- Category Toggle ----------
    function toggleCategoryMode() {
        const isNew = document.getElementById('newMode').checked;
        document.getElementById('category_select_div').style.display = isNew ? 'none' : 'block';
        document.getElementById('category_new_div').style.display = isNew ? 'block' : 'none';
    }
</script>
@endsection
