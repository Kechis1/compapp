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
                        {{__('pages.products').' - '.__('pages.update')}}
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

                        <form action="{{ action('ProductController@update', $product) }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
                            @csrf
                            {{ method_field('PUT') }}
                            <input type="hidden" name="lang" value="{{ $lang_active }}">
                            @php
                                $pleLang = $product->languages()->where('product_languages.lge_id', $lang_active)->first();
                            @endphp
                            <section>
                                <div class="form-group">
                                    <label for="put_ean">EAN:</label>
                                    <input type="text" name="put_ean" class="form-control @error('put_ean') is-invalid @enderror" value="{{ $product->put_ean }}" id="put_ean">
                                </div>
                                <div class="form-group">
                                    <label for="ple_name">{{__('label.name')}}: <span class="text-danger">*</span></label>
                                    <input type="text" name="ple_name" class="form-control @error('ple_name') is-invalid @enderror" value="@if($pleLang!==null && $pleLang->pivot !== null){{ $pleLang->pivot->ple_name }}@endif" id="ple_name" required>
                                </div>
                                <div class="form-group">
                                    <label for="mur_id">{{__('label.manufacturer')}} <span class="text-danger">*</span></label>
                                    <select class="form-control" name="mur_id" id="mur_id">
                                        @foreach(\App\Models\Manufacturer::orderBy('mur_name')->get() as $mur)
                                            <option value="{{$mur->mur_id}}" @if($mur->mur_id == $product->mur_id) selected @endif>{{$mur->mur_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="params">{{__('pages.parameters')}}</label>
                                    <select style="min-height: 300px" multiple class="form-control" name="params[]" id="params">
                                        @if($params->count() > 0)
                                            @foreach($params as $param)
                                                @php
                                                    $pveLangs = \App\Models\ParameterValueLanguage::where('lge_id', $lang_active)->whereIn('pve_id', array_column(\App\Models\ParameterValue::where('prr_id', $param->prr_id)->get()->toArray(), 'pve_id'))->orderBy('pvs_value')->get();
                                                    $productParamsActive = array_column(\App\Models\ProductParameter::where('put_id', $product->put_id)->get()->toArray(), 'pve_id');
                                                @endphp
                                                @if($pveLangs->count() > 0)
                                                    <optgroup label="{{$param->pls_name}}">
                                                        @foreach($pveLangs as $pveLang)
                                                            <option value="{{$pveLang->pve_id}}" @if(in_array($pveLang->pve_id, $productParamsActive)) selected @endif>{{$pveLang->pvs_value}}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="ple_desc_short">{{__('inputs.description_short')}}:</label>
                                    <textarea name="ple_desc_short" class="form-control" id="ple_desc_short">@if($pleLang!==null && $pleLang->pivot !== null){{ $pleLang->pivot->ple_desc_short }}@endif</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="ple_desc_long">{{__('inputs.description')}}:</label>
                                    <textarea name="ple_desc_long" class="form-control" id="ple_desc_long">@if($pleLang!==null && $pleLang->pivot !== null){{ $pleLang->pivot->ple_desc_long }}@endif</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="image">{{__('pages.images')}}:</label>
                                    <div class="custom-file">
                                        <label class="custom-file-label" for="image">{{__('pages.images')}}</label>
                                        <input multiple type="file" name="iae_image[]" class="custom-file-input" id="image">
                                    </div>

                                    @if($product->images()->count() > 0)
                                        <div class="form-group">
                                            @foreach($product->images()->get() as $image)
                                                <img alt="" class="form-image" src="{{ asset('storage/'.$image->iae_path.'.'.$image->iae_type) }}">
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="cats">{{__('pages.category')}}</label>
                                    <select style="min-height: 300px" multiple class="form-control" name="cats[]" id="cats">
                                        @if($cats->count() > 0)
                                            @foreach($cats as $cat)
                                                @php
                                                    $productCategoryActive = array_column(\App\Models\ProductCategory::where('put_id', $product->put_id)->get()->toArray(), 'cey_id');
                                                @endphp
                                                <option value="{{$cat->cey_id}}" @if(in_array($cat->cey_id, $productCategoryActive)) selected @endif>{{$cat->cle_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="ple_active" class="custom-control-input" value="1" id="ple_active" @if($pleLang!==null && $pleLang->pivot !== null && $pleLang->pivot->ple_active) checked @endif>
                                        <label class="custom-control-label" for="ple_active">{{__('label.active')}}</label>
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