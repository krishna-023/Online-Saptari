@extends('layouts.master')

@section('title')
    @lang('translation.view-item')
@endsection

@section('css')
    <link href="{{ URL::asset('build/libs/dropzone/dropzone.css') }}" rel="stylesheet" type="text/css" />
    <!-- Include any additional CSS if needed -->
    <!-- Include Leaflet.js -->
    <!-- Include Leaflet.js -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Items
        @endslot
        @slot('title')
            View Item
        @endslot
    @endcomponent
    <div class="row">
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @include('items.common.button')

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5>Basic Information</h5>
                    <p><strong>ID:</strong> {{ $item->id }}</p>
                    <p><strong>Category:</strong> {{ $item->category->Category_Name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5>Item Information</h5>
                    <p><strong>Title:</strong> {{ $item->title }}</p>
                    <p><strong>Subtitle:</strong> {{ $item->subtitle }}</p>
                    <p><strong>Content:</strong>{{ $item->content }}</p>
                    <p><strong>Featured:</strong> {{ $item->item_featured ?? 'No' }}</p>
                    <p><strong>Date:</strong>
                        {{ $item->collection_date ? \Carbon\Carbon::parse($item->collection_date)->format('d-m-Y') : 'N/A' }}</p>
                    <p><strong>Permalink:</strong> <a href="{{ $item->permalink }}"
                            target="_blank">{{ $item->permalink }}</a></p>
                            <p><strong>Image:</strong></p>
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="img-fluid mb-2" style="max-width: 20%; height: auto;">
                            @else
                                <p>No image available.</p>
                            @endif
                    <p><strong>Author UserName:</strong>{{ $item->author_username }}</p>
                    <p><strong>Author Email:</strong> <a href="mailto:{{ $item->author_email }}">{{ $item->author_email }}</a></p> <!-- Email as clickable mailto link -->
                    <p><strong>Author FirstName:</strong>{{ $item->author_first_name }}</p>
                    <p><strong>Author LastName:</strong>{{ $item->author_last_name }}</p>
                    <p><strong>Slug:</strong>{{ $item->slug }}</p>
                    <p><strong>Parent:</strong>{{ $item->parent }}</p>
                    <p><strong>Parent Slug:</strong>{{ $item->parent_slug }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card ms-auto">
                <div class="card-body">
                    <h5>Address Information</h5>
                    @php
                        $validContacts = collect($item->contacts)->filter(function ($contact) {
                            return is_numeric($contact->latitude) && is_numeric($contact->longitude);
                        });
                    @endphp

                    @forelse($validContacts as $contact)
                        <p><strong>Telephone:</strong> <a href="tel:{{$contact->telephone}}"> {{$contact->telephone }}</a></p>
                        <p><strong>Phone-1:</strong> <a href="tel:{{$contact->phone1}}"> {{$contact->phone1 }}</a></p>
                        <p><strong>Phone-2:</strong> <a href="tel:{{$contact->phone2}}"> {{$contact->phone2 }}</a></p>
                        <p><strong>Email:</strong> <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></p> <!-- Email as clickable mailto link -->
                        <p><strong>Address:</strong> {{ $contact->address }}</p>
                        <p><strong>Latitude:</strong> {{ $contact->latitude }}</p>
                        <p><strong>Longitude:</strong> {{ $contact->longitude }}</p>
                    @empty
                        <p>No valid addresses available</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="col-md-6">
            <div id="map" style="height: 400px; background: #ddd;"></div>
            <p id="map-error" style="color: red; display: none;">No valid location found. Showing default location.</p>
        </div>


        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5>Opening Times</h5>
                    @if ($item->opening_Time)
                        {{-- <p><strong>Display Opening Hours:</strong> {{ $item->openingTimes->displayOpeningHours }}</p> --}}
                        <p><strong>Display Opening Hours:</strong> {{ $item->Opening_Time->displayOpeningHours }}</p>
                        <p><strong>Monday:</strong> {{ $item->Opening_Time->openingHoursMonday }}</p>
                        <p><strong>Tuesday:</strong> {{ $item->Opening_Time->openingHoursTuesday }}</p>
                        <p><strong>Wednesday:</strong> {{ $item->opening_Time->openingHoursWednesday }}</p>
                        <p><strong>Thursday:</strong> {{ $item->opening_Time->openingHoursThursday }}</p>
                        <p><strong>Friday:</strong> {{ $item->opening_Time->openingHoursFriday }}</p>
                        <p><strong>Saturday:</strong> {{ $item->opening_Time->openingHoursSaturday }}</p>
                        <p><strong>Sunday:</strong> {{ $item->opening_Time->openingHoursSunday }}</p>
                        <p><strong>Note:</strong> {{ $item->opening_Time->openingHoursNote }}</p>
                    @else
                        <p>No opening times available.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5>Social Links</h5>
                    @forelse($item->socialIcons as $socialIcon)
                    <p><strong>Display SocialIcons:</strong> {{ $socialIcon->displaySocialIcons }}</p>
                    <p>
                            <strong>{{ $socialIcon->socialIcons }}:</strong>
                            <a href="{{ $socialIcon->socialIcons_url }}" target="{{ $socialIcon->socialIconsOpenInNewWindow ? '_blank' : '_self' }}">
                                {{ $socialIcon->socialIcons_url }}
                            </a>
                        </p>
                    @empty
                        <p>No social links available</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5>Gallery</h5>
                    <p><strong>Display Gallery:</strong> {{ $item->displayGallery ? 'Yes' : 'No' }}</p>

                    <p><strong>Gallery Url:</strong> <a href="{{ $item->gallery }}"
                        target="_blank">{{ $item->gallery }}</a></p>
                </div>
            </div>
        </div>


        <div class="row mt-4">
            <div class="col-lg-12 text-end">
                <a href="{{ route('item.index') }}" class="btn btn-primary">Back to List</a>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script src="{{ URL::asset('build/libs/masonry-layout/masonry.pkgd.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/card.init.js') }}"></script>
    <script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var validLocations = [];
            @foreach ($validContacts as $contact)
                validLocations.push({
                    lat: parseFloat("{{ $contact->latitude }}"),
                    lng: parseFloat("{{ $contact->longitude }}"),
                    address: "{{ $contact->address }}"
                });
            @endforeach

            // Default location (Kathmandu)
            var defaultLocation = {
                lat: 27.7172,
                lng: 85.3240,
                address: "Kathmandu, Nepal"
            };

            // Use first valid location, else fallback
            var mapCenter = validLocations.length > 0 ? validLocations[0] : defaultLocation;

            // Initialize map
            var map = L.map('map').setView([mapCenter.lat, mapCenter.lng], 10);

            // Add OpenStreetMap tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            if (validLocations.length > 0) {
                validLocations.forEach(function(location) {
                    L.marker([location.lat, location.lng])
                        .addTo(map)
                        .bindPopup("<b>" + location.address + "</b><br>Lat: " + location.lat + ", Lng: " +
                            location.lng);
                });
            } else {
                // Show error message
                document.getElementById("map-error").style.display = "block";
                // Mark default location
                L.marker([defaultLocation.lat, defaultLocation.lng])
                    .addTo(map)
                    .bindPopup("<b>" + defaultLocation.address + "</b><br>Default location");
            }
        });
    </script>

    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <!-- Include any additional JavaScript if needed -->
@endsection
