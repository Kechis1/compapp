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
                        {{__('pages.images').' - '.__('pages.create')}}
                    </div>
                    <div class="card-body">
                        <form action="{{ action('ImageController@store') }}" method="post" class="needs-validation" novalidate enctype="multipart/form-data">
                            @csrf
                            {{ method_field('POST') }}
                            <section>
                                <div class="form-group">
                                    <div class="custom-file">
                                        <label class="custom-file-label" for="image">{{__('alerts.image')}}</label>
                                        <input type="file" name="iae_image" class="custom-file-input" id="image">
                                    </div>
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