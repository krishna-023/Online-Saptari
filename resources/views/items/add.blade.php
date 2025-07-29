@extends('layouts.master')
@include('common.flash')
@section('title') @lang('translation.starter')  @endsection
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Category @endslot
        @slot('title')Add Items @endslot
    @endcomponent

    <div class="container mt-5">
        <div class="row">
            <div class="col-xl-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <a href="" class="btn btn-primary float-start"
                           style="background-color: deepskyblue; color: white; border: none; text-decoration: none; padding: 10px 20px; border-radius: 5px; margin-right: 10px;"
                           onmouseover="this.style.backgroundColor='brown';"
                           onmouseout="this.style.backgroundColor='#C4A484';">
                            Add
                        </a>
                        <a href="{{ route('item.index') }}" class="btn btn-primary float-start"
                           style="background-color: deepskyblue; color: white; border: none; text-decoration: none; padding: 10px 20px; border-radius: 5px;"
                           onmouseover="this.style.backgroundColor='Red';"
                           onmouseout="this.style.backgroundColor='#FF7F7F';">
                            Item
                        </a>

                    </div><!-- end card header -->
                    <div class="card-body form-steps">
                        <form action="{{ route('item.store') }} " class="vertical-navs-step" method="POST" enctype="multipart/form-data">
                            @csrf
                            @if(isset($record))
                                @method('PUT')
                            @endif
                            <div class="row gy-5">
                                <div class="col-lg-3">
                                    <div class="nav flex-column custom-nav nav-pills" role="tablist" aria-orientation="vertical">
                                        <button class="nav-link active" id="v-pills-Category-tab" data-bs-toggle="pill" data-bs-target="#v-pills-Category" type="button" role="tab" aria-controls="v-pills-Category" aria-selected="true">
                                    <span class="step-title me-2">
                                        <i class="ri-close-circle-fill step-icon me-2"></i> Step 1
                                    </span>
                                            Category
                                        </button>
                                        <button class="nav-link" id="v-pills-Main-tab" data-bs-toggle="pill" data-bs-target="#v-pills-Main" type="button" role="tab" aria-controls="v-pills-Main" aria-selected="false">
                                    <span class="step-title me-2">
                                        <i class="ri-close-circle-fill step-icon me-2"></i> Step 2
                                    </span>
                                            Main
                                        </button>
                                        <button class="nav-link" id="v-pills-contacts-tab" data-bs-toggle="pill" data-bs-target="#v-pills-contacts" type="button" role="tab" aria-controls="v-pills-contacts" aria-selected="false">
                                    <span class="step-title me-2">
                                        <i class="ri-close-circle-fill step-icon me-2"></i> Step 3
                                    </span>
                                            Contacts
                                        </button>
                                        <button class="nav-link" id="v-pills-openingtime-tab" data-bs-toggle="pill" data-bs-target="#v-pills-openingtime" type="button" role="tab" aria-controls="v-pills-openingtime" aria-selected="false">
                                    <span class="step-title me-2">
                                        <i class="ri-close-circle-fill step-icon me-2"></i> Step 4
                                    </span>
                                            Opening Hours
                                        </button>
                                        <button class="nav-link" id="v-pills-socialicons-tab" data-bs-toggle="pill" data-bs-target="#v-pills-socialicons" type="button" role="tab" aria-controls="v-pills-socialicons" aria-selected="false">
                                    <span class="step-title me-2">
                                        <i class="ri-close-circle-fill step-icon me-2"></i> Step 5
                                    </span>
                                            SocialIcons
                                        </button>
                                        <button class="nav-link" id="v-pills-gallery-tab" data-bs-toggle="pill" data-bs-target="#v-pills-gallery" type="button" role="tab" aria-controls="v-pills-gallery" aria-selected="false">
                                    <span class="step-title me-2">
                                        <i class="ri-close-circle-fill step-icon me-2"></i> Step 6
                                    </span>
                                            Gallery
                                        </button>
                                    </div>
                                    <!-- end nav -->
                                </div> <!-- end col-->
                                <div class="col-lg-8">
                                    <div class="px-lg-10">
                                        <div class="tab-content">
                                            <div class="tab-pane fade show active" id="v-pills-Category" role="tabpanel" aria-labelledby="v-pills-Category-tab">
                                                <div>
                                                    <h5>Category</h5>
                                                    <p class="text-muted">Fill all information below</p>
                                                </div>

                                                <div>
                                                    <div class="row g-3">
                                                        <div class="col-sm-6">
                                                            <input type="radio" name="category" value="select_category" onclick="showCategorList()"> Select From Category List
                                                            <input type="radio" name="category" value="new_category" onclick="showCategoryTextBox()"> Create New

                                                            <select id="category_id_select" name="category_id" class="form-select">
                                                                <option value="">All Categories</option>
                                                                @foreach($categories as $optionCategory)
                                                                    <option value="{{ $optionCategory->id }}" >
                                                                        {{ $optionCategory->Category_Name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <input type="text" name="category_name" id="category_id_new">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label for="reference_id" class="form-label">Item Reference ID:</label>
                                                        <input type="number" class="form-control @error('reference_id') is-invalid @enderror" id="reference_id" name="reference_id" value="{{ old('reference_id') }}" >
                                                        @error('reference_id')
                                                        <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="d-flex align-items-start gap-3 mt-4">
                                                    <button type="button" class="btn btn-success btn-label right ms-auto nexttab nexttab" data-nexttab="v-pills-Main-tab"><i class="ri-arrow-right-line label-icon align-middle fs-lg ms-2"></i>Go to Main</button>
                                                </div>
                                            </div>
                                            <!-- end tab pane -->
                                            <div class="tab-pane fade" id="v-pills-Main" role="tabpanel" aria-labelledby="v-pills-Main-tab">
                                                <div>
                                                    <h5>Items</h5>
                                                    <p class="text-muted">Fill all information below</p>
                                                </div>

                                                <div>
                                                    <div class="row g-3">
                                                        <div class="col-12">
                                                            <label for="title" class="form-label">Title:</label>
                                                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                                                            @error('title')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>

                                                        <div class="col-12">
                                                            <label for="subtitle" class="form-label">Subtitle: <span class="text-muted">(Optional)</span></label>
                                                            <input type="text" class="form-control @error('subtitle') is-invalid @enderror" id="subtitle" name="subtitle" value="{{ old('subtitle') }}">
                                                            @error('subtitle')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="content" class="form-label">Content:</label>
                                                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="3">{{ old('content') }}</textarea>
                                                            @error('content')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="item_featured" class="form-label">Item Featured:</label>
                                                            <input type="text" class="form-control @error('item_featured') is-invalid @enderror" id="item_featured" name="item_featured" value="{{ old('item_featured') }}">
                                                            @error('item_featured')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="collection_date" class="form-label">CollectionDate:</label>
                                                            <input type="date" class="form-control @error('collection_date') is-invalid @enderror" id="collection_date" name="collection_date" value="{{ old('collection_date') }}" required>
                                                            @error('collection_date')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="permalink" class="form-label">Permalink:</label>
                                                            <input type="text" class="form-control @error('permalink') is-invalid @enderror" id="permalink" name="permalink" value="{{ old('permalink') }}">
                                                            @error('permalink')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="image" class="form-label">Image:</label>
                                                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                                                            @error('image')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="author_username" class="form-label">Author Username:</label>
                                                            <input type="text" class="form-control @error('author_username') is-invalid @enderror" id="author_username" name="author_username" value="{{ old('author_username') }}">
                                                            @error('author_username')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <label for="author_email" class="form-label">Author Email:</label>
                                                            <input type="email" class="form-control @error('author_email') is-invalid @enderror" id="author_email" name="author_email" value="{{ old('author_email') }}">
                                                            @error('author_email')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="author_first_name" class="form-label">Author First Name:</label>
                                                            <input type="text" class="form-control @error('author_first_name') is-invalid @enderror" id="author_first_name" name="author_first_name" value="{{ old('author_first_name') }}">
                                                            @error('author_first_name')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <label for="author_last_name" class="form-label">Author Last Name:</label>
                                                            <input type="text" class="form-control @error('author_last_name') is-invalid @enderror" id="author_last_name" name="author_last_name" value="{{ old('author_last_name') }}">
                                                            @error('author_last_name')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <label for="slug" class="form-label">Slug:</label>
                                                            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug') }}" required>
                                                            @error('slug')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="parent" class="form-label">Parent:</label>
                                                            <input type="text" class="form-control @error('parent') is-invalid @enderror" id="parent" name="parent" value="{{ old('parent') }}">
                                                            @error('parent')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="parent_slug" class="form-label">Parent Slug:</label>
                                                            <input type="text" class="form-control @error('parent_slug') is-invalid @enderror" id="parent_slug" name="parent_slug" value="{{ old('parent_slug') }}">
                                                            @error('parent_slug')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-start gap-3 mt-4">
                                                    <button type="button" class="btn btn-light btn-label previestab" data-previous="v-pills-Category-tab"><i class="ri-arrow-left-line label-icon align-middle fs-lg me-2"></i> Back to Category</button>
                                                    <button type="button" class="btn btn-success btn-label right ms-auto nexttab nexttab" data-nexttab="v-pills-contacts-tab"><i class="ri-arrow-right-line label-icon align-middle fs-lg ms-2"></i>Go to Contacts</button>
                                                </div>
                                            </div>
                                            <!-- end tab pane -->
                                            <div class="tab-pane fade" id="v-pills-contacts" role="tabpanel" aria-labelledby="v-pills-contacts-tab">
                                                <div>
                                                    <h5>Contacts</h5>
                                                    <p class="text-muted">Fill all information below</p>
                                                </div>

                                                <div>
                                                    <div class="row gy-3">
                                                        <div class="col-md-6">
                                                            <label for="telephone" class="form-label">Telephone:</label>
                                                            <input type="text" class="form-control @error('telephone') is-invalid @enderror" id="telephone" name="telephone" value="{{ old('telephone') }}" required>
                                                            @error('telephone')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                            <small class="text-muted">Telephone number should be valid</small>
                                                            <div class="invalid-feedback">
                                                                Telephone number is invalid
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                        <div id="additional-phones">
                                                            <label for="phone1">Phone 1</label>
                                                            <input type="text" class="form-control @error('phone1') is-invalid @enderror" id="phone1" name="phone1" value="{{ old('phone1') }}">
                                                            @error('phone1')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                            <label for="phone2">Phone 2:</label>
                                                            <input type="text" class="form-control @error('phone2') is-invalid @enderror" id="phone2" name="phone2" value="{{ old('phone2') }}">
                                                            @error('phone2')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>
                                                        <!-- Button to Add More Phone Numbers -->
                                                        <button type="button" class="btn btn-primary" id="add-phone" >Add More Phone Numbers</button>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="email" class="form-label">Email:</label>
                                                            <input type="email" name="email" id="email" required class="form-control" value="{{ old('email') }}">
                                                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="contactOwnerBtn" class="form-label">Contact Owner Button:</label>
                                                            <input type="checkbox" id="contactOwnerBtn" name="contactOwnerBtn" value="1" {{ old('contactOwnerBtn') ? 'checked' : '' }}>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="web" class="form-label">Website:</label>
                                                            <input type="text" class="form-control @error('web') is-invalid @enderror" id="web" name="web" value="{{ old('web') }}">
                                                            @error('web')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="webLinkLabel" class="form-label">Website Link Label:</label>
                                                            <input type="text" class="form-control @error('webLinkLabel') is-invalid @enderror" id="webLinkLabel" name="webLinkLabel" value="{{ old('webLinkLabel') }}">
                                                            @error('webLinkLabel')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="address" class="form-label">Address:</label>
                                                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}">
                                                            @error('address')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="latitude" class="form-label">Latitude:</label>
                                                            <input type="text" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude') }}">
                                                            @error('latitude')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="longitude" class="form-label">Longitude:</label>
                                                            <input type="text" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude') }}">
                                                            @error('longitude')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="streetview" class="form-label">Street View:</label>
                                                            <input type="text" class="form-control @error('streetview') is-invalid @enderror" id="streetview" name="streetview" value="{{ old('streetview') }}">
                                                            @error('streetview')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="swheading" class="form-label">Street ViewStreet View Heading:</label>
                                                            <input type="text" class="form-control @error('swheading') is-invalid @enderror" id="swheading" name="swheading" value="{{ old('swheading') }}">
                                                            @error('swheading')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="swpitch" class="form-label">Street View Pitch:</label>
                                                            <input type="text" class="form-control @error('swpitch') is-invalid @enderror" id="swpitch" name="swpitch" value="{{ old('swpitch') }}">
                                                            @error('swpitch')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="swzoom" class="form-label">Street View Zoom:</label>
                                                            <input type="text" class="form-control @error('swzoom') is-invalid @enderror" id="swzoom" name="swzoom" value="{{ old('swzoom') }}">
                                                            @error('swzoom')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-start gap-3 mt-4">
                                                    <button type="button" class="btn btn-light btn-label previestab" data-previous="v-pills-Main-tab"><i class="ri-arrow-left-line label-icon align-middle fs-lg me-2"></i> Back to Main</button>
                                                    <button type="button" class="btn btn-success btn-label right ms-auto nexttab nexttab" data-nexttab="v-pills-openingtime-tab"><i class="ri-arrow-right-line label-icon align-middle fs-lg ms-2"></i>Go to Opening Hours</button>
                                                </div>
                                            </div>

{{--                                            !end tab pane--}}
                                            <div class="tab-pane fade" id="v-pills-openingtime" role="tabpanel" aria-labelledby="v-pills-openingtime-tab">
                                                <div>
                                                    <h5>Opening Hours</h5>
                                                    <p class="text-muted">Fill all information below</p>
                                                </div>

                                                <div>
                                                    <div class="row gy-3">
                                                        <div class="col-md-6">
                                                            <label for="displayOpeningHours" class="form-label">Display Opening Hours:</label>
                                                            <input type="checkbox" id="displayOpeningHours" name="displayOpeningHours" value="1" {{ old('displayOpeningHours') ? 'checked' : '' }}>

                                                        </div>
                                                        <div class="col-gy-6">
                                                            <label for="openingHoursMonday">Opening Hours Monday:</label>
                                                            <input type="text" class="form-control @error('openingHoursMonday') is-invalid @enderror" id="openingHoursMonday" name="openingHoursMonday" value="{{ old('openingHoursMonday') }}">
                                                            @error('openingHoursMonday')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>


                                                        <div class="col-gy-3">
                                                            <label for="openingHoursTuesday" class="form-label">Opening Hours Tuesday:</label>
                                                            <input type="text" class="form-control @error('openingHoursTuesday') is-invalid @enderror" id="openingHoursTuesday" name="openingHoursTuesday" value="{{ old('openingHoursTuesday') }}">
                                                            @error('openingHoursTuesday')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>

                                                        <div class="col-gy-3">
                                                            <label for="openingHoursWednesday" class="form-label">Opening Hours Wednesday:</label>
                                                            <input type="text" class="form-control @error('openingHoursWednesday') is-invalid @enderror" id="openingHoursWednesday" name="openingHoursWednesday" value="{{ old('openingHoursWednesday') }}">
                                                            @error('openingHoursWednesday')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-gy-3">
                                                            <label for="openingHoursThursday" class="form-label">Opening Hours Thursday:</label>
                                                            <input type="text" class="form-control @error('openingHoursThursday') is-invalid @enderror" id="openingHoursThursday" name="openingHoursThursday" value="{{ old('openingHoursThursday') }}">
                                                            @error('openingHoursThursday')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-gy-3">
                                                            <label for="openingHoursFriday" class="form-label">Opening Hours Friday:</label>
                                                            <input type="text" class="form-control @error('openingHoursFriday') is-invalid @enderror" id="openingHoursFriday" name="openingHoursFriday" value="{{ old('openingHoursFriday') }}">
                                                            @error('openingHoursFriday')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-gy-3">
                                                            <label for="openingHoursSaturday" class="form-label">Opening Hours Saturday:</label>
                                                            <input type="text" class="form-control @error('openingHoursSaturday') is-invalid @enderror" id="openingHoursSaturday" name="openingHoursSaturday" value="{{ old('openingHoursSaturday') }}">
                                                            @error('openingHoursSaturday')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-gy-3">
                                                            <label for="openingHoursSunday" class="form-label">Opening Hours Sunday:</label>
                                                            <input type="text" class="form-control @error('openingHoursSunday') is-invalid @enderror" id="openingHoursSunday" name="openingHoursSunday" value="{{ old('openingHoursSunday') }}">
                                                            @error('openingHoursSunday')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-gy-6">
                                                            <label for="openingHoursNote">Opening Hours Note:</label>
                                                            <textarea class="form-control @error('openingHoursNote') is-invalid @enderror" id="openingHoursNote" name="openingHoursNote" rows="3">{{ old('openingHoursNote') }}</textarea>
                                                            @error('openingHoursNote')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-start gap-3 mt-4">
                                                    <button type="button" class="btn btn-light btn-label previestab" data-previous="v-pills-contacts-tab"><i class="ri-arrow-left-line label-icon align-middle fs-lg me-2"></i> Back to Contact</button>
                                                    <button type="button" class="btn btn-success btn-label right ms-auto nexttab nexttab" data-nexttab="v-pills-socialicons-tab"><i class="ri-arrow-right-line label-icon align-middle fs-lg ms-2"></i>Go to SocialIcons</button>
                                                </div>
                                            </div>

                                            <!-- end tab pane -->
                                            <div class="tab-pane fade" id="v-pills-socialicons" role="tabpanel" aria-labelledby="v-pills-socialicons-tab">
                                                <div>
                                                    <h5>SocialIcons</h5>
                                                    <p class="text-muted">Fill all information below</p>
                                                </div>

                                                <div>
                                                    <div class="row gy-3">
                                                        <div class="col-md-6">
                                                            <label for="displaySocialIcons" class="form-label">Display Social Icons:</label>
                                                            <input type="checkbox" id="displaySocialIcons" name="displaySocialIcons" value="1" {{ old('displaySocialIcons') ? 'checked' : '' }}>
                                                        </div>


                                                        <div class="col-md-6">
                                                            <label for="socialIconsOpenInNewWindow">Social Icons Open In New Window:</label>
                                                            <input type="checkbox" id="socialIconsOpenInNewWindow" name="socialIconsOpenInNewWindow" value="1" {{ old('socialIconsOpenInNewWindow') ? 'checked' : '' }}>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="socialIcons_Icon" class="form-label">Social Icons:</label>
                                                            <div class="input-group mb-3">
                                                                <select class="form-select" id="iconSelect" name="socialIcons">
                                                                    <option value="">Select an Icon</option>
                                                                    <option value="facebook"><i class="bi bi-facebook"></i> Facebook</option>
                                                                    <option value="twitter"><i class="bi bi-twitter"></i> Twitter</option>
                                                                    <option value="linkedin"><i class="bi bi-linkedin"></i> LinkedIn</option>
                                                                    <option value="instagram"><i class="bi bi-instagram"></i> Instagram</option>
                                                                    <option value="youtube"><i class="bi bi-youtube"></i> YouTube</option>
                                                                    <option value="github"><i class="bi bi-github"></i> GitHub</option>
                                                                    <!-- Add more options as needed -->
                                                                </select>
                                                            </div>
                                                            <div class="col-gy-6">
                                                                <label for="socialIcons_url" class="form-label">SocialIcons URL:</label>
                                                                <input type="text" class="form-control @error('socialIcons_url') is-invalid @enderror" id="socialIcons_url" name="socialIcons_url" value="{{ old('socialIcons_url') }}">
                                                                @error('socialIcons_url')
                                                                <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                                @enderror
                                                            </div>
                                                            @error('socialIcons')
                                                            <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                            @error('socialIcons_url')
                                                            <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-start gap-3 mt-4">
                                                    <button type="button" class="btn btn-light btn-label previestab" data-previous="v-pills-openingtime-tab"><i class="ri-arrow-left-line label-icon align-middle fs-lg me-2"></i> Back to Opening Hours</button>
                                                    <button type="button" class="btn btn-success btn-label right ms-auto nexttab nexttab" data-nexttab="v-pills-gallery-tab"><i class="ri-arrow-right-line label-icon align-middle fs-lg ms-2"></i>Go to Gallery</button>
                                                </div>
                                            </div>

                                            <!-- end tab pane -->
                                            <div class="tab-pane fade" id="v-pills-gallery" role="tabpanel" aria-labelledby="v-pills-gallery-tab">
                                                <div>
                                                    <h5>Gallery</h5>
                                                    <p class="text-muted">Fill all information below</p>
                                                </div>

                                                <div>
                                                    <div class="row gy-3">
                                                        <div class="col-md-6">
                                                            <label for="displayGallery" class="form-label">Display Gallery:</label>
                                                            <input type="checkbox" id="displayGallery" name="displayGallery" value="1" {{ old('displayGallery') ? 'checked' : '' }}>
                                                        </div>


                                                        <div class="col-md-6">
                                                            <label for="gallery">Gallery:</label>
                                                            <textarea class="form-control @error('gallery') is-invalid @enderror" id="gallery" name="gallery" rows="3">{{ old('gallery') }}</textarea>
                                                            @error('gallery')
                                                            <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-start gap-3 mt-4">
                                                    <button type="button" class="btn btn-light btn-label previestab" data-previous="v-pills-socialicons-tab"><i class="ri-arrow-left-line label-icon align-middle fs-lg me-2"></i> Back to SocialIcons</button>
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </div>
                                            <!-- end tab pane -->
                                        </div>
                                        <!-- end tab content -->
                                    </div>
                                </div>
                                <!-- end col -->
                            </div>
                            <!-- end row -->
                        </form>
                    </div>
                </div>
                <!-- end -->
            </div>
            <!-- end col -->
        </div>
    </div>

@endsection
@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script>
        document.getElementById('add-phone').addEventListener('click', function() {
            let container = document.getElementById('additional-phones');
            let index = container.children.length + 1;
            let newField = document.createElement('div');
            newField.className = 'form-group';
            newField.innerHTML = `
            <label for="phone${index}">Phone ${index}:</label>
            <input type="text" class="form-control" id="phone${index}" name="phone${index}">
        `;
            container.appendChild(newField);
        });
    </script>

    <script src="{{ URL::asset('build/js/pages/form-wizard.init.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Call the function when the document is ready
            showCategorList();
        });
        function showCategorList(){
            $('#category_id_select').removeClass('d-none')
            $('#category_id_new').addClass('d-none')

        }
        function showCategoryTextBox(){
            $('#category_id_select').addClass('d-none')
            $('#category_id_new').removeClass('d-none')
        }
    </script>
@endsection
