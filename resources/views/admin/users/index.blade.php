@extends('admin.layouts.master')

@section('title', 'Users')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title mb-0">Users List</h4>

            {{-- Bulk Action Section --}}
            <div class="d-flex align-items-center gap-2">
                <select id="bulkActionSelect" class="form-select form-select-sm w-auto">
                    <option value="">Bulk Action</option>
                    <option value="email">Send Email</option>
                    <option value="sms">Send SMS</option>
                </select>
                <button id="applyBulkAction" class="btn btn-sm btn-primary">
                    Apply
                </button>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            @if(Auth::user()->role === 'super-admin')
                <div class="card shadow border-0 rounded-3 mb-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class="fa fa-users me-2"></i> Add New User</h5>
                        <a href="{{ route('user.create') }}" class="btn btn-success btn-sm rounded-pill shadow-sm">
                            <i class="fa fa-user-plus me-2"></i> Add User
                        </a>
                    </div>
                </div>
            @endif

            <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th><input type="checkbox" id="selectAll" class="form-check-input"></th>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Permissions</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input user-checkbox"
                                       value="{{ $user->id }}"
                                       data-email="{{ $user->email }}"
                                       data-phone="{{ $user->phone ?? '' }}"
                                       data-name="{{ $user->name }}">
                            </td>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? 'N/A' }}</td>
                            <td><span class="badge bg-primary">{{ ucfirst($user->role) }}</span></td>
                            <td>
                                {{-- Permissions Accordion --}}
                                <div class="accordion accordion-flush" id="accordionPermissions{{ $user->id }}">
                                    @php
                                        $menuConfig = config('role_permissions.menu', []);
                                        $userPerms = $user->role === 'super-admin'
                                            ? ['all']
                                            : (is_array($user->permissions)
                                                ? $user->permissions
                                                : (json_decode($user->permissions ?? '[]', true) ?? []));
                                        $hasPerm = function($permission) use ($user, $userPerms) {
                                            if ($user->role === 'super-admin') return true;
                                            if (in_array('all', $userPerms)) return true;
                                            return in_array($permission, $userPerms);
                                        };
                                    @endphp
                                    @foreach($menuConfig as $menu)
                                        @php
                                            $menuPerm = $menu['permission'] ?? null;
                                            $children = $menu['children'] ?? [];
                                            $hasMenuPerm = $menuPerm && $hasPerm($menuPerm);
                                            $childHasPerm = collect($children)->filter(function($child) use($hasPerm) {
                                                return isset($child['permission']) && $hasPerm($child['permission']);
                                            })->count() > 0;
                                        @endphp
                                        @if($hasMenuPerm || $childHasPerm)
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="heading{{ $user->id }}{{ Str::slug($menu['title']) }}">
                                                    <button class="accordion-button collapsed p-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $user->id }}{{ Str::slug($menu['title']) }}" aria-expanded="false" aria-controls="collapse{{ $user->id }}{{ Str::slug($menu['title']) }}">
                                                        {{ $menu['title'] }}
                                                    </button>
                                                </h2>
                                                <div id="collapse{{ $user->id }}{{ Str::slug($menu['title']) }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $user->id }}{{ Str::slug($menu['title']) }}" data-bs-parent="#accordionPermissions{{ $user->id }}">
                                                    <div class="accordion-body py-2">
                                                        @if($hasMenuPerm)
                                                            <span class="badge bg-success me-1 mb-1">{{ $menu['title'] }}</span>
                                                        @endif
                                                        @foreach($children as $child)
                                                            @php
                                                                $childPerm = $child['permission'] ?? null;
                                                                $hasChildPerm = $childPerm && $hasPerm($childPerm);
                                                            @endphp
                                                            @if($hasChildPerm)
                                                                <span class="badge bg-info me-1 mb-1">{{ $child['title'] }}</span>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                @if(Auth::user()->role === 'super-admin' || (Auth::user()->role === 'admin' && $user->role !== 'super-admin'))
                                    <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    @if($user->id !== Auth::id())
                                        <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    @endif
                                @else
                                    <span class="text-muted">No actions</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center">No users found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

{{-- Bulk Message Modal --}}
<div class="modal fade" id="bulkActionModal" tabindex="-1" aria-labelledby="bulkActionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="bulkActionModalLabel"><i class="fa fa-paper-plane me-2"></i> Send Bulk Message</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="bulkActionForm" method="POST" action="{{ route('user.bulkAction') }}">
        @csrf
        <input type="hidden" name="action_type" id="actionType">
        <input type="hidden" name="user_ids" id="selectedUserIds">

        <div class="modal-body">
          <div class="alert alert-info mb-3">
            <strong>Recipients:</strong>
            <ul id="selectedUserList" class="small mb-0"></ul>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Subject (for Email)</label>
            <input type="text" class="form-control" name="subject" placeholder="Enter subject (optional)">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Message</label>
            <textarea name="message" class="form-control" rows="4" placeholder="Type your message..." required></textarea>
          </div>
        </div>

        <div class="modal-footer border-0">
          <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Send</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection


@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Accordion collapse fix
    document.querySelectorAll('.accordion').forEach(acc => {
        acc.addEventListener('show.bs.collapse', function() {
            acc.querySelectorAll('.accordion-collapse.show').forEach(c => {
                new bootstrap.Collapse(c, { toggle: false }).hide();
            });
        });
    });

    // Select all checkboxes
    document.getElementById('selectAll').addEventListener('change', function() {
        const checked = this.checked;
        document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = checked);
    });

    // Bulk action handler
    document.getElementById('applyBulkAction').addEventListener('click', function() {
        const action = document.getElementById('bulkActionSelect').value;
        const selected = Array.from(document.querySelectorAll('.user-checkbox:checked'));
        if (!action) return alert('Please select an action first.');
        if (selected.length === 0) return alert('Please select at least one user.');

        const ids = selected.map(cb => cb.value);
        const list = selected.map(cb => `<li>${cb.dataset.name} (${cb.dataset.email})</li>`).join('');

        document.getElementById('selectedUserIds').value = ids.join(',');
        document.getElementById('selectedUserList').innerHTML = list;
        document.getElementById('actionType').value = action;

        new bootstrap.Modal(document.getElementById('bulkActionModal')).show();
    });
});
</script>
@endsection
