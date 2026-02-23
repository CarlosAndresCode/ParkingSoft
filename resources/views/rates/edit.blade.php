@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Rate for {{ ucfirst($rate->vehicle_type) }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('rates.update', $rate) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="hourly_rate" class="form-label">Hourly Rate</label>
                            <input type="number" step="0.01" class="form-control @error('hourly_rate') is-invalid @enderror" id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate', $rate->hourly_rate) }}" required>
                            @error('hourly_rate')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="monthly_rate" class="form-label">Monthly Rate</label>
                            <input type="number" step="0.01" class="form-control @error('monthly_rate') is-invalid @enderror" id="monthly_rate" name="monthly_rate" value="{{ old('monthly_rate', $rate->monthly_rate) }}" required>
                            @error('monthly_rate')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Update Rate</button>
                        <a href="{{ route('rates.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
