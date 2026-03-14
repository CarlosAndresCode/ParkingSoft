@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow bg-white">
                <div class="card-header">Edit Vehicle</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('vehicles.update', $vehicle) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="plate" class="form-label">Plate Number</label>
                            <input type="text" class="form-control @error('plate') is-invalid @enderror" id="plate" name="plate" value="{{ old('plate', $vehicle->plate) }}" required>
                            @error('plate') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="brand_id" class="form-label">Marca</label>
                            <select class="form-select" id="brand_id" name="brand_id">
                                <option value="">Seleccione una marca (Opcional)</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ $vehicle->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="model" class="form-label">Model/Brand</label>
                            <input type="text" class="form-control @error('model') is-invalid @enderror" id="model" name="model" value="{{ old('model', $vehicle->model) }}">
                            @error('model') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Vehicle Type</label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="car" {{ old('type', $vehicle->type) == 'car' ? 'selected' : '' }}>Car</option>
                                <option value="motorcycle" {{ old('type', $vehicle->type) == 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                            </select>
                            @error('type') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="owner_id" class="form-label">Owner (Optional)</label>
                            <select class="form-select @error('owner_id') is-invalid @enderror" id="owner_id" name="owner_id">
                                <option value="">None (Guest)</option>
                                @foreach ($owners as $owner)
                                    <option value="{{ $owner->id }}" {{ (string) old('owner_id', $vehicle->owner_id) === (string) $owner->id ? 'selected' : '' }}>{{ $owner->name }}</option>
                                @endforeach
                            </select>
                            @error('owner_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <x-button type="primary" text="{{ __('Save') }}"></x-button>
                        <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
