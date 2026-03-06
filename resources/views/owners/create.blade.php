@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow bg-white">
                <div class="card-header">Agregar Nuevo Dueño</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('owners.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                            @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Número de Teléfono</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                            @error('phone') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <x-button type="primary" text="{{ __('Save') }}"></x-button>
                        <a href="{{ route('owners.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
