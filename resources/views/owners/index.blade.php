@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card bg-white shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Vehicle Owners</span>
                    <a href="{{ route('owners.create') }}" class="btn btn-sm btn-primary">Add New Owner</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="mb-3">
                        <form action="{{ route('owners.index') }}" method="GET">
                            <input type="text" name="search" class="form-control real-time-search" placeholder="Search by name, email or phone..." value="{{ $search ?? '' }}">
                        </form>
                    </div>

                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Vehicles</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($owners as $owner)
                                <tr>
                                    <td>{{ $owner->name }}</td>
                                    <td>{{ $owner->email }}</td>
                                    <td>{{ $owner->phone }}</td>
                                    <td>{{ $owner->vehicles_count }}</td>
                                    <td>
                                        <a href="{{ route('owners.show', $owner) }}" class="btn btn-sm btn-info text-white">View</a>
                                        <a href="{{ route('owners.edit', $owner) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('owners.destroy', $owner) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $owners->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
