@extends('layouts.buyertmp.index')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Notifications</h5>
                    <form method="POST" action="{{ route('notifications.clear-all') }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            Clear All
                        </button>
                    </form>
                </div>

                <div class="card-body">
                    @if($notifications->isEmpty())
                        <div class="alert alert-info">You have no notifications</div>
                    @else
                        <div class="list-group">
                            @foreach($notifications as $notification)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="flex-grow-1">
                                        <a href="{{ route('buyer.orders.show', (json_decode($notification->data)->order_id ?? '#')) }}" 
                                               class="text-decoration-none text-dark">
                                            <div class="d-flex w-100 justify-content-between">
                                                <p class="mb-1">{{ json_decode($notification->data)->message }}</p>
                                                <small>{{ $notification->created_at->diffForHumans() }}</small>
                                            </div>
                                            @if($notification->type === 'order_cancelled' && isset(json_decode($notification->data)->reason))
                                                <small class="text-muted">Reason: {{ json_decode($notification->data)->reason }}</small>
                                            @endif
                                        </a>
                                    </div>
                                    <form method="POST" action="{{ route('notifications.destroy', $notification) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-3">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection