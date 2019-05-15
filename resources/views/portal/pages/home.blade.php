@extends('portal.layout')

@section('content')
    <div id="main">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    @include('portal.includes.categories')
                </div>
                <div class="col-md-8">
                    <h1>{{ __('titles.try_shopping_guide') }}!</h1>
                    <button class="m-auto d-block btn btn-primary text-white" data-toggle="modal" data-target="#guide"  role="button">{{ __('buttons.guide_title') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection