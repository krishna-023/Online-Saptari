@extends('layouts.master')

@section('title')
    @lang('translation.profile')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Pages @endslot
        @slot('title') Profile @endslot
    @endcomponent

    <div class="row">
        <!-- Profile Information Card -->
        <div class="col-xl-3">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('default-avatar.png') }}" alt="Profile Picture" class="img-thumbnail rounded-circle mb-3" style="width: 150px; height: 150px;">
                    <h5>{{ Auth::user()->name }} <i class="bi bi-patch-check-fill align-baseline text-info ms-1"></i></h5>
                    <p class="text-muted">All about Saptari</p>
                </div>
            </div>
        </div>

        <!-- Information Card -->
        <div class="col-xl-9">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Information</h5>
                    <table class="table table-borderless table-sm align-middle mb-0">
                        <tbody>
                            <tr>
                                <th class="ps-0" scope="row">Phone No</th>
                                <td class="text-muted text-end"><a href="tel:+977 31-530780"> +977 31-530780</a></td>
                            </tr>
                            <tr>
                                <th class="ps-0" scope="row">Established In:</th>
                                <td class="text-muted text-end">01-Jan-2020</td>
                            </tr>
                            <tr>
                                <th class="ps-0" scope="row">Website</th>
                                <td class="text-muted text-end"><a href="https://businesswithtechnology.com/" target="_blank">www.businesswithtechnology.com</a></td>
                            </tr>
                            <tr>
                                <th class="ps-0" scope="row">Email</th>
                                <td class="text-muted text-end">{{ Auth::user()->email }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0" scope="row">Location</th>
                                <td class="text-muted text-end">Rajbiraj-8, Rupani Road, Saptari</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Social Media Card -->
        <div class="col-xl-3">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Social Media</h5>
                    <div class="d-flex flex-wrap justify-content-start gap-2">
                        <a href="javascript:void(0);" class="avatar-xs d-block">
                            <span class="avatar-title rounded-circle bg-secondary-subtle text-dark">
                                <i class="bi bi-github"></i>
                            </span>
                        </a>
                        <a href="https://www.facebook.com/businesswithtechnology" class="avatar-xs d-block">
                            <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                <i class="bi bi-facebook"></i>
                            </span>
                        </a>
                        <a href="javascript:void(0);" class="avatar-xs d-block">
                            <span class="avatar-title rounded-circle bg-success-subtle text-success">
                                <i class="bi bi-dribbble"></i>
                            </span>
                        </a>
                        <a href="javascript:void(0);" class="avatar-xs d-block">
                            <span class="avatar-title rounded-circle bg-danger-subtle text-danger">
                                <i class="bi bi-instagram"></i>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Settings Card -->
        <div class="col-xl-9">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Profile Settings</h5>
                    <form action="{{ route('profile.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="{{ Auth::user()->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Change Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank if you don't want to change">
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Leave blank if you don't want to change">
                        </div>
                        <div class="mb-3">
                            <label for="profile_picture" class="form-label">Profile Picture</label>
                            <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection To further enhance the layout and styling of your profile page, you can introduce additional cards for social media links and contact options, ensuring each card has a unique Bootstrap style. Below is an updated version of your Blade template that incorporates these changes:
