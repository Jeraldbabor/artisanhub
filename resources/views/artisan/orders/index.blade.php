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
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Orders List</h1>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" id="success-alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    const alert = document.getElementById('success-alert');
                    if (alert) {
                        alert.classList.remove('show');
                        alert.classList.add('fade');
                    }
                }, 2000); 
            });
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

    @if($orders->isEmpty())
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="fas fa-box-open fa-3x text-gray-300 mb-3"></i>
                <h5 class="text-gray-600">No orders found</h5>
                <p class="text-muted">You currently have no orders.</p>
            </div>
        </div>
    @else
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Order List</h6>
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
                                <th width="15%">Order ID</th>
                                <th width="20%">Customer Name</th>
                                <th width="25%">Order Date</th>
                                <th width="10%">Total Amount</th>
                                <th width="15%">Status</th>
                                <th width="10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr id="order-row-{{ $order->id }}">
                                    <td class="text-center">{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                                    <td class="font-weight-bold">{{ $order->id }}</td>
                                    <td>{{ $order->first_name }}</td>
                                    <td>{{ $order->created_at ? $order->created_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                    <td class="text-right">
                                        <span class="badge badge-success p-2">
                                            ₱{{ number_format($order->total, 2) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if(strtolower($order->status) === 'completed')
                                            <span class="badge badge-primary p-2">Completed</span>
                                        @elseif(strtolower($order->status) === 'pending')
                                            <span class="badge badge-warning p-2">Pending</span>
                                        @elseif(strtolower($order->status) === 'confirmed')
                                            <span class="badge badge-success p-2">Confirmed</span>
                                        @else
                                            <span class="badge badge-danger p-2">Cancelled</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button type="button" 
                                                    class="btn btn-sm btn-info" 
                                                    data-toggle="modal" 
                                                    data-target="#viewOrderModal-{{ $order->id }}" 
                                                    title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <form action="{{ route('artisan.orders.destroy', $order->id) }}" method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm delete-btn">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-right font-weight-bold">Total Orders Amounts:</td>
                                <td class="text-right font-weight-bold">₱{{ number_format($orders->sum('total'), 2) }}</td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} entries
                    </div>
                    <nav>
                        {{ $orders->links('pagination::bootstrap-4') }}
                    </nav>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Modal -->
@foreach($orders as $order)
<div class="modal fade" id="viewOrderModal-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="viewOrderModalLabel-{{ $order->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewOrderModalLabel-{{ $order->id }}">
                    <i class="fas fa-receipt mr-2"></i>Order #{{ $order->id }} Details
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card border-left-primary h-100">
                            <div class="card-body">
                                <h6 class="card-title text-uppercase text-muted mb-3">Customer Information</h6>
                                <div class="row">
                                    <div class="col-5">
                                        <p class="mb-2"><strong>Name:</strong></p>
                                        <p class="mb-2"><strong>Email:</strong></p>
                                        <p class="mb-2"><strong>Phone:</strong></p>
                                    </div>
                                    <div class="col-7">
                                        <p class="mb-2">{{ $order->first_name }} {{ $order->last_name ?? '' }}</p>
                                        <p class="mb-2">{{ $order->email }}</p>
                                        <p class="mb-2">{{ $order->phone ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-left-success h-100">
                            <div class="card-body">
                                <h6 class="card-title text-uppercase text-muted mb-3">Order Summary</h6>
                                <div class="row">
                                    <div class="col-5">
                                        <p class="mb-2"><strong>Date:</strong></p>
                                        <p class="mb-2"><strong>Status:</strong></p>
                                        <p class="mb-2"><strong>Total:</strong></p>
                                    </div>
                                    <div class="col-7">
                                        <p class="mb-2">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                                        <p class="mb-2">
                                            @if(strtolower($order->status) === 'completed')
                                                <span class="badge badge-success">Completed</span>
                                            @elseif(strtolower($order->status) === 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif(strtolower($order->status) === 'confirmed')
                                                <span class="badge badge-primary">Confirmed</span>
                                            @else
                                                <span class="badge badge-danger">Cancelled</span>
                                            @endif
                                        </p>
                                        <p class="mb-2">₱{{ number_format($order->total, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add this new section for ordered products with categories -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h6 class="m-0 font-weight-bold text-primary">Ordered Products</h6>
                        <p class="text-muted mb-0">Total Items: {{ $order->items->where('product.user_id', auth()->id())->count() }}</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        @if($item->product->user_id == auth()->id())
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ asset('storage/images/' . $item->product->image) }}" 
                                                             alt="{{ $item->product->name }}" 
                                                             class="img-thumbnail rounded mr-3" 
                                                             style="width: 50px; height: 50px; object-fit: cover;">
                                                        <div>
                                                            <strong>{{ $item->product->name }}</strong><br>
                                                            <small class="text-muted">{{ Str::limit($item->product->description, 50) }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-info">
                                                        {{ $item->product->category->name ?? 'Uncategorized' }}
                                                    </span>
                                                </td>
                                                <td>₱{{ number_format($item->price, 2) }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>₱{{ number_format($item->price * $item->quantity, 2) }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-right"><strong>Total:</strong></td>
                                        <td><strong>₱{{ number_format($order->total, 2) }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Shipping Information Section -->
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="m-0 font-weight-bold text-primary">Shipping Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Shipping Address:</strong></p>
                                <p>
                                    {{ $order->address }}<br>
                                    {{ $order->city }}, {{ $order->state }}<br>
                                    {{ $order->country }} {{ $order->zipcode }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Payment Method:</strong></p>
                                <p>{{ ucfirst($order->payment_method) }}</p>
                                
                                @if($order->status === 'pending')
                                <div class="mt-3">
                                    <form action="{{ route('artisan.orders.update-status', $order) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="status-{{ $order->id }}"><strong>Update Status:</strong></label>
                                            <select class="form-control" name="status" id="status-{{ $order->id }}">
                                                <option value="confirmed">Confirm Order</option>
                                                <option value="cancelled">Cancel Order</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm mt-2">
                                            <i class="fas fa-save mr-1"></i> Update Status
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                @if($order->status === 'pending')
                <form action="{{ route('artisan.orders.update-status', $order) }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="status" value="confirmed">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check mr-1"></i> Confirm Order
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('scripts')
<script>
            document.addEventListener('DOMContentLoaded', function() {
                // SweetAlert for delete confirmation
                $(document).on('click', '.delete-btn', function(e) {
                    e.preventDefault();
                    let form = $(this).closest('form');
                    
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Submit the form after confirmation
                            form.submit();
                        }
                    });
                });

                // Show success message if exists
                @if(session('success'))
                    Swal.fire({
                        title: 'Success!',
                        text: '{{ session('success') }}',
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                @endif

                // Show error message if exists
                @if(session('error'))
                    Swal.fire({
                        title: 'Error!',
                        text: '{{ session('error') }}',
                        icon: 'error',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                @endif
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
</script>
@endpush
