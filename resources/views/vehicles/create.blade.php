@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow bg-white">
                <div class="card-header">Registrar Nuevo Vehículo</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('vehicles.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="plate" class="form-label">Número de Placa</label>
                            <input type="text" class="form-control @error('plate') is-invalid @enderror" id="plate" name="plate" value="{{ old('plate') }}" required>
                            @error('plate') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="model" class="form-label">Modelo/Marca</label>
                            <input type="text" class="form-control @error('model') is-invalid @enderror" id="model" name="model" value="{{ old('model') }}">
                            @error('model') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Tipo de Vehículo</label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="car" {{ old('type') == 'car' ? 'selected' : '' }}>Carro</option>
                                <option value="motorcycle" {{ old('type') == 'motorcycle' ? 'selected' : '' }}>Motocicleta</option>
                            </select>
                            @error('type') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="owner_id" class="form-label">Dueño (Opcional)</label>
                            <select class="form-select @error('owner_id') is-invalid @enderror" id="owner_id" name="owner_id">
                                <option value="">Ninguno (Invitado)</option>
                                @foreach ($owners as $owner)
                                    <option value="{{ $owner->id }}" {{ old('owner_id') == $owner->id ? 'selected' : '' }}>{{ $owner->name }}</option>
                                @endforeach
                            </select>
                            @error('owner_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <x-button type="primary" text="{{ __('Save') }}"></x-button>
                        <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">{{ __('Cancel') }} </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
