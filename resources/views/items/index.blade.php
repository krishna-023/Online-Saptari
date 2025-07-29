@extends('layouts.master')
@include('common.flash')
@section('title')
    @lang('translation.list-view')
@endsection

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <!--datatable css-->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <!--datatable responsive css-->
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <style>
        .dataTables_wrapper .dataTables_filter {
            display: none;
        }
    </style>
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Items
        @endslot
        @slot('title')
            List
        @endslot
    @endcomponent

    <div class="row">
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="col-lg-12">
            <div class="card" id="itemList">
                <div class="card-body">
                    <div class="row align-items-center g-2">
                        <div class="col-lg-3 me-auto">
                            <h6 class="card-title mb-0">Items List</h6>
                        </div>
                        <div class="col-lg-4">
                            <div class="search-box">
                                <input type="text" class="form-control search"
                                    placeholder="Search for items, price or something..." id="itemsearchbox">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>
                        <div class="col-lg-5 d-flex justify-content-end gap-2">
                            <!-- Add Button -->
                            @include('items.common.button')

                            <!-- Import Button -->
                            <form action="{{ route('import.items') }}" method="POST" enctype="multipart/form-data"
                                class="d-inline-flex align-items-center">
                                @csrf
                                <label for="file" class="btn btn-primary btn-sm me-2 mb-0 cursor-pointer">
                                    <i class="ri-upload-cloud-line"></i> Import
                                    <input type="file" name="file" id="file" class="d-none" required>
                                </label>
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="ri-check-line"></i> Submit
                                </button>
                            </form>
                        </div>

                    </div>

                </div>
                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table id="datatable"
                            class="table table-centered align-middle table-custom-effect table-nowrap mb-0">
                            <thead class="text-muted">
                                <tr>
                                    <th>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="checkAll">
                                            <label class="form-check-label" for="checkAll"></label>
                                        </div>
                                    </th>
                                    <th scope="col">ID</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Subtitle</th>
                                    <th scope="col">Telephone</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="checkAll"
                                                    value="{{ $item->id }}"
                                                    data-encrypted-id="{{ encrypt($item->id) }}">
                                                <label class="form-check-label"></label>
                                            </div>
                                        </td>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->category->Category_Name ?? 'N/A' }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td>{{ $item->subtitle }}</td>
                                        <td>
                                            @forelse($item->contacts as $contact)
                                                {{ $contact->telephone }}<br>
                                            @empty
                                                N/A
                                            @endforelse
                                        </td>
                                        <td>
                                            @forelse($item->contacts as $contact)
                                                {{ $contact->email }}<br>
                                            @empty
                                                N/A
                                            @endforelse
                                        </td>
                                        <td>
                                            <ul class="d-flex gap-2 list-unstyled mb-0">
                                                <li>
                                                    <a href="{{ route('item.view', encrypt($item->id)) }}"
                                                        class="btn btn-subtle-primary btn-icon btn-sm"><i
                                                            class="ph-eye"></i></a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('item.edit', $item->id) }}"
                                                        class="btn btn-subtle-secondary btn-icon btn-sm"><i
                                                            class="ph-pencil"></i></a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('item.destroy', encrypt($item->id)) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-subtle-danger btn-icon btn-sm remove-item-btn">
                                                            <i class="ph-trash"></i>
                                                        </button>
                                                    </form>

                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No items found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="noresult" style="display: none">
                            <div class="text-center py-4">
                                <i class="ph-magnifying-glass fs-1 text-primary"></i>
                                <h5 class="mt-2">Sorry! No Result Found</h5>
                                <p class="text-muted mb-0">Sorry! we have searched all but we haven't found anything
                                    related.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center mt-4 pt-2" id="pagination-element">
                        {{--                        <div class="col-sm"> --}}
                        {{--                            <div class="text-muted text-center text-sm-start"> --}}
                        {{--                                Showing <span class="fw-semibold">{{ $items->count() }}</span> of <span class="fw-semibold">{{ $items->total() }}</span> Results --}}
                        {{--                            </div> --}}
                        {{--                        </div> --}}
                        <div class="col-sm-auto mt-3 mt-sm-0">
                            {{ $items->links() }} <!-- Pagination links -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- deleteRecordModal -->
    <div id="deleteRecordModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" id="deleteRecord-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-md-5">
                    <div class="text-center">
                        <div class="text-danger">
                            <i class="bi bi-trash display-5"></i>
                        </div>
                        <div class="mt-4">
                            <h4 class="mb-2">Are you sure?</h4>
                            <p class="text-muted mx-3 mb-0">Are you sure you want to remove this record?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 pt-2 mb-2">
                        <button type="button" class="btn w-sm btn-light btn-hover"
                            data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn w-sm btn-danger btn-hover" id="delete-record">Yes, Delete
                            It!</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="courseFilters" aria-labelledby="courseFiltersLabel">
        <div class="offcanvas-header border-bottom">
            <h5 id="courseFiltersLabel" class="offcanvas-title">Filters</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form method="GET" action="{{ route('item.index') }}">
                <div class="mb-3">
                    <label for="filter-category" class="form-label">Category</label>
                    <select id="filter-category" name="category_id" class="form-select">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->Category_Name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- New Filter Fields -->
                <div class="mb-3">
                    <label for="filter-title" class="form-label">Title</label>
                    <input type="text" id="filter-title" name="title" class="form-control"
                        value="{{ request('title') }}">
                </div>
                <div class="mb-3">
                    <label for="filter-subtitle" class="form-label">Subtitle</label>
                    <input type="text" id="filter-subtitle" name="subtitle" class="form-control"
                        value="{{ request('subtitle') }}">
                </div>
                <div class="mb-3">
                    <label for="filter-featured" class="form-label">Featured</label>
                    <select id="filter-featured" name="item_featured" class="form-select">
                        <option value="">All</option>
                        <option value="1" {{ request('item_featured') == '1' ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ request('item_featured') == '0' ? 'selected' : '' }}>No</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="filter-date" class="form-label">Date</label>
                    <input type="date" id="filter-date" name="date" class="form-control"
                        value="{{ request('date') }}">
                </div>
                <div class="mb-3">
                    <label for="filter-author-username" class="form-label">Author Username</label>
                    <input type="text" id="filter-author-username" name="author_username" class="form-control"
                        value="{{ request('author_username') }}">
                </div>
                <div class="mb-3">
                    <label for="filter-author-email" class="form-label">Author Email</label>
                    <input type="email" id="filter-author-email" name="author_email" class="form-control"
                        value="{{ request('author_email') }}">
                </div>
                <div class="mb-3">
                    <label for="filter-author-first-name" class="form-label">Author First Name</label>
                    <input type="text" id="filter-author-first-name" name="author_first_name" class="form-control"
                        value="{{ request('author_first_name') }}">
                </div>
                <div class="mb-3">
                    <label for="filter-author-last-name" class="form-label">Author Last Name</label>
                    <input type="text" id="filter-author-last-name" name="author_last_name" class="form-control"
                        value="{{ request('author_last_name') }}">
                </div>
                <div class="mb-3">
                    <label for="filter-slug" class="form-label">Slug</label>
                    <input type="text" id="filter-slug" name="slug" class="form-control"
                        value="{{ request('slug') }}">
                </div>
                <div class="mb-3">
                    <label for="filter-parent" class="form-label">Parent</label>
                    <input type="text" id="filter-parent" name="parent" class="form-control"
                        value="{{ request('parent') }}">
                </div>
                <div class="mb-3">
                    <label for="filter-parent-slug" class="form-label">Parent Slug</label>
                    <input type="text" id="filter-parent-slug" name="parent_slug" class="form-control"
                        value="{{ request('parent_slug') }}">
                </div>
                <div class="mb-3">
                    <label for="filter-telephone" class="form-label">Telephone</label>
                    <input type="text" id="filter-telephone" name="telephone" class="form-control"
                        value="{{ request('telephone') }}">
                </div>
                <div class="mb-3">
                    <label for="filter-address" class="form-label">Address</label>
                    <input type="text" id="filter-address" name="address" class="form-control"
                        value="{{ request('address') }}">
                </div>
                <div class="mb-3">
                    <label for="filter-id" class="form-label">ID</label>
                    <input type="text" id="filter-id" name="id" class="form-control"
                        value="{{ request('id') }}">
                </div>

                <div class="mb-3 text-end">
                    <button type="submit" class="btn btn-primary">Apply</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <!-- apexcharts -->
    <script src="{{ URL::asset('build/libs/nouislider/nouislider.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/wnumb/wNumb.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ URL::asset('build/libs/list.js/list.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/list.pagination.js/list.pagination.min.js') }}"></script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!--datatable js-->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

    <script src="{{ URL::asset('build/js/pages/datatables.init.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let table = new DataTable('#datatable', );

            $('#itemsearchbox').keyup(function() {
                table.search($(this).val()).draw();
            })
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let table = $('#datatable').DataTable();

            function updateButtonVisibility() {
                let anyChecked = $('input[name="checkAll"]:checked').length > 0;
                $('#deleteSelectedBtn').toggle(anyChecked);
                $('#exportSelectedBtn').toggle(anyChecked);
            }

            updateButtonVisibility();

            $('#checkAll').click(function() {
                let isChecked = $(this).prop('checked');
                $('input[name="checkAll"]').prop('checked', isChecked);
                updateButtonVisibility();
            });

            $(document).on('change', 'input[name="checkAll"]', function() {
                updateButtonVisibility();
            });

            $('#deleteSelectedBtn').click(function() {
                let selectedIds = $('input[name="checkAll"]:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedIds.length > 0) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: `You are about to delete ${selectedIds.length} item(s).`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete them!',
                        cancelButtonText: 'No, cancel!',
                        reverseButtons: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '{{ route('item.deleteSelected') }}',
                                method: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    ids: selectedIds
                                },
                                success: function(response) {
                                    if (response.success) {
                                        Swal.fire('Deleted!', response.message,
                                            'success').then(() => {
                                            location
                                        .reload(); // Reload the page to show flash message
                                        });
                                    } else {
                                        Swal.fire('Error!', response.message, 'error');
                                    }
                                },
                                error: function(xhr) {
                                    Swal.fire('Error!',
                                        'There was an error deleting the items.',
                                        'error');
                                }
                            });
                        }
                    });
                } else {
                    Swal.fire('No items selected', 'Please select items to delete.', 'info');
                }
            });

            $('#exportSelectedBtn').click(function() {
                let selectedIds = $('input[name="checkAll"]:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedIds.length > 0) {
                    let url = '{{ route('item.export') }}';
                    let form = $('<form>', {
                        'method': 'POST',
                        'action': url,
                        'target': '_blank'
                    });

                    selectedIds.forEach(id => {
                        form.append($('<input>', {
                            'type': 'hidden',
                            'name': 'ids[]', // Ensure it sends an array
                            'value': id
                        }));
                    });

                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': '_token',
                        'value': '{{ csrf_token() }}'
                    }));

                    $('body').append(form);
                    form.submit();
                    form.remove();
                } else {
                    Swal.fire('No items selected', 'Please select items to export.', 'info');
                }
            });

        });
    </script>
@endsection
