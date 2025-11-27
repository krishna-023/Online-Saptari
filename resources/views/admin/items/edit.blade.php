@extends('admin.layouts.master')
@section('title')
    @lang('translation.starter')
@endsection
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
@endsection
@section('content')
<div class="flash-message">
        @include('common.flash')
    </div>
    @component('components.breadcrumb')
        @slot('li_1')
            Category
        @endslot
        @slot('title')
            Edit Items
        @endslot
    @endcomponent

    <div class="container mt-5">
        <div class="row">
            <div class="col-xl-12">
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
<form action="{{ route('item.update', encrypt($item->id)) }}" method="POST" enctype="multipart/form-data">
                               @csrf
                                @method('PUT')
                            <div class="row gy-5">
                                <div class="col-lg-3">
                                    <div class="nav flex-column custom-nav nav-pills" role="tablist"
                                        aria-orientation="vertical">
                                        <button class="nav-link active" id="v-pills-Category-tab" data-bs-toggle="pill"
                                            data-bs-target="#v-pills-Category" type="button" role="tab"
                                            aria-controls="v-pills-Category" aria-selected="true">
                                            <span class="step-title me-2">
                                                <i class="ri-close-circle-fill step-icon me-2"></i> Step 1
                                            </span>
                                            Category
                                        </button>
                                        <button class="nav-link" id="v-pills-Main-tab" data-bs-toggle="pill"
                                            data-bs-target="#v-pills-Main" type="button" role="tab"
                                            aria-controls="v-pills-Main" aria-selected="false">
                                            <span class="step-title me-2">
                                                <i class="ri-close-circle-fill step-icon me-2"></i> Step 2
                                            </span>
                                            Main
                                        </button>
                                        <button class="nav-link" id="v-pills-contacts-tab" data-bs-toggle="pill"
                                            data-bs-target="#v-pills-contacts" type="button" role="tab"
                                            aria-controls="v-pills-contacts" aria-selected="false">
                                            <span class="step-title me-2">
                                                <i class="ri-close-circle-fill step-icon me-2"></i> Step 3
                                            </span>
                                            Contacts
                                        </button>
                                        <button class="nav-link" id="v-pills-openingtime-tab" data-bs-toggle="pill"
                                            data-bs-target="#v-pills-openingtime" type="button" role="tab"
                                            aria-controls="v-pills-openingtime" aria-selected="false">
                                            <span class="step-title me-2">
                                                <i class="ri-close-circle-fill step-icon me-2"></i> Step 4
                                            </span>
                                            Opening Hours
                                        </button>
                                        <button class="nav-link" id="v-pills-socialicons-tab" data-bs-toggle="pill"
                                            data-bs-target="#v-pills-socialicons" type="button" role="tab"
                                            aria-controls="v-pills-socialicons" aria-selected="false">
                                            <span class="step-title me-2">
                                                <i class="ri-close-circle-fill step-icon me-2"></i> Step 5
                                            </span>
                                            SocialIcons
                                        </button>
                                        <button class="nav-link" id="v-pills-gallery-tab" data-bs-toggle="pill"
                                            data-bs-target="#v-pills-gallery" type="button" role="tab"
                                            aria-controls="v-pills-gallery" aria-selected="false">
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
                                            <div class="tab-pane fade show active" id="v-pills-Category" role="tabpanel"
    aria-labelledby="v-pills-Category-tab">

    <div>
        <h5>Category</h5>
        <p class="text-muted">Update all information below</p>
    </div>

    <div class="row g-3">
        <div class="col-sm-6">
            <!-- Radio buttons to choose selection mode -->
            <div class="mb-2">
                <input type="radio" name="category_mode"
                    value="select_category" id="radio_select"
                    onclick="toggleCategoryMode()"
                    {{ old('category_mode', isset($item->category_id) ? 'select_category' : 'new_category') == 'select_category' ? 'checked' : '' }}>
                <label for="radio_select">Select From Category List</label>

                <input type="radio" name="category_mode"
                    value="new_category" id="radio_new"
                    onclick="toggleCategoryMode()"
                    {{ old('category_mode', isset($item->category_id) ? 'select_category' : 'new_category') == 'new_category' ? 'checked' : '' }}>
                <label for="radio_new">Create New</label>
            </div>

            <!-- Select existing category -->
            <div id="category_select_div"
                style="{{ old('category_mode', isset($item->category_id) ? 'select_category' : 'new_category') == 'select_category' ? '' : 'display:none;' }}">
                <label for="category_id_select" class="form-label">Parent Category</label>
                <select id="category_id_select" name="category_id" class="form-select">
                    <option value="">-- Select Parent Category --</option>
                    @foreach ($categories as $optionCategory)
                        <option value="{{ $optionCategory->id }}"
                            {{ old('category_id', $item->category_id ?? '') == $optionCategory->id ? 'selected' : '' }}>
                            {{ $optionCategory->Category_Name }}
                        </option>
                        @if ($optionCategory->children)
                            @foreach ($optionCategory->children as $child)
                                <option value="{{ $child->id }}"
                                    {{ old('category_id', $item->category_id ?? '') == $child->id ? 'selected' : '' }}>
                                    &nbsp;&nbsp;— {{ $child->Category_Name }}
                                </option>
                            @endforeach
                        @endif
                    @endforeach
                </select>
            </div>

            <!-- Create new category -->
            <div id="category_new_div"
                style="{{ old('category_mode', isset($item->category_id) ? 'select_category' : 'new_category') == 'new_category' ? '' : 'display:none;' }}">
                <label for="category_name" class="form-label">New Parent Category</label>
                <input type="text" name="category_name" id="category_name"
                    class="form-control mb-2"
                    placeholder="Enter parent category name"
                    value="{{ old('category_name', $item->category->Category_Name ?? '') }}">

                <label for="child_category_name" class="form-label">New Child Category (optional)</label>
                <input type="text" name="child_category_name" id="child_category_name"
                    class="form-control"
                    placeholder="Enter child category name"
                    value="{{ old('child_category_name') }}">
            </div>
        </div>

        <div class="col-sm-6">
            <label for="reference_id" class="form-label">Item Reference ID:</label>
            <input type="number"
                class="form-control @error('reference_id') is-invalid @enderror"
                id="reference_id" name="reference_id"
                value="{{ old('reference_id', $item->reference_id ?? '') }}">
            @error('reference_id')
                <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>

    <div class="d-flex align-items-start gap-3 mt-4">
        <button type="button"
            class="btn btn-success btn-label right ms-auto nexttab"
            data-nexttab="v-pills-Main-tab">
            <i class="ri-arrow-right-line label-icon align-middle fs-lg ms-2"></i>Go to Main
        </button>
    </div>
</div>

                                            <!-- end tab pane -->
                                            <div class="tab-pane fade" id="v-pills-Main" role="tabpanel"
    aria-labelledby="v-pills-Main-tab">
    <div>
        <h5>Items</h5>
        <p class="text-muted">Fill all information below</p>
    </div>

    <div>
        <div class="row g-3">
            <div class="col-12">
                <label for="title" class="form-label">Title:</label>
                <input type="text"
                    class="form-control @error('title') is-invalid @enderror"
                    id="title" name="title"
                    value="{{ old('title', $item->title) }}" required>
                @error('title')
                    <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="col-12">
                <label for="subtitle" class="form-label">Subtitle: <span class="text-muted">(Optional)</span></label>
                <input type="text"
                    class="form-control @error('subtitle') is-invalid @enderror"
                    id="subtitle" name="subtitle"
                    value="{{ old('subtitle', $item->subtitle) }}">
                @error('subtitle')
                    <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="content" class="form-label">Content:</label>
                <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="5">{{ old('content', $item->content) }}</textarea>
                @error('content')
                    <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="item_featured" class="form-label">Item Featured:</label>
                <input type="text"
                    class="form-control @error('item_featured') is-invalid @enderror"
                    id="item_featured" name="item_featured"
                    value="{{ old('item_featured', $item->item_featured) }}">
                @error('item_featured')
                    <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="collection_date" class="form-label">CollectionDate:</label>
                <input type="date"
                    class="form-control @error('collection_date') is-invalid @enderror"
                    id="collection_date" name="collection_date"
                    value="{{ old('collection_date', $item->collection_date) }}" required>
                @error('collection_date')
                    <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="permalink" class="form-label">Permalink:</label>
                <input type="text"
                    class="form-control @error('permalink') is-invalid @enderror"
                    id="permalink" name="permalink"
                    value="{{ old('permalink', $item->permalink) }}">
                @error('permalink')
                    <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="image" class="form-label">Image:</label>
                <input type="file"
                    class="form-control @error('image') is-invalid @enderror"
                    id="image" name="image">
                @if($item->image)
                    <div class="mt-2">
                        <img src="{{ asset('storage/'.$item->image) }}" alt="Item Image" class="img-thumbnail" width="120">
                    </div>
                @endif
                @error('image')
                    <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="author_username" class="form-label">Author Username:</label>
                <input type="text"
                    class="form-control @error('author_username') is-invalid @enderror"
                    id="author_username" name="author_username"
                    value="{{ old('author_username', $item->author_username) }}">
                @error('author_username')
                    <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="col-sm-6">
                <label for="author_email" class="form-label">Author Email:</label>
                <input type="email"
                    class="form-control @error('author_email') is-invalid @enderror"
                    id="author_email" name="author_email"
                    value="{{ old('author_email', $item->author_email) }}">
                @error('author_email')
                    <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="author_first_name" class="form-label">Author First Name:</label>
                <input type="text"
                    class="form-control @error('author_first_name') is-invalid @enderror"
                    id="author_first_name" name="author_first_name"
                    value="{{ old('author_first_name', $item->author_first_name) }}">
                @error('author_first_name')
                    <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="col-sm-6">
                <label for="author_last_name" class="form-label">Author Last Name:</label>
                <input type="text"
                    class="form-control @error('author_last_name') is-invalid @enderror"
                    id="author_last_name" name="author_last_name"
                    value="{{ old('author_last_name', $item->author_last_name) }}">
                @error('author_last_name')
                    <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="col-sm-6">
                <label for="slug" class="form-label">Slug:</label>
                <input type="text"
                    class="form-control @error('slug') is-invalid @enderror"
                    id="slug" name="slug"
                    value="{{ old('slug', $item->slug) }}" required>
                @error('slug')
                    <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="parent" class="form-label">Parent:</label>
                <input type="text"
                    class="form-control @error('parent') is-invalid @enderror"
                    id="parent" name="parent"
                    value="{{ old('parent', $item->parent) }}">
                @error('parent')
                    <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="parent_slug" class="form-label">Parent Slug:</label>
                <input type="text"
                    class="form-control @error('parent_slug') is-invalid @enderror"
                    id="parent_slug" name="parent_slug"
                    value="{{ old('parent_slug', $item->parent_slug) }}">
                @error('parent_slug')
                    <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>
    </div>

    <div class="d-flex align-items-start gap-3 mt-4">
        <button type="button" class="btn btn-light btn-label previestab"
            data-previous="v-pills-Category-tab"><i
                class="ri-arrow-left-line label-icon align-middle fs-lg me-2"></i>
            Back to Category</button>
        <button type="button"
            class="btn btn-success btn-label right ms-auto nexttab nexttab"
            data-nexttab="v-pills-contacts-tab"><i
                class="ri-arrow-right-line label-icon align-middle fs-lg ms-2"></i>Go
            to Contacts</button>
    </div>
</div>

                                            <!-- end tab pane -->
                                            <div class="tab-pane fade" id="v-pills-contacts" role="tabpanel"
    aria-labelledby="v-pills-contacts-tab">
    <div>
        <h5>Contacts</h5>
        <p class="text-muted">Update all information below</p>
    </div>

    <div>
        <div class="row gy-3">
            <div class="col-md-6">
                <label for="telephone" class="form-label">Telephone:</label>
                <input type="text"
                    class="form-control @error('telephone') is-invalid @enderror"
                    id="telephone" name="telephone"
                    value="{{ old('telephone', $item->contacts->telephone ?? '') }}" required>
                @error('telephone')
                    <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="col-md-6">
                <div id="additional-phones">
                    <label for="phone1">Phone 1:</label>
                    <input type="text"
                        class="form-control @error('phone1') is-invalid @enderror"
                        id="phone1" name="phone1"
                        value="{{ old('phone1', $item->contacts->phone1 ?? '') }}">
                    @error('phone1')
                        <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror

                    <label for="phone2">Phone 2:</label>
                    <input type="text"
                        class="form-control @error('phone2') is-invalid @enderror"
                        id="phone2" name="phone2"
                        value="{{ old('phone2', $item->contacts->phone2 ?? '') }}">
                    @error('phone2')
                        <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <button type="button" class="btn btn-primary" id="add-phone">Add More Phone Numbers</button>
            </div>

            <div class="col-md-6">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" required
                    class="form-control"
                    value="{{ old('email', $item->contacts->email ?? '') }}">
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="contactOwnerBtn" class="form-label">Contact Owner Button:</label>
                <input type="checkbox" id="contactOwnerBtn"
                    name="contactOwnerBtn" value="1"
                    {{ old('contactOwnerBtn', $item->contacts->contactOwnerBtn ?? false) ? 'checked' : '' }}>
            </div>

            <div class="col-md-6">
                <label for="web" class="form-label">Website:</label>
                <input type="text"
                    class="form-control @error('web') is-invalid @enderror"
                    id="web" name="web"
                    value="{{ old('web', $item->contacts->web ?? '') }}">
                @error('web')
                    <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="webLinkLabel" class="form-label">Website Link Label:</label>
                <input type="text"
                    class="form-control @error('webLinkLabel') is-invalid @enderror"
                    id="webLinkLabel" name="webLinkLabel"
                    value="{{ old('webLinkLabel', $item->contacts->webLinkLabel ?? '') }}">
                @error('webLinkLabel')
                    <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="address" class="form-label">Address:</label>
                <input type="text"
                    class="form-control @error('address') is-invalid @enderror"
                    id="address" name="address"
                    value="{{ old('address', $item->contacts->address ?? '') }}">
                @error('address')
                    <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="latitude" class="form-label">Latitude:</label>
                <input type="text"
                    class="form-control @error('latitude') is-invalid @enderror"
                    id="latitude" name="latitude"
                    value="{{ old('latitude', $item->contacts->latitude ?? '') }}">
                @error('latitude')
                    <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="longitude" class="form-label">Longitude:</label>
                <input type="text"
                    class="form-control @error('longitude') is-invalid @enderror"
                    id="longitude" name="longitude"
                    value="{{ old('longitude', $item->contacts->longitude ?? '') }}">
                @error('longitude')
                    <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="streetview" class="form-label">Street View:</label>
                <input type="text"
                    class="form-control @error('streetview') is-invalid @enderror"
                    id="streetview" name="streetview"
                    value="{{ old('streetview', $item->contacts->streetview ?? '') }}">
                @error('streetview')
                    <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="swheading" class="form-label">Street View Heading:</label>
                <input type="text"
                    class="form-control @error('swheading') is-invalid @enderror"
                    id="swheading" name="swheading"
                    value="{{ old('swheading', $item->contacts->swheading ?? '') }}">
                @error('swheading')
                    <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="swpitch" class="form-label">Street View Pitch:</label>
                <input type="text"
                    class="form-control @error('swpitch') is-invalid @enderror"
                    id="swpitch" name="swpitch"
                    value="{{ old('swpitch', $item->contacts->swpitch ?? '') }}">
                @error('swpitch')
                    <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="swzoom" class="form-label">Street View Zoom:</label>
                <input type="text"
                    class="form-control @error('swzoom') is-invalid @enderror"
                    id="swzoom" name="swzoom"
                    value="{{ old('swzoom', $item->contacts->swzoom ?? '') }}">
                @error('swzoom')
                    <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>
    </div>

    <div class="d-flex align-items-start gap-3 mt-4">
        <button type="button" class="btn btn-light btn-label previestab"
            data-previous="v-pills-Main-tab"><i
                class="ri-arrow-left-line label-icon align-middle fs-lg me-2"></i>
            Back to Main</button>
        <button type="button"
            class="btn btn-success btn-label right ms-auto nexttab nexttab"
            data-nexttab="v-pills-openingtime-tab"><i
                class="ri-arrow-right-line label-icon align-middle fs-lg ms-2"></i>
            Go to Opening Hours</button>
    </div>
</div>

                                            {{--                                            !end tab pane --}}
                                            <div class="tab-pane fade" id="v-pills-openingtime" role="tabpanel" aria-labelledby="v-pills-openingtime-tab">
    <div class="mb-3">
        <h5>Opening Hours</h5>
        <p class="text-muted">Update all information below</p>
    </div>

    <div class="row gy-3">
        <div class="col-md-6">
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="displayOpeningHours" name="displayOpeningHours" value="1"
                    {{ old('displayOpeningHours', $item->OpeningTime->displayOpeningHours ?? false) ? 'checked' : '' }}>
                <label class="form-check-label" for="displayOpeningHours">Display Opening Hours</label>
            </div>
        </div>

        @php
            $days = [
                'Monday' => 'openingHoursMonday',
                'Tuesday' => 'openingHoursTuesday',
                'Wednesday' => 'openingHoursWednesday',
                'Thursday' => 'openingHoursThursday',
                'Friday' => 'openingHoursFriday',
                'Saturday' => 'openingHoursSaturday',
                'Sunday' => 'openingHoursSunday',
            ];
        @endphp

        @foreach($days as $dayName => $field)
            <div class="col-md-6">
                <label for="{{ $field }}" class="form-label">Opening Hours {{ $dayName }}:</label>
                <input type="text" class="form-control @error($field) is-invalid @enderror" id="{{ $field }}" name="{{ $field }}"
                    value="{{ old($field, $item->OpeningTime->$field ?? '') }}">
                @error($field)
                    <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        @endforeach

        <div class="col-12">
            <label for="openingHoursNote" class="form-label">Opening Hours Note:</label>
            <textarea class="form-control @error('openingHoursNote') is-invalid @enderror" id="openingHoursNote" name="openingHoursNote" rows="3">{{ old('openingHoursNote', $item->OpeningTime->openingHoursNote ?? '') }}</textarea>
            @error('openingHoursNote')
                <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>

    <div class="d-flex align-items-start gap-3 mt-4">
        <button type="button" class="btn btn-light btn-label previestab" data-previous="v-pills-contacts-tab">
            <i class="ri-arrow-left-line label-icon align-middle fs-lg me-2"></i>Back to Contact
        </button>
        <button type="button" class="btn btn-success btn-label ms-auto nexttab" data-nexttab="v-pills-socialicons-tab">
            Go to SocialIcons<i class="ri-arrow-right-line label-icon align-middle fs-lg ms-2"></i>
        </button>
    </div>
</div>


                                            <!-- end tab pane -->
                                           <div class="tab-pane fade" id="v-pills-socialicons" role="tabpanel" aria-labelledby="v-pills-socialicons-tab">
    <div class="mb-3">
        <h5>Social Icons</h5>
        <p class="text-muted">Fill all information below</p>
    </div>

    <div class="row gy-3">
        @php
            $social = $item->socialIcons->first() ?? null; // Assuming you only want the first or handle multiple in loop
        @endphp

        <div class="col-md-6">
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="displaySocialIcons" name="displaySocialIcons" value="1"
                    {{ old('displaySocialIcons', $social->displaySocialIcons ?? false) ? 'checked' : '' }}>
                <label class="form-check-label" for="displaySocialIcons">Display Social Icons</label>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="socialIconsOpenInNewWindow" name="socialIconsOpenInNewWindow" value="1"
                    {{ old('socialIconsOpenInNewWindow', $social->socialIconsOpenInNewWindow ?? false) ? 'checked' : '' }}>
                <label class="form-check-label" for="socialIconsOpenInNewWindow">Open in New Window</label>
            </div>
        </div>

        <div class="col-md-6">
            <label for="socialIcons" class="form-label">Social Icon:</label>
            <select class="form-select @error('socialIcons') is-invalid @enderror" id="socialIcons" name="socialIcons">
                <option value="">Select an Icon</option>
                @php
                    $icons = ['facebook','twitter','linkedin','instagram','youtube','github'];
                @endphp
                @foreach($icons as $icon)
                    <option value="{{ $icon }}" {{ old('socialIcons', $social->socialIcons ?? '') == $icon ? 'selected' : '' }}>
                        {{ ucfirst($icon) }}
                    </option>
                @endforeach
            </select>
            @error('socialIcons')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="socialIcons_url" class="form-label">Social Icon URL:</label>
            <input type="text" class="form-control @error('socialIcons_url') is-invalid @enderror" id="socialIcons_url" name="socialIcons_url"
                value="{{ old('socialIcons_url', $social->socialIcons_url ?? '') }}">
            @error('socialIcons_url')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="d-flex align-items-start gap-3 mt-4">
        <button type="button" class="btn btn-light btn-label previestab" data-previous="v-pills-openingtime-tab">
            <i class="ri-arrow-left-line label-icon align-middle fs-lg me-2"></i>Back to Opening Hours
        </button>
        <button type="button" class="btn btn-success btn-label ms-auto nexttab" data-nexttab="v-pills-gallery-tab">
            Go to Gallery<i class="ri-arrow-right-line label-icon align-middle fs-lg ms-2"></i>
        </button>
    </div>
</div>


                                            <!-- end tab pane -->
          <div class="tab-pane fade" id="v-pills-gallery" role="tabpanel" aria-labelledby="v-pills-gallery-tab">
    <div class="mb-3">
        <h5>Gallery</h5>
        <p class="text-muted">Upload images for the gallery below</p>
    </div>

    <div class="row gy-3">
        <!-- Display Gallery Checkbox -->
        <div class="col-md-6">
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="displayGallery" name="displayGallery" value="1"
                    {{ old('displayGallery', optional($item)->displayGallery) ? 'checked' : '' }}>
                <label class="form-check-label" for="displayGallery">Display Gallery</label>
            </div>
            @error('displayGallery')
                <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <!-- Gallery Images Upload -->
        <div class="col-md-12">
            <label for="gallery" class="form-label">Upload Gallery Images:</label>
            <input type="file" class="form-control @error('gallery') is-invalid @enderror" id="gallery" name="gallery[]" multiple>
            @error('gallery')
                <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
            @enderror

            <!-- Show already uploaded images -->
           @if(isset($item->galleries) && $item->galleries->count() > 0)
    <div class="mt-2 d-flex flex-wrap">
        @php use Illuminate\Support\Str; @endphp
        @foreach($item->galleries as $gallery)
            @php
                // Fallback to raw DB attribute if Eloquent cast fails
                $galleryValue = $gallery->gallery;
                if (empty($galleryValue) && isset($gallery->getAttributes()['gallery'])) {
                    $galleryValue = json_decode($gallery->getAttributes()['gallery'], true);
                }
                $images = is_array($galleryValue) ? $galleryValue : (is_string($galleryValue) ? json_decode($galleryValue, true) ?? [$galleryValue] : []);
                $images = is_array($images) ? $images : [];
            @endphp
            @foreach($images as $img)
                @php $img = trim($img); if (empty($img)) continue; @endphp
                @php
                    if (Str::startsWith($img, ['http://','https://'])) {
                        $imgUrl = $img;
                    } elseif (Str::startsWith($img, 'storage/')) {
                        $imgUrl = asset($img);
                    } else {
                        $imgUrl = asset('storage/' . ltrim($img, '/'));
                    }
                @endphp
                <div class="me-2 mb-2 position-relative">
                    <img src="{{ $imgUrl }}" width="100" class="rounded border p-1">
                </div>
            @endforeach
        @endforeach
    </div>
@endif

        </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="d-flex align-items-start gap-3 mt-4">
        <button type="button" class="btn btn-light btn-label previestab" data-previous="v-pills-socialicons-tab">
            <i class="ri-arrow-left-line label-icon align-middle fs-lg me-2"></i>Back to SocialIcons
        </button>

        <button type="submit" class="btn btn-primary">Update</button>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
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
        function toggleCategoryMode() {
            const selectDiv = document.getElementById('category_select_div');
            const newDiv = document.getElementById('category_new_div');
            const mode = document.querySelector('input[name="category_mode"]:checked').value;

            if (mode === 'select_category') {
                selectDiv.style.display = 'block';
                newDiv.style.display = 'none';
            } else {
                selectDiv.style.display = 'none';
                newDiv.style.display = 'block';
            }
        }
    </script>
    <script>
        document.getElementById('gallery').addEventListener('change', function(e) {
            const previewContainer = document.getElementById('galleryPreview');
            previewContainer.innerHTML = '';

            Array.from(e.target.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const img = document.createElement('img');
                    img.src = event.target.result;
                    img.style.width = '100px';
                    img.style.height = '100px';
                    img.style.objectFit = 'cover';
                    img.style.border = '1px solid #ccc';
                    img.style.borderRadius = '4px';
                    previewContainer.appendChild(img);
                }
                reader.readAsDataURL(file);
            });
        });
    </script>
@endsection
