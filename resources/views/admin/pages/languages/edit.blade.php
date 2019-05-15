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
                        {{__('pages.languages').' - '.__('pages.update')}}
                    </div>
                    <div class="card-body">
                        <form action="{{ action('LanguageController@update', $lang) }}" method="post" class="needs-validation" novalidate>
                            @csrf
                            {{ method_field('PUT') }}
                            <section>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="lge_abbreviation">{{__('label.abbreviation')}}: <span class="text-danger">*</span></label>
                                        <input type="text" name="lge_abbreviation" class="form-control @error('lge_abbreviation') is-invalid @enderror" value="{{ $lang->lge_abbreviation }}" id="lge_abbreviation" required autofocus>
                                    </div>
                                    <div class="form-group col">
                                        <label for="lge_name">{{__('label.name')}}: <span class="text-danger">*</span></label>
                                        <input type="text" name="lge_name" class="form-control @error('lge_name') is-invalid @enderror" value="{{ $lang->lge_name }}" id="lge_name" required>
                                    </div>
                                </div>
                            </section>
                            <button type="submit" class="btn btn-primary">{{__('buttons.update')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection