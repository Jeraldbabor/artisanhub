@extends('layouts.artisantmp.dashboard')

@section('content')
<style>
    .description-truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 250px;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    .badge {
        font-size: 0.9em;
        min-width: 60px;
    }
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }
    .pagination {
        margin-bottom: 0;
    }
    .page-item.active .page-link {
        background-color: #4e73df;
        border-color: #4e73df;
    }
    .page-link {
        color: #4e73df;
    }

        /* Add this to your style section */
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

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">My Products</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProductModal">
            <i class="fas fa-plus-circle mr-2"></i>Add New Product
        </button>
    </div>
    
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
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($products->isEmpty())
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="fas fa-box-open fa-3x text-gray-300 mb-3"></i>
                <h5 class="text-gray-600">You haven't added any products yet</h5>
                <p class="text-muted">Click the "Add New Product" button to get started</p>
            </div>
        </div>
    @else
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Product List</h6>
                <div>
                    <form class="form-inline" id="searchForm">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" 
                                   placeholder="Search for..." aria-label="Search" 
                                   aria-describedby="basic-addon2" id="searchInput">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">No.</th>
                                <th width="15%">Image</th>
                                <th width="20%">Name</th>
                                <th width="20%">Category</th>
                                <th width="25%">Description</th>
                                <th width="10%">Price</th>
                                <th width="10%">Quantity</th>
                                <th width="15%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr id="product-row-{{ $product->id }}">
                                    <td class="text-center">{{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}</td>
                                    <td class="text-center">
                                        <img src="{{ asset('storage/images/' . $product->image) }}" 
                                             alt="{{ $product->name }}" 
                                             class="img-thumbnail rounded" 
                                             style="width: 80px; height: 80px; object-fit: cover;">
                                    </td>
                                    <td class="font-weight-bold">{{ $product->name }}</td>
                                    <td>{{ $product->category->name ?? 'Uncategorized' }}</td>
                                    <td>
                                        <div class="description-truncate" data-toggle="tooltip" 
                                             title="{{ $product->description }}">
                                            {{ Str::limit($product->description, 50) }}
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <span class="badge badge-success p-2">
                                            ₱{{ number_format($product->price, 2) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($product->quantity > 10)
                                            <span class="badge badge-primary p-2">{{ $product->quantity }}</span>
                                        @elseif($product->quantity > 0)
                                            <span class="badge badge-warning p-2">{{ $product->quantity }}</span>
                                        @else
                                            <span class="badge badge-danger p-2">Out of stock</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('products.show', $product) }}" 
                                               class="btn btn-sm btn-info" 
                                               data-toggle="tooltip" 
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <button type="button" 
                                            class="btn btn-sm btn-primary edit-btn" 
                                            data-id="{{ $product->id }}"
                                            data-name="{{ $product->name }}"
                                            data-description="{{ $product->description }}"
                                            data-price="{{ $product->price }}"
                                            data-quantity="{{ $product->quantity }}"
                                            data-category_id="{{ $product->category_id }}"
                                            data-image="{{ asset('storage/images/' . $product->image) }}"
                                            data-toggle="tooltip" 
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                            </button>
                                            
                                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $product->id }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                                <td></td>
                                <td colspan="4" class="text-right font-weight-bold">Total Products:</td>
                                <td class="text-right font-weight-bold">₱{{ number_format($products->sum('price'), 2) }}</td>
                                <td class="text-center font-weight-bold">{{ $products->sum('quantity') }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} entries
                    </div>
                    <nav>
                        {{ $products->links('pagination::bootstrap-4') }}
                    </nav>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Create Product Modal -->
<div class="modal fade" id="createProductModal" tabindex="-1" aria-labelledby="createProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createProductModalLabel">
                    <i class="fas fa-plus-circle mr-2"></i>Add New Product
                </h5>
                <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Product Name *</label>
                                <input type="text" class="form-control" name="name" id="name" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category *</label>
                                <select class="form-control" name="category_id" id="category_id" required>
                                    <option value="">Select Category</option>
                                    @forelse($categories ?? [] as $category)
                                        <option value="{{ $category->id }}" 
                                            @selected(old('category_id', $product->category_id ?? '') == $category->id)
                                        >
                                            {{ $category->name }}
                                        </option>
                                    @empty
                                        <option disabled>No categories found - create one first</option>
                                    @endforelse
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Description *</label>
                                <textarea class="form-control" name="description" id="description" rows="3" required></textarea>
                            </div>
                        </div>
                        
                        <!-- Rest of your form remains the same -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Price *</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" class="form-control" name="price" id="price" step="0.01" min="0" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity *</label>
                                <input type="number" class="form-control" name="quantity" id="quantity" min="0" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="image" class="form-label">Product Image *</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="image" id="image" required>
                                    <label class="custom-file-label" for="image">Choose file...</label>
                                </div>
                                <small class="form-text text-muted">
                                    Upload a clear image of your product (JPEG, PNG, max 2MB)
                                </small>
                                <div class="mt-2 text-center">
                                    <img id="imagePreview" src="#" alt="Preview" class="img-thumbnail d-none" style="max-height: 150px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editProductModalLabel">
                    <i class="fas fa-edit mr-2"></i>Edit Product
                </h5>
                <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editProductForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_name" class="form-label">Product Name *</label>
                                <input type="text" class="form-control" name="name" id="edit_name" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_category_id" class="form-label">Category *</label>
                                <select class="form-control" name="category_id" id="edit_category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="edit_description" class="form-label">Description *</label>
                                <textarea class="form-control" name="description" id="edit_description" rows="5" required></textarea>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_price" class="form-label">Price *</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" class="form-control" name="price" id="edit_price" step="0.01" min="0" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="edit_quantity" class="form-label">Quantity *</label>
                                <input type="number" class="form-control" name="quantity" id="edit_quantity" min="0" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="edit_image" class="form-label">Product Image</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="image" id="edit_image">
                                    <label class="custom-file-label" for="edit_image">Choose file...</label>
                                </div>
                                <small class="form-text text-muted">
                                    Upload a new image (JPEG, PNG, max 2MB)
                                </small>
                                <div class="mt-2 text-center">
                                    <img id="edit_imagePreview" src="#" alt="Preview" class="img-thumbnail" style="max-height: 150px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();
        
        // Image preview for upload
        $('#image').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').attr('src', e.target.result).removeClass('d-none');
                }
                reader.readAsDataURL(file);
                $('.custom-file-label').text(file.name);
            }
        });
        
        // Delete confirmation with SweetAlert
        $('.delete-btn').on('click', function (e) {
            e.preventDefault();
            let button = $(this);
            let form = button.closest("form");
            let productId = button.data('id');
            let productName = button.closest('tr').find('td:nth-child(3)').text().trim();

            Swal.fire({
                title: "Delete Product?",
                html: `Are you sure you want to delete <strong>${productName}</strong>?<br>This action cannot be undone.`,
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
                                title: 'Product deleted successfully'
                            });

                            // Remove the row
                            $("#product-row-" + productId).fadeOut("slow", function() {
                                $(this).remove();
                                // Update row numbers
                                $('tbody tr td:first-child').each(function(index) {
                                    $(this).text(index + 1);
                                });
                            });
                        },
                        error: function() {
                            Swal.fire({
                                title: "Error!",
                                text: "Failed to delete product. Please try again.",
                                icon: "error",
                                confirmButtonColor: "#d33"
                            });
                        }
                    });
                }
            });
        });

        // Clear form when modal is closed
        $('#createProductModal').on('hidden.bs.modal', function () {
            $(this).find('form')[0].reset();
            $('.custom-file-label').text('Choose file...');
            $('#imagePreview').addClass('d-none').attr('src', '#');
        });

        // Search functionality
        $('#searchForm').on('submit', function(e) {
            e.preventDefault();
            const searchTerm = $('#searchInput').val().toLowerCase();
            
            $('tbody tr').each(function() {
                const rowText = $(this).text().toLowerCase();
                if (rowText.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Edit product functionality
    $('.edit-btn').on('click', function() {
    let productId = $(this).data('id');
    let url = "{{ route('products.update', ':id') }}".replace(':id', productId);
    
    // Set the form action
    $('#editProductForm').attr('action', url);
    
    // Set form values from data attributes
    $('#edit_name').val($(this).data('name'));
    $('#edit_description').val($(this).data('description'));
    $('#edit_price').val($(this).data('price'));
    $('#edit_quantity').val($(this).data('quantity'));
    $('#edit_category_id').val($(this).data('category_id')); 
    
    // Set image preview
    $('#edit_imagePreview').attr('src', $(this).data('image'));
    
    // Show the modal
    $('#editProductModal').modal('show');
        });

        // Image preview for edit modal
        $('#edit_image').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#edit_imagePreview').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
                $('.custom-file-label', '#editProductModal').text(file.name);
            }
        });

        // Handle edit form submission
        $('#editProductForm').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            let formData = new FormData(this);
            
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#editProductModal').modal('hide');
                    Swal.fire({
                        title: 'Success!',
                        text: 'Product updated successfully',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload(); // Refresh to show updated product
                    });
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessages = [];
                    
                    for (let field in errors) {
                        errorMessages.push(errors[field][0]);
                    }
                    
                    Swal.fire({
                        title: 'Error!',
                        html: errorMessages.join('<br>'),
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // Clear edit form when modal is closed
        $('#editProductModal').on('hidden.bs.modal', function() {
            $(this).find('form')[0].reset();
            $('.custom-file-label', '#editProductModal').text('Choose file...');
        });
    });
</script>
@endpush