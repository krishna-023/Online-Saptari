@extends('web.layouts.master')

@section('css')
<style>
/* Form Styling */
.add-listing-form { max-width: 800px; margin: 0 auto; }
.form-section { background: white; border-radius: 1rem; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
.form-section h3 { border-bottom: 2px solid #f0f0f0; padding-bottom: 0.75rem; margin-bottom: 1.5rem; color: #2c3e50; }
.form-control, .form-select { border-radius: 0.5rem; padding: 0.75rem 1rem; border: 1px solid #ddd; transition: all 0.3s; }
.form-control:focus, .form-select:focus { border-color: #4e73df; box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.15); }
.form-label { font-weight: 500; margin-bottom: 0.5rem; color: #2c3e50; }
.checkbox-label { font-weight: normal; margin-left: 0.5rem; }
.dropzone { border: 2px dashed #ccc; padding: 2rem; text-align: center; cursor: pointer; border-radius: 0.5rem; transition: all 0.3s; }
.dropzone:hover { border-color: #4e73df; background-color: #f8f9fe; }
.dropzone p { margin: 0; color: #6c757d; }
.image-preview { max-width: 100%; margin-top: 1rem; border-radius: 0.5rem; }
.btn-submit { background: linear-gradient(135deg, #4e73df, #2a3f9d); border: none; padding: 0.75rem 2rem; border-radius: 0.5rem; font-weight: 600; transition: all 0.3s; }
.btn-submit:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(78, 115, 223, 0.4); }
.btn-submit:disabled { opacity: 0.7; cursor: not-allowed; }
.required-field::after { content: '*'; color: #e74a3b; margin-left: 0.25rem; }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Add New Listing</h1>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Listings
                </a>
            </div>

     @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('danger'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('danger') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

            <form action="{{ route('usersitem.store') }}" method="POST" enctype="multipart/form-data" class="add-listing-form">
                @csrf

                <!-- Basic Information Section -->
                <div class="form-section">
                    <h3><i class="fas fa-info-circle me-2"></i>Basic Information</h3>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="reference_id" class="form-label required-field">Reference ID</label>
                            <input type="text" class="form-control" id="reference_id" name="reference_id"
                                   value="{{ old('reference_id') }}" >

                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label required-field">Category</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->Category_Name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label required-field">Title</label>
                        <input type="text" class="form-control" id="title" name="title"
                               value="{{ old('title') }}" required>
                               @error('title')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                    </div>

                    <div class="mb-3">
                        <label for="subtitle" class="form-label">Subtitle</label>
                        <input type="text" class="form-control" id="subtitle" name="subtitle"
                               value="{{ old('subtitle') }}">
                               @error('subtitle')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="4">{{ old('content') }}</textarea>
                        @error('content')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="item_featured" class="form-label">Featured</label>
                            <select class="form-select" id="item_featured" name="item_featured">
                                <option value="0" {{ old('item_featured', 0) == 0 ? 'selected' : '' }}>No</option>
                                <option value="1" {{ old('item_featured') == 1 ? 'selected' : '' }}>Yes</option>
                            </select>
                            @error('item_featured')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="collection_date" class="form-label">Collection Date</label>
                            <input type="date" class="form-control" id="collection_date" name="collection_date"
                                   value="{{ old('collection_date') }}">
                                   @error('collection_date')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                        </div>
                    </div>

                   <div class="mb-3">
    <label for="permalink" class="form-label">Permalink</label>
    <input type="text"
           name="permalink"
           id="permalink"
           class="form-control"
           value="{{ old('permalink', isset($item) ? $item->permalink : '') }}"
           placeholder="Full URL will auto-generate from slug">
</div>

                </div>

                <!-- Image Upload Section -->
                <div class="form-section">
                    <h3><i class="fas fa-image me-2"></i>Image</h3>

                    <div class="mb-3">
                        <label for="image" class="form-label">Listing Image</label>
                        <div class="dropzone" id="imageDropzone">
                            <p>Drag & drop an image here or click to browse</p>
                            <input type="file" class="d-none" id="image" name="image" accept="image/*">
                        </div>
                        <div id="imagePreview" class="mt-3 text-center"></div>
                    </div>
                </div>

                <!-- Author Information Section -->
                <div class="form-section">
                    <h3><i class="fas fa-user me-2"></i>Author Information</h3>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="author_username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="author_username" name="author_username"
                                   value="{{ old('author_username') }}">
                                   @error('author_username')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="author_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="author_email" name="author_email"
                                   value="{{ old('author_email') }}">
                                    @error('author_email')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="author_first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="author_first_name" name="author_first_name"
                                   value="{{ old('author_first_name') }}">
                                   @error('author_first_name')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="author_last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="author_last_name" name="author_last_name"
                                   value="{{ old('author_last_name') }}">
                                   @error('author_last_name')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
    <label for="slug" class="form-label">Slug</label>
    <input type="text"
           name="slug"
           id="slug"
           class="form-control"
           value="{{ old('slug') }}"
           placeholder="Auto-generated from title">
           @error('slug')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
</div>
                        <div class="col-md-6 mb-3">
                            <label for="parent" class="form-label">Parent</label>
                            <input type="text" class="form-control" id="parent" name="parent"
                                   value="{{ old('parent') }}">
                                   @error('parent')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="parent_slug" class="form-label">Parent Slug</label>
                        <input type="text" class="form-control" id="parent_slug" name="parent_slug"
                               value="{{ old('parent_slug') }}">
                               @error('parent_slug')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="form-section">
                    <h3><i class="fas fa-address-book me-2"></i>Contact Information</h3>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="telephone" class="form-label">Telephone</label>
                            <input type="text" class="form-control" id="telephone" name="telephone"
                                   value="{{ old('telephone') }}">
                                   @error('telephone')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="phone1" class="form-label">Phone 1</label>
                            <input type="text" class="form-control" id="phone1" name="phone1"
                                   value="{{ old('phone1') }}">
                                   @error('phone1')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="phone2" class="form-label">Phone 2</label>
                            <input type="text" class="form-control" id="phone2" name="phone2"
                                   value="{{ old('phone2') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                               value="{{ old('email') }}">
                                 @error('email')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="showemail" name="showemail" value="1"
                               {{ old('showemail') ? 'checked' : '' }}>
                        <label class="form-check-label checkbox-label" for="showemail">Show Email</label>
                        @error('showemail')
                                <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="contactOwnerBtn" name="contactOwnerBtn" value="1"
                               {{ old('contactOwnerBtn') ? 'checked' : '' }}>
                        <label class="form-check-label checkbox-label" for="contactOwnerBtn">Show Contact Owner Button</label>
                        @error('contactOwnerBtn')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="web" class="form-label">Website URL</label>
                            <input type="url" class="form-control" id="web" name="web"
                                   value="{{ old('web') }}">
                                      @error('web')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="webLinkLabel" class="form-label">Web Link Label</label>
                            <input type="text" class="form-control" id="webLinkLabel" name="webLinkLabel"
                                   value="{{ old('webLinkLabel') }}">
                                      @error('webLinkLabel')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="2">{{ old('address') }}</textarea>
                        @error('address')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="latitude" class="form-label">Latitude</label>
                            <input type="text" class="form-control" id="latitude" name="latitude"
                                   value="{{ old('latitude') }}">
                        @error('latitude')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="longitude" class="form-label">Longitude</label>
                            <input type="text" class="form-control" id="longitude" name="longitude"
                                   value="{{ old('longitude') }}">
                        @error('longitude')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="streetview" class="form-label">Street View URL</label>
                        <input type="text" class="form-control" id="streetview" name="streetview"
                               value="{{ old('streetview') }}">
                        @error('streetview')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="swheading" class="form-label">SW Heading</label>
                            <input type="text" class="form-control" id="swheading" name="swheading"
                                   value="{{ old('swheading') }}">
                        @error('swheading')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="swpitch" class="form-label">SW Pitch</label>
                            <input type="text" class="form-control" id="swpitch" name="swpitch"
                                   value="{{ old('swpitch') }}">
                        @error('swpitch')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="swzoom" class="form-label">SW Zoom</label>
                            <input type="text" class="form-control" id="swzoom" name="swzoom"
                                   value="{{ old('swzoom') }}">
                        @error('swzoom')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                        </div>
                    </div>
                </div>

                <!-- Opening Hours Section -->
                <div class="form-section">
                    <h3><i class="fas fa-clock me-2"></i>Opening Hours</h3>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="displayOpeningHours" name="displayOpeningHours" value="1"
                               {{ old('displayOpeningHours') ? 'checked' : '' }}>
                        <label class="form-check-label checkbox-label" for="displayOpeningHours">Display Opening Hours</label>
                        @error('displayOpeningHours')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="openingHoursMonday" class="form-label">Monday</label>
                            <input type="text" class="form-control" id="openingHoursMonday" name="openingHoursMonday"
                                   value="{{ old('openingHoursMonday') }}" placeholder="9:00 AM - 5:00 PM">
                        @error('openingHoursMonday')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="openingHoursTuesday" class="form-label">Tuesday</label>
                            <input type="text" class="form-control" id="openingHoursTuesday" name="openingHoursTuesday"
                                   value="{{ old('openingHoursTuesday') }}" placeholder="9:00 AM - 5:00 PM">
                        @error('openingHoursTuesday')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="openingHoursWednesday" class="form-label">Wednesday</label>
                            <input type="text" class="form-control" id="openingHoursWednesday" name="openingHoursWednesday"
                                   value="{{ old('openingHoursWednesday') }}" placeholder="9:00 AM - 5:00 PM">
                        @error('openingHoursWednesday')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="openingHoursThursday" class="form-label">Thursday</label>
                            <input type="text" class="form-control" id="openingHoursThursday" name="openingHoursThursday"
                                   value="{{ old('openingHoursThursday') }}" placeholder="9:00 AM - 5:00 PM">
                        @error('openingHoursThursday')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="openingHoursFriday" class="form-label">Friday</label>
                            <input type="text" class="form-control" id="openingHoursFriday" name="openingHoursFriday"
                                   value="{{ old('openingHoursFriday') }}" placeholder="9:00 AM - 5:00 PM">
                        @error('openingHoursFriday')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="openingHoursSaturday" class="form-label">Saturday</label>
                            <input type="text" class="form-control" id="openingHoursSaturday" name="openingHoursSaturday"
                                   value="{{ old('openingHoursSaturday') }}" placeholder="10:00 AM - 2:00 PM">
                        @error('openingHoursSaturday')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="openingHoursSunday" class="form-label">Sunday</label>
                        <input type="text" class="form-control" id="openingHoursSunday" name="openingHoursSunday"
                               value="{{ old('openingHoursSunday') }}" placeholder="Closed">
                        @error('openingHoursSunday')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                    </div>

                    <div class="mb-3">
                        <label for="openingHoursNote" class="form-label">Opening Note</label>
                        <textarea class="form-control" id="openingHoursNote" name="openingHoursNote" rows="2">{{ old('openingHoursNote') }}</textarea>
                        @error('openingHoursNote')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                    </div>
                </div>

                <!-- Social Media Section -->
                <div class="form-section">
                    <h3><i class="fas fa-share-alt me-2"></i>Social Media</h3>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="displaySocialIcons" name="displaySocialIcons" value="1"
                               {{ old('displaySocialIcons') ? 'checked' : '' }}>
                        <label class="form-check-label checkbox-label" for="displaySocialIcons">Display Social Icons</label>
                        @error('displaySocialIcons')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="socialIconsOpenInNewWindow" name="socialIconsOpenInNewWindow" value="1"
                               {{ old('socialIconsOpenInNewWindow') ? 'checked' : '' }}>
                        <label class="form-check-label checkbox-label" for="socialIconsOpenInNewWindow">Open in New Window</label>
                        @error('socialIconsOpenInNewWindow')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                    </div>

                    <div class="mb-3">
                        <label for="socialIcons" class="form-label">Social Icons (e.g. fa-facebook)</label>
                        <input type="text" class="form-control" id="socialIcons" name="socialIcons"
                               value="{{ old('socialIcons') }}" placeholder="fa-facebook, fa-twitter, fa-instagram">
                        @error('socialIcons')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                    </div>

                    <div class="mb-3">
                        <label for="socialIcons_url" class="form-label">Social URL</label>
                        <input type="url" class="form-control" id="socialIcons_url" name="socialIcons_url"
                               value="{{ old('socialIcons_url') }}">
                        @error('socialIcons_url')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                    </div>
                </div>

             {{-- Gallery Upload --}}
<div class="mb-3">
    <label class="form-label fw-bold">Upload Gallery Images</label>
    <input type="file" name="gallery[]" id="galleryInput" class="form-control" multiple accept="image/*">

    {{-- Preview Container --}}
    <div id="galleryPreview" class="d-flex flex-wrap gap-2 mt-3"></div>
</div>
                    {{-- Display Options --}}
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="display" id="display"
                               {{ old('display', $item->display ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="display">Display on Website</label>
                        @error('display')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                    </div>
                <!-- Submit Button -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-submit btn-lg">
                        <i class="fas fa-plus-circle me-2"></i> Add Listing
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image dropzone functionality
    const dropzone = document.getElementById('imageDropzone');
    const fileInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');

    if (dropzone && fileInput) {
        dropzone.addEventListener('click', () => fileInput.click());

        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.style.borderColor = '#4e73df';
            dropzone.style.backgroundColor = '#f8f9fe';
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.style.borderColor = '#ccc';
            dropzone.style.backgroundColor = 'transparent';
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.style.borderColor = '#ccc';
            dropzone.style.backgroundColor = 'transparent';

            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                previewImage(e.dataTransfer.files[0]);
            }
        });

        fileInput.addEventListener('change', () => {
            if (fileInput.files.length) {
                previewImage(fileInput.files[0]);
            }
        });
    }
function previewGallery(event) {
    const preview = document.getElementById('galleryPreview');
    preview.innerHTML = ''; // clear old previews
    const files = event.target.files;

    Array.from(files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = "img-thumbnail rounded";
            img.style.maxHeight = "120px";
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
}

    function previewImage(file) {
        if (!file.type.match('image.*')) {
            alert('Please select an image file');
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.innerHTML = `<img src="${e.target.result}" class="image-preview" style="max-height: 200px;">`;
        };
        reader.readAsDataURL(file);
    }

    // Auto-generate slug from title
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');

    if (titleInput && slugInput) {
        titleInput.addEventListener('blur', () => {
            if (!slugInput.value) {
                slugInput.value = titleInput.value.toLowerCase()
                    .replace(/[^a-z0-9 -]/g, '') // remove invalid chars
                    .replace(/\s+/g, '-') // replace spaces with -
                    .replace(/-+/g, '-'); // collapse dashes
            }
        });
    }
});
</script>
<script>
    document.getElementById('galleryInput').addEventListener('change', function(event) {
        const preview = document.getElementById('galleryPreview');
        preview.innerHTML = ''; // clear old previews
        const files = event.target.files;

        Array.from(files).forEach(file => {
            if (!file.type.startsWith('image/')) return; // skip non-images

            const reader = new FileReader();
            reader.onload = e => {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = "img-thumbnail rounded";
                img.style.maxHeight = "120px";
                img.style.objectFit = "cover";
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.querySelector('#title');
    const slugInput = document.querySelector('#slug');
    const permalinkInput = document.querySelector('#permalink');
    const baseUrl = "{{ url('/') }}/items"; // adjust to your route prefix

    titleInput.addEventListener('input', function() {
        let slug = this.value.toLowerCase()
                             .replace(/[^a-z0-9\s-]/g, '')
                             .replace(/\s+/g, '-')
                             .replace(/-+/g, '-');
        slugInput.value = slug;

        // auto-update permalink
        permalinkInput.value = baseUrl + '/' + slug;
    });
});
</script>


@endsection
