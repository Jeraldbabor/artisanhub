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
    
    .customer-avatar {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 50%;
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">My Customers</h1>
        <div class="input-group" style="width: 300px;">
            <input type="text" class="form-control bg-light border-0 small" 
                   placeholder="Search customers..." 
                   id="customerSearchInput">
            <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
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

    @if($customers->isEmpty())
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="fas fa-users fa-3x text-gray-300 mb-3"></i>
                <h5 class="text-gray-600">You don't have any customers yet</h5>
                <p class="text-muted">Customers will appear here once they purchase your products</p>
            </div>
        </div>
    @else
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Customer List</h6>
                <div class="text-muted">
                    Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }} entries
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="customerTable" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="15%">Customer</th>
                                <th width="20%">Email</th>
                                <th width="15%">Total Orders</th>
                                <th width="20%">Last Order</th>
                                <th width="15%">Total Spent</th>
                                <th width="10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                            <tr>
                                <td class="text-center">{{ ($customers->currentPage() - 1) * $customers->perPage() + $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $customer->profile_picture ? asset('storage/' . $customer->profile_picture) : asset('assetsadmin/dist/img/user2-160x160.jpg') }}" 
                                             class="customer-avatar mr-3" 
                                             alt="{{ $customer->name }}">
                                        <div>
                                            <strong>{{ $customer->name }}</strong>
                                            @if($customer->created_at > now()->subDays(30))
                                                <span class="badge badge-success ml-2">New</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $customer->email }}</td>
                                <td class="text-center">
                                    <span class="badge badge-primary p-2">{{ $customer->orders_count }}</span>
                                </td>
                                <td>
                                    @if($customer->orders->isNotEmpty())
                                        {{ $customer->orders->first()->created_at->format('M d, Y') }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <span class="badge badge-success p-2">
                                        ₱{{ number_format($customer->orders->sum('total'), 2) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" 
                                                class="btn btn-sm btn-primary" 
                                                data-toggle="modal" 
                                                data-target="#customerModal{{ $customer->id }}"
                                                data-toggle="tooltip" 
                                                title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }} entries
                    </div>
                    <nav>
                        {{ $customers->links('pagination::bootstrap-4') }}
                    </nav>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Customer Details Modals -->
@foreach($customers as $customer)
<div class="modal fade" id="customerModal{{ $customer->id }}" tabindex="-1" role="dialog" aria-labelledby="customerModalLabel{{ $customer->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="customerModalLabel{{ $customer->id }}">
                    <i class="fas fa-user mr-2"></i>Customer Details - {{ $customer->name }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-4 text-center">
                        <img src="{{ $customer->profile_picture ? asset('storage/' . $customer->profile_picture) : asset('assetsadmin/dist/img/user2-160x160.jpg') }}" 
                             class="img-circle elevation-2 mb-3" 
                             alt="User Image"
                             style="width: 120px; height: 120px; object-fit: cover;">
                        <h4>{{ $customer->name }}</h4>
                        <p class="text-muted">{{ $customer->email }}</p>
                        <p>
                            <span class="badge badge-info">
                                {{ $customer->orders_count }} order(s)
                            </span>
                            <span class="badge badge-success ml-2">
                                ₱{{ number_format($customer->orders->sum('total'), 2) }} spent
                            </span>
                        </p>
                    </div>
                    <div class="col-md-8">
                        <h5 class="mb-3"><i class="fas fa-info-circle mr-2"></i>Customer Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Joined:</strong> {{ $customer->created_at->format('M d, Y') }}</p>
                                <p><strong>Last Order:</strong> 
                                    @if($customer->orders->isNotEmpty())
                                        {{ $customer->orders->first()->created_at->format('M d, Y') }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Phone:</strong> {{ $customer->phone ?? 'N/A' }}</p>
                                <p><strong>Address:</strong> {{ $customer->address ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <h5 class="mb-3"><i class="fas fa-shopping-cart mr-2"></i>Order History</h5>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Items</th>
                                <th class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customer->orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge badge-{{ 
                                        $order->status == 'completed' ? 'success' : 
                                        ($order->status == 'confirmed' ? 'primary' : 
                                        ($order->status == 'pending' ? 'warning' : 'danger')) 
                                    }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    @foreach($order->items as $item)
                                        @if($item->product->user_id == auth()->id())
                                            <span class="d-block">{{ $item->product->name }} (x{{ $item->quantity }})</span>
                                        @endif
                                    @endforeach
                                </td>
                                <td class="text-right">₱{{ number_format($order->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-right font-weight-bold">Total:</td>
                                <td class="text-right font-weight-bold">₱{{ number_format($customer->orders->sum('total'), 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-2"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();
        
        // Customer search functionality
        $('#customerSearchInput').on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            
            $('#customerTable tbody tr').each(function() {
                const rowText = $(this).text().toLowerCase();
                if (rowText.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
</script>
@endpush