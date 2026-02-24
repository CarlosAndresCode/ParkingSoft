@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card bg-white shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Vehicles</span>
                    <a href="{{ route('vehicles.create') }}" class="btn btn-sm btn-primary">Register New Vehicle</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="mb-3">
                        <form action="{{ route('vehicles.index') }}" method="GET">
                            <input type="text" name="search" class="form-control real-time-search" placeholder="Search by plate, model or owner..." value="{{ $search ?? '' }}">
                        </form>
                    </div>

                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Plate</th>
                                <th>Model</th>
                                <th>Type</th>
                                <th>Owner</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vehicles as $vehicle)
                                <tr>
                                    <td>{{ $vehicle->plate }}</td>
                                    <td>{{ $vehicle->model ?? 'N/A' }}</td>
                                    <td>{{ ucfirst($vehicle->type) }}</td>
                                    <td>{{ $vehicle->owner->name ?? 'None (Guest)' }}</td>
                                    <td>
                                        <a href="{{ route('vehicles.edit', $vehicle) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $vehicles->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
