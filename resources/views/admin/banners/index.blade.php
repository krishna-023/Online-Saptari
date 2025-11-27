@extends('admin.layouts.master')
@include('common.flash')
@section('content')
<div class="container">
    <h2 class="mb-4">Active Banners</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>User</th>
                <th>Title</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Created By</th>
                <th>Updated By</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($banners as $banner)
            <tr>
                <td>{{ $banner->user?->name ?? 'N/A' }}</td>
                <td>{{ $banner->title }}</td>
                <td>{{ $banner->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $banner->updated_at->format('Y-m-d H:i') }}</td>
                <td>{{ $banner->creator?->name ?? 'N/A' }}</td>
                <td>{{ $banner->updater?->name ?? 'N/A' }}</td>
                <td>{{ $banner->is_active ? 'Active' : 'Inactive' }}</td>
                <td>
                    <a href="{{ route('banners.show', $banner->id) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('banners.edit', $banner->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('banners.destroy', $banner->id) }}" method="POST" style="display:inline-block;">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7">No active banners found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    {{ $banners->links() }}
</div>
@endsection
