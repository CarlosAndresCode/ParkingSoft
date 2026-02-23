@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card bg-white shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Manage Subscriptions</span>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="card mb-4 bg-white">
                        <div class="card-header">Create New Subscription</div>
                        <div class="card-body">
                            <form action="{{ route('subscriptions.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-5">
                                        <label for="vehicle_id" class="form-label">Vehicle</label>
                                        <select class="form-select" id="vehicle_id" name="vehicle_id" required>
                                            <option value="">Select a vehicle (must have an owner)</option>
                                            @foreach ($vehicles as $vehicle)
                                                <option value="{{ $vehicle->id }}">{{ $vehicle->plate }} ({{ $vehicle->owner->name }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="months" class="form-label">Months</label>
                                        <input type="number" class="form-control" id="months" name="months" value="1" min="1" required>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100">Subscribe</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <table class="table table-sm">
                        <thead>
                            <tr>
{{--                                <th>Type</th>--}}
                                <th>Plate</th>
                                <th>Owner</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subscriptions as $subscription)
                                <tr>
{{--                                    <td>{{ $subscriptions->vehicle->type }}</td>--}}
                                    <td>{{ $subscription->vehicle->plate }}</td>
                                    <td>{{ $subscription->vehicle->owner->name }}</td>
                                    <td>{{ $subscription->start_date->format('Y-m-d') }}</td>
                                    <td>{{ $subscription->end_date->format('Y-m-d') }}</td>
                                    <td>${{ number_format($subscription->price, 2) }}</td>
                                    <td>
                                        <span class="badge {{ $subscription->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ ucfirst($subscription->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($subscription->status == 'active')
                                            <form action="{{ route('subscriptions.destroy', $subscription) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Cancel</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $subscriptions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
