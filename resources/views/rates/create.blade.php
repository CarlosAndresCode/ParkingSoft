@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-white shadow">
                <div class="card-header">Nueva Tarifa de Parqueo</div>

                <div class="card-body">
                    <form action="{{ route('rates.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="vehicle_type" class="form-label">Tipo de Vehículo</label>
                            <input type="text" class="form-control @error('vehicle_type') is-invalid @enderror" id="vehicle_type" name="vehicle_type" value="{{ old('vehicle_type') }}" required placeholder="Ej: Carro, Moto, Camioneta">
                            @error('vehicle_type')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="hourly_rate" class="form-label">Tarifa por Hora</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control @error('hourly_rate') is-invalid @enderror" id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate') }}" required>
                            </div>
                            @error('hourly_rate')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="monthly_rate" class="form-label">Tarifa Mensual</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control @error('monthly_rate') is-invalid @enderror" id="monthly_rate" name="monthly_rate" value="{{ old('monthly_rate') }}" required>
                            </div>
                            @error('monthly_rate')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-flex ">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cloud-check" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M10.354 6.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7 8.793l2.646-2.647a.5.5 0 0 1 .708 0"/>
                                    <path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383m.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z"/>
                                </svg>
                                Guardar Tarifa</button>
                            <a href="{{ route('rates.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
