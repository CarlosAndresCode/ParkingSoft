@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card bg-white shadow">
                <div class="card-header">Tarifas de Parqueo</div>

                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Tipo de Vehículo</th>
                                <th>Tarifa por Hora</th>
                                <th>Tarifa Mensual</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rates as $rate)
                                <tr>
                                    <td>{{ $rate->vehicle_type == 'car' ? 'Carro' : 'Moto' }}</td>
                                    <td>${{ number_format($rate->hourly_rate, 2) }}</td>
                                    <td>${{ number_format($rate->monthly_rate, 2) }}</td>
                                    <td>
                                        <a href="{{ route('rates.edit', $rate) }}" class="btn btn-sm btn-primary">{{ __('Edit') }}</a>
                                    </td>
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
