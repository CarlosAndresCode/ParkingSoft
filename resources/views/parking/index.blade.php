@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card bg-white shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Parking Dashboard</span>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-white">
                                <div class="card-header">Check-In Vehicle</div>
                                <div class="card-body">
                                    <form action="{{ route('parking.check-in') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="plate" class="form-label">Plate Number</label>
                                            <input type="text" class="form-control" id="plate" name="plate" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="type" class="form-label">Vehicle Type</label>
                                            <select class="form-select" id="type" name="type" required>
                                                <option value="car">Car</option>
                                                <option value="motorcycle">Motorcycle</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-success w-100">Check-In</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h4>Active Sessions</h4>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Plate</th>
                                <th>Type</th>
                                <th>Owner</th>
                                <th>Entry Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($activeSessions as $session)
                                <tr>
                                    <td>{{ $session->vehicle->plate }}</td>
                                    <td>{{ ucfirst($session->vehicle->type) }}</td>
                                    <td>{{ $session->vehicle->owner->name ?? 'Guest' }}</td>
                                    <td>{{ $session->entry_time->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <form action="{{ route('parking.check-out', $session) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">Check-Out</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No active sessions</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <hr>

                    <h4>Recent Completed Sessions</h4>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Plate</th>
                                <th>Entry</th>
                                <th>Exit</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentSessions as $session)
                                <tr>
                                    <td>{{ $session->vehicle->plate }}</td>
                                    <td>{{ $session->entry_time->format('H:i') }}</td>
                                    <td>{{ $session->exit_time->format('H:i') }}</td>
                                    <td>${{ number_format($session->total_price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
