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
                        {{__('pages.parameters').' - '.__('pages.create')}}
                    </div>
                    <div class="card-body">
                        <ul class="list-inline">
                            @foreach(\App\Models\Language::all() as $lang)
                                <li class="list-inline-item">
                                    @if($lang->lge_id == $lang_active)
                                        <a class="btn btn-primary" href="?lang={{$lang->lge_id}}">{{$lang->lge_abbreviation}}</a>
                                    @else
                                        <a class="btn btn-outline-primary" href="?lang={{$lang->lge_id}}">{{$lang->lge_abbreviation}}</a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>

                        <form action="{{ action('ParameterController@store') }}" method="post" class="needs-validation" novalidate>
                            @csrf
                            {{ method_field('POST') }}
                            <input type="hidden" name="lang" value="{{ $lang_active }}">
                            <section>
                                <div class="form-group">
                                    <label for="pls_name">{{__('label.name')}}: <span class="text-danger">*</span></label>
                                    <input type="text" name="pls_name" class="form-control @error('pls_name') is-invalid @enderror" value="{{ old('pls_name') }}" id="pls_name" required>
                                </div>
                                <div class="form-group">
                                    <label for="pls_unit">{{__('label.unit')}}:</label>
                                    <input type="text" name="pls_unit" class="form-control" value="{{ old('pls_unit') }}" id="pls_unit">
                                </div>
                                <div class="form-group">
                                    <label for="pvs_value">{{__('label.values')}}:</label>
                                    <textarea name="pvs_value" class="form-control" id="pvs_value">{{ old('pvs_value') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="prr_numeric" class="custom-control-input" value="1" id="check" checked>
                                        <label class="custom-control-label" for="check">{{__('label.numeric')}}</label>
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