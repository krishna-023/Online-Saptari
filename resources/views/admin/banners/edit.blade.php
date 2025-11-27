@extends('admin.layouts.master')
@include('common.flash')
@section('content')
<div class="container">
    <h2>Edit Banner</h2>

    <form action="{{ route('banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" value="{{ old('title', $banner->title) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Link</label>
            <input type="url" name="link" value="{{ old('link', $banner->link) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Active</label>
            <select name="is_active" class="form-control">
                <option value="1" {{ $banner->is_active ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ !$banner->is_active ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Image</label><br>
            @if($banner->image)
                <img src="{{ asset('storage/'.$banner->image) }}" alt="Banner" width="200" class="mb-2"><br>
            @endif
            <input type="file" name="image" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('banners.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
