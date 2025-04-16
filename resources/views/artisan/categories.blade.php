@extends('layouts.artisantmp.dashboard')

@section('content')
<style>
    /* Add the toast styling from the first code */
    .swal2-toast {
        font-size: 0.875rem !important;
        padding: 0.75rem 1rem !important;
    }

    .swal2-icon.swal2-success .swal2-success-ring {
        border-color: rgba(72, 187, 120, 0.3) !important;
    }

    .swal2-icon.swal2-success [class^=swal2-success-line] {
        background-color: #48bb78 !important;
    }
</style>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <script>
        setTimeout(function() {
            $('.alert-success').alert('close');
        }, 2000); // Closes the alert after 5 seconds
    </script>
@endif
<!-- Category Management Section -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">My Categories</h5>
        <button class="btn btn-sm btn-primary ml-auto" data-toggle="modal" data-target="#createCategoryModal">
            <i class="fas fa-plus"></i> Add Category
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th>Name</th>
                        <th>Products</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr id="category-row-{{ $category->id }}">
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->products ? $category->products->count() : 0 }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning edit-category" 
                                        data-id="{{ $category->id }}"
                                        data-name="{{ $category->name }}"
                                        data-toggle="modal" 
                                        data-target="#editCategoryModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline delete-category-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger delete-category-btn" data-id="{{ $category->id }}" data-name="{{ $category->name }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Pagination --> 
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $categories->count() > 0 ? 1 : 0 }} to {{ $categories->count() }} of {{ $categories->count() }} entries
                    </div>
                    <nav>
                        @if(method_exists($categories, 'links'))
                            {{ $categories->links('pagination::bootstrap-4') }}
                        @endif
                    </nav>
                </div>
        </div>
    </div>
</div>

<!-- Create Category Modal -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Category</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Category Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Category Name</label>
                        <input type="text" name="name" id="editCategoryName" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editCategoryModal = document.getElementById('editCategoryModal');
        const editCategoryForm = document.getElementById('editCategoryForm');
        const editCategoryName = document.getElementById('editCategoryName');

        document.querySelectorAll('.edit-category').forEach(button => {
            button.addEventListener('click', function () {
                const categoryId = this.getAttribute('data-id');
                const categoryName = this.getAttribute('data-name');

                editCategoryForm.action = `/categories/${categoryId}`;
                editCategoryName.value = categoryName;
            });
        });

        // Delete confirmation with SweetAlert
        document.querySelectorAll('.delete-category-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                let button = $(this);
                let form = button.closest("form");
                let categoryId = button.data('id');
                let categoryName = button.data('name');

                Swal.fire({
                    title: "Delete Category?",
                    html: `Are you sure you want to delete <strong>${categoryName}</strong>?<br>This action cannot be undone.`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "Cancel",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: form.attr('action'),
                            type: 'POST',
                            data: form.serialize(),
                            success: function(response) {
                                // Show toast notification
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    didOpen: (toast) => {
                                        toast.addEventListener('mouseenter', Swal.stopTimer)
                                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                                    }
                                });
                                
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Category deleted successfully'
                                });

                                // Remove the row
                                $("#category-row-" + categoryId).fadeOut("slow", function() {
                                    $(this).remove();
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    title: "Error!",
                                    text: "Failed to delete category. Please try again.",
                                    icon: "error",
                                    confirmButtonColor: "#d33"
                                });
                            }
                        });
                    }
                });
            });
        });
    });
</script>
@endpush
@endsection