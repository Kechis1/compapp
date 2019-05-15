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
                        {{__('pages.category').' - '.__('pages.create')}}
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

                        <form action="{{ action('CategoryController@store') }}" method="post" class="needs-validation" novalidate enctype="multipart/form-data">
                            @csrf
                            {{ method_field('POST') }}
                            <input type="hidden" name="lang" value="{{ $lang_active }}">
                            <section>
                                <div class="form-group">
                                    <label for="cle_name">{{__('label.name')}}: <span class="text-danger">*</span></label>
                                    <input type="text" name="cle_name" class="form-control @error('cle_name') is-invalid @enderror" value="{{ old('cle_name') }}" id="cle_name" required>
                                </div>
                                <div class="form-group">
                                    <label for="cle_description">{{__('inputs.description')}}:</label>
                                    <textarea name="cle_description" class="form-control @error('cle_description') is-invalid @enderror" id="cle_description">{{ old('cle_description') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="params">{{__('pages.parameters')}}</label>
                                    <select multiple class="form-control" name="params[]" id="params">
                                        @foreach(\App\Models\ParameterLanguage::where('lge_id', $lang_active)->orderBy('pls_name')->get() as $param)
                                            <option value="{{$param->prr_id}}">{{$param->pls_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="parent">{{__('inputs.parent')}}</label>
                                    <select class="form-control" name="cey_cey_id" id="parent">
                                        <option value="0">--</option>
                                        @foreach(\App\Models\CategoryLanguage::where('lge_id', $lang_active)->orderBy('cle_name')->get() as $cat)
                                            <option value="{{$cat->cey_id}}">{{$cat->cle_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>{{__('alerts.image')}}</label>
                                    <div class="custom-file">
                                        <label class="custom-file-label" for="logo">{{__('alerts.image')}}</label>
                                        <input type="file" name="iae_image" class="custom-file-input" id="logo">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="cle_active" class="custom-control-input" value="1" id="check" checked>
                                        <label class="custom-control-label" for="check">{{__('label.is_active')}}</label>
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