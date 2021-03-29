@extends('layouts.app')

@section('content')
<div class="container">
    <alerts></alerts>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Ingredients') }}</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <ingredients></ingredients>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
