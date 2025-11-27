@extends('admin.layouts.master')
@include('common.flash')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Edit User</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('user.update', $user) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                        <option value="">Select Role</option>
                                        @foreach($roles as $value => $label)
                                            <option value="{{ $value }}" {{ old('role', $user->role) == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="profile_picture" class="form-label">Profile Picture</label>
                                    <input type="file" class="form-control @error('profile_picture') is-invalid @enderror"
                                           id="profile_picture" name="profile_picture" accept="image/*">
                                    @error('profile_picture')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($user->profile_picture)
                                        <div class="mt-2">
                                            <img src="{{ Storage::url($user->profile_picture) }}" alt="Profile" class="rounded" width="80">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($authUser->role !== 'user')
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Permissions</label>
                                    <div class="permissions-container">
                                        @foreach($permissionGroups as $groupKey => $group)
                                            <div class="card mb-3">
                                                <div class="card-header">
                                                    <h6 class="mb-0">{{ $group['name'] }}</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        @foreach($group['permissions'] as $permissionKey => $permissionName)
                                                            <div class="col-md-4 mb-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                           name="permissions[]"
                                                                           value="{{ $permissionKey }}"
                                                                           id="perm_{{ $permissionKey }}"
                                                                           {{ in_array($permissionKey, $user->permissions ?? []) ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="perm_{{ $permissionKey }}">
                                                                        {{ $permissionName }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Update User</button>
                                <a href="{{ route('user.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-select permissions based on role
    document.getElementById('role').addEventListener('change', function() {
        if(confirm('Changing the role will reset the permissions. Do you want to continue?')) {
            // You can add AJAX call here to fetch default permissions for the selected role
            // For now, it will just reset the checkboxes
            document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
                checkbox.checked = false;
            });
        }
    });
</script>
@endsection
