@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow bg-white">
                <div class="card-header">{{ __($title) }}</div>

                <div class="card-body">
{{--                    {{ __('You are logged in!') }}--}}
                    <div class="row">
                        <div class="col-4">
                            <div class="card bg-warning-subtle shadow-sm">
                                <div class="d-flex justify-content-between m-2">
                                    <div class="fs-5">Ingresos del día:</div>
                                    <div class="fs-1">60</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card bg-success-subtle shadow-sm">
                                <div class="d-flex justify-content-between m-2">
                                    <div class="fs-4">Carros</div>
                                    <div class="fs-1">15</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card bg-primary-subtle shadow-sm">
                                <div class="d-flex justify-content-between m-2">
                                    <div class="fs-4">Motos</div>
                                    <div class="fs-1">45</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
