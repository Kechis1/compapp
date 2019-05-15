@extends('layout')

@section('content')
    <div id="wrapper">
        @include('admin.includes.sidebar')
        <div id="content-wrapper">
            <div class="container-fluid">
                @include('breadcrumbs')
                @include('portal.includes.messages')
                <div class="card">
                    <div class="card-header">
                        {{__('pages.deliveries').' - '.__('pages.create')}}
                    </div>
                    <div class="card-body">
                        <form action="{{ action('DeliveryController@store') }}" method="post" class="needs-validation" novalidate>
                            @csrf
                            {{ method_field('POST') }}
                            <section>
                                <div class="form-group">
                                    <label for="dly_name">{{__('label.name')}}: <span class="text-danger">*</span></label>
                                    <input type="text" name="dly_name" class="form-control @error('dly_name') is-invalid @enderror" value="{{ old('dly_name') }}" id="dly_name" required>
                                </div>
                            </section>
                            <button type="submit" class="btn btn-primary">{{__('buttons.add')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection