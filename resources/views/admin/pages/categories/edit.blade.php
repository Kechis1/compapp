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
                        {{__('pages.category').' - '.__('pages.update')}}
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

                        <form action="{{ action('CategoryController@update', $cey) }}" method="post" class="needs-validation" novalidate enctype="multipart/form-data">
                            @csrf
                            {{ method_field('PUT') }}
                            <input type="hidden" name="lang" value="{{ $lang_active }}">
                            @php
                                $ceyLang = $cey->languages()->where('category_languages.lge_id', $lang_active)->first();
                            @endphp
                            <section>
                                <div class="form-group">
                                    <label for="cle_name">{{__('label.name')}}: <span class="text-danger">*</span></label>
                                    <input type="text" name="cle_name" class="form-control @error('cle_name') is-invalid @enderror" value="@if($ceyLang !== null){{ $ceyLang->pivot->cle_name }}@endif" id="cle_name" required>
                                </div>
                                <div class="form-group">
                                    <label for="cle_description">{{__('inputs.description')}}:</label>
                                    <textarea name="cle_description" class="form-control @error('cle_description') is-invalid @enderror" id="cle_description">@if($ceyLang !== null){{ $ceyLang->pivot->cle_description }}@endif</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="params">{{__('pages.parameters')}}</label>
                                    <select multiple class="form-control" name="params[]" id="params">
                                        @if(\App\Models\CategoryParameter::where('cey_id', $cey->cey_id)->get()->count() > 0)
                                            @foreach(\App\Models\CategoryParameter::where('cey_id', $cey->cey_id)->get() as $param)
                                                @php
                                                    $paramPivot = $param->parameter()->first()->languages()->where('parameter_languages.lge_id', $lang_active)->first();
                                                @endphp
                                                @if($paramPivot !== null)<option value="{{$param->prr_id}}" selected>{{$paramPivot->pivot->pls_name}}</option>@endif
                                            @endforeach
                                        @endif
                                        @foreach(\App\Models\ParameterLanguage::whereNotIn('prr_id', array_column(\App\Models\CategoryParameter::where('cey_id', $cey->cey_id)->get()->toArray(), 'prr_id'))->where('lge_id', $lang_active)->orderBy('pls_name')->get() as $param)
                                            <option value="{{$param->prr_id}}">{{$param->pls_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="parent">{{__('inputs.parent')}}</label>
                                    <select class="form-control" name="cey_cey_id" id="parent">
                                        @if($cey->cey_cey_id !== null)
                                            @php
                                                $parent = \App\Models\CategoryLanguage::where([['lge_id',$lang_active],['cey_id', $cey->cey_cey_id]])->first();
                                            @endphp
                                            <option value="{{$cey->cey_cey_id}}" selected>
                                                @if($parent === null)
                                                    {{__('label.attribute_not_set')}}
                                                @else
                                                    {{$parent->cle_name}}
                                                @endif
                                            </option>
                                        @endif
                                        <option value="0">--</option>
                                        @foreach(\App\Models\CategoryLanguage::where('lge_id', $lang_active)->orderBy('cle_name')->get() as $cat)
                                            @if($cey->cey_cey_id != $cat->cey_id)
                                                <option value="{{$cat->cey_id}}">{{$cat->cle_name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>{{__('alerts.image')}}</label>
                                    <div class="custom-file">
                                        <label class="custom-file-label" for="logo">{{__('alerts.image')}}</label>
                                        <input type="file" name="iae_image" class="custom-file-input" id="logo">
                                    </div>
                                    @if($cey->image()->first() !== null)
                                        <img class="form-image" alt="" src="{{ asset('storage/'.$cey->image()->first()->iae_path.'.'.$cey->image()->first()->iae_type) }}">
                                    @else
                                        <i class="fas text-secondary fa-2x fa-image"></i>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="cle_active" class="custom-control-input" value="1" id="check"@if($ceyLang !== null && $ceyLang->pivot->cle_active) checked @endif>
                                        <label class="custom-control-label" for="check">{{__('label.is_active')}}</label>
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