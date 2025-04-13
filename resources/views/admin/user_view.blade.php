@extends('layouts.admintmp.dashboard')

@section('content')
<div class="container">
    <h1>User Details</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><strong>Name:</strong>{{ $user->name }}</h5>
            <p class="card-text"><strong>Email:</strong> {{ $user->email }}</p>
            <p class="card-text"><strong>Role:</strong> {{ $user->role }}</p>
            <p class="card-text"><strong>Created At:</strong> {{ $user->created_at->format('Y-m-d H:i:s') }}</p>
            <p class="card-text"><strong>Updated At:</strong> {{ $user->updated_at->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>

    <a href="{{ route('admin.userlist') }}" class="btn btn-primary mt-3">Back to User List</a>
</div>
@endsection